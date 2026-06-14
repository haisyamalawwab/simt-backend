# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs/YAYASAN

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** DRAFT  
**Penulis:** Tim Proyek SIMT MTs

---

## 1. EXECUTIVE SUMMARY

### 1.1 Product Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                      PRODUCT OVERVIEW                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Product Name:   SIMT MTs (Sistem Informasi Manajemen Terpadu)     │
│  Product Type:   Web-based School Management System                │
│  Target Market:  Madrasah Tsanawiyah (MTs/SMP Islamic) Indonesia   │
│  Target Users:   11 stakeholder groups (see section 2)             │
│                                                                     │
│  Core Value Proposition:                                           │
│  "Platform terintegrasi untuk mengelola seluruh aspek              │
│   operasional MTs dengan fitur UNIK Tahfiz & Inklusi yang          │
│   tidak ada di kompetitor manapun"                                 │
│                                                                     │
│  Key Differentiators:                                              │
│  ✦ Modul Tahfiz profesional (monitoring hafalan, munaqosah)       │
│  ✦ Modul Inklusi/PDBK sesuai regulasi Kemendikdasmen             │
│  ✦ Integrasi resmi EMIS & DAPODIK                                 │
│  ✦ E-Rapor terintegrasi RDM (Rapor Digital Madrasah)              │
│  ✦ Portal Orang Tua dengan WhatsApp integration                   │
│  ✦ Multi-tenant untuk yayasan dengan banyak MTs                   │
│                                                                     │
│  Current State:     MVP Development (Planning Phase)               │
│  Launch Target:     Q1 2027 (18 months from project start)         │
│  Development Model: Agile/Scrum with phased deployment             │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.2 Problem Statement

```
┌─────────────────────────────────────────────────────────────────────┐
│                     PROBLEM STATEMENT                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  PROBLEM 1: Fragmentasi Sistem                                     │
│  ─────────────────────────────────                                 │
│  Sekolah menggunakan 5-10 aplikasi terpisah untuk pengelolaan:     │
│  - Excel/spreadsheet untuk nilai                                   │
│  - RDM untuk rapor (Kemenag)                                       │
│  - EMIS web untuk data pokok (Kemenag)                            │
│  - Aplikasi terpisah untuk keuangan                                │
│  - Grup WhatsApp untuk komunikasi                                  │
│  → Akibat: Data tidak konsisten, redundant, susah dianalisis       │
│                                                                     │
│  PROBLEM 2: Tidak Ada Sistem Terintegrasi untuk MTs               │
│  ────────────────────────────────────────                          │
│  Produk yang ada di pasar:                                         │
│  - SISFOKOL (outdated, tidak support fitur Islamic)               │
│  - Smart School Codecanyon (internasional, tidak ada fitur MTs)    │
│  - Vendor lokal (tidak ada yang fokus MTs)                        │
│  → Akibat: Tidak ada yang memenuhi kebutuhan spesifik MTs          │
│                                                                     │
│  PROBLEM 3: Modul Tahfiz & Inklusi Tidak Ada di Pasaran            │
│  ────────────────────────────────────────────────                  │
│  Tidak ada sistem komersial yang punya:                            │
│  - Monitoring hafalan Al-Quran per siswa                           │
│  - Sistem munaqosah dan tilawati                                   │
│  - Program Pembelajaran Individual (PPI) untuk ABK                 │
│  - Dashboard GPK (Guru Pendamping Khusus)                         │
│  → Akibat: Sekolah masih用手工 (manual) untuk kebutuhan ini        │
│                                                                     │
│  PROBLEM 4: Komunikasi dengan Orang Tua Tidak Efektif             │
│  ────────────────────────────────────────────                      │
│  Saat ini:                                                         │
│  - Info nilai: Perlu tunggu orang tua datang ke sekolah            │
│  - Info presensi: Baru tahu jam 10 malam via grup WA               │
│  - Info keuangan: Sering miss pembayaran                          │
│  → Akibat: Orang tua tidak maksimal dalam mendukung pembelajaran   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.3 Solution Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                     SOLUTION OVERVIEW                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  VISI: "Menjadi platform #1 untuk digitalisasi MTs di Indonesia"   │
│                                                                     │
│  MISI:                                                              │
│  1. Mengintegrasikan seluruh proses bisnis sekolah dalam 1 platform│
│  2. Menyediakan fitur spesifik MTs yang tidak ada di kompetitor   │
│  3. Mempermudah komunikasi sekolah dengan orang tua                │
│  4. Memastikan kepatuhan terhadap regulasi Kemdikdasmen & Kemenag  │
│  5. Memberikan insights berbasis data untuk pengambilan keputusan   │
│                                                                     │
│  HOW WE SOLVE IT:                                                   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                     SIMT MTs                                │   │
│  │  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐        │   │
│  │  │ AKADEMIK│  │KESISWAAN│  │KEUANGAN │  │ TAHFIZ  │        │   │
│  │  │         │  │         │  │         │  │ (UNIQUE)│        │   │
│  │  └─────────┘  └─────────┘  └─────────┘  └─────────┘        │   │
│  │  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐        │   │
│  │  │INKLUSI  │  │   BK    │  │E-OFFICE │  │ PORTAL  │        │   │
│  │  │ (UNIQUE)│  │         │  │         │  │         │        │   │
│  │  └─────────┘  └─────────┘  └─────────┘  └─────────┘        │   │
│  │           ▲             ▲            ▲                      │   │
│  │           │             │            │                      │   │
│  │           └─────────────┴────────────┘                      │   │
│  │                     │                                        │   │
│  │                     ▼                                        │   │
│  │              ┌────────────┐                                  │   │
│  │              │  DASHBOARD │                                  │   │
│  │              │  KEPALA    │                                  │   │
│  │              └────────────┘                                  │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  CORE PRINCIPLES:                                                   │
│  ├── User-Centric: Desain untuk non-technical users                │
│  ├── Mobile-First: Akses via smartphone (majority users)           │
│  ├── Compliance-First: Selalu patuh regulasi                       │
│  ├── Scalable: Multi-tenant untuk growth                          │
│  └── Secure: Data sensitif siswa & keluarga terlindungi           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. STAKEHOLDERS & USERS

### 2.1 Stakeholder Map

```
┌─────────────────────────────────────────────────────────────────────┐
│                       STAKEHOLDER MAP                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                          ┌─────────────┐                            │
│                          │  YAYASAN    │                            │
│                          │  (Sponsor)  │                            │
│                          └──────┬──────┘                            │
│                                 │                                   │
│                    ┌────────────┼────────────┐                      │
│                    │            │            │                      │
│                    ▼            ▼            ▼                      │
│             ┌───────────┐ ┌───────────┐ ┌───────────┐              │
│             │  KEPALA   │ │   WAKA    │ │    TU     │              │
│             │ MADRASAH  │ │ (Kur, KS) │ │(Operator) │              │
│             └─────┬─────┘ └─────┬─────┘ └─────┬─────┘              │
│                   │            │            │                      │
│         ┌─────────┴────────────┴────────────┴─────────┐            │
│         │                      │                      │            │
│         ▼                      ▼                      ▼            │
│    ┌─────────┐          ┌─────────┐          ┌─────────┐          │
│    │  GURU   │          │ GURU BK │          │  GPK    │          │
│    │   +     │          │   +     │          │   +     │          │
│    │WALI KLS │          │ TAHFIZ  │          │ SISWA   │          │
│    └────┬────┘          └────┬────┘          └────┬────┘          │
│         │                    │                    │                │
│         └────────────────────┼────────────────────┘                │
│                              │                                     │
│                              ▼                                     │
│                       ┌───────────┐                                │
│                       │  ORANG    │                                │
│                       │   TUA     │                                │
│                       └───────────┘                                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.2 User Personas

