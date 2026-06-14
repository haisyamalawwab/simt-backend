# ANALISIS KEBUTUHAN
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs/YAYASAN

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** DRAFT  
**Penulis:** Tim Proyek SIMT MTs

---

## 1. PENDAHULUAN

### 1.1 Tujuan Dokumen

Dokumen Analisis Kebutuhan ini bertujuan untuk:
- Mengidentifikasi seluruh kebutuhan fungsional dan non-fungsional sistem
- Mendefinisikan user requirements dari perspektif stakeholder
- Menyediakan dasar untuk proses design dan development
- Menjadi acuan dalam proses verifikasi dan validasi sistem

### 1.2 Ruang Lingkup Sistem

```
SCOPE SISTEM YANG AKAN DIBANGUN:
├── Sistem: Web-based School Management Information System
├── Jenjang: Madrasah Tsanawiyah (MTs/SMP Islamic)
├── Multi-tenant: Mendukung yayasan dengan banyak MTs
├── Users: Kepala Madrasah, Guru, TU, Orang Tua, Siswa
├── Platform: Web responsive + Mobile-ready (PWA)
├── Integrasi: EMIS (Kemenag), DAPODIK (Kemendikbud)
└── Coverage: Malang, Jawa Timur (pilot), nasional (future)
```

### 1.3 Metodologi Analisis

```
METODOLOGI YANG DIGUNAKAN:
├── Stakeholder Interview: Wawancara dengan 10+ peran
├── Document Analysis: Analisis dokumen eksisting
├── Process Observation: Observasi proses kerja manual
├── Benchmarking: Perbandingan dengan sistem sejenis
├── Workshop: Joint session dengan tim kurikulum
└── Survey: Kuesioner kebutuhan pengguna
```

---

## 2. IDENTIFIKASI STAKEHOLDER

### 2.1 Matriks Stakeholder

| Stakeholder | Peran | Kepentingan | Kekuatan |
|-------------|-------|-------------|----------|
| **Kepala Madrasah** | Decision Maker | Monitoring seluruh operasional | Otoritas implementasi |
| **Waka Kurikulum** | Process Owner | Kurikulum & akademik | Pengetahuan domain |
| **Waka Kesiswaan** | Process Owner | Kesiswaan & ekskul | Pengetahuan domain |
| **Guru** | End User | Penilaian & administrasi | Pengalaman lapangan |
| **Wali Kelas** | End User | Data siswa & kelas | Koordinasi orang tua |
| **Guru BK** | End User | Konseling & kasus siswa | Pengetahuan psikologi |
| **GPK Inklusi** | End User | Pendampingan ABK | Expertise inklusi |
| **Tahfiz** | End User | Monitoring hafalan | Expertise tahfiz |
| **Tata Usaha (TU)** | End User | Administrasi & keuangan | Data management |
| **Orang Tua** | End User | Info anak & pembayaran | Driver utama |
| **Siswa** | End User | Info akademik & jadwal | End beneficiary |
| **Yayasan** | Sponsor/Owner | Oversight keuangan & mutu | Budget control |

### 2.2 User Stories per Stakeholder

#### A. Kepala Madrasah

```
USER STORY: KEPALA MADRASAH

📋 US-KM-001: Dashboard Eksekutif
Sebagai Kepala Madrasah,
Saya ingin melihat dashboard dengan semua KPI sekolah secara real-time,
Agar saya dapat mengambil keputusan berbasis data dengan cepat.

Acceptance Criteria:
✓ Menampilkan statistik kehadiran siswa (target >90%)
✓ Menampilkan statistik kehadiran guru (target >95%)
✓ Menampilkan rekap nilai per mata pelajaran
✓ Menampilkan status keuangan (pemasukan vs pengeluaran)
✓ Menampilkan progress tahfiz siswa
✓ Menampilkan notifikasi issue yang perlu perhatian
✓ Data ter-update setiap 15 menit

📋 US-KM-002: Monitoring Akademik
Sebagai Kepala Madrasah,
Saya ingin melihat laporan hasil belajar seluruh siswa,
Agar saya dapat mengevaluasi kinerja guru dan sekolah.

📋 US-KM-003: Approval Dokumen
Sebagai Kepala Madrasah,
Saya ingin menandatangani dokumen secara digital,
Agar proses administrasi lebih efisien.

📋 US-KM-004: Laporan Keuangan
Sebagai Kepala Madrasah,
Saya ingin melihat laporan keuangan real-time,
Agar saya dapat monitoring keuangan sekolah.
```

#### B. Waka Kurikulum

```
USER STORY: WAKA KURIKULUM

📋 US-WK-001: Kelola Kurikulum
Sebagai Waka Kurikulum,
Saya ingin mengatur struktur kurikulum dan mata pelajaran,
Agar pembelajaran terstruktur sesuai KMA 450.

Acceptance Criteria:
✓ Input mapping CP (Capaian Pembelajaran)
✓ Input mapping TP (Tujuan Pembelajaran)
✓ Atur alokasi jam per mapel
✓ Support Kurikulum Merdeka (Paket & SKS)

📋 US-KK-002: Monitoring Penilaian
Sebagai Waka Kurikulum,
Saya ingin monitoring progres input nilai guru,
Agar rapor dapat selesai tepat waktu.

Acceptance Criteria:
✓ Dashboard progres input nilai per guru
✓ Notifikasi deadline input nilai
✓ Rekap ketuntasan belajar per kelas/mapel

📋 US-KK-003: Supervisi Modul Ajar
Sebagai Waka Kurikulum,
Saya ingin review dan approve modul ajar guru,
Agar kualitas pembelajaran terjaga.

📋 US-KK-004: Analisis Hasil Belajar
Sebagai Waka Kurikulum,
Saya ingin melihat analisis hasil belajar siswa,
Agar dapat membuat program perbaikan.
```

#### C. Guru

