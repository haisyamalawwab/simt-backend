# ROADMAP, TASK & SPRINT PLANNING
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs/YAYASAN

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** DRAFT  
**Penulis:** Tim Proyek SIMT MTs

---

## BAGIAN 1: PROJECT ROADMAP (18 BULAN)

### 1.1 Overview Timeline

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           PROJECT TIMELINE - 18 BULAN                          │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│  ╔═══════════════════════════════════════════════════════════════════════════╗ │
│  ║                     PHASE 1: FOUNDATION (Bulan 1-3)                      ║ │
│  ╠═══════════════════════════════════════════════════════════════════════════╣ │
│  ║  Month 1          Month 2          Month 3                              ║ │
│  ║  ├───────────────│───────────────│───────────────│                      ║ │
│  ║  │ Planning       │ Design        │ Development   │                      ║ │
│  ║  │ ├─ Requirements│ ├─ Wireframes │ ├─ Setup      │                      ║ │
│  ║  │ ├─ Architecture│ ├─ UI/UX      │ ├─ Core       │                      ║ │
│  ║  │ └─ Team setup  │ └─ DB Schema  │ └─ Auth       │                      ║ │
│  ║  └───────────────┴───────────────┴───────────────┘                      ║ │
│  ╚═══════════════════════════════════════════════════════════════════════════╝ │
│                                                                                 │
│  ╔═══════════════════════════════════════════════════════════════════════════╗ │
│  ║                   PHASE 2: MVP DEVELOPMENT (Bulan 4-9)                    ║ │
│  ╠═══════════════════════════════════════════════════════════════════════════╣ │
│  ║  Month 4     Month 5     Month 6     Month 7     Month 8     Month 9     ║ │
│  ║  ├──────────│──────────│──────────│──────────│──────────│──────────│      ║ │
│  ║  │Sprint 1  │Sprint 2  │Sprint 3  │Sprint 4  │Sprint 5  │Sprint 6  │      ║ │
│  ║  │Akademik  │Akademik  │Keuangan  │Integrasi │Portal    │Dashboard │      ║ │
│  ║  │Core      │Advanced  │Billing   │EMIS/RDM  │Orang Tua │Kepala    │      ║ │
│  ║  └──────────┴──────────┴──────────┴──────────┴──────────┴──────────┘      ║ │
│  ║                                                                        ║ │
│  ║  ═══════════════════════════════════════════════════════════════════      ║ │
│  ║  PILOT LAUNCH (Month 9) - 1-2 MTs testing                              ║ │
│  ╚═══════════════════════════════════════════════════════════════════════════╝ │
│                                                                                 │
│  ╔═══════════════════════════════════════════════════════════════════════════╗ │
│  ║                  PHASE 3: ENHANCEMENT (Bulan 10-14)                      ║ │
│  ╠═══════════════════════════════════════════════════════════════════════════╣ │
│  ║  Month 10    Month 11    Month 12    Month 13    Month 14              ║ │
│  ║  ├──────────│──────────│──────────│──────────│──────────│               ║ │
│  ║  │Tahfiz    │Inklusi   │BK        │E-Office  │Multi-    │               ║ │
│  ║  │Module    │Module    │Module    │Module    │tenant    │               ║ │
│  ║  └──────────┴──────────┴──────────┴──────────┴──────────┘               ║ │
│  ╚═══════════════════════════════════════════════════════════════════════════╝ │
│                                                                                 │
│  ╔═══════════════════════════════════════════════════════════════════════════╗ │
│  ║                   PHASE 4: SCALE & LAUNCH (Bulan 15-18)                  ║ │
│  ╠═══════════════════════════════════════════════════════════════════════════╣ │
│  ║  Month 15    Month 16    Month 17    Month 18                           ║ │
│  ║  ├──────────│──────────│──────────│──────────│                           ║ │
│  ║  │Mobile App│Mobile App│Marketing │Commercial│                           ║ │
│  ║  │iOS       │Android   │& Sales   │Launch    │                           ║ │
│  ║  └──────────┴──────────┴──────────┴──────────┘                           ║ │
│  ╚═══════════════════════════════════════════════════════════════════════════╝ │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 1.2 Milestone Breakdown

