# DESIGN SYSTEM & DATABASE SCHEMA (ERD)
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs/YAYASAN

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** DRAFT  
**Penulis:** Tim Proyek SIMT MTs

---

## BAGIAN 1: DESAIN SISTEM (SYSTEM DESIGN)

### 1.1 Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ARSITEKTUR SISTEM SIMT MTs                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                         INTERNET                                    │
│                            │                                       │
│                            ▼                                       │
│                 ┌──────────────────────┐                           │
│                 │     CLOUDflare       │                           │
│                 │  (CDN + WAF + SSL)  │                           │
│                 └──────────┬───────────┘                           │
│                            │                                       │
│                            ▼                                       │
│                 ┌──────────────────────┐                           │
│                 │    LOAD BALANCER     │                           │
│                 │   (Nginx / HAProxy)  │                           │
│                 └──────────┬───────────┘                           │
│                            │                                       │
│          ┌─────────────────┼─────────────────┐                     │
│          │                 │                 │                     │
│          ▼                 ▼                 ▼                     │
│   ┌────────────┐    ┌────────────┐    ┌────────────┐              │
│   │  Web Node  │    │  Web Node  │    │  Web Node  │              │
│   │   (Pod)    │    │   (Pod)    │    │   (Pod)    │              │
│   │  Node.js   │    │  Node.js   │    │  Node.js   │              │
│   └──────┬─────┘    └──────┬─────┘    └──────┬─────┘              │
│          │                 │                 │                     │
│          └─────────────────┼─────────────────┘                     │
│                            │                                       │
│                            ▼                                       │
│   ┌─────────────────────────────────────────────────────────────┐  │
│   │                    MESSAGE QUEUE                            │  │
│   │              (Redis / RabbitMQ)                             │  │
│   │    ├── Email queue      ├── WA notification queue          │  │
│   │    ├── Report generation├── Sync queue                     │  │
│   └─────────────────────────────────────────────────────────────┘  │
│                            │                                       │
│          ┌─────────────────┼─────────────────┐                     │
│          │                 │                 │                     │
│          ▼                 ▼                 ▼                     │
│   ┌────────────┐    ┌────────────┐    ┌────────────┐              │
│   │ PostgreSQL │    │    Redis   │    │    S3      │              │
│   │  Primary   │    │   Cache    │    │   Object   │              │
│   └──────┬─────┘    │            │    │   Storage  │              │
│          │          └────────────┘    └────────────┘              │
│          │                                                         │
│          ▼                                                         │
│   ┌────────────┐                                                   │
│   │ PostgreSQL │                                                   │
│   │  Replica   │                                                   │
│   └────────────┘                                                   │
│                                                                     │
│   EXTERNAL INTEGRATIONS:                                            │
│   ┌──────────────┐ ┌──────────────┐ ┌──────────────┐              │
│   │    EMIS      │ │    RDM       │ │  WhatsApp    │              │
│   │   (Kemenag)  │ │  (Kemenag)   │ │   (Green)    │              │
│   └──────────────┘ └──────────────┘ └──────────────┘              │
│          │                │                │                       │
│   ┌──────────────┐ ┌──────────────┐ ┌──────────────┐              │
│   │   DAPODIK    │ │  Midtrans    │ │   Monitoring │              │
│   │ (Kemendikbud)│ │   (Payment)  │ │  (Prometheus)│              │
│   └──────────────┘ └──────────────┘ └──────────────┘              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 1.2 Arsitektur Microservices (Future)

```
┌─────────────────────────────────────────────────────────────────────┐
│              ARSITEKTUR MICROSERVICES (SCALING)                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                      API GATEWAY                                    │
│               (Kong / AWS API Gateway)                              │
│                            │                                       │
│          ┌─────────────────┼─────────────────┐                     │
│          │                 │                 │                     │
│          ▼                 ▼                 ▼                     │
│   ┌──────────────┐  ┌──────────────┐  ┌──────────────┐            │
│   │   Akademik   │  │  Keuangan    │  │  Kesiswaan   │            │
│   │   Service    │  │   Service    │  │   Service    │            │
│   │  :3001       │  │   :3002      │  │   :3003      │            │
│   └──────────────┘  └──────────────┘  └──────────────┘            │
│          │                 │                 │                     │
│          ▼                 ▼                 ▼                     │
│   ┌──────────────┐  ┌──────────────┐  ┌──────────────┐            │
│   │   Tahfiz     │  │   Inklusi    │  │     BK      │            │
│   │   Service    │  │   Service    │  │   Service    │            │
│   │   :3004      │  │   :3005      │  │   :3006      │            │
│   └──────────────┘  └──────────────┘  └──────────────┘            │
│          │                 │                 │                     │
│          └─────────────────┼─────────────────┘                     │
│                            │                                       │
│                            ▼                                       │
│                 ┌──────────────────────┐                           │
│                 │      DATABASE        │                           │
│                 │   (PostgreSQL)       │                           │
│                 │                      │                           │
│                 │  ┌────────────────┐  │                           │
│                 │  │akademik_db    │  │                           │
│                 │  │keuangan_db    │  │                           │
│                 │  │kesiswaan_db   │  │                           │
│                 │  │tahfiz_db      │  │                           │
│                 │  │inklusi_db     │  │                           │
│                 │  │shared_db      │  │                           │
│                 │  └────────────────┘  │                           │
│                 └──────────────────────┘                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 2: DESIGN SYSTEM

### 2.1 Design Principles

```
┌─────────────────────────────────────────────────────────────────────┐
│                      DESIGN PRINCIPLES                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  1. MOBILE-FIRST                                                    │
│  ├── Design dimulai dari smallest screen (320px)                   │
│  ├── Progressive enhancement untuk screen yang lebih besar          │
│  └── Touch targets minimum 44x44px                                 │
│                                                                     │
│  2. CONSISTENCY                                                     │
│  ├── Satu source of truth untuk design tokens                      │
│  ├── Gunakan komponen yang sudah ada sebelum membuat baru          │
│  └── Pattern yang sama untuk fungsi yang sama                       │
│                                                                     │
│  3. CLARITY                                                         │
│  ├── Hierarki visual yang jelas                                    │
│  ├── White space yang cukup                                        │
│  └── Progressive disclosure untuk informasi complex                 │
│                                                                     │
│  4. ACCESSIBILITY                                                   │
│  ├── WCAG 2.1 AA compliant                                         │
│  ├── Color contrast minimum 4.5:1                                  │
│  └── Keyboard navigation support                                    │
│                                                                     │
│  5. PERFORMANCE                                                     │
│  ├── Optimize untuk 3G network                                     │
│  ├── Lazy loading untuk konten below-the-fold                       │
│  └── Minimal re-render                                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.2 Color Palette

