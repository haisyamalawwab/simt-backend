# BRIEFING Q&A STRATEGIS — CEO & CTO
## Keputusan: Apakah 4 Modul Dijual Terpisah sebagai Plug & Play Plugin (Micro-SaaS)?

**Tanggal:** 14 Juni 2026
**Format:** Pertanyaan pengambil keputusan → Jawaban berbasis bukti → Keputusan yang dibutuhkan
**Dokumen pendukung (teknis lengkap):** `57_ANALISIS_MICROSAAS_PLUGIN_STRATEGY.md`
**Sifat:** Ringkas untuk rapat board. Detail bukti kode ada di Doc 57.

---

## PERTANYAAN YANG DIAJUKAN

> "Apakah 4 modul saat ini (Core, Student, Attendance, Finance) bisa menjadi micro-SaaS terpisah & mandiri, namun bisa diintegrasikan — sehingga kami (CEO & CTO) perlu mempertimbangkan menjualnya terpisah sebagai PLUG & PLAY PLUGIN?"

---

## JAWABAN SINGKAT (1 kalimat)

**Belum bisa dijual sebagai produk terpisah yang berdiri sendiri**, tetapi **sudah bisa dijual sekarang sebagai modul/add-on berlangganan di atas satu platform** — dan itu pilihan yang lebih tepat untuk pasar MTs & budget saat ini.

---

## Q&A

### Q1. Apakah modul-modul ini benar-benar terpisah?
**Tidak sepenuhnya.** Arsitekturnya **"modular monolith"**:
- ✅ **Terpisah** di lapisan atas: Route, Controller, View (folder `Modules/` masing-masing).
- ❌ **Menyatu** di lapisan bawah: semua **Model & data ada di `app/Models/` + 1 database bersama**; modul tidak punya Model/migration sendiri.

### Q2. Kenapa belum mandiri secara teknis?
Tiga pengikat keras (bukti di Doc 57 §1):
1. **Database FK lintas-domain:** `attendances` & `bills` ber-FK ke `students`. → Attendance & Finance **tidak bisa jalan tanpa tabel siswa** milik modul Student.
2. **Model bersama:** semua modul `use App\Models\...` (bukan model milik sendiri).
3. **Panggilan langsung antar-modul** (`Student::find()`, `SendWaNotification::dispatch()`), **belum** pakai Event/Contract → modul tak benar-benar independen.

### Q3. Lalu "plug & play" yang sudah jalan itu apa?
Itu **feature-toggle komersial**, bukan produk mandiri:
- `tenant_modules` + middleware `module.active` → sekolah tanpa langganan dapat **403** (menu hilang).
- Ini SUDAH cukup untuk **menjual add-on**, tapi BUKAN "cabut-pasang ke server/pelanggan lain".

> Analogi: yang ada = **lampu bisa dimatikan**. Yang dimaksud "jual terpisah" = **lampu bisa dicabut & dipasang di rumah lain**.

### Q4. Modul mana yang paling layak dijual terpisah?
| Modul | Kelayakan mandiri |
|---|---|
| Core | 🔒 Tidak — ini fondasi (tenant/auth/RBAC) |
| Student | 🟡 Jadikan fondasi bersama, bukan add-on (semua butuh data siswa) |
| Attendance | 🟠 Add-on (tak berguna tanpa data siswa) |
| Finance | 🟢 Kandidat add-on premium #1 (domain paling jelas) |
| **WA Gateway** (Sprint 4, belum dibangun) | ✅ **Kandidat micro-SaaS mandiri #1** — by-design service terpisah |

### Q5. Apa pilihan model bisnisnya?
| Model | Inti | Cocok kapan |
|---|---|---|
| **A — Add-on 1 platform** | Paket dasar + modul berbayar via `tenant_modules` | ✅ **SEKARANG** (0 biaya rekayasa) |
| **B — Micro-SaaS mandiri penuh** | Tiap modul deploy/DB/billing sendiri | Setelah ada traction + pendanaan (biaya besar) |
| **C — Hybrid (REKOMENDASI)** | Platform terpadu (Model A) + WA Gateway mandiri + decoupling bertahap | ✅ **Jalur yang dianjurkan** |

