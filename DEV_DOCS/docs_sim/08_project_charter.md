# PROJECT CHARTER
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs
### Versi Lean MVP - Budget Terbatas

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** APPROVED  
**Sponsor:** [Nama Yayasan/Sponsor]  
**Project Manager:** [Nama PM]

---

## 1. PROJECT OVERVIEW

### 1.1 Project Name
**SIMT MTs - Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah**

### 1.2 Project Type
Pengembangan Sistem Informasi Sekolah berbasis Web untuk Madrasah Tsanawiyah (MTs/SMP Islamic) dengan pendekatan **SaaS (Software as a Service)** multi-tenant.

### 1.3 Project Justification

```
┌─────────────────────────────────────────────────────────────────────┐
│                        JUSTIFIKASI PROYEK                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  BUSINESS PROBLEM:                                                  │
│  ├── Proses administrasi sekolah masih manual (Excel, kertas)      │
│  ├── Data tersebar di banyak tempat, tidak konsisten               │
│  ├── Orang tua tidak punya visibilitas terhadap progress anak      │
│  ├── Laporan untuk manajemen sering telat dan tidak akurat          │
│  └── Tidak ada sistem terintegrasi yang support fitur MTs/Islamic  │
│                                                                     │
│  SOLUTION:                                                          │
│  ├── Digitalisasi seluruh proses administrasi sekolah              │
│  ├── Single source of truth untuk data                             │
│  ├── Portal orang tua dengan notifikasi WhatsApp                   │
│  ├── Dashboard real-time untuk pengambilan keputusan                │
│  └── Sistem scalable untuk multi-tenant (yayasan dengan banyak MTs)│
│                                                                     │
│  BENEFITS:                                                          │
│  ├── Efisiensi waktu input data: -60%                              │
│  ├── Akurasi laporan: +95%                                         │
│  ├── Engagement orang tua: +80%                                    │
│  └── Decision-making berbasis data: +100%                          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. PROJECT SCOPE

### 2.1 In-Scope (MVP 3 Bulan)

```
┌─────────────────────────────────────────────────────────────────────┐
│                      MVP SCOPE (3 BULAN)                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ═══ USER MANAGEMENT ═══                                           │
│  ├── Authentication (login, logout, password reset)                │
│  ├── Role-based access (15 role simplified to 6)                   │
│  └── User profile management                                        │
│                                                                     │
│  ═══ AKADEMIK CORE ═══                                             │
│  ├── Student biodata (CRUD, import)                                │
│  ├── Class/Rombel management                                        │
│  ├── Subject management                                             │
│  ├── Schedule (manual input)                                        │
│  ├── Attendance recording (daily)                                  │
│  ├── Assessment & scoring (formatif, sumatif)                      │
│  └── E-Rapor (PDF export)                                          │
│                                                                     │
│  ═══ FINANCE ═══                                                   │
│  ├── Billing component setup (SPP, dll)                            │
│  ├── Student bill generation                                        │
│  └── Payment recording & validation                                 │
│                                                                     │
│  ═══ PORTAL & NOTIFICATION ═══                                     │
│  ├── Parent portal (dashboard nilai anak)                          │
│  └── WhatsApp notification (kehadiran, nilai dasar)                │
│                                                                     │
│  ═══ REPORTING ═══                                                 │
│  └── Dashboard kepala sekolah (basic KPI)                          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.2 Out-of-Scope (Next Phase)

