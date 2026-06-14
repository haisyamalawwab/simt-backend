# SESSION CONTEXT & HANDOVER — SIMT MTs
## Konteks Lengkap untuk Melanjutkan di Sesi Berikutnya

**Tanggal sesi:** 14 Juni 2026
**Agent:** Arena Agent Mode
**Tujuan dokumen:** Agar sesi/agent berikutnya bisa LANGSUNG lanjut tanpa kehilangan konteks. **BACA INI DULU**, lalu Doc 54 & 55.

---

## 0. CARA CEPAT MELANJUTKAN (mulai dari sini)

```bash
cd /home/user/simt-backend

# 1) WAJIB: vendor & .git sering hilang antar-sesi (di-exclude snapshot)
ls vendor/autoload.php || composer install        # jika error "Class Illuminate\Foundation\Application not found"

# 2) Setup DB dev (SQLite)
cp .env.example .env 2>/dev/null; php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed                   # 106 siswa demo

# 3) Pastikan sehat
php artisan test                                   # HARUS 23 passed (51 assertions)
php artisan module:list                            # 4 modul [Enabled]

# 4) (opsional) clone ulang dokumen master dari Drive bila perlu referensi
pip install gdown -q
gdown --folder "https://drive.google.com/drive/folders/1KzGGH8oEg3zyD9SkJQUDa9VZOFF4LKHk" -O /home/user/DEV_DOCS
```

**Baca berurutan:** Doc 56 (ini) → Doc 54 (stabilisasi) → Doc 55 (patch SQL) → `ARSITEKTUR_MODUL_CORE_vs_PLUGNPLAY.md`.

---

## 1. RINGKAS PROYEK (1 paragraf)

SIMT MTs = Micro-SaaS B2B2C multi-tenant manajemen madrasah (MTs/SMP yayasan). Laravel 11 + MySQL(prod)/SQLite(test) + nwidart modules + Spatie RBAC teams + Sanctum + Baileys WA (zero-cost). Single-DB multi-tenant (`tenant_id` + global scope). Hybrid: Blade (admin/guru) + Next.js (portal ortu). Budget MVP Rp 5jt / 3 bulan, 6 sprint.

---

## 2. APA YANG SUDAH DIKERJAKAN DI SESI INI

### A. Analisis awal repo vs dokumentasi
- Clone DEV_DOCS (Google Drive, gdown) + repo GitHub `haisyamalawwab/simt-backend`.
- Temuan: repo `main` punya 3 bug runtime kritis (banyak halaman 500).

### B. Stabilisasi Sprint 1-2-3 (detail di Doc 54)
- 🔴 Fix `app('currentTenant')` tak ter-bind → ganti ke `Tenancy` singleton + bridge di AppServiceProvider.
- 🔴 Fix NIS/NISN tidak unik → `UNIQUE(tenant_id, nis/nisn)` di migration + validasi controller.
- 🟠 Hapus duplikasi controller `app/Http/Controllers/{Web,Api}` (sumber kebenaran = `Modules/`).
- 🟠 Fix bug double-submit presensi (cast `date:Y-m-d` + Carbon normalize).
- Rename tabel `classes` → `school_classes` (mandat anti-reserved-word).
- Buat view yang hilang: `admin/student/{index,create,edit}`, `admin/attendance/rekap`.
- Tambah method `classGrid` (route `/attendance/class/{class}`).
- Fix query rekap MySQL-only `DATE_FORMAT` → `whereBetween` portable.

### C. Modularisasi (detail di ARSITEKTUR_MODUL doc)
- Pisahkan **Finance** jadi modul nwidart mandiri (`Modules/Finance/`) — sebelumnya nempel di Attendance.
- Plug & play 2 lapisan TERBUKTI: nwidart (kode) + `tenant_modules` (langganan per-tenant).
- 4 modul: **Core** (🔒 inti) + Student, Attendance, Finance (🔌 plug & play).

### D. Testing
- **23 passed (51 assertions)**: TenantIsolation(8) + Student(8) + Attendance(5, BARU) + Example(2).

### E. Patch & validasi `.sql` MySQL (detail di Doc 55)
- `.sql` user (commit `5e0fa3a`) dipatch: `classes`→`school_classes` + NISN/NIS UNIQUE, seed dipertahankan.
- Divalidasi import ke MariaDB 11.8 (zero error) + Laravel jalan di atasnya.

