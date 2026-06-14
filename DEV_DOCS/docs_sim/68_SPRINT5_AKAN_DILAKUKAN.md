Saya akan cek Doc 40 dan status live repo untuk Sprint 5 (Keuangan-Lite + Portal Ortu).

# 🗓️ SPRINT 5 — Apa yang Perlu Dilanjutkan

## ✅ Status Live Saat Ini (Repo @ `3cbe997`)

**Subtotal Sprint 5 yang SUDAH selesai:** 19 jam dari 46 jam = **41% selesai**

| Task | Status | Bukti |
|---|---|---|
| **S5-01** Module `Finance` + generate tagihan massal | ✅ **SELESAI** | `Modules/Finance/` lengkap + `FinanceController::generateBills()` line 38 |
| **S5-02** UI Catat pembayaran + Kwitansi PDF | ✅ **SELESAI** | `recordPayment()` line 70 + `printReceipt()` PDF + DomPDF + `KW/{tenant}/{year}/{seq}` |
| **S5-03** Rekap tunggakan + trigger WA pengingat | ✅ **SELESAI** | `sendReminders()` line 108 + antri ke `wa_notifications` |
| **S5-04** Next.js portal setup | ❌ BELUM | Folder `simt-portal/` tidak ada |
| **S5-05** Next.js kalender presensi + tagihan | ❌ BELUM | — |
| **S5-06** PWA manifest + service worker | ❌ BELUM | — |
| **S5-07** Deploy portal VPS-1 | ❌ BELUM | — |
| **S5-08** Non-coding MoU + invoice | ❌ BELUM | Bisnis/legal |

---

## 🎯 Yang Perlu Dilanjutkan di Sprint 5

### 🥇 OPSI A: Portal Next.js (S5-04..07) — **REKOMENDASI SAYA**

**Estimasi:** 23 jam coding (S5-04..07) + 4 jam bisnis (S5-08) = **27 jam**

| Task ID | Detail | Est |
|---|---|---|
| **S5-04** | Setup Next.js 14 (App Router) + login Sanctum + selector anak | 6h |
| **S5-05** | Halaman: kalender presensi + tagihan/riwayat + unduh kwitansi | 10h |
| **S5-06** | PWA manifest + service worker + placeholder "Segera Hadir" (Nilai/Tahfiz) | 4h |
| **S5-07** | Deploy portal ke VPS-1 (PM2 + Nginx reverse proxy) | 3h |
| **S5-08** | Non-coding: finalisasi MoU + invoice prepaid | 4h |

**Tech stack Portal:**
- Next.js 14 (App Router)
- TypeScript
- Tailwind CSS + shadcn/ui
- PWA (manifest.json + service worker)
- Backend API: Laravel (sudah ada)

**Struktur Portal:**
```
simt-portal/                  (repo TERPISAH)
├── app/
│   ├── login/                # Login via No. WA (FR-O01)
│   ├── dashboard/            # Pilih anak (kalau wali punya >1 anak)
│   ├── presensi/             # Kalender presensi anak
│   ├── tagihan/              # Daftar tagihan + riwayat bayar
│   └── kwitansi/[id]/        # Unduh kwitansi PDF
├── lib/
│   └── api.ts                # Client w/ header X-Tenant-Domain + Bearer
├── public/
│   ├── manifest.json         # PWA
│   └── sw.js                 # Service worker
├── package.json
└── next.config.mjs
```

**Gate S5:** E2E penuh: absen → WA → bayar SPP → kwitansi → terlihat di portal ortu.

### 🥈 OPSI B: Selesaikan Backend Finance dulu (lebih kecil)

**Estimasi:** 8-10 jam (memperkuat fondasi sebelum Portal)

| Task | Detail | Est |
|---|---|---|
| **B1** | Buat `FinanceApiController` + aktifkan endpoint `/api/v1/students/{student}/bills` | 2h |
| **B2** | Pindahkan view Finance ke `Modules/Finance/resources/views/` (Doc 56 §6.C) | 1h |
| **B3** | Tambah test Finance: `tests/Feature/FinanceModuleTest.php` (5-7 test) | 3h |
| **B4** | Generate tagihan massal dari Blade form (UI lebih lengkap) | 2h |
| **B5** | Export Excel rekap tagihan + tunggakan | 2h |

### 🥉 OPSI C: Build SIM Akademik Lite (Opsi A dari sesi sebelumnya)

**Estimasi:** 34 jam — bukan Sprint 5 sebenarnya, tapi sering dianggap bagian dari Sprint 5 jika bicara "fitur ortu".

