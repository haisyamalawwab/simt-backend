# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs/YAYASAN

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** DRAFT  
**Penulis:** Tim Proyek SIMT MTs

---

## 1. INTRODUCTION

### 1.1 Purpose

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PURPOSE                                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Dokumen Software Requirements Specification (SRS) ini bertujuan    │
│  untuk menyediakan spesifikasi lengkap dan terperinci untuk         │
│  pengembangan Sistem Informasi Manajemen Terpadu (SIMT) MTs.        │
│                                                                     │
│  Dokumen ini berfungsi sebagai:                                     │
│  ├── Kontrak antara stakeholder dan tim development                 │
│  ├── Acuan untuk design dan development                            │
│  ├── Dasar untuk testing dan quality assurance                     │
│  ├── Dokumentasi untuk maintenance dan enhancement                 │
│  └── Standar untuk evaluasi dan acceptance                         │
│                                                                     │
│  Target Audience:                                                   │
│  ├── Project Manager & Team Lead                                   │
│  ├── Software Architects & Developers                              │
│  ├── QA Engineers & Testers                                        │
│  ├── System Administrators                                          │
│  ├── Stakeholders (Kepala Madrasah, Yayasan)                       │
│  └── External Auditors (jika diperlukan)                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.2 Scope

```
┌─────────────────────────────────────────────────────────────────────┐
│                           SCOPE                                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  PRODUCT NAME:     SIMT MTs (Sistem Informasi Manajemen Terpadu)   │
│  PRODUCT TYPE:     Web-based School Management Information System  │
│  VERSION:          1.0 (MVP)                                        │
│                                                                     │
│  INCLUDED IN SCOPE:                                                 │
│  ├── Modul Akademik (biodata, nilai, rapor, jadwal)               │
│  ├── Modul Kesiswaan (organisasi, ekskul, pelanggaran)             │
│  ├── Modul Keuangan (tagihan, pembayaran, laporan)                 │
│  ├── Modul SDM/Kepegawaian                                         │
│  ├── Modul Tahfiz (UNIQUE: monitoring hafalan, munaqosah)         │
│  ├── Modul Inklusi/PDBK (UNIQUE: PPI, GPK)                        │
│  ├── Modul BK/Konseling                                            │
│  ├── Modul E-Office (surat, disposisi, e-sign)                    │
│  ├── Portal Orang Tua dengan WhatsApp integration                 │
│  ├── Dashboard Kepala Madrasah                                     │
│  ├── Integrasi EMIS, DAPODIK, RDM                                 │
│  ├── User Management & RBAC                                        │
│  └── Multi-tenant untuk yayasan                                    │
│                                                                     │
│  OUT OF SCOPE:                                                      │
│  ├── E-Learning/LMS (pembelajaran online)                         │
│  ├── Online Examination dengan proctoring                          │
│  ├── Financial accounting lengkap (GL, AP, AR)                     │
│  ├── HR Payroll system                                             │
│  ├── Inventory management untuk non-pendidikan                     │
│  ├── Mobile native app (iOS/Android) - Phase 3                    │
│  └── AI/ML features - Phase 3+                                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.3 Definitions, Acronyms, and Abbreviations

```
┌─────────────────────────────────────────────────────────────────────┐
│                    GLOSSARY & ABBREVIATIONS                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  GENERAL TERMS:                                                     │
│  ├── SIMT: Sistem Informasi Manajemen Terpadu                      │
│  ├── MTs: Madrasah Tsanawiyah (SMP Islamic)                        │
│  ├── Madrasah: Sekolah Islam di bawah Kemenag                      │
│  ├── Yayasan: Organisasi pengelola sekolah                         │
│  └── NSM: Nomor Statistik Madrasah                                 │
│                                                                     │
│  AKADEMIK:                                                          │
│  ├── CP: Capaian Pembelajaran                                      │
│  ├── TP: Tujuan Pembelajaran                                       │
│  ├── ATP: Alur Tujuan Pembelajaran                                 │
│  ├── SAS: Sumatif Akhir Semester                                   │
│  ├── PTS: Penilaian Tengah Semester                                │
│  ├── PAS: Penilaian Akhir Semester                                 │
│  ├── P5RA: Projek Penguatan Profil Pelajar Rahmatan lil 'Alamin   │
│  ├── RDM: Rapor Digital Madrasah                                   │
│  └── Rombel: Rombongan Belajar (kelas)                             │
│                                                                     │
│  ADMINISTRASI:                                                      │
│  ├── EMIS: Education Management Information System                 │
│  ├── DAPODIK: Data Pokok Pendidikan                                │
│  ├── NISN: Nomor Induk Siswa Nasional                              │
│  ├── NPSN: Nomor Pokok Sekolah Nasional                            │
│  ├── NIK: Nomor Induk Kependudukan                                 │
│  └── NUPTK: Nomor Unik Pendidik dan Tenaga Kependidikan           │
│                                                                     │
│  SPESIFIK ISLAMIC:                                                  │
│  ├── Tahfidz: Penghafalan Al-Quran                                 │
│  ├── Munaqosah: Ujian hafalan Al-Quran                             │
│  ├── Murajaah: Pengulangan hafalan                                 │
│  ├── Tilawati: Metode belajar membaca Al-Quran                     │
│  ├── PPI: Program Pembelajaran Individual                          │
│  ├── ABK: Anak Berkebutuhan Khusus                                │
│  └── GPK: Guru Pendamping Khusus                                  │
│                                                                     │
│  TEKNIS:                                                            │
│  ├── API: Application Programming Interface                        │
│  ├── RBAC: Role-Based Access Control                              │
│  ├── JWT: JSON Web Token                                           │
│  ├── CRUD: Create, Read, Update, Delete                            │
│  ├── SSO: Single Sign-On                                           │
│  ├── SLA: Service Level Agreement                                  │
│  ├── RTO: Recovery Time Objective                                  │
│  └── RPO: Recovery Point Objective                                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.4 References

