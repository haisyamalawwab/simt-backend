# SESSION MEMORY & SUMMARY (Sesi 4: Fase Eksekusi — Sprint 1 & 2 SELESAI)
## Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)

**Tanggal Sesi:** 12 Juni 2026
**Status Proyek:** Dokumen 100% ✅ · Sprint 1 (Fondasi) 100% ✅ · Sprint 2 (Kesiswaan) 100% ✅ · **Berikutnya: Sprint 3 (Presensi)**
**Dokumen ini = titik masuk WAJIB untuk sesi berikutnya.**

---

## 1. RINGKASAN EKSEKUTIF (Apa yang Telah Diselesaikan di Sesi Ini)

Sesi ini menutup fase perencanaan dan **memasuki Fase Eksekusi dengan kode nyata yang teruji**:

1. **Clone Google Drive** `!!SIMSEKOLAH2026!!` (78 file, Doc 01–36 + 3 paket ZIP) → `/home/user/SIMSEKOLAH2026/`
2. **Paket Dokumen MVP** (Doc 37–43) di `session3_mvp_5jt/`: PRD MVP 3-bulan/Rp 5jt, SRS, Design (ERD 12 tabel + arsitektur 2-VPS), Task 6 Sprint, Visualisasi Konsep (HTML), 8 Diagram Proses Mermaid, Peta Dokumen Drive
3. **Sprint 1** — repo `simt-backend/` (Laravel 13 + PHP 8.4): 10 migrasi, 11 model, multi-tenant single-DB + Spatie Teams + Sanctum, 3 middleware pertahanan, seeder demo, **10 test hijau** (Doc 44)
4. **Sprint 2** — Modul nwidart aktif (Core + Student): login Blade, dashboard, CRUD TA/Kelas/Siswa, **Import Excel wizard 3 langkah**, akun wali otomatis + antrian WA, seeder 100 siswa, **19 test hijau / 46 assertions** (Doc 45)
5. Repo `simt-portal/` (Next.js 14 skeleton): login via No. WA + daftar anak (FR-O01)

---

## 2. MEMORIZE: KEPUTUSAN ARSITEKTUR & TEKNIS (Key Takeaways — JANGAN DINEGOSIASI ULANG)

### A. Warisan Sesi 1–3 (tetap berlaku)
1. **Hybrid Rendering:** Blade (Admin/Guru/TU) + Next.js (Portal Ortu saja)
2. **Single-DB Multi-Tenant:** semua tabel domain ber-`tenant_id` + Global Scope; **TANPA** stancl/tenancy (manual ringan via singleton `App\Support\Tenancy`)
3. **Plug & Play:** nwidart/laravel-modules + tabel `tenant_modules` + middleware `module:{code}` → 403 `MODULE_INACTIVE` (JSON utk API, abort utk web)
4. **RBAC Spatie Teams** (`teams=true`, `team_id = tenant_id`): kasus "Guru Ahmad" terbukti — admin_sekolah @ MTs-1, guru @ MTs-2
5. **Bisnis:** B2B2C Rp 2.000/siswa/bln · min Rp 200rb/bln · prepaid 1 semester · Zero-Cost WA (Baileys, sekolah scan QR sendiri) · MoU Doc 36