```
┌─────────────────────────────────────────────────────────────────────┐
│                         COLOR PALETTE                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  PRIMARY COLORS (Brand):                                            │
│  ├── Primary:        #1E40AF (Blue 800)      - Main actions        │
│  ├── Primary Dark:   #1E3A8A (Blue 900)      - Hover states        │
│  ├── Primary Light:  #3B82F6 (Blue 500)      - Links, highlights   │
│                                                                     │
│  SECONDARY COLORS:                                                  │
│  ├── Secondary:      #059669 (Emerald 600)   - Success, Tahfiz     │
│  ├── Secondary Dark: #047857 (Emerald 700)   - Hover                │
│  ├── Secondary Light:#10B981 (Emerald 500)   - Highlights          │
│                                                                     │
│  ACCENT COLORS:                                                     │
│  ├── Accent:         #8B5CF6 (Violet 500)    - Special features    │
│  ├── Warning:        #F59E0B (Amber 500)     - Warnings            │
│  ├── Error:          #DC2626 (Red 600)       - Errors, violations  │
│  └── Info:           #3B82F6 (Blue 500)      - Information         │
│                                                                     │
│  NEUTRAL COLORS:                                                    │
│  ├── Gray 50:        #F9FAFB (Background)                          │
│  ├── Gray 100:       #F3F4F6 (Card background)                     │
│  ├── Gray 200:       #E5E7EB (Borders)                             │
│  ├── Gray 300:       #D1D5DB (Disabled)                            │
│  ├── Gray 400:       #9CA3AF (Placeholder)                         │
│  ├── Gray 500:       #6B7280 (Secondary text)                      │
│  ├── Gray 600:       #4B5563 (Body text)                           │
│  ├── Gray 700:       #374151 (Primary text)                        │
│  ├── Gray 800:       #1F2937 (Headings)                            │
│  └── Gray 900:       #111827 (Dark text)                           │
│                                                                     │
│  SEMANTIC COLORS (untuk grafik/chart):                              │
│  ├── Chart 1:        #1E40AF (Primary Blue)                        │
│  ├── Chart 2:        #059669 (Green)                               │
│  ├── Chart 3:        #8B5CF6 (Violet)                              │
│  ├── Chart 4:        #F59E0B (Amber)                               │
│  ├── Chart 5:        #EC4899 (Pink)                                │
│  └── Chart 6:        #06B6D4 (Cyan)                                │
│                                                                     │
│  USAGE:                                                             │
│  ├── Primary actions:     Primary color                            │
│  ├── Secondary actions:   Secondary color                          │
│  ├── Success states:      Green / Emerald                           │
│  ├── Warning states:      Amber                                     │
│  ├── Error states:        Red                                       │
│  └── Neutral UI:          Gray scale                                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.3 Typography

```
┌─────────────────────────────────────────────────────────────────────┐
│                         TYPOGRAPHY SYSTEM                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  FONT FAMILY:                                                       │
│  ├── Primary:     "Inter", -apple-system, BlinkMacSystemFont       │
│  │               "Segoe UI", Roboto, sans-serif                    │
│  ├── Monospace:   "JetBrains Mono", "Fira Code", monospace         │
│  └── Arabic (untuk Quran): "Amiri Quran", "Scheherazade"           │
│                                                                     │
│  FONT WEIGHTS:                                                      │
│  ├── Regular (400):     Body text, descriptions                    │
│  ├── Medium (500):      Labels, button text                        │
│  ├── Semi-bold (600):   Subheadings, emphasis                      │
│  └── Bold (700):        Headings (H1-H3)                           │
│                                                                     │
│  TYPE SCALE:                                                        │
│  ├── Display:    48px / 3rem    - Dashboard hero stats             │
│  ├── H1:         32px / 2rem    - Page titles                      │
│  ├── H2:         24px / 1.5rem  - Section headings                 │
│  ├── H3:         20px / 1.25rem - Card titles                      │
│  ├── H4:         18px / 1.125rem- Subsection headings              │
│  ├── Body:       16px / 1rem    - Standard text                    │
│  ├── Small:      14px / 0.875rem- Secondary text, captions         │
│  └── Tiny:       12px / 0.75rem - Badges, timestamps               │
│                                                                     │
│  LINE HEIGHTS:                                                      │
│  ├── Tight:      1.25   - Headings                                 │
│  ├── Normal:     1.5    - Body text                                │
│  └── Relaxed:    1.75   - Long-form content                        │
│                                                                     │
│  LETTER SPACING:                                                    │
│  ├── Tight:      -0.025em - Headings                               │
│  ├── Normal:     0       - Body text                               │
│  └── Wide:       0.025em - All caps labels                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.4 Spacing System