```
┌─────────────────────────────────────────────────────────────────────┐
│                         REFERENCES                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  REGULASI & STANDAR:                                                │
│  1. KMA 450 Tahun 2023 - Kurikulum Merdeka untuk Madrasah          │
│  2. Permendikbud No. 79 Tahun 2014 - DAPODIK                       │
│  3. KMA tentang Pengelolaan Data EMIS                               │
│  4. UU No. 27 Tahun 2022 - Perlindungan Data Pribadi               │
│  5. Panduan RDM (Rapor Digital Madrasah) Kemenag                   │
│  6. Standar Pengelolaan Keuangan Sekolah - Kemendikdasmen          │
│  7. Standar Pendidian Inklusif - Kemendikdasmen                    │
│  8. Panduan Akreditasi Madrasah - BAN-PDM                          │
│                                                                     │
│  TEKNIS:                                                            │
│  9. IEEE Std 830-1998 - SRS Best Practices                         │
│  10. OWASP Top 10 - Web Application Security                       │
│  11. WCAG 2.1 - Web Accessibility Guidelines                       │
│  12. RFC 7519 - JSON Web Token (JWT)                               │
│  13. OpenAPI 3.0 Specification                                     │
│                                                                     │
│  INTERNAL DOCUMENTS:                                                │
│  14. Dokumen Analisis Kelayakan (01_analisis_kelayakan.md)         │
│  15. Dokumen Analisis Kebutuhan (02_analisis_kebutuhan.md)         │
│  16. Dokumen Pemetaan Modul & RBAC (03_pemetaan_modul_rbac.md)     │
│  17. Dokumen PRD (04_prd_sim_mts.md)                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.5 Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                      DOCUMENT OVERVIEW                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Struktur dokumen SRS ini mengikuti standar IEEE 830:               │
│                                                                     │
│  Section 1: Introduction                                            │
│  ├── 1.1 Purpose                                                   │
│  ├── 1.2 Scope                                                     │
│  ├── 1.3 Definitions & Acronyms                                    │
│  ├── 1.4 References                                                │
│  └── 1.5 Overview                                                  │
│                                                                     │
│  Section 2: Overall Description                                     │
│  ├── 2.1 Product Perspective                                       │
│  ├── 2.2 Product Functions                                         │
│  ├── 2.3 User Classes and Characteristics                         │
│  ├── 2.4 Operating Environment                                     │
│  ├── 2.5 Design and Implementation Constraints                     │
│  ├── 2.6 Assumptions and Dependencies                              │
│  └── 2.7 Apportioning of Requirements                              │
│                                                                     │
│  Section 3: Specific Requirements (Detail)                          │
│  ├── 3.1 External Interface Requirements                           │
│  ├── 3.2 Functional Requirements (per modul)                       │
│  ├── 3.3 Performance Requirements                                   │
│  ├── 3.4 Design Constraints                                        │
│  ├── 3.5 Software System Attributes                                │
│  └── 3.6 Other Requirements                                        │
│                                                                     │
│  Section 4: Verification                                           │
│  └── 4.1 Acceptance Criteria                                       │
│                                                                     │
│  Section 5: Appendices                                             │
│  └── 5.1 Issue Tracking                                            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. OVERALL DESCRIPTION

### 2.1 Product Perspective

```
┌─────────────────────────────────────────────────────────────────────┐
│                    PRODUCT PERSPECTIVE                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  SIMT MTs adalah sistem informasi manajemen sekolah yang           │
│  dikembangkan untuk memenuhi kebutuhan spesifik Madrasah           │
│  Tsanawiyah (MTs) di Indonesia, dengan fokus pada:                 │
│                                                                     │
│  1. INTEGRASI MULTI-SISTEM                                         │
│                                                                     │
│     ┌─────────────────────────────────────────────────────────┐    │
│     │                        USERS                            │    │
│     │   ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐   │    │
│     │   │ KAMAD   │  │  GURU   │  │ ORANG   │  │  TU     │   │    │
│     │   │         │  │         │  │  TUA    │  │         │   │    │
│     │   └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘   │    │
│     └────────┼───────────┼───────────┼───────────┼────────────┘    │
│              │           │           │           │                  │
│              └───────────┴───────────┴───────────┘                  │
│                          │                                          │
│                          ▼                                          │
│              ┌─────────────────────────┐                           │
│              │       SIMT MTs          │                           │
│              │   (Central System)      │                           │
│              └────────────┬────────────┘                           │
│                           │                                        │
│         ┌─────────────────┼─────────────────┐                      │
│         │                 │                 │                      │
│         ▼                 ▼                 ▼                      │
│  ┌─────────────┐   ┌─────────────┐   ┌─────────────┐              │
│  │    EMIS     │   │   RDM       │   │  WHATSAPP   │              │
│  │  (Kemenag)  │   │  (Kemenag)  │   │  (Parents)  │              │
│  └─────────────┘   └─────────────┘   └─────────────┘              │
│         ▲                                                              │
│         │                                                              │
│  ┌─────────────┐                                                       │
│  │  DAPODIK    │                                                       │
│  │(Kemendikbud)│                                                       │
│  └─────────────┘                                                       │
│                                                                     │
│  2. POSISI PRODUK DALAM EKOSISTEM                                   │
│                                                                     │
│  SIMT MTs beroperasi sebagai sistem sentral yang mengintegrasikan: │
│  ├── Input data dari user (guru, TU, dll)                          │
│  ├── Sinkronisasi dengan sistem pemerintah (EMIS, DAPODIK)         │
│  ├── Pengisian rapor ke RDM (sistem resmi Kemenag)                 │
│  └── Notifikasi ke orang tua via WhatsApp                          │
│                                                                     │
│  3. KEY DIFFERENTIATORS                                             │
│                                                                     │
│  Unlike generic school management systems, SIMT MTs menyediakan:    │
│  ├── Modul Tahfiz profesional (UNIQUE di pasar)                   │
│  ├── Modul Inklusi/PDBK sesuai regulasi (UNIQUE di pasar)         │
│  ├── Dashboard terintegrasi untuk kepala madrasah                  │
│  └── Notifikasi WhatsApp untuk engagement orang tua                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.2 Product Functions

```
┌─────────────────────────────────────────────────────────────────────┐
│                    PRODUCT FUNCTIONS SUMMARY                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FUNCTION GROUP 1: AKADEMIK MANAGEMENT                             │
│  ───────────────────────────────────────────                        │
│  F1.1: Student Information Management                              │
│       → Biodata, keluarga, riwayat kelas                           │
│       → Import dari EMIS/DAPODIK                                   │
│  F1.2: Class & Rombel Management                                   │
│       → Setup rombel, naik kelas, mutasi                           │
│  F1.3: Schedule Management                                         │
│       → Generate jadwal, penugasan guru                            │
│  F1.4: Attendance Management                                       │
│       → Presensi harian, izin, sakit, alpa                         │
│  F1.5: Assessment & Grading                                        │
│       → Input formatif, sumatif, projek                            │
│       → Auto-calculate dan auto-deskripsi                          │
│  F1.6: Report Card (E-Rapor)                                       │
│       → Generate rapor Kurikulum Merdeka                           │
│       → Integrasi RDM                                              │
│                                                                     │
│  FUNCTION GROUP 2: STUDENT AFFAIRS                                 │
│  ─────────────────────────────────────                             │
│  F2.1: Organization & Extracurricular                              │
│       → Kelola organisasi siswa & ekskul                           │
│  F2.2: Discipline Management                                       │
│       → Sistem poin pelanggaran & reward                           │
│  F2.3: Permission System                                           │
│       → Request & approval izin                                    │
│  F2.4: Achievement Tracking                                        │
│       → Input prestasi & sertifikat                                │
│                                                                     │
│  FUNCTION GROUP 3: FINANCIAL MANAGEMENT                            │
│  ───────────────────────────────────────                           │
│  F3.1: Billing System                                              │
│       → Setup komponen, generate tagihan                           │
│  F3.2: Payment Processing                                          │
│       → Input, validasi, konfirmasi pembayaran                     │
│  F3.3: Financial Reporting                                         │
│       → Laporan pemasukan, arus kas, neraca                        │
│  F3.4: Payment Notification                                        │
│       → Reminder WA, konfirmasi payment                            │
│                                                                     │
│  FUNCTION GROUP 4: TAHFIZ (UNIQUE)                                 │
│  ─────────────────────────────────                                 │
│  F4.1: Tahfiz Program Management                                   │
│       → Target hafalan, kurikulum tahfiz                           │
│  F4.2: Hafalan Recording                                           │
│       → Setoran, murajaah, penilaian tajwid                        │
│  F4.3: Munaqosah Management                                        │
│       → Jadwal, penilaian, sertifikat                              │
│  F4.4: Tilawati Tracking                                           │
│       → Level, progress tilawati                                   │
│  F4.5: Tahfiz Dashboard                                            │
│       → Grafik progress, warning target                            │
│                                                                     │
│  FUNCTION GROUP 5: INKLUSI/PDBK (UNIQUE)                          │
│  ───────────────────────────────────────                           │
│  F5.1: ABK Identification                                          │
│       → Screening, kategori, database                              │
│  F5.2: PPI Management                                              │
│       → Program pembelajaran individual                            │
│  F5.3: GPK Integration                                             │
│       → Jadwal, catatan pendampingan                              │
│  F5.4: Psychological Assessment                                     │
│       → Input tes IQ, hasil psikolog                               │
│  F5.5: Inklusi Dashboard                                           │
│       → Statistik, monitoring progress                             │
│                                                                     │
│  FUNCTION GROUP 6: BK/KONSELING                                    │
│  ──────────────────────────────                                    │
│  F6.1: Counseling Records                                          │
│       → Catatan kasus, planning                                    │
│  F6.2: Behavior Monitoring                                         │
│       → Tracking perubahan perilaku                                │
│  F6.3: Parent Call System                                          │
│       → Generate surat, tracking komunikasi                        │
│  F6.4: Psychological Tools                                         │
│       → Tes bakat, minat, kepribadian                              │
│                                                                     │
│  FUNCTION GROUP 7: E-OFFICE                                        │
│  ────────────────────────                                          │
│  F7.1: Document Management                                         │
│       → Surat masuk/keluar, disposisi                              │
│  F7.2: Digital Signature                                           │
│       → TTD digital kepala madrasah                                │
│  F7.3: Calendar & Agenda                                           │
│       → Kalender akademik, jadwal pimpinan                         │
│  F7.4: Cloud Storage                                               │
│       → Penyimpanan dokumen terpusat                               │
│                                                                     │
│  FUNCTION GROUP 8: PORTAL & NOTIFICATION                           │
│  ──────────────────────────────────────────                        │
│  F8.1: Parent Portal                                               │
│       → Monitoring nilai, presensi, keuangan                       │
│  F8.2: WhatsApp Integration                                        │
│       → Notifikasi otomatis (kehadiran, nilai, keuangan)           │
│  F8.3: Headmaster Dashboard                                        │
│       → KPI sekolah, drill-down reporting                          │
│                                                                     │
│  FUNCTION GROUP 9: SYSTEM ADMINISTRATION                           │
│  ──────────────────────────────────────                            │
│  F9.1: User Management                                             │
│       → CRUD user, role assignment                                 │
│  F9.2: RBAC (Role-Based Access Control)                           │
│       → Permission management per role                             │
│  F9.3: Data Integration                                            │
│       → EMIS sync, DAPODIK import, RDM export                     │
│  F9.4: Audit Trail                                                 │
│       → Logging seluruh aktivitas                                  │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.3 User Classes and Characteristics

```
┌─────────────────────────────────────────────────────────────────────┐
│                  USER CLASSES & CHARACTERISTICS                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ CLASS 1: ADMINISTRATOR (Super Admin)                       │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Skills:          Expert IT, system administration           │   │
│  │ Frequency:       Daily (system maintenance)                │   │
│  │ Interface:       Web-based admin panel                     │   │
│  │ Authorization:   Full system access                        │   │
│  │ Training:        1 week intensive                          │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ CLASS 2: KEPALA MADRASAH                                    │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Skills:          Basic-intermediate computer literacy      │   │
│  │ Frequency:       Daily (dashboard review, approvals)       │   │
│  │ Interface:       Web dashboard, mobile-responsive           │   │
│  │ Authorization:   School-wide read, selective write         │   │
│  │ Training:        2 days                                    │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ CLASS 3: GURU (Termasuk Wali Kelas, Tahfiz, BK, GPK)       │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Skills:          Basic computer, smartphone proficiency    │   │
│  │ Frequency:       Daily (input nilai, presensi)             │   │
│  │ Interface:       Web + Mobile optimized forms              │   │
│  │ Authorization:   Scope-based (mapel, kelas, siswa tertentu)│   │
│  │ Training:        3 days                                    │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ CLASS 4: TATA USAHA & BENDAHARA                             │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Skills:          Intermediate (data entry, reporting)      │   │
│  │ Frequency:       Daily (transaction processing)            │   │
│  │ Interface:       Web-based full feature                    │   │
│  │ Authorization:   Admin functions (data entry, validation)  │   │
│  │ Training:        4 days                                    │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ CLASS 5: ORANG TUA                                          │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Skills:          Basic smartphone (WhatsApp level)         │   │
│  │ Frequency:       Weekly (monitoring anak)                  │   │
│  │ Interface:       Mobile-first, simplified (WhatsApp-like)  │   │
│  │ Authorization:   Own child data only                       │   │
│  │ Training:        Self-service (video tutorial)             │   │
│  │ Special:         WhatsApp notification primary channel     │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ CLASS 6: SISWA                                              │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Skills:          High (digital native)                     │   │
│  │ Frequency:       Occasional (view schedule, nilai)         │   │
│  │ Interface:       Mobile-first, simple                       │   │
│  │ Authorization:   Own data only                             │   │
│  │ Training:        None (self-explanatory UI)                │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ CLASS 7: YAYASAN                                            │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Skills:          Intermediate                               │   │
│  │ Frequency:       Monthly (oversight, reporting)            │   │
│  │ Interface:       Web dashboard (multi-school view)         │   │
│  │ Authorization:   All schools in foundation                 │   │
│  │ Training:        2 days                                    │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.4 Operating Environment