#### Persona 1: Budi Santoso (Kepala Madrasah)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PERSONA 1                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Name:         Budi Santoso, 45 tahun                              │
│  Role:         Kepala Madrasah MTs Al-Hidayah                       │
│  Education:    S2 Pendidikan Islam                                   │
│  Tech Level:   Intermediate (WhatsApp, email, basic Excel)         │
│                                                                     │
│  Goals:                                                        │
│  ├── Monitoring seluruh operasional sekolah tanpa harus turun langsung│
│  ├── Mendapatkan laporan accurate untuk pengambilan keputusan       │
│  ├── Memastikan kepatuhan terhadap regulasi Kemendik & Kemenag     │
│  └── Efisiensi waktu untuk hal strategis                           │
│                                                                     │
│  Pain Points:                                                    │
│  ├── Harus cek banyak aplikasi untuk dapat gambaran sekolah        │
│  ├── Laporan sering telat dan tidak akurat                          │
│  ├── Tidak punya visibilitas real-time                             │
│  └── Banyak waktu habis untuk approval surat fisik                 │
│                                                                     │
│  Wants:                                                         │
│  ├── 1 dashboard untuk lihat semua KPI sekolah                     │
│  ├── Laporan real-time yang bisa di-drill down                      │
│  ├── Tanda tangan digital untuk efisiensi                          │
│  └── Notifikasi untuk hal urgent                                   │
│                                                                     │
│  User Story:                                                     │
│  "Sebagai Kepala Madrasah, saya ingin melihat dashboard yang        │
│   menunjukkan kehadiran siswa, guru, dan status keuangan sekolah    │
│   dalam 1 layar, supaya saya bisa mengambil keputusan cepat         │
│   tanpa harus menunggu laporan dari TU."                           │
│                                                                     │
│  Success Metrics:                                                 │
│  ├── Waktu untuk buat laporan bulanan: 2 jam (dari 2 hari)         │
│  ├── Kepuasan terhadap visibility operasional: 90%                  │
│  └── Pengurangan waktu approval dokumen: 60%                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### Persona 2: Siti Rahayu (Guru Matematika)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PERSONA 2                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Name:         Siti Rahayu, 32 tahun                               │
│  Role:         Guru Matematika Kelas 7 & 8                          │
│  Education:    S1 Pendidikan Matematika                              │
│  Tech Level:   Basic-Medium (WhatsApp, spreadsheet, PowerPoint)    │
│                                                                     │
│  Goals:                                                        │
│  ├── Input nilai dengan cepat dan akurat                           │
│  ├── Mudah akses jadwal dan informasi kelas                        │
│  ├── Komunikasi dengan orang tua tanpa perlu telepon               │
│  └── Kurangi pekerjaan administrasi manual                         │
│                                                                     │
│  Pain Points:                                                    │
│  ├── Input nilai di Excel memakan waktu 2-3 jam/minggu            │
│  ├── Sering salah hitung karena manual                             │
│  ├── Tidak tahu siapa orang tua siswa tanpa tanya TU              │
│  └── Tidak ada sistem pencatatan jurnal mengajar                   │
│                                                                     │
│  Wants:                                                         │
│  ├── Input nilai via HP dengan UI yang simple                      │
│  ├── Auto-calculate nilai akhir dan deskripsi                      │
│  ├── Langsung kirim pesan ke orang tua via sistem                 │
│  └── Template jurnal mengajar yang mudah diisi                     │
│                                                                     │
│  User Story:                                                     │
│  "Sebagai Guru, saya ingin input nilai harian via smartphone       │
│   dengan cepat, dan sistem otomatis generate deskripsi, supaya      │
│   saya tidak perlu buang waktu 2 jam untuk pekerjaan administrative."│
│                                                                     │
│  Success Metrics:                                                  │
│  ├── Waktu input nilai per minggu: 30 menit (dari 2-3 jam)         │
│  ├── Akurasi perhitungan nilai: 100% (dari 95%)                    │
│  └── Kepuasan penggunaan sistem: 85%                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### Persona 3: Ahmad Fauzi (Orang Tua)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PERSONA 3                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Name:         Ahmad Fauzi, 40 tahun                               │
│  Role:         Orang Tua (Ayah dari siswa kelas 7)                 │
│  Occupation:   Pegawai Swasta                                       │
│  Tech Level:   Intermediate (WhatsApp, media sosial, banking app)  │
│                                                                     │
│  Goals:                                                        │
│  ├──Monitor perkembangan akademik anak secara real-time           │
│  ├──Tahu jika anak tidak hadir atau terlambat                      │
│  ├── Mudah akses info pembayaran dan jadwal                        │
│  └── Komunikasi dengan guru tanpa ribet                            │
│                                                                     │
│  Pain Points:                                                    │
│  ├── Tidak tahu nilai anak kecuali tunggu rapor semester           │
│  ├── Tidak tahu anak sakit/izin sampai guru hubungi                │
│  ├── Bingung kapan harus bayar SPP                                  │
│  └── Tidak punya cara mudah komunikasi dengan guru                 │
│                                                                     │
│  Wants:                                                         │
│  ├── Lihat nilai anak setiap saat via HP                           │
│  ├── Dapat notifikasi WA jika anak tidak hadir                     │
│  ├── Dapat reminder pembayaran SPP                                 │
│  └── Bisa chat guru via sistem                                     │
│                                                                     │
│  User Story:                                                     │
│  "Sebagai Orang Tua, saya ingin dapat notifikasi WA jika anak      │
│   saya tidak hadir di sekolah, supaya saya bisa segera tahu dan     │
│   mengambil tindakan. Saya juga ingin lihat nilai anak setiap      │
│   saat tanpa harus tunggu rapor."                                  │
│                                                                     │
│  Success Metrics:                                                  │
│  ├── Engagement dengan sistem: 80% orang tua login weekly          │
│  ├── Response time orang tua terhadap info: <1 jam                 │
│  └── Kepuasan orang tua: 90%                                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### Persona 4: Ustadz Hasan (Pembina Tahfiz)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PERSONA 4                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Name:         Ustadz Hasan, 38 tahun                              │
│  Role:         Pembina Tahfiz MTs Al-Hidayah                        │
│  Education:    S1 Pendidikan Agama, Hafiz                           │
│  Tech Level:   Low-Basic (WhatsApp, basic smartphone)              │
│                                                                     │
│  Goals:                                                        │
│  ├── Monitoring perkembangan hafalan seluruh siswa tahfiz          │
│  ├── Mudahcatatan setoran dan murajaah                             │
│  ├── Komunikasi dengan orang tua tentang progress hafalan          │
│  └── Persiapan ujian munaqosah yang terstruktur                    │
│                                                                     │
│  Pain Points:                                                    │
│  ├── Masih pakai buku hafalan manual untuk 150+ siswa             │
│  ├── Tidak punya visibility siapa yang perlu di-fokuskan           │
│  ├── Orang tua sering bertanya progress anak tapi tidak punya data │
│  └── Tidak ada sistem yang support kebutuhan tahfiz                │
│                                                                     │
│  Wants:                                                         │
│  ├── Input setoran hafalan via HP dengan cepat                     │
│  ├── Lihat grafik perkembangan setiap siswa                        │
│  ├── Generate laporan progress untuk orang tua                     │
│  ├── Sistem pengingat untuk murajaah                               │
│                                                                     │
│  User Story:                                                     │
│  "Sebagai Pembina Tahfiz, saya ingin input setoran hafalan via     │
│   HP dengan cepat, dan sistem otomatis tracking progress siswa,     │
│   supaya saya bisa fokus mengajarkan Al-Quran bukan administrativo."│
│                                                                     │
│  Success Metrics:                                                  │
│  ├── Waktu administrasi tahfiz per minggu: 2 jam (dari 8 jam)      │
│  ├── Akurasi data hafalan: 95%                                     │
│  └── Kepuasan pembina: 90%                                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 3. PRODUCT SPECIFICATIONS