```
USER STORY: GURU

📋 US-G-001: Input Nilai
Sebagai Guru,
Saya ingin input nilai siswa dengan mudah dan cepat,
Agar tidak memakan waktu banyak.

Acceptance Criteria:
✓ Input nilai formatif (harian)
✓ Input nilai sumatif ( PTS, PAS )
✓ Input nilai projek/kokurikuler
✓ Auto-calculate nilai akhir
✓ Auto-generate deskripsi

📋 US-G-002: Kelola Jadwal
Sebagai Guru,
Saya ingin melihat jadwal mengajar saya,
Agar saya dapat persiapan mengajar.

📋 US-G-003: Isi Jurnal Mengajar
Sebagai Guru,
Saya ingin mengisi jurnal mengajar harian,
Agar terdokumentasi dengan baik.

📋 US-G-004: Upload Modul Ajar
Sebagai Guru,
Saya ingin upload dan manage modul ajar,
Agar pembelajaran lebih terstruktur.
```

#### D. Orang Tua

```
USER STORY: ORANG TUA

📋 US-OT-001: Monitoring Anak
Sebagai Orang Tua,
Saya ingin melihat perkembangan akademik anak saya secara real-time,
Agar saya dapat mengawasi dan mendukung belajar anak.

Acceptance Criteria:
✓ Lihat nilai mata pelajaran
✓ Lihat presensi kehadiran
✓ Lihat jadwal pelajaran
✓ Lihat tugas/PR
✓ Lihat catatan guru

📋 US-OT-002: Info Keuangan
Sebagai Orang Tua,
Saya ingin melihat tagihan dan riwayat pembayaran,
Agar tidak miss pembayaran.

Acceptance Criteria:
✓ Lihat tagihan aktif
✓ Lihat riwayat pembayaran
✓ Notifikasi jatuh tempo
✓ Upload bukti transfer

📋 US-OT-003: Komunikasi Guru
Sebagai Orang Tua,
Saya ingin dapat berkonsultasi dengan guru/wali kelas,
Agar dapat mendukung pembelajaran anak di rumah.

📋 US-OT-004: Info Kegiatan
Sebagai Orang Tua,
Saya ingin melihat pengumuman dan jadwal kegiatan sekolah,
Agar saya dapat mengikuti kegiatan sekolah.
```

---

## 3. ANALISIS KEBUTUHAN FUNGSIONAL

### 3.1 Daftar Modul dan Fitur

```
STRUKTUR MODUL SISTEM:

┌─────────────────────────────────────────────────────────────────────┐
│                           SIMT MTs                                  │
├─────────────┬─────────────┬─────────────┬─────────────┬─────────────┤
│ AKADEMIK    │ KESISWAAN   │ KEUANGAN    │ SDM         │ TAHFIZ      │
├─────────────┼─────────────┼─────────────┼─────────────┼─────────────┤
│ • Biodata   │ • Organisasi│ • Tagihan   │ • Biodata   │ • Program   │
│ • Rombel    │ • Ekskul    │ • Bayar     │ • Presensi  │ • Penilaian │
│ • Jadwal    │ • Presensi  │ • Transaksi │ • Beban     │ • Monitoring│
│ • Presensi  │ • Pelanggaran│ • Laporan   │ • Dokumen   │ • Ujian     │
│ • Penilaian │ • Prestasi  │ • Notifikasi│ • Kinerja   │ • Munaqosah │
│ • Rapor     │ • Perizinan │             │ • Dashboard │ • Dashboard │
│ • Modul Ajar│ • Dashboard │             │             │             │
│ • Jurnal    │             │             │             │             │
│ • Bank Soal │             │             │             │             │
└─────────────┴─────────────┴─────────────┴─────────────┴─────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                           SIMT MTs                                  │
├─────────────┬─────────────┬─────────────┬─────────────┬─────────────┤
│ INKLUSI     │ BK          │ PERPUSTAKAAN│ E-OFFICE    │ PORTAL      │
├─────────────┼─────────────┼─────────────┼─────────────┼─────────────┤
│ • Identifikasi│ • Konseling│ • Katalog   │ • Surat M/K │ • Orang Tua │
│ • PPI        │ • Kasus    │ • Pinjam    │ • Disposisi │ • Siswa     │
│ • GPK        │ • Pemanggilan│ • Kembali │ • Kalender  │ • Guru      │
│ • Asesmen    │ • Rujukan   │ • Denda    │ • Cloud     │ • Dashboard │
│ • Dashboard  │ • Tes       │ • Statistik │ • E-sign    │             │
│             │ • Dashboard │ • Dashboard │ • Monitoring│             │
└─────────────┴─────────────┴─────────────┴─────────────┴─────────────┘
```

### 3.2 Kebutuhan Fungsional Detail

#### MODUL AKADEMIK