```
┌─────────────────────────────────────────────────────────────────────┐
│                    OPERATING ENVIRONMENT                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  1. CLIENT-SIDE                                                     │
│                                                                     │
│  BROWSER SUPPORT:                                                    │
│  ├── Chrome 90+ (recommended)                                      │
│  ├── Firefox 88+                                                   │
│  ├── Safari 14+                                                    │
│  ├── Edge 90+                                                      │
│  └── Opera 76+                                                     │
│                                                                     │
│  DEVICE SUPPORT:                                                     │
│  ├── Desktop: Windows 10+, macOS 10.15+, Linux                     │
│  ├── Tablet: iPadOS 14+, Android 10+                              │
│  ├── Mobile: Android 10+, iOS 14+                                 │
│  └── PWA-ready for installation                                    │
│                                                                     │
│  2. SERVER-SIDE                                                     │
│                                                                     │
│  WEB SERVER:                                                         │
│  ├── Nginx 1.20+ atau Apache 2.4+                                 │
│  ├── Load balancer (for scaling)                                  │
│  └── CDN integration (Cloudflare)                                 │
│                                                                     │
│  APPLICATION SERVER:                                                │
│  ├── Node.js 18+ atau PHP 8.2+                                     │
│  ├── Memory: 8GB RAM minimum                                       │
│  ├── CPU: 4 cores minimum                                          │
│  └── Container: Docker 20+                                         │
│                                                                     │
│  DATABASE:                                                           │
│  ├── PostgreSQL 15+ (recommended) atau MySQL 8+                   │
│  ├── Memory: 4GB minimum                                           │
│  ├── Storage: 100GB SSD minimum                                    │
│  └── Backup: Daily automated                                       │
│                                                                     │
│  CACHE & QUEUE:                                                      │
│  ├── Redis 7+ (cache + queue)                                     │
│  └── Memory: 2GB minimum                                           │
│                                                                     │
│  3. INFRASTRUCTURE                                                   │
│                                                                     │
│  HOSTING OPTIONS:                                                    │
│  ├── VPS Indonesia (IDCloudhost, Niagahoster) - DEFAULT           │
│  ├── Cloud Indonesia (Alibaba Cloud, AWS Jakarta)                  │
│  └── On-premise (untuk sekolah dengan IT support)                  │
│                                                                     │
│  NETWORK REQUIREMENTS:                                              │
│  ├── Minimum bandwidth: 10 Mbps per sekolah                        │
│  ├── Latency: < 100ms ke server                                    │
│  ├── Uptime target: 99.5%                                          │
│  └── Backup internet (recommended)                                 │
│                                                                     │
│  4. INTEGRATION ENVIRONMENT                                         │
│                                                                     │
│  EXTERNAL SYSTEMS:                                                   │
│  ├── EMIS API (Kemenag) - Production endpoint varies              │
│  ├── DAPODIK API (Kemendikbud) - Production endpoint varies       │
│  ├── RDM System (Kemenag) - File-based integration                │
│  ├── WhatsApp Business API (Green API/Fonnte)                     │
│  └── Payment Gateway (Midtrans/Xendit) - Optional                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.5 Design and Implementation Constraints

```
┌─────────────────────────────────────────────────────────────────────┐
│              DESIGN & IMPLEMENTATION CONSTRAINTS                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  1. REGULATORY CONSTRAINTS                                          │
│  ├── Wajib integrasi dengan EMIS (Kemenag)                         │
│  ├── Wajib support Kurikulum Merdeka Madrasah (KMA 450/2023)       │
│  ├── Wajib kepatuhan UU Perlindungan Data Pribadi (UU PDP)         │
│  ├── Wajib kompatibilitas dengan RDM (Rapor Digital Madrasah)      │
│  └── Format pelaporan harus sesuai standar Kemendik & Kemenag      │
│                                                                     │
│  2. TECHNICAL CONSTRAINTS                                           │
│  ├── Mobile-first design (mayoritas akses via smartphone)          │
│  ├── Bahasa Indonesia sebagai default (i18n ready)                 │
│  ├── Aksesibilitas WCAG 2.1 AA minimum                             │
│  ├── Responsive design untuk semua device                          │
│  ├── Offline capability untuk area dengan koneksi tidak stabil     │
│  └── Progressive Web App (PWA) capability                           │
│                                                                     │
│  3. SECURITY CONSTRAINTS                                            │
│  ├── SSL/TLS mandatory untuk semua koneksi                         │
│  ├── Data encryption at-rest (AES-256)                            │
│  ├── OWASP Top 10 compliant                                        │
│  ├── Annual penetration testing                                    │
│  ├── Audit trail untuk semua write operations                      │
│  └── 2FA untuk admin accounts                                      │
│                                                                     │
│  4. PERFORMANCE CONSTRAINTS                                         │
│  ├── API response < 200ms (P95)                                    │
│  ├── Page load < 3s (P95, 3G network)                             │
│  ├── Support 500+ concurrent users per instance                    │
│  └── 99.5% uptime target                                           │
│                                                                     │
│  5. BUDGET CONSTRAINTS                                              │
│  ├── Target development cost: < Rp 1.5 milyar (Year 1)             │
│  ├── Operational cost: < Rp 50 juta/bulan                          │
│  └── Subscription model terjangkau untuk sekolah                   │
│                                                                     │
│  6. TIMELINE CONSTRAINTS                                            │
│  ├── MVP launch: 9 bulan dari project start                        │
│  ├── Full feature: 18 bulan dari project start                     │
│  └── Pilot dengan 1-2 MTs sebelum full deployment                  │
│                                                                     │
│  7. INTEROPERABILITY CONSTRAINTS                                    │
│  ├── RESTful API dengan OpenAPI documentation                      │
│  ├── JSON format untuk semua API responses                         │
│  ├── JWT authentication                                            │
│  └── Standard HTTP methods and status codes                        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.6 Assumptions and Dependencies

