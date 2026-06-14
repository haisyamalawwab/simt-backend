# SOFTWARE ARCHITECTURE DOCUMENT (SAD)
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** DRAFT  
**Architect:** [Nama Architect]

---

## 1. INTRODUCTION

### 1.1 Purpose

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PURPOSE                                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Dokumen Software Architecture Document (SAD) ini bertujuan untuk: │
│                                                                     │
│  ├── Mendefinisikan arsitektur sistem secara detail                │
│  ├── Menjadi acuan bagi developer dalam development                │
│  ├── Memfasilitasi komunikasi antar tim teknis                      │
│  ├── Menjadi dasar untuk technical decisions                       │
│  └── Mendukung maintainability dan scalability future              │
│                                                                     │
│  AUDIENCE:                                                          │
│  ├── Development Team (Backend + Frontend)                         │
│  ├── System Administrator / DevOps                                  │
│  ├── Technical Lead / Architect                                     │
│  └── Future maintainers                                            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.2 Scope

```
┌─────────────────────────────────────────────────────────────────────┐
│                           SCOPE                                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  SYSTEM: SIMT MTs (Sistem Informasi Manajemen Terpadu Madrasah)   │
│  VERSION: MVP 1.0 (3 bulan, budget Rp 5jt)                        │
│  SCALE: Pilot 1 MTs, ~125 user                                     │
│  FUTURE: Multi-tenant SaaS (Phase 2+)                              │
│                                                                     │
│  ARCHITECTURE TYPE:                                                 │
│  ├── Monolithic (MVP) - sederhana dan cepat                       │
│  └── Modular Monolith - untuk transisi ke microservices            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.3 Constraints

```
┌─────────────────────────────────────────────────────────────────────┐
│                        ARCHITECTURE CONSTRAINTS                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  BUDGET CONSTRAINTS:                                                │
│  ├── Total budget: Rp 5.000.000                                    │
│  ├── Hosting: Rp 300.000/bulan (minimal VPS)                       │
│  ├── Tools: Open-source atau free tier only                        │
│  └── No dedicated DevOps - developer handles ops                   │
│                                                                     │
│  TIMELINE CONSTRAINTS:                                              │
│  ├── MVP in 3 months (12 weeks)                                    │
│  ├── No room for complex architecture decisions                    │
│  └── Must ship fast, iterate later                                 │
│                                                                     │
│  TECHNICAL CONSTRAINTS:                                             │
│  ├── Indonesia deployment (latency consideration)                   │
│  ├── Mobile-first (users mostly on phone)                          │
│  └── Low bandwidth consideration (3G network)                       │
│                                                                     │
│  SKILL CONSTRAINTS:                                                 │
│  ├── Single/small team (1-2 developers)                            │
│  ├── Full-stack capability preferred                                │
│  └── No microservices expertise (keep simple)                      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. ARCHITECTURAL DECISIONS

### 2.1 Technology Stack

