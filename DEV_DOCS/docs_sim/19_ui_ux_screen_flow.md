# Spesifikasi Alur Layar UI/UX (UI/UX Screen Flow)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** UI/UX Designer & System Analyst  

---

## 1. Pendahuluan
Dokumen ini memetakan hierarki navigasi (*Screen Flow*) dan kondisi (*State*) halaman untuk aplikasi *frontend* (SPA Vue.js). Panduan ini dirancang agar *Developer Frontend* tidak perlu menebak perilaku UI, terutama pada skenario data kosong (*empty state*) atau proses memuat data (*loading state*).

Desain UI (Visual) akan bergantung 100% pada *Framework CSS Utility* **TailwindCSS** dan komponen UI bebas pakai (misal: Headless UI atau Shadcn-Vue) untuk mempercepat rilis MVP (3 bulan).

---

## 2. Struktur Navigasi & Routing (Vue Router)

Semua rute dilindungi oleh *Navigation Guards*, kecuali `/login` dan `/parent/...`.

### 2.1. Alur Autentikasi Publik
- `/login` (Halaman Login Guru/TU)
  - Layout: Form terpusat, logo madrasah di atas.
  - State: Jika tombol ditekan, muncul *spinner* loading, tombol *disabled* (mencegah klik ganda).
- `/forgot-password` (Lupa Password)
- `/parent/login` (Halaman Login Khusus Orang Tua via NISN + PIN)

### 2.2. Alur Internal Tata Usaha (Layout: Sidebar Kiri, Topbar Profil)
- `/tu/dashboard` (Beranda TU)
  - Menampilkan Widget: Total Siswa Aktif, Total Guru, Rekap SPP Hari Ini.
- `/tu/students` (Data Induk Siswa - Tabel)
  - `/tu/students/create` (Formulir Tambah Siswa)
  - `/tu/students/{id}/edit` (Edit Profil Siswa)
- `/tu/classes` (Manajemen Kelas/Rombel)
  - `/tu/classes/{id}` (Detail Kelas: List Siswa dalam kelas, Tombol "Tambah Siswa ke Kelas")
- `/tu/billing` (Manajemen Keuangan / SPP)
  - Layout: Tab "Tagihan Aktif" dan "Riwayat Pembayaran".
  - Aksi: Tombol "Generate Tagihan" memunculkan Modal/Dialog (bukan pindah halaman).

### 2.3. Alur Internal Guru (Layout: Sidebar Kiri, Topbar Profil)
- `/guru/dashboard` (Beranda Guru)
  - Menampilkan Widget: Jadwal mengajar hari ini, Rekap persentase kehadiran anak didik.
- `/guru/attendance` (Presensi Siswa)
  - Layout: Pilih Kelas > Muncul Tabel Siswa.
  - Baris tiap siswa memiliki *Radio Button / Toggle* tersembunyi berwujud tombol (H/S/I/A). Default H (hijau). S (kuning), I (biru), A (merah).
- `/guru/scores` (Input Nilai Formatif & Sumatif)
  - Layout: Mirip *Spreadsheet / Excel*. Header kolom adalah Jenis Ujian (UH1, UH2, UTS, UAS). 
  - Sel dapat di-*edit inline* (diklik langsung isi angka), auto-save (debounce) atau pakai tombol "Simpan" massal di bawah tabel.
- `/guru/report-card` (e-Rapor - *Hanya untuk role Wali Kelas*)
  - Layout: Tabel daftar siswa di kelasnya. Kolom "Status Input Nilai Mapel". Tombol "Kalkulasi Nilai".

### 2.4. Alur Portal Orang Tua (Layout: Mobile First / Bottom Navigation)
Karena mayoritas diakses dari HP, menu berbentuk *Bottom Tab Bar* (seperti aplikasi Gojek/Tokopedia).
- `/parent/home` (Beranda)
  - Menampilkan kartu profil anak dan ringkasan kehadiran minggu ini.
- `/parent/scores` (Nilai)
  - List vertikal nilai ulangan terbaru.
- `/parent/billing` (Keuangan)
  - Kartu tagihan (merah jika belum dibayar, hijau jika lunas). Klik untuk lihat rincian/download PDF.

---

## 3. Aturan Keadaan (State Rules)

Developer UI wajib menerapkan 3 keadaan ini di setiap halaman dinamis:

### 3.1. Loading State (Memuat)
- Jangan gunakan halaman putih kosong.
- Gunakan **Skeleton Loader** (kotak abu-abu berkedip halus) berbekal Tailwind (`animate-pulse`) dengan bentuk menyerupai konten asli (tabel/teks).
- Untuk aksi spesifik (misal tombol "Simpan"), gunakan spinner berputar (`svg animate-spin`) di dalam tombol dan ubah state tombol jadi `disabled`.

### 3.2. Empty State (Data Kosong)
- Jika tabel siswa kosong / belum ada data SPP, jangan tampilkan tabel kosong bergaris.
- Tampilkan Ilustrasi SVG abu-abu (misal gambar kotak kosong/folder), diikuti teks penjelas: *"Belum ada data siswa di kelas ini."*
- Sediakan **Call-to-Action (CTA)** utama. (Contoh: *"Belum ada siswa? [Tambah Siswa Sekarang]"*).

### 3.3. Error State (Kegagalan Sistem)
- Jika API merespons 500 atau jaringan terputus.
- Tampilkan komponen sentral (di tengah area konten) berikon peringatan (segitiga kuning/merah).
- Teks: *"Maaf, gagal memuat data. Periksa koneksi internet Anda atau coba lagi nanti."*
- Tombol: *"Muat Ulang (Retry)"*.

---

## 4. Sistem Warna UI (Color Palette - Tailwind)

Merujuk pada desain logo Islami / Pendidikan Madrasah (Hijau & Emas/Kuning):
- **Primary:** `emerald-600` (Sidebar, Tombol Utama, Header Aksen).
- **Secondary:** `amber-500` (Peringatan, Tombol Edit).
- **Danger/Error:** `rose-600` (Tombol Hapus, Status Alpa/Tunggakan).
- **Success:** `teal-500` (Status Hadir, Lunas).
- **Background:** `gray-50` (Warna dasar halaman di luar kartu putih).
- **Text:** `slate-800` (Teks utama), `slate-500` (Teks sekunder/label).

---
*Dokumen ini merupakan referensi visual bagi tim frontend. Penggunaan framework komponen seperti Vue-Tailwind atau sejenisnya sangat disarankan untuk mencapai target MVP 3 bulan.*