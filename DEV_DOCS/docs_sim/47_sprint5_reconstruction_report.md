# DEV PROGRESS REPORT — Sesi 5 (Rekonstruksi Sprint 1+2 + Sprint 3 Skeleton)
## SIMT MTs — Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah

**Tanggal:** 12 Juni 2026  
**Status:** ✅ REKONSTRUKSI S1+S2 SELESAI + 18 TEST HIJAU + Sprint 3 skeleton terbangun  
**Dibuat oleh:** AI Agent (Arena.ai)

---

## 1. RINGKASAN EKSEKUTIF

| Milestone | Status |
|---|---|
| Clone Google Drive (89 file dokumen) | ✅ |
| Rekonstruksi `simt-backend/` Sprint 1+2 | ✅ |
| 10 Migrasi → 24 tabel | ✅ |
| Seeders (DemoTenant + PitchingDemo) | ✅ |
| Test suite: **18 passed / 45 assertions** | ✅ |
| Sprint 3 — Modul Attendance (controller + views) | 🟡 Skeleton terbangun |
| `simt-portal/` (Next.js) | ⬜ |

### Bukti Test Hijau

```
Tests:    18 passed (45 assertions)
Duration: 2.03s
```

| Test Suite | Jumlah | Detail |
|---|---|---|
| ExampleTest | 2 | root redirect, sanity |
| TenantIsolationTest | 8 | global scope, autofill, cross-tenant null, dual-role Ahmad, 400/402/403 |
| StudentModuleTest | 8 | CRUD, RBAC, module disabled, dup NISN, import validate/commit/skip, cross-tenant |

---

## 2. BUG DITEMUKAN & DIPERBAIKI SELAMA REKONSTRUKSI

| # | Bug | Perbaikan | Dampak |
|---|---|---|---|
| 1 | `Tenancy` bukan singleton — `app(Tenancy::class)` buat instance baru tiap resolve → global scope tidak pernah memfilter | Daftarkan `Tenancy::class` sebagai singleton di `AppServiceProvider::register()` | 🔒 KRITIS — tanpa ini seluruh multi-tenancy tidak berfungsi |
| 2 | Migration `create_permission_tables`: `$teams` variable tidak masuk ke Schema::create closure | Hardcode `teams=true` (kita selalu pakai teams mode), hapus semua kondisional | Blocker migrate |
| 3 | Cache store `default` belum terdefinisi saat migration jalan | Wrap `app('cache')->store()` dalam try-catch di migration | Blocker migrate |
| 4 | `php artisan module:make` gagal karena ServiceProvider belum ter-autoload | Buat module dir manual + composer.json + module.json | Workaround |
| 5 | PHPUnit 12 tidak mendukung `@test` doc annotation | Ganti semua ke `#[Test]` attribute | Test tidak terdeteksi |
| 6 | Ahmad menggunakan phone sama → unique constraint violation saat membuat 2 user entry | Ahmad = 1 user, assign role di kedua team via `setPermissionsTeamId()` | Sesuai desain sebenarnya |
| 7 | NISN/phone overlap antara DemoTenantSeeder (6 siswa) dan PitchingDemoSeeder (100 siswa) | Offset NISN +100 dan phone prefix `628530` | Data demo |
| 8 | `StudentImportService` tidak terautoload dari `Modules/` untuk test | Copy juga ke `app/Services/` | Test import gagal |
| 9 | Spatie `hasRole()` cache role setelah team switch | Re-fetch user via `User::find()` setelah `setPermissionsTeamId()` | Test dual-role |

---

## 3. DB STATS (SQLite dev, setelah seeder)

| Tabel | Baris | Keterangan |
|---|---|---|
| tenants | 2 | MTs Al-Hikmah, MTs An-Nur |
| tenant_modules | 7 | T1: 4 modul, T2: 3 modul (tanpa finance) |
| users | 112 | 6 staff + 106 wali |
| students | 106 | 6 demo + 100 pitching |
| school_years | 2 | 2025/2026 aktif per tenant |
| school_classes | 6 | 3 di T1 (7A, 7B, 8A) + 3 tambahan (8B, 9A, 9B) |
| wa_notifications | 106 | Semua credential, status=queued |
| attendances | 0 | Menunggu Sprint 3 |
| roles | 12 | 6 role × 2 tenant |
| permissions | 22 | 11 unique × 2 team context |

---

## 4. KODE METRIK