```
┌─────────────────────────────────────────────────────────────────────┐
│                      TECHNOLOGY STACK                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ═══ FRONTEND (Client) ═══                                          │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Framework:    Vue.js 3 + Vite                               │   │
│  │ UI Library:   TailwindCSS + HeadlessUI                      │   │
│  │ State:        Pinia (Lightweight, Vue-native)               │   │
│  │ Forms:        Vue Final Form                                │   │
│  │ Charts:       Chart.js / ApexCharts                         │   │
│  │ Icons:        Heroicons (free, MIT)                         │   │
│  │                                                               │   │
│  │ Justification:                                              │   │
│  │ - Fast development (template-based)                         │   │
│  │ - Small bundle size (performance)                           │   │
│  │ - Good documentation & community                             │   │
│  │ - Easy to hire (popular skill)                              │   │
│  │ - Free (no licensing)                                       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ═══ BACKEND (Server) ═══                                          │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Framework:    Laravel 10 (PHP 8.2+)                         │   │
│  │ API:          RESTful JSON API                              │   │
│  │ ORM:          Eloquent                                      │   │
│  │ Auth:         Laravel Sanctum (JWT-based)                   │   │
│  │ Validation:   Laravel Form Request                          │   │
│  │ Queue:        Laravel Queue (Redis - future)                │   │
│  │                                                               │   │
│  │ Justification:                                              │   │
│  │ - PHP: paling banyak developer di Indonesia                 │   │
│  │ - Laravel: fastest development, best documentation          │   │
│  │ - Eloquent: intuitive database operations                   │   │
│  │ - Sanctum: simple JWT auth, no extra dependencies           │   │
│  │ - Widely supported on shared hosting (if needed)            │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ═══ DATABASE ═══                                                   │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Engine:       PostgreSQL 15                                 │   │
│  │ Cache:        Redis (future, file-cache for MVP)            │   │
│  │                                                               │   │
│  │ Justification:                                              │   │
│  │ - PostgreSQL: robust, good performance, free                │   │
│  │ - JSON support untuk flexible data                          │   │
│  │ - PostGIS ready (future location features)                  │   │
│  │ - Heroku/Supabase compatible (easy deployment)              │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ═══ INFRASTRUCTURE ═══                                            │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │ Hosting:       VPS Indonesia (NusaCloud/IDCloudhost)        │   │
│  │ CDN:           Cloudflare (free tier)                       │   │
│  │ Domain:        .id domain                                   │   │
│  │ SSL:           Let's Encrypt (free)                         │   │
│  │ Monitoring:    Laravel Log + Error tracking (free)          │   │
│  │                                                               │   │
│  │ Justification:                                              │   │
│  │ - Indonesia VPS: low latency for users                      │   │
│  │ - Cloudflare: DDoS protection + CDN (free)                  │   │
│  │ - Let's Encrypt: free SSL, auto-renew                       │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.2 Decision Record

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ARCHITECTURAL DECISION RECORD                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ADR-001: Monolithic Architecture over Microservices               │
│  ├── Decision: Use monolithic architecture for MVP                 │
│  ├── Context: Budget limited, timeline tight, small team           │
│  ├── Consequences:                                                 │
│  │   ├── PRO: Faster development, simpler deployment              │
│  │   ├── PRO: Lower infrastructure cost                           │
│  │   ├── CON: Harder to scale отдельные parts later             │
│  │   └── CON: Single point of failure                             │
│  └── Status: ACCEPTED                                              │
│                                                                     │
│  ADR-002: Vue.js over React                                         │
│  ├── Decision: Use Vue.js 3 for frontend                          │
│  ├── Context: Developer familiarity, simpler learning curve        │
│  ├── Consequences:                                                 │
│  │   ├── PRO: Better documentation in Bahasa                      │
│  │   ├── PRO: Smaller community but dedicated                     │
│  │   └── CON: Less hiring pool than React                         │
│  └── Status: ACCEPTED                                              │
│                                                                     │
│  ADR-003: Laravel over Node.js                                      │
│  ├── Decision: Use Laravel for backend                             │
│  ├── Context: PHP ecosystem maturity in Indonesia                  │
│  ├── Consequences:                                                 │
│  │   ├── PRO: More developers available                           │
│  │   ├── PRO: Faster development (built-in features)              │
│  │   ├── PRO: Better hosting support in Indonesia                 │
│  │   └── CON: Performance slightly lower than Node.js             │
│  └── Status: ACCEPTED                                              │
│                                                                     │
│  ADR-004: PostgreSQL over MySQL                                     │
│  ├── Decision: Use PostgreSQL for database                         │
│  ├── Context: Better data integrity, JSON support                  │
│  ├── Consequences:                                                 │
│  │   ├── PRO: Better ACID compliance                              │
│  │   ├── PRO: Advanced indexing options                           │
│  │   └── CON: Slightly higher hosting cost                        │
│  └── Status: ACCEPTED                                              │
│                                                                     │
│  ADR-005: Server-Side Rendering for Dashboard                      │
│  ├── Decision: Use SSR for admin dashboard                         │
│  ├── Context: SEO tidak penting untuk admin, tapi initial load    │
│  ├── Consequences:                                                 │
│  │   ├── PRO: Faster first paint for admin                        │
│  │   ├── PRO: Better UX for low-end devices                       │
│  │   └── CON: More complex deployment                             │
│  └── Status: ACCEPTED (Laravel Blade + Vue as needed)              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 3. SYSTEM ARCHITECTURE

### 3.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                   HIGH-LEVEL ARCHITECTURE                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                         USERS                                        │
│     ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐   │
│     │ Desktop │ │ Mobile  │ │ Tablet  │ │WhatsApp │ │  API    │   │
│     │ Browser │ │ Browser │ │ Browser │ │  Bot    │ │ Client  │   │
│     └────┬────┘ └────┬────┘ └────┬────┘ └────┬────┘ └────┬────┘   │
│          │           │           │           │           │         │
│          └───────────┴───────────┴───────────┴───────────┘         │
│                             │                                       │
│                             ▼                                       │
│                 ┌───────────────────────┐                          │
│                 │       CDN/Firewall     │                          │
│                 │     (Cloudflare)       │                          │
│                 └───────────┬───────────┘                          │
│                             │                                       │
│                             ▼                                       │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                        LOAD BALANCER                         │   │
│  │                     (Nginx/Cloudflare)                       │   │
│  └────────────────────────────────┬────────────────────────────┘   │
│                                   │                                │
│                                   ▼                                │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    APPLICATION SERVER                        │   │
│  │  ┌─────────────────────────────────────────────────────┐   │   │
│  │  │                                                     │   │   │
│  │  │   ┌───────────┐  ┌───────────┐  ┌───────────┐     │   │   │
│  │  │   │  Laravel  │  │   Vue.js  │  │   API     │     │   │   │
│  │  │   │   (PHP)   │  │   (SPA)   │  │  Gateway  │     │   │   │
│  │  │   │           │  │           │  │           │     │   │   │
│  │  │   │ • Auth    │  │ • Router  │  │ • REST    │     │   │   │
│  │  │   │ • API     │  │ • State   │  │ • JWT     │     │   │   │
│  │  │   │ • Business│  │ • Views   │  │ • Rate    │     │   │   │
│  │  │   │ • ORM     │  │ • Forms   │  │ • Validate│     │   │   │
│  │  │   └───────────┘  └───────────┘  └───────────┘     │   │   │
│  │  │                                                     │   │   │
│  │  └─────────────────────────────────────────────────────┘   │   │
│  └─────────────────────────────┬───────────────────────────────┘   │
│                                │                                    │
│              ┌─────────────────┼─────────────────┐                  │
│              │                 │                 │                  │
│              ▼                 ▼                 ▼                  │
│  ┌───────────────┐    ┌───────────────┐   ┌───────────────┐       │
│  │  PostgreSQL   │    │     Redis     │   │   File Store  │       │
│  │    (Data)     │    │   (Cache)     │   │   (Images)    │       │
│  └───────────────┘    └───────────────┘   └───────────────┘       │
│                                                                     │
│  EXTERNAL SERVICES:                                                 │
│  ┌───────────────┐    ┌───────────────┐   ┌───────────────┐       │
│  │   WhatsApp    │    │   Email       │   │     RDM       │       │
│  │  (Green API)  │    │   (SMTP)      │   │   (Export)    │       │
│  └───────────────┘    └───────────────┘   └───────────────┘       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Application Structure

```
┌─────────────────────────────────────────────────────────────────────┐
│                    APPLICATION STRUCTURE                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  SIMT-MTs/                                                          │
│  ├── app/                          # Laravel Application            │
│  │   ├── Http/                                                     │
│  │   │   ├── Controllers/        # API Controllers                 │
│  │   │   │   ├── Auth/                                           │
│  │   │   │   ├── Student/                                       │
│  │   │   │   ├── Class/                                          │
│  │   │   │   ├── Attendance/                                    │
│  │   │   │   ├── Assessment/                                     │
│  │   │   │   ├── Finance/                                        │
│  │   │   │   └── Dashboard/                                      │
│  │   │   │                                                       │
│  │   │   ├── Middleware/       # Custom Middleware                │
│  │   │   │   ├── RoleMiddleware.php                               │
│  │   │   │   └── AuditMiddleware.php                              │
│  │   │   │                                                       │
│  │   │   ├── Requests/       # Form Validation                    │
│  │   │   └── Resources/      # API Resources (transformers)       │
│  │   │                                                       │
│  │   ├── Models/             # Eloquent Models                    │
│  │   │   ├── User.php                                        │
│  │   │   ├── Student.php                                      │
│  │   │   ├── ClassRoom.php                                    │
│  │   │   ├── Attendance.php                                    │
│  │   │   ├── Score.php                                        │
│  │   │   ├── Bill.php                                         │
│  │   │   └── Payment.php                                       │
│  │   │                                                       │
│  │   ├── Services/            # Business Logic                    │
│  │   │   ├── AuthService.php                                   │
│  │   │   ├── StudentService.php                                │
│  │   │   ├── AttendanceService.php                             │
│  │   │   ├── ScoreCalculationService.php                       │
│  │   │   └── ReportService.php                                 │
│  │   │                                                       │
│  │   ├── Notifications/       # Laravel Notifications            │
│  │   │   ├── WhatsAppNotification.php                          │
│  │   │   ├── AttendanceNotification.php                        │
│  │   │   └── PaymentReminderNotification.php                   │
│  │   │                                                       │
│  │   └── Enums/               # PHP Enums (PHP 8.1+)             │
│  │       ├── UserRole.php                                      │
│  │       ├── AttendanceStatus.php                               │
│  │       └── PaymentStatus.php                                  │
│  │                                                               │
│  ├── config/                    # Laravel Config                   │
│  ├── database/                  # Database                         │
│  │   ├── migrations/                                          │
│  │   ├── seeders/                                             │
│  │   └── factories/                                           │
│  │                                                               │
│  ├── routes/                     # API Routes                      │
│  │   ├── api.php                                            │
│  │   └── web.php                                            │
│  │                                                               │
│  ├── resources/                  # Frontend (Vue)                  │
│  │   ├── js/                                                    │
│  │   │   ├── app.js                                            │
│  │   │   ├── router/                                           │
│  │   │   ├── stores/                                           │
│  │   │   ├── components/                                       │
│  │   │   ├── pages/                                            │
│  │   │   └── composables/                                      │
│  │   │                                                       │
│  │   └── css/                                                   │
│  │                                                               │
│  ├── tests/                      # Testing                         │
│  │   ├── Feature/                                            │
│  │   └── Unit/                                               │
│  │                                                               │
│  ├── docker/                     # Docker Configuration            │
│  └── composer.json                                             │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.3 Directory Structure (Vue Frontend)

