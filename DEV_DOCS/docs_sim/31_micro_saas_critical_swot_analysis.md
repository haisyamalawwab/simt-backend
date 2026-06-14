# Analisis Kritis & SWOT: Pivot Menuju "Micro SaaS Plug & Play"
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Lead Product Manager & SaaS Strategist  

---

## 1. Pendahuluan: Realita vs Ekspektasi (The Reality Check)

Setelah meninjau ke-30 dokumen teknis dan 1 dokumen `docx` yang memuat **13 Modul Raksasa** (mulai dari Kurikulum, Tahfiz, Inklusi, hingga E-Office), kita harus melakukan **Evaluasi Kritis (Reality Check)**.

Membangun 13 modul tersebut secara utuh setara dengan membangun sebuah *Enterprise Resource Planning (ERP)* berskala raksasa. Dengan batasan **Budget Rp 5.000.000** dan waktu **3 Bulan**, membangun ERP adalah sebuah kemustahilan (Mustahil secara teknis, kualitas, dan operasional).

Oleh karena itu, strategi bisnis dan produk **WAJIB DI-PIVOT** menjadi model **Micro SaaS Plug & Play**. 

**Apa itu Micro SaaS Plug & Play untuk SIMT MTs?**
Kita tidak menjual "Sistem Manajemen Sekolah Super Lengkap" di hari pertama. Kita menjual **"Mesin Inti (Core Engine)"** yang sangat ringan, dan membiarkan madrasah "menginstal" modul tambahan (Plug & Play) layaknya menginstal aplikasi di *Google Play Store / App Store* sesuai anggaran dan kebutuhan mereka.

---

## 2. Analisis Mendalam (Deep Dive Analysis) Model Micro SaaS

### A. Dekonstruksi 13 Modul menjadi Sistem Lapisan (Tiering System)
Kita membelah ke-13 modul pada dokumen `docx` menjadi 3 lapisan realistik:

1. **The Core Engine (Wajib Ada di MVP 3 Bulan):**
   - **Modul 1 (Sebagian):** Database Siswa, Guru, Kelas, dan Presensi Harian.
   - **Modul 6:** Notifikasi WhatsApp (Ini adalah *killer feature* yang paling dirasakan dampaknya oleh orang tua).
   - *Fokus:* Membuat sekolah merasa bahwa sistem ini menyelesaikan masalah "Komunikasi Absensi ke Orang Tua" secara instan.

2. **The "Cash Cow" Plugins (Fokus Bulan 4-6):**
   - **Modul 4:** Keuangan & Payment Gateway BYOA (Sekolah rela membayar lebih untuk modul ini karena ini mengamankan *cash-flow* mereka).
   - **Modul 3:** Tahfiz (Ini adalah keunggulan kompetitif (USP) MTs dibanding SMP umum. Orang tua sangat peduli dengan hafalan anaknya).
   - **Modul 1 & 5:** E-Rapor & Portal Orang Tua (Next.js).

3. **The "Nice-to-Have" Plugins (Ditinggalkan sementara, dibangun tahun depan):**
   - Modul Inklusi (Bab 7), Modul Sarpras (Bab 8), Modul BK (Bab 9), Modul Perpustakaan (Bab 12), Modul E-Office (Bab 13).
   - *Alasan:* Fitur-fitur ini sangat berat secara *logic*, tapi **tidak memberikan nilai jual langsung (ROI)** bagi sekolah untuk tahap awal adaptasi digital.

### B. Arsitektur Komersial (Pricing Strategy)
Karena ini *Micro SaaS*, kita tidak menagih Rp 10 Juta di depan.
- **Base Plan (Free / Rp 150.000/bulan):** Hanya dapat Modul Core (Data Siswa + Absensi + WA Ortus).
- **Add-on Keuangan (Rp 100.000/bulan):** Otomatisasi SPP & Midtrans.
- **Add-on Tahfiz (Rp 50.000/bulan):** Mutaba'ah hafalan.
Dengan begini, sekolah kecil bisa ikut memakai tanpa terbebani, sekolah besar bisa mengaktifkan semua modul (hingga Rp 500.000/bulan).

---

## 3. Analisis SWOT: SIMT MTs (Micro SaaS Plug & Play)