```
┌─────────────────────────────────────────────────────────────────────┐
│                        MILESTONE TIMELINE                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M1: PROJECT KICKOFF (Week 1-2)                                │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ Team formation complete                                     │ │
│  │ ✓ Project charter signed                                     │ │
│  │ ✓ Communication plan established                             │ │
│  │ ✓ Development environment ready                              │ │
│  │ ✓ Risk register initialized                                  │ │
│  │                                                               │ │
│  │ Owner: Project Manager                                        │ │
│  │ Dependencies: None                                            │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M2: REQUIREMENTS COMPLETE (Week 3-6)                         │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ All user stories documented                                │ │
│  │ ✓ Wireframes approved by stakeholders                        │ │
│  │ ✓ Database schema finalized                                  │ │
│  │ ✓ API spec documented (OpenAPI)                              │ │
│  │ ✓ Sprint backlog groomed                                     │ │
│  │                                                               │ │
│  │ Owner: Product Owner + Tech Lead                             │ │
│  │ Dependencies: M1                                              │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M3: DESIGN COMPLETE (Week 7-10)                              │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ UI/UX designs finalized (all modules)                      │ │
│  │ ✓ Design system documented                                    │ │
│  │ ✓ Component library ready                                    │ │
│  │ ✓ Design tokens defined                                      │ │
│  │ ✓ Responsive layouts verified                                │ │
│  │                                                               │ │
│  │ Owner: UI/UX Designer + Tech Lead                            │ │
│  │ Dependencies: M2                                              │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M4: MVP DEVELOPMENT COMPLETE (Week 11-24)                    │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ User management & authentication                            │ │
│  │ ✓ Modul Akademik (biodata, nilai, rapor)                     │ │
│  │ ✓ Modul Keuangan (tagihan, pembayaran)                       │ │
│  │ ✓ Modul Kesiswaan (presensi, pelanggaran)                    │ │
│  │ ✓ Portal Orang Tua basic                                     │ │
│  │ ✓ Dashboard Kepala Madrasah                                  │ │
│  │ ✓ WhatsApp integration                                       │ │
│  │ ✓ EMIS/DAPODIK integration                                   │ │
│  │                                                               │ │
│  │ Owner: Development Team                                       │ │
│  │ Dependencies: M3                                              │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M5: PILOT GO-LIVE (Week 25-28)                               │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ System deployed di 1-2 MTs pilot                           │ │
│  │ ✓ User training completed                                    │ │
│  │ ✓ UAT passed                                                  │ │
│  │ ✓ All critical bugs fixed                                    │ │
│  │ ✓ Documentation complete                                     │ │
│  │ ✓ Support team ready                                         │ │
│  │                                                               │ │
│  │ Owner: Project Manager + QA Lead                             │ │
│  │ Dependencies: M4                                              │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M6: ENHANCED FEATURES COMPLETE (Week 29-44)                  │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ Modul Tahfiz (UNIQUE)                                      │ │
│  │ ✓ Modul Inklusi/PDBK (UNIQUE)                                │ │
│  │ ✓ Modul BK/Konseling                                         │ │
│  │ ✓ Modul E-Office                                             │ │
│  │ ✓ Multi-tenant architecture                                  │ │
│  │ ✓ Performance optimization                                   │ │
│  │                                                               │ │
│  │ Owner: Development Team                                       │ │
│  │ Dependencies: M5                                              │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M7: MOBILE APP COMPLETE (Week 45-52)                         │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ iOS app published to App Store                             │ │
│  │ ✓ Android app published to Play Store                        │ │
│  │ ✓ Push notifications working                                 │ │
│  │ ✓ Offline capability tested                                  │ │
│  │                                                               │ │
│  │ Owner: Mobile Developer + DevOps                             │ │
│  │ Dependencies: M6                                              │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
│  ┌───────────────────────────────────────────────────────────────┐ │
│  │ M8: COMMERCIAL LAUNCH (Week 53-72)                           │ │
│  ├───────────────────────────────────────────────────────────────┤ │
│  │ Deliverables:                                                 │ │
│  │ ✓ Marketing campaign active                                  │ │
│  │ ✓ Sales pipeline established (50+ schools)                   │ │
│  │ ✓ Customer success team fully operational                    │ │
│  │ ✓ Support SLA met (response < 4 hours)                       │ │
│  │ ✓ Revenue target achieved (Rp 500jt ARR)                     │ │
│  │                                                               │ │
│  │ Owner: Product Manager + Sales                               │ │
│  │ Dependencies: M7                                              │ │
│  │ Status: PLANNED                                               │ │
│  └───────────────────────────────────────────────────────────────┘ │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 2: WORK BREAKDOWN STRUCTURE (WBS)

### 2.1 Level 1 WBS

```
┌─────────────────────────────────────────────────────────────────────┐
│                    WORK BREAKDOWN STRUCTURE                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  1. PROJECT MANAGEMENT                                              │
│  2. REQUIREMENTS & DESIGN                                           │
│  3. FRONTEND DEVELOPMENT                                            │
│  4. BACKEND DEVELOPMENT                                             │
│  5. MOBILE DEVELOPMENT                                              │
│  6. DEVOPS & INFRASTRUCTURE                                         │
│  7. TESTING & QA                                                    │
│  8. DEPLOYMENT & SUPPORT                                            │
│  9. TRAINING & DOCUMENTATION                                        │
│  10. MARKETING & SALES                                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.2 Level 2 WBS - Detail