```
┌─────────────────────────────────────────────────────────────────────┐
│ MODUL AKADEMIK - DETAIL FITUR                                        │
├─────────────────────────────────────────────────────────────────────┤
│ F001: Data Akademik Siswa                                          │
│ ├── F001.01: Input/edit biodata siswa lengkap                      │
│ ├── F001.02: Riwayat kelas (naik/turun/tinggal)                    │
│ ├── F001.03: Riwayat nilai per semester                            │
│ ├── F001.04: Status aktif/nonaktif/alumni                          │
│ ├── F001.05: Data keluarga & kontak orang tua                      │
│ └── F001.06: Import dari EMIS/DAPODIK                              │
│                                                                     │
│ F002: Kelola Rombel & Kelas                                         │
│ ├── F002.01: Setup rombel per tahun ajaran                         │
│ ├── F002.02: Atur composition kelas (wali kelas, siswa)            │
│ ├── F002.03: Proses naik kelas otomatis                            │
│ ├── F002.04: Mutasi siswa masuk/keluar                             │
│ └── F002.05: Laporan rekap kelas                                    │
│                                                                     │
│ F003: Jadwal Pelajaran                                              │
│ ├── F003.01: Setup jam pelajaran & jam ke                          │
│ ├── F003.02: Generate jadwal otomatis                              │
│ ├── F003.03: Penugasan guru per mapel-kelas                        │
│ ├── F003.04: Perubahan jadwal dengan alasan                        │
│ ├── F003.05: View jadwal: harian, mingguan, per guru              │
│ └── F003.06: Export jadwal ke Excel/PDF                            │
│                                                                     │
│ F004: Presensi Pembelajaran                                          │
│ ├── F004.01: Input presensi harian per kelas                       │
│ ├── F004.02: Kategori: Hadir, Izin, Sakit, Alpa                    │
│ ├── F004.03: Input keterlambatan (menit)                           │
│ ├── F004.04: Rekap presensi per siswa, per kelas, per bulan        │
│ ├── F004.05: Presensi dengan QR code / fingerprint                 │
│ └── F004.06: Notifikasi otomatis ke orang tua jika tidak hadir     │
│                                                                     │
│ F005: Penilaian                                                      │
│ ├── F005.01: Input nilai formatif (harian, weekly)                 │
│ ├── F005.02: Input nilai sumatif (PTS, PAS)                        │
│ ├── F005.03: Input nilai projek/kokurikuler (P5RA)                 │
│ ├── F005.04: Input nilai praktik                                   │
│ ├── F005.05: Input SAS/SAT (Sumatif Akhhir Semester)               │
│ ├── F005.06: Auto-generate deskripsi capaian                       │
│ ├── F005.07: Konversi nilai ke skala 1-100 dan deskripsi           │
│ └── F005.08: Remidial & pengayaan                                   │
│                                                                     │
│ F006: E-Rapor                                                        │
│ ├── F006.01: Generate rapor format Kurikulum Merdeka               │
│ ├── F006.02: Deskripsi otomatis per CP                             │
│ ├── F006.03: Integrasi dengan RDM (ekspor/nggak)                   │
│ ├── F006.04: Cetak rapor PDF dengan barcode/watermark              │
│ ├── F006.05: Tanda tangan digital                                   │
│ ├── F006.06: Arsip rapor per semester                              │
│ └── F006.07: Riwayat perubahan nilai (audit trail)                 │
│                                                                     │
│ F007: Modul Ajar                                                     │
│ ├── F007.01: Upload modul ajar (PDF, Word)                         │
│ ├── F007.02: Template standar (Identitas, CP, TP, Asesmen)         │
│ ├── F007.03: Status: Draft, Upload, Revisi, Disetujui              │
│ ├── F007.04: Review dan approve oleh Waka Kurikulum                │
│ ├── F007.05: Repository modul tahun sebelumnya                     │
│ └── F007.06: Integrasi dengan jurnal mengajar                      │
│                                                                     │
│ F008: Jurnal Mengajar                                                │
│ ├── F008.01: Isi jurnal harian per pertemuan                       │
│ ├── F008.02: Pilih modul ajar yang digunakan                       │
│ ├── F008.03: Catatan materi yang dibahas                           │
│ ├── F008.04: Catatan observasi kelas                               │
│ └── F008.05: Monitoring keterlaksanaan oleh Waka                   │
│                                                                     │
│ F009: Bank Soal                                                      │
│ ├── F009.01: Upload soal dengan kategorisasi                       │
│ ├── F009.02: Tipe: PG, Essay, Praktik                              │
│ ├── F009.03: Tag tingkat kesulitan                                 │
│ ├── F009.04: Generate kisi-kisi otomatis                           │
│ └── F009.05: Arsip soal per ujian                                  │
│                                                                     │
│ F010: Dashboard Akademik                                             │
│ ├── F010.01: Statistik nilai per mapel, per kelas                  │
│ ├── F010.02: Grafik ketuntasan belajar                             │
│ ├── F010.03: Perbandingan antar kelas                              │
│ └── F010.04: Trend nilai per semester                              │
└─────────────────────────────────────────────────────────────────────┘
```

#### MODUL KESISWAAN

```
┌─────────────────────────────────────────────────────────────────────┐
│ MODUL KESISWAAN - DETAIL FITUR                                       │
├─────────────────────────────────────────────────────────────────────┤
│ F011: Data Kesiswaan                                                 │
│ ├── F011.01: Kelola organisasi siswa (OSIS, MPK)                   │
│ ├── F011.02: Kelola ekstrakurikuler                                 │
│ ├── F011.03: Riwayat kegiatan siswa                                 │
│ └── F011.04: Portfolio siswa                                        │
│                                                                     │
│ F012: Presensi Kesiswaan                                             │
│ ├── F012.01: Check-in harian                                        │
│ ├── F012.02: Keterlambatan dengan threshold                         │
│ ├── F012.03: Rekap kehadiran siswa                                 │
│ └── F012.04: Laporan harian ke orang tua                            │
│                                                                     │
│ F013: Sistem Poin Pelanggaran                                        │
│ ├── F013.01: Kategori pelanggaran (ringan, sedang, berat)          │
│ ├── F013.02: Input pelanggaran oleh guru/TU                        │
│ ├── F013.03: Konsekuensi otomatis berdasarkan poin                  │
│ ├── F013.04: Riwayat pelanggaran per siswa                         │
│ └── F013.05: Dashboard pelanggaran sekolah                          │
│                                                                     │
│ F014: Sistem Poin Prestasi                                           │
│ ├── F014.01: Input prestasi akademik & non-akademik                 │
│ ├── F014.02: Penghargaan otomatis berdasarkan pencapaian            │
│ └── F014.03: Leaderboard prestasi                                   │
│                                                                     │
│ F015: Perizinan                                                       │
│ ├── F015.01: Request izin (sakit, pulang, kegiatan)                │
│ ├── F015.02: Approval oleh wali kelas/kamad                        │
│ ├── F015.03: Notifikasi ke orang tua                               │
│ └── F015.04: Verifikasi surat dokter                                │
│                                                                     │
│ F016: Data Prestasi & Sertifikat                                     │
│ ├── F016.01: Input data lomba & pencapaian                         │
│ ├── F016.02: Generate sertifikat                                    │
│ └── F016.03: Portfolio prestasi siswa                               │
│                                                                     │
│ F017: Ekstrakurikuler                                                │
│ ├── F017.01: Pendaftaran ekskul                                     │
│ ├── F017.02: Jadwal ekskul                                          │
│ ├── F017.03: Presensi ekskul                                        │
│ ├── F017.04: Penilaian ekskul                                       │
│ └── F017.05: Dokumentasi kegiatan                                  │
└─────────────────────────────────────────────────────────────────────┘
```

