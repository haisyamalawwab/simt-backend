# Rencana Uji Penerimaan Pengguna (UAT Plan)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Business Analyst & Project Manager  

---

## 1. Pendahuluan
Dokumen ini mendefinisikan skenario *User Acceptance Testing* (UAT) untuk SIMT MTs (Fase MVP). UAT adalah pengujian tahap akhir yang dilakukan langsung oleh *end-user* (Staf TU, Guru, Kepala Sekolah, dan perwakilan Orang Tua) untuk memastikan sistem yang dibangun sudah sesuai dengan proses bisnis nyata di sekolah.

## 2. Metodologi UAT
- **Lokasi:** Ruang Komputer/Lab MTs (UAT On-site) atau secara daring via Google Meet.
- **Data:** Menggunakan **Dummy Data** yang merepresentasikan data nyata (Siswa "Budi", Guru "Pak Ahmad", dll) di lingkungan `Staging`.
- **Peserta UAT:**
  - 1 Orang Admin/Kepala TU
  - 2 Orang Staf TU (Akademik & Keuangan)
  - 3 Orang Guru (termasuk Wali Kelas)
  - 1 Orang Kepala Sekolah
- **Mekanisme Persetujuan:** Setiap *Test Case* ditandai "LULUS" (Pass) atau "GAGAL" (Fail) beserta catatan/masukan. Jika gagal, perbaikan akan dimasukkan ke iterasi Sprint berikutnya sebelum *Go-Live*.

---

## 3. Skenario UAT (Test Cases)

### 3.1. Skenario Modul Tata Usaha (Admin Sekolah)
**Aktor:** Staf TU

| ID | Skenario Pengujian | Langkah Pengujian | Kriteria Keberhasilan (Expected Result) | Status (Pass/Fail) | Catatan |
|---|---|---|---|---|---|
| UAT-TU-01 | Login Sistem | Masukkan NIP/Email dan Password. | Berhasil masuk ke Dashboard TU, menu navigasi muncul sesuai hak akses. | | |
| UAT-TU-02 | Tambah Siswa Baru | Buka menu Siswa > Tambah Data. Isi Biodata (NISN, Nama, dll). Klik Simpan. | Data siswa tersimpan, muncul di tabel daftar siswa. | | |
| UAT-TU-03 | Manajemen Rombel | Buka menu Kelas. Buat Kelas "7A". Masukkan Wali Kelas. Tambahkan 30 siswa ke kelas tersebut. | Kelas 7A terbentuk, Wali Kelas ter-assign, jumlah siswa 7A tercatat 30 orang. | | |
| UAT-TU-04 | Generate Tagihan SPP | Buka menu Keuangan. Pilih Kelas 7A, generate tagihan SPP bulan Juli 2026. | Tagihan SPP Rp 100.000 tercipta otomatis untuk 30 siswa di Kelas 7A. | | |
| UAT-TU-05 | Terima Pembayaran Kasir | Buka menu Keuangan. Cari siswa "Budi". Input pembayaran tunai untuk Juli. | Status tagihan "Budi" berubah menjadi "Lunas", kwitansi PDF dapat di-print. | | |

### 3.2. Skenario Modul Guru Akademik
**Aktor:** Guru Mata Pelajaran / Wali Kelas

| ID | Skenario Pengujian | Langkah Pengujian | Kriteria Keberhasilan (Expected Result) | Status (Pass/Fail) | Catatan |
|---|---|---|---|---|---|
| UAT-GR-01 | Input Presensi Harian | Buka menu Presensi Kelas 7A. Set kehadiran (Hadir/Sakit/Izin/Alpa). Simpan. | Presensi tersimpan. Siswa yang sakit/izin/alpa muncul di rekap absen. | | |
| UAT-GR-02 | Input Nilai Formatif | Buka menu Penilaian. Pilih Mapel "Fiqih" Kelas 7A. Masukkan nilai UH-1 (range 0-100) untuk seluruh siswa. | Nilai berhasil disimpan. Sistem menolak jika input nilai huruf atau > 100. | | |
| UAT-GR-03 | Input Nilai Sumatif | Masukkan nilai Ujian Akhir Semester (PAS/SAS) di mapel yang sama. | Nilai Sumatif tersimpan, tabel menampilkan rerata sementara. | | |
| UAT-GR-04 | Proses Rapor (Wali Kelas) | Login sebagai Wali Kelas. Buka menu Rapor Kelas 7A. Klik "Kalkulasi Rapor Akhir". | Sistem menarik nilai dari semua mapel, menghitung nilai akhir, dan memunculkan draft rapor per siswa. | | |
| UAT-GR-05 | Cetak PDF Rapor | Di halaman detail Rapor Siswa, klik "Cetak PDF". | File PDF Rapor terunduh dengan format yang sesuai standar (header, kop surat, tabel nilai, TTD Wali Kelas & Kepsek). | | |

### 3.3. Skenario Modul Kepala Sekolah
**Aktor:** Kepala Madrasah

| ID | Skenario Pengujian | Langkah Pengujian | Kriteria Keberhasilan (Expected Result) | Status (Pass/Fail) | Catatan |
|---|---|---|---|---|---|
| UAT-KS-01 | View Dashboard Executive | Login sebagai Kepala Sekolah. Lihat halaman depan (Dashboard). | Tampil grafik persentase kehadiran siswa hari ini, jumlah siswa aktif, dan rasio pembayaran SPP. | | |
| UAT-KS-02 | Laporan Keuangan Global | Buka menu Laporan Keuangan. Filter bulan Juli 2026. | Muncul total uang SPP yang masuk dan daftar siswa menunggak dalam bulan tersebut. | | |

### 3.4. Skenario Portal Orang Tua (Simulasi via WhatsApp)
**Aktor:** Tester / Staf TU mewakili Orang Tua

| ID | Skenario Pengujian | Langkah Pengujian | Kriteria Keberhasilan (Expected Result) | Status (Pass/Fail) | Catatan |
|---|---|---|---|---|---|
| UAT-PR-01 | Notifikasi Absensi | TU men-set status absen siswa "Budi" sebagai ALPA di jam pertama. | Nomor WhatsApp orang tua menerima notifikasi teks bahwa anaknya Alpa hari ini. | | |
| UAT-PR-02 | Cek Tagihan via Portal | Orang tua login ke Portal Wali Murid menggunakan NISN & PIN. | Tampil riwayat pembayaran SPP dan tagihan bulan berjalan. | | |

---

## 4. Lembar Pengesahan (Sign-Off)

Setelah pengujian diselesaikan, pihak sekolah akan menandatangani dokumen persetujuan ini sebagai bukti bahwa sistem **Layak Rilis (Go-Live)**.

**Pihak Pengembang (Developer/Vendor)**
Nama: _______________________
Tanggal: _______________________
Tanda Tangan:

**Pihak Madrasah (Kepala Sekolah/Project Sponsor)**
Nama: _______________________
Tanggal: _______________________
Tanda Tangan:

*(Catatan: Isu-isu minor/kosmetik yang disepakati sebagai "Non-Blocker" akan dicatat dalam lembar terpisah sebagai utang teknis / Technical Debt di fase maintenance).*