### Q6. Berapa biaya jadi "plugin sejati" (Model B)?
**Tinggi.** Perlu: pindah Model ke tiap modul, hilangkan FK lintas-domain, ganti dgn API/Event, auth/SSO antar produk, sinkronisasi data siswa antar service, billing & DevOps per service. Bertentangan dengan budget Rp 5jt & tim 1 orang. **ROI negatif untuk pasar MTs saat ini.**

### Q7. Apakah keputusan sekarang menutup pintu ke Model B nanti?
**Tidak — jika kita investasi decoupling Fase 1 (murah) sambil jalan:** pindahkan Model ke modul + ganti panggilan langsung jadi **Event/Listener** + definisikan **Contract**. Ini membuka opsi Model B tanpa rewrite besar di masa depan.

---

## REKOMENDASI (ringkas)

1. **Tahun-1: Model A/C.** Jual platform terpadu + add-on toggle. Pasarkan "plugin" sebagai **"modul berlangganan"** (jujur & cukup sebagai diferensiasi).
2. **WA Gateway = micro-SaaS mandiri** (bangun Sprint 4) — bisa dijual juga ke luar segmen sekolah.
3. **Decoupling Fase 1 bertahap** (Model/Event/Contract per modul), mulai dari **Finance**.
4. **Jangan** pisahkan jadi micro-SaaS penuh sekarang.
5. **Pricing tangga:** Dasar (Core+Student) → Attendance (killer WA) → Finance (margin tinggi) → modul masa depan (Tahfiz/Library/E-Office). Mesin `tenant_modules` sudah siap.

---

## KEPUTUSAN YANG DIBUTUHKAN DARI CEO/CTO

- [ ] **D1.** Setujui **Model C (Hybrid)** sebagai strategi go-to-market tahun-1? (default rekomendasi)
- [ ] **D2.** Setujui **WA Gateway dibangun sebagai service mandiri** di Sprint 4 (bukan sekadar fitur internal)?
- [ ] **D3.** Alokasikan effort kecil untuk **decoupling Fase 1** (Event/Contract) mulai modul Finance — ya/tunda?
- [ ] **D4.** Tetapkan **struktur pricing/paket** (dasar + add-on) untuk pitching ke sekolah?
- [ ] **D5.** Target pasar add-on terpisah: **internal SIM saja** atau **juga jual WA-as-a-service ke luar**?

---

## DAMPAK TEKNIS PER KEPUTUSAN (untuk CTO)

| Keputusan | Effort | Risiko | Catatan |
|---|---|---|---|
| D1 Model C | **0** (sudah didukung) | Rendah | Tinggal kemas pricing |
| D2 WA Gateway mandiri | Sedang (Sprint 4, sudah direncanakan) | Rendah | Service Node.js terpisah, VPS-2 |
| D3 Decoupling Fase 1 | Kecil–sedang, bertahap | Rendah (strangler) | Tidak hentikan bisnis |
| Model B penuh (jika dipaksa) | **Besar** | **Tinggi** | distributed systems, SSO, sinkronisasi data |

---

## STATUS KAPABILITAS SAAT INI (cek cepat)

| Kapabilitas | Status |
|---|---|
| Jual add-on di 1 platform (Model A) | ✅ SIAP DIJUAL |
| On/off modul per sekolah | ✅ SIAP |
| WA Gateway service mandiri | 🔜 Bangun Sprint 4 |
| Modul punya Model/data sendiri | ❌ Belum (Fase 1) |
| Event/Contract antar modul | ❌ Belum (Fase 1) |
| Deploy/DB/billing terpisah (Model B) | ❌ Belum (Fase 2-3) |

---

*Dokumen ini merangkum diskusi strategis 14 Juni 2026. Untuk dasar bukti teknis lengkap, baca Doc 57. Keputusan D1–D5 menanti persetujuan CEO/CTO sebelum eksekusi.*