### 3.1 Product Features (MVP)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    MVP FEATURES (Must Have)                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  MODULE 1: AKADEMIK (Priority: CRITICAL)                           │
│  ─────────────────────────────────────────────                      │
│  ✅ F001: Data Akademik Siswa                                      │
│     - Biodata lengkap, riwayat kelas, status                       │
│     - Import dari EMIS                                             │
│                                                                     │
│  ✅ F002: Kelola Rombel & Kelas                                     │
│     - Setup rombel, naik kelas, mutasi                             │
│                                                                     │
│  ✅ F003: Jadwal Pelajaran                                          │
│     - Generate jadwal, penugasan guru, perubahan jadwal            │
│                                                                     │
│  ✅ F004: Presensi Pembelajaran                                     │
│     - Input harian, kategori H/I/S/A, rekap                       │
│     - Notifikasi WA ke orang tua jika tidak hadir                  │
│                                                                     │
│  ✅ F005: Penilaian                                                 │
│     - Input formatif, sumatif, projek                              │
│     - Auto-calculate, auto-deskripsi                               │
│                                                                     │
│  ✅ F006: E-Rapor                                                   │
│     - Generate rapor Kurikulum Merdeka                             │
│     - Integrasi RDM (export/import format)                         │
│     - Cetak PDF dengan barcode                                     │
│                                                                     │
│  MODULE 2: KESISWAAN (Priority: HIGH)                              │
│  ─────────────────────────────────────                             │
│  ✅ F011: Data Organisasi & Ekskul                                  │
│  ✅ F012: Presensi Kesiswaan (check-in)                            │
│  ✅ F013: Sistem Poin Pelanggaran                                   │
│  ✅ F014: Sistem Poin Prestasi                                      │
│  ✅ F015: Perizinan (request, approval)                             │
│                                                                     │
│  MODULE 3: KEUANGAN (Priority: HIGH)                               │
│  ────────────────────────────────                                   │
│  ✅ F018: Tagihan Sekolah (SPP, daftar ulang, dll)                 │
│  ✅ F019: Pembayaran (input, validasi, bukti transfer)             │
│  ✅ F020: Riwayat Transaksi (rekap, tunggakan)                     │
│  ✅ F021: Laporan Keuangan (pemasukan, arus kas)                    │
│  ✅ F022: Notifikasi Pembayaran (reminder WA)                       │
│                                                                     │
│  MODULE 4: PORTAL ORANG TUA (Priority: HIGH)                       │
│  ───────────────────────────────────────                           │
│  ✅ Dashboard Monitoring Akademik (nilai, presensi, jadwal)        │
│  ✅ Dashboard Monitoring Keuangan (tagihan, pembayaran)            │
│  ✅ Notifikasi WhatsApp (kehadiran, nilai, keuangan)               │
│  ✅ Fitur Komunikasi (chat guru, pengumuman)                        │
│                                                                     │
│  MODULE 5: DASHBOARD KEPALA MADRASAH (Priority: HIGH)              │
│  ────────────────────────────────────────────────                  │
│  ✅ KPI Overview (kehadiran, nilai, keuangan, tahfiz)              │
│  ✅ Laporan Real-time (drill-down capability)                       │
│  ✅ Notifikasi Issue (hal urgent yang perlu perhatian)             │
│                                                                     │
│  MODULE 6: USER MANAGEMENT (Priority: CRITICAL)                    │
│  ─────────────────────────────────────────────                      │
│  ✅ Multi-role authentication (15 roles)                           │
│  ✅ RBAC (Role-based access control)                               │
│  ✅ Scope-based data access                                        │
│  ✅ Audit trail                                                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Enhanced Features (Phase 2)