```
┌─────────────────────────────────────────────────────────────────────┐
│                   OUT OF SCOPE (NEXT PHASE)                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FEATURES:                                                          │
│  ├── Modul Tahfiz (monitoring hafalan, munaqosah)                  │
│  ├── Modul Inklusi/PDBK (ABK, PPI, GPK)                           │
│  ├── Modul BK/Konseling lengkap                                    │
│  ├── EMIS/DAPODIK integration (API)                                │
│  ├── RDM integration (API)                                         │
│  ├── Payment gateway (Midtrans)                                    │
│  ├── Mobile app (iOS/Android)                                      │
│  ├── Advanced analytics                                             │
│  └── Multi-tenant untuk multiple schools                           │
│                                                                     │
│  INTEGRATIONS:                                                      │
│  ├── Fingerprint device                                             │
│  ├── QR code attendance                                             │
│  ├── E-learning/LMS                                                 │
│  └── Online examination                                            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 3. PROJECT OBJECTIVES

### 3.1 Primary Objectives (SMART)

```
┌─────────────────────────────────────────────────────────────────────┐
│                      PRIMARY OBJECTIVES                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  OBJ-1: Sistem Terintegrasi                                        │
│  └── Develop web-based school management system yang terintegrasi   │
│      dengan minimum 6 modul: Akademik, Keuangan, Presensi,         │
│      Pelaporan, Portal Orang Tua, Dashboard Kepala Sekolah          │
│      dalam waktu 3 bulan                                            │
│                                                                     │
│  OBJ-2: Pilot Operational                                          │
│  └── Implementasi dan pengoperasian sistem di 1 MTs pilot           │
│      dengan 125+ user (20 guru, 4 TU, 1 kepala, 1 BK, 100 siswa)   │
│      dengan uptime minimal 99% dalam 1 bulan after go-live          │
│                                                                     │
│  OBJ-3: User Adoption                                              │
│  └── Minimal 80% guru dan TU dapat menggunakan sistem               │
│      untuk input data harian tanpa training ulang                   │
│      dalam 2 minggu setelah go-live                                 │
│                                                                     │
│  OBJ-4: Cost Efficiency                                            │
│  └── Selesaikan pengembangan MVP dengan budget maksimal Rp 5jt      │
│      diluar biaya operasional hosting (Rp 300rb/bulan)             │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Success Metrics

