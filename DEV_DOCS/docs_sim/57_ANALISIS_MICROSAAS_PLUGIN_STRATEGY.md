# ANALISIS MENDALAM — BISAKAH 4 MODUL DIJUAL TERPISAH SEBAGAI PLUGIN MICRO-SAAS?
## Untuk Pertimbangan CEO & CTO — SIMT MTs

**Tanggal:** 14 Juni 2026
**Disusun oleh:** Agent Arena (analisis berbasis kode aktual, bukan teori)
**Pertanyaan inti:** Apakah 4 modul (Core, Student, Attendance, Finance) bisa menjadi micro-SaaS terpisah & mandiri, namun tetap terintegrasi, sehingga bisa dijual terpisah sebagai plug & play plugin?

---

## 0. JAWABAN SINGKAT (Executive Summary)

> **Saat ini: BELUM benar-benar mandiri.** Yang ada adalah **"modular monolith"** — kemasan modul (route/controller/view) terpisah, TAPI **otak & data (Models + tabel + FK) masih satu kesatuan (shared kernel)**. Plug & play yang berfungsi sekarang adalah **feature-toggle komersial**, BUKAN produk yang bisa berdiri sendiri di server berbeda.
>
> **Rekomendasi strategis:** Jangan jual sebagai produk teknis terpisah dulu. **Jual sebagai "modul/add-on berlangganan" di atas satu platform** (model yang SUDAH didukung arsitektur sekarang). Pisahkan jadi micro-SaaS mandiri **hanya** untuk kandidat yang tepat (WA Gateway) dan **bertahap**, karena biaya rekayasa + operasional pemisahan total sangat tinggi vs nilai bisnisnya untuk pasar MTs.

---

## 1. TEMUAN TEKNIS (bukti dari kode)

### 1.1 Modul terpisah di lapisan ATAS, menyatu di lapisan BAWAH

```
            ┌──────────────────────────────────────────────┐
 TERPISAH   │  Routes · Controllers · Views (per Modules/)  │  ✅ modular
 ───────────┼──────────────────────────────────────────────┤
            │  Models (app/Models/*) · Services · Jobs      │  ❌ SHARED
 MENYATU    │  Satu Database · FK lintas-domain             │  ❌ SHARED
            │  Tenancy · RBAC · Auth (App\ kernel)          │  ❌ SHARED
            └──────────────────────────────────────────────┘
```

**Bukti coupling (hasil scan):**
- **SEMUA model domain ada di `app/Models/`**, BUKAN di dalam modul. `Modules/Attendance`, `Finance`, `Student` semua `use App\Models\...`. Modul tidak memiliki model/migration/seeder sendiri (0 migration per-modul; semua di `database/migrations/` root).
- **FK database mengikat keras antar-domain:**
  - `attendances.student_id → students` · `attendances.class_id → school_classes`
  - `bills.student_id → students` · `payments.bill_id → bills`
  - `class_student`, `guardian_student → students`
  → **Attendance & Finance secara fisik TIDAK BISA jalan tanpa tabel `students` (milik modul Student).**
- **Komunikasi antar-modul = panggilan kelas langsung, BUKAN event/contract:**
  - `Modules/Attendance/.../AttendanceController` memanggil `Student::find()` dan `SendWaNotification::dispatch()` langsung.
  - `Modules/Finance/...` juga panggil `SendWaNotification` & `Bill`/`Payment` langsung.
  - **TIDAK ADA** interface/contract antar modul. Doc 28 §3 mengamanatkan Event/Listener, tapi belum diimplementasi.
- **WA Notification** (`app/Jobs/SendWaNotification`) dipakai 3 modul (Attendance, Finance, Student-import) sebagai dependency langsung.

### 1.2 Dua makna "plug & play" yang berbeda (jangan tertukar)

| Tingkat | Yang ADA sekarang | Yang DIBUTUHKAN untuk "jual terpisah" |
|---|---|---|
| **Feature toggle** (1 deploy, on/off menu+API per tenant) | ✅ BERFUNGSI (`tenant_modules` + `module.active` → 403; `module:disable` → route hilang) | — |
| **Produk mandiri** (deploy sendiri, DB sendiri, dijual ke pelanggan yang TIDAK punya modul lain) | ❌ BELUM (model & FK & auth menyatu) | API contract, DB terpisah / bounded context, auth/SSO antar produk, billing terpisah |

