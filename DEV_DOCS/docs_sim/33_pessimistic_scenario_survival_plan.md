# Analisis Pesimis & Rencana Bertahan Hidup (Worst-Case Scenario)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Risk Management & Financial Analyst  

---

## 1. Realita Anggaran Development Rp 5.000.000

Merekrut Programmer / Software House untuk membuat SaaS Multi-Tenant dengan modal 5 Juta adalah **mustahil**. UMP/UMR saja sudah di atas 3-4 Juta sebulan. Jika dipaksakan menyewa *freelancer* murah, yang didapat adalah *spaghetti code* yang penuh *bug* dan ditinggal kabur (hit & run).

**Satu-satunya Cara Mengeksekusi (The AI-Bootstrapping Route):**
Anda (sebagai *Founder*) harus melakukan *coding* mandiri dibantu oleh AI (*Sweat Equity*). Modal 5 Juta tidak dipakai untuk menggaji orang, melainkan untuk membeli "Otak AI" dan Infrastruktur.

**RAB Development (3 Bulan):**
1. **AI Coding Assistant (Cursor Pro / Claude Pro):** $20/bulan x 3 bulan = Rp 960.000
2. **Server Staging / Dev (VPS 1GB):** Rp 100.000 x 3 bulan = Rp 300.000
3. **Domain (.id):** Rp 250.000 / tahun
4. **Biaya Legal / Materai / Bensin Survey ke Sekolah:** Rp 1.000.000
5. **Dana Darurat (Runway):** Rp 2.490.000
**Total Terpakai: Rp 5.000.000**

*Kesimpulan:* Membeli token AI / *Subscription* Claude sangat murah (Hanya ±Rp 320rb/bulan) dibandingkan menggaji dev (Rp 5 Juta/bulan). Ini adalah solusi mutlak.

---

## 2. Kalkulasi Skenario Pesimis (Sekolah Skala Mikro)

Mari kita asumsikan kegagalan penetrasi pasar yang masif. Kita hanya mendapat **3 Madrasah** dalam 6 bulan pertama, dan rata-rata muridnya hanya **75 Siswa**.

### A. Titik Kritis Pendapatan Bulanan (Model Pay-per-Student)
- Total Siswa: 3 Sekolah x 75 Siswa = **225 Siswa**
- Pemasukan (Rp 2.000/siswa) = 225 x Rp 2.000 = **Rp 450.000 / Bulan**

### B. Beban Operasional Pasti (Fixed Opex)
- VPS Server Production (2GB RAM): Rp 250.000
- Gateway WhatsApp (1 Nomor Sentral untuk semua sekolah): Rp 150.000
- **Total Beban: Rp 400.000 / Bulan**

### C. Analisis Kerugian (Profit & Loss)
- Pemasukan: Rp 450.000
- Pengeluaran: Rp 400.000
- **Laba Bersih: Rp 50.000 / Bulan.** *(Sangat kritis. Jika 1 sekolah telat bayar, kita langsung MERUGI/Minus dan harus nombok server pakai uang pribadi).*

---

## 3. Strategi Pivot: Melawan Kebangkrutan (Survival Strategy)

Angka Rp 2.000/siswa ternyata **membunuh kita secara perlahan** jika target pasarnya adalah sekolah gurem (mikro). Kita harus mengubah *Pricing Structure* agar tetap "Win-Win" namun melindungi *cashflow* perusahaan.

### Solusi 1: Terapkan "Minimum Commitment Fee" (Ambang Batas Bawah)
Jangan gunakan harga flat murni per siswa. Gunakan model *Threshold*.
**Kontrak ke Sekolah:** 
*"Biaya sistem adalah Rp 2.000 / Siswa / Bulan. NAMUN, biaya minimal tagihan per sekolah adalah **Rp 200.000 / Bulan** (Mana yang lebih besar)."*

**Skenario Sekolah A (50 Siswa):**
- Hitungan normal: 50 x 2000 = 100.000.
- Tagihan aktual: **Rp 200.000 / Bulan** (Karena kena batas minimum).
*(Ini wajar. Rp 200rb/bulan itu seharga patungan internet Wifi Telkomsel sekolah).*

**Skenario Sekolah B (150 Siswa):**
- Hitungan normal: 150 x 2000 = **Rp 300.000 / Bulan** (Tidak kena minimum).

**Dampak Skenario 3 Sekolah Mikro (75 Siswa):**
- 3 Sekolah x Minimum Rp 200.000 = **Rp 600.000 / Bulan**.
- Profit sekarang: Rp 600k (Rev) - Rp 400k (Opex) = **Rp 200.000 Profit Bersih.** Lebih aman.

### Solusi 2: Shift Beban "WhatsApp" ke Sekolah
Biaya Rp 150rb/bulan untuk Gateway WA (seperti Wablas/Watzap) membebani server kita. Jika satu sekolah melakukan *spam*, kita yang rugi.
**Taktik:**
Sistem SIMT MTs (Backend) tidak menyediakan nomor WA *Sender*. Kita menyediakan *Device Pairing (Scan QR)*. Setiap admin MTs harus men-scan QR WhatsApp mereka sendiri di Dashboard TU.
- **Efek 1:** Biaya sewa API/Gateway WA kita menjadi Rp 0 (Nol).
- **Efek 2:** Orang tua menerima pesan dari Nomor Resmi Sekolah (Lebih profesional).
- *Catatan Teknis:* Ini membutuhkan *library* Node.js `baileys` yang berjalan di *background* VPS kita. VPS RAM 2GB masih kuat menangani 3-5 *session* WA Baileys. Beban Opex kita turun drastis!

### Solusi 3: Strategi "Cash in Advance" (Pembayaran di Depan)
Sekolah swasta mikro rawan telat membayar tagihan SaaS bulanan (karena menunggu SPP cair).
**Taktik:** Tagihan Rp 200.000/bulan jangan ditagih di akhir bulan. Terapkan sistem **Sistem Kuota / Prabayar (Prepaid)** per Semester (6 Bulan).
- Sekolah bayar Rp 1.200.000 di awal semester. (Bisa diambil dari dana BOS/Tahunan).
- Jika kita dapat 3 sekolah di bulan Juli, kita langsung pegang *Cash* Rp 3.600.000 untuk modal bayar VPS setahun penuh tanpa takut nombok.

---

## 4. Kesimpulan Kritis
1. **Jangan merekrut Developer.** Gunakan budget 5 Juta untuk biaya hidup Anda dan berlangganan AI (Claude Pro / Cursor) untuk *coding* mandiri.
2. **Sekolah < 100 siswa akan membuat Anda merugi** jika hanya mengandalkan tagihan Rp 2.000/siswa. Wajib ada **Minimum Tagihan Rp 200.000/bulan/sekolah**.
3. **Pangkas biaya operasional WA** dengan memaksa sekolah men-scan QR WhatsApp mereka sendiri (Self-hosted Baileys), jangan berlangganan API *third-party* yang memakan biaya bulanan tetap.

*Dengan skema pesimis ini, bahkan jika Anda hanya punya 3 sekolah miskin sebagai klien, server Anda tidak akan mati dan Anda tetap tidak perlu mengeluarkan uang dari kantong pribadi.*