# 📊 LAPORAN SURVEY & ANALISIS MARKET CHECK
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs/MADRASAH

**Tanggal Analisis:** 12 Juni 2026  
**Objek:** Rancangan Fitur SIMT MTs berbasis web untuk MTs/Yayasan  
**Konteks:** Keterkaitan dengan DAPODIK Kemendikdasmen dan EMIS Kemenag

---

## BAGIAN 1: ANALISIS DOKUMEN RANCANGAN

### 1.1 Pemetaan Kebutuhan Fitur

Berdasarkan dokumen rancangan yang dianalisis, teridentifikasi **13 modul utama** dengan total **200+ fitur** yang diklasifikasikan sebagai berikut:

| Kategori | Modul | Complexity | User Roles | External Integration |
|----------|-------|-----------|------------|----------------------|
| **CRITICAL** | Akademik/Kurikulum | ⭐⭐⭐⭐⭐ | 8 roles | **RDM Kemenag, EMIS, Dapodik** |
| **HIGH** | Kesiswaan | ⭐⭐⭐⭐ | 6 roles | **EMIS, Dapodik** |
| **HIGH** | Keuangan | ⭐⭐⭐⭐ | 5 roles | - |
| **HIGH** | Dashboard Orang Tua | ⭐⭐⭐⭐ | 3 roles | **WhatsApp API** |
| **HIGH** | Notifikasi WA | ⭐⭐⭐ | Multi | **WhatsApp Business API** |
| **MEDIUM** | SDM/Kepegawaian | ⭐⭐⭐ | 5 roles | **EMIS, Simpatika** |
| **MEDIUM** | E-Office/Pimpinan | ⭐⭐⭐ | 4 roles | - |
| **MEDIUM** | Inklusi (PDBK) | ⭐⭐⭐ | 5 roles | **EMIS** |
| **MEDIUM** | BK/Konseling | ⭐⭐⭐ | 4 roles | - |
| **MEDIUM** | Tahfiz | ⭐⭐⭐ | 5 roles | **UNIQUE ISLAMIC** |
| **LOW** | Perpustakaan | ⭐⭐ | 4 roles | - |
| **LOW** | Sarana Prasarana | ⭐⭐ | 4 roles | - |
| **LOW** | Ekstrakurikuler | ⭐⭐ | 4 roles | - |

### 1.2 User Roles yang Dilayani

```
PENGGUNA SISTEM:
├── Kepala Madrasah
├── Wakil Kepala (Waka Kurikulum, Waka Kesiswaan, dll)
├── Guru
├── Wali Kelas
├── Guru BK (Bimbingan Konseling)
├── GPK Inklusi (Guru Pendamping Khusus)
├── Tahfiz (Pembina Tahfiz)
├── Tata Usaha (TU)
├── Orang Tua/Wali Murid
└── Siswa
```

### 1.3 Fitur UNIQUE yang WAJIB untuk MTs/Madrasah

```markdown
✦ Modul Tahfiz (Khas Islamic)
   - Tahfidzul Quran monitoring & tracking
   - Mutaba'ah Ibadah daily
   - Tilawati System
   - Munaqosah certification

✦ Modul Inklusi (PDBK - Pendidikan Dasar Berkelanjutan)
   - Program Pembelajaran Individual (PPI)
   - Identifikasi ABK ( Anak Berkebutuhan Khusus )
   - GPK Integration
   - Asesmen Psikologis

✦ Modul P5RA
   - Projek Penguatan Profil Pelajar Rahmatan lil 'Alamin
   - Kurikulum Merdeka Madrasah

✦ Integrasi EMIS-Dapodik
   - Sinkronisasi data siswa
   - Validasi NIK (Dukcapil)
   - NISN/NPSN verification
```

---

## BAGIAN 2: KLASIFIKASI PRODUK DI GITHUB

### 2.1 Proyek Open Source Indonesia

