# Dokumen Rencana Keamanan (Security Plan)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Senior Full-Stack Developer & Security Analyst  

---

## 1. Pendahuluan
Dokumen ini mendefinisikan strategi keamanan, standar kepatuhan, dan arsitektur perlindungan data untuk SIMT MTs (MVP). Mengingat keterbatasan anggaran operasional (budget awal Rp 5.000.000, VPS ± Rp 300.000/bulan) dan model penyebaran (SaaS monolithic di VPS lokal), pendekatan yang diambil adalah **Lean & Pragmatic Security**—berfokus pada mitigating high-impact risks dengan open-source tools dan free-tier cloud services.

## 2. Kepatuhan (Compliance)

### 2.1. Kepatuhan UU PDP (Undang-Undang Pelindungan Data Pribadi No. 27 Tahun 2022)
Karena sistem memproses data pribadi siswa (NIK, NISN, alamat, tanggal lahir, rekam nilai) dan orang tua (Nama, No WhatsApp, pekerjaan), kepatuhan dasar UU PDP wajib diimplementasikan:
1. **Consent Management (Persetujuan):** 
   - Terdapat halaman *Terms of Service* (ToS) dan *Privacy Policy* saat login pertama kali bagi Guru, TU, dan Orang Tua.
2. **Data Minimization & Retention:**
   - Menyimpan data esensial yang diperlukan untuk kegiatan akademik.
   - Penghapusan data (soft delete) untuk user inaktif setelah retensi 5 tahun (sesuai regulasi pendidikan).
3. **Data Security (Keamanan Data):**
   - Enkripsi Data in Transit (HTTPS/TLS 1.3 via Cloudflare).
   - Enkripsi Data at Rest untuk kolom sensitif (seperti password) menggunakan `Bcrypt` dengan work factor minimum 12.
4. **Data Subject Rights (Hak Subjek Data):**
   - Fitur bagi user untuk meminta koreksi data (melalui portal TU) dan melihat profil data pribadi mereka.

### 2.2. Kepatuhan OWASP Top 10 (2021)
Sebagai aplikasi berbasis web (Vue.js + Laravel), kita menerapkan mitigasi standar OWASP Top 10:
1. **Broken Access Control:** 
   - Penerapan *Role-Based Access Control* (RBAC) ketat di sisi middleware Laravel (Spatie Permission) dan Vue Router Navigation Guards.
   - Semua *endpoint* API (kecuali public) dikunci di belakang `auth:sanctum`.
2. **Cryptographic Failures:** 
   - Konfigurasi HSTS via Cloudflare. Semua komunikasi harus via HTTPS.
3. **Injection:** 
   - Penggunaan Eloquent ORM/Query Builder Laravel yang otomatis menangani parameter binding (mencegah SQL Injection).
   - Validasi ketat (Request Validation) untuk semua input API.
4. **Insecure Design:** 
   - *Threat modeling* sederhana pada alur autentikasi dan otorisasi. 
   - *Rate limiting* pada endpoint krusial (login, reset password).
5. **Security Misconfiguration:** 
   - Mematikan `APP_DEBUG` di environment production. 
   - Menyembunyikan versi server dan framework di response headers (X-Powered-By removed).
6. **Vulnerable and Outdated Components:** 
   - Audit dependencies secara rutin menggunakan `composer audit` dan `npm audit`.
7. **Identification and Authentication Failures:** 
   - Implementasi *Brute Force Protection* menggunakan Laravel Throttle (max 5 attempts, lockout 1 menit).
8. **Software and Data Integrity Failures:** 
   - Konfigurasi CI/CD aman tanpa mengekspos `.env`.
9. **Security Logging and Monitoring Failures:** 
   - Log aktivitas mencurigakan disalurkan ke `storage/logs/laravel.log` dengan fail2ban parsing untuk blokir IP.
10. **Server-Side Request Forgery (SSRF):** 
    - Tidak ada fitur pengambilan URL eksternal oleh user di fase MVP.

---

## 3. Arsitektur Autentikasi dan Otorisasi

### 3.1. Authentication Framework
- **Tools:** Laravel Sanctum (Stateful SPA / Token-based auth).
- **Mekanisme:** 
  - Login mengembalikan HTTP-Only Cookie (jika frontend & backend dalam domain/subdomain yang sama, e.g., `simt.school.id` dan `api.simt.school.id`).
  - Hal ini memitigasi risiko XSS mengambil JWT Token di LocalStorage.
  - Implementasi *CSRF Protection* diaktifkan melalui endpoint `/sanctum/csrf-cookie`.