```
┌─────────────────────────────────────────────────────────────────────┐
│                 ASSUMPTIONS & DEPENDENCIES                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ASSUMPTIONS:                                                       │
│  ├── A1: Sekolah memiliki akses internet stabil                    │
│  ├── A2: Minimum 1 komputer untuk operator dengan spesifikasi      │
│      minimum: Windows 10, 4GB RAM, Chrome browser                  │
│  ├── A3: Users (guru, TU) memiliki basic computer literacy         │
│  ├── A4: Orang tua memiliki smartphone dengan WhatsApp aktif       │
│  ├── A5: Sekolah memiliki akun EMIS yang aktif                     │
│  ├── A6: Ada dedicated person untuk operator sistem                │
│  ├── A7: Data EMIS dan Dapodik dapat diakses via API/file          │
│  ├── A8: Regulasi Kurikulum Merdeka tidak berubah drastis          │
│  ├── A9: Sekolah/yayasan memiliki budget untuk subscription        │
│  └── A10: Willingness untuk change management dan training         │
│                                                                     │
│  EXTERNAL DEPENDENCIES:                                             │
│  ├── D1: EMIS API availability - Depends on Kemenag                │
│  ├── D2: DAPODIK API availability - Depends on Kemendikdasmen      │
│  ├── D3: RDM file format stability - Depends on Kemenag            │
│  ├── D4: WhatsApp Business API stability - Depends on provider     │
│  ├── D5: Cloud hosting provider uptime - SLA with provider         │
│  └── D6: Payment gateway availability - Depends on provider        │
│                                                                     │
│  DEVELOPMENT DEPENDENCIES:                                          │
│  ├── D7: Wireframe & UI design completion before development       │
│  ├── D8: Database schema approval before coding                    │
│  ├── D9: RBAC definition finalization before coding                │
│  ├── D10: Test environment setup before development                │
│  └── D11: Third-party library compatibility verification           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 3. SPECIFIC REQUIREMENTS

### 3.1 External Interface Requirements

#### 3.1.1 User Interfaces

```
┌─────────────────────────────────────────────────────────────────────┐
│                    USER INTERFACE REQUIREMENTS                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  UI-1: GENERAL STYLE REQUIREMENTS                                   │
│  ├── Consistent design language throughout all modules             │
│  ├── Mobile-first responsive design                                │
│  ├── Clean, uncluttered interface (minimal cognitive load)         │
│  ├── Consistent color scheme and typography                        │
│  ├── Standard icons and visual indicators                          │
│  └── Loading states and progress indicators                        │
│                                                                     │
│  UI-2: WEB APPLICATION                                              │
│  ├── Single Page Application (SPA) architecture                    │
│  ├── Server-side rendering for SEO (landing page)                  │
│  ├── Lazy loading for performance                                  │
│  ├── Progressive enhancement approach                              │
│  └── PWA capability for offline access                             │
│                                                                     │
│  UI-3: NAVIGATION                                                   │
│  ├── Sidebar menu for complex modules (admin, guru)               │
│  ├── Bottom navigation for mobile (parent, student)               │
│  ├── Breadcrumb navigation for deep hierarchies                   │
│  ├── Quick actions toolbar for common operations                   │
│  └── Search functionality global and contextual                    │
│                                                                     │
│  UI-4: FORMS                                                        │
│  ├── Inline validation with immediate feedback                     │
│  ├── Auto-save drafts for long forms                              │
│  ├── Bulk operations for list views                                │
│  ├── Import capability (Excel, CSV) for data entry                │
│  └── Confirmation dialogs for destructive actions                   │
│                                                                     │
│  UI-5: DATA DISPLAY                                                 │
│  ├── Sortable and filterable data tables                           │
│  ├── Drill-down capability for hierarchical data                   │
│  ├── Export to Excel/PDF for reports                               │
│  ├── Chart and graph visualizations                                │
│  └── Responsive data cards for mobile view                         │
│                                                                     │
│  UI-6: ACCESSIBILITY                                                │
│  ├── Keyboard navigation support                                    │
│  ├── Screen reader compatibility                                    │
│  ├── Sufficient color contrast (WCAG AA)                          │
│  ├── Focus indicators for interactive elements                     │
│  └── Alt text for images and icons                                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 3.1.2 Hardware Interfaces

```
┌─────────────────────────────────────────────────────────────────────┐
│                   HARDWARE INTERFACE REQUIREMENTS                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  CLIENT-SIDE (Minimum):                                             │
│  ├── Display: 320px width (mobile), 1024px (desktop)              │
│  ├── Input: Touch screen (mobile), Keyboard + Mouse (desktop)     │
│  ├── Network: WiFi/4G with minimum 1 Mbps bandwidth               │
│  └── Browser: Chrome/Firefox/Safari/Edge latest 2 versions        │
│                                                                     │
│  OPTIONAL CLIENT HARDWARE:                                          │
│  ├── Fingerprint scanner (ZKTeco compatible) - untuk presensi guru│
│  ├── Barcode/QR scanner - untuk presensi siswa, perpustakaan      │
│  └── Thermal printer - untuk cetkak bukti pembayaran, rapor       │
│                                                                     │
│  SERVER-SIDE (Minimum):                                             │
│  ├── CPU: 4 cores (Intel Xeon or equivalent)                      │
│  ├── RAM: 8 GB                                                      │
│  ├── Storage: 100 GB SSD                                            │
│  ├── Network: 100 Mbps dedicated                                   │
│  └── UPS for power backup (recommended)                            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 3.1.3 Software Interfaces

```
┌─────────────────────────────────────────────────────────────────────┐
│                   SOFTWARE INTERFACE REQUIREMENTS                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  INTEGRATION 1: EMIS (Education Management Information System)     │
│  ├── Type: REST API / File-based sync                             │
│  ├── Direction: Bi-directional (read/write where allowed)         │
│  ├── Data: Student, Teacher, School data                          │
│  ├── Authentication: API Key / Token                              │
│  ├── Frequency: On-demand sync + scheduled daily                  │
│  └── Error Handling: Retry mechanism + fallback to manual         │
│                                                                     │
│  INTEGRATION 2: DAPODIK (Data Pokok Pendidikan)                    │
│  ├── Type: REST API / File import                                 │
│  ├── Direction: One-way (import from Dapodik)                     │
│  ├── Data: Student biodata, NISN, NIK                            │
│  ├── Authentication: API Key / Token                              │
│  ├── Frequency: On-demand import                                  │
│  └── Error Handling: Validation + manual correction               │
│                                                                     │
│  INTEGRATION 3: RDM (Rapor Digital Madrasah)                       │
│  ├── Type: File export/import (Excel/JSON format)                 │
│  ├── Direction: Export nilai ke RDM format                        │
│  ├── Data: Assessment values, student grades                      │
│  ├── Frequency: Per semester (during reporting period)            │
│  └── Error Handling: Validation before export                     │
│                                                                     │
│  INTEGRATION 4: WhatsApp Business API                              │
│  ├── Provider: Green API or Fonnte (Indonesian providers)         │
│  ├── Type: REST API to WhatsApp Cloud                             │
│  ├── Features: Send message, template message, media              │
│  ├── Authentication: API Key from provider                        │
│  ├── Rate Limit: 100 messages/minute                              │
│  └── Error Handling: Queue with retry, fallback to notification   │
│                                                                     │
│  INTEGRATION 5: Payment Gateway (Optional)                         │
│  ├── Provider: Midtrans / Xendit                                  │
│  ├── Type: REST API                                               │
│  ├── Features: Virtual account, credit card, e-wallet             │
│  ├── Authentication: Server key from provider                     │
│  └── Error Handling: Webhook callback + reconciliation            │
│                                                                     │
│  INTEGRATION 6: SMS Gateway (Backup)                               │
│  ├── Provider: Gammu / Twilio                                     │
│  ├── Type: REST API / SMPP                                        │
│  ├── Features: OTP, notification fallback                         │
│  └── Authentication: API Key                                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 3.1.4 Communication Interfaces

