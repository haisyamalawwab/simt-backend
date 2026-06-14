# SPRINT 2 — EXECUTION REPORT (Doc 45)
## SIMT MTs — Modul Kesiswaan: CRUD Blade + Import Excel Wizard + Akun Wali Otomatis

**Tanggal:** 12 Juni 2026 | **Sprint:** 2 dari 6 (Doc 40) | **Status:** ✅ SELESAI — GATE LOLOS

---

## 1. Yang Dibangun

### A. Arsitektur Modular nwidart AKTIF (pindah dari skeleton Sprint 1)
```
Modules/
├── Core/                                 # Modul inti
│   ├── app/Http/Controllers/
│   │   ├── AuthController.php            # login web (email ATAU no. WA) + throttle 5/mnt
│   │   └── DashboardController.php       # statistik + status modul per tenant
│   ├── resources/views/dashboard.blade.php
│   └── routes/web.php                    # /login /logout /dashboard
└── Student/                              # Modul Kesiswaan (FR-S01..S04)
    ├── app/Http/Controllers/
    │   ├── StudentController.php         # CRUD + addGuardian (akun wali otomatis)
    │   ├── SchoolClassController.php     # kelas per TA aktif + wali kelas
    │   ├── SchoolYearController.php      # TA, enforce 1 aktif/tenant
    │   └── ImportController.php          # wizard 3 langkah + template CSV
    ├── app/Services/StudentImportService.php   # parse -> validate -> commit (transaksi)
    ├── resources/views/ (students/, classes/, school-years/, import/)
    └── routes/web.php                    # 18 route, berlapis: auth -> tenant.web
                                          #   -> module:student -> can:students.*
```
- `composer.json` root memakai **merge-plugin** → autoload `Modules/*/composer.json` (cara resmi nwidart).
- `modules_statuses.json`: Core ✓, Student ✓.

### B. Komponen baru di app/
- `SetTenantFromUser` (alias `tenant.web`) — tenancy sesi web dari `users.tenant_id` (lebih aman dari subdomain spoofing); auto-logout jika tenant suspended.
- `EnsureModuleActive` kini dual-mode: JSON 403 untuk API, `abort(403)` untuk web.
- **Middleware priority fix (KRITIS)**: `IdentifyTenant`/`SetTenantFromUser` dijalankan **sebelum `SubstituteBindings`** — tanpa ini route model binding `{student}` bocor lintas tenant. Ditemukan oleh test, langsung diperbaiki. 🔒

### C. Import Excel Wizard 3 Langkah (FR-S02) — fitur inti sprint
| Langkah | Perilaku |
|---|---|
| 1. Upload | xlsx/xls/csv, maks 5 MB / 1.000 baris; template CSV bisa diunduh |
| 2. Preview | Validasi **per baris**: nama, JK L/P, NISN 8–12 digit + duplikat (DB & dalam-file), kelas harus ada di TA aktif, normalisasi WA 08xx→628xx, tanggal Excel-serial & string; baris error disorot merah dan **dilewati** |
| 3. Commit | **Satu transaksi DB** (no partial-commit); hasil di-cache 30 menit by token (commit = persis yang di-preview) |

Side-effects commit (FR-S04): akun wali dibuat otomatis (username = no. WA, password acak 8 char), role `wali` per-team, kredensial **diantrikan ke `wa_notifications`** (worker dikirim Sprint 4).

---

## 2. Bukti Gate Sprint 2

### Gate: "Import 100 siswa dari template Excel tanpa error"
`PitchingDemoSeeder` (S2-07) men-generate **CSV 100 baris nyata** lalu menjalankan **pipeline import produksi yang sama** (dogfooding):
```
PitchingDemo: 100 valid / 0 error -> commit: 100 siswa, 100 wali, 100 WA queued.
```

### Smoke test UI live (`php artisan serve` + curl session-cookie)
```
GET  /login                  → 200, halaman Blade Tailwind
POST /login (Ahmad/admin)    → 302 → /dashboard
GET  /dashboard              → "Assalamu'alaikum, Ahmad Fauzi 👋" | 106 siswa, 3 rombel, 106 wali
GET  /students?q=Muhammad    → tabel hasil pencarian (paginated 25/halaman)
GET  /import/students/template → CSV template valid
```

### Test otomatis: **19 passed / 46 assertions** (CI)
Baru di sprint ini (`StudentModuleTest`, 9 test):
| Test | Bukti |
|---|---|
| tu can create student with class | CRUD + auto-fill tenant_id + attach kelas |
| guru cannot create student | RBAC: guru hanya `students.view` → 403 |
| student module disabled returns 403 | Plug & Play di jalur **web** |
| duplicate nisn in same tenant rejected | Rule unique per-tenant |
| import validate reports errors per row | "Nama kosong; JK harus L/P; Kelas '9Z' tidak ditemukan" |
| import commit creates students+guardians+wa queue | 2 siswa, 2 wali (08→628), 2 WA queued |
| import skips invalid rows only | 1 valid masuk, 1 invalid dilewati |
| tu cannot see other tenant student detail | **404** lintas tenant (fix middleware priority) |
| root redirect + login page render | sanity web |

---

## 3. Keputusan Teknis Sprint Ini
1. **Tenancy web dari user login** (bukan subdomain) untuk sesi Blade — sumber kebenaran `users.tenant_id`; subdomain tetap dipakai di API/produksi.
2. **Otorisasi di route-level** (`can:students.manage`) — Laravel 13 menghapus `$this->middleware()` di controller.
3. **Cache token utk wizard import** — commit tidak memproses ulang file; sesi kedaluwarsa 30 menit.
4. **Tailwind via CDN** untuk dev; build Vite lokal ditunda ke Sprint 6 (hardening).
5. Stub `StudentController` bawaan nwidart diganti penuh; route resource dipecah manual agar permission granular.

## 4. Posisi Terhadap Rencana (Doc 40)
| Task | Status |
|---|---|
| S2-01 struktur modul + migrations | ✅ (migrations dari Sprint 1, modul aktif sekarang) |
| S2-02 CRUD TA & Kelas | ✅ |
| S2-03 CRUD Siswa + search/filter | ✅ |
| S2-04 Import wizard 3 langkah | ✅ |
| S2-05 Wali + generate akun | ✅ (massal via import; manual via halaman siswa) |
| S2-06 API /me/children | ✅ (sejak Sprint 1) |
| S2-07 Seeder 100 siswa demo | ✅ |
| S2-08 Test modul | ✅ 9 test baru |

## 5. Next (Sprint 3 — Doc 40)
- [ ] Module `Attendance`: grid presensi per kelas (default Hadir, tap toggle, bulk save ≤60 dtk)
- [ ] Edit presensi + audit `marked_by`
- [ ] Rekap bulanan + export Excel
- [ ] Dashboard kepala: % kehadiran hari ini
- [ ] API portal `GET /students/{id}/attendances?month=`
- [ ] 🚩 Non-coding: mulai pitching (deck dari Doc 34)