```
┌─────────────────────────────────────────────────────────────────────┐
│               ENHANCED FEATURES (Should Have)                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  MODULE 7: TAHFIZ (Priority: HIGH) - UNIQUE DIFFERENTIATOR         │
│  ─────────────────────────────────────────────                      │
│  ✅ F024: Program Tahfiz (target, kurikulum)                        │
│  ✅ F025: Penilaian Hafalan (setoran, tajwid, murajaah)            │
│  ✅ F026: Monitoring Perkembangan (grafik, warning)                │
│  ✅ F027: Ujian Tahfiz (tasmik, munaqosah)                         │
│  ✅ F028: Munaqosah (pendaftaran, sertifikat)                      │
│  ✅ F029: Tilawati System (tracking level)                         │
│  ✅ F030: Dashboard Tahfiz (rekap, statistik)                      │
│                                                                     │
│  MODULE 8: INKLUSI/PDBK (Priority: HIGH) - UNIQUE DIFFERENTIATOR   │
│  ─────────────────────────────────────────────                      │
│  ✅ F031: Identifikasi ABK (screening, kategori)                    │
│  ✅ F032: Program Pembelajaran Individual (PPI)                     │
│  ✅ F033: Pendampingan GPK (jadwal, catatan sesi)                  │
│  ✅ F034: Asesmen Psikologis (IQ, bakat minat)                     │
│  ✅ F035: Adaptasi Kurikulum (modifikasi, akomodasi)               │
│  ✅ F036: Komunikasi Orang Tua (progress, home program)            │
│  ✅ F037: Dashboard Inklusi (statistik, monitoring)                │
│                                                                     │
│  MODULE 9: BK/KONSELING (Priority: MEDIUM)                         │
│  ────────────────────────────────────                              │
│  ✅ F038: Catatan Konseling (kasus, tindak lanjut)                 │
│  ✅ F039: Monitoring Perilaku                                       │
│  ✅ F040: Pemanggilan Orang Tua (generate surat)                   │
│  ✅ F041: Rujukan (psikolog, profesional)                          │
│  ✅ F042: Alat Tes (bakat, kepribadian, penjurusan)                │
│                                                                     │
│  MODULE 10: E-OFFICE (Priority: MEDIUM)                            │
│  ──────────────────────────────────                                │
│  ✅ F044: Surat Masuk/Keluar (digital, tracking)                   │
│  ✅ F045: Disposisi Digital (instruksi, deadline)                   │
│  ✅ F046: Kalender & Agenda                                         │
│  ✅ F047: E-Signature (TTD digital kepala madrasah)                │
│  ✅ F048: Cloud Storage (penyimpanan dokumen terpusat)             │
│  ✅ F049: Arsip Akreditasi                                          │
│  ✅ F050: Monitoring Program Kerja                                  │
│                                                                     │
│  MODULE 11: MODUL AJAR & ADMINISTRASI GURU (Priority: MEDIUM)      │
│  ────────────────────────────────────────────────                  │
│  ✅ F007: Upload Modul Ajar                                         │
│  ✅ F008: Jurnal Mengajar                                           │
│  ✅ F009: Bank Soal                                                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.3 Advanced Features (Phase 3+)

```
┌─────────────────────────────────────────────────────────────────────┐
│               ADVANCED FEATURES (Could Have)                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  MODULE 12: SDM/KEPEGAWAIAN                                         │
│  ──────────────────────────────                                    │
│  ⬜ F051: Data Biodata Guru/PTK                                     │
│  ⬜ F052: Presensi Guru (fingerprint integration)                   │
│  ⬜ F053: Beban Mengajar                                            │
│  ⬜ F054: Pengembangan SDM (pelatihan, sertifikasi)                 │
│  ⬜ F055: Penilaian Kinerja                                         │
│                                                                     │
│  MODULE 13: PERPUSTAKAAN                                            │
│  ──────────────────────────                                        │
│  ⬜ F056: Katalog Buku (ISBN, lokasi rak)                           │
│  ⬜ F057: Peminjaman & Pengembalian                                  │
│  ⬜ F058: Statistik Literasi                                        │
│                                                                     │
│  MOBILE APPLICATION                                                  │
│  ────────────────────                                              │
│  ⬜ iOS App untuk Guru & Siswa                                      │
│  ⬜ Android App untuk Guru & Siswa                                  │
│  ⬜ Push notification via app                                       │
│                                                                     │
│  ADVANCED ANALYTICS                                                  │
│  ────────────────────                                              │
│  ⬜ Predictive analytics (学生的 risiko)                            │
│  ⬜ Comparison benchmarking antar MTs dalam yayasan                 │
│  ⬜ Custom report builder                                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 4. USER INTERACTIONS & FLOWS

### 4.1 User Flow: Input Nilai