```
┌─────────────────────────────────────────────────────────────────────┐
│                        SPACING SYSTEM                               │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  BASE UNIT: 4px                                                     │
│                                                                     │
│  SPACING SCALE:                                                     │
│  ├── space-0:    0px        - No spacing                           │
│  ├── space-1:    4px / 0.25rem - Tight spacing                     │
│  ├── space-2:    8px / 0.5rem  - Default small                     │
│  ├── space-3:    12px / 0.75rem - Medium small                     │
│  ├── space-4:    16px / 1rem   - Default                           │
│  ├── space-5:    20px / 1.25rem - Medium                           │
│  ├── space-6:    24px / 1.5rem  - Default large                    │
│  ├── space-8:    32px / 2rem   - Section spacing                   │
│  ├── space-10:   40px / 2.5rem - Large spacing                     │
│  ├── space-12:   48px / 3rem   - XL spacing                        │
│  ├── space-16:   64px / 4rem   - XXL spacing                       │
│  └── space-20:   80px / 5rem   - Page margins                      │
│                                                                     │
│  USAGE GUIDELINES:                                                  │
│  ├── Inline elements:     space-1 to space-2                       │
│  ├── Between related items: space-2 to space-4                     │
│  ├── Between sections:    space-6 to space-8                       │
│  ├── Page padding:        space-4 (mobile), space-6 (desktop)      │
│  └── Card padding:        space-4                                  │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.5 Border Radius & Shadows

```
┌─────────────────────────────────────────────────────────────────────┐
│                    BORDER RADIUS & SHADOWS                          │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  BORDER RADIUS:                                                     │
│  ├── rounded-none: 0px        - No radius (full width)             │
│  ├── rounded-sm:   4px / 0.25rem - Badges, small elements          │
│  ├── rounded:      6px / 0.375rem - Buttons, inputs (default)      │
│  ├── rounded-md:   8px / 0.5rem  - Cards                           │
│  ├── rounded-lg:   12px / 0.75rem - Modals, large cards            │
│  ├── rounded-xl:   16px / 1rem   - Feature cards                   │
│  └── rounded-full: 9999px     - Pills, avatars                     │
│                                                                     │
│  SHADOWS:                                                           │
│  ├── shadow-sm:    0 1px 2px 0 rgb(0 0 0 / 0.05)                  │
│  │                - Subtle elevation for cards                     │
│  ├── shadow:       0 1px 3px 0 rgb(0 0 0 / 0.1),                  │
│  │                  0 1px 2px -1px rgb(0 0 0 / 0.1)               │
│  │                - Default card elevation                         │
│  ├── shadow-md:    0 4px 6px -1px rgb(0 0 0 / 0.1),               │
│  │                  0 2px 4px -2px rgb(0 0 0 / 0.1)               │
│  │                - Elevated cards, dropdowns                      │
│  ├── shadow-lg:    0 10px 15px -3px rgb(0 0 0 / 0.1),             │
│  │                  0 4px 6px -4px rgb(0 0 0 / 0.1)               │
│  │                - Modals, popovers                              │
│  └── shadow-xl:    0 20px 25px -5px rgb(0 0 0 / 0.1),             │
│  │                  0 8px 10px -6px rgb(0 0 0 / 0.1)              │
│  │                - Large overlays                                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 2.6 Component Library

```
┌─────────────────────────────────────────────────────────────────────┐
│                    CORE COMPONENTS                                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  BUTTONS:                                                            │
│  ├── Primary Button   - Primary actions, filled blue               │
│  ├── Secondary Button - Secondary actions, outlined                │
│  ├── Ghost Button     - Tertiary actions, text only                │
│  ├── Danger Button    - Destructive actions, red                   │
│  ├── Icon Button      - Compact actions with icon                  │
│  └── Button Group     - Multiple actions in group                  │
│                                                                     │
│  STATES:                                                            │
│  ├── Default: Normal appearance                                     │
│  ├── Hover: Slight darken + cursor pointer                         │
│  ├── Active/Pressed: Scale down 98% + darken                       │
│  ├── Disabled: Opacity 50%, cursor not-allowed                     │
│  ├── Loading: Spinner + disabled + "Loading..." text               │
│  └── Success: Green tint + checkmark                               │
│                                                                     │
│  INPUTS:                                                            │
│  ├── Text Input     - Single line text                             │
│  ├── Textarea       - Multi-line text                              │
│  ├── Number Input   - Numeric with increment/decrement             │
│  ├── Select/Dropdown- Single selection from list                   │
│  ├── Multi-select   - Multiple selection                           │
│  ├── Checkbox       - Boolean selection                            │
│  ├── Radio Button   - Single selection from group                  │
│  ├── Toggle Switch  - On/off switch                                │
│  ├── Date Picker    - Date selection                               │
│  ├── Time Picker    - Time selection                               │
│  └── File Upload    - File selection with preview                  │
│                                                                     │
│  FEEDBACK:                                                          │
│  ├── Alert/Banner   - Information, warning, error messages         │
│  ├── Toast/Notify   - Temporary notifications                       │
│  ├── Progress Bar   - Visual progress indicator                    │
│  ├── Spinner/Loader - Loading state                                │
│  ├── Skeleton       - Placeholder while loading                    │
│  └── Tooltip        - Additional information on hover              │
│                                                                     │
│  NAVIGATION:                                                        │
│  ├── Sidebar        - Vertical navigation for admin                │
│  ├── Bottom Nav     - Bottom navigation for mobile                 │
│  ├── Breadcrumb     - Hierarchical navigation                       │
│  ├── Tabs           - Tabbed content switching                      │
│  ├── Pagination     - Page navigation for lists                    │
│  └── Dropdown Menu  - Action menu                                  │
│                                                                     │
│  DATA DISPLAY:                                                      │
│  ├── Table          - Tabular data with sorting/filtering          │
│  ├── Card           - Content container                            │
│  ├── List           - Vertical list of items                       │
│  ├── Badge/Tag      - Small label                                  │
│  ├── Avatar         - User photo                                   │
│  ├── Stat Card      - Key metric display                           │
│  └── Chart          - Data visualization                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 3: ENTITY RELATIONSHIP DIAGRAM (ERD)

### 3.1 Schema Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ERD SCHEMA OVERVIEW                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                         ┌──────────────┐                            │
│                         │   yayasans   │                            │
│                         └──────┬───────┘                            │
│                                │ 1:N                                │
│                                ▼                                    │
│                         ┌──────────────┐                            │
│                         │  madrasahs   │                            │
│                         └──────┬───────┘                            │
│                                │ 1:N                                │
│         ┌──────────────────────┼──────────────────────┐             │
│         │                      │                      │             │
│         ▼                      ▼                      ▼             │
│  ┌──────────────┐      ┌──────────────┐      ┌──────────────┐      │
│  │   users      │      │   students   │      │   teachers   │      │
│  │              │      │              │      │              │      │
│  │ - id         │      │ - id         │      │ - id         │      │
│  │ - madrasah_id│      │ - madrasah_id│      │ - madrasah_id│      │
│  │ - role       │      │ - nis        │      │ - nuptk      │      │
│  │ - email      │      │ - name       │      │ - name       │      │
│  │ - password   │      │ - nisn       │      │ - email      │      │
│  │ - is_active  │      │ - nik        │      │ - subject_id │      │
│  └──────────────┘      │ - class_id   │      └──────────────┘      │
│         │              │ - status     │             │              │
│         │              └──────────────┘             │              │
│         │                     │                    │              │
│         └─────────────────────┼────────────────────┘              │
│                               │                                    │
│                               ▼                                    │
│                      ┌──────────────┐                             │
│                      │   classes    │                             │
│                      │              │                             │
│                      │ - id         │                             │
│                      │ - madrasah_id│                             │
│                      │ - name       │                             │
│                      │ - level      │                             │
│                      │ - year_id    │                             │
│                      │ - homeroom   │──────────────────────────────│
│                      └──────────────┘                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Core Tables

```sql
-- =====================================================
-- CORE TABLES - Foundation Schema
-- =====================================================

