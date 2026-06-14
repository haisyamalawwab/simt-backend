# SIMT MTs — Backend (Laravel 11)

**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah** — Micro-SaaS B2B2C multi-tenant untuk manajemen madrasah/sekolah yayasan (MTs/SMP).

> Status: MVP — Sprint 1, 2, 3 selesai & terverifikasi. Siap lanjut Sprint 4 (WA Gateway).
> **Test:** `23 passed (51 assertions)`. Lihat [`DEV_DOCS/54_dev_report_sprint123_stabilization.md`](DEV_DOCS/54_dev_report_sprint123_stabilization.md) untuk konteks lengkap.

---

## 📋 Ringkasan

| Aspek | Nilai |
|---|---|
| Framework | **Laravel 11** (PHP 8.2+) |
| Database | **MySQL 8** (produksi) · **SQLite** (dev/test) |
| Multi-tenancy | Single-DB, row-level (`tenant_id` + Global Scope `BelongsToTenant`) |
| RBAC | Spatie Permission mode **teams** (`team_id = tenant_id`) |
| Modularitas | **nwidart/laravel-modules** (plug & play) |
| Auth | Sanctum (API token 30 hari) + session (web Blade) |
| Frontend | Blade (admin/guru) · Next.js portal ortu (repo terpisah) |
| Notifikasi | WhatsApp via Baileys self-hosted (Sprint 4) |

---

## 🧩 Arsitektur Modul

Dua lapisan modularitas (lihat [`DEV_DOCS/ARSITEKTUR_MODUL_CORE_vs_PLUGNPLAY.md`](DEV_DOCS/ARSITEKTUR_MODUL_CORE_vs_PLUGNPLAY.md)):

| Modul | Tipe | Isi |
|---|---|---|
| **Core** | 🔒 INTI (tak bisa dilepas) | Tenant, RBAC, Auth, Dashboard, Super-Admin |
| **Student** | 🔌 Plug & Play | CRUD siswa/kelas/TA, import Excel 3-langkah |
| **Attendance** | 🔌 Plug & Play | Grid presensi, rekap bulanan, hook WA |
| **Finance** | 🔌 Plug & Play | Tagihan SPP, pembayaran, kwitansi PDF, pengingat WA |

- **Lapisan kode (nwidart):** `php artisan module:disable Finance` → route modul hilang dari aplikasi.
- **Lapisan langganan (`tenant_modules`):** middleware `module.active:{Kode}` → per-sekolah; tenant tanpa langganan dapat `403`.

Cara menambah modul baru: lihat [`DEV_DOCS/PANDUAN_BUAT_MODUL_PLUGNPLAY.md`](DEV_DOCS/PANDUAN_BUAT_MODUL_PLUGNPLAY.md).

---

## 🚀 Setup Cepat

```bash
# Prasyarat: PHP 8.2+ (ext: sqlite3/pdo_mysql, mbstring, xml, curl, zip, gd, bcmath), Composer 2

composer install
cp .env.example .env
php artisan key:generate

# --- DEV (SQLite) ---
touch database/database.sqlite          # pastikan DB_CONNECTION=sqlite di .env
php artisan migrate:fresh --seed        # 11 migrasi + demo 106 siswa

php artisan serve                       # http://127.0.0.1:8000
```

### Produksi (MySQL)
Set `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, lalu `php artisan migrate --seed`.
Skema lengkap & catatan migrasi: [`DEV_DOCS/DATABASE_SCHEMA.md`](DEV_DOCS/DATABASE_SCHEMA.md).

---

## 🔑 Akun Demo (password: `password`)

| Login | Peran | Tenant |
|---|---|---|
| `vendor@simt.id` | superadmin | lintas tenant |
| `ahmad@mts-alhikmah.sch.id` | admin_sekolah | T1 (modul lengkap) |
| `ahmad@mts-annur.sch.id` | guru | T2 (Core+Student saja) |
| phone `628520000001` | wali | T1 (portal) |

---

## 🗂️ Struktur Direktori

```
app/
├── Http/Middleware/    IdentifyTenant, SetTenantFromUser, CheckTenantAccess, EnsureModuleActive
├── Models/             Tenant, User, Student, SchoolClass, Attendance, Bill, Payment, ...
├── Services/           TenantRoleService, StudentImportService
├── Support/            Tenancy (singleton konteks tenant)
├── Traits/             BelongsToTenant (global scope + auto-fill)
└── Jobs/               SendWaNotification
Modules/                Core · Student · Attendance · Finance (nwidart)
database/
├── migrations/         11 file (lihat DATABASE_SCHEMA.md)
└── seeders/            RolePermissionSeeder, DemoTenantSeeder, PitchingDemoSeeder
tests/Feature/          TenantIsolationTest(8) · StudentModuleTest(8) · AttendanceModuleTest(5)
DEV_DOCS/               Dokumentasi proyek (dev report, arsitektur, API, DB, panduan modul)
```

---

## 🧪 Testing

```bash
php artisan test                 # 23 passed (51 assertions)
php artisan test --filter=AttendanceModuleTest
```
Detail: [`DEV_DOCS/...`](DEV_DOCS/). Test pakai SQLite in-memory (lihat `phpunit.xml`).

---

## 📡 API

Base: `/api/v1` · Auth: `Authorization: Bearer {token}` · Tenant: header `X-Tenant-Domain: {domain}`.
Kontrak lengkap: [`DEV_DOCS/API_CONTRACT.md`](DEV_DOCS/API_CONTRACT.md).

Contoh:
```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"login":"ahmad@mts-alhikmah.sch.id","password":"password"}'
```

---

## 📚 Dokumentasi (DEV_DOCS/)

| Dokumen | Isi |
|---|---|
| `54_dev_report_sprint123_stabilization.md` | Dev report & handover konteks (WAJIB dibaca agent baru) |
| `ARSITEKTUR_MODUL_CORE_vs_PLUGNPLAY.md` | Pemetaan Core vs Plug & Play |
| `API_CONTRACT.md` | Kontrak endpoint web + API |
| `DATABASE_SCHEMA.md` | 12 tabel domain + relasi + catatan MySQL |
| `PANDUAN_BUAT_MODUL_PLUGNPLAY.md` | Langkah membuat modul nwidart baru |

> ⚠️ Folder `vendor/` bisa hilang antar-sesi sandbox. Jika `artisan` error "Class Illuminate\Foundation\Application not found", jalankan `composer install`.

---

*MVP Edition — 3 Bulan / Rp 5 Juta · Bahasa Indonesia · istilah madrasah (Wali Kelas, TU, Bendahara, dst.)*