```
┌─────────────────────────────────────────────────────────────────────┐
│                    WBS DETAIL (LEVEL 2)                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  1. PROJECT MANAGEMENT                                              │
│  ├── 1.1 Project Planning                                           │
│  │   ├── 1.1.1 Create project charter                              │
│  │   ├── 1.1.2 Define scope & objectives                           │
│  │   └── 1.1.3 Setup project plan                                  │
│  ├── 1.2 Team Management                                            │
│  │   ├── 1.2.1 Team formation                                      │
│  │   ├── 1.2.2 Role assignments                                    │
│  │   └── 1.2.3 Communication plan                                  │
│  ├── 1.3 Progress Tracking                                          │
│  │   ├── 1.3.1 Sprint planning                                     │
│  │   ├── 1.3.2 Daily standups                                      │
│  │   ├── 1.3.3 Weekly reporting                                    │
│  │   └── 1.3.4 Risk management                                     │
│  └── 1.4 Stakeholder Management                                     │
│      ├── 1.4.1 Regular updates                                     │
│      ├── 1.4.2 Feedback collection                                 │
│      └── 1.4.3 Change management                                    │
│                                                                     │
│  2. REQUIREMENTS & DESIGN                                           │
│  ├── 2.1 Requirements Analysis                                      │
│  │   ├── 2.1.1 Stakeholder interviews                              │
│  │   ├── 2.1.2 User stories creation                               │
│  │   └── 2.1.3 Acceptance criteria defined                         │
│  ├── 2.2 System Design                                              │
│  │   ├── 2.2.1 Architecture design                                 │
│  │   ├── 2.2.2 Database schema design                              │
│  │   └── 2.2.3 API specification                                   │
│  └── 2.3 UI/UX Design                                               │
│      ├── 2.3.1 Wireframing                                          │
│      ├── 2.3.2 Visual design                                        │
│      ├── 2.3.3 Component library                                   │
│      └── 2.3.4 Design system                                       │
│                                                                     │
│  3. FRONTEND DEVELOPMENT                                            │
│  ├── 3.1 Core Framework Setup                                       │
│  │   ├── 3.1.1 Project initialization                              │
│  │   ├── 3.1.2 State management setup                              │
│  │   └── 3.1.3 Routing configuration                               │
│  ├── 3.2 Authentication Module                                      │
│  │   ├── 3.2.1 Login page                                          │
│  │   ├── 3.2.2 Registration                                        │
│  │   ├── 3.2.3 Password reset                                      │
│  │   └── 3.2.4 JWT handling                                        │
│  ├── 3.3 Dashboard Module                                           │
│  │   ├── 3.3.1 Admin dashboard                                     │
│  │   ├── 3.3.2 Kepala Madrasah dashboard                           │
│  │   ├── 3.3.3 Guru dashboard                                      │
│  │   └── 3.3.4 Orang Tua dashboard                                 │
│  ├── 3.4 Akademik Module                                           │
│  │   ├── 3.4.1 Biodata siswa                                       │
│  │   ├── 3.4.2 Kelola kelas                                        │
│  │   ├── 3.4.3 Jadwal pelajaran                                    │
│  │   ├── 3.4.4 Presensi                                            │
│  │   ├── 3.4.5 Penilaian                                           │
│  │   └── 3.4.6 E-Rapor                                             │
│  ├── 3.5 Keuangan Module                                           │
│  │   ├── 3.5.1 Setup tagihan                                       │
│  │   ├── 3.5.2 Input pembayaran                                    │
│  │   └── 3.5.3 Laporan keuangan                                    │
│  ├── 3.6 Kesiswaan Module                                          │
│  │   ├── 3.6.1 Organisasi & Ekskul                                 │
│  │   ├── 3.6.2 Pelanggaran & Prestasi                              │
│  │   └── 3.6.3 Perizinan                                           │
│  └── 3.7 Utility Components                                         │
│      ├── 3.7.1 Table components                                     │
│      ├── 3.7.2 Form components                                      │
│      └── 3.7.3 Chart components                                     │
│                                                                     │
│  4. BACKEND DEVELOPMENT                                             │
│  ├── 4.1 API Infrastructure                                         │
│  │   ├── 4.1.1 Server setup                                       │
│  │   ├── 4.1.2 Database connection                                 │
│  │   └── 4.1.3 Authentication middleware                           │
│  ├── 4.2 User & Auth APIs                                           │
│  │   ├── 4.2.1 User CRUD                                          │
│  │   ├── 4.2.2 Role management                                     │
│  │   └── 4.2.3 Session handling                                    │
│  ├── 4.3 Akademik APIs                                             │
│  │   ├── 4.3.1 Student APIs                                        │
│  │   ├── 4.3.2 Class APIs                                          │
│  │   ├── 4.3.3 Schedule APIs                                       │
│  │   ├── 4.3.4 Attendance APIs                                     │
│  │   ├── 4.3.5 Assessment APIs                                     │
│  │   └── 4.3.6 Report APIs                                         │
│  ├── 4.4 Keuangan APIs                                             │
│  │   ├── 4.4.1 Billing APIs                                        │
│  │   ├── 4.4.2 Payment APIs                                        │
│  │   └── 4.4.3 Finance APIs                                        │
│  ├── 4.5 Tahfiz APIs (UNIQUE)                                       │
│  │   ├── 4.5.1 Program APIs                                        │
│  │   ├── 4.5.2 Hafalan APIs                                        │
│  │   ├── 4.5.3 Munaqosah APIs                                      │
│  │   └── 4.5.4 Progress APIs                                       │
│  ├── 4.6 Inklusi APIs (UNIQUE)                                      │
│  │   ├── 4.6.1 ABK APIs                                            │
│  │   ├── 4.6.2 PPI APIs                                            │
│  │   ├── 4.6.3 GPK APIs                                            │
│  │   └── 4.6.4 Assessment APIs                                     │
│  └── 4.7 Integration APIs                                           │
│      ├── 4.7.1 EMIS integration                                     │
│      ├── 4.7.2 RDM export                                           │
│      └── 4.7.3 WhatsApp integration                                 │
│                                                                     │
│  5. MOBILE DEVELOPMENT (Phase 3)                                    │
│  ├── 5.1 iOS Development                                            │
│  │   ├── 5.1.1 Project setup                                       │
│  │   ├── 5.1.2 Authentication                                      │
│  │   ├── 5.1.3 Core features                                       │
│  │   └── 5.1.4 Push notifications                                  │
│  └── 5.2 Android Development                                        │
│      ├── 5.2.1 Project setup                                       │
│      ├── 5.2.2 Authentication                                      │
│      ├── 5.2.3 Core features                                       │
│      └── 5.2.4 Push notifications                                  │
│                                                                     │
│  6. DEVOPS & INFRASTRUCTURE                                         │
│  ├── 6.1 CI/CD Pipeline                                             │
│  │   ├── 6.1.1 GitHub Actions setup                                │
│  │   ├── 6.1.2 Automated testing                                   │
│  │   └── 6.1.3 Deployment automation                               │
│  ├── 6.2 Infrastructure Setup                                       │
│  │   ├── 6.2.1 Server provisioning                                 │
│  │   ├── 6.2.2 Database setup                                      │
│  │   └── 6.2.3 CDN configuration                                   │
│  └── 6.3 Monitoring & Security                                      │
│      ├── 6.3.1 Monitoring setup                                     │
│      ├── 6.3.2 Security hardening                                   │
│      └── 6.3.3 Backup system                                       │
│                                                                     │
│  7. TESTING & QA                                                    │
│  ├── 7.1 Unit Testing                                               │
│  │   ├── 7.1.1 Backend unit tests                                  │
│  │   └── 7.1.2 Frontend unit tests                                 │
│  ├── 7.2 Integration Testing                                        │
│  │   ├── 7.2.1 API integration tests                               │
│  │   └── 7.2.2 External system tests                               │
│  ├── 7.3 UAT                                                         │
│  │   ├── 7.3.1 Test case creation                                  │
│  │   ├── 7.3.2 User testing                                       │
│  │   └── 7.3.3 Bug tracking                                        │
│  └── 7.4 Performance Testing                                        │
│      ├── 7.4.1 Load testing                                         │
│      └── 7.4.2 Stress testing                                       │
│                                                                     │
│  8. DEPLOYMENT & SUPPORT                                            │
│  ├── 8.1 Production Deployment                                      │
│  │   ├── 8.1.1 Staging deployment                                  │
│  │   └── 8.1.2 Production deployment                               │
│  ├── 8.2 Post-Launch Support                                        │
│  │   ├── 8.2.1 Bug fixes                                           │
│  │   ├── 8.2.2 Performance optimization                            │
│  │   └── 8.2.3 Feature enhancements                                │
│  └── 8.3 Customer Support                                           │
│      ├── 8.3.1 Helpdesk setup                                       │
│      └── 8.3.2 SLA management                                       │
│                                                                     │
│  9. TRAINING & DOCUMENTATION                                        │
│  ├── 9.1 User Training                                              │
│  │   ├── 9.1.1 Training materials                                  │
│  │   ├── 9.1.2 Video tutorials                                     │
│  │   └── 9.1.3 Hands-on training                                   │
│  └── 9.2 Technical Documentation                                    │
│      ├── 9.2.1 API documentation                                    │
│      ├── 9.2.2 Deployment guide                                     │
│      └── 9.2.3 Admin guide                                          │
│                                                                     │
│  10. MARKETING & SALES                                              │
│  ├── 10.1 Marketing                                                 │
│  │   ├── 10.1.1 Website & branding                                 │
│  │   ├── 10.1.2 Content marketing                                  │
│  │   └── 10.1.3 Social media                                       │
│  └── 10.2 Sales                                                     │
│      ├── 10.2.1 Sales materials                                    │
│      ├── 10.2.2 Demo setup                                         │
│      └── 10.2.3 Customer acquisition                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 3: SPRINT PLANNING (MVP PHASE)

### 3.1 Sprint Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                      SPRINT OVERVIEW (MVP)                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Total Sprints: 6 sprints                                          │
│  Duration: 2 weeks per sprint                                       │
│  Total Duration: 12 weeks (3 months)                                │
│                                                                     │
│  ┌──────────┬────────────────────────────────────────────────┐     │
│  │  Sprint  │  Focus Area                                    │     │
│  ├──────────┼────────────────────────────────────────────────┤     │
│  │  #1      │  Project Setup + Authentication + Core Data    │     │
│  │  #2      │  Akademik Module - Biodata & Kelas            │     │
│  │  #3      │  Akademik Module - Penilaian & Presensi       │     │
│  │  #4      │  Keuangan Module + Integrations               │     │
│  │  #5      │  Portal Orang Tua + WhatsApp                  │     │
│  │  #6      │  Dashboard + Polishing + UAT                  │     │
│  └──────────┴────────────────────────────────────────────────┘     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Sprint 1 Detail (Week 1-2)

```
┌─────────────────────────────────────────────────────────────────────┐
│              SPRINT 1: PROJECT SETUP + AUTH                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Sprint Goal: Setup project infrastructure and basic authentication │
│  Duration: 2 weeks                                                  │
│  Team: 5 developers + 1 designer + 1 PM                            │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ USER STORIES:                                               │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │                                                               │   │
│  │ US-001: Setup Project                                        │   │
│  │ ├── Task: Initialize Next.js project                        │   │
│  │ ├── Task: Setup Prisma + PostgreSQL                         │   │
│  │ ├── Task: Setup TailwindCSS with design tokens              │   │
│  │ └── Task: Configure ESLint + Prettier                       │   │
│  │                                                               │   │
│  │ US-002: User Authentication                                  │   │
│  │ ├── Task: Create user registration flow                     │   │
│  │ ├── Task: Create login with email/password                  │   │
│  │ ├── Task: Implement JWT token handling                      │   │
│  │ ├── Task: Create password reset flow                        │   │
│  │ └── Task: Setup RBAC middleware                             │   │
│  │                                                               │   │
│  │ US-003: User Management (Admin)                              │   │
│  │ ├── Task: CRUD users                                        │   │
│  │ ├── Task: Role assignment                                   │   │
│  │ ├── Task: Active/inactive users                             │   │
│  │ └── Task: User profile management                           │   │
│  │                                                               │   │
│  │ US-004: Madrasah & Academic Year Setup                       │   │
│  │ ├── Task: CRUD madrasah                                     │   │
│  │ ├── Task: Setup academic year                               │   │
│  │ ├── Task: Initial data seeding                              │   │
│  │ └── Task: Multi-school support (future)                     │   │
│  │                                                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  CAPACITY PLANNING:                                                 │
│  ├── Dev Days Available: 5 devs × 10 days = 50 dev-days           │
│  ├── Estimated Velocity: 40 story points                           │
│  └── Buffer: 20% for unexpected issues                             │
│                                                                     │
│  DEFINITION OF DONE:                                                │
│  ├── Code reviewed and merged to main                              │
│  ├── Unit tests passing (>80% coverage)                           │
│  ├── Integration tests passing                                     │
│  ├── Deployed to staging environment                               │
│  └── Product Owner acceptance                                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.3 Sprint 2 Detail (Week 3-4)

