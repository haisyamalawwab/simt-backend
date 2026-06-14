# TASK BREAKDOWN & SPRINT PLAN — MVP 3 BULAN
## SIMT MTs — 6 Sprint × 2 Minggu, 1 Founder-Developer (AI-Assisted)

**Versi:** 1.0 | **Tanggal:** 12 Juni 2026 | **Status:** FINAL
**Referensi:** Doc 37–39, Doc 07, Doc 23

Kapasitas: ±25 jam efektif/minggu (solo + AI-assisted) → ±50 jam/sprint.
Estimasi memakai jam (h). Total rencana ±268h dari kapasitas 300h (buffer ~11%).

---

## SPRINT 1 (Minggu 1–2) — FOUNDATION: Tenancy + RBAC
**Goal:** Login multi-tenant aman, role per sekolah berfungsi.

| ID | Task | Est. | Output / DoD |
| :-- | :--- | :-: | :--- |
| S1-01 | Init repo Laravel 10 + nwidart + Spatie (teams) + Sanctum + Pint/PHPStan | 4h | `composer install` bersih, CI lint jalan |
| S1-02 | Migration: `tenants`, `tenant_modules`, `invoices`, modif `users` (+phone, tenant_id) | 4h | migrate fresh sukses |
| S1-03 | Trait `BelongsToTenant` (global scope + auto-fill) + middleware `IdentifyTenant` (subdomain & header) | 6h | unit test scope lolos |
| S1-04 | Konfigurasi Spatie teams: resolver `team_id` dari tenant aktif; seeder 6 role + permissions | 5h | role per-tenant terbukti (kasus Guru Ahmad) |
| S1-05 | Auth: login Blade (admin) + `POST /v1/auth/login` (API) + rate limit | 5h | login 2 jalur OK |
| S1-06 | Layout Blade dasar (Tailwind, sidebar per-role, mobile-first) | 6h | dashboard kosong per role |
| S1-07 | Panel Super-Admin: CRUD tenant + toggle modul + catat invoice | 8h | tenant baru aktif <5 menit |
| S1-08 | **Test isolasi tenant otomatis** (cross-tenant via UI & API) | 5h | 0 kebocoran, masuk CI |
| S1-09 | Setup dev environment + GitHub Actions (lint+test) | 3h | CI hijau |
|  | **Subtotal** | **46h** | |

✅ **Sprint Review Gate:** 2 tenant demo, 1 user dengan role beda di tiap tenant, isolasi terbukti.

---

## SPRINT 2 (Minggu 3–4) — MODUL KESISWAAN
**Goal:** Master data lengkap + import Excel andal.

| ID | Task | Est. | Output / DoD |
| :-- | :--- | :-: | :--- |
| S2-01 | Module `Student`: migrations (`school_years`, `classes`, `students`, `class_student`, `guardian_student`) | 5h | ERD Doc 39 terimplementasi |
| S2-02 | CRUD Tahun Ajaran & Kelas (Blade) | 5h | wali kelas ter-assign |
| S2-03 | CRUD Siswa (list + search + filter kelas, form) | 7h | paginasi cepat 500 data |
| S2-04 | Import Excel wizard 3 langkah (Laravel Excel): upload → preview+error/baris → commit | 10h | file 100 baris <30 dtk, error jelas |
| S2-05 | Relasi Wali: tambah wali per siswa, generate akun massal (password acak) | 6h | akun wali siap kirim WA (Sprint 4) |
| S2-06 | API portal: `GET /me/children` | 3h | kontrak Doc 39 |
| S2-07 | Seeder demo realistis (1 sekolah, 3 kelas, 100 siswa) untuk pitching | 3h | data demo meyakinkan |
| S2-08 | Test modul (isolasi + happy path import) | 4h | CI hijau |
|  | **Subtotal** | **43h** | |

✅ **Gate:** Import 100 siswa dari template Excel sungguhan tanpa error.

---

## SPRINT 3 (Minggu 5–6) — MODUL PRESENSI (UI + Rekap)
**Goal:** Guru absen ≤60 detik; rekap bulanan jadi.

| ID | Task | Est. | Output / DoD |
| :-- | :--- | :-: | :--- |
| S3-01 | Module `Attendance`: migration `attendances` (uniq student+date) + model+scope | 4h | |
| S3-02 | UI Grid presensi per kelas (default Hadir, tap toggle status, bulk save) | 10h | stopwatch ≤60 dtk/40 siswa di HP |
| S3-03 | Edit presensi hari berjalan + audit `marked_by` | 4h | |
| S3-04 | Rekap bulanan per kelas/siswa + export Excel | 7h | |
| S3-05 | Dashboard Kepala: % kehadiran hari ini & tren 7 hari | 5h | |
| S3-06 | API portal: `GET /students/{id}/attendances?month=` | 4h | |
| S3-07 | 🚩 **NON-CODING: Mulai pitching!** Susun deck dari Doc 34, kontak 10 sekolah, jadwalkan demo | 8h | ≥3 demo terjadwal |
| S3-08 | Sewa & hardening VPS-1 (Ubuntu 22.04, Nginx, MySQL, Redis, UFW, fail2ban) — staging dulu | 6h | staging live |
|  | **Subtotal** | **48h** | |

✅ **Gate:** Demo presensi live di HP ke calon sekolah pertama.

---

## SPRINT 4 (Minggu 7–8) — WA GATEWAY (KILLER FEATURE)
**Goal:** Notifikasi WA stabil end-to-end.