```
┌─────────────────────────────────────────────────────────────────────┐
│                 USER FLOW: INPUT NILAI                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  START: Guru login ke sistem                                        │
│                                                                     │
│  Step 1: Pilih Menu "Penilaian"                                    │
│          │                                                          │
│          ▼                                                          │
│  Step 2: Pilih Kelas & Mata Pelajaran                              │
│          │                                                          │
│          ▼                                                          │
│  Step 3: Pilih Jenis Penilaian (Harian/PTS/PAS)                   │
│          │                                                          │
│          ▼                                                          │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  DISPLAY: List siswa dengan input nilai                     │   │
│  │                                                           │   │
│  │  [📱 Mobile View]                                         │   │
│  │                                                           │   │
│  │  ┌─────────────────────────────────────────────────────┐  │   │
│  │  │ Ahmad Fauzi                                          │  │   │
│  │  │ Nilai: [____] Deskripsi: [Auto dari sistem_____]   │  │   │
│  │  └─────────────────────────────────────────────────────┘  │   │
│  │  ┌─────────────────────────────────────────────────────┐  │   │
│  │  │ Budi Santoso                                         │  │   │
│  │  │ Nilai: [____] Deskripsi: [Auto dari sistem_____]   │  │   │
│  │  └─────────────────────────────────────────────────────┘  │   │
│  └─────────────────────────────────────────────────────────────┘   │
│          │                                                          │
│          ▼                                                          │
│  Step 4: Input nilai per siswa (tap, input, next)                  │
│          │                                                          │
│          ▼                                                          │
│  Step 5: Sistem auto-generate deskripsi berdasarkan nilai          │
│          │                                                          │
│          ▼                                                          │
│  Step 6: Submit - Sistem validasi & simpan                         │
│          │                                                          │
│          ▼                                                          │
│  Step 7: Notifikasi ke Wali Kelas (ada nilai baru)                 │
│          │                                                          │
│          ▼                                                          │
│  END: Nilai tersimpan, siap untuk rapor                             │
│                                                                     │
│  ALTERNATIVE FLOWS:                                                │
│  ├── A1: Import dari Excel                                         │
│  ├── A2: Template tidak sesuai → Guru bisa edit deskripsi manual  │
│  └── A3: Error validasi → Show error message, allow retry         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.2 User Flow: Monitoring Anak (Orang Tua)

```
┌─────────────────────────────────────────────────────────────────────┐
│            USER FLOW: MONITORING ANAK (ORANG TUA)                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  START: Orang Tua buka link portal dari WA notification            │
│         (atau buka app/website)                                     │
│                                                                     │
│  Step 1: Login dengan no HP & OTP                                   │
│          │                                                          │
│          ▼                                                          │
│  Step 2: Dashboard Home menampilkan:                                │
│          ├── Notifikasi terbaru (3 item terakhir)                   │
│          ├── Ringkasan kehadiran minggu ini                        │
│          ├── Ringkasan nilai terbaru                               │
│          └── Quick actions (Lihat nilai, Bayar, Izin)              │
│          │                                                          │
│          ▼                                                          │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  APP HOME SCREEN                                            │   │
│  │                                                             │   │
│  │  👋 Selamat Pagi, Pak Ahmad!                               │   │
│  │                                                             │   │
│  │  ┌─────────────────────────────────────────────────────┐   │   │
│  │  │ 📊 Ringkasan Anak: Budi Santoso (Kelas 7A)          │   │   │
│  │  │                                                    │   │   │
│  │  │ Kehadiran: ✅ Hadir 18/20 hari                     │   │   │
│  │  │ Nilai Rata-rata: 85 (↑5 dari semester lalu)        │   │   │
│  │  │ Hafalan: 📖 3 Juz (Target: 4 Juz)                  │   │   │
│  │  │ SPP: ✅ Lunas                                        │   │   │
│  │  └─────────────────────────────────────────────────────┘   │   │
│  │                                                             │   │
│  │  📢 Pengumuman                                             │   │
│  │  ├── 🔴 Ujian Tengah Semester: 20 Juni 2026               │   │
│  │  └── 🟡 Jadwal Munaqosah: 25 Juni 2026                    │   │   │
│  │                                                             │   │
│  │  [Lihat Nilai] [Bayar SPP] [Izin] [Chat Guru]             │   │
│  └─────────────────────────────────────────────────────────────┘   │
│          │                                                          │
│          ▼                                                          │
│  Step 3: Orang Tua bisa drill-down:                                │
│          ├── "Lihat Nilai" → Detail per mapel                      │
│          ├── "Bayar SPP" → Halaman pembayaran                      │
│          ├── "Izin" → Form request izin                            │
│          └── "Chat Guru" → Chat dengan Wali Kelas                  │
│          │                                                          │
│          ▼                                                          │
│  END: Orang tua punya visibilitas penuh terhadap anak               │
│                                                                     │
│  PUSH NOTIFICATION TRIGGERS:                                       │
│  ├── NT1: Anak tidak hadir → Auto WA jam 08:30                    │
│  ├── NT2: Anak terlambat >15 menit → Auto WA jam 07:45            │
│  ├── NT3: Nilai baru diinput → Notifikasi dalam app               │
│  ├── NT4: Tagihan jatuh tempo → Reminder 3 hari sebelum           │
│  └── NT5: Pengumuman penting → Push notification                  │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.3 User Flow: Modul Tahfiz