#### MODUL KEUANGAN

```
┌─────────────────────────────────────────────────────────────────────┐
│ MODUL KEUANGAN - DETAIL FITUR                                        │
├─────────────────────────────────────────────────────────────────────┤
│ F018: Tagihan Sekolah                                                │
│ ├── F018.01: Setup komponen biaya (SPP, daftar ulang, dll)         │
│ ├── F018.02: Generate tagihan per siswa                             │
│ ├── F018.03: Diskon &奖学金 management                              │
│ ├── F018.04: Tagihan per semester/tahunan                           │
│ └── F018.05: Custom tagihan khusus                                  │
│                                                                     │
│ F019: Pembayaran                                                     │
│ ├── F019.01: Input pembayaran manual                                │
│ ├── F019.02: Upload bukti transfer                                  │
│ ├── F019.03: Validasi pembayaran oleh TU                           │
│ ├── F019.04: Generate virtual account                               │
│ ├── F019.05: Integrasi payment gateway (Midtrans)                   │
│ └── F019.06: Split payment (multiple metode)                        │
│                                                                     │
│ F020: Riwayat Transaksi                                              │
│ ├── F020.01: Rekap pembayaran per siswa                             │
│ ├── F020.02: Laporan tunggakan                                       │
│ ├── F020.03: Aging analysis tunggakan                               │
│ └── F020.04: Ekspor laporan keuangan                                │
│                                                                     │
│ F021: Laporan Keuangan                                                │
│ ├── F021.01: Rekap pemasukan per periode                           │
│ ├── F021.02: Laporan arus kas                                       │
│ ├── F021.03: Buku besar                                             │
│ ├── F021.04: Neraca sekolah                                         │
│ └── F021.05: Laporan bulanan untuk年度报告                           │
│                                                                     │
│ F022: Notifikasi Pembayaran                                          │
│ ├── F022.01: Reminder jatuh tempo via WA                            │
│ ├── F022.02: Konfirmasi pembayaran received                         │
│ └── F022.03: Alert tunggakan                                        │
│                                                                     │
│ F023: Tabungan Siswa                                                 │
│ ├── F023.01: Setor/tarik tabungan                                   │
│ ├── F023.02: Mutasi tabungan                                        │
│ └── F023.03: Laporan tabungan                                        │
└─────────────────────────────────────────────────────────────────────┘
```

#### MODUL TAHFIZ (UNIQUE ISLAMIC)

```
┌─────────────────────────────────────────────────────────────────────┐
│ MODUL TAHFIZ - DETAIL FITUR (UNIQUE)                                │
├─────────────────────────────────────────────────────────────────────┤
│ F024: Program Tahfiz                                                 │
│ ├── F024.01: Target hafalan per semester                            │
│ ├── F024.02: Atur kurikulum tahfiz (Tilawati, Iqro, Quran)         │
│ ├── F024.03: Setup tingkat/juz target                               │
│ └── F024.04: Jadwal kegiatan tahfiz                                 │
│                                                                     │
│ F025: Penilaian Hafalan                                              │
│ ├── F025.01: Input setoran hafalan ( setoran hafalan )             │
│ ├── F025.02: Penilaian kualitas bacaan                              │
│ ├── F025.03: Penilaian tajwid                                       │
│ ├── F025.04: Catatan murajaah (pengulangan)                        │
│ └── F025.05: Nilai ubudiyah (sholat, adab)                          │
│                                                                     │
│ F026: Monitoring Perkembangan                                        │
│ ├── F026.01: Grafik progress hafalan per siswa                      │
│ ├── F026.02: Dashboard konsistensi murajaah                         │
│ ├── F026.03: Capaian target hafalan                                 │
│ └── F026.04: Warning jika target tidak tercapai                    │
│                                                                     │
│ F027: Ujian Tahfiz                                                   │
│ ├── F027.01: Jadwal ujian (tasmik, munaqosah)                       │
│ ├── F027.02: Input nilai ujian                                      │
│ ├── F027.03: Sertifikat kelulusan tahfiz                            │
│ └── F027.04: Integrasi dengan rapor                                 │
│                                                                     │
│ F028: Munaqosah                                                      │
│ ├── F028.01: Pendaftaran munaqosah                                  │
│ ├── F028.02: Jadwal ujian                                          │
│ ├── F028.03: Penilaian juri                                         │
│ ├── F028.04: Generate sertifikat munaqosah                         │
│ └── F028.05: Database hafalan tersimpan                            │
│                                                                     │
│ F029: Tilawati System                                                │
│ ├── F029.01: Tracking level Tilawati                                │
│ ├── F029.02: Penilaian tilawati                                     │
│ └── F029.03: Integrasi dengan progress hafalan                     │
│                                                                     │
│ F030: Dashboard Tahfiz                                               │
│ ├── F030.01: Rekap progress seluruh siswa                          │
│ ├── F030.02: Perbandingan antar kelas                              │
│ └── F030.03: Statistik kelulusan target                            │
└─────────────────────────────────────────────────────────────────────┘
```

#### MODUL INKLUSI (PDBK)