```
┌─────────────────────────────────────────────────────────────────────┐
│                  FRONTEND DIRECTORY STRUCTURE                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  resources/js/                                                      │
│  ├── app.js                    # Vue app entry point                │
│  ├── app.vue                   # Root component                     │
│  │                                                               │
│  ├── router/                   # Vue Router                         │
│  │   ├── index.js              # Router configuration              │
│  │   └── routes.js             # Route definitions                 │
│  │                                                               │
│  ├── stores/                   # Pinia Stores                       │
│  │   ├── index.js              # Store registry                    │
│  │   ├── auth.js               # Authentication store              │
│  │   ├── student.js            # Student management store          │
│  │   ├── attendance.js         # Attendance store                  │
│  │   ├── score.js              # Score/assessment store            │
│  │   ├── finance.js            # Finance store                     │
│  │   └── notification.js       # Notification store                │
│  │                                                               │
│  ├── composables/              # Vue Composables (hooks)            │
│  │   ├── useApi.js             # API request helper                │
│  │   ├── useAuth.js            # Auth helpers                      │
│  │   ├── useRole.js            # Role checking                     │
│  │   └── useNotification.js    # Toast/notification                │
│  │                                                               │
│  ├── components/               # Vue Components                     │
│  │   ├── common/               # Shared components                 │
│  │   │   ├── AppButton.vue                                   │
│  │   │   ├── AppInput.vue                                    │
│  │   │   ├── AppSelect.vue                                    │
│  │   │   ├── AppModal.vue                                     │
│  │   │   ├── AppTable.vue                                     │
│  │   │   ├── AppPagination.vue                                 │
│  │   │   ├── AppToast.vue                                      │
│  │   │   ├── AppCard.vue                                       │
│  │   │   ├── AppBadge.vue                                      │
│  │   │   └── AppSpinner.vue                                    │
│  │   │                                                       │
│  │   ├── layout/               # Layout components                 │
│  │   │   ├── AppSidebar.vue                                    │
│  │   │   ├── AppHeader.vue                                     │
│  │   │   ├── AppFooter.vue                                     │
│  │   │   └── AppLayout.vue                                     │
│  │   │                                                       │
│  │   ├── student/              # Student module components         │
│  │   ├── attendance/           # Attendance components             │
│  │   ├── score/                # Score components                  │
│  │   ├── finance/              # Finance components                │
│  │   └── dashboard/            # Dashboard components              │
│  │                                                               │
│  ├── pages/                    # Page Components (Views)            │
│  │   ├── Auth/                                                  │
│  │   │   ├── Login.vue                                         │
│  │   │   └── ForgotPassword.vue                                 │
│  │   │                                                       │
│  │   ├── Dashboard/                                             │
│  │   │   └── Index.vue                                         │
│  │   │                                                       │
│  │   ├── Student/                                              │
│  │   │   ├── Index.vue                                         │
│  │   │   ├── Create.vue                                        │
│  │   │   └── Show.vue                                          │
│  │   │                                                       │
│  │   ├── Attendance/                                           │
│  │   │   ├── Index.vue                                         │
│  │   │   └── Create.vue                                        │
│  │   │                                                       │
│  │   ├── Score/                                                │
│  │   │   ├── Index.vue                                         │
│  │   │   ├── Input.vue                                         │
│  │   │   └── Report.vue                                        │
│  │   │                                                       │
│  │   ├── Finance/                                              │
│  │   │   ├── Index.vue                                         │
│  │   │   ├── Billing.vue                                       │
│  │   │   └── Payment.vue                                       │
│  │   │                                                       │
│  │   └── Parent/                                               │
│  │       ├── Index.vue                                         │
│  │       └── ChildDetail.vue                                    │
│  │                                                               │
│  └── utils/                    # Utilities                         │
│      ├── api.js                # Axios instance + interceptors     │
│      ├── helpers.js            # Helper functions                  │
│      └── constants.js          # App constants                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 4. DATA ARCHITECTURE

### 4.1 Database Design

```
┌─────────────────────────────────────────────────────────────────────┐
│                      DATABASE ARCHITECTURE                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ═══ POSTGRESQL 15 ═══                                             │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │  DATABASE: simt_mts                                           │   │
│  │                                                             │   │
│  │  ┌─────────────────────────────────────────────────────┐   │   │
│  │  │  SCHEMA: public                                       │   │   │
│  │  │                                                       │   │   │
│  │  │  TABLES:                                              │   │   │
│  │  │  ├── users (with role enum)                           │   │   │
│  │  │  ├── students (biodata)                               │   │   │
│  │  │  ├── student_guardians (family info)                  │   │   │
│  │  │  ├── classes (rombel)                                 │   │   │
│  │  │  ├── subjects (mapel)                                 │   │   │
│  │  │  ├── class_enrollments (history)                      │   │   │
│  │  │  ├── schedules (jadwal)                               │   │   │
│  │  │  ├── attendances (presensi)                           │   │   │
│  │  │  ├── assessments (jenis nilai)                        │   │   │
│  │  │  ├── scores (nilai siswa)                             │   │   │
│  │  │  ├── reports (rapor)                                  │   │   │
│  │  │  ├── billing_components (komponen tagihan)            │   │   │
│  │  │  ├── student_bills (tagihan)                          │   │   │
│  │  │  ├── payments (pembayaran)                            │   │   │
│  │  │  ├── notifications (log notifikasi)                   │   │   │
│  │  │  ├── audit_logs (log aktivitas)                       │   │   │
│  │  │  └── settings (konfigurasi)                           │   │   │
│  │  │                                                       │   │   │
│  │  └─────────────────────────────────────────────────────┘   │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ═══ FILE STORAGE ═══                                              │
│  └── Local disk (public/storage) or S3-compatible (future)         │
│      ├── avatars/           (user & student photos)                │
│      ├── documents/         (rapor PDF, etc)                       │
│      └── receipts/          (payment proof)                        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.2 Entity Relationship (Simplified for MVP)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ENTITY RELATIONSHIP (MVP)                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                    users (1)                                        │
│                       │                                             │
│                       │ 1:N                                         │
│                       ▼                                             │
│            ┌─────────────────┐                                      │
│            │    students     │                                      │
│            └────────┬────────┘                                      │
│                     │                                               │
│        ┌───────────┼───────────┐                                    │
│        │ 1:N       │           │ N:1                                │
│        ▼           │           ▼                                    │
│   ┌────────┐       │      ┌──────────┐                             │
│   │family  │       │      │  classes │                             │
│   │(guardian│      │      └────┬─────┘                             │
│   └────────┘       │           │                                    │
│                    │           │ 1:N                                │
│                    │           ▼                                    │
│                    │    ┌───────────────┐                           │
│                    │    │enrollments   │                           │
│                    │    └───────┬───────┘                           │
│                    │            │                                   │
│                    │            │ 1:N                               │
│                    │            ▼                                   │
│                    │    ┌───────────────┐                           │
│                    │    │  attendances  │                           │
│                    │    └───────┬───────┘                           │
│                    │            │                                   │
│                    │            │ 1:N                               │
│                    │            ▼                                   │
│                    │    ┌───────────────┐                           │
│                    │    │   scores      │                           │
│                    │    └───────────────┘                           │
│                    │                                               │
│                    │ 1:N                                           │
│                    ▼                                               │
│            ┌───────────────┐                                       │
│            │ student_bills │                                       │
│            └───────┬───────┘                                       │
│                    │                                               │
│                    │ 1:N                                           │
│                    ▼                                               │
│            ┌───────────────┐                                       │
│            │   payments    │                                       │
│            └───────────────┘                                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.3 Key Tables Schema (MVP)

```sql
-- =====================================================
-- CORE TABLES FOR MVP (Simplified)
-- =====================================================

-- USERS
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL, -- 'admin', 'headmaster', 'teacher', 'tu', 'bk', 'parent'
    phone VARCHAR(20),
    avatar_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- STUDENTS
CREATE TABLE students (
    id BIGSERIAL PRIMARY KEY,
    nis VARCHAR(20) UNIQUE,
    nisn VARCHAR(20),
    nik VARCHAR(16) UNIQUE,
    name VARCHAR(255) NOT NULL,
    gender VARCHAR(10),
    place_of_birth VARCHAR(100),
    date_of_birth DATE,
    address TEXT,
    photo_url VARCHAR(500),
    status VARCHAR(20) DEFAULT 'active', -- 'active', 'graduated', 'transferred'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- STUDENT GUARDIANS (simplified)
CREATE TABLE student_guardians (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT REFERENCES students(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    relationship VARCHAR(50),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255),
    occupation VARCHAR(100),
    address TEXT,
    is_primary BOOLEAN DEFAULT TRUE,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL, -- for portal access
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CLASSES (Rombel)
CREATE TABLE classes (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level VARCHAR(10) NOT NULL, -- '7', '8', '9'
    academic_year VARCHAR(9) NOT NULL, -- '2026/2027'
    homeroom_teacher_id BIGINT REFERENCES users(id),
    capacity INTEGER DEFAULT 40,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(name, academic_year)
);

-- CLASS ENROLLMENTS
CREATE TABLE class_enrollments (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT REFERENCES students(id) ON DELETE CASCADE,
    class_id BIGINT REFERENCES classes(id) ON DELETE CASCADE,
    academic_year VARCHAR(9) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, class_id, academic_year)
);

-- SUBJECTS
CREATE TABLE subjects (
    id BIGSERIAL PRIMARY KEY,
    code VARCHAR(20),
    name VARCHAR(255) NOT NULL,
    short_name VARCHAR(50),
    level VARCHAR(10), -- '7', '8', '9', or NULL for all
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TEACHER SUBJECTS (mapping)
CREATE TABLE teacher_subjects (
    id BIGSERIAL PRIMARY KEY,
    teacher_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    subject_id BIGINT REFERENCES subjects(id) ON DELETE CASCADE,
    class_id BIGINT REFERENCES classes(id) ON DELETE CASCADE,
    UNIQUE(teacher_id, subject_id, class_id)
);

-- ATTENDANCES
CREATE TABLE attendances (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT REFERENCES students(id) ON DELETE CASCADE,
    class_id BIGINT REFERENCES classes(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    status VARCHAR(10) NOT NULL, -- 'H', 'I', 'S', 'A'
    arrival_time TIME,
    notes TEXT,
    recorded_by BIGINT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, date)
);

-- ASSESSMENTS
CREATE TABLE assessments (
    id BIGSERIAL PRIMARY KEY,
    subject_id BIGINT REFERENCES subjects(id) ON DELETE CASCADE,
    class_id BIGINT REFERENCES classes(id) ON DELETE CASCADE,
    type VARCHAR(20) NOT NULL, -- 'formatif', 'uts', 'uas'
    title VARCHAR(255),
    date DATE,
    max_score DECIMAL(5,2) DEFAULT 100,
    weight DECIMAL(5,2) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SCORES
CREATE TABLE scores (
    id BIGSERIAL PRIMARY KEY,
    assessment_id BIGINT REFERENCES assessments(id) ON DELETE CASCADE,
    student_id BIGINT REFERENCES students(id) ON DELETE CASCADE,
    score DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(assessment_id, student_id)
);

-- REPORTS (Rapor)
CREATE TABLE reports (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT REFERENCES students(id) ON DELETE CASCADE,
    class_id BIGINT REFERENCES classes(id) ON DELETE CASCADE,
    academic_year VARCHAR(9) NOT NULL,
    semester VARCHAR(10) NOT NULL, -- 'ganjil', 'genap'
    status VARCHAR(20) DEFAULT 'draft', -- 'draft', 'final'
    final_score JSONB, -- calculated final scores per subject
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- BILLING COMPONENTS
CREATE TABLE billing_components (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(20) NOT NULL, -- 'monthly', 'annual', 'once'
    amount DECIMAL(12,2) NOT NULL,
    due_day INTEGER DEFAULT 15,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- STUDENT BILLS
CREATE TABLE student_bills (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT REFERENCES students(id) ON DELETE CASCADE,
    billing_component_id BIGINT REFERENCES billing_components(id) ON DELETE CASCADE,
    amount DECIMAL(12,2) NOT NULL,
    due_date DATE,
    status VARCHAR(20) DEFAULT 'unpaid', -- 'unpaid', 'partial', 'paid'
    paid_amount DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PAYMENTS
CREATE TABLE payments (
    id BIGSERIAL PRIMARY KEY,
    student_bill_id BIGINT REFERENCES student_bills(id) ON DELETE CASCADE,
    amount DECIMAL(12,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(20), -- 'cash', 'transfer'
    reference_number VARCHAR(100),
    proof_url VARCHAR(500),
    validated_by BIGINT REFERENCES users(id),
    validated_at TIMESTAMP,
    status VARCHAR(20) DEFAULT 'pending', -- 'pending', 'validated', 'rejected'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- NOTIFICATIONS LOG
CREATE TABLE notifications (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(50) NOT NULL, -- 'whatsapp', 'email', 'app'
    recipient VARCHAR(100),
    message TEXT,
    status VARCHAR(20) DEFAULT 'pending', -- 'pending', 'sent', 'failed'
    sent_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- INDEXES
CREATE INDEX idx_students_nis ON students(nis);
CREATE INDEX idx_students_status ON students(status);
CREATE INDEX idx_attendances_date ON attendances(date);
CREATE INDEX idx_attendances_student ON attendances(student_id);
CREATE INDEX idx_scores_assessment ON scores(assessment_id);
CREATE INDEX idx_payments_bill ON payments(student_bill_id);
```

---

## 5. API ARCHITECTURE

### 5.1 API Design Principles

```
┌─────────────────────────────────────────────────────────────────────┐
│                    API DESIGN PRINCIPLES                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  1. RESTful Design                                                  │
│  ├── Resource-based URLs (/api/v1/students)                        │
│  ├── HTTP methods (GET, POST, PUT, DELETE)                         │
│  └── Standard status codes (200, 201, 400, 401, 404, 500)         │
│                                                                     │
│  2. JSON Response Format                                            │
│  ├── Consistent response structure                                  │
│  ├── Error messages yang informatif                                 │
│  └── Pagination untuk list endpoints                               │
│                                                                     │
│  3. Authentication                                                  │
│  ├── JWT Bearer Token                                               │
│  ├── Token expiration: 1 hour (access), 7 days (refresh)           │
│  └── Role-based access dalam token claims                           │
│                                                                     │
│  4. Versioning                                                      │
│  ├── URL-based versioning (/api/v1/)                               │
│  └── Backward compatibility untuk minor versions                   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.2 API Response Format

```json
// Success Response
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Ahmad Fauzi",
        "class": "7A"
    },
    "meta": {
        "page": 1,
        "per_page": 20,
        "total": 100
    },
    "message": "Success"
}