```
┌─────────────────────────────────────────────────────────────────────┐
│                 USER FLOW: INPUT SETORAN HAFALAN                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  START: Ustadz Hasan (Pembina Tahfiz) login                        │
│                                                                     │
│  Step 1: Pilih Menu "Tahfiz" → "Setoran Hafalan"                   │
│          │                                                          │
│          ▼                                                          │
│  Step 2: Pilih kelas dan jadwal kegiatan                           │
│          │                                                          │
│          ▼                                                          │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  SETORAN HAFALAN - Kelas 7A - Senin, 10 Juni 2026          │   │
│  │                                                             │   │
│  │  ┌─────────────────────────────────────────────────────┐   │   │
│  │  │ 👤 Ahmad Fauzi                                       │   │   │
│  │  │ Surah: [Al-Mulk] Halaman: [____]                     │   │   │
│  │  │ Target: Ayat 1-10                                    │   │   │
│  │  │ Status: ○ Belum ○ Sedang ○ Lancar ○ Qira'ah Bagus   │   │   │
│  │  │ Catatan: [________________________]                  │   │   │
│  │  │ Murajaah: □ Sudah □ Belum                             │   │   │
│  │  └─────────────────────────────────────────────────────┘   │   │
│  │                                                             │   │
│  │  [+ Siswa Berikutnya] [Simpan Semua]                       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│          │                                                          │
│          ▼                                                          │
│  Step 3: Input setoran per siswa (ceklist cepat)                   │
│          │                                                          │
│          ▼                                                          │
│  Step 4: Sistem update progress hafalan siswa                       │
│          │                                                          │
│          ▼                                                          │
│  Step 5: Notifikasi otomatis ke orang tua:                          │
│          "Anak Anda telah menyetor hafalan Al-Mulk ayat 1-10.       │
│           Status: Lancar. Murajaah: Sudah."                          │
│          │                                                          │
│          ▼                                                          │
│  END: Data setoran tersimpan, progress otomatis terupdate           │
│                                                                     │
│  DASHBOARD TAHFIZ OTOMATIS:                                         │
│  ├── Grafik progress per siswa                                      │
│  ├── Ranking murajaah                                               │
│  ├── Warning siswa yang target tidak tercapai                       │
│  └── Export laporan bulanan                                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 5. API DESIGN (OUTLINE)

### 5.1 API Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                      API ARCHITECTURE                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                        CLIENTS                              │   │
│  │   ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐       │   │
│  │   │Web App  │  │ Mobile  │  │  WA Bot │  │ 3rd     │       │   │
│  │   │(React)  │  │ (React  │  │(Green   │  │ Party   │       │   │
│  │   │         │  │  Native)│  │  API)   │  │         │       │   │
│  │   └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘       │   │
│  └────────┼────────────┼────────────┼────────────┼─────────────┘   │
│           │            │            │            │                 │
│           └────────────┴────────────┴────────────┘                 │
│                              │                                      │
│                              ▼                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    API GATEWAY                               │   │
│  │   Rate Limiting | Auth | Logging | Versioning               │   │
│  └────────────────────────────┬────────────────────────────────┘   │
│                               │                                      │
│           ┌───────────────────┼───────────────────┐                 │
│           │                   │                   │                 │
│           ▼                   ▼                   ▼                 │
│  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐          │
│  │   REST API  │     │  GraphQL    │     │  WebSocket  │          │
│  │   (Main)    │     │  (Future)   │     │  (Real-time)│          │
│  └──────┬──────┘     └─────────────┘     └─────────────┘          │
│         │                                                       │
│         └───────────────────────┬───────────────────────────────────┘
│                                 │
│                                 ▼
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    SERVICE LAYER                             │   │
│  │   ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐       │   │
│  │   │Akademik │  │Keuangan │  │ Kesiswan │  │ Tahfiz  │       │   │
│  │   │Service  │  │ Service │  │ Service  │  │ Service │       │   │
│  │   └─────────┘  └─────────┘  └─────────┘  └─────────┘       │   │
│  │   ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐       │   │
│  │   │Inklusi  │  │   BK    │  │ E-Office│  │  User   │       │   │
│  │   │Service  │  │ Service │  │ Service │  │ Service │       │   │
│  │   └─────────┘  └─────────┘  └─────────┘  └─────────┘       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                               │                                      │
│                               ▼                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    DATA LAYER                                │   │
│  │         PostgreSQL  |  Redis  |  S3  |  Elasticsearch       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                               │                                      │
│                               ▼                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                 EXTERNAL INTEGRATIONS                        │   │
│  │   ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐       │   │
│  │   │  EMIS   │  │DAPODIK  │  │   RDM   │  │  WhatsApp│       │   │
│  │   │   API   │  │   API   │  │   API   │  │  Gateway │       │   │
│  │   └─────────┘  └─────────┘  └─────────┘  └─────────┘       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.2 Key API Endpoints

```
┌─────────────────────────────────────────────────────────────────────┐
│                      KEY API ENDPOINTS                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  AUTHENTICATION                                                      │
│  POST   /api/v1/auth/login                    # Login              │
│  POST   /api/v1/auth/logout                   # Logout             │
│  POST   /api/v1/auth/refresh                  # Refresh token      │
│  POST   /api/v1/auth/forgot-password          # Forgot password    │
│                                                                     │
│  STUDENTS (Akademik)                                                 │
│  GET    /api/v1/students                      # List siswa         │
│  GET    /api/v1/students/:id                  # Detail siswa       │
│  POST   /api/v1/students                      # Create siswa       │
│  PUT    /api/v1/students/:id                  # Update siswa       │
│  GET    /api/v1/students/:id/nilai            # Nilai siswa        │
│  GET    /api/v1/students/:id/presensi         # Presensi siswa     │
│                                                                     │
│  CLASSES & ROMBEL                                                    │
│  GET    /api/v1/classes                       # List kelas         │
│  GET    /api/v1/classes/:id/students          # Siswa di kelas     │
│  POST   /api/v1/classes/:id/promote           # Naik kelas         │
│                                                                     │
│  SCHEDULES                                                           │
│  GET    /api/v1/schedules                     # List jadwal        │
│  GET    /api/v1/schedules/my                  # Jadwal guru sendiri│
│  POST   /api/v1/schedules/generate            # Generate jadwal    │
│                                                                     │
│  ATTENDANCE                                                          │
│  POST   /api/v1/attendance                    # Input presensi     │
│  GET    /api/v1/attendance/rekap/:classId     # Rekap presensi     │
│  POST   /api/v1/attendance/notify-parent      # Notif ortu         │
│                                                                     │
│  ASSESSMENT (Nilai)                                                  │
│  POST   /api/v1/assessments                   # Input nilai        │
│  GET    /api/v1/assessments/:studentId        # Nilai siswa        │
│  POST   /api/v1/assessments/bulk              # Bulk input         │
│  GET    /api/v1/assessments/report/:classId   # Report nilai       │
│                                                                     │
│  FINANCE                                                             │
│  GET    /api/v1/bills                         # List tagihan       │
│  POST   /api/v1/bills                         # Create tagihan     │
│  GET    /api/v1/payments                      # List pembayaran    │
│  POST   /api/v1/payments                      # Input pembayaran   │
│  POST   /api/v1/payments/confirm              # Confirm byr        │
│  GET    /api/v1/reports/financial             # Laporan keuangan   │
│                                                                     │
│  TAHFIZ (UNIQUE)                                                     │
│  GET    /api/v1/tahfiz/programs               # List program       │
│  POST   /api/v1/tahfiz/recordings             # Input setoran      │
│  GET    /api/v1/tahfiz/progress/:studentId    # Progress hafalan   │
│  GET    /api/v1/tahfiz/dashboard              # Dashboard tahfiz   │
│  POST   /api/v1/tahfiz/exam                   # Jadwal ujian       │
│  GET    /api/v1/tahfiz/certificate/:studentId # Certificate        │
│                                                                     │
│  INKLUSI (PDBK)                                                      │
│  GET    /api/v1/inklusi/students              # List siswa inklusi │
│  POST   /api/v1/inklusi/ppi                   # Create PPI         │
│  GET    /api/v1/inklusi/ppi/:studentId        # Detail PPI         │
│  POST   /api/v1/inklusi/session               # Catatan sesi       │
│  GET    /api/v1/inklusi/dashboard             # Dashboard          │
│                                                                     │
│  PARENT PORTAL                                                       │
│  GET    /api/v1/parent/dashboard              # Dashboard ortu     │
│  GET    /api/v1/parent/children/:id/nilai     # Nilai anak         │
│  GET    /api/v1/parent/children/:id/attendance│# Presensi anak     │
│  GET    /api/v1/parent/messages               # Pesan masuk        │
│  POST   /api/v1/parent/messages               # Kirim pesan        │
│  GET    /api/v1/parent/bills                  # Tagihan            │
│                                                                     │
│  ADMIN/HEADMASTER                                                    │
│  GET    /api/v1/admin/dashboard               # KPI dashboard      │
│  GET    /api/v1/admin/reports/:type           # laporan            │
│  POST   /api/v1/admin/approvals/:type         # Approval           │
│  GET    /api/v1/admin/users                   # Kelola user        │
│                                                                     │
│  INTEGRATIONS                                                        │
│  GET    /api/v1/integrations/emis/sync        # Sync EMIS          │
│  POST   /api/v1/integrations/dapodik/import   # Import Dapodik     │
│  GET    /api/v1/integrations/rdm/export       # Export RDM         │
│  POST   /api/v1/integrations/whatsapp/send    # Send WA            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.3 Response Format

