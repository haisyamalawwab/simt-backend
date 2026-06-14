# SESSION CONTEXT & HANDOVER (Sesi 7 Akhir: Finishing S3 + Sprint 4 Selesai)
## SIMT MTs — Dokumen Handover Lengkap untuk Melanjutkan Sesi Berikutnya

**Tanggal Sesi:** 14 Juni 2026  
**Agent:** Arena Agent Mode (Sesi S3 Finishing & S4 Complete)  
**Status Akhir:** ✅ Sprint 1-3 SELESAI TOTAL · ✅ Sprint 4 (WA Gateway) SELESAI TOTAL · 🔜 SPRINT 5 (Keuangan & Portal Ortu)  
**Tujuan Dokumen:** Agar agen pada sesi berikutnya bisa **LANGSUNG LANJUT** tanpa kehilangan konteks. **BACA INI DULU!**

---

## 0. CARA CEPAT MELANJUTKAN (Mulai dari sini di Sesi Baru)

Karena lingkungan sandbox di-exclude dari snapshot (terutama folder `vendor/` PHP dan `node_modules/` Node.js), jalankan langkah cepat berikut di awal sesi baru Anda:

### 1) Pasang Ekstensi OS & Library PHP/Composer
```bash
# Update & Install OS Packages
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y php-cli php-mbstring php-xml php-sqlite3 php-curl php-zip php-gd php-mysql unzip composer

# Restore PHP 8.4 Safe Package Require Bug (SANGAT KRITIS!)
# Buat stub kosong di generated/8.4, 8.2, dan 8.1 agar composer autoload & php artisan tidak throw fatal error di PHP 8.4
composer install --no-interaction --prefer-dist --optimize-autoloader

mkdir -p vendor/thecodingmachine/safe/generated/8.1 && for f in apache.php apcu.php array.php bzip2.php calendar.php classobj.php com.php cubrid.php curl.php datetime.php dir.php eio.php errorfunc.php exec.php fileinfo.php filesystem.php filter.php fpm.php ftp.php funchand.php functionsList.php gettext.php gmp.php gnupg.php hash.php ibase.php ibmDb2.php iconv.php image.php imap.php info.php inotify.php ldap.php mbstring.php misc.php network.php openssl.php outcontrol.php pgsql.php posix.php pspell.php readline.php sockets.php sodium.php solr.php spl.php sqlsrv.php ssdeep.php ssh2.php stream.php strings.php swoole.php uodbc.php uopz.php url.php var.php xdiff.php xml.php xmlrpc.php yaml.php yaz.php zip.php zlib.php; do echo "<?php" > vendor/thecodingmachine/safe/generated/8.1/$f; done

mkdir -p vendor/thecodingmachine/safe/generated/8.2 && for f in errorfunc.php mbstring.php openssl.php pcre.php pgsql.php sqlite3.php exec.php; do echo "<?php" > vendor/thecodingmachine/safe/generated/8.2/$f; done

mkdir -p vendor/thecodingmachine/safe/generated/8.4 && for f in apache.php array.php curl.php datetime.php ftp.php functionsList.php gettext.php ibmDb2.php image.php imap.php info.php inotify.php ldap.php mbstring.php misc.php network.php openssl.php outcontrol.php pgsql.php posix.php pspell.php readline.php sockets.php sodium.php stream.php uodbc.php xml.php zlib.php; do echo "<?php" > vendor/thecodingmachine/safe/generated/8.4/$f; done
```

### 2) Jalankan Setup DB & Verifikasi Test (HARUS 28 passed)
```bash
cp .env.example .env 2>/dev/null; php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan test                                   # HARUS 28 passed (63 assertions)
```

### 3) Pasang & Jalankan WA Gateway (Node.js/TypeScript)
```bash
cd /home/user/simt-wa-gateway
npm install
npm run build                                      # Compile TS -> JS (Pastikan 0 error)
npm run dev                                        # Jalankan dev-server di port 8081
```

---

## 1. APA YANG SUDAH DISELESAIKAN DI SESI INI (SESI 7)

### A. Finishing Sprint 3 (Presensi) — 100% Selesai & Teruji
*   **Export Excel Rekap Bulanan (FR-P06):** Membuat class ekspor `Modules/Attendance/app/Exports/AttendanceRecapExport.php` (Maatwebsite Excel) bersanding dengan tampilan grid tabel berwarna di `resources/views/admin/attendance/rekap_excel.blade.php`.
*   **Tombol Unduh UI:** Menambahkan tombol "Export Excel" berikon SVG di sebelah tombol filter tampilan pada `resources/views/admin/attendance/rekap.blade.php`.
*   **Pembersihan Berkas Orphan:** Menghapus berkas legacy/orphan controller `FinanceController.php` dari `Modules/Attendance/app/Http/Controllers/` agar modularitas nwidart tetap bersih.

### B. Penyelesaian Sprint 4 (WhatsApp Gateway - Baileys) — 100% Selesai & Teruji
*   **Decoupled Microservice Gateway (`simt-wa-gateway/`):**  
    Projek Node.js + TypeScript independen berbasis **Express.js & Baileys** (`@whiskeysockets/baileys`). Berfungsi mengelola multi-session secara paralel, auto-restore sesi dari disk saat restart, mengonversi QR Code teks menjadi **Base64 Data URI** (memangkas beban frontend), serta menyajikan HTTP REST API & Webhooks callback.
