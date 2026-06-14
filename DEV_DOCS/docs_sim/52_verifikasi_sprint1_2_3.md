# LAPORAN VERIFIKASI SPRINT 1-2-3 — STATUS FACTUAL
## SIMT MTs — Hasil Pengecekan Workspace

**Tanggal:** 13 Juni 2026  
**Metode:** Verifikasi langsung terhadap kode di workspace + jalankan test + bandingkan dengan dokumentasi (Doc 46/48)  
**Status: 🚨 TIDAK SIAP untuk Sprint selanjutnya — perlu rekonstruksi**

---

## 1. TEMUAN UTAMA

### 🚨 Codebase yang ada di workspace adalah versi Sprint 1-2 ORIGINAL (awal), BUKAN versi rekonstruksi Sprint 3-5 yang didokumentasikan di Doc 46/48.

| Aspek | Doc 46/48 Mengklaim | Fakta di Workspace |
|---|---|---|
| **Laravel version** | Laravel 13 (PHP 8.4) | **Laravel 11** (`"laravel/framework": "^11.31"`) |
| **Tenancy implementation** | Singleton `App\Support\Tenancy` | **TIDAK ADA** — pakai `app()->instance('currentTenant')` langsung |
| **nwidart Modules** | Core, Student, Attendance (3 module) | **TIDAK ADA** — `Modules/` directory kosong |
| **StudentImportService** | Ada (wizard 3 langkah) | **TIDAK ADA** |
| **TenantRoleService** | Ada (6 role per tenant) | **TIDAK ADA** |
| **Test suite** | 18 passed / 45 assertions | **2 passed / 2 assertions** (hanya ExampleTest) |
| **TenantIsolationTest** | 8 test | **TIDAK ADA** |
| **StudentModuleTest** | 9 test | **TIDAK ADA** |
| **PitchingDemoSeeder** | 100 siswa + 100 wali + 100 WA | **TIDAK ADA** — hanya DemoTenantSeeder (35 siswa) |
| **Attendance grid view** | Blade view untuk presensi per kelas | **HANYA index biasa** — bukan grid per kelas |
| **Import Excel wizard** | 3 step: upload → preview → commit | **TIDAK ADA** |
| **WA notification hook** | Otomatis saat presensi | **HANYA Job SendWaNotification** — tidak ada hook |

### Kesimpulan: Versi rekonstruksi yang didokumentasikan di Doc 46/48 **TIDAK PERNAH DI-UPLOAD ke Google Drive** atau **hilang saat workspace reset**.

---

## 2. APA YANG VALID (Sprint 1-2 Original)

### ✅ Berjalan dan Teruji

| # | Komponen | File | Status |
|---|---|---|---|
| 1 | Migrations (11 file) | `database/migrations/` | ✅ `migrate:fresh` berhasil |
| 2 | Models (11 file) | `app/Models/` | ✅ Semua ada |
| 3 | Middleware (4 file) | `app/Http/Middleware/` | ✅ IdentifyTenant, SetTenantFromUser, CheckTenantAccess, EnsureModuleActive |
| 4 | BelongsToTenant Trait | `app/Traits/BelongsToTenant.php` | ✅ Global scope + auto-fill tenant_id |
| 5 | Seeders (3 file) | `database/seeders/` | ✅ RolePermissionSeeder + DemoTenantSeeder |
| 6 | Controllers (9 file) | `app/Http/Controllers/` | ✅ Web (5) + API (3) + base (1) |
| 7 | Blade Views (10 file) | `resources/views/` | ✅ Auth, admin, layouts, pdf |
| 8 | Job SendWaNotification | `app/Jobs/` | ✅ Ada |
| 9 | Routes | `routes/web.php`, `routes/api.php` | ✅ CRUD + API |
| 10 | Composer | `composer.json` | ✅ `composer install` + `php artisan test` = 2 passed |

### Data Demo yang Berhasil Di-seed

| Tabel | Jumlah |
|---|---|
| Tenants | 2 |
| Users | 5 |
| Students | 35 |
| Classes | 4 |
| SchoolYears | 2 |
| Attendances | 30 |
| Bills | 30 |
| Payments | 0 |
| WaNotifications | 0 |
| Invoices | 0 |

### Test yang Lulus

```
PASS  Tests\Unit\ExampleTest
  ✓ that true is true

PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

Tests:    2 passed (2 assertions)
```

---

## 3. APA YANG TIDAK VALID (Harus Ada tapi Tidak Ada)

### 🔴 KRITIS — Bloker untuk Sprint Selanjutnya

| # | Komponen yang Hilang | Dampak | Solusi |
|---|---|---|---|
| 1 | `App\Support\Tenancy.php` (singleton) | Multi-tenant tidak bekerja dengan benar — global scope bocor antar tenant | Buat ulang dari Doc 48 §2.B.1 |
| 2 | `Modules/` directory (Core, Student, Attendance) | Tidak bisa menambah modul secara plug & play | Buat ulang dari Doc 48 §3 |
| 3 | `App\Services\TenantRoleService.php` | Role per tenant tidak bisa diprovisioning | Buat ulang dari Doc 46 §3 |
| 4 | `App\Services\StudentImportService.php` | Import Excel siswa tidak bisa | Buat ulang dari Doc 46 §3 |
| 5 | TenantIsolationTest (8 test) | Isolasi tenant tidak terverifikasi | Buat ulang |
| 6 | StudentModuleTest (9 test) | Modul siswa tidak terverifikasi | Buat ulang |