```json
{
    "success": true,
    "data": {
        "id": 123,
        "name": "Ahmad Fauzi",
        "class": "7A",
        "nilai": {
            "matematika": {
                "formatif": [85, 90, 78],
                "pts": 88,
                "pas": 92,
                "akhir": 87
            }
        }
    },
    "meta": {
        "page": 1,
        "per_page": 20,
        "total": 150
    },
    "message": "Success"
}

// Error Response
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Nilai harus antara 0-100",
        "details": [
            {
                "field": "nilai",
                "message": "Nilai harus antara 0-100"
            }
        ]
    }
}
```

---

## 6. TECHNICAL REQUIREMENTS

### 6.1 Technology Stack

```
┌─────────────────────────────────────────────────────────────────────┐
│                    TECHNOLOGY STACK                                 │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FRONTEND:                                                           │
│  ├── Framework: Next.js 14 (React) atau Nuxt.js (Vue)              │
│  ├── UI Library: TailwindCSS + HeadlessUI atau Chakra UI           │
│  ├── State Management: Zustand atau Pinia                          │
│  ├── Forms: React Hook Form + Zod                                  │
│  ├── Charts: Recharts atau Chart.js                                │
│  └── Icons: Heroicons atau Lucide                                   │
│                                                                     │
│  BACKEND:                                                            │
│  ├── Framework: NestJS (TypeScript) atau Laravel (PHP)             │
│  ├── Language: TypeScript atau PHP 8.2                             │
│  ├── ORM: Prisma atau Eloquent                                     │
│  ├── Validation: class-validator atau Laravel Validator            │
│  ├── Auth: Passport.js + JWT                                       │
│  └── API Docs: Swagger/OpenAPI                                     │
│                                                                     │
│  DATABASE:                                                           │
│  ├── Primary: PostgreSQL 15                                        │
│  ├── Cache: Redis 7                                                │
│  ├── Search: Meilisearch atau Elasticsearch                         │
│  └── Queue: Bull (Redis) atau RabbitMQ                             │
│                                                                     │
│  INFRASTRUCTURE:                                                     │
│  ├── Hosting: VPS Indonesia (IDCloudhost) atau AWS Jakarta         │
│  ├── Container: Docker + Docker Compose                             │
│  ├── CI/CD: GitHub Actions                                          │
│  ├── Monitoring: Prometheus + Grafana                              │
│  ├── Logging: ELK Stack atau Loki                                  │
│  └── CDN: Cloudflare                                               │
│                                                                     │
│  INTEGRATIONS:                                                       │
│  ├── WhatsApp: Green API atau Fonnte                               │
│  ├── Payment: Midtrans                                             │
│  ├── SMS: Gammu atau Twilio                                        │
│  └── Storage: S3-compatible (Scaleway Object Storage)             │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 6.2 Non-Functional Requirements

```
┌─────────────────────────────────────────────────────────────────────┐
│                  NON-FUNCTIONAL REQUIREMENTS                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  PERFORMANCE:                                                        │
│  ├── API Response < 200ms (P95)                                    │
│  ├── Page Load < 3s (P95)                                          │
│  ├── Support 500 concurrent users per instance                     │
│  └── Database query < 100ms (P95)                                  │
│                                                                     │
│  AVAILABILITY:                                                       │
│  ├── Target Uptime: 99.5%                                          │
│  ├── Maximum downtime: 4 hours/month                               │
│  ├── Backup: Daily incremental, Weekly full                        │
│  └── Disaster Recovery: RTO < 4 hours, RPO < 1 hour                │
│                                                                     │
│  SECURITY:                                                           │
│  ├── SSL/TLS everywhere                                             │
│  ├── OWASP Top 10 compliant                                        │
│  ├── Encryption: AES-256 for data at rest                          │
│  ├── Password: bcrypt with salt                                    │
│  ├── 2FA: TOTP for admin accounts                                  │
│  ├── Rate limiting: 100 req/min per IP                             │
│  ├── WAF: Cloudflare or AWS WAF                                    │
│  └── Annual penetration testing                                    │
│                                                                     │
│  SCALABILITY:                                                        │
│  ├── Horizontal scaling: Add more web servers                      │
│  ├── Database replication: Read replicas                           │
│  ├── Caching: Redis for session and query cache                    │
│  └── CDN for static assets                                         │
│                                                                     │
│  COMPATIBILITY:                                                      │
│  ├── Browser: Chrome, Firefox, Safari, Edge (latest 2 versions)   │
│  ├── Mobile: iOS 14+, Android 10+                                  │
│  ├── Responsive: Mobile-first design                               │
│  └── Accessibility: WCAG 2.1 AA                                    │
│                                                                     │
│  MAINTAINABILITY:                                                    │
│  ├── Code coverage: >80% for critical modules                      │
│  ├── CI/CD pipeline with automated testing                         │
│  ├── API documentation: OpenAPI 3.0                                │
│  └── Modular architecture for easy updates                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 7. SUCCESS METRICS

### 7.1 Product Metrics

```
┌─────────────────────────────────────────────────────────────────────┐
│                      PRODUCT METRICS                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ADOPTION:                                                           │
│  ├── MAU (Monthly Active Users): Target 1000+ dalam 6 bulan        │
│  ├── DAU (Daily Active Users): Target 30% of MAU                   │
│  ├── Stickiness Ratio (DAU/MAU): Target > 30%                      │
│  └── User Activation Rate: Target > 70% (completed onboarding)    │
│                                                                     │
│  ENGAGEMENT:                                                         │
│  ├── Average session duration: > 5 minutes                         │
│  ├── Actions per session: > 10 actions                             │
│  ├── Feature adoption rate: > 60% using core features              │
│  └── Parent engagement: > 80% logged in weekly                     │
│                                                                     │
│  PERFORMANCE:                                                        │
│  ├── API Response Time: < 200ms (P95)                              │
│  ├── Error Rate: < 0.1%                                            │
│  ├── Availability: > 99.5%                                         │
│  └── Mobile Load Time: < 3s (3G network)                           │
│                                                                     │
│  RETENTION:                                                          │
│  ├── Monthly Retention: > 85%                                      │
│  ├── Annual Retention: > 70%                                       │
│  └── Churn Rate: < 5% per bulan                                    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 7.2 Business Metrics

```
┌─────────────────────────────────────────────────────────────────────┐
│                      BUSINESS METRICS                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  GROWTH:                                                             │
│  ├── Number of Schools: Target 50 dalam 12 bulan                   │
│  ├── Number of Students: Target 10.000+ dalam 12 bulan             │
│  └── ARR (Annual Recurring Revenue): Target Rp 500jt dalam 18 bln  │
│                                                                     │
│  REVENUE:                                                            │
│  ├── Average Revenue Per School (ARPS): Target Rp 10jt/tahun       │
│  ├── Customer Acquisition Cost (CAC): Target < Rp 5jt              │
│  └── Lifetime Value (LTV): Target > Rp 30jt                        │
│                                                                     │
│  CUSTOMER SATISFACTION:                                              │
│  ├── NPS (Net Promoter Score): Target > 50                        │
│  ├── CSAT (Customer Satisfaction): Target > 85%                    │
│  └── Response Time to Support: < 4 hours                           │
│                                                                     │
│  EFFICIENCY:                                                         │
│  ├── Time to Value: < 1 week (from signup to active)               │
│  ├── Support Ticket Resolution: < 24 hours                        │
│  └── System Downtime Cost: < Rp 500.000/hour                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 8. ROADMAP & MILESTONES