```
┌─────────────────────────────────────────────────────────────────────┐
│                 COMMUNICATION INTERFACE REQUIREMENTS                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  API PROTOCOL:                                                      │
│  ├── Type: RESTful API                                             │
│  ├── Format: JSON for request/response                             │
│  ├── Authentication: JWT Bearer Token                              │
│  ├── Versioning: URL-based (/api/v1/)                              │
│  └── Documentation: OpenAPI 3.0 (Swagger)                         │
│                                                                     │
│  API ENDPOINT PATTERNS:                                             │
│  ├── GET    /api/v1/{resource}             # List                 │
│  ├── GET    /api/v1/{resource}/:id         # Detail               │
│  ├── POST   /api/v1/{resource}             # Create               │
│  ├── PUT    /api/v1/{resource}/:id         # Update               │
│  ├── DELETE /api/v1/{resource}/:id         # Delete               │
│  └── POST   /api/v1/{resource}/bulk        # Bulk operation       │
│                                                                     │
│  REAL-TIME COMMUNICATION:                                           │
│  ├── Type: WebSocket for live notifications                        │
│  ├── Channels: User-specific, Role-specific                       │
│  └── Features: Typing indicator, read receipts                    │
│                                                                     │
│  ASYNC COMMUNICATION:                                               │
│  ├── Type: Message Queue (Redis/Bull)                             │
│  ├── Use cases: Email, WA notifications, report generation        │
│  └── Retry: 3 attempts with exponential backoff                   │
│                                                                     │
│  ERROR RESPONSE FORMAT:                                             │
│  {                                                                 │
│    "success": false,                                               │
│    "error": {                                                      │
│      "code": "ERROR_CODE",                                         │
│      "message": "Human readable message",                          │
│      "details": [...]                                              │
│    }                                                               │
│  }                                                                 │
│                                                                     │
│  PAGINATION:                                                        │
│  ├── Default: 20 items per page                                    │
│  ├── Maximum: 100 items per page                                   │
│  ├── Format: ?page=1&limit=20                                      │
│  └── Response includes: total, page, limit, total_pages            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Functional Requirements

Berikut adalah daftar functional requirements per modul. Format: FR-XX-YYY

#### 3.2.1 Modul Akademik

```
┌─────────────────────────────────────────────────────────────────────┐
│                  MODUL AKADEMIK - REQUIREMENTS                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FR-AK-001: Student Information Management                          │
│  ├── FR-AK-001.1: Sistem harus dapat menerima input biodata       │
│  │   siswa lengkap termasuk nama, NIK, NISN, tempat/tgl lahir,     │
│  │   alamat, data orang tua, dan foto.                             │
│  ├── FR-AK-001.2: Sistem harus dapat melakukan import data siswa  │
│  │   dari file Excel dengan format yang ditentukan.                │
│  ├── FR-AK-001.3: Sistem harus dapat sync dengan data EMIS        │
│  │   untuk validasi NIK dan NISN.                                  │
│  ├── FR-AK-001.4: Sistem harus dapat mencatat riwayat kelas       │
│  │   setiap siswa (naik kelas, mutasi, keluar).                    │
│  ├── FR-AK-001.5: Sistem harus dapat melacak status siswa         │
│  │   (aktif, nonaktif, alumni) dengan timestamp.                   │
│  └── FR-AK-001.6: Sistem harus dapat menyimpan dokumen pendukung  │
│      siswa (scan KK, akta lahir, dll) dengan enkripsi.             │
│                                                                     │
│  FR-AK-002: Class Management                                        │
│  ├── FR-AK-002.1: Sistem harus dapat membuat dan mengelolah       │
│  │   rombel dengan nama, tingkat, tahun ajaran.                    │
│  ├── FR-AK-002.2: Sistem harus dapat menugaskan wali kelas        │
│  │   dan siswa ke setiap rombel.                                   │
│  ├── FR-AK-002.3: Sistem harus dapat melakukan proses naik kelas  │
│  │   secara batch dengan validasi.                                 │
│  ├── FR-AK-002.4: Sistem harus dapat menangani mutasi masuk       │
│  │   dan keluar dengan update otomatis.                            │
│  └── FR-AK-002.5: Sistem harus dapat menampilkan rekap kelas      │
│      meliputi jumlah siswa, wali kelas, guru mapel.                │
│                                                                     │
│  FR-AK-003: Schedule Management                                     │
│  ├── FR-AK-003.1: Sistem harus dapat mendefinisikan jam           │
│  │   pelajaran dengan waktu mulai dan selesai.                     │
│  ├── FR-AK-003.2: Sistem harus dapat menugaskan guru mapel        │
│  │   ke kelas tertentu dengan hari dan jam.                        │
│  ├── FR-AK-003.3: Sistem harus dapat mendeteksi konflik jadwal    │
│  │   (guru double book, ruangan double book).                      │
│  ├── FR-AK-003.4: Sistem harus dapat mencatat perubahan jadwal    │
│  │   beserta alasan dan approval.                                  │
│  └── FR-AK-003.5: Sistem harus dapat menampilkan jadwal           │
│  │   berdasarkan view: per guru, per kelas, per ruangan.          │
│                                                                     │
│  FR-AK-004: Attendance Management                                   │
│  ├── FR-AK-004.1: Sistem harus dapat menerima input presensi      │
│  │   harian dengan status: Hadir (H), Izin (I), Sakit (S), Alpa (A).│
│  ├── FR-AK-004.2: Sistem harus dapat mencatat jam datang          │
│  │   untuk deteksi keterlambatan.                                  │
│  ├── FR-AK-004.3: Sistem harus dapat menghitung rekap presensi    │
│  │   per siswa per bulan dengan breakdown H/I/S/A.                 │
│  ├── FR-AK-004.4: Sistem harus dapat mengirim notifikasi WA       │
│  │   otomatis ke orang tua jika siswa tidak hadir sebelum jam 09:00│
│  └── FR-AK-004.5: Sistem harus dapat mengekspor rekap presensi    │
│      ke format Excel dan PDF.                                      │
│                                                                     │
│  FR-AK-005: Assessment & Grading                                    │
│  ├── FR-AK-005.1: Sistem harus dapat menerima input nilai         │
│  │   formatif per pertemuan dengan skala 0-100.                    │
│  ├── FR-AK-005.2: Sistem harus dapat menerima input nilai PTS     │
│  │   dan PAS dengan bobot yang dapat dikonfigurasi.                │
│  ├── FR-AK-005.3: Sistem harus dapat menerima input nilai projek  │
│  │   kokurikuler (P5RA) terpisah dari nilai mapel.                 │
│  ├── FR-AK-005.4: Sistem harus dapat menghitung nilai akhir        │
│  │   semester secara otomatis berdasarkan formula yang ditentukan. │
│  ├── FR-AK-005.5: Sistem harus dapat menghasilkan deskripsi        │
│  │   capaian otomatis berdasarkan CP dan nilai siswa.              │
│  └── FR-AK-005.6: Sistem harus dapat menangani remedial dan       │
│      pengayaan dengan kategori terpisah.                           │
│                                                                     │
│  FR-AK-006: Report Card (E-Rapor)                                   │
│  ├── FR-AK-006.1: Sistem harus dapat menghasilkan rapor           │
│  │   semester dengan format Kurikulum Merdeka.                     │
│  ├── FR-AK-006.2: Sistem harus dapat mengekspor data nilai        │
│  │   ke format yang compatible dengan RDM.                         │
│  ├── FR-AK-006.3: Sistem harus dapat mencetak rapor PDF           │
│  │   dengan barcode dan watermark sekolah.                         │
│  ├── FR-AK-006.4: Sistem harus dapat menerapkan tanda tangan      │
│  │   digital kepala madrasah dengan QR verification.               │
│  └── FR-AK-006.5: Sistem harus dapat mengarsipkan rapor           │
│  │   setiap semester dengan backup otomatis.                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 3.2.2 Modul Keuangan