### Strengths (Kekuatan)
1. **Sangat Niche & Tertarget:** Berbeda dengan sistem sekolah umum (yang sekuler), kita memiliki modul "Tahfiz" dan "Ubudiyah" yang dirancang khusus untuk _Pain Points_ Madrasah Tsanawiyah.
2. **Arsitektur Modular Mutakhir:** Penggunaan `nwidart/laravel-modules` dan *Global Scope Tenancy* membuat aplikasi sangat ringan di server kecil (Cost VPS hanya Rp 300rb/bulan), sangat *scalable*.
3. **Bring-Your-Own-Account (BYOA) Payment:** Menghindari masalah regulasi OJK/BI karena uang SPP langsung masuk ke rekening madrasah, bukan mengendap di platform kita.

### Weaknesses (Kelemahan)
1. **Keterbatasan Resources Dev:** Membangun ekosistem "Plug & Play" di awal membutuhkan fondasi arsitektur yang rumit. Mengandalkan 1-2 developer dengan budget Rp 5 Juta sangat rawan *burnout* (kelelahan).
2. **Fragmentasi UX:** Jika madrasah hanya membeli sebagian modul, _Parent Portal_ (Aplikasi Orang Tua) mungkin terlihat kosong atau memiliki banyak fitur yang terkunci, yang bisa menurunkan kepuasan _user experience_ (UX).
3. **Ketergantungan Eksternal:** Sangat bergantung pada stabilitas API pihak ketiga (Gateway WhatsApp tidak resmi / Green API) yang rawan terblokir oleh Meta.

### Opportunities (Peluang)
1. **Pasar MTs yang Masif & *Underserved*:** Ada puluhan ribu MTs (Swasta & Negeri) di bawah Kemenag yang belum tersentuh digitalisasi secara masif dibandingkan sekolah Kemdikbud.
2. **Viral Loop via WhatsApp:** Setiap kali sistem mengirimkan notifikasi SPP/Absen ke WhatsApp orang tua dengan *footer* _"Dikirim menggunakan SIMT MTs"_, itu adalah pemasaran gratis ke komunitas masyarakat.
3. **Upselling Jangka Panjang:** Begitu sekolah terikat (*vendor lock-in*) dengan Modul Inti (Absensi), sangat mudah untuk menawarkan Modul Keuangan dan E-Rapor di semester berikutnya.

### Threats (Ancaman)
1. **Regulasi Kemenag / EMIS / RDM:** Kementerian Agama sudah memiliki *Rapor Digital Madrasah (RDM)*. Jika Kemenag mewajibkan semua MTs hanya menggunakan RDM secara terpusat, Modul E-Rapor kita bisa tidak laku (harus memutar strategi ke arah API integrasi).
2. **Resistensi Perubahan SDM:** Guru honorer atau tenaga TU senior sering kali menolak penggunaan sistem baru karena dianggap "menambah kerjaan baru" (double entry dengan sistem pemerintah).

---

## 4. Revisi Eksekusi PRD (The "Cut-Throat" MVP Rule)

Untuk mengeksekusi visi Micro SaaS ini agar sukses di bulan ke-3, Anda sebagai Project Manager harus mengambil keputusan "Berdarah dingin" (Cut-Throat):

1. **Fitur yang DIBUNUH dari MVP (Tunda ke Tahun 2027):**
   - Hapus Modul Inklusi (Terlalu spesifik, hanya 5% MTs yang butuh).
   - Hapus Modul Perpustakaan (Sudah banyak _software_ gratis seperti SLiMS).
   - Hapus E-Office/Disposisi Pimpinan.
2. **Fitur yang DIPERTAHANKAN untuk MVP (Rilis Agustus 2026):**
   - Sistem Autentikasi & RBAC Multi-Tenant.
   - Modul Manajemen Data Siswa & Rombel.
   - Modul Absensi Harian + Broadcast WA otomatis.
   - Portal Next.js Orang Tua (Hanya untuk pantau Absen).
3. **Fitur yang DIBANGUN SAMBIL BERJALAN (Rilis Oktober 2026 - Versi Berbayar):**
   - Modul Keuangan (Xendit/Midtrans BYOA).
   - Modul Tahfiz & Ubudiyah.

**Kesimpulan Eksekutif:** 
Berhentilah melihat dokumen 13 Modul itu sebagai "Target Proyek 3 Bulan". Jadikan dokumen `docx` tersebut sebagai **"Buku Menu / Roadmap 3 Tahun"**. 

Di 3 bulan pertama ini dengan modal Rp 5.000.000, Anda hanya sedang membangun sebuah warung kecil (Core Engine) yang pondasinya sangat kuat agar kelak bisa dibangun 13 lantai ke atas. Arsitektur Modular (Laravel Modules + Next.js) yang kita rancang di dokumen 21-30 telah mengakomodasi strategi Micro SaaS ini dengan sempurna.