// Error Response
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid",
        "details": {
            "email": ["The email field is required."]
        }
    }
}
```

### 5.3 Core API Endpoints (MVP)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    CORE API ENDPOINTS                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ═══ AUTHENTICATION ═══                                             │
│  POST   /api/v1/auth/login              # Login                     │
│  POST   /api/v1/auth/logout             # Logout                    │
│  POST   /api/v1/auth/refresh            # Refresh token             │
│  POST   /api/v1/auth/forgot-password    # Request reset             │
│  POST   /api/v1/auth/reset-password     # Reset password            │
│                                                                     │
│  ═══ USER MANAGEMENT ═══                                            │
│  GET    /api/v1/users                   # List users (admin)        │
│  POST   /api/v1/users                   # Create user (admin)       │
│  GET    /api/v1/users/:id               # Get user detail           │
│  PUT    /api/v1/users/:id               # Update user               │
│  GET    /api/v1/me                      # Current user profile      │
│  PUT    /api/v1/me                      # Update own profile        │
│                                                                     │
│  ═══ STUDENTS ═══                                                  │
│  GET    /api/v1/students                # List students             │
│  POST   /api/v1/students                # Create student            │
│  GET    /api/v1/students/:id            # Get student detail        │
│  PUT    /api/v1/students/:id            # Update student            │
│  DELETE /api/v1/students/:id            # Soft delete student       │
│  POST   /api/v1/students/import         # Import from Excel         │
│  GET    /api/v1/students/:id/guardians  # Get guardians             │
│                                                                     │
│  ═══ CLASSES ═══                                                   │
│  GET    /api/v1/classes                 # List classes              │
│  POST   /api/v1/classes                 # Create class              │
│  GET    /api/v1/classes/:id             # Get class detail          │
│  PUT    /api/v1/classes/:id             # Update class              │
│  GET    /api/v1/classes/:id/students    # Get class students        │
│  POST   /api/v1/classes/:id/enroll      # Enroll student            │
│                                                                     │
│  ═══ ATTENDANCE ═══                                                │
│  GET    /api/v1/attendances             # List attendances          │
│  POST   /api/v1/attendances             # Create attendance record  │
│  POST   /api/v1/attendances/bulk        # Bulk create attendance    │
│  GET    /api/v1/attendances/rekap       # Get attendance recap      │
│                                                                     │
│  ═══ ASSESSMENTS ═══                                               │
│  GET    /api/v1/subjects                # List subjects             │
│  GET    /api/v1/assessments             # List assessments          │
│  POST   /api/v1/assessments             # Create assessment         │
│  GET    /api/v1/assessments/:id/scores  # Get assessment scores     │
│  POST   /api/v1/scores                  # Input score               │
│  POST   /api/v1/scores/bulk             # Bulk input scores         │
│                                                                     │
│  ═══ REPORTS ═══                                                   │
│  GET    /api/v1/reports/:studentId      # Get student report        │
│  POST   /api/v1/reports/:studentId/generate  # Generate report       │
│  GET    /api/v1/reports/:studentId/pdf  # Export report as PDF      │
│                                                                     │
│  ═══ FINANCE ═══                                                   │
│  GET    /api/v1/billing-components      # List billing components   │
│  POST   /api/v1/billing-components      # Create billing component  │
│  GET    /api/v1/student-bills           # List student bills        │
│  POST   /api/v1/student-bills/generate  # Generate bills            │
│  GET    /api/v1/payments                # List payments             │
│  POST   /api/v1/payments                # Create payment            │
│  PUT    /api/v1/payments/:id/validate   # Validate payment (TU)     │
│                                                                     │
│  ═══ PARENT PORTAL ═══                                             │
│  GET    /api/v1/parent/dashboard        # Parent dashboard          │
│  GET    /api/v1/parent/children         # Get children info         │
│  GET    /api/v1/parent/children/:id/attendance  # Child attendance   │
│  GET    /api/v1/parent/children/:id/scores     # Child scores       │
│  GET    /api/v1/parent/children/:id/bills      # Child bills        │
│                                                                     │
│  ═══ DASHBOARD ═══                                                 │
│  GET    /api/v1/dashboard/overview      # Overview statistics       │
│  GET    /api/v1/dashboard/attendance    # Attendance summary        │
│  GET    /api/v1/dashboard/scores        # Scores summary            │
│  GET    /api/v1/dashboard/finance       # Finance summary           │
│                                                                     │
│  ═══ NOTIFICATIONS ═══                                             │
│  POST   /api/v1/notifications/whatsapp  # Send WhatsApp             │
│  GET    /api/v1/notifications           # List notifications        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 6. SECURITY ARCHITECTURE

### 6.1 Security Layers

```
┌─────────────────────────────────────────────────────────────────────┐
│                     SECURITY ARCHITECTURE                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    EXTERNAL LAYER                           │   │
│  │                                                             │   │
│  │  Cloudflare (WAF + DDoS Protection)                        │   │
│  │  ├── Rate limiting (100 req/min per IP)                    │   │
│  │  ├── Bot protection                                         │   │
│  │  ├── SSL/TLS termination                                    │   │
│  │  └── CDN for static assets                                  │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                              │                                      │
│                              ▼                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    APPLICATION LAYER                        │   │
│  │                                                             │   │
│  │  Laravel Security Middleware                                │   │
│  │  ├── CORS configuration                                     │   │
│  │  ├── CSRF protection (for web routes)                      │   │
│  │  ├── XSS sanitization                                       │   │
│  │  ├── SQL injection prevention (Eloquent)                   │   │
│  │  ├── Rate limiting (API)                                    │   │
│  │  └── JWT validation                                         │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                              │                                      │
│                              ▼                                      │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                     DATA LAYER                              │   │
│  │                                                             │   │
│  │  Database Security                                          │   │
│  │  ├── Password hashing (bcrypt)                             │   │
│  │  ├── Parameterized queries (Eloquent)                      │   │
│  │  ├── Row-level security (RBAC in app)                      │   │
│  │  └── Audit logging                                          │   │
│  │                                                             │   │
│  │  File Storage                                               │   │
│  │  ├── Signed URLs for uploads                               │   │
│  │  └── File type validation                                   │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 6.2 Authentication Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                  AUTHENTICATION FLOW                                 │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  LOGIN FLOW:                                                        │
│                                                                     │
│  1. User submits email + password                                   │
│  2. Server validates credentials                                    │
│  3. Server generates JWT (access + refresh)                        │
│  4. Return tokens to client                                         │
│  5. Client stores in localStorage (or httpOnly cookie)             │
│                                                                     │
│  AUTHENTICATED REQUEST:                                             │
│                                                                     │
│  1. Client sends request with Authorization: Bearer <token>        │
│  2. Server validates JWT signature and expiration                  │
│  3. Server extracts user ID and role from token                    │
│  4. Server checks RBAC permissions                                  │
│  5. If authorized, process request; else return 403                │
│                                                                     │
│  REFRESH TOKEN FLOW:                                                │
│                                                                     │
│  1. Access token expires                                           │
│  2. Client sends refresh token to /auth/refresh                    │
│  3. Server validates refresh token                                 │
│  4. Server issues new access token                                 │
│  5. Client updates stored token                                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 7. PERFORMANCE ARCHITECTURE