```
┌─────────────────────────────────────────────────────────────────────┐
│                   MODUL KEUANGAN - REQUIREMENTS                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FR-KE-001: Billing Setup                                           │
│  ├── FR-KE-001.1: Sistem harus dapat mendefinisikan komponen       │
│  │   tagihan (SPP, daftar ulang, uang kegiatan, dll) dengan        │
│  │   nominal dan jadwal.                                           │
│  ├── FR-KE-001.2: Sistem harus dapat membuat tagihan massal        │
│  │   untuk seluruh siswa atau per kelas.                           │
│  ├── FR-KE-001.3: Sistem harus dapat menerapkan diskon dan         │
│  │  奖学金 dengan alasan dan approval.                             │
│  ├── FR-KE-001.4: Sistem harus dapat membuat tagihan khusus        │
│  │   (event-based) di luar routine.                                │
│  └── FR-KE-001.5: Sistem harus dapat menampilkan aging report      │
│      tunggakan.                                                    │
│                                                                     │
│  FR-KE-002: Payment Processing                                      │
│  ├── FR-KE-002.1: Sistem harus dapat menerima input pembayaran    │
│  │   manual dengan detail: tanggal, nominal, metode, bukti.        │
│  ├── FR-KE-002.2: Sistem harus dapat menerima upload bukti        │
│  │   transfer dari orang tua dengan auto-detect.                   │
│  ├── FR-KE-002.3: Sistem harus dapat memvalidasi pembayaran        │
│  │   dengan approval dari TU atau Bendahara.                       │
│  ├── FR-KE-002.4: Sistem harus dapat generate nomor_virtual       │
│  │   untuk pembayaran via bank.                                    │
│  ├── FR-KE-002.5: Sistem harus dapat mengintegrasikan dengan       │
│  │   payment gateway (Midtrans) untuk pembayaran otomatis.         │
│  └── FR-KE-002.6: Sistem harus dapat mencatat split payment        │
│      (bayar beberapa kali untuk satu tagihan).                     │
│                                                                     │
│  FR-KE-003: Financial Reporting                                     │
│  ├── FR-KE-003.1: Sistem harus dapat menampilkan laporan           │
│  │   pemasukan per periode (harian, mingguan, bulanan).            │
│  ├── FR-KE-003.2: Sistem harus dapat menampilkan laporan           │
│  │   arus kas dengan kategori pemasukan dan pengeluaran.           │
│  ├── FR-KE-003.3: Sistem harus dapat menampilkan buku besar        │
│  │   per akun dengan detail transaksi.                             │
│  ├── FR-KE-003.4: Sistem harus dapat menampilkan neraca            │
│  │   sekolah dengan total asset dan liability.                     │
│  └── FR-KE-003.5: Sistem harus dapat mengekspor laporan            │
│      ke Excel dan PDF dengan format yang ditentukan.               │
│                                                                     │
│  FR-KE-004: Payment Notification                                     │
│  ├── FR-KE-004.1: Sistem harus dapat mengirim reminder WA          │
│  │   otomatis 7 hari sebelum jatuh tempo.                           │
│  ├── FR-KE-004.2: Sistem harus dapat mengirim konfirmasi WA        │
│  │   otomatis setelah pembayaran divalidasi.                        │
│  ├── FR-KE-004.3: Sistem harus dapat mengirim alert WA             │
│  │   untuk tunggakan lebih dari 30 hari.                           │
│  └── FR-KE-004.4: Sistem harus dapat mengirim notifikasi dalam     │
│      app untuk orang tua yang tidak punya WA aktif.                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 3.2.3 Modul Tahfiz (UNIQUE)

```
┌─────────────────────────────────────────────────────────────────────┐
│                   MODUL TAHFIZ - REQUIREMENTS                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FR-TH-001: Tahfiz Program Setup                                    │
│  ├── FR-TH-001.1: Sistem harus dapat mendefinisikan target         │
│  │   hafalan per semester per tingkat (juz atau halaman).          │
│  ├── FR-TH-001.2: Sistem harus dapat mengatur kurikulum tahfiz     │
│  │   (Tilawati, Iqro, Quran) dengan alur yang jelas.               │
│  ├── FR-TH-001.3: Sistem harus dapat menugaskan pembina tahfiz     │
│  │   ke kelas atau kelompok.                                       │
│  └── FR-TH-001.4: Sistem harus dapat membuat jadwal kegiatan       │
│      tahfiz dengan jam dan ruangan.                                 │
│                                                                     │
│  FR-TH-002: Hafalan Recording                                       │
│  ├── FR-TH-002.1: Sistem harus dapat menerima input setoran        │
│  │   hafalan dengan data: surah, ayat mulai-akhir, halaman.        │
│  ├── FR-TH-002.2: Sistem harus dapat memberikan penilaian          │
│  │   setoran dengan skala: Belum, Sedang, Lancar, Qira'ah Bagus.   │
│  ├── FR-TH-002.3: Sistem harus dapat mencatat penilaian tajwid     │
│  │   dan makhorijul huruf.                                         │
│  ├── FR-TH-002.4: Sistem harus dapat mencatat murajaah             │
│  │   (pengulangan) per siswa per periode.                          │
│  ├── FR-TH-002.5: Sistem harus dapat mencatat nilai ubudiyah       │
│  │   (sholat, adab) harian.                                        │
│  └── FR-TH-002.6: Sistem harus dapat mengirim notifikasi WA        │
│      otomatis ke orang tua setelah setoran hafalan.                 │
│                                                                     │
│  FR-TH-003: Progress Monitoring                                     │
│  ├── FR-TH-003.1: Sistem harus dapat menampilkan grafik            │
│  │   perkembangan hafalan per siswa dari waktu ke waktu.           │
│  ├── FR-TH-003.2: Sistem harus dapat menampilkan konsistensi       │
│  │   murajaah per siswa.                                           │
│  ├── FR-TH-003.3: Sistem harus dapat memberikan warning            │
│  │   jika siswa tidak mencapai target hafalan.                      │
│  └── FR-TH-003.4: Sistem harus dapat membandingkan progress        │
│      antar siswa dalam satu kelas.                                 │
│                                                                     │
│  FR-TH-004: Munaqosah Management                                    │
│  ├── FR-TH-004.1: Sistem harus dapat membuat jadwal munaqosah      │
│  │   dengan tanggal, jam, dan jumlah peserta.                       │
│  ├── FR-TH-004.2: Sistem harus dapat mencatat pendaftaran          │
│  │   siswa untuk munaqosah.                                        │
│  ├── FR-TH-004.3: Sistem harus dapat menerima input penilaian      │
│  │   dari juri dengan skala kelulusan.                             │
│  ├── FR-TH-004.4: Sistem harus dapat generate sertifikat           │
│  │   kelulusan munaqosah.                                          │
│  └── FR-TH-004.5: Sistem harus dapat menyimpan database hafalan    │
│      per siswa secara permanen.                                     │
│                                                                     │
│  FR-TH-005: Dashboard Tahfiz                                        │
│  ├── FR-TH-005.1: Sistem harus dapat menampilkan rekap progress     │
│  │   seluruh siswa (hafalan per juz).                              │
│  ├── FR-TH-005.2: Sistem harus dapat menampilkan statistik         │
│  │   kelulusan munaqosah per periode.                              │
│  ├── FR-TH-005.3: Sistem harus dapat menampilkan ranking           │
│  │   murajaah terbaik per kelas.                                   │
│  └── FR-TH-005.4: Sistem harus dapat mengekspor laporan tahfiz     │
│      per siswa per semester.                                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 3.2.4 Modul Inklusi/PDBK (UNIQUE)