```
┌─────────────────────────────────────────────────────────────────────┐
│                      SUCCESS METRICS                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  TECHNICAL METRICS:                                                 │
│  ├── System uptime: ≥ 99%                                          │
│  ├── Page load time: ≤ 3 detik (P95)                              │
│  ├── API response time: ≤ 200ms (P95)                             │
│  └── Critical bugs: 0 (at launch)                                  │
│                                                                     │
│  ADOPTION METRICS:                                                  │
│  ├── Login rate: ≥ 90% (weekly)                                    │
│  ├── Daily active users: ≥ 60%                                     │
│  ├── Data entry completion: ≥ 95% (weekly)                        │
│  └── Parent engagement: ≥ 50% (first month)                        │
│                                                                     │
│  BUSINESS METRICS:                                                  │
│  ├── Pilot satisfaction: ≥ 4/5                                     │
│  ├── Intent to continue: ≥ 80%                                     │
│  └── Ready for scale: Yes                                          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 4. STAKEHOLDER REGISTER

### 4.1 Internal Stakeholders

```
┌─────────────────────────────────────────────────────────────────────┐
│                    INTERNAL STAKEHOLDERS                            │
├──────────────────┬────────────────────┬─────────────────────────────┤
│ Stakeholder     │ Role in Project    │ Expectations                │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Sponsor/Yayasan │ Budget provider    │ ROI, scalability, control   │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Kepala Madrasah │ Decision maker     │ Dashboard, laporan, kontrol │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Waka Kurikulum  │ Process owner      │ Akademik data, rapor       │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Guru (20 org)   │ End user           │ Mudah digunakan, efisien   │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ TU (4 org)      │ Data entry operator│ Simple, accurate, fast     │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Guru BK (1)     │ End user           │ Data siswa, laporan        │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Siswa (100)     │ End user           │ Jadwal, nilai (view only)  │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Orang Tua       │ Stakeholder        │ Info anak, notifikasi       │
└──────────────────┴────────────────────┴─────────────────────────────┘
```

### 4.2 External Stakeholders

```
┌─────────────────────────────────────────────────────────────────────┐
│                    EXTERNAL STAKEHOLDERS                            │
├──────────────────┬────────────────────┬─────────────────────────────┤
│ Stakeholder     │ Role in Project    │ Expectations                │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Kemenag (EMIS)  │ Reporting target   │ Data-compliant format       │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Kemdikdasmen    │ Reporting target   │ Dapodik compatible          │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Orang Tua       │ End user           │ Real-time info, easy access │
├──────────────────┼────────────────────┼─────────────────────────────┤
│ Green API       │ Service provider   │ WA integration              │
└──────────────────┴────────────────────┴─────────────────────────────┘
```

---

## 5. MILESTONES & DELIVERABLES

### 5.1 Project Milestones

```
┌─────────────────────────────────────────────────────────────────────┐
│                       PROJECT MILESTONES                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  M1: PROJECT KICKOFF (Week 1)                                      │
│  ├── Deliverable: Project charter signed                           │
│  ├── Deliverable: Development environment ready                    │
│  └── Success Criteria: Team aligned, tools ready                   │
│                                                                     │
│  M2: CORE FOUNDATION (Week 4)                                      │
│  ├── Deliverable: User management & auth system                    │
│  ├── Deliverable: Student & class management                       │
│  ├── Deliverable: Basic attendance system                          │
│  └── Success Criteria: Core CRUD operations working                │
│                                                                     │
│  M3: AKADEMIK COMPLETE (Week 8)                                    │
│  ├── Deliverable: Assessment & scoring system                      │
│  ├── Deliverable: E-Rapor (PDF export)                            │
│  ├── Deliverable: Schedule display                                 │
│  └── Success Criteria: Guru dapat input nilai dan generate rapor   │
│                                                                     │
│  M4: FINANCE & PORTAL (Week 10)                                    │
│  ├── Deliverable: Billing system                                   │
│  ├── Deliverable: Payment recording                                │
│  ├── Deliverable: Parent portal                                    │
│  ├── Deliverable: WhatsApp notification                            │
│  └── Success Criteria: Orang tua dapat lihat data anak             │
│                                                                     │
│  M5: PILOT GO-LIVE (Week 12)                                       │
│  ├── Deliverable: System deployed dan operational                  │
│  ├── Deliverable: User training complete                           │
│  ├── Deliverable: UAT passed                                       │
│  └── Success Criteria: 80% user adoption dalam 2 minggu            │
│                                                                     │
│  M6: STABILIZATION (Week 14)                                       │
│  ├── Deliverable: All critical bugs fixed                          │
│  ├── Deliverable: Performance optimized                            │
│  ├── Deliverable: Documentation complete                           │
│  └── Success Criteria: System stable, user satisfied               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.2 Timeline Overview

```
Bulan 1          Bulan 2          Bulan 3          Minggu 13-14
├───────────────│───────────────│───────────────│───────────────│
│               │               │               │               │
│ ████ M1-M2   │ ████████ M3   │ ████████ M4   │ ██████ M5-M6  │
│ Foundation    │ Akademik      │ Finance+Portal│ Go-Live       │
│ + Core CRUD   │ + Scoring     │ + WA Notif    │ + Stabilize   │
│               │ + E-Rapor     │ + Dashboard   │               │
│               │               │               │               │
│ [DEVELOPMENT] │               │               │ [PILOT]       │
└───────────────┴───────────────┴───────────────┴───────────────┘
```

---

## 6. BUDGET

### 6.1 Budget Breakdown

