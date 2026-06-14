# Rencana Rilis & Deployment (Deployment Plan)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** DevOps / Senior Developer  

---

## 1. Pendahuluan
Dokumen ini merangkum strategi rilis aplikasi ke lingkungan produksi (Production Environment) pada peladen VPS (Virtual Private Server). Karena budget yang ketat (Rp 5 juta), proses deployment akan dilakukan secara semi-otomatis menggunakan script *bash* (Git Pull hook) tanpa menggunakan tools CI/CD berbayar (seperti Jenkins/CircleCI) demi menjaga arsitektur tetap efisien.

## 2. Spesifikasi Infrastruktur Production
- **Provider:** Provider VPS Indonesia (misal: IDCloudHost / NusaCloud)
- **Spesifikasi VPS:** 2 vCPU, 2GB RAM, 40GB NVMe SSD, Ubuntu 22.04 LTS (Biaya ± Rp 150.000 - 300.000 / bulan).
- **Web Server:** Nginx.
- **Database:** PostgreSQL 15.
- **Runtime:** PHP 8.2 (FPM), Node.js v20 (hanya untuk build frontend saat pertama, bisa juga build di lokal lalu unggah *dist* file).
- **Network:** Cloudflare (DNS, WAF, SSL Strict).

## 3. Persiapan Sebelum Rilis (Pre-Deployment)
1. **Domain & DNS:** Domain (misal `simt-mts.sch.id`) telah terdaftar dan Name Server (NS) diarahkan ke Cloudflare.
2. **Environment Variables:** Menyiapkan file `.env` produksi yang berisi kredensial Database, APP_KEY (digenerate ulang), Mail/WhatsApp API Key, serta URL yang benar.
3. **Database Setup:** 
   - Membuat User Role `simt_user` dan Database `simt_db` di PostgreSQL server produksi.
   - Mengatur limit memori dan koneksi di `postgresql.conf` (disesuaikan dengan RAM 2GB).
4. **Code Freeze:** Source code di *branch* `main` di GitHub telah dikunci dan lolos proses UAT.

---

## 4. Langkah-Langkah Deployment (Deployment Steps)

*Deployment ini menggunakan pendekatan Monolithic: Frontend Vue.js akan di-build dan file statisnya diletakkan berdampingan atau disajikan oleh Nginx di VPS yang sama dengan Backend Laravel.*

### Langkah 1: Setup Server & Dependencies
*(Hanya dilakukan pada rilis pertama)*
```bash
# Update OS
sudo apt update && sudo apt upgrade -y

# Instal Nginx, PostgreSQL, PHP 8.2 FPM, Composer, Git, Unzip
sudo apt install nginx postgresql postgresql-contrib php8.2-fpm php8.2-pgsql php8.2-cli php8.2-xml php8.2-curl php8.2-zip composer git unzip -y
```

### Langkah 2: Setup Direktori Aplikasi
```bash
sudo mkdir -p /var/www/simt-backend
sudo mkdir -p /var/www/simt-frontend
sudo chown -R $USER:$USER /var/www/
```

### Langkah 3: Deploy Backend (Laravel)
```bash
cd /var/www/simt-backend
git clone -b main https://github.com/YourOrg/simt-backend.git .
composer install --optimize-autoloader --no-dev
cp .env.example .env
# Edit .env dengan credentials DB produksi
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan db:seed --class=RoleAndPermissionSeeder --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
# Set permission untuk storage dan bootstrap cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Langkah 4: Deploy Frontend (Vue.js)
Karena kita meminimalisir beban server (RAM hanya 2GB), *build process* frontend direkomendasikan dilakukan di mesin lokal developer atau via GitHub Actions.
1. Di Lokal: Jalankan `npm run build`.
2. Transfer folder `dist` (hasil build) ke VPS (`/var/www/simt-frontend`) via `rsync` atau `scp`.
```bash
rsync -avzP ./dist/ user@vps_ip:/var/www/simt-frontend/
```

### Langkah 5: Konfigurasi Nginx
Membuat dua Server Blocks (Virtual Hosts).
1. `api.simt-mts.sch.id` mengarah ke `/var/www/simt-backend/public`.
2. `app.simt-mts.sch.id` mengarah ke `/var/www/simt-frontend` (mengembalikan `index.html` untuk semua rute karena SPA Vue Router).
- *Restart Nginx:* `sudo systemctl restart nginx`

### Langkah 6: Konfigurasi SSL (Cloudflare)
1. Set SSL mode di Cloudflare ke "Full (Strict)".
2. Instal Origin Certificate dari Cloudflare di VPS Nginx untuk komunikasi HTTPS *end-to-end* yang aman.

---

## 5. Rencana Pasca-Rilis (Post-Deployment Sanity Check)
Setelah aplikasi *live*:
1. **Akses URL Frontend:** Pastikan UI Vue.js termuat dengan baik, tanpa ada 404 dari file CSS/JS.
2. **Coba Login:** Gunakan akun Super Admin untuk login. Pastikan token Sanctum berhasil di-set dalam cookie/storage dan navigasi bekerja.
3. **Cek Koneksi Database:** Coba tambahkan 1 data guru dummy (CRUD test).
4. **Cek Permissions Storage:** Upload foto profil. Pastikan gambar bisa diakses dan tersimpan.

---

## 6. Rencana Pembatalan (Rollback Plan)

Jika pada saat rilis (atau pembaruan aplikasi) terjadi *Fatal Error* atau sistem tidak berfungsi, langkah pengembalian harus dijalankan dalam waktu < 15 menit (Maximum Tolerable Downtime).

### Rollback Skenario 1: Bug di Source Code (Logical Error)
1. Akses folder backend: `cd /var/www/simt-backend`.
2. Pindah ke *commit* sebelumnya yang stabil: `git checkout HEAD^1` (atau spesifik hash `git checkout a1b2c3d`).
3. Bersihkan cache: `php artisan optimize:clear`.

### Rollback Skenario 2: Database Migration Gagal / Merusak Data
1. Jika migrasi gagal, lakukan `php artisan migrate:rollback --step=1`.
2. Jika data terkorupsi secara sistemik, kembalikan database menggunakan file `.sql` *dump* yang diambil *tepat sebelum* proses rilis dimulai.
   ```bash
   dropdb simt_db
   createdb simt_db
   psql -U simt_user -d simt_db < /backup/simt_db_pre_release.sql
   ```

### Rollback Skenario 3: Frontend Crash
1. Masuk ke direktori frontend: `cd /var/www/simt-frontend`.
2. Hapus isi folder dan timpa dengan file ZIP dari versi rilis sebelumnya. (Wajib menyimpan *backup/snapshot* file `dist.zip` dari setiap versi sebelumnya di VPS).

---
*Dengan panduan ini, siklus deployment SIMT MTs dapat berjalan secara deterministik dan minim gangguan terhadap operasional harian sekolah.*