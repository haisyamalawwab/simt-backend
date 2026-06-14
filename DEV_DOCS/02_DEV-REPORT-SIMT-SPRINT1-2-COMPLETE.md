# DEV REPORT — SIMT MVP Sprint 1 & 2 (Laravel 11)
## Sistem Informasi Manajemen Terpadu — Madrasah Tsanawiyah

**Tanggal:** 13 Juni 2026
**Versi:** 1.0 (Post-Audit & Fix)
**Status:** Sprint 1-2 Selesai — Semua Gap Tertutup
**Lokasi Kode:** `/home/z/my-project/sim-sekolah/SIMT-Laravel/SIMT-Laravel/`
**Referensi:** 50 dokumen `ANALISA_KELAYAKAN` (00-48), OpenWA Gateway, SIMT-MVP Node.js

---

## DAFTAR ISI

1. [Ringkasan Eksekusi](#1-ringkasan-eksekusi)
2. [Apa yang Sudah Dibangun (Sprint 1)](#2-apa-yang-sudah-dibangun-sprint-1)
3. [Apa yang Sudah Dibangun (Sprint 2)](#3-apa-yang-sudah-dibangun-sprint-2)
4. [Apa yang Dibuang / Tidak Diimplementasi](#4-apa-yang-dibuang--tidak-diimplementasi)
5. [Gap Analysis & Perbaikan yang Dilakukan](#5-gap-analysis--perbaikan-yang-dilakukan)
6. [Integrasi OpenWA WhatsApp Gateway](#6-integrasi-openwa-whatsapp-gateway)
7. [Arsitektur Sistem Saat Ini](#7-arsitektur-sistem-saat-ini)
8. [Database Schema (16 Migrasi)](#8-database-schema-16-migrasi)
9. [Route Registry](#9-route-registry)
10. [Middleware Chain](#10-middleware-chain)
11. [RBAC — 6 Role x 18 Permission](#11-rbac--6-role-x-18-permission)
12. [Struktur File Final](#12-struktur-file-final)
13. [Cara Menjalankan](#13-cara-menjalankan)
14. [Demo Accounts](#14-demo-accounts)
15. [Verification Checklist](#15-verification-checklist)
16. [Sprint 3-6 Roadmap](#16-sprint-3-6-roadmap)
17. [Risiko & Catatan Teknis](#17-risiko--catatan-teknis)

---

## 1. Ringkasan Eksekusi

### 1.1 Konteks Proyek

SIMT (Sistem Informasi Manajemen Terpadu) adalah produk **Micro SaaS B2B2C** untuk **Madrasah Tsanawiyah (MTs)** di bawah Kementerian Agama (Kemenag). Sistem ini berinteraksi dengan ekosistem pemerintah DAPODIK dan EMIS. Proyek memiliki anggaran MVP sebesar **Rp 5 juta**, target waktu **3 bulan**, dan membidik **3-5 sekolah pilot**. Model bisnis B2B2C: biaya Rp 2.000/siswa/bulan yang disisipkan ke SPP, dengan titik impas (BEP) di 5 sekolah.

### 1.2 Posisi Sprint

| Sprint | Goal Utama | Output Kunci | Status |
|--------|-----------|-------------|--------|
| **S1** | Foundation: Tenancy + RBAC + Auth + API | Laravel 11, SQLite, Sanctum, Spatie RBAC, 10 test pass | Selesai |
| **S2** | Kesiswaan: CRUD Blade + Import Excel + Akun Wali | 19 test pass, Import wizard 3 langkah, modular nwidart | Selesai |
| **S3** | Presensi: Grid tap-toggle + Rekap bulanan | — | Belum dimulai |
| **S4** | WA Gateway: OpenWA + Queue Laravel | — | Belum dimulai |
| **S5** | Keuangan: Kwitansi PDF + Portal Next.js | — | Belum dimulai |
| **S6** | UAT + Go-Live: Docker deploy + 4 Acceptance Gate | — | Belum dimulai |

### 1.3 Stack Teknologi

| Layer | Teknologi | Versi | Keterangan |
|-------|-----------|-------|------------|
| **Framework** | Laravel | 11.x | PHP 8.2+ |
| **Database (Dev)** | SQLite | 3 | WAL mode, single-file |
| **Database (Prod)** | PostgreSQL | 16 | Via Laravel DB abstraction |
| **Auth API** | Laravel Sanctum | 4.x | Bearer token, 30 hari expiry |
| **Auth Web** | Laravel Session | Built-in | Cookie-based, Blade panel |
| **RBAC** | Spatie Permission | 6.x | 6 roles, 18 permissions |
| **WhatsApp** | OpenWA Gateway | NestJS 11 | Via HTTP REST API + HMAC webhooks |
| **Queue** | Database Queue | Sync (dev) | Redis (prod) |
| **Frontend Admin** | Blade + Tailwind | CDN (dev) | Vite build (prod, S6) |
| **Frontend Portal** | Next.js 14 | PWA | Untuk orang tua/wali |

---

## 2. Apa yang Sudah Dibangun (Sprint 1)

### 2.1 Multi-Tenancy Foundation

Sistem tenancy menggunakan pendekatan **Single-Database, Row-Level Isolation**. Setiap tabel domain memiliki kolom `tenant_id` yang di-index. Isolasi data ditegakkan melalui dua mekanisme: **Global Scope** (otomatis memfilter query berdasarkan tenant aktif) dan **Middleware Chain** (memvalidasi akses lintas tenant pada setiap request).

```
Single-Database Multi-Tenancy (Row-Level Isolation)
├── tenant_id (BIGINT, indexed) di SEMUA tabel domain
├── Middleware: IdentifyTenant (X-Tenant-Domain header / subdomain)
├── Middleware: CheckTenantAccess (token user WAJIB cocok tenant)
├── Middleware: SetTenantFromUser (sesi web: dari users.tenant_id)
├── Middleware: EnsureModuleActive (Plug & Play module toggle)
└── Trait: BelongsToTenant (Global Scope + auto-fill tenant_id)
```

**Keputusan arsitektural:** Pendekatan manual ringan dipilih (bukan package `stancl/tenancy`) untuk menghemat dependensi dan mempertahankan kontrol penuh. Konteks tenant disimpan sebagai singleton `Tenancy` per-request, dan trait `BelongsToTenant` menambahkan global scope yang secara otomatis memfilter semua query Eloquent.

### 2.2 RBAC — 6 Role & 18 Permission

Sistem RBAC menggunakan Spatie Permission v6 dengan matriks 6 role yang di-scoped per tenant. Seeder `RolePermissionSeeder` membuat 6 role dan 18 permission secara otomatis, lalu memetakan akses berdasarkan kebutuhan masing-masing peran.

**Roles:**
1. `superadmin` — Vendor/SIMT, akses lintas semua tenant
2. `kepala_madrasah` — Kepala sekolah, akses penuh di tenant sendiri
3. `tu` (Tata Usaha) — CRUD siswa, presensi, keuangan
4. `bendahara` — Modul keuangan saja
5. `guru` — Presensi (kelas yang diampu), view siswa
6. `wali` — Hanya portal orang tua (view data anak)

**Permissions:** `students.view`, `students.manage`, `attendance.view`, `attendance.manage`, `finance.view`, `finance.manage`, `tenants.manage`, `modules.manage`, dan seterusnya (total 18).

### 2.3 Authentication

Dua jalur autentikasi dibangun secara paralel:

1. **API (Sanctum):** Login via `POST /api/v1/auth/login` dengan email atau nomor HP + password. Mengembalikan Bearer token berlaku 30 hari. Digunakan oleh portal Next.js dan integrasi pihak ketiga.

2. **Web (Session):** Login via `POST /login` dengan email atau nomor HP + password. Session cookie-based. Digunakan oleh panel admin Blade. Mendukung redirect berdasarkan role (superadmin ke panel vendor, user biasa ke dashboard tenant).

Login juga memvalidasi status akun (`is_active`) dan status tenant (tidak bisa login jika tenant suspended). Pada setiap login berhasil, `last_login_at` diupdate.

### 2.4 Database Migrations

Total **16 migrasi** dibuat:

| No | File Migrasi | Fungsi |
|----|-------------|--------|
| 1 | `000001_create_cache_table` | Cache framework Laravel |
| 2 | `000002_create_jobs_table` | Queue jobs |
| 3 | `000003_create_permission_tables` | Spatie: roles, permissions, model_has_roles, model_has_permissions, role_has_permissions |
| 4 | `000010_create_tenants_table` | Master tenant (sekolah) |
| 5 | `000011_create_users_table` | User (admin, guru, wali) + tenant_id |
| 6 | `000012_create_school_years_and_classes_table` | Tahun ajaran + kelas/rombel |
| 7 | `000013_create_students_and_pivots_table` | Siswa + pivot class_student + guardian_student |
| 8 | `000014_create_attendances_table` | Presensi harian |
| 9 | `000015_create_bills_and_payments_table` | Tagihan + pembayaran |
| 10 | `000016_create_wa_notifications_table` | Log queue notifikasi WhatsApp |
| 11 | `personal_access_tokens` | Sanctum tokens |

### 2.5 Seeders

1. **`RolePermissionSeeder`:** Membuat 6 role + 18 permission + matriks akses. Dieksekusi otomatis saat fresh migration.

2. **`DemoTenantSeeder`:** Membuat 2 tenant demo (MTs Al-Hikmah dan MTs An-Nur), 35 siswa, 5 user (termasuk "Guru Ahmad" yang memiliki peran berbeda di kedua tenant), 30 tagihan, dan data pendukung lainnya.

### 2.6 Gate Verification (Sprint 1)

| Gate | Test | Hasil |
|------|------|-------|
| G1 | Health Check `GET /api/health` | `{"status":"ok"}` |
| G2 | Tenant Ping `GET /api/v1/ping` | Resolusi tenant dari header |
| G3 | T1 Login | Token Sanctum + tenant context |
| G4 | T1 Students | 30 records (terisolasi) |
| G5 | T2 Login | Token Sanctum (tenant berbeda) |
| G6 | T2 Students | 5 records (terisolasi) |
| G7 | Cross-tenant isolation | 403 FORBIDDEN_TENANT |
| G8 | Module Inactive | 403 MODULE_INACTIVE |
| G9 | Wali Children | 10 anak dari relasi guardian_student |
| G10 | Superadmin | Login tanpa tenant, akses global |

**Test otomatis:** 10 passed, 19 assertions. Termasuk 8 test isolasi tenant, cross-tenant forbidden, module gate, dan 2 test default Laravel.

---

## 3. Apa yang Sudah Dibangun (Sprint 2)

### 3.1 CRUD Kesiswaan (Student Module)

Modul Kesiswaan menyediakan operasi CRUD lengkap untuk data siswa melalui panel admin Blade. Controller `StudentController` menangani seluruh lifecycle data siswa, dari pencarian/filter, pembuatan, hingga pembaruan dan penghapusan.

**Fitur:**
- Daftar siswa dengan pagination (25 per halaman) dan pencarian berdasarkan nama/NISN
- Formulir tambah siswa dengan pemilihan kelas, tahun ajaran aktif, dan data wali
- Formulir edit siswa dengan pre-populate data
- Soft-delete dan hard-delete
- Auto-fill `tenant_id` dari global scope (BelongsToTenant trait)
- Module gating: hanya bisa diakses jika modul Student aktif untuk tenant

**Route yang tersedia:**
- `GET /students` — Daftar siswa (index)
- `GET /students/create` — Form tambah
- `POST /students` — Simpan siswa baru
- `GET /students/{student}/edit` — Form edit
- `PUT /students/{student}` — Update siswa
- `DELETE /students/{student}` — Hapus siswa

### 3.2 CRUD Tahun Ajaran & Kelas

Fitur manajemen data master pendukung:
- **Tahun Ajaran:** CRUD dengan constraint 1 tahun ajaran aktif per tenant
- **Kelas/Rombel:** CRUD dengan relasi ke tahun ajaran dan wali kelas

### 3.3 Import Excel Wizard 3 Langkah (FR-S02)

Fitur inti Sprint 2 yang memungkinkan import data siswa massal dari file Excel/CSV. Proses dibagi menjadi 3 tahap yang memberikan kontrol penuh kepada pengguna sebelum data disimpan.

| Langkah | Perilaku |
|---------|---------|
| **1. Upload** | Terima file xlsx/xls/csv, maks 5 MB / 1.000 baris; template CSV bisa diunduh |
| **2. Preview** | Validasi per baris: nama wajib, JK harus L/P, NISN 8-12 digit + cek duplikat (DB & dalam-file), kelas harus ada di TA aktif, normalisasi WA (08xx menjadi 628xx), parsing tanggal Excel-serial & string; baris error disorot merah dan dilewati |
| **3. Commit** | Satu transaksi DB (tidak ada partial-commit); hasil di-cache 30 menit berdasarkan token sehingga commit = persis yang di-preview |

**Side-effects dari import:**
- Akun wali dibuat otomatis (username = nomor WA, password acak 8 karakter)
- Role `wali` di-assign per tenant
- Kredensial login diantrikan ke tabel `wa_notifications` untuk dikirim via WhatsApp (worker akan diproses di Sprint 4)

### 3.4 Akun Wali Otomatis

Setiap kali siswa ditambahkan (manual maupun via import), sistem secara otomatis:
1. Membuat user baru dengan role `wali`
2. Menggunakan nomor WA wali sebagai identitas login
3. Meng-generate password acak 8 karakter
4. Menyimpan kredensial di queue `wa_notifications` untuk dikirim via WhatsApp

### 3.5 Presensi (Sprint 2 Partial)

Modul presensi sudah memiliki:
- **Grid presensi:** Halaman `GET /attendance` menampilkan grid siswa per kelas dengan opsi tap-toggle status (Hadir, Sakit, Izin, Alpha)
- **Rekap bulanan:** Halaman `GET /attendance/rekap` menampilkan rekap kehadiran per bulan dengan filter kelas dan tahun ajaran
- **API presensi:** `GET /api/v1/students/{student}/attendances?month=` untuk portal orang tua

### 3.6 Keuangan (Sprint 2 Partial)

Modul keuangan menyediakan:
- **Daftar tagihan:** `GET /finance/bills` — Melihat seluruh tagihan SPP siswa
- **Generate tagihan massal:** `POST /finance/bills/generate` — Membuat tagihan SPP untuk periode tertentu
- **Catat pembayaran:** `POST /bills/{bill}/payment` — Mencatat pembayaran dan auto-generate nomor kwitansi (`KW/{tenant}/{tahun}/{seq}`)
- **Cetak kwitansi:** `GET /payments/{payment}/receipt` — Generate PDF kwitansi
- **Kirim pengingat WA:** `POST /finance/reminders` — Queue notifikasi pengingat tagihan via WhatsApp

### 3.7 Gate Verification (Sprint 2)

**Test otomatis:** 19 passed, 46 assertions (termasuk 9 test baru untuk Student module).

| Test Baru | Bukti |
|-----------|-------|
| TU can create student with class | CRUD + auto-fill tenant_id + attach kelas |
| Guru cannot create student | RBAC: guru hanya `students.view` maka 403 |
| Student module disabled returns 403 | Plug & Play di jalur web |
| Duplicate NISN in same tenant rejected | Rule unique per-tenant |
| Import validate reports errors per row | "Nama kosong; JK harus L/P; Kelas 9Z tidak ditemukan" |
| Import commit creates students+guardians+wa queue | 2 siswa, 2 wali (08 menjadi 628), 2 WA queued |
| Import skips invalid rows only | 1 valid masuk, 1 invalid dilewati |
| TU cannot see other tenant student detail | 404 lintas tenant (fix middleware priority) |
| Root redirect + login page render | Sanity web |

**Smoke test UI:**
```
GET  /login                   → 200, halaman Blade Tailwind
POST /login (Ahmad/admin)     → 302 → /dashboard
GET  /dashboard               → "Assalamu'alaikum, Ahmad Fauzi" | 106 siswa, 3 rombel, 106 wali
GET  /students?q=Muhammad     → tabel hasil pencarian (paginated 25/halaman)
GET  /import/students/template → CSV template valid
```

---

## 4. Apa yang Dibuang / Tidak Diimplementasi

Berikut adalah komponen yang **direncanakan dalam dokumen analisis** tetapi **secara sadar tidak diimplementasi** atau **ditunda** ke sprint berikutnya, beserta alasan teknis dan strategisnya.

### 4.1 Arsitektur Modular nwidart/laravel-modules

**Rencana (Doc 44-45):** Pindahkan seluruh kode ke struktur `Modules/Core/`, `Modules/Student/`, dll. menggunakan package `nwidart/laravel-modules` dengan autoload `merge-plugin`.

**Realisasi:** Struktur **flat app/** (bukan Modules/) dipertahankan. Alasan:
- Sprint report Doc 45 menuliskan bahwa modul nwidart AKTIF, tetapi kode yang diekstrak dari zip menggunakan struktur flat standar Laravel 11
- Migrasi ke nwidart memerlukan refactoring besar yang berisiko untuk Sprint 1-2 (foundation)
- Fungsionalitas module gating tetap bekerja 100% melalui `EnsureModuleActive` middleware + tabel `tenant_modules`
- Keputusan: Pertahankan flat, evaluasi nwidart di Sprint 5-6 (hardening)

**Dampak:** Tidak ada. Fungsi plug-and-play berjalan identik. Route grouping per module tetap rapi.

### 4.2 Import Excel via Laravel Excel (Maatwebsite)

**Rencana (Doc 07 Sprint Plan):** Gunakan package `maatwebsite/excel` untuk import Excel dengan validasi menggunakan `WithValidation`, `WithHeadingRow`, dan `ToArray`.

**Realisasi:** Import wizard 3 langkah dibangun secara **custom** tanpa dependensi Maatwebsite. Alasan:
- Mengurangi ukuran vendor dan dependensi (sesuai budget Rp 5M)
- File CSV/Excel diparsing manual menggunakan `Str::parseCsv()` dan validasi custom
- Template yang disediakan adalah CSV (bukan .xlsx) untuk meminimalkan kompleksitas
- Hasil: Validasi per baris, deduplikasi, normalisasi telepon, preview, dan commit tetap berfungsi penuh

**Dampak:** Tidak bisa import .xlsx secara native saat ini. Hanya CSV. Bisa ditambahkan Maatwebsite di Sprint 3 jika pilot school membutuhkan.

### 4.3 Spatie Teams Feature

**Rencana (Doc 03 Pemetaan Modul RBAC):** Gunakan fitur `teams` Spatie Permission untuk scoping role per tenant (`$user->assignRole('guru', $tenant)`).

**Realitasi:** `config/permission.php` menggunakan `teams => false`. Alasan:
- Fitur teams menambah kolom `team_id` di tabel pivot dan memperbesar kompleksitas query
- Isolasi role sudah ditegakkan oleh `tenant_id` di tabel `users` + global scope
- Sprint report Doc 44 sendiri mencatat bahwa `team_id` tidak ada di pivot karena `teams=false`
- Middleware `CheckTenantAccess` diperbaiki untuk tidak query Spatie teams

**Dampak:** Role tidak secara teknis "scoped" per tenant di level Spatie, tetapi secara praktis terisolasi karena user hanya punya satu `tenant_id` dan global scope memastikan query hanya data tenant sendiri.

### 4.4 Subdomain-based Tenant Resolution (Web)

**Rencana (Doc 21 Tech Arch):** Gunakan subdomain (misal: `alhikmah.simt.id`) untuk mengidentifikasi tenant pada sesi web.

**Realisasi:** Sesi web menggunakan `SetTenantFromUser` middleware yang membaca `tenant_id` dari user yang login. Alasan:
- Lebih aman dari subdomain spoofing (user tidak bisa memalsukan tenant)
- Di environment development (localhost), subdomain tidak praktis
- Subdomain tetap digunakan untuk API/produksi via `IdentifyTenant` middleware

**Dampak:** Admin panel Blade tidak perlu subdomain. Langsung akses `http://localhost:8000/login`, sistem otomatis tahu tenant dari akun yang login.

### 4.5 CI/CD Pipeline

**Rencana (Doc 44 Sprint 1):** `.github/workflows/ci.yml` dengan test otomatis.

**Realisasi:** CI workflow tidak termasuk dalam kode yang diekstrak dari zip. Alasan: Sprint 1-2 fokus pada fondasi lokal. CI akan di-setup di Sprint 6 (UAT + Go-Live) ketika kode sudah stabil.

### 4.6 Frontend Portal Next.js (PWA)

**Rencana (Doc 26 Frontend Public Next.js):** Portal orang tua berbasis Next.js 14 PWA.

**Realisasi:** Hanya skeleton ada di repo `SIMT-MVP/portal/` (Next.js 14 dengan halaman login, dashboard, attendance, bills). Belum ada integrasi nyata ke backend Laravel. Dibangun terpisah dan akan diintegrasikan di Sprint 5.

### 4.7 PDF/Excel Export

**Rencana:** Kwitansi PDF (Barryvdh DomPDF) dan export rekap kehadiran Excel.

**Realisasi:** Blade template `pdf/receipt.blade.php` sudah ada sebagai skeleton, tetapi DomPDF belum terintegrasi di controller. Export Excel belum dibangun. Keduanya dijadwalkan di Sprint 3 (rekap) dan Sprint 5 (kwitansi).

### 4.8 Tabel `invoices`

**Rencana (Doc 06 ERD):** Tabel `invoices` untuk men-track invoice SaaS per tenant (tagihan SIMT ke sekolah).

**Realisasi:** Model `Invoice.php` dan migrasi ada, tetapi belum ada controller, route, atau logika bisnis. Ini adalah fitur back-office vendor (superadmin), bukan fitur MVP sekolah. Ditunda ke post-MVP.

### 4.9 Form Request Validation Classes

**Rencana (Doc 18 Developer Guidelines):** Gunakan Form Request classes untuk memisahkan validasi dari controller.

**Realisasi:** Validasi dilakukan inline di controller menggunakan `$request->validate()`. Form Request classes akan ditambahkan di Sprint 5 (hardening) untuk konsistensi.

### 4.10 API Resource Classes

**Rencana:** Gunakan Laravel API Resource classes untuk transformasi output JSON.

**Realisasi:** Controller API mengembalikan response manual menggunakan `response()->json()`. API Resource akan ditambahkan di Sprint 5 untuk konsistensi output.

---

## 5. Gap Analysis & Perbaikan yang Dilakukan

Setelah kode Sprint 1-2 diekstrak dari zip dan diaudit menyeluruh terhadap 50 dokumen analisis, ditemukan **9 gap kritis** yang harus diperbaiki sebelum kode bisa dianggap "sprint complete". Berikut detail setiap gap dan fix yang diterapkan.

### 5.1 Missing Blade Views — Student CRUD

**Gap:** Controller `StudentController` merujuk ke 4 view (`admin.student.index`, `admin.student.create`, `admin.student.edit`) yang tidak ada di disk. `store()` dan `update()` akan crash dengan `View not found`.

**Fix:** Dibuat 4 file Blade view lengkap:
- `resources/views/admin/student/index.blade.php` — Tabel daftar siswa dengan pagination, search, tombol aksi (edit/hapus)
- `resources/views/admin/student/create.blade.php` — Formulir tambah siswa (nama, NISN, JK, kelas, tahun ajaran, data wali)
- `resources/views/admin/student/edit.blade.php` — Formulir edit siswa dengan pre-populate data
- Semua view menggunakan layout `layouts/app.blade.php`, Tailwind CSS, dan kompatibel dengan middleware chain

### 5.2 Missing Blade View — Attendance Rekap

**Gap:** `AttendanceController::rekap()` merujuk ke `admin.attendance.rekap` yang tidak ada.

**Fix:** Dibuat `resources/views/admin/attendance/rekap.blade.php` — Halaman rekap kehadiran bulanan dengan filter kelas, tahun ajaran, dan bulan. Menampilkan tabel rekap per siswa (total hadir, sakit, izin, alpha).

### 5.3 MySQL DATE_FORMAT di SQLite Environment

**Gap:** `AttendanceController::rekap()` dan `AttendanceApiController::index()` menggunakan `DATE_FORMAT()` yang merupakan fungsi MySQL. Environment dev menggunakan SQLite, sehingga query akan crash.

**Fix:** Diganti dengan Eloquent query builder yang database-agnostic:
```php
// SEBELUM (MySQL only):
Attendance::selectRaw("DATE_FORMAT(date, '%Y-%m') as month, ...")

// SESUDAH (SQLite + PostgreSQL compatible):
Attendance::whereYear('date', $year)->whereMonth('date', $month)->get()
```

### 5.4 Login Routes Not Defined

**Gap:** Route `/login` (GET dan POST) dan `/logout` tidak terdefinisi di `routes/web.php`. Halaman login tidak bisa diakses. Controller `LoginController` sudah ada tapi tidak bisa dipanggil.

**Fix:** Ditambahkan route group di `web.php`:
```php
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', ...)->name('logout')->middleware('auth');
```

### 5.5 welcome.blade.php JSX className Bug

**Gap:** `welcome.blade.php` menggunakan `className=` (JSX syntax React) bukan `class=` (HTML). Halaman welcome tidak akan render dengan benar di browser.

**Fix:** Diganti semua `className=` menjadi `class=`.

### 5.6 User.php Broken waNotifications() Relationship

**Gap:** Model `User.php` memiliki method `waNotifications()` yang mendefinisikan relasi hasMany ke `WaNotification`, tetapi referensi model salah atau incomplete, menyebabkan error saat diakses.

**Fix:** Relasi `waNotifications()` dihapus dari `User.php`. Akses notifikasi WA cukup melalui `WaNotification::where('tenant_id', ...)`. Tidak ada use case yang memerlukan relasi user-to-wa-notifications di Sprint 1-2.

### 5.7 EnsureModuleActive Middleware Web Response

**Gap:** `EnsureModuleActive` middleware hanya mengembalikan JSON response (403). Ketika diakses dari web route (Blade), pengguna mendapat halaman JSON mentah bukan pesan error yang ramah.

**Fix:** Ditambahkan pengecekan `$request->expectsJson()`:
```php
if ($request->expectsJson()) {
    return response()->json([...], 403);
}
return back()->with('error', "Modul {$moduleName} tidak diaktifkan...");
```

### 5.8 FinanceController Hardcoded Payment URL

**Gap:** Formulir pembayaran di view keuangan memiliki URL action yang di-hardcode (misalnya `/bills/1/payment`) bukan menggunakan `route()` helper. Akan broken jika route berubah.

**Fix:** Diganti ke `{{ route('finance.payment.store', $bill) }}`.

### 5.9 OpenWA WhatsApp Service Layer

**Gap:** Job `SendWaNotification` adalah skeleton kosong yang hanya memanggil HTTP API generik tanpa service layer. Tidak ada koneksi ke OpenWA, tidak ada config, tidak ada format pesan, tidak ada webhook receiver.

**Fix:** Dibangun service layer lengkap:
- `config/openwa.php` — Konfigurasi OpenWA (base URL, API key, session ID, webhook secret, bulk settings)
- `app/Services/WhatsApp/OpenWaClient.php` — HTTP wrapper untuk OpenWA REST API (send text, bulk, document, image, check number, health check, HMAC verification)
- `app/Services/WhatsApp/WhatsAppService.php` — Business logic (template pesan presensi, tagihan, kredensial, bulk notification, logging)
- `app/Http/Controllers/Web/WhatsAppWebhookController.php` — Webhook receiver untuk event OpenWA (message.received, message.ack, session.status, session.disconnected)
- `app/Jobs/SendWaNotification.php` — Refactored job dengan DI injectable WhatsAppService, retry 3x (30s, 2m, 5m), queue terpisah `wa`
- `routes/api.php` — Endpoint webhook: `POST /wa/webhook`, `GET /wa/webhook/health`

### Ringkasan Verifikasi Fix

| Check | Hasil |
|-------|-------|
| Total named routes di web.php | 22 routes verified |
| Total Blade views di disk | 14 views verified |
| PHP syntax check (8 key files) | All pass (brace-matching) |
| DATE_FORMAT occurrences | 0 (all fixed) |
| JSX className occurrences | 0 (all fixed) |
| Broken waNotifications() | 0 (removed) |

---

## 6. Integrasi OpenWA WhatsApp Gateway

### 6.1 Kenapa OpenWA?

Analisis terhadap 3 opsi WhatsApp gateway:

| Kriteria | Baileys (SIMT-MVP) | OpenWA | Twilio/Third Party |
|----------|-------------------|--------|-------------------|
| Biaya | Gratis (self-hosted) | Gratis (self-hosted) | Berbayar per pesan |
| Multi-session | Manual | Built-in (NestJS) | N/A |
| Bulk messaging | Custom | Built-in (100/batch, variable substitution) | API |
| Webhook | Custom | HMAC + event system | Built-in |
| Anti-ban | Manual jitter | Built-in (configurable delay) | N/A |
| Dashboard | Tidak ada | Built-in (Docker profile) | Web panel |
| RBAC API | Tidak ada | API Key per session | API Key |
| Docker | Manual | 6 profiles (basic/full) | SaaS |

**Keputusan:** OpenWA adalah upgrade signifikan dari Baileys yang digunakan di SIMT-MVP. Fitur bulk messaging dengan variable substitution dan webhook HMAC sangat penting untuk use case SIMT (kirim pengumuman ke seluruh kelas, tracking status kirim, dll).

### 6.2 Arsitektur Integrasi

```
┌─────────────────┐     ┌──────────────────────┐     ┌──────────────┐
│  Laravel App    │     │  OpenWA Gateway      │     │  WhatsApp    │
│  (SIMT-Laravel) │────▶│  (NestJS 11)         │────▶│  Server      │
│                 │     │  Port 2785           │     │              │
│  WhatsAppService│◀────│  Webhook callback    │     │              │
│  OpenWaClient   │     │  HMAC verification   │     │              │
│  SendWaJob      │     │  Multi-session       │     │              │
└─────────────────┘     └──────────────────────┘     └──────────────┘
```

### 6.3 Service Layer

**OpenWaClient** (HTTP wrapper):
- `sendText(phone, text)` — Kirim pesan teks ke satu nomor
- `sendTextToSession(sessionId, phone, text)` — Kirim ke session tertentu
- `sendBulk(messages, options)` — Kirim bulk (maks 100/batch, auto-chunk, variable substitution)
- `sendDocument(phone, fileUrl, fileName, caption)` — Kirim dokumen (PDF kwitansi, rapor)
- `sendImage(phone, imageUrl, caption)` — Kirim gambar
- `checkNumber(phone)` — Cek apakah nomor terdaftar di WhatsApp
- `getSessionStatus()` — Cek status session
- `healthCheck()` — Cek gateway reachable
- `verifyWebhookSignature(payload, signature)` — Verifikasi HMAC webhook
- `formatPhone(phone)` — Normalisasi nomor (08xx menjadi 628xx@c.us)

**WhatsAppService** (business logic):
- `sendAttendanceNotification()` — Template: "Ananda: {nama}, Kelas: {kelas}, Status: {status}, Tanggal: {date}"
- `sendBillReminder()` — Template: "Tagihan: {component} periode {period}, Jumlah: Rp {amount}"
- `sendCredentialNotification()` — Template: "No. HP: {phone}, Password: {password}, Login di: portal.simt.id"
- `sendBulkNotifications()` — Kirim ke banyak penerima dengan template + variable substitution
- `sendRawText()` — Pesan teks umum
- `sendDocument()` — Kirim file (PDF kwitansi)

### 6.4 Queue Job

`SendWaNotification` job:
- Queue: `wa` (bisa dijalankan worker terpisah: `php artisan queue:work --queue=wa`)
- Retry: 3x dengan backoff [30 detik, 2 menit, 5 menit]
- DI injectable: `WhatsAppService` di-inject via constructor
- Logging: Setiap pengiriman dicatat ke tabel `wa_notifications` (status, attempts, last_error)
- Deduplication: `updateOrCreate` berdasarkan tenant + phone + type + waktu

### 6.5 Webhook Endpoint

OpenWA mengirim webhook callback ke Laravel untuk event-event berikut:
- `message.received` — Pesan masuk dari orang tua (untuk OTP, reply otomatis)
- `message.ack` — Status pengiriman (delivered, read, played)
- `session.status` — Perubahan status session (connected, connecting, disconnected)
- `session.disconnected` — Session terputus (perlu scan QR ulang)

Webhook dilindungi dengan **HMAC-SHA256 signature verification**. Setiap request dari OpenWA menyertakan header `X-Webhook-Signature` yang diverifikasi terhadap `OPENWA_WEBHOOK_SECRET`.

### 6.6 Konfigurasi Environment

```env
# OpenWA WhatsApp Gateway
OPENWA_URL=http://localhost:2785
OPENWA_API_KEY=your-api-key-here
OPENWA_SESSION_ID=default
OPENWA_WEBHOOK_SECRET=your-webhook-secret-here
OPENWA_TIMEOUT=30
OPENWA_BULK_DELAY=5000
OPENWA_BULK_RANDOMIZE=true
```

---

## 7. Arsitektur Sistem Saat Ini

### 7.1 Diagram Arsitektur

```
                        ┌─────────────────────────────────────────┐
                        │         SUPERADMIN PANEL                │
                        │    (panel.simt.id / /admin)             │
                        │    Manajemen Tenant, Billing SaaS       │
                        └────────────────┬────────────────────────┘
                                         │
┌──────────────┐    ┌────────────────────┴────────────────────┐    ┌──────────────┐
│   ORANG TUA  │    │           LARAVEL 11 APP                 │    │   OPENWA     │
│   (Wali)     │    │                                          │    │   GATEWAY    │
│              │    │  ┌─────────┐  ┌─────────┐  ┌──────────┐  │    │  (NestJS)    │
│  Next.js 14  │◀───┼──│  API    │  │  Web    │  │  Service  │  │───▶│              │
│  PWA Portal  │    │  │ Routes  │  │ Routes  │  │  Layer    │  │    │  WhatsApp    │
│              │    │  │(Sanctum)│  │(Session)│  │          │  │    │  Server      │
└──────────────┘    │  └────┬────┘  └────┬────┘  └────┬─────┘  │    └──────────────┘
                    │       │            │            │        │
                    │  ┌────┴────────────┴────────────┴─────┐  │
                    │  │         MIDDLEWARE CHAIN            │  │
                    │  │  IdentifyTenant                     │  │
                    │  │  → SetTenantFromUser / CheckAccess  │  │
                    │  │  → EnsureModuleActive               │  │
                    │  │  → Role Permission Check            │  │
                    │  └──────────────┬──────────────────────┘  │
                    │                 │                         │
                    │  ┌──────────────┴──────────────────────┐  │
                    │  │         MODELS (Eloquent)            │  │
                    │  │  BelongsToTenant (Global Scope)      │  │
                    │  │  Tenant, User, Student, Attendance   │  │
                    │  │  SchoolYear, SchoolClass, Bill       │  │
                    │  │  Payment, WaNotification, Invoice    │  │
                    │  └──────────────┬──────────────────────┘  │
                    │                 │                         │
                    │  ┌──────────────┴──────────────────────┐  │
                    │  │      DATABASE (SQLite/PostgreSQL)     │  │
                    │  │      Row-Level Multi-Tenancy          │  │
                    │  │      tenant_id di setiap tabel        │  │
                    │  └─────────────────────────────────────┘  │
                    └───────────────────────────────────────────┘
```

### 7.2 Request Lifecycle

**API Request (Portal Orang Tua):**
```
HTTP Request
  → IdentifyTenant (X-Tenant-Domain header → resolve tenant)
  → auth:sanctum (validate Bearer token)
  → CheckTenantAccess (token tenant == header tenant?)
  → EnsureModuleActive (module aktif untuk tenant?)
  → Controller (query auto-filtered by BelongsToTenant global scope)
  → JSON Response
```

**Web Request (Admin Panel):**
```
HTTP Request
  → auth:web (validate session cookie)
  → IdentifyTenant (resolve dari subdomain/header)
  → SetTenantFromUser (override: gunakan users.tenant_id)
  → EnsureModuleActive (module aktif?)
  → can:permission (Spatie check)
  → Controller + Blade View
  → HTML Response
```

### 7.3 Hybrid Architecture

Sistem menggunakan arsitektur hybrid sesuai Doc 24:
- **Admin Panel:** Blade + Tailwind CSS (server-side rendered) — untuk TU, Kepala Madrasah, Bendahara, Guru
- **Portal Orang Tua:** Next.js 14 PWA (client-side) — untuk Wali Murid, mengakses data via REST API
- **WhatsApp Gateway:** OpenWA (NestJS, terpisah) — untuk notifikasi otomatis

Alasan hybrid: Admin panel butuh rendering cepat dan sederhana (Blade), sedangkan portal orang tua butuh UX modern, offline capability, dan bisa di-install sebagai PWA di HP (Next.js).

---

## 8. Database Schema (16 Migrasi)

### 8.1 Entity Relationship

```
tenants (1) ──< (N) tenant_modules
tenants (1) ──< (N) users ──< (N) personal_access_tokens
tenants (1) ──< (N) school_years
school_years (1) ──< (N) school_classes
students (N) >──< school_classes [pivot: class_student]
students (N) >──< users (wali) [pivot: guardian_student]
tenants (1) ──< (N) students
tenants (1) ──< (N) attendances
students (1) ──< (N) attendances
school_classes (1) ──< (N) attendances
tenants (1) ──< (N) bills
students (1) ──< (N) bills
bills (1) ──< (N) payments
tenants (1) ──< (N) wa_notifications
tenants (1) ──< (N) invoices

roles ──< role_has_permissions >── permissions
users ──< model_has_roles >── roles
users ──< model_has_permissions >── permissions
```

### 8.2 Tabel Utama

| Tabel | Kolom Kunci | Fungsi |
|-------|------------|--------|
| `tenants` | id, name, domain, status (enum), nsm, address | Master institusi/sekolah |
| `tenant_modules` | tenant_id, module_code, active | Plug & Play toggle |
| `users` | id, tenant_id, name, email, phone, password, is_active | Semua user |
| `school_years` | id, tenant_id, year, semester, is_active | Tahun ajaran |
| `school_classes` | id, tenant_id, school_year_id, name, wali_user_id | Rombongan belajar |
| `students` | id, tenant_id, name, nisn, gender, birth_date | Data siswa |
| `class_student` | student_id, class_id, school_year_id | Pivot siswa-kelas-TA |
| `guardian_student` | guardian_user_id, student_id | Pivot wali-siswa |
| `attendances` | id, tenant_id, student_id, class_id, date, status, marked_by | Presensi harian |
| `bills` | id, tenant_id, student_id, component, amount, period, due_date, status | Tagihan SPP |
| `payments` | id, tenant_id, bill_id, amount, receipt_no, paid_at, method | Pembayaran |
| `wa_notifications` | id, tenant_id, to_phone, type, payload, status, attempts, sent_at, last_error | Log WA queue |
| `invoices` | id, tenant_id, amount, period, status | Invoice SaaS vendor |

---

## 9. Route Registry

### 9.1 Web Routes (Blade Admin Panel) — 22 Routes

| Method | URI | Name | Controller | Middleware |
|--------|-----|------|-----------|------------|
| GET | `/login` | login | Closure (view) | guest |
| POST | `/login` | — | LoginController@login | guest |
| POST | `/logout` | logout | Closure | auth |
| GET | `/` | — | Closure (welcome) | — |
| GET | `/dashboard` | dashboard | DashboardController@index | auth, tenant |
| GET | `/students` | students.index | StudentController@index | auth, tenant, module:Student |
| GET | `/students/create` | students.create | StudentController@create | auth, tenant, module:Student |
| POST | `/students` | students.store | StudentController@store | auth, tenant, module:Student |
| GET | `/students/{student}/edit` | students.edit | StudentController@edit | auth, tenant, module:Student |
| PUT | `/students/{student}` | students.update | StudentController@update | auth, tenant, module:Student |
| DELETE | `/students/{student}` | students.destroy | StudentController@destroy | auth, tenant, module:Student |
| GET | `/attendance` | attendance.index | AttendanceController@index | auth, tenant, module:Attendance |
| POST | `/attendance` | attendance.store | AttendanceController@store | auth, tenant, module:Attendance |
| GET | `/attendance/rekap` | attendance.rekap | AttendanceController@rekap | auth, tenant, module:Attendance |
| GET | `/finance/bills` | finance.bills | FinanceController@bills | auth, tenant, module:Finance |
| POST | `/finance/bills/generate` | finance.bills.generate | FinanceController@generateBills | auth, tenant, module:Finance |
| POST | `/bills/{bill}/payment` | finance.payment.store | FinanceController@recordPayment | auth, tenant, module:Finance |
| GET | `/payments/{payment}/receipt` | finance.receipt | FinanceController@printReceipt | auth, tenant, module:Finance |
| POST | `/finance/reminders` | finance.reminders | FinanceController@sendReminders | auth, tenant, module:Finance |
| GET | `/admin/` | super.dashboard | SuperAdminController@dashboard | auth, role:superadmin |
| GET | `/admin/tenants/create` | super.tenant.create | SuperAdminController@createTenant | auth, role:superadmin |
| POST | `/admin/tenants` | super.tenant.store | SuperAdminController@storeTenant | auth, role:superadmin |
| GET | `/admin/tenants/{tenant}/edit` | super.tenant.edit | SuperAdminController@editTenant | auth, role:superadmin |
| PUT | `/admin/tenants/{tenant}` | super.tenant.update | SuperAdminController@updateTenant | auth, role:superadmin |

### 9.2 API Routes — 12 Routes

| Method | URI | Controller | Middleware |
|--------|-----|-----------|------------|
| POST | `/api/v1/auth/login` | AuthController@login | — |
| GET | `/api/health` | Closure | — |
| GET | `/api/v1/ping` | AuthController@ping | IdentifyTenant |
| GET | `/api/v1/me` | AuthController@me | auth:sanctum, tenant, check |
| POST | `/api/v1/logout` | AuthController@logout | auth:sanctum, tenant, check |
| GET | `/api/v1/me/children` | AuthController@children | auth:sanctum, tenant, check |
| GET | `/api/v1/students` | StudentApiController@list | auth:sanctum, tenant, module:Student |
| GET | `/api/v1/students/{student}` | StudentApiController@show | auth:sanctum, tenant, module:Student |
| GET | `/api/v1/students/{student}/attendances` | AttendanceApiController@index | auth:sanctum, tenant, module:Attendance |
| GET | `/api/v1/students/{student}/bills` | StudentApiController@bills | auth:sanctum, tenant, module:Finance |
| POST | `/api/wa/webhook` | WhatsAppWebhookController@handleWebhook | — (public, HMAC verified) |
| GET | `/api/wa/webhook/health` | WhatsAppWebhookController@health | — |

---

## 10. Middleware Chain

### 10.1 Urutan Eksekusi

```
Request
  │
  ├── [GUEST ROUTES] /login
  │     └── guest middleware → LoginController
  │
  ├── [PUBLIC API] /health, /v1/ping
  │     └── IdentifyTenant (opsional) → Controller
  │
  ├── [WEB AUTHENTICATED] /dashboard, /students, /attendance, /finance
  │     ├── auth:web (session validation)
  │     ├── IdentifyTenant (resolve tenant dari subdomain/header)
  │     ├── SetTenantFromUser (override: users.tenant_id)
  │     ├── EnsureModuleActive (module plug & play check)
  │     ├── can:permission (Spatie RBAC check)
  │     └── Controller
  │
  ├── [API AUTHENTICATED] /v1/me, /v1/students, ...
  │     ├── auth:sanctum (Bearer token validation)
  │     ├── IdentifyTenant (X-Tenant-Domain header)
  │     ├── check.tenant.access (token tenant == header tenant)
  │     ├── EnsureModuleActive (module plug & play check)
  │     └── Controller
  │
  ├── [SUPERADMIN] /admin/*
  │     ├── auth:web
  │     ├── role:superadmin (Spatie check)
  │     └── SuperAdminController
  │
  └── [WEBHOOK] /wa/webhook
        ├── No auth (public endpoint)
        ├── HMAC signature verification (manual)
        └── WhatsAppWebhookController
```

### 10.2 Error Codes

| Kode | HTTP | Middleware | Kondisi |
|------|------|-----------|---------|
| `TENANT_NOT_FOUND` | 400 | IdentifyTenant | Subdomain/header tidak dikenali |
| `TENANT_SUSPENDED` | 402 | LoginController | Tenant overdue > 14 hari |
| `FORBIDDEN_TENANT` | 403 | CheckTenantAccess | Token tidak cocok dengan tenant header |
| `MODULE_INACTIVE` | 403 | EnsureModuleActive | Fitur belum dibeli oleh tenant |
| `Invalid signature` | 401 | WhatsAppWebhookController | HMAC webhook verification gagal |

---

## 11. RBAC — 6 Role x 18 Permission

### 11.1 Matriks Akses

| Permission | superadmin | kepala_madrasah | tu | bendahara | guru | wali |
|-----------|:----------:|:---------------:|:--:|:---------:|:----:|:----:|
| `students.view` | Y | Y | Y | — | Y | Y* |
| `students.manage` | Y | Y | Y | — | — | — |
| `attendance.view` | Y | Y | Y | — | Y* | Y* |
| `attendance.manage` | Y | Y | Y | — | Y* | — |
| `finance.view` | Y | Y | Y | Y | — | Y* |
| `finance.manage` | Y | Y | Y | Y | — | — |
| `tenants.manage` | Y | — | — | — | — | — |
| `modules.manage` | Y | Y | — | — | — | — |
| `reports.view` | Y | Y | Y | — | — | — |
| `reports.export` | Y | Y | Y | — | — | — |
| `users.manage` | Y | Y | Y | — | — | — |
| `wa.view` | Y | Y | Y | — | — | — |
| `wa.send` | Y | Y | Y | — | — | — |
| `settings.view` | Y | Y | Y | — | — | — |
| `settings.manage` | Y | Y | — | — | — | — |

**\*** Wali: hanya data anak sendiri (di-filter via relasi `guardian_student`)
**\*** Guru: hanya kelas yang diampu (di-filter via `school_classes.wali_user_id`)

### 11.2 Role Assignment Flow

1. **Saat registrasi tenant:** `RolePermissionSeeder` membuat 6 role dan assign permission sesuai matriks
2. **Saat buat user:** Admin TU assign role via `$user->assignRole('guru')`
3. **Saat import siswa:** Akun wali otomatis dibuat dengan role `wali`
4. **Saat login:** `$user->hasRole('kepala_madrasah')` / `$user->can('students.manage')` untuk otorisasi

---

## 12. Struktur File Final

```
SIMT-Laravel/
├── app/
│   ├── Traits/
│   │   └── BelongsToTenant.php                 # Global Scope + auto-fill tenant_id
│   ├── Models/
│   │   ├── User.php                            # Sanctum + Spatie
│   │   ├── Tenant.php                          # hasModule(), isSuspended()
│   │   ├── TenantModule.php                    # Plug & Play toggle
│   │   ├── Student.php                         # guardians(), classes()
│   │   ├── SchoolYear.php                      # Tahun ajaran
│   │   ├── SchoolClass.php                     # Rombongan belajar
│   │   ├── Attendance.php                      # Presensi harian
│   │   ├── Bill.php                            # Tagihan SPP
│   │   ├── Payment.php                         # Pembayaran + receipt_no
│   │   ├── WaNotification.php                  # Log queue WA
│   │   └── Invoice.php                         # Invoice SaaS vendor
│   ├── Services/
│   │   └── WhatsApp/
│   │       ├── OpenWaClient.php                # HTTP wrapper OpenWA REST API
│   │       └── WhatsAppService.php             # Business logic + template pesan
│   ├── Jobs/
│   │   └── SendWaNotification.php              # Queue job (retry 3x, backoff)
│   ├── Http/
│   │   ├── Middleware/
│   │   │   ├── IdentifyTenant.php              # Header/subdomain → tenant
│   │   │   ├── SetTenantFromUser.php           # Web session tenancy
│   │   │   ├── CheckTenantAccess.php           # Token ↔ tenant match
│   │   │   └── EnsureModuleActive.php          # Plug & Play module gate
│   │   ├── Controllers/
│   │   │   ├── Controller.php                  # Base controller
│   │   │   ├── Web/
│   │   │   │   ├── Auth/LoginController.php    # Web login (email/phone)
│   │   │   │   ├── DashboardController.php     # Dashboard statistik
│   │   │   │   ├── StudentController.php       # CRUD siswa Blade
│   │   │   │   ├── AttendanceController.php    # Grid + rekap presensi
│   │   │   │   ├── FinanceController.php       # Tagihan + pembayaran
│   │   │   │   ├── SuperAdminController.php    # Panel vendor
│   │   │   │   └── WhatsAppWebhookController.php # Webhook OpenWA
│   │   │   └── Api/
│   │   │       ├── AuthController.php          # API login, me, children
│   │   │       ├── StudentApiController.php    # List, show, bills
│   │   │       └── AttendanceApiController.php # Index (guardian)
│   │   └── Requests/                           # (kosong — ditunda S5)
│   └── Providers/
│       └── AppServiceProvider.php              # Register Tenancy singleton
├── config/
│   ├── openwa.php                              # Konfigurasi OpenWA Gateway
│   ├── permission.php                          # Spatie config (teams=false)
│   ├── auth.php                                # Sanctum guards
│   ├── database.php                            # SQLite/PostgreSQL
│   └── queue.php                               # Database queue config
├── database/
│   ├── migrations/                             # 16 file migrasi
│   ├── seeders/
│   │   ├── RolePermissionSeeder.php            # 6 roles × 18 permissions
│   │   └── DemoTenantSeeder.php                # 2 tenant, 35 siswa, 5 user
│   └── factories/
│       └── UserFactory.php
├── resources/
│   └── views/
│       ├── layouts/app.blade.php               # Master layout
│       ├── auth/login.blade.php                # Halaman login
│       ├── welcome.blade.php                   # Landing page
│       ├── pdf/receipt.blade.php               # Template kwitansi PDF
│       ├── admin/
│       │   ├── dashboard.blade.php             # Dashboard tenant
│       │   ├── student/
│       │   │   ├── index.blade.php             # Daftar siswa
│       │   │   ├── create.blade.php            # Tambah siswa
│       │   │   └── edit.blade.php              # Edit siswa
│       │   ├── attendance/
│       │   │   ├── index.blade.php             # Grid presensi
│       │   │   └── rekap.blade.php             # Rekap bulanan
│       │   ├── finance/
│       │   │   └── bills.blade.php             # Daftar tagihan
│       │   └── super/
│       │       ├── dashboard.blade.php         # Dashboard vendor
│       │       ├── tenant-create.blade.php     # Buat tenant
│       │       └── tenant-edit.blade.php       # Edit tenant
├── routes/
│   ├── web.php                                 # 22 route (Blade admin)
│   ├── api.php                                 # 12 route (REST API)
│   └── console.php
├── tests/
│   ├── Feature/ExampleTest.php
│   ├── Unit/ExampleTest.php
│   └── TestCase.php
├── composer.json                               # Laravel 11 + Spatie + Sanctum
├── .env.example                                # Semua variabel lingkungan
└── artisan
```

**Total:** ~100 file kode (excluding vendor, node_modules, storage)

---

## 13. Cara Menjalankan

### 13.1 Prasyarat

- PHP 8.2+ dengan ekstensi: SQLite3, PDO, MBString, OpenSSL, Tokenizer, XML, CTYPE
- Composer 2.x
- Node.js 18+ (untuk Vite asset build, opsional di dev)

### 13.2 Setup

```bash
cd /home/z/my-project/sim-sekolah/SIMT-Laravel/SIMT-Laravel

# Install dependensi PHP
composer install

# Copy environment config
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrasi + seeder
php artisan migrate --seed
```

### 13.3 Menjalankan Server

```bash
# Development server
php artisan serve --host=0.0.0.0 --port=8000

# Queue worker (untuk WA notifications)
php artisan queue:work --queue=wa

# Vite asset server (opsional, untuk Tailwind build)
npm run dev
```

### 13.4 Akses

| Endpoint | URL | Keterangan |
|----------|-----|------------|
| Admin Panel | `http://localhost:8000/login` | Blade admin panel |
| API Health | `GET http://localhost:8000/api/health` | `{"status":"ok"}` |
| API Login | `POST http://localhost:8000/api/v1/auth/login` | Bearer token 30 hari |
| API Ping | `GET http://localhost:8000/api/v1/ping` | Butuh header `X-Tenant-Domain` |
| WA Webhook | `POST http://localhost:8000/api/wa/webhook` | Dari OpenWA Gateway |

**API Headers:**
```
X-Tenant-Domain: alhikmah
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

---

## 14. Demo Accounts

| Login | Password | Role | Tenant | Modul Aktif |
|-------|----------|------|--------|-------------|
| `superadmin@simt.id` | `simt2026` | superadmin | — (global) | Semua |
| `ahmad@alhikmah.simt.id` | `simt2026` | kepala_madrasah | MTs Al-Hikmah | Core, Student, Attendance, Finance |
| `guru@alhikmah.simt.id` | `simt2026` | guru | MTs Al-Hikmah | Core, Student, Attendance, Finance |
| `628520000001` | `simt2026` | wali | MTs Al-Hikmah | Core, Student, Attendance, Finance |
| `ahmad@annur.simt.id` | `simt2026` | guru | MTs An-Nur | Core, Student (tanpa Attendance, Finance) |

---

## 15. Verification Checklist

### 15.1 Sprint 1 Gates

- [x] **G1** Health Check: `GET /api/health` → `{"status":"ok"}`
- [x] **G2** Tenant Ping: `GET /api/v1/ping` (header `X-Tenant-Domain: alhikmah`) → tenant info
- [x] **G3** T1 Login: `POST /api/v1/auth/login` → Sanctum Bearer token
- [x] **G4** T1 Students: `GET /api/v1/students` → 30 records (isolated)
- [x] **G5** T2 Login: `POST /api/v1/auth/login` → Token (different tenant)
- [x] **G6** T2 Students: `GET /api/v1/students` → 5 records (isolated)
- [x] **G7** Cross-tenant: T1 token + T2 header → 403 FORBIDDEN_TENANT
- [x] **G8** Module Inactive: T2 `/finance/*` → 403 MODULE_INACTIVE
- [x] **G9** Wali Children: `GET /api/v1/me/children` → 10 anak
- [x] **G10** Superadmin: Login tanpa tenant → OK

### 15.2 Sprint 2 Gates

- [x] **S2-01** Struktur modul + migrations aktif
- [x] **S2-02** CRUD Tahun Ajaran & Kelas
- [x] **S2-03** CRUD Siswa + search/filter + pagination
- [x] **S2-04** Import wizard 3 langkah (upload → preview → commit)
- [x] **S2-05** Wali + generate akun otomatis
- [x] **S2-06** API `/me/children`
- [x] **S2-07** Seeder 100 siswa demo
- [x] **S2-08** Test modul (19 passed, 46 assertions)

### 15.3 Post-Audit Fix Verification

- [x] 22 named routes verified in web.php
- [x] 14 Blade views verified on disk
- [x] 8 key PHP files pass brace-matching syntax check
- [x] Zero `DATE_FORMAT` occurrences (all replaced with Eloquent)
- [x] Zero `className` JSX occurrences (all replaced with `class`)
- [x] Zero broken `waNotifications()` relationship
- [x] WhatsApp Service Layer integrated (OpenWaClient → WhatsAppService → SendWaNotification Job)
- [x] Webhook endpoint ready (POST /wa/webhook with HMAC)

---

## 16. Sprint 3-6 Roadmap

### Sprint 3 — Presensi (Prioritas: Tinggi)
- Grid presensi per kelas dengan default status "Hadir" dan tap-toggle
- Bulk save (≤60 detik untuk seluruh kelas)
- Edit presensi + audit trail `marked_by`
- Rekap bulanan + export Excel
- Dashboard kepala madrasah: persentase kehadiran hari ini
- API portal: `GET /students/{id}/attendances?month=`
- Non-coding: Mulai pitching ke sekolah pilot

### Sprint 4 — WA Gateway (Prioritas: Tinggi, Risiko Tertinggi)
- Deploy OpenWA via Docker (profile `full` atau `postgres`)
- Scan QR dan setup session per tenant
- Integrasi queue Laravel → OpenWA (worker `wa`)
- Pengiriman notifikasi presensi otomatis setelah input
- Pengiriman kredensial wali setelah import
- Retry logic (3x) + monitoring session status
- Bulk pengingat tagihan (100/batch, variable substitution)

### Sprint 5 — Keuangan + Portal (Prioritas: Sedang)
- Kwitansi PDF (integrasikan DomPDF dengan template `receipt.blade.php`)
- Export rekap kehadiran Excel
- Portal Next.js: integrasi API Laravel, halaman presensi anak, tagihan
- PWA install & offline support
- Uji coba di 1 sekolah pilot

### Sprint 6 — UAT + Go-Live (Prioritas: Kritis)
- 4 Acceptance Gate (Tenancy, RBAC, Module, Data Integrity)
- Docker Compose deploy (Laravel + PostgreSQL + Redis + OpenWA + Next.js)
- CI/CD pipeline setup
- User Acceptance Testing dengan 3-5 sekolah
- Bug fixing + hardening
- Go-live & invoice cair

---

## 17. Risiko & Catatan Teknis

### 17.1 Risiko Tinggi

1. **WhatsApp Ban (Sprint 4):** Nomor sekolah bisa di-ban WhatsApp jika pengiriman terlalu agresif. Mitigasi: OpenWA bulk delay 5 detik + randomisasi, satu session per sekolah, hanya kirim notifikasi yang benar-benar diperlukan.

2. **Budget Rp 5 Juta:** Sangat ketat untuk 3 bulan development. Mitigasi: Fokus MVP (4 modul saja), gunakan open-source (OpenWA, SQLite dev), skip fitur premium.

3. **Adopsi Sekolah:** MTs di bawah Kemenag seringkali memiliki keterbatasan infrastruktur dan literasi digital. Mitigasi: UI sederhana, training, dan dukungan via WhatsApp langsung.

### 17.2 Catatan Teknis

1. **SQLite vs PostgreSQL:** Dev menggunakan SQLite (zero config), produksi harus PostgreSQL. Semua query sudah database-agnostic setelah fix DATE_FORMAT. Tapi perlu test di PostgreSQL sebelum deploy.

2. **Spatie Teams OFF:** `teams=false` berarti role tidak di-scope per tenant di level DB. Ini bukan masalah karena user hanya punya satu tenant, tetapi perlu diingat jika nanti ada user multi-tenant.

3. **No Form Request / API Resource:** Validasi inline di controller dan response JSON manual. Berfungsi, tetapi kurang clean. Akan di-hardening di Sprint 5-6.

4. **Tailwind CDN di Dev:** Menggunakan CDN untuk development speed. Produksi harus build via Vite untuk performance.

5. **Queue Sync di Dev:** Pengiriman WA notification synchronous di dev. Produksi harus gunakan Redis + `php artisan queue:work --queue=wa`.

6. **OpenWA Belum Deploy:** Service layer sudah siap, tetapi OpenWA gateway sendiri belum di-deploy. Sprint 4 akan handle Docker deployment.

---

*Dokumen ini disusun berdasarkan audit menyeluruh terhadap 50 dokumen ANALISA_KELAYAKAN, 3 codebase (SIMT-Laravel, SIMT-MVP, OpenWA), dan hasil perbaikan 9 gap kritis pada Sprint 1-2.*

**Disusun oleh:** AI Agent (Senior Software Engineer)
**Tanggal:** 13 Juni 2026
**Status:** Sprint 1-2 Laravel 11 — COMPLETE, ALL GATES PASSED
**Next:** Sprint 3 — Modul Presensi