```
┌─────────────────────────────────────────────────────────────────────┐
│                 MODUL INKLUSI/PDBK - REQUIREMENTS                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FR-IN-001: ABK Identification                                      │
│  ├── FR-IN-001.1: Sistem harus dapat melakukan screening awal      │
│  │   untuk siswa baru dengan kategori kebutuhan khusus.            │
│  ├── FR-IN-001.2: Sistem harus dapat menyimpan hasil observasi     │
│  │   dan asesmen awal.                                             │
│  ├── FR-IN-001.3: Sistem harus dapat memberikan rekomendasi        │
│  │   asesmen lanjutan oleh profesional.                            │
│  └── FR-IN-001.4: Sistem harus dapat mengelola database siswa      │
│      inklusi dengan privacy protection.                             │
│                                                                     │
│  FR-IN-002: Program Pembelajaran Individual (PPI)                   │
│  ├── FR-IN-002.1: Sistem harus dapat membuat PPI digital dengan    │
│  │   template standar yang sesuai regulasi.                        │
│  ├── FR-IN-002.2: Sistem harus dapat menetapkan target             │
│  │   perkembangan individual per semester.                         │
│  ├── FR-IN-002.3: Sistem harus dapat mendefinisikan strategi       │
│  │   pembelajaran dan akomodasi yang diperlukan.                   │
│  ├── FR-IN-002.4: Sistem harus dapat monitoring capaian PPI        │
│  │   dengan progress report berkala.                               │
│  ├── FR-IN-002.5: Sistem harus dapat workflow approval PPI         │
│  │   (GPK → Guru Mapel → Wali Kelas → Waka Kurikulum).             │
│  └── FR-IN-002.6: Sistem harus dapat mengarsipkan PPI per          │
│      tahun ajaran.                                                 │
│                                                                     │
│  FR-IN-003: GPK Integration                                         │
│  ├── FR-IN-003.1: Sistem harus dapat membuat jadwal                │
│  │   pendampingan GPK per siswa.                                   │
│  ├── FR-IN-003.2: Sistem harus dapat menerima catatan sesi         │
│  │   pendampingan dengan strategi yang digunakan.                  │
│  ├── FR-IN-003.3: Sistem harus dapat mendokumentasikan             │
│  │   hambatan belajar dan solusi yang diterapkan.                  │
│  └── FR-IN-003.4: Sistem harus dapat menghasilkan laporan          │
│      perkembangan untuk orang tua.                                 │
│                                                                     │
│  FR-IN-004: Psychological Assessment                                 │
│  ├── FR-IN-004.1: Sistem harus dapat menerima input hasil tes IQ   │
│  │   dengan norm skor.                                             │
│  ├── FR-IN-004.2: Sistem harus dapat menerima upload hasil         │
│  │   assessment psikolog dalam format digital.                     │
│  ├── FR-IN-004.3: Sistem harus dapat menyimpan hasil tes           │
│  │   bakat dan minat.                                              │
│  ├── FR-IN-004.4: Sistem harus dapat menampilkan grafik            │
│  │   perkembangan hasil asesmen dari waktu ke waktu.               │
│  └── FR-IN-004.5: Sistem harus dapat memberikan rekomendasi        │
│      layanan pembelajaran berdasarkan hasil asesmen.                │
│                                                                     │
│  FR-IN-005: Dashboard Inklusi                                       │
│  ├── FR-IN-005.1: Sistem harus dapat menampilkan statistik          │
│  │   siswa inklusi per jenis kebutuhan khusus.                     │
│  ├── FR-IN-005.2: Sistem harus dapat menampilkan progress          │
│  │   layanan per siswa.                                            │
│  ├── FR-IN-005.3: Sistem harus dapat menampilkan workload          │
│  │   GPK per periode.                                              │
│  └── FR-IN-005.4: Sistem harus dapat menampilkan heatmap           │
│      kebutuhan pelatihan GPK.                                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

#### 3.2.5 Modul Core & Platform (Superadmin)

```
┌─────────────────────────────────────────────────────────────────────┐
│               MODUL CORE & PLATFORM - REQUIREMENTS                 │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FR-SA-001: Onboarding Tenant Baru                                  │
│  ├── SA-01: Sistem harus dapat membuat tenant baru (sekolah/yayasan)│
│  │   melalui subdomain unik (misal: sekolah.simt.id).                │
│  ├── SA-01.1: Sistem secara otomatis menginisialisasi modul default │
│  │   (Core, Student, Attendance, Finance) setelah tenant dibuat.      │
│  └── SA-01.2: Sistem secara otomatis membuat akun administrator     │
│      default untuk tenant tersebut dengan role kepala_madrasah.     │
│                                                                     │
│  FR-SA-002: Manajemen Subskripsi & Modul                            │
│  ├── SA-02: Superadmin harus dapat mengaktifkan atau menonaktifkan  │
│  │   modul per-tenant sesuai dengan status subskripsi.              │
│  └── SA-02.1: Superadmin dapat merubah status operasional tenant    │
│      (prospect, contracted, active, suspended, terminated).         │
│                                                                     │
│  FR-SA-003: Dashboard Pemantauan Global                             │
│  └── SA-03: Sistem menyediakan dashboard global bagi Superadmin     │
│      untuk melihat statistik jumlah tenant, total user aktif, dan   │
│      status subskripsi tenant secara real-time.                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.3 Performance Requirements


```
┌─────────────────────────────────────────────────────────────────────┐
│                    PERFORMANCE REQUIREMENTS                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  PR-1: RESPONSE TIME                                                │
│  ├── PR-1.1: API response < 200ms untuk P95                       │
│  ├── PR-1.2: Page load < 3 detik untuk P95 (3G network)           │
│  ├── PR-1.3: Report generation < 30 detik untuk 1000 records      │
│  ├── PR-1.4: Bulk import 1000 records < 5 menit                   │
│  └── PR-1.5: Real-time notification delivery < 5 detik            │
│                                                                     │
│  PR-2: CAPACITY                                                     │
│  ├── PR-2.1: Support 500+ concurrent users per instance           │
│  ├── PR-2.2: Database capacity 50.000+ students per sekolah       │
│  ├── PR-2.3: File storage capacity 10GB per sekolah               │
│  └── PR-2.4: API throughput 1000 req/min per instance             │
│                                                                     │
│  PR-3: ACCURACY                                                     │
│  ├── PR-3.1: Nilai calculation accuracy: 100% (automated tests)   │
│  ├── PR-3.2: Financial transaction accuracy: 100%                 │
│  ├── PR-3.3: Data sync accuracy with EMIS: 99%                    │
│  └── PR-3.4: Report data accuracy: match source data              │
│                                                                     │
│  PR-4: RELIABILITY                                                   │
│  ├── PR-4.1: System availability: 99.5% (excluding maintenance)   │
│  ├── PR-4.2: Error rate: < 0.1% of all operations                 │
│  ├── PR-4.3: Data loss prevention: 0% for committed transactions  │
│  └── PR-4.4: Recovery time from failure: < 4 hours                │
│                                                                     │
│  PR-5: SCALABILITY                                                   │
│  ├── PR-5.1: Horizontal scaling: Add web nodes without downtime   │
│  ├── PR-5.2: Database scaling: Read replicas for load distribution │
│  ├── PR-5.3: Cache scaling: Redis cluster for session/query cache │
│  └── PR-5.4: CDN scaling: Static asset caching globally           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.4 Design Constraints

```
┌─────────────────────────────────────────────────────────────────────┐
│                      DESIGN CONSTRAINTS                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  DC-1: ARCHITECTURE                                                  │
│  ├── DC-1.1: Web application dengan SPA (Single Page Application)  │
│  ├── DC-1.2: Server-side rendering untuk landing page dan SEO      │
│  ├── DC-1.3: Microservices-ready architecture untuk scalability    │
│  └── DC-1.4: API-first design untuk integrasi third-party          │
│                                                                     │
│  DC-2: DATABASE                                                      │
│  ├── DC-2.1: Relational database (PostgreSQL) untuk data utama     │
│  ├── DC-2.2: Document store (Elasticsearch) untuk pencarian        │
│  ├── DC-2.3: Time-series database (InfluxDB) untuk analytics       │
│  └── DC-2.4: Object storage (S3) untuk file dan dokumen            │
│                                                                     │
│  DC-3: SECURITY                                                      │
│  ├── DC-3.1: OWASP Top 10 compliant                                │
│  ├── DC-3.2: GDPR/UU PDP compliant untuk data pribadi             │
│  ├── DC-3.3: PCI-DSS compliant untuk data pembayaran               │
│  └── DC-3.4: Annual penetration testing                             │
│                                                                     │
│  DC-4: ACCESSIBILITY                                                │
│  ├── DC-4.1: WCAG 2.1 AA compliance minimum                       │
│  ├── DC-4.2: Keyboard navigation support                            │
│  ├── DC-4.3: Screen reader compatible                              │
│  └── DC-4.4: Sufficient color contrast ratio (4.5:1 minimum)       │
│                                                                     │
│  DC-5: INTERNATIONALIZATION                                         │
│  ├── DC-5.1: Bahasa Indonesia sebagai default                       │
│  ├── DC-5.2: i18n ready untuk multi-language support               │
│  └── DC-5.3: Unicode support untuk karakter Indonesia              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.5 Software System Attributes

