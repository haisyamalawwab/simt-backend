# Slide Deck: Kick-off Meeting Proyek SIMT MTs
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah**

*Gunakan outline ini sebagai draf presentasi (Google Slides/PowerPoint) untuk Kick-off Meeting bersama Klien (Yayasan/Kepala Sekolah) dan Tim Pengembang.*

---

## Slide 1: Judul Presentasi
**Judul Utama:** Kick-off Meeting Proyek SIMT MTs
**Sub-judul:** Transformasi Digital Madrasah dengan Sistem yang Tepat Guna & Tepat Dana
**Detail:** 
- Tanggal: [Tanggal Meeting]
- Presenter: [Nama Anda - Project Manager / IT Consultant]
- Klien: MTs [Nama Sekolah]

---

## Slide 2: Latar Belakang & Masalah
*(Sampaikan rasa empati terhadap masalah sehari-hari di Madrasah)*

**Poin-poin:**
- **Beban Administrasi:** Guru dan TU terjebak rutinitas rekap manual (absen, nilai) menggunakan Excel/Kertas.
- **Transparansi Keuangan:** Proses pencatatan SPP yang lambat, rentan selisih, dan sulit dimonitor secara *real-time*.
- **Komunikasi Orang Tua:** Wali murid sering tidak tahu jika anaknya membolos atau ada tagihan menunggak sampai akhir semester.
- **Keterbatasan Anggaran:** Software manajemen sekolah di pasaran terlalu mahal (bisa > Rp 20 Juta) atau terlalu rumit (*bloated*).

---

## Slide 3: Solusi Kami (Visi SIMT MTs)
*(Jual solusi dengan bahasa bisnis, jangan bahasa teknis)*

**Poin-poin:**
- **SIMT MTs (MVP):** Solusi *Software as a Service* (SaaS) berbasis web yang dirancang *khusus* untuk kebutuhan Madrasah Tsanawiyah.
- **Fokus Utama:** Menyelesaikan 80% masalah administrasi paling krusial dengan pendekatan *Lean* (Sederhana, Cepat, Mudah Digunakan).
- **Akses Di Mana Saja:** Dapat diakses melalui Laptop (untuk TU/Guru) maupun Smartphone (untuk Orang Tua).

---

## Slide 4: Ruang Lingkup (Apa yang akan kita bangun?)
*(Jelaskan batasan MVP agar klien tidak meminta fitur "ajaib" di tengah jalan)*

**Fase 1: MVP (Minimum Viable Product) - Fokus Saat Ini**
1. **Manajemen Induk:** Data Siswa, Guru, dan Kelas (Rombel).
2. **Akademik:** Presensi harian & Input Nilai (Formatif/Sumatif).
3. **e-Rapor:** Perhitungan nilai otomatis & cetak PDF.
4. **Keuangan Dasar:** Pembuatan Tagihan Bulanan (SPP) & Pencatatan Bayar Tunai.
5. **Portal Orang Tua:** Akses nilai, absensi, tagihan (via Smartphone) + Notifikasi Absen WA.

**Fase 2 & 3 (Masa Depan / Not in Scope Now):**
- Modul Tahfiz & Inklusi, Payment Gateway (Midtrans), Integrasi EMIS/RDM, Aplikasi Native Android/iOS.

---

## Slide 5: Timeline & Roadmap (3 Bulan)
*(Tunjukkan keseriusan dan jadwal yang terukur)*

**Poin-poin:**
- **Bulan 1:** *Foundation & Master Data* (Setup Server, Sistem Login, Manajemen Siswa, Kelas & Guru).
- **Bulan 2:** *Academic & Core Engine* (Modul Kehadiran, Input Nilai, Kalkulasi e-Rapor PDF).
- **Bulan 3:** *Finance & Portal* (Manajemen Tagihan SPP, Portal Orang Tua, Integrasi WA, UAT & Pelatihan).
- **Target Go-Live:** [Isi Tanggal Go-Live, misal: Pertengahan Agustus 2026].

---

## Slide 6: Komitmen Anggaran (The "Lean" Approach)
*(Transparansi biaya yang sangat menekan budget)*

**Poin-poin:**
- **Modal Awal / Development:** Dibatasi maksimal **Rp 5.000.000**.
- **Biaya Operasional Bulanan:** ± **Rp 300.000/bulan** (Untuk sewa Cloud VPS Indonesia & infrastruktur web).
- *Bagaimana kita mencapainya?* Menggunakan arsitektur *Monolithic* sederhana, teknologi *Open-Source* teruji (Vue.js + Laravel), dan menunda fitur mewah berbiaya tinggi ke fase selanjutnya.

---

## Slide 7: Pembagian Peran (Roles & Responsibilities)
*(Siapa mengerjakan apa)*

**Tim Pengembang (Internal/Vendor):**
- **Project Manager:** Mengawasi timeline & budget.
- **Tech Lead / Full-Stack Dev:** Menulis kode, setup server.
- **UI/UX & Frontend:** Mendesain antarmuka aplikasi.

**Tim Madrasah (Klien):**
- **Project Sponsor (Kepala Sekolah):** Pengambil keputusan utama.
- **Subject Matter Expert (Kepala TU/Kurikulum):** Tempat bertanya alur bisnis dan validasi formula rapor.
- **UAT Testers:** 3-5 perwakilan Guru & TU untuk menguji sistem sebelum rilis.

---

## Slide 8: Risiko & Mitigasi
*(Menunjukkan profesionalisme bahwa kita siap jika ada masalah)*

- **Risiko:** Server Down / Data Hilang.
  - *Mitigasi:* Backup harian otomatis ke *Cloud Storage* terpisah (RPO 24 Jam).
- **Risiko:** Guru gaptek / menolak pakai sistem.
  - *Mitigasi:* Desain UI yang sangat familiar, dan ada sesi Pelatihan serta *User Manual* cetak/digital.
- **Risiko:** Proyek molor (*Scope Creep*).
  - *Mitigasi:* Disiplin pada dokumen "Requirements" awal. Tambahan fitur baru dicatat untuk *Fase 2*.

---

## Slide 9: Keamanan Data & Privasi
*(Penting di era UU Pelindungan Data Pribadi)*

**Poin-poin:**
- Data disimpan di peladen (VPS) berlokasi di **Indonesia** (Kepatuhan UU PDP).
- Komunikasi dienkripsi dengan **SSL/TLS** standar perbankan.
- Sistem **Hak Akses Berlapis (RBAC)**: Guru X tidak bisa mengubah nilai di kelas Guru Y. Siswa tidak bisa melihat data SPP siswa lain.

---

## Slide 10: Next Steps (Apa selanjutnya?)
*(Call to Action)*

1. Penandatanganan Lembar Persetujuan (*Project Sign-off*).
2. Penyerahan Data *Dummy* atau Data Mentah (Excel) dari sekolah untuk inisialisasi basis data.
3. Tim IT mulai bekerja (Sprint 1 dimulai besok).
4. Jadwal *Update Meeting* berikutnya: [Tanggal/Hari, misal: Setiap Jumat Sore].

---

## Slide 11: Q&A (Tanya Jawab)
**"Terima Kasih"**
*Buka sesi diskusi untuk mendengarkan masukan atau kekhawatiran dari pihak Kepala Sekolah atau Guru.*