| Metrik | Nilai |
|---|---|
| Total custom PHP files | ~48 |
| Total custom Blade views | 12 |
| Baris kode custom | ~3.800 |
| Migrasi | 10 → 24 tabel |
| Model Eloquent | 11 + 1 trait |
| Middleware | 4 |
| Controller | 10 (3 API + 7 Module) |
| Service | 2 |
| Test files | 3 (18 tests / 45 assertions) |

---

## 5. SPRINT 3 — STATUS SAAT INI

### ✅ Sudah Terbangun

| Komponen | Status |
|---|---|
| `Modules/Attendance/composer.json` + `module.json` | ✅ |
| `AttendanceController` (index, classGrid, store, recap, exportRecap) | ✅ |
| WA notification hook (dalam `store()`) | ✅ |
| View: `index.blade.php` (pilih kelas) | ✅ |
| View: `grid.blade.php` (grid presensi default H, radio toggle) | ✅ |
| View: `recap.blade.php` (rekap bulanan per siswa) | ✅ |
| Routes web (middleware module:attendance + can:attendance.*) | ✅ |
| API route `GET /v1/students/{id}/attendances?month=` | ✅ |
| `AttendanceApiController` (ownership check) | ✅ |

### ⬜ Belum / Perlu Ditingkatkan

| Task | Catatan |
|---|---|
| AttendanceModuleTest (9 test) | S3-08 — guru tandai, wali lihat, isolasi, modul off |
| Rekap export Excel (maatwebsite) | S3-05 — pakai maatwebsite/excel |
| Dashboard kepala: % kehadiran + tren | S3-06 — tambah card di Core dashboard |
| Grid presensi: JS tap toggle yang lebih smooth | UX improvement |
| Edit presensi hari berjalan + audit | S3-03 — sudah ada audit `marked_by`, tinggal UI edit |

---

## 6. WORKSPACE PETA

```
/home/user/SIMSEKOLAH2026/
├── ANALISA_KELAYAKAN_SIMSEKOLAH (Unzipped Files)/
├── session3_mvp_5jt/  (Doc 37–46 + laporan ini)
├── package_client/ package_developer/ package_investor/
└── simt-backend/      ← REKONSTRUKSI SELESAI ✅
    ├── app/
    │   ├── Support/Tenancy.php                    ← SINGLETON (fix kritis)
    │   ├── Models/Concerns/BelongsToTenant.php    ← global scope + autofill
    │   ├── Models/ (11 model)
    │   ├── Http/Middleware/ (4 middleware)
    │   ├── Http/Controllers/Api/ (Auth + AttendanceApi)
    │   ├── Services/ (TenantRoleService + StudentImportService)
    │   └── Providers/AppServiceProvider.php       ← Tenancy singleton
    ├── database/
    │   ├── migrations/ (11 file: 10 custom + 1 Sanctum)
    │   └── seeders/ (DatabaseSeeder, RolePermission, DemoTenant, PitchingDemo)
    ├── Modules/
    │   ├── Core/ (AuthController, DashboardController, login/dashboard views)
    │   ├── Student/ (4 controller + import service + 7 views)
    │   └── Attendance/ (AttendanceController + 3 views + composer/module.json)
    ├── routes/ (api.php, web.php — granular can: middleware)
    ├── tests/Feature/ (TenantIsolationTest 8✅, StudentModuleTest 8✅, ExampleTest 2✅)
    └── modules_statuses.json (Core, Student, Attendance = true)
```

---

## 7. LANGKAH SELANJUTNYA

### Prioritas 1: Sprint 3 Completion

| # | Task | Est. |
|---|---|---|
| 1 | `AttendanceModuleTest` — 9 test (guru tandai kelas, wali lihat anak sendiri, isolasi tenant, modul off = 403) | 1h |
| 2 | Rekap export Excel via maatwebsite/excel | 30 min |
| 3 | Dashboard kepala: % kehadiran hari ini + tren 7 hari | 30 min |
| 4 | Smoke test: `php artisan serve` + curl grid presensi | 15 min |
| 5 | Laporan `48_sprint3_execution_report.md` | 30 min |

### Prioritas 2: simt-portal (Next.js 14 skeleton)

### Prioritas 3: CI workflow + hardening

---

*Dokumen ini = titik masuk untuk sesi berikutnya. 18 test hijau membuktikan rekonstruksi Sprint 1+2 berhasil. Sprint 3 (Attendance) sudah 60% terbangun — perlu test suite + export + dashboard untuk mencapai gate.*