**Kesimpulan teknis:** "Plug & play" sekarang = **lampu kamar yang bisa dimatikan**, BUKAN **lampu yang bisa dicabut dan dipasang di rumah lain**.

---

## 2. PENILAIAN PER MODUL (kelayakan jadi micro-SaaS mandiri)

| Modul | Mandiri? | Ketergantungan keras | Nilai jual terpisah | Verdict |
|---|---|---|---|---|
| **Core** | ❌ Tidak relevan | — (Core ADALAH platform: tenant, auth, RBAC) | Tidak dijual terpisah; ini fondasi | 🔒 Tetap inti |
| **Student** | 🟡 Bisa jadi "master data service" | Butuh Core (tenant/auth) | Rendah sebagai produk sendiri — semua modul butuh data siswa | Jadikan **shared foundation**, bukan add-on |
| **Attendance** | 🟠 Sebagian | Butuh `students`, `school_classes`, WA | Sedang — presensi+WA menarik, tapi tak berguna tanpa data siswa | Add-on di atas platform |
| **Finance** | 🟢 Paling layak | Butuh `students`, WA | **Tinggi** — SPP/keuangan punya nilai berdiri sendiri, batas domain jelas | Kandidat #1 add-on premium |
| **WA Gateway** (Sprint 4, blm dibuat) | ✅ Paling mandiri | Hanya butuh API key + nomor | **Sangat tinggi** — sudah dirancang VPS terpisah (Doc 39); bisa jadi produk/utility sendiri | **Kandidat #1 micro-SaaS mandiri** |

> Insight kunci: **WA Gateway** (yang BELUM dibangun) justru paling cocok jadi micro-SaaS mandiri karena memang dirancang sebagai service Node.js terpisah di VPS sendiri. Sedangkan 4 modul Laravel saat ini terlalu terikat ke shared kernel.

---

## 3. TIGA MODEL BISNIS — PLUS/MINUS

### Model A — "Modular Add-on" di atas SATU platform (SEKARANG didukung)
Jual: paket dasar (Core+Student) + add-on berbayar (Attendance, Finance, nanti Tahfiz dll) lewat `tenant_modules`.
- ✅ **Sudah jalan** (0 biaya rekayasa tambahan), upsell mudah, 1 codebase 1 DB (operasional murah — sesuai budget Rp 5jt).
- ✅ Cocok pasar MTs: sekolah mau 1 sistem terpadu, bukan 5 langganan terpisah.
- ❌ Bukan "produk terpisah" sejati; pelanggan tetap di 1 vendor/platform.
- 💰 **Paling cocok untuk MVP & 12 bulan pertama.**

### Model B — Micro-SaaS Mandiri per modul (deploy & DB terpisah)
Jual tiap modul sebagai produk independen yang bisa dipakai sekolah tanpa modul lain.
- ✅ Bisa jual ke pasar lebih luas (sekolah yang sudah punya SIM lain tapi mau WA/Finance saja).
- ❌ **Biaya rekayasa SANGAT besar:** pisahkan model ke tiap modul, hilangkan FK lintas-domain → ganti dengan API/event, bikin auth/SSO antar produk, sinkronisasi data siswa antar service, billing per produk, DevOps per service.
- ❌ Operasional mahal (banyak service/DB) — bertentangan dengan budget & tim 1 orang.
- ❌ Konsistensi data lintas service jadi masalah kelas distributed-systems.
- 💰 **Hanya layak setelah ada traction & pendanaan.**

### Model C — Hybrid (REKOMENDASI)
- **Platform terpadu** (Core+Student+Attendance+Finance) dijual model A (add-on toggle).
- **WA Gateway** dijadikan **micro-SaaS/utility mandiri** (sudah by-design terpisah) — bisa dijual juga ke non-pelanggan SIM (mis. sekolah lain, UMKM) sebagai "WA notification as a service".
- Siapkan **API contract + Event/Listener** secara bertahap supaya pintu ke Model B terbuka TANPA rewrite besar nanti.