| Task | Detail | Est |
|---|---|---|
| Migration: `subjects`, `grades` | 4h |
| CRUD Subject | 6h |
| Input nilai sederhana | 8h |
| Cetak rapor PDF | 12h |
| Tests | 4h |

---

## 📊 Perbandingan 3 Opsi

| Opsi | Total Jam | Keterangan | ROI |
|---|---|---|---|
| **A: Portal Next.js** | 27h | Lengkapi Sprint 5 (Gate E2E) | 🟢 Tinggi |
| **B: Backend Finance Robust** | 8-10h | Fondasi kuat + tests | 🟡 Sedang-Tinggi |
| **C: SIM Akademik Lite** | 34h | Modul Akademik | 🟠 Sedang |

---

## 🎯 REKOMENDASI SAYA: **Opsi A + B (gabung)**

**Total estimasi:** 35-37 jam (4-5 hari kerja)

**Urutan eksekusi:**

### Phase 1: Backend Finance Robust (1-2 hari, 10h)
1. ✅ B1 — Aktifkan API `/api/v1/students/{student}/bills`
2. ✅ B2 — Pindahkan view ke `Modules/Finance/`
3. ✅ B3 — Tambah Finance tests
4. ✅ B4 — Polish UI generate tagihan
5. ✅ B5 — Export Excel tunggakan

### Phase 2: Portal Next.js (3-4 hari, 27h)
1. ✅ S5-04 — Setup Next.js + Sanctum login + selector anak
2. ✅ S5-05 — Halaman kalender presensi + tagihan + unduh kwitansi
3. ✅ S5-06 — PWA manifest + service worker
4. ✅ S5-07 — Deploy ke VPS-1
5. ✅ S5-08 — MoU + invoice (non-coding, paralel)

---

## 📁 API Endpoint yang Perlu Diaktifkan (Phase 1)

```php
// Modules/Finance/routes/api.php (saat ini commented)
Route::middleware(['auth:sanctum', IdentifyTenant::class, 'check.tenant.access', 'module.active:Finance'])->group(function () {
    Route::get('/v1/students/{student}/bills', [FinanceApiController::class, 'index']);
});
```

**`FinanceApiController` perlu dibuat** dengan method:
- `index($student)` — list tagihan & riwayat bayar anak (untuk portal ortu)
- `ownership check` — wali hanya bisa akses data anak sendiri
- `API Resources` (Doc 22 §1.2)

---

## 📁 Endpoint API yang Sudah Aktif untuk Portal

| Endpoint | Method | Module | Status |
|---|---|---|---|
| `/api/v1/auth/login` | POST | Core | ✅ |
| `/api/v1/me` | GET | Core | ✅ |
| `/api/v1/me/children` | GET | Core | ✅ (untuk wali pilih anak) |
| `/api/v1/students/{student}/attendances?month=` | GET | Attendance | ✅ |
| `/api/v1/students/{student}/bills` | GET | Finance | ❌ **Placeholder** |

---

## 🧪 Test yang Perlu Ditambahkan

| Test File | Test | Est |
|---|---|---|
| `tests/Feature/FinanceModuleTest.php` | bendahara can create bill | 1h |
| | payment records and updates bill status | 1h |
| | partial payment supported | 30min |
| | receipt PDF generated with correct format `KW/{tenant}/{year}/{seq}` | 30min |
| | finance module disabled returns 403 | 30min |
| | bills isolated per tenant | 30min |
| | `FinanceApiController` portal endpoint with ownership | 1h |

---

## 🗂️ File yang Akan Disentuh (Estimasi)

### Phase 1 — Backend Finance (10h)

```
Modules/Finance/
├── app/Http/Controllers/
│   ├── FinanceController.php            (existing, polish UI)
│   └── FinanceApiController.php         (NEW)
├── resources/views/
│   ├── bills.blade.php                  (move from /resources/views/admin/finance/)
│   └── generate.blade.php               (NEW: form generate tagihan)
├── routes/
│   ├── web.php                          (existing)
│   └── api.php                          (uncomment /v1/students/{student}/bills)
└── app/Exports/
    └── BillsRecapExport.php             (NEW: Excel export tagihan)

tests/Feature/
└── FinanceModuleTest.php                (NEW: 6-7 tests)
```

### Phase 2 — Portal Next.js (27h)