### 8.1 Product Roadmap

```
┌─────────────────────────────────────────────────────────────────────┐
│                       PRODUCT ROADMAP                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Q3 2026: FOUNDATION                                                │
│  ├── Phase 1: Planning & Design                                     │
│  │   ├── System architecture design                                │
│  │   ├── Database schema design                                    │
│  │   ├── UI/UX wireframing                                         │
│  │   └── MVP feature prioritization                                │
│  │                                                              │
│  └── Month 1-2: Core Infrastructure                                 │
│      ├── Dev environment setup                                     │
│      ├── CI/CD pipeline                                            │
│      └── Base authentication system                                │
│                                                                     │
│  Q4 2026: MVP DEVELOPMENT                                           │
│  ├── Month 3-4: Core Akademik                                       │
│  │   ├── Student management                                        │
│  │   ├── Class management                                          │
│  │   ├── Schedule management                                       │
│  │   └── Attendance system                                         │
│  │                                                              │
│  └── Month 5-6: Core Finance + Portal                               │
│      ├── Billing system                                            │
│      ├── Payment management                                        │
│      ├── Parent portal                                             │
│      └── WhatsApp integration                                      │
│                                                                     │
│  Q1 2027: MVP LAUNCH + PILOT                                        │
│  ├── Month 7-8: Integration + Dashboard                             │
│  │   ├── RDM integration                                           │
│  │   ├── EMIS integration                                          │
│  │   ├── Dashboard kepala madrasah                                 │
│  │   └── Reporting system                                          │
│  │                                                              │
│  └── Month 9: PILOT LAUNCH                                          │
│      ├── UAT dengan 1-2 MTs pilot                                  │
│      ├── Training users                                            │
│      └── Go-live MVP                                               │
│                                                                     │
│  Q2 2027: ENHANCED FEATURES                                         │
│  ├── Month 10-11: Tahfiz + Inklusi                                  │
│  │   ├── Modul Tahfiz (UNIQUE DIFFERENTIATOR)                     │
│  │   ├── Modul Inklusi/PDBK (UNIQUE DIFFERENTIATOR)              │
│  │   └── Enhanced dashboard                                        │
│  │                                                              │
│  └── Month 12: BK + E-Office                                       │
│      ├── Modul BK/Konseling                                        │
│      └── Modul E-Office                                            │
│                                                                     │
│  Q3-Q4 2027: SCALE & GROWTH                                         │
│  ├── Month 13-14: Multi-tenant & Scaling                           │
│  │   ├── Multi-tenant architecture                                 │
│  │   └── Performance optimization                                  │
│  │                                                              │
│  ├── Month 15-16: Mobile App                                       │
│  │   ├── iOS App                                                   │
│  │   └── Android App                                               │
│  │                                                              │
│  └── Month 17-18: Commercial Launch                                │
│      ├── Full-scale marketing                                      │
│      ├── Customer success team                                     │
│      └── Expansion to other regions                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Milestone Timeline

```
┌─────────────────────────────────────────────────────────────────────┐
│                       MILESTONE TIMELINE                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  M1: Foundation Complete (Month 2)                                  │
│      → System architecture approved                                │
│      → Wireframes finalized                                         │
│      → Development environment ready                                │
│      → Budget approved                                              │
│                                                                     │
│  M2: Core MVP Ready (Month 6)                                       │
│      → Akademik module functional                                  │
│      → Finance module functional                                   │
│      → Parent portal basic functional                              │
│      → WhatsApp integration working                                │
│                                                                     │
│  M3: Integration Complete (Month 8)                                │
│      → RDM integration tested                                      │
│      → EMIS integration tested                                     │
│      → Dashboard kepala madrasah complete                          │
│      → Reporting system functional                                 │
│                                                                     │
│  M4: Pilot Live (Month 9)                                           │
│      → 1-2 MTs using system in production                          │
│      → User training completed                                     │
│      → Support team active                                         │
│      → First user feedback collected                               │
│                                                                     │
│  M5: Enhanced Release (Month 12)                                    │
│      → Tahfiz module complete                                      │
│      → Inklusi module complete                                     │
│      → BK and E-Office modules complete                            │
│      → System stable and scalable                                  │
│                                                                     │
│  M6: Commercial Launch (Month 18)                                   │
│      → Marketing campaign active                                   │
│      → Sales pipeline established                                  │
│      → 50+ schools using system                                    │
│      → Revenue target achieved                                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 9. APPENDIX

### 9.1 Glossary

| Term | Definition |
|------|------------|
| SIMT | Sistem Informasi Manajemen Terpadu |
| MTs | Madrasah Tsanawiyah (SMP Islamic) |
| RBAC | Role-Based Access Control |
| RDM | Rapor Digital Madrasah |
| EMIS | Education Management Information System |
| DAPODIK | Data Pokok Pendidikan |
| PDBK | Pendidikan Dasar Berkelanjutan |
| ABK | Anak Berkebutuhan Khusus |
| GPK | Guru Pendamping Khusus |
| PPI | Program Pembelajaran Individual |
| P5RA | Projek Penguatan Profil Pelajar Rahmatan lil 'Alamin |
| Munaqosah | Ujian hafalan Al-Quran |
| MVP | Minimum Viable Product |
| MAU | Monthly Active Users |
| DAU | Daily Active Users |
| NPS | Net Promoter Score |
| ARPS | Average Revenue Per School |

### 9.2 References

1. KMA 450 Tahun 2023 - Kurikulum Merdeka Madrasah
2. Permendikbud No. 79 Tahun 2014 - DAPODIK
3. KMA tentang EMIS 4.0 - Pengelolaan Data Pendidikan Islam
4. UU No. 27 Tahun 2022 - Perlindungan Data Pribadi
5. Panduan RDM Kemenag
6. Standar Kompetensi Guru

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*