```
┌─────────────────────────────────────────────────────────────────────┐
│           SPRINT 2: AKADEMIK MODULE - BIODATA & KELAS              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Sprint Goal: Complete student management and class setup           │
│  Duration: 2 weeks                                                  │
│  Team: 5 developers + 1 designer + 1 PM                            │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ USER STORIES:                                               │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │                                                               │   │
│  │ US-010: Student Biodata Management                           │   │
│  │ ├── Task: Create student list view                          │   │
│  │ ├── Task: Create student form (add/edit)                    │   │
│  │ ├── Task: Student photo upload                              │   │
│  │ ├── Task: Family information section                        │   │
│  │ ├── Task: Student status management                         │   │
│  │ └── Task: Bulk import from Excel                            │   │
│  │                                                               │   │
│  │ US-011: Class Management                                     │   │
│  │ ├── Task: Create/manage rombel                              │   │
│  │ ├── Task: Assign homeroom teacher                           │   │
│  │ ├── Task: Student-class assignment                          │   │
│  │ ├── Task: Class promotion workflow                          │   │
│  │ └── Task: Class list view with stats                        │   │
│  │                                                               │   │
│  │ US-012: Subject Management                                   │   │
│  │ ├── Task: CRUD subjects                                     │   │
│  │ ├── Task: Subject grouping                                  │   │
│  │ └── Task: Teacher assignment per subject                    │   │
│  │                                                               │   │
│  │ US-013: EMIS Import                                          │   │
│  │ ├── Task: EMIS file parser                                  │   │
│  │ ├── Task: NIK validation with EMIS                          │   │
│  │ └── Task: Bulk student creation                             │   │
│  │                                                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  CAPACITY PLANNING:                                                 │
│  ├── Dev Days Available: 5 devs × 10 days = 50 dev-days           │
│  ├── Estimated Velocity: 45 story points                           │
│  └── Dependencies: Sprint 1 completed                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.4 Sprint 3 Detail (Week 5-6)

```
┌─────────────────────────────────────────────────────────────────────┐
│           SPRINT 3: AKADEMIK - PENILAIAN & PRESENSI                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Sprint Goal: Complete assessment and attendance features           │
│  Duration: 2 weeks                                                  │
│  Team: 5 developers + 1 designer + 1 PM                            │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ USER STORIES:                                               │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │                                                               │   │
│  │ US-020: Schedule Management                                  │   │
│  │ ├── Task: Create schedule view                              │   │
│  │ ├── Task: Schedule generation algorithm                     │   │
│  │ ├── Task: Conflict detection                                 │   │
│  │ ├── Task: Schedule change workflow                           │   │
│  │ └── Task: Teacher/class schedule export                      │   │
│  │                                                               │   │
│  │ US-021: Attendance System                                    │   │
│  │ ├── Task: Daily attendance input                             │   │
│  │ ├── Task: Status options (H/I/S/A)                          │   │
│  │ ├── Task: Late arrival tracking                              │   │
│  │ ├── Task: Attendance rekap per student/class                │   │
│  │ └── Task: QR code attendance (future)                        │   │
│  │                                                               │   │
│  │ US-022: Assessment & Grading                                 │   │
│  │ ├── Task: Create assessment (formatif/summative)            │   │
│  │ ├── Task: Score input per student                           │   │
│  │ ├── Task: Auto-calculate final score                         │   │
│  │ ├── Task: Auto-generate description                          │   │
│  │ └── Task: Score export to Excel                              │   │
│  │                                                               │   │
│  │ US-023: Semester Report (E-Rapor)                            │   │
│  │ ├── Task: Report generation                                  │   │
│  │ ├── Task: PDF export with design                             │   │
│  │ ├── Task: Digital signature integration                      │   │
│  │ └── Task: RDM export format                                  │   │
│  │                                                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  CAPACITY PLANNING:                                                 │
│  ├── Dev Days Available: 5 devs × 10 days = 50 dev-days           │
│  ├── Estimated Velocity: 50 story points                           │
│  └── Dependencies: Sprint 2 completed                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.5 Sprint 4 Detail (Week 7-8)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SPRINT 4: KEUANGAN & INTEGRASI                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Sprint Goal: Complete finance module and external integrations     │
│  Duration: 2 weeks                                                  │
│  Team: 5 developers + 1 designer + 1 PM                            │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ USER STORIES:                                               │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │                                                               │   │
│  │ US-030: Billing Setup                                        │   │
│  │ ├── Task: Setup billing components                          │   │
│  │ ├── Task: Generate student bills                            │   │
│  │ ├── Task: Discount management                                │   │
│  │ └── Task: Bill reminder setup                               │   │
│  │                                                               │   │
│  │ US-031: Payment Processing                                   │   │
│  │ ├── Task: Payment input                                     │   │
│  │ ├── Task: Proof upload & validation                         │   │
│  │ ├── Task: Payment confirmation flow                          │   │
│  │ └── Task: Transaction history                               │   │
│  │                                                               │   │
│  │ US-032: Financial Reporting                                  │   │
│  │ ├── Task: Income report                                     │   │
│  │ ├── Task: Outstanding report                                 │   │
│  │ ├── Task: Export to Excel                                    │   │
│  │ └── Task: Dashboard widgets                                  │   │
│  │                                                               │   │
│  │ US-033: Integration - EMIS                                   │   │
│  │ ├── Task: EMIS API connection                               │   │
│  │ ├── Task: Student data sync                                 │   │
│  │ └── Task: Sync status monitoring                             │   │
│  │                                                               │   │
│  │ US-034: Integration - DAPODIK                                │   │
│  │ ├── Task: DAPODIK file import                               │   │
│  │ ├── Task: Data validation                                    │   │
│  │ └── Task: NISN verification                                  │   │
│  │                                                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  CAPACITY PLANNING:                                                 │
│  ├── Dev Days Available: 5 devs × 10 days = 50 dev-days           │
│  ├── Estimated Velocity: 40 story points                           │
│  └── Dependencies: Sprint 3 completed                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.6 Sprint 5 Detail (Week 9-10)