### 7.1 Performance Strategies

```
┌─────────────────────────────────────────────────────────────────────┐
│                    PERFORMANCE STRATEGIES                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FRONTEND OPTIMIZATION:                                             │
│  ├── Lazy loading untuk route components                           │
│  ├── Image optimization (WebP, lazy load)                           │
│  ├── Code splitting (per route)                                    │
│  ├── Gzip compression (Nginx)                                      │
│  └── Service worker untuk offline capability (future)              │
│                                                                     │
│  BACKEND OPTIMIZATION:                                              │
│  ├── Database indexing yang tepat                                  │
│  ├── Eager loading (avoid N+1 queries)                             │
│  ├── Caching untuk data yang jarang berubah (file-based for MVP)   │
│  ├── Pagination untuk list endpoints                               │
│  └── Queue untuk heavy operations (future with Redis)              │
│                                                                     │
│  INFRASTRUCTURE OPTIMIZATION:                                       │
│  ├── CDN untuk static assets (Cloudflare free)                     │
│  ├── Gzip compression di server                                    │
│  ├── HTTP/2 support                                                │
│  └── Database query optimization                                    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 7.2 Caching Strategy (MVP)

```
┌─────────────────────────────────────────────────────────────────────┐
│                      CACHING STRATEGY                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  MVP CACHING (Simple - No Redis yet):                              │
│                                                                     │
│  APPLICATION CACHE (Laravel Cache - File Driver):                  │
│  ├── Route definitions: 1 hour                                     │
│  ├── User roles/permissions: 1 hour                                │
│  ├── Subject list: 1 day                                           │
│  └── Class list: 1 hour                                            │
│                                                                     │
│  QUERY CACHE (Manual):                                             │
│  ├── Dashboard statistics: 5 minutes                               │
│  ├── Attendance recap: 15 minutes                                  │
│  └── Finance summary: 30 minutes                                   │
│                                                                     │
│  FUTURE (When revenue allows):                                     │
│  └── Redis untuk caching yang lebih efisien                        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 8. DEPLOYMENT ARCHITECTURE