```
┌─────────────────────────────────────────────────────────────────────┐
│ MODUL INKLUSI (PDBK) - DETAIL FITUR                                 │
├─────────────────────────────────────────────────────────────────────┤
│ F031: Identifikasi ABK                                               │
│ ├── F031.01: Screening awal siswa baru                             │
│ ├── F031.02: Kategori kebutuhan khusus                              │
│ ├── F031.03: Upload hasil observasi                                 │
│ ├── F031.04: Rekomendasi asesmen lanjutan                           │
│ └── F031.05: Database siswa inklusi                                 │
│                                                                     │
│ F032: Program Pembelajaran Individual (PPI)                         │
│ ├── F032.01: Penyusunan PPI digital                                 │
│ ├── F032.02: Template PPI standar                                   │
│ ├── F032.03: Target individual                                      │
│ ├── F032.04: Strategi pembelajaran                                  │
│ ├── F032.05: Monitoring capaian PPI                                 │
│ └── F032.06: Workflow approval (GPK → Guru → Wali → Waka)          │
│                                                                     │
│ F033: Pendampingan GPK                                               │
│ ├── F033.01: Jadwal pendampingan                                   │
│ ├── F033.02: Catatan sesi pendampingan                             │
│ ├── F033.03: Strategi pembelajaran yang digunakan                  │
│ ├── F033.04: Dokumentasi hambatan & solusi                         │
│ └── F033.05: Laporan perkembangan                                   │
│                                                                     │
│ F034: Asesmen Psikologis                                             │
│ ├── F034.01: Input hasil tes IQ                                     │
│ ├── F034.02: Upload hasil psikolog                                  │
│ ├── F034.03: Hasil asesmen bakat & minat                           │
│ ├── F034.04: Grafik perkembangan                                    │
│ └── F034.05: Rekomendasi layanan pembelajaran                       │
│                                                                     │
│ F035: Adaptasi Kurikulum                                             │
│ ├── F035.01: Modifikasi tugas                                       │
│ ├── F035.02: Penyesuaian asesmen                                    │
│ ├── F035.03: Akomodasi belajar                                      │
│ └── F035.04: Progress adaptasi                                      │
│                                                                     │
│ F036: Komunikasi Orang Tua                                           │
│ ├── F036.01: Laporan progress mingguan                              │
│ ├── F036.02: Home program                                          │
│ └── F036.03: Jadwal konsultasi                                       │
│                                                                     │
│ F037: Dashboard Inklusi                                              │
│ ├── F037.01: Statistik ABK per jenis kebutuhan                      │
│ ├── F037.02: Progress layanan per siswa                            │
│ └── F037.03: Monitoring GPK workload                                │
└─────────────────────────────────────────────────────────────────────┘
```

#### MODUL BK/KONSELING

```
┌─────────────────────────────────────────────────────────────────────┐
│ MODUL BK/KONSELING - DETAIL FITUR                                   │
├─────────────────────────────────────────────────────────────────────┤
│ F038: Catatan Konseling                                             │
│ ├── F038.01: Daftar cek masalah                                     │
│ ├── F038.02: Riwayat sesi konseling                                │
│ ├── F038.03: Catatan kasus khusus                                  │
│ └── F038.04: Planning & tindak lanjut                               │
│                                                                     │
│ F039: Monitoring Perilaku                                           │
│ ├── F039.01: Tracking perubahan perilaku                           │
│ ├── F039.02: Korelasi dengan pelanggaran                           │
│ └── F039.03: Evaluasi perkembangan                                  │
│                                                                     │
│ F040: Pemanggilan Orang Tua                                         │
│ ├── F040.01: Generate surat pemanggilan                             │
│ ├── F040.02: Tracking history komunikasi                           │
│ └── F040.03: Dokumentasi hasil pertemuan                           │
│                                                                     │
│ F041: Rujukan                                                        │
│ ├── F041.01: Rujukan ke psikolog                                    │
│ ├── F041.02: Rujukan ke profesional lain                           │
│ └── F041.03: Tracking hasil rujukan                                 │
│                                                                     │
│ F042: Alat Tes                                                       │
│ ├── F042.01: Tes bakat minat                                        │
│ ├── F042.02: Tes kepribadian                                       │
│ ├── F042.03: Tes IQ                                                │
│ ├── F042.04: Multiple Intelligence                                  │
│ ├── F042.05: Gaya belajar                                          │
│ └── F042.06: Tes penjurusan                                         │
│                                                                     │
│ F043: Dashboard BK                                                   │
│ ├── F043.01: Statistik kasus aktif                                 │
│ ├── F043.02: Heatmap masalah                                       │
│ └── F043.03: Monitoring siswa berbobot tinggi                      │
└─────────────────────────────────────────────────────────────────────┘
```

#### MODUL E-OFFICE

