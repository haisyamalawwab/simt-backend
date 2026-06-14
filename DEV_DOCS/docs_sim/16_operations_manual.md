# Panduan Operasional Sistem (Operations Manual)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** IT Administrator / DevOps  

---

## 1. Pendahuluan
Dokumen *Operations Manual* ini berfungsi sebagai panduan harian, mingguan, dan bulanan bagi Administrator IT sekolah (atau vendor pihak ketiga yang ditunjuk) untuk mengelola, memelihara, dan memastikan sistem SIMT MTs (SaaS MVP) berjalan dengan optimal. Panduan ini mencakup rutinitas *maintenance*, manajemen layanan, serta penyelesaian masalah (troubleshooting) dasar.

## 2. Pemeliharaan Rutin (Routine Maintenance)

Untuk menjaga performa aplikasi yang berjalan di atas VPS berkapasitas terbatas (2GB RAM), rutinitas pemeliharaan sangat krusial.

### 2.1. Cek Harian (Daily Checks)
- **Monitoring Uptime:** Gunakan layanan gratis seperti *UptimeRobot* (mem-ping server setiap 5 menit). Jika mendapat alert *Down*, segera cek status server.
- **Log Error:** Periksa file `storage/logs/laravel.log`. Cari pesan error (`ERROR` atau `CRITICAL`) yang sering muncul dan catat sebagai *Technical Debt*.

### 2.2. Cek Mingguan (Weekly Checks)
- **Kapasitas Disk (Disk Usage):** 
  - Jalankan perintah: `df -h`.
  - Pastikan kapasitas disk utama (`/`) tidak melebihi **80%**. Jika penuh, aplikasi dan database bisa lumpuh total.
  - Hapus log Nginx yang terlalu lama jika logrotate gagal berfungsi.
- **Penggunaan Memori & CPU:**
  - Jalankan `htop` pada jam sibuk (07:00 - 10:00 WIB saat input presensi).
  - Pastikan RAM tidak menyentuh 95% (masuk ke swap yang akan membuat server sangat lambat).

### 2.3. Cek Bulanan & Semesteran (Monthly & Semestrial Checks)
- **OS Updates:** Jalankan `sudo apt update && sudo apt upgrade` sebulan sekali pada jam malam untuk menambal celah keamanan Linux. (Hindari meng-upgrade major version PHP/Postgres tanpa testing di Staging).
- **Archiving Data (Semesteran):** Saat pergantian semester/tahun ajaran baru, lakukan backup full *Database*. 

---

## 3. Manajemen Layanan (Service Management)

Berikut adalah perintah-perintah *Linux* yang wajib dikuasai untuk merestart komponen sistem.

**1. Web Server & Reverse Proxy (Nginx)**
```bash
# Cek status
sudo systemctl status nginx
# Cek apakah konfigurasi Nginx tidak ada error/typo
sudo nginx -t
# Restart setelah mengubah konfigurasi block
sudo systemctl restart nginx
```

**2. PHP-FPM (Laravel Backend)**
```bash
# Restart proses PHP jika ada perubahan file .env atau kode yang tidak termuat
sudo systemctl restart php8.2-fpm
```

**3. Database (PostgreSQL)**
```bash
# Cek status database
sudo systemctl status postgresql
# Jika koneksi ditolak (connection refused) atau server restart paksa
sudo systemctl restart postgresql
```

**4. Cache & Route Laravel (Optimisasi)**
Setiap ada perubahan environment (misal ganti email pengirim tagihan) atau perubahan logic routing, jalankan:
```bash
cd /var/www/simt-backend
php artisan optimize:clear
php artisan optimize
```

---

## 4. Troubleshooting Dasar (Penyelesaian Masalah)

### Masalah 1: Muncul Halaman "502 Bad Gateway"
**Penyebab Umum:** Nginx berjalan, tetapi PHP-FPM mati atau *overloaded*.
**Solusi:**
1. Cek log Nginx: `sudo tail -f /var/log/nginx/error.log`.
2. Restart PHP: `sudo systemctl restart php8.2-fpm`.
3. Jika gagal restart karena kurang RAM, jalankan `sudo systemctl restart nginx php8.2-fpm postgresql` atau *reboot* server.

### Masalah 2: Aplikasi Sangat Lambat (Time-Out) saat Diakses
**Penyebab Umum:** Database terkunci (deadlock), *Memory Leak*, atau terkena serangan *DDoS/Brute force*.
**Solusi:**
1. Masuk ke *dashboard* Cloudflare, aktifkan fitur **"Under Attack Mode"**. Ini akan memaksa pengunjung melewati verifikasi *Captcha* untuk memblokir Bot/DDoS.
2. Periksa memori via `htop`.
3. Cek jumlah koneksi database: `sudo netstat -anp | grep 5432 | grep ESTABLISHED | wc -l`. Jika > 100, pertimbangkan meningkatkan limit `max_connections` di `postgresql.conf` (dengan konsekuensi RAM).

### Masalah 3: Pengguna Tidak Bisa Login (CSRF Token Mismatch)
**Penyebab Umum:** 
- Cache browser bermasalah.
- Konfigurasi Sanctum Statefull Domain pada `.env` (variabel `SANCTUM_STATEFUL_DOMAINS` dan `SESSION_DOMAIN`) tidak cocok dengan URL yang sedang diakses.
**Solusi:**
1. Minta user *Clear Cache/Cookies* browser.
2. Pastikan file `.env` di production memiliki konfigurasi:
   ```env
   SANCTUM_STATEFUL_DOMAINS="app.simt-mts.sch.id"
   SESSION_DOMAIN=".simt-mts.sch.id"
   ```
3. Jalankan `php artisan config:clear`.

### Masalah 4: Gagal Upload File (Error HTTP 413 / Request Entity Too Large)
**Penyebab Umum:** File lebih besar dari batas Nginx atau PHP.
**Solusi:**
1. Edit `/etc/nginx/nginx.conf`, tambahkan `client_max_body_size 5M;`
2. Edit `/etc/php/8.2/fpm/php.ini`, ubah `upload_max_filesize = 5M` dan `post_max_size = 5M`.
3. Restart Nginx dan PHP-FPM.

---

## 5. Kontak Dukungan (Escalation Matrix)

Jika permasalahan tidak dapat diselesaikan oleh IT Administrator sekolah (Melebihi SLA 4 jam *downtime*), segera hubungi pihak ke-3:

- **Level 1 (Infrastruktur / VPS Down):**
  - Hubungi *Support Ticketing* Provider VPS (NusaCloud/IDCloudhost).
- **Level 2 (Bug Aplikasi / Error Code):**
  - Hubungi Lead Developer Vendor Pembuat SIMT MTs (via WhatsApp / Email support@vendor-simt.id).
  - Siapkan bukti berupa *screenshot* halaman error dan potongan dari file `laravel.log`. 

*Operations manual ini dirancang sebagai "Buku Pintar" bagi pengelola IT sekolah agar memiliki kemandirian dalam menjaga keandalan sistem sehari-hari.*