| ID | Task | Est. | Output / DoD |
| :-- | :--- | :-: | :--- |
| S4-01 | Service Node.js Baileys multi-session: start/qr/status/send + auth state per tenant | 12h | 2 sesi tenant simultan stabil |
| S4-02 | Pengaman API key internal + systemd + auto-reconnect | 4h | restart otomatis <30 dtk |
| S4-03 | Halaman "WA Connect" di Blade: QR live (poll), status sesi, tombol reset | 6h | TU non-teknis bisa scan sendiri |
| S4-04 | Laravel Queue `SendWaNotification`: rate-limit 10/menit, jitter 3–8 dtk, retry 3× backoff, log `wa_notifications` | 8h | uji 100 pesan: ≥95% delivered |
| S4-05 | Hook presensi → notif (template per status, toggle notif-Hadir per tenant) | 4h | |
| S4-06 | Kirim kredensial akun wali massal via WA | 3h | |
| S4-07 | Template pesan editable per tenant (variabel) | 4h | |
| S4-08 | Sewa VPS-2 (1GB) + deploy gateway + uji ketahanan 48 jam | 4h | uptime 48 jam tanpa drop sesi |
| S4-09 | 🚩 NON-CODING: lanjut pitching — target 2 MoU draft beredar (Doc 36) | 6h | |
|  | **Subtotal** | **51h** | ⚠ sprint terpadat — fitur P1 lain boleh digeser |

✅ **Gate:** Skenario absen→WA <5 menit terbukti dengan nomor WA asli.

---

## SPRINT 5 (Minggu 9–10) — KEUANGAN-LITE + PORTAL ORTU
**Goal:** Uang tercatat rapi; orang tua punya "aplikasi".

| ID | Task | Est. | Output / DoD |
| :-- | :--- | :-: | :--- |
| S5-01 | Module `Finance`: migrations `bills`, `payments` + generate tagihan massal | 6h | |
| S5-02 | UI Catat pembayaran (parsial OK) + kwitansi PDF bernomor (dompdf) | 8h | |
| S5-03 | Rekap tunggakan + trigger WA pengingat (template sopan) | 5h | |
| S5-04 | Next.js portal: setup + login Sanctum + selector anak | 6h | |
| S5-05 | Next.js: kalender presensi + halaman tagihan/riwayat + unduh kwitansi | 10h | |
| S5-06 | PWA manifest + service worker + placeholder "Segera Hadir" (Nilai/Tahfiz) | 4h | installable di Android |
| S5-07 | Deploy portal ke VPS-1 (PM2 + Nginx reverse proxy) | 3h | |
| S5-08 | 🚩 NON-CODING: finalisasi MoU + invoice prepaid ke sekolah yang deal | 4h | ≥2 MoU ditandatangani |
|  | **Subtotal** | **46h** | |

✅ **Gate:** E2E penuh: absen → WA → bayar SPP → kwitansi → terlihat di portal ortu.

---

## SPRINT 6 (Minggu 11–12) — UAT, GO-LIVE & ONBOARDING PILOT
**Goal:** 3–5 sekolah live, invoice cair, BEP.

| ID | Task | Est. | Output / DoD |
| :-- | :--- | :-: | :--- |
| S6-01 | UAT internal: jalankan 4 Gate Acceptance (Doc 38 §4) + perbaikan bug | 8h | semua gate lolos |
| S6-02 | Hardening produksi: backup harian otomatis + restore drill + monitoring uptime gratis (UptimeRobot) | 5h | restore <60 menit terbukti |
| S6-03 | Onboarding sekolah #1–#3: import data, scan WA, training TU+guru (paket jasa sesuai tarif Doc 36) | 12h | sekolah live |
| S6-04 | Materi training: video 10 menit + PDF panduan 1 halaman/role | 6h | mengurangi beban support |
| S6-05 | Minggu pendampingan: standby WA support, fix bug lapangan | 8h | respon <4 jam kerja |
| S6-06 | Penagihan: kirim invoice, konfirmasi pembayaran prepaid semester | 2h | **cash-in ≥ Rp 6,6 jt** |
| S6-07 | Retrospective + backlog Fase 2 (Tahfiz, Nilai, Payment Gateway add-on) | 3h | dokumen next-phase |
|  | **Subtotal** | **44h** | + buffer sisa untuk bug lapangan |

✅ **EXIT CRITERIA MVP (hari ke-90):**
- [ ] ≥3 sekolah live & membayar prepaid (cash-in ≥ Rp 6,6 jt → BEP)
- [ ] WA delivery ≥95%, uptime ≥99% selama 2 minggu terakhir
- [ ] 4 Acceptance Gate (Doc 38) lolos terdokumentasi
- [ ] Sisa kas > Rp 0 dari budget Rp 5 jt

---

## RINGKASAN BEBAN & JALUR KRITIS

```
Jam per sprint :  S1=46  S2=43  S3=48  S4=51  S5=46  S6=44   Σ=278h/300h
Jalur kritis   :  S1 Tenancy ─► S2 Siswa ─► S3 Presensi ─► S4 WA ─► S6 Go-Live
                  (S5 Keuangan/Portal bisa diparalelkan sebagian dengan S4)
Aturan potong  :  Jika terlambat >1 minggu di akhir S4 → korbankan semua P1
                  (FR-C07/08, S05, P07, K05, O04) demi tanggal go-live.
```
