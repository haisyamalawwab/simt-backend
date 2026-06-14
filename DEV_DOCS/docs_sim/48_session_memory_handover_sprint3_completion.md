# SESSION MEMORY & SUMMARY (Sesi 5: Rekonstruksi S1+S2 + Sprint 3 Skeleton)
## Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)

**Tanggal Sesi:** 12 Juni 2026  
**Status Proyek:** Dokumen 100% ✅ · Sprint 1 (Fondasi) 100% ✅ · Sprint 2 (Kesiswaan) 100% ✅ · **Sprint 3 (Presensi) ~60%** · **Berikutnya: Sprint 3 Completion**  
**Dokumen ini = titik masuk WAJIB untuk sesi berikutnya.**

---

## 1. RINGKASAN EKSEKUTIF (Apa yang Telah Diselesaikan di Sesi Ini)

Sesi ini dimulai dari sandbox kosong — kode dari sesi sebelumnya TIDAK ikut snapshot. Seluruh codebase harus direkonstruksi dari dokumentasi:

1. **Clone Google Drive** `!!SIMSEKOLAH2026!!` → 89 file dokumentasi (Doc 01–46 + ZIP + docx) → `/home/user/SIMSEKOLAH2026/`
2. **Rekonstruksi `simt-backend/`** (Laravel 13 + PHP 8.4): 10 migrasi, 11 model, 4 middleware, 3 seeder, Modules/{Core,Student,Attendance}, 12 Blade views
3. **Fix bug KRITIS**: `Tenancy` class bukan singleton → global scope multi-tenant sama sekali tidak bekerja → diperbaiki di `AppServiceProvider::register()` dengan `$this->app->singleton(Tenancy::class)`
4. **Seeders**: DemoTenantSeeder (2 tenant, Ahmad dual-role, 6 siswa) + PitchingDemoSeeder (100 siswa + 100 wali + 100 WA queued)
5. **Test suite: 18 passed / 45 assertions** — membuktikan rekonstruksi setara dengan output sesi sebelumnya
6. **Sprint 3 skeleton**: AttendanceController (index, classGrid, store, recap, exportRecap) + 3 views (index, grid, recap) + WA notification hook + routes + API

---

## 2. MEMORIZE: KEPUTUSAN ARSITEKTUR & TEKNIS (Key Takeaways — JANGAN DINEGOSIASI ULANG)

### A. Warisan Sesi 1–3 (tetap berlaku)
1. **Hybrid Rendering:** Blade (Admin/Guru/TU) + Next.js (Portal Ortu saja)
2. **Single-DB Multi-Tenant:** semua tabel domain ber-`tenant_id` + Global Scope; **TANPA** stancl/tenancy (manual ringan via singleton `App\Support\Tenancy`)
3. **Plug & Play:** nwidart/laravel-modules + tabel `tenant_modules` + middleware `module:{code}` → 403 `MODULE_INACTIVE` (JSON utk API, abort utk web)
4. **RBAC Spatie Teams** (`teams=true`, `team_id = tenant_id`): kasus "Guru Ahmad" terbukti — admin_sekolah @ MTs-1, guru @ MTs-2
5. **Bisnis:** B2B2C Rp 2.000/siswa/bln · min Rp 200rb/bln · prepaid 1 semester · Zero-Cost WA (Baileys, sekolah scan QR sendiri) · MoU Doc 36

