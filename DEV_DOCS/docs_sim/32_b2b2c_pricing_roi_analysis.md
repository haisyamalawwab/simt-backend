# Analisis Bisnis Eksekutif: Skema Harga & ROI "Win-Win Solution"
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Business Analyst & SaaS Strategist  

---

## 1. Tantangan Bisnis (The Business Dilemma)

- **Modal Awal Vendor/Developer:** Sangat terbatas (Rp 5.000.000).
- **Kondisi Pasar (Madrasah Swasta):** Anggaran sangat ketat. SPP bulanan siswa sering menunggak. Mereka alergi dengan biaya "Investasi Software" di depan yang menyentuh angka jutaan rupiah. Mereka juga enggan membayar biaya berlangganan bulanan (*subscription*) yang mahal layaknya korporasi.

**Bagaimana kita balik modal (ROI) dan mencetak profit dengan skema harga yang "TIDAK MENCEKIK" madrasah swasta?**

Jawabannya adalah membuang model *Subscription Bulanan Flat* (seperti Rp 1 Juta/bulan/sekolah), dan beralih ke model **B2B2C (Business-to-Business-to-Consumer) / Pay-Per-Student Model**.

---

## 2. Skema Harga "Win-Win" (SaaS Pricing Strategy)

Kita menerapkan model **"Beli Mesin Core (Sekali Bayar Murah) + Sewa Layanan (Ditebeng ke SPP Siswa)"**.

### Tahap 1: Biaya Setup Awal (Onboarding Fee)
- **Harga:** **Rp 1.000.000 / Sekolah** (Dibayar 1 kali seumur hidup).
- **Apa yang sekolah dapatkan?** 
  - Hak guna sistem Core (Data Induk, Presensi, Akun Guru/TU).
  - Bantuan import data Excel (Dapodik/EMIS) awal.
- *Psikologi Harga:* Angka Rp 1 Juta sangat masuk akal bagi sekolah swasta terkecil sekalipun. Bisa diambil dari dana BOS.

### Tahap 2: Biaya Operasional (Model Pay-Per-Student / B2B2C)
Alih-alih menagih Rp 500.000/bulan ke Yayasan, kita menagih **Rp 2.000 / Siswa / Bulan**.

**Bagaimana skema ini tidak mencekik sekolah?**
- Sekolah **TIDAK** membayar ini dari kas yayasan. 
- Sekolah membuat kebijakan: *"Mulai tahun ajaran ini, ada tambahan **Biaya Digitalisasi / IT Orang Tua sebesar Rp 3.000/bulan** yang disisipkan ke dalam SPP bulanan."*
- Dari Rp 3.000 tersebut:
  - **Rp 2.000** disetor ke vendor SIMT MTs (Kita).
  - **Rp 1.000** menjadi kas tambahan untuk kesejahteraan TU/Guru Admin di madrasah tersebut.

**Apa yang Orang Tua dapatkan dengan membayar Rp 3.000 ekstra/bulan?**
- Notifikasi WA (Harian) jika anak sampai di sekolah / Alpa.
- Akses ke Aplikasi (Next.js Portal) untuk melihat nilai, hafalan Tahfiz, dan sisa tagihan.
*(Psikologi: Bagi orang tua MTs, Rp 3.000/bulan setara harga parkir motor 1x. Sangat murah untuk ketenangan batin).*

---

## 3. Kalkulasi Proyeksi Pendapatan (Revenue Projection)

Mari kita asumsikan dalam 1 tahun pertama (Juli 2026 - Juni 2027), kita menargetkan **10 Madrasah Swasta (Tenant)** bergabung.
Asumsi rata-rata 1 Madrasah memiliki **200 Siswa**.

### A. Modal Awal Vendor (Kita)
- Development (Gaji/Bensin Dev): Rp 4.000.000
- Server VPS (Rp 300rb x 3 bulan pertama): Rp 900.000
- Domain (.id): Rp 100.000
- **Total Modal: Rp 5.000.000**

### B. Biaya Operasional Berjalan (Running Cost / Bulan)
- VPS Cloud (2GB RAM - Menampung 10 Sekolah via Multi-Tenant): Rp 300.000
- API WhatsApp (Green API/Wablas) untuk broadcast: Rp 150.000
- **Total Cost per Bulan: Rp 450.000**

### C. Pendapatan (Revenue) dari 10 Sekolah
1. **Pendapatan Setup Awal (One-time):**
   - 10 Sekolah x Rp 1.000.000 = **Rp 10.000.000**
   *(Di titik ini, modal awal Rp 5.000.000 sudah kembali 200%).*

2. **Pendapatan Bulanan (Recurring B2B2C):**
   - Total Siswa: 10 sekolah x 200 siswa = 2.000 siswa.
   - Tagihan SaaS (Rp 2.000/siswa): 2.000 x Rp 2.000 = **Rp 4.000.000 / Bulan**

### D. Perhitungan Profit (Keuntungan Bersih / Bulan)
- Revenue Bulanan: Rp 4.000.000
- Cost Bulanan: Rp 450.000
- **Net Profit Bulanan: Rp 3.550.000**

---

## 4. Analisis Skema Modul Add-on (Plug & Play Revenue)

Ketika sekolah sudah berjalan 1 semester dan merasa terbantu dengan Sistem Core (Absensi & WA), kita tawarkan "Add-on Modul Keuangan (Payment Gateway)".

**Skema Payment Gateway (Xendit/Midtrans BYOA):**
- Kita membebaskan sekolah dari biaya aktivasi modul. (GRATIS!).
- **Revenue Model:** Kita menerapkan *Markup* biaya Admin PG (Convenience Fee).
  - Biaya asli Midtrans/Xendit (Virtual Account): Rp 4.000 / Transaksi.
  - Kita tagihkan ke Orang Tua (via Portal): Rp 5.500 / Transaksi.
  - **Margin Vendor (Kita): Rp 1.500 / Transaksi SPP.**

**Proyeksi Tambahan:**
- Dari 2.000 siswa, asumsikan 30% (600 siswa) orang tuanya sibuk bekerja dan memilih bayar SPP online via aplikasi agar tidak repot ke bank/sekolah.
- Tambahan Pasif Income: 600 transaksi x Rp 1.500 = **Rp 900.000 / bulan.**

---

## 5. Kesimpulan (Executive Summary)

Dengan strategi **Micro SaaS (Pay-Per-Student)**:
1. **Sekolah tidak merasa "diperas":** Kas yayasan aman. Mereka hanya keluar Rp 1 Juta sekali. Biaya bulanan dibebankan ke komponen SPP siswa dengan nominal mikro yang sangat wajar (Rp 3.000). Sekolah malah diuntungkan Rp 1.000/siswa/bulan.
2. **Orang Tua puas:** Mendapat transparansi, aplikasi keren, dan ketenangan batin hanya dengan harga sebungkus permen.
3. **Vendor (Kita) cuan besar:** Modal Rp 5 Juta bisa kembali dalam 1 bulan pertama jika sukses *closing* minimal 5 sekolah. Pada bulan-bulan berikutnya, sistem mencetak _Passive Income_ sekitar Rp 4 Jutaan dari infrastruktur server yang biayanya di bawah Rp 500 ribu.

*Skema ini adalah formula rahasia perusahaan-perusahaan EdTech (Education Technology) besar yang sukses merangsek pasar sekolah menengah ke bawah.*