### B. Keputusan BARU di sesi ini (Sprint 1 & 2)
1. **`users` TANPA global scope tenant** — autentikasi terjadi sebelum konteks tenant; isolasi via middleware `tenant.user` (API) / `tenant.web` (sesi Blade dari `users.tenant_id`)
2. **🔒 KRITIS — Middleware priority:** `IdentifyTenant`/`SetTenantFromUser` HARUS jalan **sebelum `SubstituteBindings`** (sudah di-set di `bootstrap/app.php`). Tanpa ini route model binding bocor lintas tenant. Ditemukan & dibuktikan oleh test `tu_cannot_see_other_tenant_student_detail` (404).
3. **Laravel 13:** `$this->middleware()` di controller TIDAK ADA — otorisasi di route-level (`can:students.manage`)
4. **Tabel `school_classes`** (bukan `classes` — reserved word); model `SchoolClass`
5. **Composer merge-plugin** untuk autoload `Modules/*/composer.json` (BUKAN psr-4 `Modules\` manual — pernah error)
6. **Import wizard:** hasil validasi di-cache 30 menit by UUID token; commit = transaksi tunggal, baris error dilewati (no partial-commit per file)
7. **Normalisasi WA:** `08xx → 628xx` di semua titik input; regex valid `^628\d{7,12}$`
8. **Kwitansi:** `Payment::nextReceiptNo()` → `KW/{tenant}/{tahun}/{seq}` (sudah ada, dipakai Sprint 5)
9. **Status tenant** = state machine Doc 42 §5 langsung di enum DB (`prospect|contracted|active|grace_read|suspended|terminated`)
10. Tailwind via CDN untuk dev; build Vite ditunda ke Sprint 6

---

## 3. PETA REPO & FILE PENTING (Lokasi Workspace)

```
/home/user/SIMSEKOLAH2026/
├── session3_mvp_5jt/          # Doc 37–46 (dokumen MVP + laporan sprint + memory ini)
├── simt-backend/              # Laravel 13 — INTI PROYEK
│   ├── app/Support/Tenancy.php                  # singleton konteks tenant
│   ├── app/Models/Concerns/BelongsToTenant.php  # global scope + auto-fill
│   ├── app/Models/ (Tenant, TenantModule, Invoice, User, SchoolYear,
│   │                SchoolClass, Student, Attendance, Bill, Payment, WaNotification)
│   ├── app/Http/Middleware/ (IdentifyTenant, SetTenantFromUser,
│   │                         EnsureUserBelongsToTenant, EnsureModuleActive)
│   ├── app/Services/TenantRoleService.php       # provision 6 role/tenant
│   ├── bootstrap/app.php                        # alias + PRIORITY middleware (jangan ubah urutan!)
│   ├── Modules/Core/      (AuthController, DashboardController, dashboard view, routes)
│   ├── Modules/Student/   (Student/Class/Year/Import controllers,
│   │                       Services/StudentImportService.php, 7 views, routes)
│   ├── database/migrations/  (10 migrasi: 2026_06_12_1100xx_*)
│   ├── database/seeders/  (RolePermissionSeeder, DemoTenantSeeder, PitchingDemoSeeder)
│   ├── routes/api.php     # /v1: ping, auth/login, me, me/children, finance|attendance ping
│   ├── tests/Feature/     (TenantIsolationTest 8 test, StudentModuleTest 9 test, ExampleTest 2)
│   └── .github/workflows/ci.yml
└── simt-portal/               # Next.js 14 skeleton (app/page.tsx login, lib/api.ts)
```

**DB saat ini (SQLite dev):** 2 tenant + 106 siswa + 106 wali + 100 `wa_notifications` queued (credential) di MTs Al-Hikmah.

### Akun Demo (password semua: `password`)
| Akun | Login | Peran |
|---|---|---|
| Vendor | `vendor@simt.id` | superadmin lintas tenant |
| Ahmad | `ahmad@mts-alhikmah.sch.id` | admin_sekolah @ T1 (mts-alhikmah) |
| Ahmad | `ahmad@mts-annur.sch.id` | guru @ T2 (mts-annur, TANPA modul finance) |
| Siti Maryam | `siti@mts-alhikmah.sch.id` | guru / wali kelas 7A |
| Wali contoh | phone `628520000001` | wali (API portal) |

### Matriks Role → Permission (RolePermissionSeeder::ROLE_MATRIX)
`admin_sekolah`=semua · `kepala_madrasah`=dashboard+view/recap presensi · `tu`=students.* + attendance view/recap + wa.connect · `bendahara`=bills/payments/arrears · `guru`=students.view + attendance.mark/view · `wali`=ownership-based (tanpa permission admin)

---

## 4. ⚠️ SETUP ULANG SANDBOX (WAJIB di awal sesi berikutnya!)

PHP/Composer/vendor **TIDAK ikut snapshot** workspace. Jalankan dulu:
```bash
sudo apt-get update -qq && sudo apt-get install -y -qq php-cli php-mbstring php-xml php-curl php-sqlite3 php-zip php-gd unzip
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
cd /home/user/SIMSEKOLAH2026/simt-backend && composer install --no-interaction
php artisan test          # HARUS: 19 passed (46 assertions) sebelum mulai coding
```
(`.env`, `database/database.sqlite`, dan semua kode ikut snapshot — tidak perlu migrate ulang kecuali ingin fresh.)

---

## 5. STATUS SPRINT (Doc 40) — POSISI SEKARANG

| Sprint | Scope | Status |
|---|---|---|
| S1 (Mg 1–2) | Core + Tenancy + RBAC | ✅ SELESAI (Doc 44) |
| S2 (Mg 3–4) | Kesiswaan + Import Excel | ✅ SELESAI (Doc 45) |
| **S3 (Mg 5–6)** | **Presensi UI + Rekap** | ⬅️ **BERIKUTNYA** |
| S4 (Mg 7–8) | WA Gateway Baileys + Queue | ⬜ |
| S5 (Mg 9–10) | Keuangan SPP + Portal Ortu | ⬜ |
| S6 (Mg 11–12) | UAT + Go-Live + Onboarding | ⬜ |

---

## 6. LANGKAH SELANJUTNYA: SPRINT 3 — MODUL PRESENSI (Rencana Eksekusi Detail)

Target gate: **"Guru absen 1 kelas (40 siswa) ≤ 60 detik dari HP"** + demo live ke calon sekolah.

| ID | Task | Catatan implementasi |
|---|---|---|
| S3-01 | `php artisan module:make Attendance` + registrasi merge-plugin | Migration `attendances` SUDAH ADA sejak Sprint 1 (uniq student+date, index (tenant,class,date)) — tinggal pakai |
| S3-02 | **Grid presensi per kelas** (Blade): pilih kelas → semua siswa default `H`, tap siklus H→A→I→S→T, bulk save 1 POST | Route guru: hanya kelas yang diampu ATAU semua jika `attendance.mark` + cek wali kelas; simpan via upsert `(student_id, date)`; flash "X notif diantrikan" |
| S3-03 | Edit presensi hari berjalan + audit `marked_by` | Upsert menimpa; tampilkan siapa & kapan terakhir input |
| S3-04 | **Hook notifikasi**: setelah save → buat `WaNotification` type `attendance` per siswa (semua status jika `tenant.wa_notify_present`, selain H jika false) | Template: "Ananda {nama} {status_label} pukul {jam}" — worker pengirim baru aktif Sprint 4 |
| S3-05 | Rekap bulanan per kelas/siswa + export CSV/Excel | Query agregat per (student, status) bulan berjalan; pakai maatwebsite/excel (sudah ter-install) |
| S3-06 | Dashboard kepala: % kehadiran hari ini + tren 7 hari | Tambah card di Core dashboard, gate `can:attendance.recap` |
| S3-07 | API portal: `GET /v1/students/{id}/attendances?month=YYYY-MM` | WAJIB cek ownership: siswa harus anak dari `$request->user()` (guardian_student) — bukan sekadar permission |
| S3-08 | Test: guru tandai kelas, wali lihat kalender anak sendiri (bukan anak lain), isolasi tenant, modul off = 403 | Pola test ikuti `StudentModuleTest` |

**Definition of Done S3:** test suite hijau (target ±28 test), smoke test live grid presensi via curl/server, rekap bulanan berisi data 106 siswa demo, laporan `46→47_sprint3_execution_report.md`.

**Setelah S3 → S4 (paling berisiko):** service Node.js Baileys multi-session di `simt-wa-gateway/` (repo baru), endpoint `/session/{tenant}/qr|status` + `/send`, Laravel queue worker dengan rate-limit 10/mnt + jitter 3–8 dtk + retry 3× — `wa_notifications` yang sudah menumpuk (100 credential + hasil presensi S3) jadi data uji nyata.

---

*Dokumen ini dibuat agar sesi berikutnya cukup membaca Doc 46 (ini) + Doc 40 (sprint plan) + Doc 45 (laporan S2) untuk melanjutkan tanpa kehilangan konteks. Semua keputusan di §2 bersifat FINAL kecuali ada blocker teknis nyata.*