```
┌─────────────────────────────────────────────────────────────────────┐
│                          BUDGET                                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  TOTAL BUDGET: Rp 5.000.000                                        │
│                                                                     │
│  ═══ DEVELOPMENT COSTS ═══                                         │
│  ├── Tools & Software:     Rp 500.000                              │
│  │   ├── Domain (year):       Rp 200.000                           │
│  │   ├── SSL Certificate:     Rp 100.000                           │
│  │   └── Development tools:   Rp 200.000                           │
│  │                                                             │
│  ├── Services & Contingency:Rp 2.500.000                           │
│  │   ├── API services (WA):   Rp 500.000 (Green API trial)        │
│  │   ├── Design assets:       Rp 300.000                           │
│  │   └── Contingency:         Rp 1.700.000                         │
│  │                                                             │
│  ═══ OPERATIONAL COSTS ═══                                         │
│  └── Monthly Hosting: Rp 300.000 x 3 bulan = Rp 900.000           │
│      (Separate from 5jt budget - ditanggung operasional)           │
│                                                                     │
│  ═══ SWEAT EQUITY (No Cost) ═══                                    │
│  ├── Development: Developer (founder/sweat equity)                 │
│  ├── Project Management: PM (founder/sweat equity)                 │
│  ├── Testing: Team (included in development)                       │
│  └── Documentation: PM (included in project time)                  │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 6.2 Cost Saving Strategies

```
┌─────────────────────────────────────────────────────────────────────┐
│                    COST SAVING STRATEGIES                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  TECHNOLOGY:                                                        │
│  ├── Use open-source stack (Laravel/PHP, Vue.js - free)            │
│  ├── Use free-tier cloud services untuk development                │
│  ├── Use shared hosting initially (upgrade later)                   │
│  └── Use open-source libraries (no licensing)                      │
│                                                                     │
│  DEVELOPMENT:                                                       │
│  ├── Lean methodology (Agile, small sprints)                       │
│  ├── Focus on MVP only (no gold-plating)                           │
│  ├── Reuse components dari open-source                             │
│  ├── Sweat equity dari founder (belum bergaji)                      │
│  └── No dedicated designer (use free templates)                    │
│                                                                     │
│  OPERATIONAL:                                                       │
│  ├── Start with minimal VPS (upgrade when revenue)                 │
│  ├── No dedicated DevOps (developer handles)                       │
│  ├── Use managed services (reduce maintenance)                      │
│  └── Bootstrap marketing (no paid ads initially)                   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 7. RISKS & MITIGATIONS

### 7.1 High Priority Risks

```
┌─────────────────────────────────────────────────────────────────────┐
│                     HIGH PRIORITY RISKS                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  R01: Development Timeline Delay                                    │
│  ├── Probability: MEDIUM │ Impact: HIGH                            │
│  ├── Mitigation:                                                         │
│  │   ├── Prioritize ruthlessly (MVP only)                          │
│  │   ├── Daily standups untuk early detection                       │
│  │   ├── Reduce scope if needed (tanggal 2 minggu sebelum deadline) │
│  │   └── Sweat equity - lebih jam kerja jika perlu                  │
│  └── Contingency: Extend timeline atau reduce features              │
│                                                                     │
│  R02: Technical Complexity Underestimated                            │
│  ├── Probability: MEDIUM │ Impact: HIGH                            │
│  ├── Mitigation:                                                         │
│  │   ├── Spike research untuk area uncertain                       │
│  │   ├── Break down complex features                              │
│  │   ├── Use proven technology stack                               │
│  │   └── Get early feedback from pilot user                        │
│  └── Contingency: Simplify feature atau use third-party            │
│                                                                     │
│  R03: User Adoption Low                                             │
│  ├── Probability: MEDIUM │ Impact: HIGH                            │
│  ├── Mitigation:                                                         │
│  │   ├── UX research di awal (bicara dengan guru)                  │
│  │   ├── Simple, intuitive interface                               │
│  │   ├── Comprehensive training                                    │
│  │   └── Change champion di setiap role                            │
│  └── Contingency: Additional training, UI redesign                 │
│                                                                     │
│  R04: Budget Overrun                                                │
│  ├── Probability: LOW │ Impact: HIGH                               │
│  ├── Mitigation:                                                         │
│  │   ├── Track expenses weekly                                     │
│  │   ├── Fixed scope (no change requests yang menambah cost)       │
│  │   └── Prioritize spending (hosting > tools > contingency)       │
│  └── Contingency: Reduce scope, extend timeline                    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 7.2 Risk Summary Matrix

```
┌─────────────────────────────────────────────────────────────────────┐
│                    RISK SUMMARY MATRIX                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                         Impact                                      │
│              Low       Medium      High      Very High              │
│         ┌────────┬────────┬────────┬────────┐                     │
│   High  │   -    │   -    │ R01    │   -    │                     │
│         ├────────┼────────┼────────┼────────┤                     │
│  Medium │   -    │   -    │ R03    │ R02    │                     │
│         ├────────┼────────┼────────┼────────┤                     │
│   Low   │   -    │ R04    │   -    │   -    │                     │
│         └────────┴────────┴────────┴────────┘                     │
│                       Probability                                    │
│                                                                     │
│  R01 = Timeline Delay | R02 = Technical Complexity                 │
│  R03 = Low Adoption   | R04 = Budget Overrun                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 8. GOVERNANCE