-- 1. YAYASAN (Foundation/Organization)
CREATE TABLE yayasans (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(255),
    logo_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. MADRASAH (School)
CREATE TABLE madrasahs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    yaysan_id UUID REFERENCES yayasans(id) ON DELETE SET NULL,
    npsn VARCHAR(20) UNIQUE,
    nsm VARCHAR(20) UNIQUE,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    logo_url VARCHAR(500),
    headmaster_name VARCHAR(255),
    headmaster_signature_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    settings JSONB DEFAULT '{}', -- School-specific settings
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. ACADEMIC YEARS
CREATE TABLE academic_years (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    year VARCHAR(9) NOT NULL, -- '2025/2026'
    start_date DATE,
    end_date DATE,
    is_current BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(madrasah_id, year)
);

-- 4. USERS (All user types)
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    role VARCHAR(50) NOT NULL, -- kepala_madrasah, waka, guru, tu, ortu, siswa
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    photo_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. USER ROLE ASSIGNMENTS (For users with multiple roles)
CREATE TABLE user_roles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    role VARCHAR(50) NOT NULL,
    scope_type VARCHAR(20) DEFAULT 'ALL', -- ALL, OWN, DEPT, CLASS
    scope_id UUID, -- class_id, department_id, etc.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, role, scope_id)
);

