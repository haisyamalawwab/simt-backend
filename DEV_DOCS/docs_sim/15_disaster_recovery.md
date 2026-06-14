# Rencana Pemulihan Bencana (Disaster Recovery Plan)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** System Administrator  

---

## 1. Pendahuluan
Dokumen *Disaster Recovery Plan* (DRP) ini merumuskan langkah-langkah strategis untuk menyelamatkan dan memulihkan sistem SIMT MTs (MVP) saat terjadi bencana tak terduga (seperti kegagalan *hardware* VPS, serangan siber, atau kerusakan data/database corruption). Dengan keterbatasan anggaran operasional sekolah, DRP ini didesain menggunakan metode *Cold Standby* dan *Asynchronous Backup* untuk menekan biaya namun tetap menjamin keselamatan data akademik siswa.

## 2. Tujuan Pemulihan
Dua metrik utama dalam DRP SIMT MTs adalah:
1. **Recovery Point Objective (RPO): Maksimal 24 Jam**
   - RPO adalah toleransi hilangnya data. Karena sistem pendidikan (input nilai, SPP, absensi) berjalan harian, kehilangan data maksimal yang dapat ditoleransi adalah aktivitas dalam satu hari terakhir.
2. **Recovery Time Objective (RTO): Maksimal 8 Jam Kerja**
   - RTO adalah target waktu untuk menghidupkan kembali sistem setelah bencana terjadi.

---

## 3. Strategi Pencadangan (Backup Strategy)

Mengingat kita tidak menggunakan layanan *managed database* berbayar mahal, strategi pencadangan dilakukan secara mandiri via skrip otomatis.

### 3.1. Apa yang Dicadangkan?
- **Database (PostgreSQL):** Seluruh skema, tabel, dan data (termasuk users, nilai, presensi, data keuangan).
- **Storage/File (Assets):** File unggahan (foto profil, bukti bayar, dokumen sekolah) di direktori `storage/app/public`.

### 3.2. Frekuensi & Metode Backup
- **Daily Backup (Harian):** Berjalan setiap pukul 02:00 WIB (dini hari) menggunakan *Cron Job*. Menjalankan `pg_dump` dan meng-zip folder storage.
- **Weekly Full Backup (Mingguan):** Setiap hari Minggu pukul 01:00 WIB.

### 3.3. Penyimpanan Backup (Off-site Storage)
Sangat terlarang menyimpan hasil backup hanya di dalam VPS yang sama dengan aplikasi. Jika VPS mati total, data akan hilang. Oleh karena itu:
- File hasil backup (.sql dan .zip) akan dienkripsi dan diunggah secara otomatis (menggunakan rclone / AWS CLI) ke penyedia Cloud Storage gratis/murah (seperti Google Drive dari akun admin sekolah, atau AWS S3 Glacier / Cloudflare R2 yang sangat murah).

---

## 4. Klasifikasi Bencana (Disaster Tiers)

### Level 1: Kesalahan Data Minor (Data Corruption/Accidental Deletion)
- **Kondisi:** Server hidup, namun data penting (misal: tabel siswa atau transaksi SPP) terhapus tidak sengaja oleh TU.
- **Mitigasi:** 
  1. Jangan matikan server, namun batasi akses user (Maintenance Mode: `php artisan down`).
  2. Restore tabel spesifik dari *SQL Dump* harian terakhir.

### Level 2: Kegagalan Komponen Sistem (Service Crash / Out of Memory)
- **Kondisi:** Nginx mati, PostgreSQL *crash* karena OOM (Out of Memory, sangat mungkin terjadi di VPS RAM 2GB).
- **Mitigasi:**
  1. SSH ke dalam server.
  2. Analisis `dmesg -T` atau `/var/log/syslog` untuk melihat status OOM-Killer.
  3. *Restart service:* `sudo systemctl restart nginx postgresql php8.2-fpm`.
  4. Atur ulang konfigurasi memori (kurangi `shared_buffers` di Postgres).

### Level 3: Kerusakan Fatal Infrastruktur (Server Hilang / Terkena Ransomware)
- **Kondisi:** VPS Provider mengalami *datacenter fire*, *hardware failure*, atau server terkena infeksi *malware* yang mengenkripsi *storage*.
- **Mitigasi (Full Disaster Recovery - DRP Eksekusi):**
  Lanjut ke Bab 5.

---

## 5. Prosedur Eksekusi Pemulihan Bencana (Level 3 DR)

Jika server mati total dan tidak bisa dipulihkan, berikut adalah urutan *Runbook* untuk mencapai RTO < 8 jam:

### Fase 1: Inisiasi & Komunikasi (Jam ke-1)
1. IT/Dev mengonfirmasi ke pihak provider VPS (apakah bisa diselamatkan atau tidak).
2. Jika tidak, Kepala Sekolah diberitahu bahwa sistem akan *offline* dan masuk fase pemulihan.
3. Ubah DNS Cloudflare untuk mengarahkan pengunjung ke halaman statis berbunyi *"Sistem dalam Perawatan Darurat"*.

### Fase 2: Provisioning Server Baru (Jam ke-2 hingga ke-3)
1. Menyewa VPS baru (bisa di provider yang sama atau berbeda).
2. Menjalankan *script provisioning* (menginstal Nginx, PostgreSQL, PHP, Node.js seperti di dokumen Deployment).

### Fase 3: Pemulihan Data & Kode (Jam ke-4 hingga ke-5)
1. **Pull Codebase:** Lakukan `git clone` repository aplikasi ke server baru. Jalankan `composer install`.
2. **Download Backup:** Tarik data terbaru dari Cloud Storage (Google Drive / Cloudflare R2).
   ```bash
   rclone copy remote:backup/simt_db_latest.sql /tmp/
   rclone copy remote:backup/simt_storage_latest.zip /tmp/
   ```
3. **Restore Database:**
   ```bash
   createdb simt_db
   psql -U simt_user -d simt_db < /tmp/simt_db_latest.sql
   ```
4. **Restore Files:** Ekstrak file `zip` storage ke `/var/www/simt-backend/storage/app/public`.

### Fase 4: Konfigurasi Jaringan & Pengujian (Jam ke-6 hingga ke-7)
1. Perbarui *IP Address* A-Record di Cloudflare untuk mengarah ke IP VPS baru.
2. Tunggu propagasi DNS (biasanya instan jika menggunakan *proxied* mode Cloudflare).
3. Lakukan pengujian cepat (Sanity Test):
   - Login menggunakan akun Guru.
   - Periksa apakah data kemarin (sebelum jam 2 pagi) masih utuh.

### Fase 5: Operasional Normal (Jam ke-8)
1. Sistem dinyatakan *Live*.
2. TU diinstruksikan untuk melakukan *Data Reconciliation*: Jika ada transaksi (seperti bayar SPP tunai) di hari terjadinya sistem *crash* (antara jam 02.00 pagi hingga jam kejadian), transaksi tersebut harus diinput ulang (karena RPO adalah 24 jam).

---

## 6. Pengujian DRP (DRP Testing)
Rencana ini hanya menjadi teori jika tidak diuji. 
Sistem administrator wajib melakukan **Simulasi DR (Tabletop Exercise / Dry Run)** setiap 6 bulan sekali dengan mencoba melakukan *restore database* ke environment lokal/staging untuk memastikan file backup tidak *corrupt* dan script pemulihan masih relevan.