| No | Project | Tech Stack | Status | Kompatibilitas MTs | Link |
|----|---------|------------|--------|-------------------|------|
| 1 | **SISFOKOL** | PHP + MySQL + jQuery | Active (2019) | ❌ SD/SMP/SMA/SMK | [GitHub](https://github.com/hajirodeon/SISFOKOL) |
| 2 | **SISFOKOL-SMP** | PHP + MySQL | Archive | ❌ SMP Umum | [GitHub](https://github.com/hajirodeon/SISFOKOL_SLTP_v1.0) |
| 3 | **SIMS DigiSchool** | CodeIgniter 3 | Active | ❌ General School | [GitHub](https://github.com/FirmanKurnialloh/SIMS-DigiSchool-CI3) |
| 4 | **ASIMSE 4.0 SD** | PHP | Active | ❌ SD | [GitHub](https://github.com/anak-desa1/ASIMSE-4.0-SD) |
| 5 | **SchoolHUB** | Full Stack | Active | ❌ General | [GitHub](https://github.com/mrandika/schoolhub) |
| 6 | **Tuhfah (TPA/TPQ)** | Laravel 11 + Svelte | Active | ⚠️ TPQ Only | [GitHub](https://github.com/404NotFoundIndonesia/tuhfah-webapp) |
| 7 | **Raktrek (Perpustakaan)** | Full Stack | Active | ❌ General | [GitHub](https://github.com/404NotFoundIndonesia/raktrek) |

### 2.2 Kondisi SISFOKOL (Sebagai Referensi)

```
Stars: 13
Forks: 5
Last Commit: 7 tahun lalu (Desember 2019)
Teknologi: PHP Native + MySQL (outdated)
Kesimpulan: Tidak direkomendasikan untuk projekt baru
```

### 2.3 Critical Gap Analysis GitHub

```
❌ TIDAK ADA proyek open source yang fokus untuk MTs/Madrasah
❌ TIDAK ADA modul Tahfiz yang mature di GitHub
❌ TIDAK ADA modul Inklusi/PDBK di repository manapun
❌ SISFOKOL terakhir update 2019 (teknologi outdated)
❌ Tuhfah hanya untuk TPA/TPQ, bukan MTs
```

---

## BAGIAN 3: KLASIFIKASI PRODUK DI ENVATO/CODECANYON

### 3.1 Produk Internasional dengan Fitur School Management

| No | Produk | Harga | Rating | Review | Platform | Kelebihan | Kekurangan |
|----|--------|-------|--------|--------|----------|-----------|-------------|
| 1 | **Smart School by QDOCS** | $59 | ⭐4.68 | 582 | CodeIgniter | 40+ modules, 8 user roles, Android app, biometric | ❌ Tidak ada integrasi EMIS/Dapodik, fokus internasional, tidak ada fitur Islamic-specific |
| 2 | **Ekattor School Management** | $50 | ⭐4.34 | 275 | CodeIgniter | Complete features, transport module, live location | ❌ Tidak support Kurikulum Merdeka, tidak ada fitur Tahfiz |
| 3 | **Fedena** (Open Source) | Free | - | - | Ruby on Rails | Government Kerala uses it | ❌ Tidak ada versi Indonesia, teknologi berbeda |
| 4 | **Odoo Education Module** | $27 | - | - | Odoo 15-18 | Terintegrasi ERP lengkap | ❌ Complex, expensive hosting, tidak ada fitur MTs |
| 5 | **Skoolia** | $99/bulan | ⭐4.9 | 1247 | SaaS | Modern UI, complete | ❌ Subscription, tidak bisa self-host, tidak ada fitur MTs |

### 3.2 Critical Gap Analysis Envato

```
❌ SEMUA produk internasional TIDAK memiliki:
   - Modul Tahfiz / Tahfidzul Quran
   - Modul Inklusi (PDBK) sesuai regulasi Kemenag
   - Integrasi EMIS / Dapodik
   - Fitur P5RA (Profil Pelajar Rahmatan lil 'Alamin)
   - Kurikulum Merdeka Madrasah compatibility
   - Deskripsi capaiian otomatis sesuai format KMS
   
❌ Bahasa dan format penilaian Indonesia

❌ Tidak ada yang support Islamic-specific features

✅ Produk internasional BISA jadi referensi UI/UX dan base code
   untuk modul GENERAL (keuangan, presensi, perpustakaan)
```

---

## BAGIAN 4: KLASIFIKASI PRODUK DI PASAR INDONESIA

### 4.1 Produk Resmi Kemendikbud & Kemenag (GRATIS)

| No | Produk | Jenjang | Status | Fitur | Integrasi |
|----|--------|---------|--------|-------|-----------|
| 1 | **RDM (Rapor Digital Madrasah)** | RA, MI, MTs, MA, MAK | ✅ Active | Nilai harian, PTS, PAS, rapor, P5RA | ✅ EMIS |
| 2 | **ARD (Aplikasi Rapor Digital)** | Semua Madrasah | ⚠️ Deprecated | Nilai, rapor | ✅ EMIS |
| 3 | **EMIS 4.0** | Semua jenjang Islam | ✅ Active | Data pokok pendidikan | ✅ Dapodik, PDUM |
| 4 | **E-Learning Madrasah** | Semua Madrasah | ✅ Active | LMS, konten digital | - |
| 5 | **SIMPATIKA** | PTK Madrasah | ✅ Active | Sertifikasi, tunjangan | ✅ EMIS |
| 6 | **DAPODIK** | SD-SMA/SMK | ✅ Active | Data pokok pendidikan | ✅ EMIS |

### 4.2 Detail RDM (Rapor Digital Madrasah)

**KELEBIHAN:**
- FREE - tidak dipungut biaya
- Wajib digunakan untuk kelulusan & ijazah
- Integrasi EMIS untuk validasi data
- Support Kurikulum Merdeka (Paket & SKS)
- P5RA Feature
- Backup otomatis
- Barcode & watermark pada rapor
- Multi-platform (web + mobile)
- 52.000+ madrasah sudah menggunakan

**KETERBATASAN:**
- HANYA rapor, bukan sistem terintegrasi penuh
- Tidak ada modul keuangan
- Tidak ada modul Tahfiz
- Tidak ada modul Inklusi lengkap
- Tidak ada portal orang tua yang advanced
- Tidak ada modul BK yang comprehensive
- Tidak ada e-office untuk kepala madrasah
- Tidak ada dashboard analytics yang advanced

### 4.3 Vendor Komersial Indonesia

| No | Vendor/Produk | Lokasi | Spesialisasi | Teknologi | Harga | Fitur Islamic | Link |
|----|---------------|--------|--------------|-----------|-------|-------------|------|
| 1 | **AKS-SIMAKOM 6** | Online | Keuangan Sekolah | PHP 8.2 | Comercial | ❌ | [e-simas.com](https://e-simas.com/) |
| 2 | **SIAKAD (MuaraWeb)** | Indonesia | Akademik umum | PHP Framework | Comercial | ❌ | [muaraweb.com](https://www.muaraweb.com/) |
| 3 | **Argalyta** | Indonesia | SIAKAD Sekolah | Web-based | Comercial | ❌ | [argalyta.com](https://argalyta.com/jasa-pembuatan-website-sistem-informasi-akademik/) |
| 4 | **Rufinka** | Indonesia | School Management | Various | Comercial | ❌ | [rufinka.net](https://rufinka.net/) |
| 5 | **Sekolah.mu** | Jakarta | K12 LMS | Cloud SaaS | Subscription | ❌ | - |
| 6 | **Sevima** | Bandung | University ERP | Cloud SaaS | Subscription | ❌ | - |
| 7 | **Simak.online** | Indonesia | School Management | Cloud SaaS | Subscription | ❌ | - |
| 8 | **Odoo Indonesian Module** | Semarang | School on Odoo | Odoo 15-18 | $27/module | ❌ | [apps.odoo.com](https://apps.odoo.com/apps/modules/15.0/skl_sekolah_base) |

### 4.4 Produk/Template WordPress Indonesia

| No | Produk | Fungsi | Harga | Cocok Untuk |
|----|--------|--------|-------|-------------|
| 1 | **Akademia Theme** | Website Sekolah | Rp375.000 | Website profile sekolah |
| 2 | **Theme WordPress Sekolah** | Website sekolah | Various | SD, SMP, SMA |

### 4.5 Marketplace Lokal Indonesia

| Marketplace | Jenis Produk | Keterangan |
|-------------|--------------|------------|
| **ThemeForest Indonesia** | Themes WP | Template website sekolah |
| **Envato Market** | Scripts PHP | Smart School, Ekattor |
| **Tokopedia/Bukalapak** | Aplikasi siap pakai | Various |
| **GitHub Indonesia** | Open source | SISFOKOL, dll |

---

## BAGIAN 5: ANALISIS GAP (KEKOSONGAN PASAR)

### 5.1 Critical Gaps - Peluang Emas

```
┌─────────────────────────────────────────────────────────────────────────┐
│  GAP #1: TIDAK ADA sistem terintegrasi FULL untuk MTs/Madrasah         │
│  → RDM hanya rapor, SISFOKOL outdated, vendor komersial tidak Islamic  │
│  → PELUANG: Sistem SIMT MTs Terintegrasi dengan modul Tahfiz & Inklusi │
├─────────────────────────────────────────────────────────────────────────┤
│  GAP #2: MODUL TAHFIZ yang professional                                 │
│  → Tidak ada produk komersial yang punya fitur Tahfidz yang mature     │
│  → PELUANG: Modul monitoring hafalan, munaqosah, tilawati              │
├─────────────────────────────────────────────────────────────────────────┤
│  GAP #3: MODUL INKLUSI (PDBK) sesuai regulasi Kemendikdasmen           │
│  → Tidak ada sistem yang punya fitur PPI, GPK integration, asesmen ABK │
│  → PELUANG: Modul inklusi komprehensif dengan standar Kemendikdasmen   │
├─────────────────────────────────────────────────────────────────────────┤
│  GAP #4: INTEGRASI EMIS-DAPODIK yang real-time                          │
│  → Belum ada sistem swasta yang bisa sync real-time dengan EMIS        │
│  → PELUANG: API integration layer untuk sinkronisasi data              │
├─────────────────────────────────────────────────────────────────────────┤
│  GAP #5: PORTAL ORANG TUA yang advanced dengan WhatsApp integration     │
│  → RDM memiliki portal terbatas, vendor lain tidak ada WA integration  │
│  → PELUANG: Dashboard orang tua + auto-notifikasi WA                   │
├─────────────────────────────────────────────────────────────────────────┤
│  GAP #6: E-OFFICE untuk Kepala Madrasah                                 │
│  → Tidak ada produk yang fokus ke kebutuhan pimpinan madrasah          │
│  → PELUANG: Digital signature, cloud storage, disposisi digital        │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 6: MATRIKS PERBANDINGAN FITUR

| Fitur | RDM | SISFOKOL | Smart School (Codecanyon) | Vendor Lokal | **SIMT MTs (Needed)** |
|-------|-----|----------|--------------------------|--------------|----------------------|
| **Akademik** | | | | | |
| Biodata Siswa | ❌ | ✅ | ✅ | ✅ | ✅ |
| Rombel & Kelas | ✅ | ✅ | ✅ | ✅ | ✅ |
| Jadwal Pelajaran | ❌ | ✅ | ✅ | ✅ | ✅ |
| Presensi Harian | ❌ | ✅ | ✅ | ✅ | ✅ |
| Penilaian Formatif | ✅ | ✅ | ✅ | ✅ | ✅ |
| Penilaian Sumatif | ✅ | ✅ | ✅ | ✅ | ✅ |
| SAS/SAT | ❌ | ❌ | ❌ | ❌ | ✅ |
| Deskripsi Otomatis | ✅ | ❌ | ❌ | ❌ | ✅ |
| E-Rapor | ✅ | ✅ | ✅ | ✅ | ✅ |
| Modul Ajar | ❌ | ❌ | ❌ | ❌ | ✅ |
| Jurnal Mengajar | ❌ | ✅ | ✅ | ⚠️ | ✅ |
| Bank Soal | ❌ | ✅ | ✅ | ⚠️ | ✅ |
| **Khas MTs/Islamic** | | | | | |
| Modul Tahfiz | ❌ | ❌ | ❌ | ❌ | ✅ |
| Mutaba'ah Ibadah | ❌ | ❌ | ❌ | ❌ | ✅ |
| Tilawati System | ❌ | ❌ | ❌ | ❌ | ✅ |
| Munaqosah | ❌ | ❌ | ❌ | ❌ | ✅ |
| P5RA Rapor | ✅ | ❌ | ❌ | ❌ | ✅ |
| **Inklusi** | | | | | |
| Identifikasi ABK | ❌ | ❌ | ❌ | ❌ | ✅ |
| Program PPI | ❌ | ❌ | ❌ | ❌ | ✅ |
| GPK Integration | ❌ | ❌ | ❌ | ❌ | ✅ |
| Asesmen Psikologis | ❌ | ❌ | ❌ | ❌ | ✅ |
| **Integrasi External** | | | | | |
| EMIS Sync | ✅ | ❌ | ❌ | ❌ | ✅ |
| Dapodik Integration | ⚠️ | ❌ | ❌ | ❌ | ✅ |
| RDM Integration | - | ❌ | ❌ | ❌ | ✅ |
| **User Portal** | | | | | |
| Portal Orang Tua | ⚠️ | ⚠️ | ✅ | ⚠️ | ✅ |
| WhatsApp Notification | ❌ | ❌ | ❌ | ❌ | ✅ |
| Student App | ✅ | ❌ | ✅ | ❌ | ✅ |
| **Ekonomi** | | | | | |
| Biaya (Gratis/Bulan) | FREE | FREE | $59 (sekali) | varies | varies |

---

## BAGIAN 7: REKOMENDASI STRATEGI PENGEMBANGAN

### 7.1 Tahap 1: MVP (Minimum Viable Product) - 6 Bulan

```
┌─────────────────────────────────────────────────────────────┐
│  MODUL WAJIB MVP:                                           │
│  1. Akademik (Biodata, Kelas, Presensi, Nilai, E-Rapor)    │
│  2. Kesiswaan (Organisasi, Ekskul, Pelanggaran)            │
│  3. Keuangan (SPP, Tagihan, Pembayaran)                    │
│  4. Dashboard Kepala Madrasah                              │
│  5. Portal Orang Tua (Web + WhatsApp)                      │
│  6. Integrasi RDM (sinkronisasi nilai)                     │
│  7. User Management multi-role                             │
├─────────────────────────────────────────────────────────────┤
│  TARGET: Sekolah bisa operasional dasar digital             │
│  USER: TU, Guru, Wali Kelas, Kepala Madrasah, Orang Tua    │
└─────────────────────────────────────────────────────────────┘
```

### 7.2 Tahap 2: Enhanced - 6-12 Bulan

```
┌─────────────────────────────────────────────────────────────┐
│  MODUL TAMBAHAN:                                            │
│  1. Modul Tahfiz (Tahfidzul Quran, Munaqosah)              │
│  2. Modul Inklusi (ABK, PPI, GPK)                          │
│  3. Modul BK/Konseling                                     │
│  4. Modul SDM/Kepegawaian                                  │
│  5. Modul E-Office (Surat masuk/keluar, Disposisi)         │
│  6. Modul Perpustakaan                                     │
├─────────────────────────────────────────────────────────────┤
│  TARGET: Sistem terintegrasi penuh dengan fitur MTs-spesific│
└─────────────────────────────────────────────────────────────┘
```

### 7.3 Tahap 3: Advanced - 12-18 Bulan

```
┌─────────────────────────────────────────────────────────────┐
│  MODUL LANJUTAN:                                            │
│  1. Modul Ajar & Supervisi Kurikulum                       │
│  2. Integrasi EMIS real-time                               │
│  3. E-Signature digital                                     │
│  4. Analytics & Reporting Advanced                         │
│  5. Mobile App (iOS + Android)                             │
│  6. API untuk third-party integration                      │
├─────────────────────────────────────────────────────────────┤
│  TARGET: Sistem enterprise-ready untuk grup sekolah/yayasan│
└─────────────────────────────────────────────────────────────┘
```

### 7.4 Strategi Pengembangan

| Strategi | Penjelasan |
|----------|------------|
| **Buat dari nol vs Fork existing** | Fork SISFOKOL TIDAK direkomendasikan (teknologi outdated). Mulai dari arsitektur modern (Laravel/Django/Node.js) |
| **Modular Architecture** | Gunakan microservices/modular design agar bisa development parallel dan deploy bertahap |
| **Integrasi RDM** | Jangan buat rapor sendiri - integrasikan dengan RDM yang sudah digunakan 52.000+ madrasah |
| **WhatsApp Integration** | Gunakan Green API atau Fonnte sebagai gateway (sudah banyak digunakan di Indonesia) |
| **Cloud-first** | Untuk pasar Malang/Indonesia, gunakan hosting lokal (IDCloudhost, Niagahoster) |
| **White-label untuk Yayasan** | Sediakan multi-tenant untuk yayasan dengan banyak MTs |
| **Compliance-first** | Pastikan semua fitur sesuai regulasi Kemendikdasmen dan Kemenag |

---

## BAGIAN 8: KESIMPULAN

### 8.1 Ringkasan Market Check

```
✅ PELUANG BESAR: Tidak ada produk komersial yang真正 (genuinely) 
   memenuhi kebutuhan MTs/Madrasah di Indonesia

✅ DEMAND TINGGI: 52.000+ madrasah sudah menggunakan RDM, 
   berarti sudah ada adopsi digital yang tinggi

✅ INTEGRASI WAJIB: EMIS + Dapodik adalah keharusan untuk 
   legalitas dan compliance

✅ KEUNGGULAN SAING: Modul Tahfiz + Inklusi adalah differentiator
   utama yang tidak ada di competitor manapun

❌ TANTANGAN: Regulasi yang sering berubah (Kurikulum Merdeka, 
   format rapor baru)

❌ TANTANGAN: User digital literacy yang bervariasi

❌ TANTANGAN: Budget ограничен untuk sekolah 
   (rekomendasikan model subscription terjangkau)
```

### 8.2 Prioritas Pengembangan

```
URGENT (Q1-Q2):
├── Modul Akademik inti (biodata, nilai, rapor)
├── Portal Orang Tua + WhatsApp
├── Dashboard Kepala Madrasah
└── User Management

IMPORTANT (Q3-Q4):
├── Modul Tahfiz
├── Modul Inklusi
├── Modul Keuangan lengkap
└── Modul BK/Konseling

NICE-TO-HAVE (Year 2+):
├── Modul Ajar & Supervisi
├── E-Office lengkap
├── Mobile App
└── Advanced Analytics
```

### 8.3 Rekomendasi Teknis

```
TEKNOLOGI STACK REKOMENDASI:
├── Backend: Laravel 11 / Django / Node.js
├── Frontend: Vue.js 3 / React / Svelte
├── Database: PostgreSQL / MySQL 8
├── Cache: Redis
├── Queue: RabbitMQ / Redis Queue
├── Storage: S3-compatible (Scaleway/IDCloudhost)
├── Hosting: VPS Indonesia (IDCloudhost, Niagahoster)
├── WhatsApp: Green API / Fonnte
├── SMS: Gammu / Twilio
└── Monitoring: Sentry, Prometheus, Grafana

ARSITEKTUR:
├── Modular/Microservices
├── API-first design
├── JWT Authentication
├── Role-based access control (RBAC)
├── Multi-tenant untuk yayasan
├── Responsive web (PWA-ready)
└── Mobile-first approach
```

---

## LAMPIRAN A: DAFTAR SUMBER

### Sumber Resmi Govt

1. RDM (Rapor Digital Madrasah) - rdm.kemenag.go.id
2. EMIS 4.0 - emispendis.kemenag.go.id
3. DAPODIK - dapodik.kemdikbud.go.id
4. E-Learning Madrasah - elearning.kemenag.go.id
5. SIMPATIKA - simpatika.kemenag.go.id

### GitHub Repositories

1. SISFOKOL - github.com/hajirodeon/SISFOKOL
2. SIMS DigiSchool - github.com/FirmanKurnialloh/SIMS-DigiSchool-CI3
3. Tuhfah (TPA) - github.com/404NotFoundIndonesia/tuhfah-webapp
4. SchoolHUB - github.com/mrandika/schoolhub
5. ASIMSE 4.0 - github.com/anak-desa1/ASIMSE-4.0-SD

### Marketplace

1. Codecanyon - codecanyon.net (Smart School, Ekattor)
2. Odoo Apps - apps.odoo.com (Indonesian School Module)
3. Themebagus - themebagus.com (Akademia Theme)

### Vendor Indonesia

1. AKS-SIMAKOM - e-simas.com
2. MuaraWeb - muaraweb.com
3. Argalyta - argalyta.com
4. Rufinka - rufinka.net
5. Sekolah.mu
6. Sevima
7. Simak.online

---

## LAMPIRAN B: INTEGRASI EMIS-DAPODIK

### Alur Integrasi Data

```
┌──────────────────────────────────────────────────────────────────┐
│                     INTEGRASI DATA EMIS-DAPODIK                  │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│   ┌─────────┐         ┌─────────┐         ┌─────────────┐      │
│   │  EMIS   │◄───────►│ PUSDATIN│◄───────►│   DAPODIK   │      │
│   │ (Kemenag)│         │(Kemdikbud)│         │ (Kemdikbud) │      │
│   └────┬────┘         └────┬────┘         └──────┬──────┘      │
│        │                   │                     │             │
│        │                   │                     │             │
│        ▼                   ▼                     ▼             │
│   ┌─────────────────────────────────────────────────────┐      │
│   │              ATTRIBUT DATA ALIRAN                   │      │
│   ├─────────────────────────────────────────────────────┤      │
│   │  Id Peserta Didik    │  NISN       │  NUPTK         │      │
│   │  NIK (Dukcapil)      │  NPSN       │  LTMPT         │      │
│   │  Nama Siswa          │  Email      │  KIP Kuliah    │      │
│   │  Tempat/Tgl Lahir    │  Alamat     │  Asesmen Nasi  │      │
│   │  Jenis Kelamin       │  Agama      │                │      │
│   │  Kebutuhan Khusus    │  Nama Ibu   │                │      │
│   │  Tingkat/Kelas       │  Rombel     │                │      │
│   └─────────────────────────────────────────────────────┘      │
│                                                                  │
│   ┌─────────────────────────────────────────────────────┐      │
│   │              KRITIS ISSUE                            │      │
│   ├─────────────────────────────────────────────────────┤      │
│   │  • Data siswa ganda (input di EMIS & Dapodik terpisah)│     │
│   │  • NISN berubah-ubah saat mutasi                    │      │
│   │  • Belum ada sinkronisasi otomatis untuk PPDB       │      │
│   │  • Perlu audiensi disdikbud-kandepag-disdukcapil    │      │
│   └─────────────────────────────────────────────────────┘      │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

### Atribut Data yang Dialirkan dari EMIS ke Pusdatin

```
• Id Peserta Didik       • NISN
• NIK                    • Nomor HP
• Nama Siswa             • Alamat email
• Tempat Lahir           • Tanggal Masuk Sekolah
• Tanggal Lahir          • Tahun Lulus
• Jenis Kelamin          • Jenis Keluar
• Agama                  • Id Sekolah
• Kebutuhan Khusus       • Tingkat/Kelas
• Nama Ibu Kandung       • NPSN
• Alamat Siswa           • Rombel Paralel
• Nama Madrasah
```

---

## LAMPIRAN C: KURIKULUM MERDEKA MADRASAH

### Struktur Kurikulum MTs Kurikulum Merdeka

```
┌──────────────────────────────────────────────────────────────────┐
│              STRUKTUR KURIKULUM MADRASAH 2025                    │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  MATA PELAJARAN ISLAM (Khas Madrasah):                          │
│  ├── Al-Qur'an Hadis         (2 JP/minggu)                      │
│  ├── Akidah Akhlak           (2 JP/minggu)                      │
│  ├── Fikih                   (2 JP/minggu)                      │
│  └── Sejarah Kultur Islam    (2 JP/minggu)                      │
│                                                                  │
│  MATA PELAJARAN UMUM (K13 Merdeka):                             │
│  ├── Bahasa Indonesia        (4 JP)                              │
│  ├── Matematika              (4 JP)                              │
│  ├── IPA/Sains               (4 JP)                              │
│  ├── IPS                     (4 JP)                              │
│  ├── Bahasa Inggris          (3 JP)                              │
│  ├── PJOK                    (3 JP)                              │
│  ├── Seni Budaya             (2 JP)                              │
│  └── Prakarya                (2 JP)                              │
│                                                                  │
│  PENDIDIKAN BUDAYA:                                              │
│  ├── PPKn                    (2 JP)                              │
│  └── Bahasa Jawa             (2 JP) - opsional                  │
│                                                                  │
│  PROJEK PENGUATAN PROFIL PELAJAR RAHMATAN LIL 'ALAMIN (P5RA)   │
│  └── 3-4 JP per minggu (kokurikuler)                            │
│                                                                  │
│  TOTAL: ~48 JP/minggu                                           │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

### Fitur P5RA yang Harus Didukung

```
P5RA (Projek Penguatan Profil Pelajar Rahmatan lil 'Alamin):
├── Profil Pelajar Pancasila
│   ├── Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia
│   ├── Berkebinekaan Global
│   ├── Bergotong Royong
│   ├── Mandiri
│   ├── Bernalar Kritis
│   └── Kreatif
│
├── Profil Pelajar Rahmatan lil 'Alamin
│   ├── Harmonis (dalam kehidupan pribadi dan sosial)
│   ├── Inklusif (dengan lingkungan sekitar)
│   ├── Peduli (terhadap sesama dan alam)
│   └── Berkelanjutan (dengan alam)
│
└── Contoh Projek:
    ├── Lingkungan (kebersihan, daur ulang)
    ├── Sosial (bakti sosial, fundraising)
    ├── Budaya (pentas seni islami)
    └── Literasi (membaca Al-Quran, menulis puisi Islami)
```

---

*Dokumen ini dibuat sebagai bahan analisis untuk pengembangan Sistem Informasi Manajemen Terpadu (SIMT) MTs/Madrasah*

*Tanggal: 12 Juni 2026*