### 3.2. Password Policy & Security
- Minimal 8 karakter, kombinasi huruf dan angka (opsional spesial karakter untuk MVP, agar guru/TU tidak kesulitan).
- Passwords di-hash menggunakan algoritma Bcrypt.
- Fitur *Forgot Password* dengan link token one-time yang expired dalam 60 menit.

### 3.3. Role-Based Access Control (RBAC)
- **Library:** `spatie/laravel-permission` (Backend) & Custom Directive v-role (Frontend).
- **Matrix:** Sesuai dokumen *03_pemetaan_modul_rbac.md*. Backend akan memvalidasi *Gates* dan *Policies* pada setiap request (Misal: Hanya Guru Pengampu yang bisa mengedit nilai di kelas yang diajarnya).

---

## 4. Keamanan Infrastruktur (VPS & Network)

Karena menggunakan Unmanaged VPS, kita harus mengamankan server secara mandiri:

### 4.1. Network Security Layer
- **Cloudflare Free Tier:** 
  - Bertindak sebagai Reverse Proxy, CDN, dan perlindungan *DDoS*.
  - Mengaktifkan **WAF Managed Rules** gratis milik Cloudflare.
  - Setup *Firewall Rules* (WAF Custom Rules) untuk memblokir traffic dari luar Indonesia (Geo-blocking) jika diperlukan (opsional, karena ada kemungkinan wali murid di luar negeri, MVP di-allow globally dengan challenge).
- **UFW (Uncomplicated Firewall):** 
  - Hanya port 80 (HTTP), 443 (HTTPS), dan port SSH (custom port, misalnya 2222) yang dibuka.
  - Koneksi database (PostgreSQL port 5432) **TIDAK** diekspos ke publik, hanya menerima dari `localhost`.

### 4.2. Host Security Configuration
- **SSH Hardening:** 
  - Disable root login (`PermitRootLogin no`).
  - Disable password authentication (`PasswordAuthentication no`), gunakan SSH Keys (Ed25519).
  - Ubah default port SSH untuk mengurangi *noise* bot scanner.
- **Fail2Ban:** 
  - Menganalisis log SSH dan log Nginx untuk memblokir IP yang melakukan *brute force* ke SSH, login aplikasi, atau request path mencurigakan (seperti `/wp-admin`, `/.env`).

---

## 5. Perlindungan Level Aplikasi

### 5.1. File Upload Security
Modul yang menerima upload (foto siswa, logo sekolah, bukti pembayaran):
- **Validasi Ekstensi & MIME Type:** Hanya izinkan `jpg, jpeg, png, pdf`.
- **Batas Ukuran File:** Maksimal 2MB (hemat disk space VPS).
- **Penyimpanan:** File disimpan di direktori `storage/app/public` (bukan public html). File di-rename menjadi UUID acak untuk mencegah eksekusi file PHP berbahaya (Directory Traversal / RCE).
- **Scanning:** (Idealnya menggunakan ClamAV, namun berat untuk VPS kecil, mitigasi MVP bertumpu pada validasi file yang ketat).

### 5.2. API Security
- **Rate Limiting:** `throttle:60,1` untuk API reguler (60 request per menit). `throttle:5,1` untuk login.
- **CORS Configuration:** Dibatasi hanya untuk origin Vue frontend (`https://app.simt-mts.id`).

## 6. Incident Response Plan (Sederhana)

Jika terjadi *Security Breach* (Data Leak, Deface, Ransomware):
1. **Containment:** Mematikan sementara service Nginx atau merubah DNS record di Cloudflare ke halaman "Under Maintenance".
2. **Eradication:** Investigasi melalui log (`/var/log/nginx/access.log`, `laravel.log`) untuk menemukan vektor serangan. Patch celah atau restore file yang terinfeksi.
3. **Recovery:** Restore sistem dan database dari backup harian terakhir (mengacu ke DRP).
4. **Notification:** Jika ada kebocoran data sensitif, pihak sekolah (Kepala Sekolah) wajib diberitahu dalam 1x24 jam untuk diteruskan ke pemangku kepentingan sesuai mandat UU PDP.

---
*Dokumen ini merupakan panduan keamanan taktis MVP, menjembatani kebutuhan compliance tingkat tinggi dengan realita budget 5 juta rupiah.*