### 8.1 MVP Deployment (Single Server)

```
┌─────────────────────────────────────────────────────────────────────┐
│                  MVP DEPLOYMENT ARCHITECTURE                         │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                      VPS (2GB RAM, 1 Core)                  │   │
│  │                                                             │   │
│  │  ┌─────────────────────────────────────────────────────┐   │   │
│  │  │                   NGINX                               │   │   │
│  │  │  ┌─────────────────────────────────────────────┐   │   │   │
│  │  │  │         Reverse Proxy + SSL Termination      │   │   │   │
│  │  │  └─────────────────────────────────────────────┘   │   │   │
│  │  │                      │                             │   │   │
│  │  └──────────────────────┼─────────────────────────────┘   │   │
│  │                         │                                  │   │
│  │  ┌──────────────────────┼─────────────────────────────┐   │   │
│  │  │         PHP-FPM (Laravel)                          │   │   │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐        │   │   │
│  │  │  │  Worker  │  │  Worker  │  │  Worker  │        │   │   │
│  │  │  │    1    │  │    2    │  │    3    │        │   │   │
│  │  │  └──────────┘  └──────────┘  └──────────┘        │   │   │
│  │  │                      │                             │   │   │
│  │  └──────────────────────┼─────────────────────────────┘   │   │
│  │                         │                                  │   │
│  │  ┌──────────────────────┼─────────────────────────────┐   │   │
│  │  │                PostgreSQL                          │   │   │
│  │  │                (on same VPS)                       │   │   │
│  │  └─────────────────────────────────────────────────────┘   │   │
│  │                                                             │   │
│  │  ┌─────────────────────────────────────────────────────┐   │   │
│  │  │                   File Storage                       │   │   │
│  │  │            (/var/www/simt/storage)                   │   │   │
│  │  └─────────────────────────────────────────────────────┘   │   │
│  │                                                             │   │
│  └─────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  PROVIDER: Indonesia VPS (NusaCloud / IDCloudhost)                 │
│  COST: ~Rp 300.000/bulan                                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Future Scaling (Phase 2+)

```
┌─────────────────────────────────────────────────────────────────────┐
│              FUTURE SCALING ARCHITECTURE                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  PHASE 2: Database Separation                                       │
│  ├── Move PostgreSQL to managed database (Supabase/Railway)        │
│  ├── Add Redis for caching                                          │
│  └── Keep app server on VPS                                         │
│                                                                     │
│  PHASE 3: Load Balancing                                            │
│  ├── Add load balancer (Cloudflare)                                │
│  ├── Scale to 2 app servers                                         │
│  └── Use managed database                                           │
│                                                                     │
│  PHASE 4: Multi-Region                                              │
│  ├── CDN untuk static assets                                        │
│  ├── Database read replicas                                         │
│  └──可能的 di另外的 region (Jawa Barat, etc)                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 9. MONITORING & LOGGING