### F. Dokumentasi dibuat
- README.md (ganti default Laravel) + `DEV_DOCS/`: 54, 55, 56(ini), API_CONTRACT, DATABASE_SCHEMA, ARSITEKTUR_MODUL, PANDUAN_BUAT_MODUL.

---

## 3. KEPUTUSAN TEKNIS YANG SUDAH DIAMBIL (jangan diubah tanpa alasan)

| # | Keputusan | Alasan |
|---|---|---|
| 1 | **Laravel 11** (bukan 13) | User: 13 terlalu dini/tinggi secara teknis |
| 2 | **SQLite** dev/test, **MySQL** produksi | `.sql` MySQL = acuan produksi |
| 3 | Tabel kelas = **`school_classes`** (bukan `classes`) | Hindari reserved word MySQL/PgSQL |
| 4 | Sumber kebenaran controller = **`Modules/`** | Hapus duplikat legacy `app/` |
| 5 | **Finance = modul plug & play terpisah** | Sesuai Doc 28 |
| 6 | **Core = modul inti**, tak pernah di-gate `module.active` | Tanpa Core sistem mati |
| 7 | Provider modul dikelola **nwidart** (bukan force di bootstrap/providers.php) | Plug & play sejati |
| 8 | Tenancy = **singleton**; pakai `app(Tenancy::class)->tenant()`, BUKAN `app('currentTenant')` | Mandat Doc 53 |
| 9 | Web route pakai `SetTenantFromUser`; API pakai `IdentifyTenant` | session vs header |

---

## 4. STATUS SPRINT (Doc 40)

| Sprint | Status | Catatan |
|---|---|---|
| 1 — Tenancy + RBAC | ✅ SELESAI TOTAL | login web+API, isolasi, ping 200 |
| 2 — Kesiswaan + Import | ✅ SELESAI TOTAL | CRUD+view, import 3-langkah, NISN unik |
| 3 — Presensi + Rekap | ✅ TERIMPLEMENTASI | grid, classGrid, rekap, audit, API portal, 5 test |
| 4 — WA Gateway (Baileys) | 🔜 BERIKUTNYA | belum mulai |
| 5 — Keuangan-Lite + Portal Ortu | ⏳ Finance-lite ADA; portal Next.js belum | repo terpisah |
| 6 — UAT + Go-Live | ⏳ | — |

---

## 5. NEXT — SPRINT 4: WA GATEWAY (BAILEYS)

Target (Doc 40 §S4): Notifikasi WA stabil end-to-end.

- [ ] **S4-01** Service Node.js Baileys multi-session (start/qr/status/send), auth state per tenant — **repo/folder TERPISAH** (mis. `simt-wa-gateway/`, VPS-2). 12h.
- [ ] **S4-02** API key internal + systemd + auto-reconnect. 4h.
- [ ] **S4-03** Halaman "WA Connect" Blade (QR live poll, status sesi, reset) — di modul Core atau modul baru `Notification`. 6h.
- [ ] **S4-04** Laravel Queue `SendWaNotification` (Job SUDAH ADA di `app/Jobs/`) → sambung ke gateway: rate-limit 10/mnt, jitter 3-8 dtk, retry 3× backoff, log `wa_notifications`. 8h.
- [ ] **S4-05** Hook presensi → notif (SUDAH ADA di `AttendanceController::store`, perlu disambung ke gateway nyata). 4h.
- [ ] **S4-06** Kirim kredensial wali massal via WA (sudah diantrikan saat import). 3h.
- [ ] **S4-07** Template pesan editable per tenant. 4h.
- [ ] **Gate S4:** absen → WA < 5 menit dengan nomor WA asli.

**Aset relevan yang SUDAH ADA:**
- `app/Jobs/SendWaNotification.php` (job)
- Tabel `wa_notifications` (to_phone, type, payload, status, attempts, last_error, sent_at)
- Hook dispatch di `AttendanceController::store` (non-Hadir → antri WA)
- Normalisasi WA 08xx→628xx di `StudentImportService`
- Referensi: PDF `WhatsApp_Gateway_Runbook_SIMT_MTs.pdf` (di Drive), Doc 49/50 (sprint4 WA di Drive)

---

## 6. HUTANG TEKNIS / CATATAN PENTING

