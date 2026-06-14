# PRODUCT REQUIREMENTS DOCUMENT (PRD) — MVP EDITION
## SIMT MTs — MVP 3 BULAN, MODAL Rp 5.000.000

**Versi:** 1.0
**Tanggal:** 12 Juni 2026
**Status:** FINAL — SIAP EKSEKUSI
**Penulis:** Founder / Product Owner
**Referensi:** Doc 04 (PRD Full), Doc 21–30 (Tech Arch), Doc 31–36 (Bisnis & Legal)

---

## 1. EXECUTIVE SUMMARY

```
┌─────────────────────────────────────────────────────────────────────┐
│                       MVP PRODUCT OVERVIEW                          │
├─────────────────────────────────────────────────────────────────────┤
│  Product Name :  SIMT MTs — MVP "Presensi + WA + SPP"               │
│  Product Type :  Micro SaaS B2B2C, Multi-Tenant (Single Database)   │
│  Target       :  Madrasah Tsanawiyah swasta (50–300 siswa)          │
│  Timeline     :  12 Minggu (3 Bulan) — Juni s.d. September 2026     │
│  Budget       :  Rp 5.000.000 (CASH HARD LIMIT)                     │
│  Tim          :  1 Founder-Developer (AI-Assisted Coding)           │
│                                                                     │
│  Launch Target:  Go-Live Tahun Ajaran Baru (Agustus–September 2026) │
│  Goal Bisnis  :  5 sekolah pilot × 100 siswa = BEP di Bulan ke-3    │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.1 Perbedaan Mendasar dengan PRD Full (Doc 04)

| Aspek | PRD Full (Doc 04) | PRD MVP (Doc 37 — ini) |
| :--- | :--- | :--- |
| Modul | 13 Modul ERP penuh | **4 Modul saja** (Core, Siswa, Presensi, Keuangan-Lite) |
| Timeline | 18 bulan | **3 bulan** |
| Tim | Tim multi-role | 1 Founder + AI tools |
| Frontend | Full SPA | Hybrid: Blade (Admin) + Next.js (Portal Ortu) |
| Integrasi | EMIS, DAPODIK, RDM | **Hanya Import Excel** (integrasi resmi = post-MVP) |
| Tahfiz/Inklusi | Modul unggulan | **Post-MVP** (Fase 2, sebagai upsell add-on) |
| Payment Gateway | Midtrans/Xendit | **Post-MVP** (MVP: pencatatan manual oleh TU) |

### 1.2 Problem Statement (MVP Lens)

Madrasah swasta kecil tidak butuh ERP. Mereka butuh **3 hal yang terasa nyata setiap hari**:
1. **TU**: rekap presensi & tunggakan SPP tanpa Excel manual.
2. **Orang Tua**: kepastian anak sampai di sekolah (notifikasi WA) + transparansi tagihan.
3. **Kepala Madrasah**: dashboard ringkas untuk laporan ke yayasan.

> **MVP Thesis:** Jika notifikasi WA presensi berjalan stabil selama 1 semester di 5 sekolah, produk ini *defensible* dan layak dikembangkan ke 13 modul.

---

## 2. SCOPE MVP (IN / OUT)

### 2.1 IN-SCOPE (4 Modul)

```
┌──────────────────────────────────────────────────────────────────┐
│ M1. CORE (Platform)                                              │
│     ├─ Multi-tenant single-DB (tenant_id + Global Scope)         │
│     ├─ Auth (Laravel Sanctum) + RBAC Spatie Teams (per sekolah)  │
│     ├─ Manajemen modul aktif per tenant (nwidart, Plug & Play)   │
│     └─ Panel Super-Admin Vendor (aktivasi tenant & invoice)      │
│                                                                  │
│ M2. KESISWAAN (Master Data)                                      │
│     ├─ CRUD Siswa, Kelas, Tahun Ajaran, Wali (relasi ortu-anak)  │
│     ├─ Import Excel (template dari Dapodik/EMIS export)          │
│     └─ Generate akun Wali otomatis (login via no. WA)            │
│                                                                  │
│ M3. PRESENSI + NOTIFIKASI WA  ★ KILLER FEATURE                   │
│     ├─ Input presensi per kelas oleh guru (Blade, 3 klik)        │
│     ├─ WA Gateway self-hosted (Baileys) — admin scan QR sendiri  │
│     ├─ Notif WA otomatis: Hadir / Alpa / Izin (queue + retry)    │
│     └─ Rekap bulanan per siswa/kelas (export Excel)              │
│                                                                  │
│ M4. KEUANGAN-LITE (SPP)                                          │
│     ├─ Setup tagihan SPP per tahun ajaran                        │
│     ├─ Pencatatan pembayaran MANUAL oleh TU + kwitansi PDF       │
│     ├─ Notif WA pengingat tunggakan (template sopan)             │
│     └─ Portal Ortu (Next.js): lihat presensi + tagihan + nilai*  │
│        (*nilai = placeholder, diisi di Fase 2)                   │
└──────────────────────────────────────────────────────────────────┘
```

### 2.2 OUT-OF-SCOPE (Eksplisit DITOLAK di MVP — Anti Scope Creep, ref. Doc 36)

- ❌ Modul Tahfiz, Inklusi/PDBK, Perpustakaan, Sarpras, Kepegawaian, PPDB online
- ❌ Integrasi resmi EMIS/DAPODIK/RDM (hanya import Excel)
- ❌ Payment Gateway (Midtrans/Xendit BYOA) → Add-on Fase 2
- ❌ Custom UI / custom report per sekolah (terkunci di MoU Doc 36)
- ❌ Mobile app native (Portal Ortu = PWA Next.js)
- ❌ WhatsApp API resmi berbayar (pakai Baileys self-hosted, Zero-Cost WA)

---

## 3. BUDGET PLAN Rp 5.000.000 (HARD CAP)

| # | Pos Biaya | Bulan 1 | Bulan 2 | Bulan 3 | Total |
| :- | :--- | ---: | ---: | ---: | ---: |
| 1 | VPS 2GB (production, mulai Bulan 2) | 0 | 300.000 | 300.000 | 600.000 |
| 2 | VPS 1GB staging/WA gateway (Bulan 2–3) | 0 | 150.000 | 150.000 | 300.000 |
| 3 | Domain .id + .com (1 tahun) | 350.000 | 0 | 0 | 350.000 |
| 4 | AI Coding Assistant (3 bulan) | 300.000 | 300.000 | 300.000 | 900.000 |
| 5 | Nomor WA khusus + paket data demo | 150.000 | 100.000 | 100.000 | 350.000 |
| 6 | Marketing: brosur, transport pitching 10 sekolah | 0 | 300.000 | 500.000 | 800.000 |
| 7 | Legal: materai MoU ×10, print kontrak | 0 | 0 | 200.000 | 200.000 |
| 8 | Onboarding pilot (transport, pulsa, konsumsi) | 0 | 0 | 500.000 | 500.000 |
| 9 | **Buffer darurat (20%)** | — | — | — | 1.000.000 |
| | **TOTAL** | 800.000 | 1.150.000 | 2.050.000 | **5.000.000** |

**Aturan kas (ref. Doc 33/35):**
1. Bulan 1 development di **laptop lokal + free tier** — VPS baru disewa saat ada calon pilot serius.
2. Buffer 1 juta TIDAK boleh disentuh kecuali server down / WA banned.
3. Target: invoice pertama (Rp 1jt setup + Rp 1,2jt sewa prepaid/sekolah) cair ≤ minggu ke-12 → **BEP sebelum modal habis**.

---

## 4. PERSONAS & USER STORIES (MVP ONLY)

| Persona | User Story Inti | Prioritas |
| :--- | :--- | :--- |
| **Guru** | "Saya absen 1 kelas dalam <60 detik dari HP." | P0 |
| **Orang Tua** | "Saya dapat WA saat anak hadir/alpa, dan bisa cek tagihan SPP." | P0 |
| **TU/Bendahara** | "Saya catat pembayaran SPP, kwitansi otomatis, rekap tunggakan 1 klik." | P0 |
| **Kepala Madrasah** | "Saya lihat dashboard kehadiran & kas hari ini." | P1 |
| **Super Admin (Vendor)** | "Saya aktifkan tenant baru + set modul + cek status invoice dalam 5 menit." | P0 |

---

## 5. SUCCESS METRICS (Definition of Done MVP)

| Metric | Target Minggu-12 |
| :--- | :--- |
| Sekolah pilot tanda tangan MoU (Doc 36) | ≥ 3 (target 5) |
| Siswa aktif dalam sistem | ≥ 300 |
| Delivery rate notifikasi WA | ≥ 95% dalam 5 menit |
| Waktu input presensi 1 kelas (40 siswa) | ≤ 60 detik |
| Uptime production | ≥ 99% (maks. ~7 jam down/bulan) |
| Cash-in (invoice terbayar) | ≥ Rp 6.600.000 (3 sekolah × Rp 2,2jt) |
| Sisa budget pada hari ke-90 | > Rp 0 (tidak overbudget) |

---

## 6. TIMELINE RINGKAS (Detail di Doc 40)

```
 Bulan 1 (Minggu 1–4)   Bulan 2 (Minggu 5–8)    Bulan 3 (Minggu 9–12)