```
simt-portal/                             (NEW repo TERPISAH)
├── app/
│   ├── login/page.tsx                   (login via phone + password)
│   ├── layout.tsx
│   ├── dashboard/page.tsx               (selector anak)
│   ├── presensi/page.tsx                (kalender presensi)
│   ├── tagihan/page.tsx                 (daftar tagihan + bayar)
│   ├── tagihan/[id]/page.tsx            (detail tagihan)
│   └── kwitansi/[id]/page.tsx           (unduh PDF)
├── lib/
│   ├── api.ts                           (HTTP client + X-Tenant-Domain + Bearer)
│   └── auth.ts                          (Sanctum helpers)
├── public/
│   ├── manifest.json                    (PWA)
│   ├── icon-192.png
│   ├── icon-512.png
│   └── sw.js                            (service worker)
├── package.json
├── tsconfig.json
├── next.config.mjs
├── tailwind.config.ts
├── .env.example
└── README.md
```

---

## 📋 Checklist untuk Sprint 5

### Phase 1 — Backend Finance (10h)
- [ ] **B1** Buat `FinanceApiController` dengan ownership check + API Resources
- [ ] **B1** Aktifkan route `/api/v1/students/{student}/bills`
- [ ] **B2** Pindahkan `resources/views/admin/finance/bills.blade.php` → `Modules/Finance/resources/views/bills.blade.php`
- [ ] **B3** Tambah `tests/Feature/FinanceModuleTest.php` (6-7 test)
- [ ] **B4** Polish UI generate tagihan (form dengan filter siswa, due_date, dll)
- [ ] **B5** Buat `Modules/Finance/app/Exports/BillsRecapExport.php` (Export Excel)
- [ ] Verify: `php artisan test` (target 30-31 passed)

### Phase 2 — Portal Next.js (27h)
- [ ] **S5-04** Clone repo `simt-portal/` (TERPISAH dari `simt-backend/`)
- [ ] **S5-04** Setup Next.js 14 + App Router + TypeScript + Tailwind
- [ ] **S5-04** Buat login page (form No. WA + password → Sanctum token)
- [ ] **S5-04** Sanctum helper + HTTP client w/ header `X-Tenant-Domain`
- [ ] **S5-04** Halaman dashboard selector anak (kalau wali punya >1 anak)
- [ ] **S5-05** Halaman kalender presensi (fetch `/api/v1/students/{id}/attendances`)
- [ ] **S5-05** Halaman daftar tagihan (fetch `/api/v1/students/{id}/bills`)
- [ ] **S5-05** Halaman detail tagihan + bayar (mock payment)
- [ ] **S5-05** Halaman unduh kwitansi PDF
- [ ] **S5-06** Buat `public/manifest.json` + icons (192/512px)
- [ ] **S5-06** Service worker untuk offline capability
- [ ] **S5-06** Placeholder "Segera Hadir" untuk Nilai/Tahfiz
- [ ] **S5-07** Deploy ke VPS-1 (PM2 + Nginx reverse proxy + SSL via Cloudflare)
- [ ] **S5-08** (Non-coding) Finalisasi MoU + invoice prepaid ke sekolah yang deal

### Gate S5 (Final Acceptance)
- [ ] Absen di Blade → SPP di Finance → kwitansi PDF → Portal Next.js menampilkan
- [ ] Wali bisa login via No. WA
- [ ] Wali bisa lihat presensi & tagihan anak
- [ ] Wali bisa unduh kwitansi PDF
- [ ] Portal PWA installable di Android

---

## 🎯 Rekomendasi Final

**Saya rekomendasikan Opsi A+B (gabung):**

```
Phase 1: Backend Finance Robust       ~10h (1-2 hari)
Phase 2: Portal Next.js              ~27h (3-4 hari)
TOTAL                                ~37h (4-6 hari kerja)
```

**Alasan:**
1. **Sprint 4 sudah ditunda** → tidak ada blocker lain
2. **Finance sudah hampir lengkap** → tinggal poles + API + tests (~10h)
3. **Portal Next.js** → ini deliverables E2E Sprint 5 yang ditunggu ortu
4. **Gate S5 = E2E penuh** → Sprint 5 tidak "selesai" tanpa portal

Apakah Anda ingin saya:
- **(A)** Mulai **Phase 1** (Backend Finance Robust) sekarang (10h)
- **(B)** Mulai **Phase 2** (Portal Next.js) sekarang (27h)
- **(C)** Mulai **Phase 1 + 2** berurutan (37h)
- **(D)** Diskusikan strategi lain dulu (misalnya ganti Sprint 5 dengan Sprint Akademik Lite)
- **(E)** Anda mau assign agent lain untuk salah satu phase (parallel)?

Mana yang Anda pilih?