### 8.1 Project Organization

```
┌─────────────────────────────────────────────────────────────────────┐
│                    PROJECT ORGANIZATION                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                    ┌─────────────────┐                              │
│                    │    SPONSOR      │                              │
│                    │   (Yayasan)     │                              │
│                    └────────┬────────┘                              │
│                             │                                       │
│                             │                                       │
│                    ┌────────┴────────┐                              │
│                    │   PROJECT       │                              │
│                    │    MANAGER      │                              │
│                    └────────┬────────┘                              │
│                             │                                       │
│              ┌──────────────┼──────────────┐                       │
│              │              │              │                       │
│              ▼              ▼              ▼                       │
│     ┌────────────┐  ┌────────────┐  ┌────────────┐               │
│     │ DEVELOPER  │  │    QA      │  │  PRODUCT   │               │
│     │  (Lead)    │  │  (Part-time│  │   OWNER    │               │
│     │            │  │  兼 PM)    │  │ (兼 PM)    │               │
│     └────────────┘  └────────────┘  └────────────┘               │
│                                                                     │
│  Note: Small team - many roles are combined (sweat equity)         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Decision Making

```
┌─────────────────────────────────────────────────────────────────────┐
│                      DECISION MAKING                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  DECISIONS MADE BY PROJECT MANAGER (Day-to-day):                   │
│  ├── Sprint planning dan task assignment                           │
│  ├── Technical decisions dalam scope                               │
│  ├── Timeline adjustments minor                                    │
│  └── Resource allocation                                            │
│                                                                     │
│  DECISIONS ESCALATED TO SPONSOR (Major):                          │
│  ├── Scope changes (add/remove features)                          │
│  ├── Budget changes > 10%                                          │
│  ├── Timeline extension > 2 weeks                                  │
│  └── Go/no-go untuk pilot launch                                   │
│                                                                     │
│  DECISION VELOCITY:                                                 │
│  ├── Routine decisions: < 24 hours                                 │
│  ├── Technical decisions: < 48 hours                               │
│  └── Major decisions: < 1 week                                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 9. COMMUNICATION PLAN (SUMMARY)

### 9.1 Meeting Cadence

```
┌─────────────────────────────────────────────────────────────────────┐
│                       MEETING CADENCE                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  DAILY (Development Team):                                         │
│  └── Async standup via chat (5-10 menit)                           │
│                                                                     │
│  WEEKLY (Team + Sponsor):                                          │
│  ├── Demo progress (Friday, 30 menit)                              │
│  ├── Decisions needed list                                          │
│  └── Blockers & support required                                   │
│                                                                     │
│  BI-WEEKLY (Steering - if needed):                                 │
│  └── Review milestone progress, major decisions                    │
│                                                                     │
│  AD-HOC:                                                            │
│  └── Urgent issues: langsung chat/call                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 9.2 Reporting

```
┌─────────────────────────────────────────────────────────────────────┐
│                        REPORTING                                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  WEEKLY STATUS REPORT (to Sponsor):                                │
│  ├── Progress (sprint velocity)                                    │
│  ├── Issues & blockers                                              │
│  ├── Budget status                                                  │
│  ├── Next week plan                                                 │
│  └── Risk update                                                    │
│                                                                     │
│  FORMAT: Simple email or chat message (no fancy PPT)               │
│  TIMING: Every Friday                                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 10. ASSUMPTIONS & CONSTRAINTS