┌─────────────────────┬─────────────────────────┬─────────────────────────┐
│ FOUNDATION          │ KILLER FEATURE          │ MONETIZE & LAUNCH       │
│ Sprint 1: Core +    │ Sprint 3: Presensi UI   │ Sprint 5: SPP + Portal  │
│   Tenancy + RBAC    │ Sprint 4: WA Gateway    │   Ortu (Next.js)        │
│ Sprint 2: Siswa +   │   Baileys + Notif Queue │ Sprint 6: UAT, Deploy,  │
│   Import Excel      │ + mulai pitching        │   Onboarding 3–5 pilot  │
└─────────────────────┴─────────────────────────┴─────────────────────────┘
                        ▲ VPS mulai dibayar       ▲ Invoice cair → BEP
```

---

## 7. RISIKO UTAMA & MITIGASI (ringkas, ref. Doc 31/33)

| Risiko | Dampak | Mitigasi |
| :--- | :--- | :--- |
| WA Baileys di-banned Meta | Killer feature mati | Nomor milik sekolah (bukan vendor) + klausul liabilitas Doc 36 + rate-limit & jeda human-like |
| Solo founder sakit/burnout | Timeline molor | Scope dikunci 4 modul; AI-assisted; buffer 1 minggu di Sprint 6 |
| Sekolah menolak bayar di depan | Cash-flow mati | Tanpa prepaid 1 semester = tidak go-live (aturan Doc 32, no free trial penuh) |
| Budget bocor | Proyek mangkrak | Review kas mingguan; VPS ditunda sampai ada pilot serius |

---

*Dokumen ini menutup fase perencanaan dan menjadi acuan tunggal eksekusi 90 hari. Perubahan scope hanya melalui amandemen tertulis (ref. Doc 36).*