```
┌─────────────────────────────────────────────────────────────────────┐
│ MODUL E-OFFICE - DETAIL FITUR                                       │
├─────────────────────────────────────────────────────────────────────┤
│ F044: Surat Masuk & Keluar                                          │
│ ├── F044.01: Input & scan surat masuk                              │
│ ├── F044.02: Generate surat keluar                                  │
│ ├── F044.03: Penomoran surat otomatis                              │
│ ├── F044.04: Disposisi surat                                       │
│ └── F044.05: Tracking status surat                                  │
│                                                                     │
│ F045: Disposisi Digital                                              │
│ ├── F045.01: Instruksi dari kepala madrasah                        │
│ ├── F045.02: Penugasan tindak lanjut                                │
│ ├── F045.03: Deadline pekerjaan                                    │
│ └── F045.04: Monitoring progres disposisi                          │
│                                                                     │
│ F046: Kalender & Agenda                                              │
│ ├── F046.01: Kalender akademik                                       │
│ ├── F046.02: Agenda pimpinan                                       │
│ ├── F046.03: Jadwal supervisi                                       │
│ └── F046.04: Reminder otomatis                                      │
│                                                                     │
│ F047: E-Signature                                                    │
│ ├── F047.01: Tanda tangan digital kepala madrasah                  │
│ ├── F047.02: Validasi dokumen                                       │
│ ├── F047.03: QR verification                                       │
│ └── F047.04: Log tanda tangan                                       │
│                                                                     │
│ F048: Cloud Storage                                                  │
│ ├── F048.01: Penyimpanan dokumen terpusat                          │
│ ├── F048.02: Folder per bidang (Kurikulum, Kesiswaan, dll)        │
│ ├── F048.03: Hak akses folder                                      │
│ └── F048.04: Versioning file                                        │
│                                                                     │
│ F049: Arsip Akreditasi                                               │
│ ├── F049.01: Upload dokumen akreditasi                             │
│ ├── F049.02: Mapping per instrumen                                 │
│ └── F049.03: Tracking status kesiapan akreditasi                   │
│                                                                     │
│ F050: Monitoring Program Kerja                                       │
│ ├── F050.01: Input program kerja per bidang                        │
│ ├── F050.02: Timeline & milestone                                  │
│ └── F050.03: Progress report                                       │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.3 Kebutuhan Non-Fungsional

```
┌─────────────────────────────────────────────────────────────────────┐
│ KEBUTUHAN NON-FUNGSIONAL                                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ A. PERFORMANCE                                                       │
│ ├── Response time < 2 detik untuk operasi normal                   │
│ ├── Response time < 5 detik untuk report generation                │
│ ├── Support 500+ concurrent users per instance                     │
│ ├── Page load < 3 detik (P95)                                      │
│ └── Database query < 500ms (P95)                                   │
│                                                                     │
│ B. AVAILABILITY                                                      │
│ ├── Uptime: 99.5% (excluded scheduled maintenance)                 │
│ ├── Scheduled maintenance: max 4 hours/month                       │
│ ├── Backup: daily incremental, weekly full                         │
│ └── Disaster recovery: RTO < 4 hours, RPO < 1 hour                 │
│                                                                     │
│ C. SECURITY                                                          │
│ ├── SSL/TLS untuk semua koneksi                                     │
│ ├── Enkripsi AES-256 untuk data sensitif at-rest                   │
│ ├── JWT authentication dengan refresh token                         │
│ ├── Password policy: min 8 chars, alphanumeric, force change 90 days│
│ ├── Session timeout: 30 minutes inactivity                         │
│ ├── Rate limiting: 100 req/min per IP                              │
│ ├── Audit log untuk semua operasi write                            │
│ ├── Penetration testing: annual                                    │
│ └── Compliance: UU PDP, Permendikbud, KMA                          │
│                                                                     │
│ D. SCALABILITY                                                       │
│ ├── Horizontal scaling untuk web tier                              │
│ ├── Database replication untuk read scaling                        │
│ ├── CDN integration untuk static assets                            │
│ └── Support multi-tenant architecture                              │
│                                                                     │
│ E. COMPATIBILITY                                                     │
│ ├── Browser: Chrome, Firefox, Safari, Edge (latest 2 versions)     │
│ ├── Mobile: iOS 14+, Android 10+                                   │
│ ├── Screen: Responsive (desktop, tablet, mobile)                   │
│ └── API: RESTful, JSON, standard HTTP methods                       │
│                                                                     │
│ F. USABILITY                                                         │
│ ├── UX Konsisten seluruh aplikasi                                  │
│ ├── User-friendly interface (Non-IT user)                          │
│ ├── Accessibility: WCAG 2.1 AA                                     │
│ ├── Multi-language ready (Bahasa Indonesia default)                │
│ └── Onboarding & help system                                       │
│                                                                     │
│ G. MAINTAINABILITY                                                   │
│ ├── Modular architecture                                            │
│ ├── API documentation (OpenAPI/Swagger)                            │
│ ├── Code quality: ESLint, PHPStan, automated testing               │
│ ├── CI/CD pipeline                                                  │
│ └── Technical documentation lengkap                                │
│                                                                     │
│ H. RELIABILITY                                                       │
│ ├── Error rate < 0.1%                                               │
│ ├── Graceful degradation jika service down                         │
│ ├── Data integrity validation                                       │
│ └── Transaction rollback capability                                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 4. ANALISIS KEBUTUHAN INTEGRASI

### 4.1 Integrasi Wajib (Regulasi)

```
┌─────────────────────────────────────────────────────────────────────┐
│ INTEGRASI WAJIB (REGULASI)                                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ 1. INTEGRASI DAPODIK (Kemendikdasmen)                               │
│    ├── Direction: Two-way sync (baca & tulis where allowed)        │
│    ├── Entitas: Siswa, Guru, Kelas, Sekolah                        │
│    ├── Data: NISN, NPSN, biodata, mutasi                           │
│    ├── API: Dapodik API (if available) / File-based import         │
│    └── Criticality: WAJIB - Legalitas data pendidikan              │
│                                                                     │
│ 2. INTEGRASI EMIS (Kemenag)                                         │
│    ├── Direction: One-way (sinkronisasi dari EMIS)                 │
│    ├── Entitas: Sekolah, Siswa, PTK, Sarana                        │
│    ├── Data: Data pokok, NSM/NPSN                                  │
│    ├── API: EMIS API                                               │
│    └── Criticality: WAJIB - Pelaporan wajib                        │
│                                                                     │
│ 3. INTEGRASI RDM (Rapor Digital Madrasah)                           │
│    ├── Direction: One-way export / Import                           │
│    ├── Entitas: Nilai, Rapor                                       │
│    ├── Data: Format RDM (JSON/Excel)                               │
│    ├── Method: Export nilai ke RDM import format                    │
│    └── Criticality: WAJIB - Pengisian rapor resmi                  │
│                                                                     │
│ 4. INTEGRASI SIMPATIKA (Kemenag)                                    │
│    ├── Direction: Read-only                                        │
│    ├── Entitas: PTK, Sertifikasi                                   │
│    └── Criticality: Medium - Info guru                             │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.2 Integrasi Opsional

```
┌─────────────────────────────────────────────────────────────────────┐
│ INTEGRASI OPSIONAL                                                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ 1. WHATSAPP BUSINESS API                                            │
│    ├── Provider: Green API / Fonnte                                │
│    ├── Usage: Notifikasi kehadiran, nilai, keuangan                │
│    └── Criticality: HIGH - Engagement orang tua                     │
│                                                                     │
│ 2. PAYMENT GATEWAY                                                  │
│    ├── Provider: Midtrans / Xendit                                 │
│    ├── Usage: Pembayaran SPP, tagihan                              │
│    └── Criticality: Medium - Kemudahan pembayaran                  │
│                                                                     │
│ 3. SMS GATEWAY                                                      │
│    ├── Provider: Twilio / Gammu                                    │
│    ├── Usage: OTP, notifikasi backup                               │
│    └── Criticality: Low - Backup notifikasi                        │
│                                                                     │
│ 4. CLOUD STORAGE                                                    │
│    ├── Provider: AWS S3 / Scaleway                                  │
│    ├── Usage: Penyimpanan file, backup                             │
│    └── Criticality: Medium - Data safety                           │
│                                                                     │
│ 5. EMAIL SERVICE                                                    │
│    ├── Provider: SendGrid / AWS SES                                │
│    ├── Usage: Notifikasi, laporan                                  │
│    └── Criticality: Low - Alternative channel                      │
│                                                                     │
│ 6. FINGERPRINT DEVICE                                               │
│    ├── Provider: ZKTeco / Hikvision                                │
│    ├── Usage: Presensi guru & siswa                                │
│    └── Criticality: Low - Optional enhancement                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 5. ANALISIS KEBUTUHAN REPORTING

