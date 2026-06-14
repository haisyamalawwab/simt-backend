# SPRINT 1 — EXECUTION REPORT (Doc 44)
## SIMT MTs — Skeleton Repo + Migrations Nyata + Bukti Gate

**Tanggal:** 12 Juni 2026 | **Sprint:** 1 dari 6 (Doc 40) | **Status:** ✅ SELESAI — SEMUA GATE LOLOS

---

## 1. Yang Dibangun (Kode Nyata, Bukan Teori)

### Repo 1: `simt-backend/` (Laravel 13 + PHP 8.4)
```
simt-backend/
├── app/
│   ├── Support/Tenancy.php                  # konteks tenant per-request (singleton)
│   ├── Models/Concerns/BelongsToTenant.php  # Global Scope + auto-fill tenant_id
│   ├── Models/  (Tenant, TenantModule, Invoice, User, SchoolYear,
│   │             SchoolClass, Student, Attendance, Bill, Payment, WaNotification)
│   ├── Http/Middleware/
│   │   ├── IdentifyTenant.php               # X-Tenant-Domain / subdomain → 400/402
│   │   ├── EnsureUserBelongsToTenant.php    # token A ≠ tenant B → 403
│   │   └── EnsureModuleActive.php           # Plug & Play → 403 MODULE_INACTIVE
│   └── Services/TenantRoleService.php       # provision 6 role/tenant (Spatie Teams)
├── database/
│   ├── migrations/  (10 migrasi baru: tenants, tenant_modules, invoices,
│   │                 users+tenant, school_years, school_classes, students
│   │                 +class_student+guardian_student, attendances,
│   │                 bills+payments, wa_notifications)
│   └── seeders/     (RolePermissionSeeder: 13 permission × matriks 6 role;
│                     DemoTenantSeeder: 2 tenant + kasus "Guru Ahmad")
├── routes/api.php   # /v1/ping, /auth/login (throttle 5/mnt), /me/children,
│                    # /finance/ping (module-gated), /attendance/ping
├── tests/Feature/TenantIsolationTest.php    # 8 test isolasi — Acceptance Gate #2
└── .github/workflows/ci.yml                 # CI: test wajib hijau (S1-09)
```

### Repo 2: `simt-portal/` (Next.js 14 skeleton — Portal Ortu)
```
simt-portal/
├── app/page.tsx     # Login via No. WA → daftar anak (FR-O01)
├── app/layout.tsx
├── lib/api.ts       # client dgn header X-Tenant-Domain + Bearer (Doc 39 §3)
└── package.json / tsconfig.json / next.config.mjs / .env.example
```

---

## 2. Bukti Sprint-1 Gate (dijalankan live di sandbox)

### Gate A — Kasus "Guru Ahmad" (FR-C04, Spatie Teams)
```
Ahmad @ MTs Al-Hikmah : admin_sekolah | can students.manage? YA
Ahmad @ MTs An-Nur    : guru          | can students.manage? TIDAK
```

### Gate B — Isolasi Tenant (NFR-04, Global Scope)
```
Konteks T1 (Al-Hikmah): siswa terlihat = 6
Konteks T2 (An-Nur)   : siswa terlihat = 0   ← ISOLASI OK
```

### Gate C — Plug & Play (FR-C05)
```
T1 punya modul finance? YA
T2 punya modul finance? TIDAK → API /finance/* = 403 MODULE_INACTIVE
```

### Gate D — Test Otomatis: **10 passed (19 assertions)**
| Test | Hasil |
|---|---|
| global scope blocks cross tenant reads | ✅ |
| tenant id is autofilled on create | ✅ |
| cross tenant find by id returns null | ✅ |
| roles are scoped per tenant (Guru Ahmad) | ✅ |
| unknown tenant header → 400 TENANT_NOT_FOUND | ✅ |
| suspended tenant → 402 TENANT_SUSPENDED | ✅ |
| token cannot cross tenants → 403 FORBIDDEN_TENANT | ✅ |
| inactive module → 403 MODULE_INACTIVE | ✅ |
| (+2 test bawaan Laravel) | ✅ |

### Gate E — Smoke Test API live (`php artisan serve`)
```
GET  /v1/ping (mts-alhikmah)        → {"success":true,"tenant":{"name":"MTs Al-Hikmah",...}}
POST /v1/auth/login (wali, no. WA)  → token Sanctum 30 hari ✓
GET  /v1/me/children                → [{"name":"Muhammad Rizki","nisn":"0071234010"}]
GET  /v1/finance/ping @ mts-annur
     dgn token mts-alhikmah         → 403 FORBIDDEN_TENANT (pertahanan ganda ✓)
GET  /v1/ping (tenant tak dikenal)  → 400 TENANT_NOT_FOUND
```

---

## 3. Keputusan Teknis Sprint Ini

1. **Tenancy manual ringan** (bukan stancl/tenancy) — sesuai Doc 23 §2.2: singleton `Tenancy` + trait `BelongsToTenant`. Hemat dependensi, cukup untuk single-DB.
2. **User TANPA global scope tenant** — autentikasi terjadi sebelum konteks tenant; isolasi user ditegakkan middleware `tenant.user` (pertahanan lapis-2 teruji).
3. **`school_classes`** dipakai sebagai nama tabel (hindari reserved word `class`).
4. **Kwitansi**: `Payment::nextReceiptNo()` → `KW/{tenant}/{tahun}/{seq}` (FR-K03).
5. **Status tenant** = state machine Doc 42 §5 (prospect → contracted → active → grace_read → suspended → terminated) langsung di enum DB.
6. **Modul via folder nwidart ditunda ke Sprint 2** — paket sudah terpasang; Sprint 1 fokus fondasi core (sesuai cheat-sheet Doc 23). Routes modul saat ini di `api.php` dengan gate `module:{code}` yang sama.

## 4. Demo Accounts (password semua: `password`)
| Akun | Login | Peran |
|---|---|---|
| Vendor | `vendor@simt.id` | superadmin lintas tenant |
| Ahmad | `ahmad@mts-alhikmah.sch.id` | admin_sekolah @ T1 |
| Ahmad | `ahmad@mts-annur.sch.id` | guru @ T2 |
| Siti Maryam | `siti@mts-alhikmah.sch.id` | guru/wali kelas 7A |
| Wali Rizki | phone `628520000001` | wali (portal) |

## 5. Next (Sprint 2 — Doc 40)
- [ ] Pecah ke folder `Modules/` nwidart (Core, Student) + module:make
- [ ] CRUD Blade: Tahun Ajaran, Kelas, Siswa (layout Tailwind per-role)
- [ ] Import Excel wizard 3 langkah (Laravel Excel) — FR-S02
- [ ] Generate akun wali massal + antrian kirim kredensial (tabel `wa_notifications` sudah siap)
- [ ] Seeder 100 siswa utk demo pitching (S2-07)