### B. Keputusan dari sesi sebelumnya (tetap berlaku)
1. **`users` TANPA global scope tenant** — autentikasi terjadi sebelum konteks tenant; isolasi via middleware `tenant.user` (API) / `tenant.web` (sesi Blade dari `users.tenant_id`)
2. **🔒 KRITIS — Middleware priority:** `IdentifyTenant`/`SetTenantFromUser` HARUS jalan **sebelum `SubstituteBindings`** (sudah di-set di `bootstrap/app.php`)
3. **Laravel 13:** `$this->middleware()` di controller TIDAK ADA — otorisasi di route-level (`can:students.manage`)
4. **Tabel `school_classes`** (bukan `classes` — reserved word); model `SchoolClass`
5. **Composer merge-plugin** untuk autoload `Modules/*/composer.json`
6. **Import wizard:** hasil validasi di-cache 30 menit by UUID token; commit = transaksi tunggal, baris error dilewati
7. **Normalisasi WA:** `08xx → 628xx` di semua titik input; regex valid `^628\d{7,12}$`
8. **Kwitansi:** `Payment::nextReceiptNo()` → `KW/{tenant}/{tahun}/{seq}` (sudah ada, dipakai Sprint 5)
9. **Status tenant** = state machine langsung di enum DB (`prospect|contracted|active|grace_read|suspended|terminated`)
10. Tailwind via CDN untuk dev; build Vite ditunda ke Sprint 6

### C. Keputusan BARU di sesi ini (Sesi 5 — Rekonstruksi)
1. **🔒🔒 SANGAT KRITIS — `Tenancy` HARUS singleton.** Tanpa `$this->app->singleton(Tenancy::class)` di AppServiceProvider, `app(Tenancy::class)` mengembalikan instance baru setiap resolve → global scope `BelongsToTenant` TIDAK PERNAH memfilter. Bug ini mengakibatkan seluruh multi-tenancy tidak berfungsi. Ditemukan & dibuktikan secara empiris.
2. **PHPUnit 12 (Laravel 13):** `@test` doc annotation TIDAK didukung lagi. WAJIB pakai `#[Test]` attribute atau `test_` method prefix.
3. **Spatie role cache:** setelah `setPermissionsTeamId()`, WAJIB re-fetch user (`User::find()`) untuk mendapatkan instance bersih tanpa cached roles.
4. **`StudentImportService` ada di DUA lokasi:** `Modules/Student/app/Services/` (dipakai oleh ImportController) DAN `app/Services/` (dipakai oleh test suite). Merge-plugin autoload menangani keduanya. Saat mengubah service, update KEDUA file.
5. **Route authorization:** `->can()` tidak bisa di-chain ke `Route::resource()` di Laravel 13. Gunakan `Route::middleware('can:permission')->group()` sebagai wrapper.
6. **Sanctum migration** harus dipublish terpisah: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`

---

## 3. PETA REPO & FILE PENTING (Lokasi Workspace)

```
/home/user/SIMSEKOLAH2026/
├── session3_mvp_5jt/          # Doc 37–46 + laporan rekonstruksi (Doc 47)
├── ANALISA_KELAYAKAN_SIMSEKOLAH (Unzipped Files)/   # Doc 00–46 dari Drive
├── package_client/ package_developer/ package_investor/  # Dok Doc 01–36
└── simt-backend/              # Laravel 13 — INTI PROYEK
    ├── app/
    │   ├── Support/Tenancy.php                    # SINGLETON konteks tenant ← JANGAN UBAH
    │   ├── Models/Concerns/BelongsToTenant.php    # global scope + auto-fill
    │   ├── Models/ (Tenant, TenantModule, Invoice, User, SchoolYear,
    │   │            SchoolClass, Student, Attendance, Bill, Payment, WaNotification)
    │   ├── Http/Middleware/ (IdentifyTenant, SetTenantFromUser,
    │   │                      EnsureUserBelongsToTenant, EnsureModuleActive)
    │   ├── Http/Controllers/Api/ (AuthController, AttendanceApiController)
    │   ├── Services/ (TenantRoleService, StudentImportService)  ← duplikat di Modules
    │   └── Providers/AppServiceProvider.php       # ← Tenancy::class singleton
    ├── bootstrap/app.php        # alias + PRIORITY middleware (jangan ubah urutan!)
    ├── Modules/
    │   ├── Core/    (AuthController, DashboardController, login/dashboard views)
    │   ├── Student/ (4 controller + StudentImportService + 7 views)
    │   └── Attendance/ (AttendanceController + 3 views + composer/module.json)
    ├── database/
    │   ├── migrations/ (11 file: 10 custom + 1 Sanctum)
    │   └── seeders/ (DatabaseSeeder, RolePermissionSeeder,
    │                 DemoTenantSeeder, PitchingDemoSeeder)
    ├── routes/api.php     # /v1: ping, auth/login, me, me/children, attendance API
    ├── routes/web.php     # Login, dashboard, student CRUD+import, attendance grid+recap
    ├── tests/Feature/
    │   ├── TenantIsolationTest.php   # 8 test
    │   ├── StudentModuleTest.php     # 8 test
    │   └── ExampleTest.php           # 2 test
    ├── config/permission.php  # teams=true
    └── modules_statuses.json  # Core, Student, Attendance = true