### 5.1 Daftar Laporan yang Dibutuhkan

```
┌─────────────────────────────────────────────────────────────────────┐
│ DAFTAR LAPORAN                                                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ A. LAPORAN AKADEMIK                                                 │
│ ├── L01: Rekap nilai per kelas per mapel                           │
│ ├── L02: Legger nilai                                              │
│ ├── L03: Distribusi nilai (chart)                                  │
│ ├── L04: Ketuntasan belajar per CP                                 │
│ ├── L05: Analisis butir soal                                       │
│ ├── L06: Progress input nilai (dashboard)                          │
│ └── L07: Ranking siswa                                             │
│                                                                     │
│ B. LAPORAN KESISWAAN                                                │
│ ├── L08: Rekap kehadiran siswa (harian, bulanan)                   │
│ ├── L09: Laporan keterlambatan                                     │
│ ├── L10: Rekap pelanggaran                                         │
│ ├── L11: Rekap prestasi                                            │
│ ├── L12: Laporan ekskul                                            │
│ └── L13: Biodata lengkap siswa                                     │
│                                                                     │
│ C. LAPORAN KEUANGAN                                                 │
│ ├── L14: Laporan pemasukan per periode                             │
│ ├── L15: Laporan pengeluaran                                       │
│ ├── L16: Neraca sekolah                                            │
│ ├── L17: Laporan piutang/tunggakan                                 │
│ ├── L18: Buku besar                                                │
│ ├── L19: Laporan setor bank                                        │
│ └── L20: Tagihan per siswa                                         │
│                                                                     │
│ D. LAPORAN TAHFIZ                                                   │
│ ├── L21: Progress hafalan per siswa                                │
│ ├── L22: Rekap setoran hafalan                                     │
│ ├── L23: Laporan munaqosah                                         │
│ ├── L24: Statistik tingkat tilawati                                │
│ └── L25: Target vs actual hafalan                                  │
│                                                                     │
│ E. LAPORAN INKLUSI                                                  │
│ ├── L26: Daftar siswa ABK                                          │
│ ├── L27: Progress PPI per siswa                                   │
│ ├── L28: Rekap hasil asesmen                                       │
│ └── L29: Laporan GPK                                               │
│                                                                     │
│ F. LAPORAN BK                                                       │
│ ├── L30: Daftar kasus konseling                                    │
│ ├── L31: Statistik masalah                                         │
│ ├── L32: Laporan pemanggilan orang tua                             │
│ └── L33: Hasil tes psikologis                                      │
│                                                                     │
│ G. LAPORAN GURU/SDM                                                 │
│ ├── L34: Beban mengajar per guru                                   │
│ ├── L35: Rekap presensi guru                                       │
│ ├── L36: Evaluasi kinerja                                          │
│ └── L37: Daftar riwayat pelatihan                                  │
│                                                                     │
│ H. LAPORAN MANAJEMEN                                                │
│ ├── L38: Dashboard KPI sekolah                                     │
│ ├── L39: Laporan bulanan kepala madrasah                            │
│ ├── L40: Comparison antar kelas/sekolah                            │
│ └── L41: Trend data tahunan                                        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 6. ANALISIS PRIORITAS KEBUTUHAN

### 6.1 Matriks Prioritas MoSCoW

```
┌─────────────────────────────────────────────────────────────────────┐
│                           MOISCOOW MATRIX                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ MUST HAVE (MVP - Wajib Tahun 1)                                     │
│ ├── F001: Biodata siswa lengkap                                    │
│ ├── F002: Kelola rombel                                            │
│ ├── F003: Jadwal pelajaran                                         │
│ ├── F004: Presensi harian                                          │
│ ├── F005: Penilaian (formatif, sumatif)                            │
│ ├── F006: E-Rapor (integrasi RDM)                                  │
│ ├── F018: Tagihan & pembayaran                                     │
│ ├── F019: Konfirmasi pembayaran                                    │
│ ├── F038: Catatan konseling basic                                  │
│ ├── User Management (authentication, RBAC)                         │
│ ├── Dashboard kepala madrasah                                       │
│ └── Portal orang tua basic                                          │
│                                                                     │
│ SHOULD HAVE (Enhancement - Tahun 1-2)                               │
│ ├── F007: Modul ajar                                               │
│ ├── F008: Jurnal mengajar                                          │
│ ├── F013: Sistem poin pelanggaran                                  │
│ ├── F024-030: Modul Tahfiz                                          │
│ ├── F031-037: Modul Inklusi                                         │
│ ├── F044-050: Modul E-Office                                       │
│ ├── WhatsApp notification                                          │
│ └── User dashboard per role                                         │
│                                                                     │
│ COULD HAVE (Optional - Tahun 2+)                                    │
│ ├── F009: Bank soal                                                │
│ ├── F016: Sertifikat digital                                        │
│ ├── F023: Tabungan siswa                                           │
│ ├── F042: Alat tes BK                                              │
│ ├── Mobile app (iOS, Android)                                      │
│ └── Advanced analytics                                             │
│                                                                     │
│ WON'T HAVE (Out of Scope)                                           │
│ ├── E-learning/LMS integration                                     │
│ ├── Online exam dengan proctoring                                   │
│ ├── Financial accounting lengkap (gl, ap, ar)                      │
│ ├── HR payroll system                                              │
│ └── Inventory management untuk non-sekolah                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 6.2 Prioritas berdasarkan User Value