### 9.1 Monitoring Strategy (Budget-Conscious)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    MONITORING STRATEGY                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  MVP MONITORING (Free/Minimal Cost):                               │
│                                                                     │
│  ERROR TRACKING:                                                    │
│  └── Laravel Log (file-based) + Sentry Free Tier (future)         │
│                                                                     │
│  PERFORMANCE MONITORING:                                            │
│  └── Laravel Debugbar (development) + Custom logging (production)  │
│                                                                     │
│  UPTIME MONITORING:                                                 │
│  └── UptimeRobot (free tier - 50 monitors)                         │
│                                                                     │
│  LOGGING:                                                           │
│  ├── Application logs: storage/logs/laravel.log                    │
│  ├── Access logs: Nginx access log                                 │
│  ├── Error logs: Nginx error log                                   │
│  └── Audit logs: Custom table (audit_logs)                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 9.2 Alerting Strategy

```
┌─────────────────────────────────────────────────────────────────────┐
│                      ALERTING STRATEGY                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  CRITICAL ALERTS (Immediate):                                       │
│  ├── Application down (UptimeRobot)                                 │
│  ├── Database connection failed                                     │
│  └── Unauthorized access attempt (manual check logs)               │
│                                                                     │
│  WARNING ALERTS (Same day):                                         │
│  ├── High error rate (>1%)                                         │
│  ├── Slow response (>5s)                                           │
│  └── Disk space > 80%                                              │
│                                                                     │
│  NOTIFICATION CHANNEL:                                              │
│  └── Email ke developer (simple, free)                             │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 10. BACKUP & DISASTER RECOVERY

### 10.1 Backup Strategy (MVP)

```
┌─────────────────────────────────────────────────────────────────────┐
│                      BACKUP STRATEGY                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  MVP BACKUP (Manual/cheap):                                        │
│                                                                     │
│  DATABASE:                                                          │
│  ├── Daily automated dump via cron                                 │
│  ├── Store on server: /backup/db/                                  │
│  ├── Retention: 7 days (local)                                     │
│  └── Manual upload ke Google Drive (weekly)                        │
│                                                                     │
│  FILES:                                                             │
│  ├── Weekly backup via rsync to separate location                  │
│  └── Google Drive for critical files                               │
│                                                                     │
│  IMPLEMENTATION:                                                    │
│  └── Simple bash script + cron job (no cost)                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 10.2 Recovery Time Objective (RTO)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    RTO TARGETS                                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  SCENARIO: Database corruption                                     │
│  ├── Detection: Log monitoring                                     │
│  ├── Recovery: Restore from latest backup                          │
│  └── RTO: 2-4 hours (manual process)                               │
│                                                                     │
│  SCENARIO: Server failure                                          │
│  ├── Detection: Uptime monitoring                                  │
│  ├── Recovery: Deploy to new VPS from repo                         │
│  └── RTO: 4-8 hours (depending on provider)                        │
│                                                                     │
│  SCENARIO: Code bug causing data corruption                        │
│  ├── Detection: User report / monitoring                          │
│  ├── Recovery: Rollback to previous version                        │
│  └── RTO: 1-2 hours                                                │
│                                                                     │
│  NOTE: MVP acceptable karena single school, bukan 24/7 operation   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## APPENDIX: QUICK REFERENCE

