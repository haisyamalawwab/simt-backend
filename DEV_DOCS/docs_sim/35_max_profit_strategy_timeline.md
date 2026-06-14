# Strategi "Max-Profit": Skema Eksekusi & Arus Kas (Semester 1)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Chief Executive Officer (CEO) / Founder  

---

## 1. Pendahuluan (The "Cash is King" Philosophy)

Untuk memaksimalkan keuntungan dari modal yang hanya **Rp 5.000.000**, strategi utama kita adalah **"Cash In Advance" (Uang Tunai di Depan)**. Kita tidak boleh menalangi biaya operasional server. Klien yang harus membiayai pertumbuhan kita sejak Hari Pertama (Day 1).

Asumsi realistis "Paling Untung" namun tetap masuk akal untuk dicapai oleh 1 orang Project Manager/Founder dalam 1 bulan pemasaran adalah **Mendapatkan 5 Madrasah (Sekolah)** dengan rata-rata murid **100 Siswa/Sekolah** (Total 500 Siswa dalam ekosistem).

---

## 2. Tabel Skema Eksekusi Detail (Hari Penawaran hingga Akhir Semester 1)

*Asumsi Timeline: Penawaran dimulai Juli 2026 (Menyambut Tahun Ajaran Baru).*

| Fase / Waktu | Aktivitas Vendor (Kita) | Aktivitas Sekolah (Klien) | Arus Kas Masuk (Revenue) | Beban Server (Opex) | Profit Bersih (Kumulatif) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Bulan 0**<br>*(Juli)*<br>**PITCHING** | Presentasi ke 5-10 Kepsek. Jualan fitur WA Broadcast. Tanda tangan kontrak MoU. | Menyetujui skema biaya Rp 1 Juta (Setup) + Rp 1.2 Juta (Sewa 6 Bulan). | **Rp 0** | Rp 0 *(Gunakan local/free tier)* | **Rp 0** |
| **Bulan 1**<br>*(Agustus)*<br>**GO-LIVE & INVOICING** | Import data siswa Excel ke database. Deploy VPS 2GB. Tagih *Invoice* ke sekolah. | Membayar lunas Invoice 1 Semester. Admin TU scan QR WA. | **+ Rp 11.000.000**<br>*(5x 1Jt Setup + 5x 1.2Jt Sewa)* | - Rp 300.000 | **+ Rp 10.700.000**<br>*(Modal 5 Jt BEP!)* |
| **Bulan 2**<br>*(September)*<br>**ADOPTION** | Monitoring server (pastikan WA tidak error). Perbaikan *bug* kecil. | Guru rutin absen. Orang tua mulai merasakan notifikasi WA. | **Rp 0**<br>*(Sudah dibayar di depan)* | - Rp 300.000 | **+ Rp 10.400.000** |
| **Bulan 3**<br>*(Oktober)*<br>**UPSELLING PG** | Tawarkan Modul Payment Gateway (GRATIS) untuk permudah bayar SPP. | Kepsek mendaftar Midtrans. Ortu mulai bayar SPP via Aplikasi. | **Rp 0** | - Rp 300.000 | **+ Rp 10.100.000** |
| **Bulan 4**<br>*(November)*<br>**PASSIVE INCOME** | Server berjalan auto-pilot. Tarik margin *Fee* Transaksi Midtrans (Rp 1.500/Trx). | 40% Ortu (200 org) bayar via online karena malas ke sekolah. | **+ Rp 300.000**<br>*(200 x Rp 1.500)* | - Rp 300.000 | **+ Rp 10.100.000**<br>*(Opex tertutup dari PG!)* |
| **Bulan 5**<br>*(Desember)*<br>**DATA REPORTING** | Backup database. Siapkan PDF e-Rapor jika sekolah pakai fitur formatif. | Cetak rapor. Ujian Akhir Semester. | **+ Rp 300.000**<br>*(Margin PG)* | - Rp 300.000 | **+ Rp 10.100.000** |
| **Bulan 6**<br>*(Januari)*<br>**RENEWAL** | Terbitkan **Invoice Semester 2** (Tanpa biaya setup). Tawarkan Modul Baru (Tahfiz). | Bayar sewa Semester 2. | **+ Rp 6.000.000**<br>*(5x 1.2Jt)* + Rp 300k (PG) | - Rp 300.000 | **+ Rp 16.100.000** |

---

## 3. Rahasia Mengapa Skema Ini "Paling Untung"

### A. Return of Investment (ROI) Instan di Bulan 1
Anda memulai dengan modal Rp 5.000.000 (dari kantong pribadi). Di Bulan 1, dengan meminta pembayaran uang pangkal & semester di depan, Anda menarik uang *cash* **Rp 11.000.000**.
Dalam waktu 30 hari, modal awal Anda kembali utuh (BEP), dan Anda masih memegang uang tunai Rp 6.000.000 untuk *"runway"* mengamankan biaya peladen (VPS) ke depannya.

### B. Biaya Operasional (Opex) Menjadi Gratis (Zero-Cost Server)
Di Bulan ke-4 dan seterusnya, server VPS Anda seharga Rp 300.000/bulan akan terbayar lunas otomatis oleh **Passive Income (Margin Rp 1.500)** dari transaksi *Payment Gateway* orang tua yang membayar SPP. 
Artinya, sistem ini **membiayai dirinya sendiri**. Pendapatan *subscription* murni menjadi profit.

### C. Efek "Lock-In" (Sunk Cost Fallacy)
Karena sekolah sudah membayar Rp 1 Juta untuk biaya *setup* dan mengumpulkan seluruh data siswanya ke sistem Anda selama 6 bulan, kemungkinan mereka pindah/berhenti di Semester 2 sangat kecil (*Churn Rate* mendekati 0%). Begitu mereka bergantung pada sistem WA otomatis Anda, mereka akan terus membayar *invoice* perpanjangan Semester 2 dengan senang hati.

---

## 4. Tiga Syarat Wajib Kesuksesan (Rule of Execution)
1. **Jangan Berikan Masa Ujicoba (Free Trial) Penuh:** Jika ingin trial, gunakan Data Dummy yang sudah Anda buat. Jika mereka memasukkan data asli sekolah mereka, mereka **WAJIB** membayar Uang Setup Rp 1 Juta.
2. **Jangan Menerima Permintaan "Custom" Ekstrem:** Jika Kepala Sekolah minta warna tombol diganti atau tambah format laporan aneh-aneh, tolak dengan halus. Ingatkan mereka ini produk *SaaS/Penyewaan Massal*, bukan *Custom Software Development*.
3. **Disiplin Menagih di Awal Semester:** Berikan jeda toleransi pembayaran (Tenggang) 14 Hari. Jika sekolah menunggak *invoice* semester baru, sistem otomatis men- *suspend* pengiriman WA dan Portal Orang Tua (Orang tua akan mengeluh ke sekolah, dan sekolah akan segera membayar Anda).

*Dengan disiplin pada matriks ini, SIMT MTs tidak hanya akan bertahan hidup, tetapi akan berevolusi menjadi bisnis SaaS dengan profitabilitas yang sangat sehat hanya dengan modal tenaga (koding) & 5 juta rupiah.*