```
┌─────────────────────────────────────────────────────────────────────┐
│                   SPRINT 5: PORTAL ORANG TUA                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Sprint Goal: Complete parent portal and WhatsApp integration      │
│  Duration: 2 weeks                                                  │
│  Team: 5 developers + 1 designer + 1 PM                            │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ USER STORIES:                                               │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │                                                               │   │
│  │ US-040: Parent Portal - Dashboard                            │   │
│  │ ├── Task: Parent registration/login                         │   │
│  │ ├── Task: Child selection (for multiple children)           │   │
│  │ ├── Task: Home dashboard                                    │   │
│  │ └── Task: Notification center                               │   │
│  │                                                               │   │
│  │ US-041: Parent Portal - Monitoring                           │   │
│  │ ├── Task: View student grades                               │   │
│  │ ├── Task: View attendance history                           │   │
│  │ ├── Task: View schedule                                     │   │
│  │ └── Task: View financial status                             │   │
│  │                                                               │   │
│  │ US-042: Parent Portal - Communication                        │   │
│  │ ├── Task: Message to teacher/wali kelas                     │   │
│  │ ├── Task: Receive announcements                             │   │
│  │ └── Task: Permission request form                           │   │
│  │                                                               │   │
│  │ US-043: WhatsApp Integration                                 │   │
│  │ ├── Task: Green API integration                             │   │
│  │ ├── Task: Attendance notification                           │   │
│  │ ├── Task: Payment reminder                                   │   │
│  │ ├── Task: Announcement broadcast                             │   │
│  │ └── Task: Template message management                        │   │
│  │                                                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  CAPACITY PLANNING:                                                 │
│  ├── Dev Days Available: 5 devs × 10 days = 50 dev-days           │
│  ├── Estimated Velocity: 45 story points                           │
│  └── Dependencies: Sprint 4 completed                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.7 Sprint 6 Detail (Week 11-12)

```
┌─────────────────────────────────────────────────────────────────────┐
│                SPRINT 6: DASHBOARD & POLISHING                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Sprint Goal: Complete dashboard and prepare for pilot launch       │
│  Duration: 2 weeks                                                  │
│  Team: 5 developers + 1 designer + 1 PM                            │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ USER STORIES:                                               │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │                                                               │   │
│  │ US-050: Headmaster Dashboard                                 │   │
│  │ ├── Task: KPI overview widgets                              │   │
│  │ ├── Task: Attendance overview                               │   │
│  │ ├── Task: Academic performance charts                       │   │
│  │ ├── Task: Financial summary                                 │   │
│  │ └── Task: Alert/notifications                                │   │
│  │                                                               │   │
│  │ US-051: Reporting & Analytics                                │   │
│  │ ├── Task: Custom report builder                             │   │
│  │ ├── Task: Chart visualizations                              │   │
│  │ └── Task: Export reports                                    │   │
│  │                                                               │   │
│  │ US-052: Kesiswaan Module                                     │   │
│  │ ├── Task: Extracurricular management                        │   │
│  │ ├── Task: Violation tracking                                │   │
│  │ └── Task: Achievement records                                │   │
│  │                                                               │   │
│  │ US-053: Polish & Optimization                                │   │
│  │ ├── Task: Performance optimization                          │   │
│  │ ├── Task: Mobile responsiveness check                        │   │
│  │ ├── Task: UI/UX polish                                       │   │
│  │ └── Task: Error handling improvements                        │   │
│  │                                                               │   │
│  │ US-054: UAT Preparation                                      │   │
│  │ ├── Task: Test case documentation                           │   │
│  │ ├── Task: UAT environment setup                             │   │
│  │ ├── Task: User acceptance testing                           │   │
│  │ └── Task: Bug fixes from UAT                                │   │
│  │                                                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  CAPACITY PLANNING:                                                 │
│  ├── Dev Days Available: 5 devs × 10 days = 50 dev-days           │
│  ├── Estimated Velocity: 40 story points                           │
│  └── Dependencies: Sprint 5 completed                              │
│                                                                     │
│  SPRINT END CRITERIA:                                               │
│  ├── All MVP features implemented                                  │
│  ├── UAT completed with no critical bugs                           │
│  ├── System deployed to pilot environment                           │
│  └── Ready for pilot launch (Week 13)                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 4: TASK MANAGEMENT