### 10.1 Assumptions

```
┌─────────────────────────────────────────────────────────────────────┐
│                        ASSUMPTIONS                                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  A1: Tim development memiliki skill yang sesuai (full-stack)       │
│  A2: Sponsor menyediakan access ke data sekolah untuk development  │
│  A3: Pilot school bersedia menjadi early adopter                    │
│  A4: Pilot school memiliki minimum 1 komputer dengan internet       │
│  A5: User memiliki basic computer literacy                          │
│  A6: 3 bulan timeline adalah fix (tidak ada extension)              │
│  A7: Budget 5jt adalah maximum (tidak ada tambahan)                 │
│  A8: Teknologi stack (Laravel + Vue) adalah tepat                  │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 10.2 Constraints

```
┌─────────────────────────────────────────────────────────────────────┐
│                        CONSTRAINTS                                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  BUDGET: Maximum Rp 5.000.000 untuk development                     │
│  TIMELINE: 3 bulan (12 weeks) sampai pilot go-live                  │
│  RESOURCES: Small team (1-2 developers, sweat equity)               │
│  SCOPE: MVP only - no advanced features                             │
│  INFRASTRUCTURE: Minimal (1 VPS initially)                          │
│  STAKEHOLDER: Single pilot school (no multi-school)                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 11. APPROVALS

```
┌─────────────────────────────────────────────────────────────────────┐
│                       APPROVALS                                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Sponsor:                                                          │
│  Name: _______________________                                     │
│  Date: _______________________                                     │
│  Signature: _______________________                                │
│                                                                     │
│  ──────────────────────────────────────────────────────────────    │
│                                                                     │
│  Project Manager:                                                  │
│  Name: _______________________                                     │
│  Date: _______________________                                     │
│  Signature: _______________________                                │
│                                                                     │
│  ──────────────────────────────────────────────────────────────    │
│                                                                     │
│  Head of Pilot School (Stakeholder Representative):                 │
│  Name: _______________________                                     │
│  Date: _______________________                                     │
│  Signature: _______________________                                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## APPENDIX: SPRINT SUMMARY (MVP)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SPRINT SUMMARY (MVP)                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  SPRINT 1 (Weeks 1-2): Foundation                                   │
│  ├── Setup project, auth, user management                          │
│  └── Target: Core authentication working                           │
│                                                                     │
│  SPRINT 2 (Weeks 3-4): Student Core                                │
│  ├── Student CRUD, class management                                │
│  └── Target: Student data manageable                               │
│                                                                     │
│  SPRINT 3 (Weeks 5-6): Attendance & Schedule                       │
│  ├── Daily attendance, schedule display                            │
│  └── Target: Attendance recorded daily                              │
│                                                                     │
│  SPRINT 4 (Weeks 7-8): Assessment                                  │
│  ├── Scoring system, grade calculation                              │
│  └── Target: Guru dapat input dan hitung nilai                      │
│                                                                     │
│  SPRINT 5 (Weeks 9-10): Reporting                                  │
│  ├── E-Rapor PDF, dashboard kepala sekolah                         │
│  └── Target: Laporan bisa di-generate                              │
│                                                                     │
│  SPRINT 6 (Weeks 11-12): Finance & Portal                          │
│  ├── Billing, payment, parent portal, WA notification              │
│  └── Target: MVP complete, ready for pilot                         │
│                                                                     │
│  WEEKS 13-14: BUFFER & PILOT LAUNCH                                │
│  ├── Bug fixes, optimization, user training                        │
│  └── Target: System live dan operational                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*
*Budget: Rp 5.000.000 | Timeline: 3 Bulan*