```
┌─────────────────────────────────────────────────────────────────────┐
│                  ARCHITECTURE QUICK REFERENCE                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  TECHNOLOGY STACK:                                                  │
│  ├── Frontend: Vue.js 3 + Vite + TailwindCSS                      │
│  ├── Backend: Laravel 10 + PHP 8.2                                 │
│  ├── Database: PostgreSQL 15                                       │
│  └── Infrastructure: VPS Indonesia + Cloudflare                    │
│                                                                     │
│  KEY PATTERNS:                                                      │
│  ├── API: RESTful JSON                                             │
│  ├── Auth: JWT (Laravel Sanctum)                                   │
│  ├── State: Pinia (Vue)                                            │
│  └── Architecture: Modular Monolith (MVP)                          │
│                                                                     │
│  SCALABILITY PATH:                                                  │
│  ├── MVP: Single VPS, monolithic                                   │
│  ├── Phase 2: Managed DB, add caching                              │
│  └── Phase 3: Load balancing, multi-server                         │
│                                                                     │
│  BUDGET OPTIMIZATION:                                               │
│  ├── All open-source tools                                         │
│  ├── Free tier services (Cloudflare, UptimeRobot)                  │
│  └── Simple architecture to reduce complexity                      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*
*Budget: Rp 5.000.000 | Timeline: 3 Bulan*