### 4.1 Task Definition

```
┌─────────────────────────────────────────────────────────────────────┐
│                        TASK DEFINITION                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  TASK FORMAT:                                                       │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Task ID: TASK-XXX                                            │   │
│  │ Title: [Clear, actionable title]                             │   │
│  │ Description: [Detailed description of what needs to be done] │   │
│  │                                                               │   │
│  │ Story: US-XXX                                                │   │
│  │ Sprint: X                                                     │   │
│  │ Assignee: [Name]                                             │   │
│  │ Estimated Hours: X                                           │   │
│  │ Status: [Todo | In Progress | Code Review | Done]            │   │
│  │                                                               │   │
│  │ Acceptance Criteria:                                         │   │
│  │ - [AC1]                                                       │   │
│  │ - [AC2]                                                       │   │
│  │ - [AC3]                                                       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  TASK STATES:                                                       │
│  ├── TODO: Task created, not started                              │
│  ├── IN PROGRESS: Actively working on it                          │
│  ├── CODE REVIEW: Pull request created, awaiting review           │
│  ├── TESTING: In QA testing                                       │
│  ├── DONE: Completed and accepted                                 │
│  └── BLOCKED: Waiting on external dependency                      │
│                                                                     │
│  PRIORITY LEVELS:                                                   │
│  ├── P1 (Critical): Must complete for sprint goal                 │
│  ├── P2 (High): Should complete for sprint                        │
│  ├── P3 (Medium): Nice to have                                    │
│  └── P4 (Low): Can be deferred                                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.2 Sample Task Breakdown

```
┌─────────────────────────────────────────────────────────────────────┐
│                   SAMPLE TASK BREAKDOWN                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  USER STORY: US-010 - Student Biodata Management                   │
│  Story Points: 8                                                    │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ TASK-001: Create Student List View                          │   │
│  │ Story: US-010                                               │   │
│  │ Sprint: 2                                                   │   │
│  │ Assignee: Developer A                                       │   │
│  │ Est. Hours: 8                                               │   │
│  │ Priority: P1                                                │   │
│  │                                                               │   │
│  │ AC:                                                           │   │
│  │ - Searchable student list with pagination                   │   │
│  │ - Filter by class, status, gender                           │   │
│  │ - Quick actions (edit, view, deactivate)                    │   │
│  │ - Export to Excel                                            │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ TASK-002: Create Student Form (Add/Edit)                    │   │
│  │ Story: US-010                                               │   │
│  │ Sprint: 2                                                   │   │
│  │ Assignee: Developer B                                       │   │
│  │ Est. Hours: 12                                              │   │
│  │ Priority: P1                                                │   │
│  │                                                               │   │
│  │ AC:                                                           │   │
│  │ - Multi-step form (Personal, Family, Address)               │   │
│  │ - Photo upload with preview                                  │   │
│  │ - NIK validation (16 digits, numeric)                       │   │
│  │ - Auto-save draft                                            │   │
│  │ - Success notification after save                            │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ TASK-003: Bulk Import from Excel                            │   │
│  │ Story: US-010                                               │   │
│  │ Sprint: 2                                                   │   │
│  │ Assignee: Developer A                                       │   │
│  │ Est. Hours: 6                                               │   │
│  │ Priority: P2                                                │   │
│  │                                                               │   │
│  │ AC:                                                           │   │
│  │ - Template download                                          │   │
│  │ - Excel file upload                                          │   │
│  │ - Validation with error report                               │   │
│  │ - Preview before import                                      │   │
│  │ - Batch import with progress indicator                       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 5: VELOCITY & CAPACITY PLANNING