```
┌─────────────────────────────────────────────────────────────────────┐
│                 SOFTWARE SYSTEM ATTRIBUTES                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  SSA-1: MAINTAINABILITY                                             │
│  ├── SSA-1.1: Modular architecture dengan clear boundaries         │
│  ├── SSA-1.2: API documentation dengan OpenAPI 3.0                 │
│  ├── SSA-1.3: Code quality standards (ESLint, PHPStan)            │
│  ├── SSA-1.4: Automated testing dengan >80% coverage               │
│  ├── SSA-1.5: CI/CD pipeline untuk automated deployment           │
│  └── SSA-1.6: Technical documentation yang lengkap                 │
│                                                                     │
│  SSA-2: AVAILABILITY                                                │
│  ├── SSA-2.1: Target uptime: 99.5%                                 │
│  ├── SSA-2.2: Load balancer untuk high availability                │
│  ├── SSA-2.3: Database replication (primary + read replicas)       │
│  ├── SSA-2.4: Automated health monitoring dan alerting             │
│  ├── SSA-2.5: Graceful degradation untuk component failures        │
│  └── SSA-2.6: Scheduled maintenance dengan notification            │
│                                                                     │
│  SSA-3: SECURITY                                                     │
│  ├── SSA-3.1: Encryption at-rest (AES-256) untuk data sensitif    │
│  ├── SSA-3.2: SSL/TLS untuk semua connections                      │
│  ├── SSA-3.3: JWT authentication dengan short expiry               │
│  ├── SSA-3.4: RBAC dengan granular permissions                     │
│  ├── SSA-3.5: Rate limiting untuk API endpoints                    │
│  ├── SSA-3.6: Audit logging untuk semua write operations           │
│  └── SSA-3.7: 2FA untuk admin and privileged accounts              │
│                                                                     │
│  SSA-4: PORTABILITY                                                 │
│  ├── SSA-4.1: Container-based deployment (Docker)                  │
│  ├── SSA-4.2: Cloud-agnostic (AWS, GCP, Azure, On-premise)         │
│  ├── SSA-4.3: Standard protocols (HTTP, REST, JSON)               │
│  └── SSA-4.4: Export data dalam format terbuka (CSV, JSON, XML)    │
│                                                                     │
│  SSA-5: USABILITY                                                   │
│  ├── SSA-5.1: Mobile-first responsive design                       │
│  ├── SSA-5.2: Intuitive UI dengan minimal training required        │
│  ├── SSA-5.3: Onboarding flow untuk new users                      │
│  ├── SSA-5.4: In-app help dan tooltips                             │
│  ├── SSA-5.5: Video tutorial untuk fitur complex                   │
│  └── SSA-5.6: Consistent design language throughout                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 4. VERIFICATION

### 4.1 Acceptance Criteria

```
┌─────────────────────────────────────────────────────────────────────┐
│                      ACCEPTANCE CRITERIA                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  AC-1: FUNCTIONAL ACCEPTANCE                                        │
│  ├── AC-1.1: Semua functional requirements diuji dan passed        │
│  ├── AC-1.2: Semua user roles dapat login dan akses sesuai RBAC   │
│  ├── AC-1.3: Semua CRUD operations berfungsi dengan benar          │
│  ├── AC-1.4: Semua integrasi (EMIS, RDM, WhatsApp) berfungsi       │
│  └── AC-1.5: Tidak ada blocking bugs pada saat acceptance          │
│                                                                     │
│  AC-2: PERFORMANCE ACCEPTANCE                                       │
│  ├── AC-2.1: API response time < 200ms (P95) dalam load test      │
│  ├── AC-2.2: Page load time < 3s dalam kondisi normal              │
│  ├── AC-2.3: System tetap responsive dengan 100 concurrent users   │
│  └── AC-2.4: Report generation < 30 detik untuk 1000 records      │
│                                                                     │
│  AC-3: SECURITY ACCEPTANCE                                          │
│  ├── AC-3.1: SSL certificate valid dan properly configured         │
│  ├── AC-3.2: Authentication tidak bisa di-bypass                    │
│  ├── AC-3.3: RBAC enforced untuk semua resources                   │
│  ├── AC-3.4: No critical vulnerabilities dalam security scan       │
│  └── AC-3.5: Audit trail recorded untuk semua write operations     │
│                                                                     │
│  AC-4: USABILITY ACCEPTANCE                                         │
│  ├── AC-4.1: User dapat menyelesaikan basic tasks tanpa training   │
│  ├── AC-4.2: Navigasi konsisten dan intuitive                      │
│  ├── AC-4.3: Mobile view berfungsi dengan baik                     │
│  ├── AC-4.4: Error messages jelas dan helpful                      │
│  └── AC-4.5: Accessibility standards compliant                      │
│                                                                     │
│  AC-5: DATA INTEGRITY ACCEPTANCE                                    │
│  ├── AC-5.1: Data yang diinput tersimpan dengan benar              │
│  ├── AC-5.2: Perhitungan nilai akurat sesuai formula               │
│  ├── AC-5.3: Export ke RDM sesuai format                           │
│  ├── AC-5.4: Backup dan restore berfungsi dengan benar             │
│  └── AC-5.5: Tidak ada data loss dalam kondisi normal              │
│                                                                     │
│  AC-6: DOCUMENTATION ACCEPTANCE                                     │
│  ├── AC-6.1: User manual tersedia dalam Bahasa Indonesia           │
│  ├── AC-6.2: API documentation up-to-date                          │
│  ├── AC-6.3: Deployment guide tersedia                             │
│  └── AC-6.4: Technical documentation lengkap untuk maintenance     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 5. APPENDICES

### 5.1 Issue Tracking

```
┌─────────────────────────────────────────────────────────────────────┐
│                      ISSUE TRACKING LOG                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  | Issue ID | Date | Description | Priority | Status | Owner |     │
│  |----------|------|-------------|----------|--------|-------|     │
│  |          |      |             |          |        |       |     │
│  (To be maintained throughout development lifecycle)               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.2 Change Log

```
┌─────────────────────────────────────────────────────────────────────┐
│                        CHANGE LOG                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  | Version | Date | Author | Changes |                             │
│  |---------|------|--------|---------|                             │
│  | 1.0     |Date  | Name   | Initial version |                     │
│  |         |      |        |                 |                     │
│  (To be maintained throughout project lifecycle)                   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*
*This SRS conforms to IEEE Std 830-1998*