*   **API-Driven Modular MVC Laravel (`Modules/Notification/`):**  
    Membuat modul baru bernama `Notification` secara *Plug & Play* menggunakan nwidart. Memisahkan logika notifikasi penuh agar tidak mengganggu sistem inti apabila terjadi kendala WhatsApp.
*   **Antarmuka Scan QR Connect:**  
    Membangun view `/admin/notification/connect` dengan **JS Live Polling Script** yang mengecek status sesi ke gateway setiap 3 detik. Jika pengguna berhasil memindai QR, halaman otomatis memuat ulang (*reload*) dan beralih ke status **"TERHUBUNG"**.
*   **Delivery Status Callback Webhook:**  
    Menyediakan webhook `/api/v1/wa/delivery-callback` di Laravel untuk menerima status kirim dari Node.js. Laravel memvalidasi `X-Callback-Secret` dan secara otomatis memetakan status `delivered` menjadi `'sent'` guna mematuhi check constraint database SQLite.
*   **Test Suite:** Menambahkan skenario test feature lengkap pada `tests/Feature/NotificationModuleTest.php` dan `tests/Feature/AttendanceModuleTest.php`. Total **28 passed (63 assertions)** lulus hijau sempurna.

---

## 2. PETA PROJEK & FILE KUNCI

```
/home/user/
├── simt-backend/                                  # REPOSITORI UTAMA (Laravel)
│   ├── app/Jobs/SendWaNotification.php            # Laravel Job mengirim data ke Node.js
│   ├── config/app.php                             # Konfigurasi WA_GATEWAY terdaftar
│   ├── Modules/
│   │   ├── Core/                                  # Modul Utama (Auth, Sesi, Dashboard)
│   │   ├── Student/                               # Modul Kesiswaan & Impor
│   │   ├── Attendance/                            # Modul Presensi & Rekap (Excel Export)
│   │   │   └── app/Exports/AttendanceRecapExport.php
│   │   ├── Finance/                               # Modul Keuangan Lite (Bills/Payments)
│   │   └── Notification/                          # Modul Notifikasi (Sprint 4 Baru!)
│   │       ├── app/Http/Controllers/NotificationController.php # Polling & Webhook receiver
│   │       ├── resources/views/connect.blade.php  # UI QR Scanner & Live Polling
│   │       └── routes/ (web.php, api.php)         # Web Connect & API Webhook Callback
│   └── tests/Feature/NotificationModuleTest.php   # Unit Test S4
│
└── simt-wa-gateway/                               # GATEWAY ENGINE (Node.js/TypeScript)
    ├── sessions/                                  # Folder penyimpanan auth state per tenant
    ├── src/index.ts                               # Kode utama Express + Baileys + Pino
    ├── package.json                               # Dependensi (baileys, express, qrcode)
    └── tsconfig.json                              # Konfigurasi TypeScript
```

---

## 3. TARGET BERIKUTNYA — SPRINT 5 (KEUANGAN LITE & PORTAL ORANG TUA)

Target utama pada sesi mendatang (Sprint 5) berdasarkan Doc 40:

- [ ] **S5-01: Penerbitan Tagihan SPP Massal (Core Logic):**  
      Integrasikan logika di `Modules/Finance/app/Http/Controllers/FinanceController.php` untuk menerbitkan tagihan bulanan SPP massal berdasarkan tahun ajaran aktif dan kelas siswa.
- [ ] **S5-02: Pengiriman Nota Pembayaran WA:**  
      Saat pembayaran SPP dicatat, cetak kwitansi PDF (`Barryvdh DomPDF`) dan trigger antrean pengiriman struk pembayaran PDF/teks ke nomor wali murid melalui gateway Baileys (`SendWaNotification`).
- [ ] **S5-03: Portal Orang Tua (Next.js):**  
      Sambungkan API kesiswaan, absensi harian (`GET /api/v1/students/{student}/attendances`), dan tagihan keuangan (`GET /api/v1/students/{student}/bills`) untuk dikonsumsi secara aman oleh Next.js portal orang tua (`simt-portal`).
- [ ] **S5-04: Pengiriman Kredensial Wali Massal:**  
      Gunakan data antrean kredensial di tabel `wa_notifications` untuk dikirimkan secara masif ke nomor WhatsApp wali murid (mengandung username/no. WA dan sandi acak).

---

## 4. AKUN DEMO YANG VALID (Password semua: `password`)

| Login | Peran | Tenant | Modul Langganan |
|---|---|---|---|
| `vendor@simt.id` | superadmin | lintas tenant | Lintas |
| `ahmad@mts-alhikmah.sch.id` | admin_sekolah | T1 (Al-Hikmah) | Modul Lengkap (Core, Student, Attendance, Finance, Notification) |
| `ahmad@mts-annur.sch.id` | guru | T2 (An-Nur) | Core + Student saja (Akses Keuangan/Presensi = 403) |
| phone `628520000001` | wali | T1 (Al-Hikmah) | Akses portal ortu (Ownership check aktif) |

---

*Handover ini ditulis 14 Juni 2026. Seluruh repositori berada dalam kondisi bersih, terkompilasi sukses, dan lulus uji test suite 100%. Selamat melanjutkan pengerjaan Sprint 5!*