### 5.1 Team Capacity

```
┌─────────────────────────────────────────────────────────────────────┐
│                      TEAM CAPACITY PLANNING                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  TEAM COMPOSITION:                                                  │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Role              │ Qty │ Hours/Day │ Available/Day │ Total  │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Project Manager   │  1  │    6      │      6       │   6    │   │
│  │ Tech Lead         │  1  │    6      │      5       │   5    │   │
│  │ Senior Developer  │  2  │    6      │      5       │  10    │   │
│  │ Mid Developer     │  2  │    6      │      5       │  10    │   │
│  │ UI/UX Designer    │  1  │    6      │      5       │   5    │   │
│  │ QA Engineer       │  1  │    6      │      5       │   5    │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ TOTAL DEV HOURS/DAY:                 │                    │  41  │   │
│  │ TOTAL DEV HOURS/SPRINT (10 days):    │                    │ 410  │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ASSUMPTIONS:                                                       │
│  ├── 1 sprint = 2 weeks (10 working days)                          │
│  ├── Average utilization = 80% (meetings, emails, etc.)            │
│  ├── 20% buffer for unexpected issues                               │
│  └── Net available hours/sprint = 410 × 0.8 × 0.8 = 262 hours     │
│                                                                     │
│  VELOCITY PROJECTION:                                               │
│  ├── Average story points per hour = 0.5                            │
│  ├── Expected velocity = 262 × 0.5 = 131 points/sprint             │
│  └── Conservative estimate = 100 points/sprint                      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.2 Sprint Velocity Tracking

```
┌─────────────────────────────────────────────────────────────────────┐
│                      VELOCITY TRACKING                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌──────────┬────────────┬────────────┬────────────┬────────────┐  │
│  │  Sprint  │ Committed  │ Completed  │  Velocity  │ Difference │  │
│  ├──────────┼────────────┼────────────┼────────────┼────────────┤  │
│  │ Sprint 1 │   40 SP    │   38 SP    │   38 SP    │    -2 SP   │  │
│  │ Sprint 2 │   45 SP    │   43 SP    │   43 SP    │    -2 SP   │  │
│  │ Sprint 3 │   50 SP    │   48 SP    │   48 SP    │    -2 SP   │  │
│  │ Sprint 4 │   40 SP    │   42 SP    │   42 SP    │    +2 SP   │  │
│  │ Sprint 5 │   45 SP    │   44 SP    │   44 SP    │    -1 SP   │  │
│  │ Sprint 6 │   40 SP    │   41 SP    │   41 SP    │    +1 SP   │  │
│  ├──────────┼────────────┼────────────┼────────────┼────────────┤  │
│  │  TOTAL   │  260 SP    │  256 SP    │   43 SP    │    -4 SP   │  │
│  └──────────┴────────────┴────────────┴────────────┴────────────┘  │
│                                                                     │
│  AVERAGE VELOCITY: 43 SP/sprint                                     │
│  PROJECTED TOTAL (18 months): ~400 SP                              │
│                                                                     │
│  VELOCITY NOTES:                                                    │
│  ├── Sprint 1: Lower velocity due to setup and learning curve      │
│  ├── Sprint 4: Higher due to knowledge transfer from earlier sprints│
│  └── Target velocity for planning: 40 SP/sprint (conservative)     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 6: RISK MANAGEMENT

### 6.1 Risk Register

```
┌─────────────────────────────────────────────────────────────────────┐
│                        RISK REGISTER                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ R01: Teknisi/Developer Utama Mengundurkan Diri              │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Probability: LOW  │ Impact: HIGH  │ Risk Score: 6/25        │   │
│  │                                                               │   │
│  │ Mitigation:                                                     │   │
│  │ - Knowledge sharing sessions (pair programming)              │   │
│  │ - Documentation culture (inline docs, README)                 │   │
│  │ - Cross-training antar developer                              │   │
│  │ - Backup resource on standby                                  │   │
│  │                                                               │   │
│  │ Owner: Project Manager                                        │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ R02: Keterlambatan karena Perubahan Requirements             │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Probability: HIGH │ Impact: MEDIUM │ Risk Score: 12/25       │   │
│  │                                                               │   │
│  │ Mitigation:                                                     │   │
│  │ - Change control process (CAB)                               │   │
│  │ - MVP scope locked after Sprint 1                            │   │
│  │ - Buffer time per sprint (20%)                               │   │
│  │ - Weekly scope review dengan stakeholder                      │   │
│  │                                                               │   │
│  │ Owner: Product Owner                                          │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ R03: Integrasi EMIS/DAPODIK Bermasalah                      │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Probability: MEDIUM │ Impact: HIGH │ Risk Score: 9/25       │   │
│  │                                                               │   │
│  │ Mitigation:                                                     │   │
│  │ - Early engagement dengan admin EMIS                        │   │
│  │ - Develop mock/alternative if API unavailable               │   │
│  │ - File-based sync sebagai fallback                           │   │
│  │ - Test environment preparation                                │   │
│  │                                                               │   │
│  │ Owner: Tech Lead                                              │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ R04: Budget Overrun                                          │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Probability: MEDIUM │ Impact: HIGH │ Risk Score: 9/25       │   │
│  │                                                               │   │
│  │ Mitigation:                                                     │   │
│  │ - Fixed scope untuk MVP (locked)                             │   │
│  │ - Weekly budget tracking                                      │   │
│  │ - 15% contingency budget                                      │   │
│  │ - Phased payment untuk vendor                                 │   │
│  │                                                               │   │
│  │ Owner: Project Manager                                        │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ R05: User Adoption Rendah Saat Pilot                        │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Probability: MEDIUM │ Impact: HIGH │ Risk Score: 9/25       │   │
│  │                                                               │   │
│  │ Mitigation:                                                     │   │
│  │ - User-friendly design (UX focus)                            │   │
│  │ - Comprehensive training program                              │   │
│  │ - Change champion di setiap MTs                               │   │
│  │ - Feedback mechanism yang responsive                          │   │
│  │ - Incentives untuk early adopters                             │   │
│  │                                                               │   │
│  │ Owner: Product Owner + PM                                     │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 6.2 Contingency Plans

```
┌─────────────────────────────────────────────────────────────────────┐
│                      CONTINGENCY PLANS                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  IF budget exceeds 110% budget:                                     │
│  ├── Reduce scope (defer "nice-to-have" features)                  │
│  ├── Extend timeline if funding available                          │
│  ├── Prioritize revenue-generating features                        │
│  └── Consider external funding/partnership                         │
│                                                                     │
│  IF timeline delayed > 2 weeks:                                    │
│  ├── Add temporary resources (contractor)                          │
│  ├── Reduce scope to MVP minimum                                   │
│  ├── Focus on critical path only                                   │
│  └── Negotiate extended deadline dengan stakeholder                 │
│                                                                     │
│  IF key person leaves:                                             │
│  ├── Promote from within if possible                               │
│  ├── External recruitment (urgent)                                 │
│  ├── Knowledge transfer sessions                                   │
│  └── Adjust timeline proportionally                                │
│                                                                     │
│  IF user adoption < 50% after 1 month pilot:                       │
│  ├── Re-design UX based on feedback                                │
│  ├── Additional training sessions                                  │
│  ├── Identify and address specific pain points                      │
│  ├── Consider incentive program                                    │
│  └── Extended pilot period before scaling                          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 7: COMMUNICATION PLAN