### 🟡 SEDANG — Diperlukan untuk Sprint 3

| # | Komponen yang Hilang | Dampak | Solusi |
|---|---|---|---|
| 7 | Attendance grid view (per kelas) | Presensi guru tidak bisa 3 klik | Buat ulang |
| 8 | Import Excel wizard (3 step) | TU harus input manual | Buat ulang |
| 9 | PitchingDemoSeeder (100 siswa) | Demo untuk calon client terbatas | Buat ulang |
| 10 | Akun wali otomatis + WA queue | Notifikasi tidak ter-trigger | Buat ulang |

### ℹ️ MINOR — Bisa diperbaiki nanti

| # | Komponen | Catatan |
|---|---|---|
| 11 | Laravel 11 vs Laravel 13 | Tidak bloker — Laravel 11 masih didukung, upgrade bisa ditunda |
| 12 | Tabel `classes` vs `school_classes` | Migration pakai `classes` — reserved word di PostgreSQL tapi OK di SQLite |

---

## 4. REKOMENDASI: DUA JALUR

### Jalur A: Upgrade Codebase yang Ada (RECOMMENDED)

**Estimasi: 6-8 jam**

Mengupgrade codebase Sprint 1-2 original yang sudah ada, menambahkan komponen yang hilang:

1. **Buat `App\Support\Tenancy.php`** — Singleton, bind di AppServiceProvider
2. **Update `BelongsToTenant` trait** — Pakai Tenancy::tenantId() bukan app('currentTenant')
3. **Buat nwidart Modules** — `php artisan module:make Core`, `module:make Student`, `module:make Attendance`
4. **Pindahkan controller ke Modules** — AuthController → Core, StudentController → Student, dll
5. **Buat `TenantRoleService`** — 6 role per tenant
6. **Buat `StudentImportService`** — Import Excel 3 step
7. **Buat `PitchingDemoSeeder`** — 100 siswa + 100 wali + 100 WA
8. **Buat test suite** — TenantIsolationTest + StudentModuleTest
9. **Verifikasi: 18+ test hijau**

**Keuntungan:** Codebase sudah ada, vendor sudah ter-install, database sudah ter-seed.
**Risiko:** Perlu hati-hati saat refactor agar tidak merusak yang sudah jalan.

### Jalur B: Rekonstruksi Total dari Nol

**Estimasi: 12-16 jam**

Membangun ulang seluruh codebase dari dokumentasi (seperti yang dilakukan di Sesi 5):

1. `composer create-project laravel/laravel simt-backend`
2. Install semua packages
3. Buat semua migration, model, middleware dari Doc 48
4. Buat semua module, service, controller
5. Buat semua test
6. Verifikasi

**Keuntungan:** Bersih, sesuai dokumentasi terbaru.
**Risiko:** Waktu lama, bisa ada bug baru.

---

## 5. REKOMENDASI FINAL

### 🎯 Gunakan Jalur A — Upgrade codebase yang ada

Alasan:
1. **Codebase Sprint 1-2 ORIGINAL sudah jalan** — migrations OK, seed OK, 2 test OK
2. **Vendor sudah ter-install** — tidak perlu tunggu composer install
3. **Lebih cepat** — tinggal tambahkan komponen yang hilang, bukan mulai dari nol
4. **Risiko lebih rendah** — yang sudah jalan tidak perlu dibangun ulang

### Langkah Prioritas (Urutan Kerja)

| # | Task | Estimasi | Prioritas |
|---|---|---|---|
| 1 | Buat `App\Support\Tenancy.php` singleton | 30 menit | 🔴 |
| 2 | Update `IdentifyTenant` + `SetTenantFromUser` middleware | 30 menit | 🔴 |
| 3 | Update `BelongsToTenant` trait pakai Tenancy::tenantId() | 15 menit | 🔴 |
| 4 | Buat `App\Services\TenantRoleService.php` | 1 jam | 🔴 |
| 5 | Buat nwidart Modules (Core, Student, Attendance) | 1 jam | 🔴 |
| 6 | Pindahkan controller + views ke Modules | 2 jam | 🟡 |
| 7 | Buat `StudentImportService` + Import wizard | 2 jam | 🟡 |
| 8 | Buat `PitchingDemoSeeder` | 30 menit | 🟡 |
| 9 | Buat TenantIsolationTest (8 test) | 1 jam | 🔴 |
| 10 | Buat StudentModuleTest (9 test) | 1 jam | 🔴 |
| 11 | Verifikasi: 18+ test hijau | 30 menit | 🔴 |
| | **TOTAL ESTIMASI** | **~10 jam** | |

Setelah langkah di atas selesai, **Sprint 1-2 terverifikasi** dan siap lanjut ke Sprint 3 (Presensi UI + Rekap).

---

*Dokumen ini merupakan laporan verifikasi faktual. Semua pengecekan dilakukan langsung terhadap kode di workspace `/home/user/simt-backend/` pada 13 Juni 2026.*