```
┌─────────────────────────────────────────────────────────────────────┐
│ PRIORITAS BERDASARKAN USER VALUE                                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ HIGH VALUE + LOW EFFORT (Quick Wins)                                │
│ ├── Dashboard real-time untuk kepala madrasah                      │
│ ├── Notifikasi WhatsApp untuk orang tua                            │
│ ├── Presensi digital dengan QR code                                │
│ ├── Rekap nilai otomatis untuk guru                                │
│ └── Status tagihan untuk orang tua                                 │
│                                                                     │
│ HIGH VALUE + HIGH EFFORT (Strategic)                                │
│ ├── Modul Tahfiz (diferensiasi pasar)                              │
│ ├── Modul Inklusi (compliance regulasi)                            │
│ ├── E-Rapor terintegrasi RDM                                       │
│ ├── Multi-tenant untuk yayasan                                     │
│ └── Mobile app                                                      │
│                                                                     │
│ LOW VALUE + LOW EFFORT (Nice to Have)                               │
│ ├── Calendar integration                                           │
│ ├── Dark mode                                                       │
│ ├── Export to Excel                                                │
│ └── Dark mode                                                       │
│                                                                     │
│ LOW VALUE + HIGH EFFORT (Deprioritize)                              │
│ ├── Complex AI/ML features                                         │
│ ├── Blockchain untuk sertifikat                                    │
│ └── VR/AR untuk pembelajaran                                        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 7. ASUMSI DAN DEPENDENSI

### 7.1 Asumsi

```
┌─────────────────────────────────────────────────────────────────────┐
│                           ASUMSI                                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ ASUMSI TEKNIS:                                                      │
│ ├── A1: Sekolah memiliki akses internet stabil                     │
│ ├── A2: Sekolah memiliki minimal 1 komputer untuk operator         │
│ ├── A3: Database EMIS dan Dapodik accessible via API               │
│ └── A4: Users memiliki device (HP/laptop) untuk akses              │
│                                                                     │
│ ASUMSI OPERASIONAL:                                                 │
│ ├── A5: Sekolah siap menyediakan data akses EMIS/Dapodik           │
│ ├── A6: Ada dedicated person untuk operator sistem                 │
│ ├── A7: Guru bersedia training untuk penggunaan sistem             │
│ └── A8: Orang tua memiliki smartphone dan WhatsApp                  │
│                                                                     │
│ ASUMSI EKONOMI:                                                      │
│ ├── A9: Sekolah/yayasan memiliki budget untuk subscription         │
│ └── A10: ROI dapat diukur dalam 12-18 bulan                        │
│                                                                     │
│ ASUMSI REGULASI:                                                     │
│ ├── A11: Regulasi DAPODIK dan EMIS tetap seperti saat ini          │
│ ├── A12: Format rapor RDM tidak berubah drastis                    │
│ └── A13: UU PDP mulai diterapkan danpatuhi                          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 7.2 Dependensi

```
┌─────────────────────────────────────────────────────────────────────┐
│                          DEPENDENSI                                 │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ EXTERNAL DEPENDENCIES:                                              │
│ ├── D1: Ketersediaan API EMIS untuk integrasi                     │
│ ├── D2: Ketersediaan API DAPODIK untuk integrasi                  │
│ ├── D3: Format data RDM untuk export/import                       │
│ ├── D4: Akun EMIS untuk setiap madrasah                           │
│ └── D5: Koneksi internet provider di lokasi sekolah                │
│                                                                     │
│ INTERNAL DEPENDENCIES:                                              │
│ ├── D6: Wireframe UI/UX selesai sebelum development                │
│ ├── D7: Database schema disetujui sebelum coding                   │
│ ├── D8: Definisi RBAC disepakai sebelum coding                    │
│ └── D9: Test environment ready sebelum UAT                        │
│                                                                     │
│ PROJECT DEPENDENCIES:                                               │
│ ├── D10: Tim development lengkap sebelum sprint 1                  │
│ ├── D11: Server/staging environment ready sebelum development       │
│ └── D12: Kontribusi stakeholder untuk feedback                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 8. KESIMPULAN

### 8.1 Ringkasan Kebutuhan

```
┌─────────────────────────────────────────────────────────────────────┐
│              RINGKASAN ANALISIS KEBUTUHAN                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ TOTAL MODUL:           13 modul                                     │
│ TOTAL FITUR:           200+ fitur                                  │
│ TOTAL USER STORIES:    50+ user stories                             │
│                                                                     │
│ MUST HAVE (MVP):       ~40 fitur core                              │
│ SHOULD HAVE:           ~80 fitur                                   │
│ COULD HAVE:            ~80 fitur                                   │
│                                                                     │
│ STAKEHOLDER GROUPS:    11 groups                                   │
│ INTEGRATION WAJIB:     4 systems (EMIS, Dapodik, RDM, Simpatika)   │
│ INTEGRATION OPSIONAL:  6 systems (WA, Payment, SMS, dll)           │
│                                                                     │
│ PRIORITY:              MVP focus pada Akademik, Keuangan,           │
│                       Portal Orang Tua, Dashboard                   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Validasi Kebutuhan

```
NEXT STEPS:
├── 1. Review dokumen dengan stakeholder kunci
├── 2. Validasi prioritas dengan kepala madrasah
├── 3. Finalisasi list fitur MVP
├── 4. Konfirmasi asumsi dan dependensi
└── 5. Sign-off untuk proceed ke design phase
```

---

## LAMPIRAN

### A. Detail User Stories

(Dokumen terpisah untuk setiap stakeholder)

### B. Matriks Traceability

| Feature | User Story | Priority | Sprint |
|---------|------------|----------|--------|
| F001 | US-KM-001, US-G-001 | Must | Sprint 2 |
| F005 | US-G-001, US-WK-002 | Must | Sprint 3 |
| ... | ... | ... | ... |

### C. Glossary

Lihat glossary pada dokumen terpisah.

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*