### 7.1 Meeting Schedule

```
┌─────────────────────────────────────────────────────────────────────┐
│                       MEETING SCHEDULE                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  DAILY:                                                            │
│  ├── Daily Standup: 09:00 - 15 min (async or sync)                 │
│  │   Agenda: What done, What's next, Blockers                      │
│  └── Slack/Teams channel for quick questions                       │
│                                                                     │
│  WEEKLY:                                                           │
│  ├── Sprint Planning: Monday 09:00 - 2 hours                       │
│  │   Agenda: Select sprint backlog, estimate, assign               │
│  ├── Sprint Review: Friday 14:00 - 1 hour                          │
│  │   Agenda: Demo completed features, retrospective                 │
│  ├── Sprint Retrospective: Friday 15:00 - 1 hour                   │
│  │   Agenda: What went well, What to improve, Actions              │
│  └── Stakeholder Update: Friday 16:00 - 30 min                     │
│      Agenda: Progress summary, blockers, next steps                 │
│                                                                     │
│  MONTHLY:                                                          │
│  ├── Steering Committee Meeting: First Monday                      │
│  │   Agenda: Budget, timeline, major decisions                      │
│  └── Product Demo: Last Friday                                      │
│      Agenda: Showcase new features to all stakeholders              │
│                                                                     │
│  AS-NEEDED:                                                        │
│  ├── Bug triage: As issues arise                                    │
│  ├── Design review: Before implementing major features             │
│  └── Crisis management: For critical blockers                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 7.2 Reporting Cadence

```
┌─────────────────────────────────────────────────────────────────────┐
│                       REPORTING CADENCE                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ REPORT TYPE          │ FREQUENCY    │ AUDIENCE     │ FORMAT │   │
│  ├─────────────────────────────────────────────────────────────┤   │
│  │ Daily Status         │ Daily        │ Team          │ Async │   │
│  │ Sprint Burndown      │ Weekly       │ Team, PM      │ Chart │   │
│  │ Progress Report      │ Weekly       │ Stakeholders  │ Email │   │
│  │ Budget Report        │ Monthly      │ Sponsor       │ PDF   │   │
│  │ Risk Report          │ Monthly      │ SteerCo       │ PPT   │   │
│  │ Executive Summary    │ Monthly      │ Leadership    │ Brief │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 8: DEFINITION OF DONE

### 8.1 General DoD

```
┌─────────────────────────────────────────────────────────────────────┐
│                    DEFINITION OF DONE                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ✓ Code written and passes linting/formatting rules                │
│  ✓ Unit tests written and passing (>80% coverage)                 │
│  ✓ Integration tests written and passing                           │
│  ✓ Code reviewed and approved by at least 1 peer                   │
│  ✓ Merged to main branch                                           │
│  ✓ Deployed to staging environment                                 │
│  ✓ No critical or high severity bugs open                          │
│  ✓ Acceptance criteria met (verified by PO or QA)                  │
│  ✓ Documentation updated (code comments, README)                   │
│  ✓ No security vulnerabilities introduced                           │
│  ✓ Performance acceptable (tested if applicable)                   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Feature DoD per Module

```
┌─────────────────────────────────────────────────────────────────────┐
│                   FEATURE-SPECIFIC DoD                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  AKADEMIK MODULE:                                                   │
│  ├── Students can be added, edited, deactivated                    │
│  ├── Classes can be managed with student assignments               │
│  ├── Schedules can be generated and viewed                         │
│  ├── Attendance can be recorded and reported                       │
│  ├── Scores can be entered and calculated correctly                │
│  └── Reports can be generated in required format                   │
│                                                                     │
│  KEUANGAN MODULE:                                                   │
│  ├── Bills can be generated for all students                       │
│  ├── Payments can be recorded with validation                      │
│  ├── Reports show accurate financial data                          │
│  └── WhatsApp notifications are sent correctly                     │
│                                                                     │
│  TAHFIZ MODULE:                                                     │
│  ├── Hafalan recordings can be entered per student                 │
│  ├── Progress is tracked and visualized                            │
│  ├── Munaqosah registrations and results are managed               │
│  └── Reports show accurate tahfiz data                             │
│                                                                     │
│  INKLUSI MODULE:                                                    │
│  ├── ABK students are properly identified and tagged               │
│  ├── PPI can be created, updated, and tracked                      │
│  ├── GPK sessions are documented                                   │
│  └── Progress reports are generated correctly                      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*