### 🔴 Hutang sinkronisasi GitHub (BELUM ter-push)
Folder `.git` selalu di-exclude dari snapshot workspace → agent TIDAK bisa commit/push. Semua hasil sesi ini ada di workspace tapi BELUM di remote `main`. Yang perlu disinkronkan (user lakukan manual atau via patch bundle):
- Fix kode (Doc 54), test AttendanceModuleTest, `.sql` patched (Doc 55), README + 6 dok DEV_DOCS.
- ⚠️ **Konflik merge:** remote `main` masih `classes` (migration lama). Saat merge, versi lokal `school_classes` HARUS menang.

### 🟠 Lingkungan sandbox (selalu cek di awal sesi)
- `vendor/` & `vendor/composer/installed.json` sering hilang → `composer install`.
- `.git` hilang → tidak ada riwayat git lokal; clone ulang remote bila perlu inspeksi.
- Clone DEV_DOCS Drive tidak persist → gdown ulang bila butuh dokumen master.
- PHP & MariaDB perlu di-install ulang tiap sesi baru (apt-get).

### 🟡 Hutang fitur kecil (P1/P2, non-blocker)
- Export Excel rekap presensi (FR-P06) — UI rekap ada, export belum.
- View modul Finance masih pakai `admin.finance.*` di root (boleh dipindah ke Modules/Finance/resources/views).
- `welcome.blade.php` typo `className=` (React-ism) — kosmetik.
- Tailwind via CDN (dev) — build Vite ditunda Sprint 6.
- Endpoint API `/api/v1/students/{student}/bills` masih placeholder (Sprint 5).

---

## 7. PETA FILE PENTING

```
simt-backend/
├── app/Support/Tenancy.php              ← singleton konteks tenant
├── app/Traits/BelongsToTenant.php       ← global scope + auto-fill
├── app/Http/Middleware/                 ← IdentifyTenant, SetTenantFromUser, CheckTenantAccess, EnsureModuleActive
├── app/Services/                        ← TenantRoleService, StudentImportService
├── app/Jobs/SendWaNotification.php      ← (Sprint 4)
├── app/Models/                          ← SchoolClass($table='school_classes'), Attendance(cast date:Y-m-d), ...
├── bootstrap/app.php                    ← middleware priority (tenant SEBELUM SubstituteBindings)
├── bootstrap/providers.php              ← HANYA AppServiceProvider (modul via nwidart)
├── database/migrations/0001_..._000012..016  ← school_classes, students(UNIQUE nis), attendances, dll
├── database/seeders/PitchingDemoSeeder.php    ← 106 siswa demo
├── Modules/{Core,Student,Attendance,Finance}/ ← nwidart modules
├── resources/views/admin/{student,attendance,finance,super}/  ← Blade
├── tests/Feature/{TenantIsolation,Student,Attendance}ModuleTest.php
├── simt-backend-mysql-migrate.sql       ← .sql produksi (PATCHED)
├── modules_statuses.json                ← {Core,Student,Attendance,Finance}=true
├── README.md                            ← onboarding (sudah custom)
└── DEV_DOCS/                            ← 54,55,56,API_CONTRACT,DATABASE_SCHEMA,ARSITEKTUR_MODUL,PANDUAN_BUAT_MODUL
```

---

## 8. AKUN DEMO (password: `password`)

| Login | Peran | Tenant |
|---|---|---|
| `vendor@simt.id` | superadmin | lintas |
| `ahmad@mts-alhikmah.sch.id` | admin_sekolah | T1 (modul lengkap) |
| `ahmad@mts-annur.sch.id` | guru | T2 (Core+Student saja) |
| phone `628520000001` | wali | T1 (portal) |

---

## 9. SUMBER REFERENSI (Google Drive — `gdown`)

Folder: `https://drive.google.com/drive/folders/1KzGGH8oEg3zyD9SkJQUDa9VZOFF4LKHk`
Dokumen kunci: Doc 28 (modular MVC), Doc 29 (API general vs plugins), Doc 38/39 (SRS/Design MVP), Doc 40 (sprint plan), Doc 49/50 (WA gateway sprint 4), Doc 53 (context session 4), PDF WhatsApp Gateway Runbook + ADR.

---

*Handover ini ditulis 14 Juni 2026. Semua status diverifikasi dengan menjalankan kode. Lanjutkan dari §0 & §5.*