```

**DB saat ini (SQLite dev):** 2 tenant + 106 siswa + 112 users + 106 WA queued.  
**Test suite:** 18 passed / 45 assertions ✅

### Akun Demo (password semua: `password`)
| Akun | Login | Peran |
|---|---|---|
| Vendor | `vendor@simt.id` | superadmin lintas tenant |
| Ahmad | `ahmad@mts-alhikmah.sch.id` | admin_sekolah @ T1 (mts-alhikmah) |
| Ahmad | (user sama) | guru @ T2 (mts-annur, TANPA modul finance) |
| Siti Maryam | `siti@mts-alhikmah.sch.id` | guru / wali kelas 7A |
| Budi (TU) | `budi@mts-alhikmah.sch.id` | tu |
| Wali contoh | phone `628520000001` | wali (API portal) |

### Matriks Role → Permission (TenantRoleService::ROLE_MATRIX)
`admin_sekolah`=semua · `kepala_madrasah`=dashboard+view/recap presensi · `tu`=students.* + attendance view/recap + wa.connect · `bendahara`=bills/payments/arrears · `guru`=students.view + attendance.mark/view · `wali`=ownership-based (tanpa permission admin)

---

## 4. ⚠️ SETUP ULANG SANDBOX (WAJIB di awal sesi berikutnya!)

PHP/Composer/vendor **TIDAK ikut snapshot** workspace. Jalankan dulu:
```bash
sudo apt-get update -qq && sudo apt-get install -y -qq php-cli php-mbstring php-xml php-curl php-sqlite3 php-zip php-gd unzip sqlite3
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
cd /home/user/SIMSEKOLAH2026/simt-backend && composer install --no-interaction
php artisan test          # HARUS: 18 passed (45 assertions) sebelum mulai coding
```
(`.env`, `database/database.sqlite`, dan semua kode ikut snapshot — tidak perlu migrate ulang kecuali ingin fresh.)

---

## 5. STATUS SPRINT (Doc 40) — POSISI SEKARANG

| Sprint | Scope | Status |
|---|---|---|
| S1 (Mg 1–2) | Core + Tenancy + RBAC | ✅ SELESAI (rekonstruksi terbukti via 18 test) |
| S2 (Mg 3–4) | Kesiswaan + Import Excel | ✅ SELESAI (rekonstruksi terbukti via 18 test) |
| **S3 (Mg 5–6)** | **Presensi UI + Rekap** | 🟡 **~60% — Lihat detail di §6** |
| S4 (Mg 7–8) | WA Gateway Baileys + Queue | ⬜ |
| S5 (Mg 9–10) | Keuangan SPP + Portal Ortu | ⬜ |
| S6 (Mg 11–12) | UAT + Go-Live + Onboarding | ⬜ |

---

## 6. LANGKAH SELANJUTNYA: SPRINT 3 COMPLETION (Detail)

Target gate: **"Guru absen 1 kelas (40 siswa) ≤ 60 detik dari HP"** + demo live ke calon sekolah.

### Sudah Terbangun (di sesi ini)
| ID | Task | Status |
|---|---|---|
| S3-01 | Module Attendance + migration (sudah ada sejak S1) | ✅ |
| S3-02 | AttendanceController + grid view (index, classGrid, store) | ✅ Skeleton |
| S3-04 | WA notification hook di store() | ✅ |
| S3-07 | API portal: `GET /v1/students/{id}/attendances?month=` | ✅ |

### Belum / Perlu Ditingkatkan
| ID | Task | Catatan implementasi | Prioritas |
|---|---|---|---|
| S3-02b | **Grid presensi UX**: JS tap toggle yang smooth di HP (saat ini pakai radio button) | Target: tap siklus H→A→I→S→T, auto-advance ke baris berikutnya, bulk save | 🔴 |
| S3-03 | **Edit presensi** + audit `marked_by` | Upsert sudah jalan; tampilkan siapa & kapan terakhir input di grid; tambah tombol edit tanggal | 🟡 |
| S3-05 | **Rekap bulanan export Excel** | `maatwebsite/excel` sudah terinstall; buat Export class untuk rekap per (student, status) bulan berjalan | 🔴 |
| S3-06 | **Dashboard kepala**: % kehadiran hari ini + tren 7 hari | Tambah card di `Core::dashboard`, gate `can:attendance.recap`; query `Attendance::whereDate(...)` | 🔴 |
| S3-08 | **AttendanceModuleTest**: guru tandai kelas, wali lihat anak sendiri (bukan anak lain), isolasi tenant, modul off = 403 | Pola test ikuti StudentModuleTest; target ±9 test tambahan → total ±27 test | 🔴 |

### Definition of Done S3
- Test suite hijau (target ±27 test)
- Smoke test: `php artisan serve` + curl grid presensi
- Rekap bulanan berisi data 106 siswa demo
- Export Excel rekap bisa diunduh
- Laporan `48_sprint3_execution_report.md`

---

## 7. BUG & PITFALL YANG PERLU DIWASPADAI SESI BERIKUTNYA

### 🔒 Kritis
1. **Tenancy singleton** — JANGAN PERNAH hapus `$this->app->singleton(Tenancy::class)` dari AppServiceProvider. Tanpa ini, seluruh multi-tenancy runtuh.
2. **Middleware priority** — JANGAN ubah urutan di `bootstrap/app.php`. IdentifyTenant/SetTenantFromUser HARUS sebelum SubstituteBindings.
3. **StudentImportService duplikat** — file ada di `app/Services/` DAN `Modules/Student/app/Services/`. Update kedua-duanya saat ada perubahan.

### ⚠️ Sedang
4. **Spatie role cache** — di test, setelah `setPermissionsTeamId()`, re-fetch user dengan `User::find($id)` untuk menghindari stale cached roles.
5. **PHPUnit 12** — hanya pakai `#[Test]` attribute atau `test_` prefix. `@test` doc annotation TIDAK didukung.
6. **`->can()` on resource routes** — tidak bisa di-chain ke `Route::resource()` di Laravel 13. Gunakan middleware group wrapper.

### ℹ️ Minor
7. **Migration cache store** — `app('cache')->store()` di migration bisa error jika cache belum dikonfigurasi. Sudah di-wrap try-catch.
8. **Sanctum migration** — harus dipublish terpisah dari `vendor:publish`.

---

## 8. SETELAH S3 → S4 (Jalur Kritis)

**Sprint 4 (paling berisiko):**
- Service Node.js Baileys multi-session di `simt-wa-gateway/` (repo baru)
- Endpoint `/session/{tenant}/qr|status` + `/send`
- Laravel queue worker: rate-limit 10/mnt + jitter 3–8 dtk + retry 3×
- `wa_notifications` yang sudah menumpuk (106 credential + hasil presensi S3) jadi data uji nyata
- Target: WA delivery ≥95%

**simt-portal/ (Next.js) juga belum dibangun** — perlu skeleton minimal di sesi berikutnya atau paralel dengan S4.

---

*Dokumen ini dibuat agar sesi berikutnya cukup membaca dokumen ini + Doc 40 (sprint plan) + laporan Doc 47 untuk melanjutkan tanpa kehilangan konteks. Semua keputusan di §2 bersifat FINAL kecuali ada blocker teknis nyata.*