-- 6. SUBJECTS (Mata Pelajaran)
CREATE TABLE subjects (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    code VARCHAR(20),
    name VARCHAR(255) NOT NULL,
    short_name VARCHAR(50),
    group_name VARCHAR(100), -- Kelompok A, B, C
    is_religious BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. CLASSES (Rombel)
CREATE TABLE classes (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    name VARCHAR(50) NOT NULL, -- '7A', '7B', '8A'
    level VARCHAR(10) NOT NULL, -- '7', '8', '9'
    homeroom_teacher_id UUID REFERENCES users(id),
    capacity INTEGER DEFAULT 40,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(madrasah_id, academic_year_id, name)
);

-- 8. STUDENTS
CREATE TABLE students (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    nis VARCHAR(20) UNIQUE,
    nisn VARCHAR(20),
    nsm VARCHAR(20),
    nik VARCHAR(16) UNIQUE,
    name VARCHAR(255) NOT NULL,
    gender VARCHAR(10),
    place_of_birth VARCHAR(100),
    date_of_birth DATE,
    religion VARCHAR(50) DEFAULT 'Islam',
    address TEXT,
    village VARCHAR(100),
    district VARCHAR(100),
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code VARCHAR(10),
    photo_url VARCHAR(500),
    
    -- Family Info
    father_name VARCHAR(255),
    father_nik VARCHAR(16),
    father_phone VARCHAR(20),
    father_occupation VARCHAR(100),
    mother_name VARCHAR(255),
    mother_nik VARCHAR(16),
    mother_phone VARCHAR(20),
    mother_occupation VARCHAR(100),
    guardian_name VARCHAR(255),
    guardian_phone VARCHAR(20),
    
    -- Status
    status VARCHAR(20) DEFAULT 'active', -- active, inactive, graduated, transferred
    entry_date DATE,
    graduation_date DATE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 9. STUDENT CLASS ASSIGNMENTS (History)
CREATE TABLE student_class_assignments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    class_id UUID REFERENCES classes(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, class_id, academic_year_id)
);

-- 10. SCHEDULES
CREATE TABLE schedules (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    class_id UUID REFERENCES classes(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    teacher_id UUID REFERENCES users(id),
    day_of_week INTEGER NOT NULL, -- 0=Sunday, 1=Monday, etc.
    time_slot VARCHAR(10) NOT NULL, -- '07:00-07:40'
    room VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(class_id, day_of_week, time_slot)
);
```

### 3.3 Academic Tables

```sql
-- =====================================================
-- ACADEMIC TABLES
-- =====================================================

-- 11. ATTENDANCE (Daily)
CREATE TABLE attendances (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    class_id UUID REFERENCES classes(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    status VARCHAR(10) NOT NULL, -- 'H', 'I', 'S', 'A'
    arrival_time TIME,
    notes TEXT,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, date)
);

-- 12. ASSESSMENTS (Nilai)
CREATE TABLE assessments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    semester VARCHAR(10) NOT NULL, -- 'ganjil', 'genap'
    type VARCHAR(20) NOT NULL, -- 'formatif', 'summative', 'project'
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    class_id UUID REFERENCES classes(id) ON DELETE CASCADE,
    title VARCHAR(255),
    description TEXT,
    date DATE,
    max_score DECIMAL(5,2) DEFAULT 100,
    weight DECIMAL(5,2) DEFAULT 1,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 13. STUDENT SCORES
CREATE TABLE student_scores (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    assessment_id UUID REFERENCES assessments(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    score DECIMAL(5,2),
    description TEXT, -- Auto-generated or manual description
    is_remedial BOOLEAN DEFAULT FALSE,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(assessment_id, student_id)
);

-- 14. SEMESTER REPORTS (Rapor)
CREATE TABLE semester_reports (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    class_id UUID REFERENCES classes(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    semester VARCHAR(10) NOT NULL,
    status VARCHAR(20) DEFAULT 'draft', -- draft, validated, signed
    print_count INTEGER DEFAULT 0,
    printed_at TIMESTAMP,
    signed_by UUID REFERENCES users(id),
    signed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, academic_year_id, semester)
);

-- 15. SEMESTER REPORT DETAILS
CREATE TABLE semester_report_details (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    semester_report_id UUID REFERENCES semester_reports(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    knowledge_score DECIMAL(5,2),
    knowledge_desc TEXT,
    skill_score DECIMAL(5,2),
    skill_desc TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(semester_report_id, subject_id)
);
```

### 3.4 Financial Tables

```sql
-- =====================================================
-- FINANCIAL TABLES
-- =====================================================

-- 16. BILLING COMPONENTS
CREATE TABLE billing_components (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(20) NOT NULL, -- 'monthly', 'semester', 'yearly', 'event'
    amount DECIMAL(12,2) NOT NULL,
    due_day INTEGER DEFAULT 15,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 17. STUDENT BILLS
CREATE TABLE student_bills (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    billing_component_id UUID REFERENCES billing_components(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    semester VARCHAR(10),
    amount DECIMAL(12,2) NOT NULL,
    discount DECIMAL(12,2) DEFAULT 0,
    final_amount DECIMAL(12,2) NOT NULL,
    due_date DATE,
    status VARCHAR(20) DEFAULT 'unpaid', -- unpaid, partial, paid
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 18. PAYMENTS
CREATE TABLE payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_bill_id UUID REFERENCES student_bills(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    amount DECIMAL(12,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(20), -- 'cash', 'transfer', 'va'
    bank_account VARCHAR(50),
    reference_number VARCHAR(100),
    proof_url VARCHAR(500),
    validated_by UUID REFERENCES users(id),
    validated_at TIMESTAMP,
    status VARCHAR(20) DEFAULT 'pending', -- pending, validated, rejected
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 19. TRANSACTION JOURNAL
CREATE TABLE transaction_journals (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    type VARCHAR(20) NOT NULL, -- 'income', 'expense', 'transfer'
    category VARCHAR(100),
    description TEXT,
    amount DECIMAL(12,2) NOT NULL,
    reference_type VARCHAR(50), -- 'payment', 'expense', etc.
    reference_id UUID,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3.5 Tahfiz Tables (UNIQUE)

```sql
-- =====================================================
-- TAHFIZ TABLES (UNIQUE ISLAMIC FEATURE)
-- =====================================================

-- 20. TAHFIZ PROGRAMS
CREATE TABLE tahfiz_programs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL, -- 'Tahfidz Quran', 'Tilawati', etc.
    type VARCHAR(50) NOT NULL, -- 'quran', 'tilawati', 'ibadah'
    target_juz INTEGER,
    target_pages INTEGER,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 21. STUDENT TAHFIZ ENROLLMENT
CREATE TABLE student_tahfiz_enrollments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    tahfiz_program_id UUID REFERENCES tahfiz_programs(id) ON DELETE CASCADE,
    start_date DATE,
    target_completion_date DATE,
    status VARCHAR(20) DEFAULT 'active', -- active, completed, discontinued
    current_juz INTEGER DEFAULT 0,
    current_page INTEGER DEFAULT 0,
    current_surah VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, tahfiz_program_id)
);

-- 22. HAFALAN RECORDINGS (Setoran Hafalan)
CREATE TABLE hafalan_recordings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_enrollment_id UUID REFERENCES student_tahfiz_enrollments(id) ON DELETE CASCADE,
    recording_date DATE NOT NULL,
    surah VARCHAR(100),
    start_verse INTEGER,
    end_verse INTEGER,
    start_page INTEGER,
    end_page INTEGER,
    status VARCHAR(20) NOT NULL, -- 'belum', 'sedang', 'lancar', 'qiroah_bagus'
    tajwid_score INTEGER, -- 1-100
    makhorijul_score INTEGER, -- 1-100
    notes TEXT,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 23. MURAJAAH RECORDS
CREATE TABLE murajaah_records (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_enrollment_id UUID REFERENCES student_tahfiz_enrollments(id) ON DELETE CASCADE,
    record_date DATE NOT NULL,
    surah VARCHAR(100),
    start_verse INTEGER,
    end_verse INTEGER,
    is_completed BOOLEAN DEFAULT FALSE,
    notes TEXT,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 24. MUNAQOSAH SCHEDULE
CREATE TABLE munaqosah_schedules (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    scheduled_date DATE NOT NULL,
    registration_deadline DATE,
    location VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 25. MUNAQOSAH REGISTRATIONS
CREATE TABLE munaqosah_registrations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    munaqosah_schedule_id UUID REFERENCES munaqosah_schedules(id) ON DELETE CASCADE,
    student_enrollment_id UUID REFERENCES student_tahfiz_enrollments(id) ON DELETE CASCADE,
    surah VARCHAR(100),
    start_verse INTEGER,
    end_verse INTEGER,
    status VARCHAR(20) DEFAULT 'registered', -- registered, in_progress, passed, failed
    score INTEGER,
    passed BOOLEAN,
    certificates_url VARCHAR(500),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 26. TILAWATI TRACKING
CREATE TABLE tilawati_tracking (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    level VARCHAR(20) NOT NULL, -- 'Dasar 1', 'Dasar 2', etc.
    start_date DATE,
    completion_date DATE,
    score INTEGER,
    notes TEXT,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3.6 Inklusi/PDBK Tables (UNIQUE)

```sql
-- =====================================================
-- INKLUSI/PDBK TABLES (UNIQUE FEATURE)
-- =====================================================

-- 27. STUDENT INKLUSI (ABK)
CREATE TABLE student_inklusi (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    needs_type VARCHAR(100) NOT NULL, -- 'tunanetra', 'tunarungu', 'tunagrahita', etc.
    severity_level VARCHAR(20), -- 'ringan', 'sedang', 'berat'
    identification_date DATE,
    identification_notes TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id)
);

-- 28. PPI (Program Pembelajaran Individual)
CREATE TABLE ppis (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_inklusi_id UUID REFERENCES student_inklusi(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    title VARCHAR(255),
    target_description TEXT,
    strategies TEXT, -- JSON array of strategies
    accommodations TEXT, -- JSON array of accommodations
    start_date DATE,
    target_date DATE,
    status VARCHAR(20) DEFAULT 'draft', -- draft, active, completed, revised
    created_by UUID REFERENCES users(id),
    approved_by UUID REFERENCES users(id),
    approved_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 29. PPI TARGETS
CREATE TABLE ppi_targets (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    ppi_id UUID REFERENCES ppis(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE SET NULL,
    target_type VARCHAR(50), -- 'academic', 'behavior', 'social'
    description TEXT,
    target_value VARCHAR(255),
    deadline DATE,
    status VARCHAR(20) DEFAULT 'pending', -- pending, in_progress, achieved, not_achieved
    achievement_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 30. GPK SESSIONS
CREATE TABLE gpk_sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_inklusi_id UUID REFERENCES student_inklusi(id) ON DELETE CASCADE,
    gpk_teacher_id UUID REFERENCES users(id),
    session_date DATE NOT NULL,
    duration_minutes INTEGER,
    strategy_used TEXT,
    topics_covered TEXT,
    obstacles TEXT,
    solutions TEXT,
    next_session_plan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 31. ASSESSMENT RESULTS
CREATE TABLE assessment_results (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    assessment_type VARCHAR(50) NOT NULL, -- 'iq', 'bakat_minat', 'kepribadian'
    assessment_date DATE NOT NULL,
    instrument VARCHAR(100), -- 'WISC', 'TOKI', etc.
    raw_score DECIMAL(5,2),
    norm_score DECIMAL(5,2),
    result_summary TEXT,
    document_url VARCHAR(500),
    assessor_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3.7 Student Affairs Tables

```sql
-- =====================================================
-- STUDENT AFFAIRS TABLES
-- =====================================================

-- 32. EXTRACURRICULAR
CREATE TABLE extracurriculars (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    day VARCHAR(50),
    time VARCHAR(50),
    room VARCHAR(50),
    instructor_id UUID REFERENCES users(id),
    max_participants INTEGER,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 33. EXTRACURRICULAR ENROLLMENTS
CREATE TABLE ekskul_enrollments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    extracurricular_id UUID REFERENCES extracurriculars(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    enrollment_date DATE,
    status VARCHAR(20) DEFAULT 'active', -- active, completed, dropped
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(extracurricular_id, student_id)
);

-- 34. VIOLATIONS
CREATE TABLE violations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    category VARCHAR(50) NOT NULL, -- 'ringan', 'sedang', 'berat'
    name VARCHAR(255) NOT NULL,
    point_value INTEGER NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 35. STUDENT VIOLATIONS
CREATE TABLE student_violations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    violation_id UUID REFERENCES violations(id) ON DELETE CASCADE,
    violation_date DATE NOT NULL,
    description TEXT,
    consequence TEXT,
    recorded_by UUID REFERENCES users(id),
    validated_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 36. ACHIEVEMENTS
CREATE TABLE achievements (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100), -- 'akademik', 'non_akademik'
    level VARCHAR(50), -- 'madrasah', 'kabupaten', 'provinsi', 'nasional'
    event_name VARCHAR(255),
    event_date DATE,
    rank VARCHAR(50),
    certificate_url VARCHAR(500),
    notes TEXT,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 37. PERMISSIONS (Izin)
CREATE TABLE permissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    type VARCHAR(20) NOT NULL, -- 'sakit', 'izin', 'pulang_cepat'
    start_date DATE NOT NULL,
    end_date DATE,
    reason TEXT,
    attachment_url VARCHAR(500),
    status VARCHAR(20) DEFAULT 'pending', -- pending, approved, rejected
    requested_by UUID REFERENCES users(id),
    approved_by UUID REFERENCES users(id),
    approved_at TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3.8 System Tables

```sql
-- =====================================================
-- SYSTEM TABLES
-- =====================================================

-- 38. AUDIT LOGS
CREATE TABLE audit_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id),
    action VARCHAR(50) NOT NULL,
    resource_type VARCHAR(100),
    resource_id UUID,
    changes JSONB,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 39. NOTIFICATIONS
CREATE TABLE notifications (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    message TEXT,
    type VARCHAR(50), -- 'info', 'warning', 'success', 'error'
    reference_type VARCHAR(50),
    reference_id UUID,
    is_read BOOLEAN DEFAULT FALSE,
    sent_via VARCHAR(50) DEFAULT 'app', -- 'app', 'whatsapp', 'email', 'sms'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 40. DOCUMENTS (E-Office)
CREATE TABLE documents (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    type VARCHAR(50) NOT NULL, -- 'surat_masuk', 'surat_keluar', 'disposisi'
    number VARCHAR(50),
    subject VARCHAR(255),
    content TEXT,
    sender VARCHAR(255),
    recipient VARCHAR(255),
    date DATE,
    attachment_url VARCHAR(500),
    status VARCHAR(20) DEFAULT 'draft',
    created_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 41. DOCUMENT DISPOSITIONS
CREATE TABLE document_dispositions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    document_id UUID REFERENCES documents(id) ON DELETE CASCADE,
    assignee_id UUID REFERENCES users(id),
    instruction TEXT,
    deadline DATE,
    status VARCHAR(20) DEFAULT 'pending', -- pending, in_progress, completed
    completed_at TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 42. LIBRARY BOOKS
CREATE TABLE library_books (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    madrasah_id UUID REFERENCES madrasahs(id) ON DELETE CASCADE,
    isbn VARCHAR(20),
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    publisher VARCHAR(255),
    year_published INTEGER,
    category VARCHAR(100),
    rack_location VARCHAR(50),
    total_copies INTEGER DEFAULT 1,
    available_copies INTEGER DEFAULT 1,
    cover_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 43. LIBRARY BORROWINGS
CREATE TABLE library_borrowings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    book_id UUID REFERENCES library_books(id) ON DELETE CASCADE,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    status VARCHAR(20) DEFAULT 'borrowed', -- borrowed, returned, overdue
    fine_amount DECIMAL(10,2) DEFAULT 0,
    notes TEXT,
    recorded_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3.9 Indexes

```sql
-- =====================================================
-- INDEXES FOR PERFORMANCE
-- =====================================================

-- Core lookups
CREATE INDEX idx_users_madrasah ON users(madrasah_id);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_is_active ON users(is_active);

CREATE INDEX idx_students_madrasah ON students(madrasah_id);
CREATE INDEX idx_students_nis ON students(nis);
CREATE INDEX idx_students_nisn ON students(nisn);
CREATE INDEX idx_students_nik ON students(nik);
CREATE INDEX idx_students_status ON students(status);

CREATE INDEX idx_classes_madrasah_year ON classes(madrasah_id, academic_year_id);
CREATE INDEX idx_classes_homeroom ON classes(homeroom_teacher_id);

CREATE INDEX idx_schedules_class ON schedules(class_id);
CREATE INDEX idx_schedules_teacher ON schedules(teacher_id);
CREATE INDEX idx_schedules_day ON schedules(day_of_week);

-- Academic lookups
CREATE INDEX idx_attendances_student_date ON attendances(student_id, date);
CREATE INDEX idx_attendances_class_date ON attendances(class_id, date);

CREATE INDEX idx_assessments_class_subject ON assessments(class_id, subject_id);
CREATE INDEX idx_assessments_academic_year ON assessments(academic_year_id);

CREATE INDEX idx_student_scores_assessment ON student_scores(assessment_id);
CREATE INDEX idx_student_scores_student ON student_scores(student_id);

CREATE INDEX idx_semester_reports_student_year ON semester_reports(student_id, academic_year_id);

-- Financial lookups
CREATE INDEX idx_student_bills_student ON student_bills(student_id);
CREATE INDEX idx_student_bills_status ON student_bills(status);
CREATE INDEX idx_student_bills_due_date ON student_bills(due_date);

CREATE INDEX idx_payments_student_bill ON payments(student_bill_id);
CREATE INDEX idx_payments_date ON payments(payment_date);

-- Tahfiz lookups
CREATE INDEX idx_hafalan_recordings_student ON hafalan_recordings(student_enrollment_id);
CREATE INDEX idx_hafalan_recordings_date ON hafalan_recordings(recording_date);

CREATE INDEX idx_munaqosah_registrations_schedule ON munaqosah_registrations(munaqosah_schedule_id);

-- Inklusi lookups
CREATE INDEX idx_ppis_student ON ppis(student_inklusi_id);
CREATE INDEX idx_ppi_targets_ppi ON ppi_targets(ppi_id);

CREATE INDEX idx_gpk_sessions_student ON gpk_sessions(student_inklusi_id);
CREATE INDEX idx_gpk_sessions_date ON gpk_sessions(session_date);

-- System lookups
CREATE INDEX idx_audit_logs_user ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_date ON audit_logs(created_at);
CREATE INDEX idx_audit_logs_resource ON audit_logs(resource_type, resource_id);

CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);
```

### 3.10 Triggers & Functions

```sql
-- =====================================================
-- TRIGGERS & FUNCTIONS
-- =====================================================

-- Auto-update updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Apply to all tables with updated_at
CREATE TRIGGER update_yayasans_updated_at BEFORE UPDATE ON yayasans
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_madrasahs_updated_at BEFORE UPDATE ON madrasahs
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_students_updated_at BEFORE UPDATE ON students
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Calculate final_amount on student_bills
CREATE TRIGGER calculate_final_amount BEFORE INSERT OR UPDATE ON student_bills
    FOR EACH ROW EXECUTE FUNCTION 
BEGIN
    NEW.final_amount = NEW.amount - COALESCE(NEW.discount, 0);
    RETURN NEW;
END;

-- Update available_copies on library_borrowings
CREATE OR REPLACE FUNCTION update_book_copies()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' AND NEW.return_date IS NULL THEN
        UPDATE library_books SET available_copies = available_copies - 1
        WHERE id = NEW.book_id;
    ELSIF TG_OP = 'UPDATE' AND NEW.return_date IS NOT NULL AND OLD.return_date IS NULL THEN
        UPDATE library_books SET available_copies = available_copies + 1
        WHERE id = NEW.book_id;
    END IF;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER library_borrowing_copies AFTER INSERT OR UPDATE ON library_borrowings
    FOR EACH ROW EXECUTE FUNCTION update_book_copies();
```

---

## BAGIAN 4: ENTITY RELATIONSHIP DIAGRAM (VISUAL)

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           ERD VISUAL (Simplified)                                │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌───────────────┐                                                       │
│  │   yayasans    │                                                       │
│  ├───────────────┤                                                       │
│  │ PK id         │◄────────────────┐                                    │
│  │   name        │                 │ 1:N                                 │
│  │   address     │                 │                                    │
│  │   logo_url    │                 │                                    │
│  └───────┬───────┘                 │                                    │
│          │                         │                                    │
│          │ 1:N                     │                                    │
│          ▼                         ▼                                    │
│  ┌───────────────┐          ┌───────────────┐                         │
│  │   madrasahs   │          │     users     │                         │
│  ├───────────────┤          ├───────────────┤                         │
│  │ PK id         │          │ PK id         │                         │
│  │ FK yaysan_id  │          │ FK madrasah_id│                         │
│  │   npsn        │          │   role        │                         │
│  │   nsm         │          │   email       │──────────────────────────►
│  │   name        │          │   password    │                         │
│  │   address     │          │   full_name   │                         │
│  └───────┬───────┘          └───────┬───────┘                         │
│          │                          │                                 │
│          │ 1:N                      │ 1:N                              │
│          ▼                          ▼                                 │
│  ┌───────────────┐          ┌───────────────┐                         │
│  │academic_years │          │   teachers    │                         │
│  ├───────────────┤          ├───────────────┤                         │
│  │ PK id         │          │ PK id         │                         │
│  │ FK madrasah_id│          │ FK madrasah_id│                         │
│  │   year        │          │   nuptk       │                         │
│  │   is_current  │          │   name        │                         │
│  └───────┬───────┘          └───────────────┘                         │
│          │                                                             │
│          │ 1:N                                                        │
│          ▼                                                            │
│  ┌─────────────────────────────────────────────────────────────────┐  │
│  │                         classes                                  │  │
│  ├─────────────────────────────────────────────────────────────────┤  │
│  │ PK id         │ FK madrasah_id │ FK academic_year_id │ level    │  │
│  │ FK homeroom_teacher_id                                  │ capacity│  │
│  └─────────────────────────────────────────────────────────────────┘  │
│          │                               │                            │
│          │ 1:N                           │ 1:N                        │
│          ▼                               ▼                            │
│  ┌───────────────┐               ┌───────────────┐                   │
│  │   students    │               │   schedules   │                   │
│  ├───────────────┤               ├───────────────┤                   │
│  │ PK id         │               │ PK id         │                   │
│  │ FK madrasah_id│               │ FK class_id   │                   │
│  │   nis         │               │ FK subject_id │                   │
│  │   nisn        │               │ FK teacher_id │                   │
│  │   nik         │               │   day_of_week │                   │
│  │   name        │               │   time_slot   │                   │
│  │   gender      │               └───────────────┘                   │
│  │   dob         │                                                     │
│  │   status      │                                                     │
│  └───────┬───────┘                                                     │
│          │ 1:N                                                        │
│          ▼                                                            │
│  ┌─────────────────────────────────────────────────────────────────┐  │
│  │              student_class_assignments                           │  │
│  ├─────────────────────────────────────────────────────────────────┤  │
│  │ PK id         │ FK student_id │ FK class_id │ FK academic_year_id│  │
│  └─────────────────────────────────────────────────────────────────┘  │
│          │                                                             │
│          │ 1:N                                                        │
│          ▼                                                            │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐         │
│  │  attendances  │    │student_scores │    │student_violations│     │
│  ├───────────────┤    ├───────────────┤    ├───────────────┤         │
│  │ PK id         │    │ PK id         │    │ PK id         │         │
│  │ FK student_id │    │FK assessment_id│    │ FK student_id │         │
│  │ FK class_id   │    │ FK student_id │    │ FK violation_id│         │
│  │   date        │    │   score       │    │   date        │         │
│  │   status      │    │   description │    │   point_value │         │
│  └───────────────┘    └───────────────┘    └───────────────┘         │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────────┐  │
│  │                    TAHFIZ (UNIQUE)                               │  │
│  ├─────────────────────────────────────────────────────────────────┤  │
│  │                                                                 │  │
│  │   tahfiz_programs ──────► student_tahfiz_enrollments            │  │
│  │          │                          │                           │  │
│  │          │                          │ 1:N                       │  │
│  │          │                          ▼                           │  │
│  │          │                  ┌───────────────┐                   │  │
│  │          │                  │hafalan_recordings│                │  │
│  │          │                  ├───────────────┤                   │  │
│  │          │                  │   surah       │                   │  │
│  │          │                  │   verse_range │                   │  │
│  │          │                  │   status      │                   │  │
│  │          │                  └───────────────┘                   │  │
│  │          │                          │                           │  │
│  │          │                          │ 1:N                       │  │
│  │          │                          ▼                           │  │
│  │          │                  ┌───────────────┐                   │  │
│  │          │                  │munaqosah_registrations│           │  │
│  │          │                  ├───────────────┤                   │  │
│  │          │                  │   score       │                   │  │
│  │          │                  │   passed      │                   │  │
│  │          │                  │   certificate │                   │  │
│  │          │                  └───────────────┘                   │  │
│  │          │                                                         │  │
│  └──────────┴─────────────────────────────────────────────────────────┘  │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────────┐  │
│  │                    INKLUSI/PDBK (UNIQUE)                        │  │
│  ├─────────────────────────────────────────────────────────────────┤  │
│  │                                                                 │  │
│  │   student_inklusi ──────────► ppis                              │  │
│  │        │                        │                              │  │
│  │        │                        │ 1:N                          │  │
│  │        │                        ▼                              │  │
│  │        │                  ┌───────────────┐                    │  │
│  │        │                  │  ppi_targets  │                    │  │
│  │        │                  ├───────────────┤                    │  │
│  │        │                  │   target_type │                    │  │
│  │        │                  │   status      │                    │  │
│  │        │                  └───────────────┘                    │  │
│  │        │                                                         │  │
│  │        ▼                                                         │  │
│  │   ┌───────────────┐                                            │  │
│  │   │  gpk_sessions │                                            │  │
│  │   ├───────────────┤                                            │  │
│  │   │   gpk_id      │                                            │  │
│  │   │   strategy    │                                            │  │
│  │   │   obstacles   │                                            │  │
│  │   └───────────────┘                                            │  │
│  │                                                                 │  │
│  └─────────────────────────────────────────────────────────────────┘  │
│                                                                     │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 5: RELATIONSHIP SUMMARY

```
┌─────────────────────────────────────────────────────────────────────┐
│                    RELATIONSHIP SUMMARY                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ONE-TO-MANY (1:N):                                                 │
│  ├── yayasans → madrasahs                                          │
│  ├── madrasahs → academic_years                                    │
│  ├── madrasahs → users                                             │
│  ├── madrasahs → students                                          │
│  ├── madrasahs → teachers                                          │
│  ├── madrasahs → classes                                           │
│  ├── madrasahs → subjects                                          │
│  ├── madrasahs → extracurriculars                                  │
│  ├── classes → schedules                                           │
│  ├── classes → students (via student_class_assignments)            │
│  ├── students → attendances                                        │
│  ├── students → student_bills                                      │
│  ├── students → violations                                         │
│  ├── students → achievements                                       │
│  ├── assessments → student_scores                                  │
│  └── tahfiz_programs → enrollments                                 │
│                                                                     │
│  ONE-TO-ONE (1:1):                                                  │
│  ├── students → student_inklusi (conditional)                      │
│  ├── semester_reports → semester_report_details                    │
│  └── documents → document_dispositions                             │
│                                                                     │
│  MANY-TO-MANY (N:M):                                                │
│  ├── students ↔ extracurriculars (via ekskul_enrollments)          │
│  ├── students ↔ tahfiz_programs (via student_tahfiz_enrollments)   │
│  └── users ↔ roles (via user_roles)                                │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## BAGIAN 6: DATA FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────────────┐
│                    DATA FLOW DIAGRAM                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│                         USERS                                       │
│    ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐          │
│    │ KAMAD  │ │  GURU  │ │   TU   │ │ ORANG  │ │ SISWA  │          │
│    │        │ │        │ │        │ │  TUA   │ │        │          │
│    └───┬────┘ └───┬────┘ └───┬────┘ └───┬────┘ └───┬────┘          │
│        │          │          │          │          │                │
│        └──────────┴──────────┴──────────┴──────────┘                │
│                           │                                          │
│                           ▼                                          │
│                  ┌──────────────────┐                               │
│                  │   API GATEWAY    │                               │
│                  │   (Auth, RBAC)   │                               │
│                  └────────┬─────────┘                               │
│                           │                                          │
│           ┌───────────────┼───────────────┐                        │
│           │               │               │                        │
│           ▼               ▼               ▼                        │
│    ┌────────────┐  ┌────────────┐  ┌────────────┐                 │
│    │  Akademik │  │  Keuangan  │  │ Kesiswaan  │                 │
│    │  Service   │  │  Service   │  │  Service   │                 │
│    └─────┬──────┘  └─────┬──────┘  └─────┬──────┘                 │
│          │               │               │                         │
│          └───────────────┼───────────────┘                         │
│                          │                                          │
│                          ▼                                          │
│                   ┌────────────┐                                   │
│                   │  DATABASE  │                                   │
│                   │ PostgreSQL │                                   │
│                   └─────┬──────┘                                   │
│                         │                                          │
│                         │                                          │
│          ┌──────────────┼──────────────┐                          │
│          │              │              │                          │
│          ▼              ▼              ▼                          │
│    ┌────────────┐ ┌────────────┐ ┌────────────┐                   │
│    │   Cache    │ │   Object   │ │  Message   │                   │
│    │   (Redis)  │ │   (S3)     │ │   Queue    │                   │
│    └────────────┘ └────────────┘ └────────────┘                   │
│                                                                     │
│  EXTERNAL INTEGRATIONS:                                             │
│  ─────────────────────                                              │
│        │              │              │              │              │
│        ▼              ▼              ▼              ▼              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐           │
│  │   EMIS   │  │  DAPODIK │  │   RDM    │  │  WhatsApp│           │
│  │ (Kemenag)│  │(Kemdikbud)│  │ (Kemenag)│  │  (Green) │           │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*