---

## 4. ROADMAP TEKNIS MENUJU "PLUGIN SEJATI" (jika kelak pilih Model B)

Bertahap, tanpa hentikan bisnis (strangler pattern):

**Fase 1 — Decoupling internal (murah, lakukan sambil jalan)**
- [ ] Pindahkan Model domain ke modul masing-masing (`Modules/Student/app/Models/Student.php`, dst.) — Student model jadi milik modul Student.
- [ ] Migration & seeder per-modul (`Modules/*/database/`).
- [ ] Ganti panggilan lintas-modul langsung → **Event/Listener** (Doc 28 §3): mis. `AttendanceMarked` event, Finance/WA jadi listener. Modul mati ≠ crash.
- [ ] Definisikan **Contract/Interface** untuk data yang dibutuhkan lintas modul (mis. `StudentDirectory` interface).

**Fase 2 — Bounded context data**
- [ ] Hilangkan FK lintas-domain DB; ganti `student_id` jadi referensi lunak + sinkronisasi via event.
- [ ] Opsi: schema/DB terpisah per modul (tetap 1 server dulu).

**Fase 3 — Pemisahan deployment (mahal, hanya jika perlu)**
- [ ] API gateway + auth terpusat (SSO/OAuth) antar produk.
- [ ] Billing & provisioning per produk.
- [ ] Service mandiri + observability per service.

> **WA Gateway boleh langsung dibangun mandiri di Sprint 4** (tidak perlu nunggu fase ini) karena memang service Node.js terpisah.

---

## 5. REKOMENDASI UNTUK CEO & CTO

1. **MVP & tahun pertama → Model A/C.** Jual platform terpadu + add-on toggle. Ini SUDAH bisa dijual sekarang, sesuai budget & pasar MTs. "Plug & play plugin" dipasarkan sebagai **"modul berlangganan"** — jujur secara teknis & cukup sebagai diferensiasi jualan.
2. **Jadikan WA Gateway produk/utility mandiri** (Sprint 4) — ini micro-SaaS paling layak dijual terpisah, bahkan ke luar segmen SIM sekolah.
3. **Investasi decoupling Fase 1 secara bertahap** (Model/Event/Contract per modul) — murah, menjaga opsi Model B terbuka tanpa rewrite. Mulai dari modul Finance (batas domain paling jelas).
4. **JANGAN pisahkan jadi micro-SaaS penuh (Model B) sekarang.** ROI negatif untuk pasar MTs + tim kecil + budget Rp 5jt. Tunggu sinyal pasar (pelanggan minta beli 1 modul saja tanpa SIM) & pendanaan.
5. **Pricing:** paket dasar (Core+Student) murah sebagai pintu masuk → upsell Attendance (killer feature WA) → Finance (margin tinggi) → modul masa depan (Tahfiz/Library/E-Office). Mekanisme `tenant_modules` sudah siap menopang ini.

---

## 6. RINGKAS: APA YANG SUDAH SIAP vs BELUM

| Kapabilitas | Status |
|---|---|
| On/off modul per sekolah (langganan) | ✅ SIAP (`tenant_modules` + middleware) |
| On/off modul di level kode (nwidart) | ✅ SIAP |
| Jual sebagai add-on di 1 platform (Model A) | ✅ SIAP DIJUAL |
| Modul punya Model/migration/data sendiri | ❌ Belum (shared `app/Models`) |
| Komunikasi antar-modul via Event/Contract | ❌ Belum (panggilan langsung) |
| Modul jalan tanpa modul lain (mandiri) | ❌ Belum (FK keras ke `students`) |
| Deploy/DB/billing terpisah per modul (Model B) | ❌ Belum (butuh Fase 1-3) |
| WA Gateway sebagai service mandiri | 🔜 By-design siap (bangun di Sprint 4) |

---

*Analisis ini berbasis pemeriksaan langsung kode `/home/user/simt-backend` (14 Juni 2026): peta `use App\...`, FK migration, pola dispatch antar-modul, lokasi Model. Keputusan akhir ada di tangan CEO/CTO; dokumen ini menyediakan dasar fakta + kerangka pilihan.*
