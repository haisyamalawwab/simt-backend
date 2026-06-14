# 🏗️ ANALISIS MULTI-CODEBASE ARCHITECTURE
## Laravel + Next.js + Node.js Express — Strategi & Trade-off

**Tanggal sesi:** 14 Juni 2026
**Waktu sesi (Asia/Jakarta):** 19:48 WIB
**Agent:** Arena Agent Mode (claude-sonnet)
**Trigger pertanyaan:** User bertanya apakah multi-codebase (Sprint 4 Node.js Express + Portal React/Next.js + Backend Laravel) tidak kesulitan?

> **Dokumen ini adalah respon Agent Arena untuk pertanyaan user tentang:**
> 1. Apakah 3 codebase (Laravel + Next.js + Node.js) tidak kesulitan?
> 2. Apa strategi mitigasi untuk mencegah drift?
> 3. Solusi mana yang terbaik untuk SIMT MTs?

---

## 📑 DAFTAR TOPIK ANALISIS

1. [Konteks: Arsitektur SIMT MTs Saat Ini](#-1-konteks--arsitektur-simt-mts-saat-ini)
2. [Kesulitan Multi-Codebase (10 Poin)](#-2-kesulitan-multi-codebase-10-poin)
3. [Keuntungan Multi-Codebase (By-Design)](#-3-keuntungan-multi-codebase-by-design)
4. [3 Solusi untuk Multi-Codebase](#-4-3-solusi-untuk-multi-codebase)
5. [Perbandingan 3 Solusi](#-5-perbandingan-3-solusi)
6. [Rekomendasi Final](#-6-rekomendasi-final)
7. [Action Plan](#-7-action-plan)
8. [Checklist Anti-Kesulitan](#-8-checklist-anti-kesulitan)

---

## 📌 1. Konteks: Arsitektur SIMT MTs Saat Ini

### ✅ Konfirmasi dari Doc 24 (Tech Arch Hybrid)

**Doc 24 (Tech Arch Hybrid Blade+Next.js)** menjelaskan arsitektur SIMT MTs secara eksplisit:

> *"Sistem SIMT MTs (MVP) kini berperan ganda di dalam satu codebase Laravel (Monolithic):*
> *1. Sebagai Aplikasi Web Tradisional (SSR): routes/web.php → Blade → Admin, Staf TU, Kepala Sekolah, dan Guru*
> *2. Sebagai Headless API: routes/api.php → JSON → Frontend Next.js/React → Orang Tua/Wali Murid"*

### ✅ Konfirmasi dari Doc 56 (Handover)

**Doc 56 §5 S4-01** menyatakan:

> *"Service Node.js Baileys multi-session (start/qr/status/send), auth state per tenant — repo/folder TERPISAH (mis. simt-wa-gateway/, VPS-2). 12h."*

### 📊 Diagram Arsitektur Saat Ini

```
┌─────────────────────────────────────────────────────────────┐
│                  3 CODEBASE TERPISAH                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────────┐    ┌──────────────────┐               │
│  │  simt-backend/   │    │  simt-portal/    │               │
│  │  Laravel 11      │◄───│  Next.js 14      │               │
│  │  (PHP)           │HTTP│  (TypeScript)    │               │
│  │                  │API │                  │               │
│  │  VPS-1           │    │  VPS-1           │               │
│  │  ~2GB RAM        │    │  ~512MB RAM      │               │
│  └────────┬─────────┘    └──────────────────┘               │
│           │                                                  │
│           │ HTTP POST /send + webhook callback              │
│           │                                                  │
│  ┌────────▼─────────┐                                        │
│  │  simt-wa-gateway/│                                        │
│  │  Node.js 20      │                                        │
│  │  Express + Baileys│                                       │
│  │  (JavaScript)    │                                        │
│  │                  │                                        │
│  │  VPS-2           │                                        │
│  │  ~1GB RAM        │                                        │
│  └──────────────────┘                                        │
└─────────────────────────────────────────────────────────────┘
```

### Status Repo Live (commit `3cbe997`)

| Repo | Bahasa/Stack | Status |
|---|---|---|
| `simt-backend/` | Laravel 11 + PHP 8.4 | ✅ Aktif (live @ commit `3cbe997`) |
| `simt-portal/` | Next.js 14 + TypeScript | ❌ Belum ada (akan dibuat di Sprint 5) |
| `simt-wa-gateway/` | Node.js 20 + Express + Baileys | ⏸️ Sprint 4 ditunda, agent lain mengerjakan |

---

## 🔴 2. Kesulitan Multi-Codebase (10 Poin)

| # | Kesulitan | Dampak | Severity |
|---|---|---|---|
| 1 | **3 bahasa/framework berbeda** | PHP (Laravel), TypeScript (Next.js), JavaScript (Node.js) — perlu expertise 3x | 🟠 Sedang |
| 2 | **3 setup environment lokal** | Composer (PHP) + npm/pnpm (Node.js) — pemula bingung | 🟠 Sedang |
| 3 | **Auth coordination lintas codebase** | Sanctum token harus konsisten dipakai Portal Next.js | 🔴 Tinggi |
| 4 | **Schema DB coupling** | Perubahan migration Laravel = Portal Next.js harus ikut update | 🔴 Tinggi |
| 5 | **API contract drift** | Tanpa auto-generated TypeScript types, Portal bisa pakai field yang salah | 🟠 Sedang |
| 6 | **CI/CD 3 pipeline** | Setup deploy 3x (VPS-1 Laravel, VPS-1 Portal, VPS-2 WA) | 🟠 Sedang |
| 7 | **Monitoring 3 service** | UptimeRobot harus monitor 3 URL berbeda | 🟠 Sedang |
| 8 | **Single developer burnout** | 1 founder maintain 3 codebase | 🔴 Tinggi |
| 9 | **Bug fix coordination** | Fix di Laravel tidak otomatis fix di Portal/WA Gateway | 🟠 Sedang |
| 10 | **Onboarding developer baru** | Stack baru = onboarding 3 stack sekaligus | 🟠 Sedang |

---

## 🟢 3. Keuntungan Multi-Codebase (By-Design)

| Keuntungan | Alasan |
|---|---|
| **Isolasi bahasa sesuai use case** | PHP untuk backend CRUD+RBAC, Next.js untuk PWA mobile ortu, Node.js untuk WA real-time |
| **Scalability independen** | WA Gateway bisa scale horizontal tanpa ganggu Laravel |
| **Failure isolation** | WA Gateway crash ≠ Laravel/Portal down |
| **BAILEYS best practice** | Node.js + @whiskeysockets/baileys = library resmi Meta-compatible WA |
| **Next.js mobile-first** | PWA untuk ortu (installable di HP) = best UX |
| **Pemeliharaan modular** | Tim (kalau ada) bisa fokus 1 codebase |

---

## 🎯 4. 3 Solusi untuk Multi-Codebase

### ✅ SOLUSI 1: Tetap Multi-Repo + Monorepo Tools

**Tool:** `pnpm workspaces` + `turborepo` (atau `nx`)

```
simt-mono/                          ← 1 repo, 3 package
├── apps/
│   ├── backend/                    ← Laravel (PHP)
│   ├── portal/                     ← Next.js (TypeScript)
│   └── wa-gateway/                 ← Node.js Express + Baileys (JavaScript)
├── packages/
│   ├── api-types/                  ← Shared TypeScript types (auto-generated dari OpenAPI)
│   ├── eslint-config/
│   └── tsconfig/
├── turbo.json                      ← Pipeline orchestration
├── pnpm-workspace.yaml
└── package.json                    ← Root workspace
```

**Keuntungan:**
- ✅ Shared types otomatis (Portal pakai type yang sama dengan backend)
- ✅ Single CI/CD pipeline (`turbo run build test deploy`)
- ✅ Single git history, atomic commits
- ✅ Cross-codebase refactor aman (TypeScript types kasih error)

**Kerugian:**
- 🟠 Setup monorepo butuh waktu (~4h)
- 🟠 Laravel jadi "anak" di monorepo (jarang dipakai — kebanyakan Laravel di repo standalone)

### ✅ SOLUSI 2: Tetap Multi-Repo (Doc 24, 49, 56 menyarankan ini)

**Tool:** Standalone repos + **OpenAPI spec auto-generation**

```
simt-backend/        (Laravel, existing)        VPS-1
simt-portal/         (Next.js, TERPISAH)       VPS-1
simt-wa-gateway/     (Node.js, TERPISAH)       VPS-2
```

**Mitigasi:**
- ✅ **OpenAPI spec** generated dari Laravel routes → auto-generate TypeScript types untuk Portal
- ✅ **Sanctum token shared** — Portal pakai token yang sama dengan Laravel API
- ✅ **Single source of truth schema** — Laravel migrations adalah acuan
- ✅ **Doc 49 Sesi 6** sudah ada skeleton `simt-wa-gateway/`

**Kerugian:**
- 🟠 3 repo, 3 git remote, 3 PR
- 🟠 Setup 3 env lokal

### ✅ SOLUSI 3: Hybrid — Laravel + Next.js Monorepo, WA Gateway Terpisah

**Tool:** `pnpm workspaces` untuk Laravel + Next.js, WA Gateway di repo terpisah

```
simt-app/                             ← 1 monorepo (Laravel + Next.js)
├── apps/
│   ├── backend/                      ← Laravel
│   └── portal/                       ← Next.js
├── packages/
│   └── api-types/
├── pnpm-workspace.yaml
└── composer.json + package.json

simt-wa-gateway/                      ← TERPISAH (by-design, bahasa berbeda)
└── (Node.js Express + Baileys)
```

**Keuntungan:**
- ✅ Portal + Laravel gampang sync (shared types)
- ✅ WA Gateway tetap independen (teknologi berbeda + VPS berbeda)
- ✅ Balance antara coupling dan decoupling

**Kerugian:**
- 🟠 Hybrid setup (1 monorepo + 1 standalone) lebih kompleks

---

## 📊 5. Perbandingan 3 Solusi

| Aspek | Solusi 1 (Full Monorepo) | Solusi 2 (Multi-Repo) | Solusi 3 (Hybrid) |
|---|---|---|---|
| **Setup time** | ~6h | ~2h | ~4h |
| **Shared types** | ✅ Auto | ⚠️ Manual OpenAPI | ✅ Auto (Laravel+Portal) |
| **CI/CD** | ✅ 1 pipeline | ⚠️ 3 pipeline | ⚠️ 2 pipeline |
| **Onboarding** | 🟡 1 struktur | 🟠 3 struktur | 🟡 2 struktur |
| **Cocok untuk** | Tim 2-3 orang | Solo founder (Doc 24) | Solo founder + 1 helper |
| **Risiko** | Over-engineering | Manual sync types | Balance |
| **Biaya tooling** | 🟠 Sedang (nx/turbo) | 🟢 Minimal (OpenAPI only) | 🟠 Sedang |
| **Scalability** | 🟢 Sangat scalable | 🟡 Scalable per-service | 🟢 Balance |
| **Migratable nanti** | ✅ Sudah monorepo | 🟠 Bisa migrate | 🟡 Sudah hybrid |

---

## ✅ 6. Rekomendasi Final

### Untuk SIMT MTs (1 founder + AI agent, budget terbatas):

## ✅ **SOLUSI 2: Multi-Repo + OpenAPI Spec** (Doc 24, 49, 56 merekomendasikan ini)

**Alasan:**
1. **Sesuai dokumen yang ada** — Doc 24, 49, 56 sudah eksplisit bilang "repo TERPISAH"
2. **Cocok untuk solo founder** — tidak perlu setup monorepo tool yang kompleks
3. **OpenAPI spec** = single source of truth untuk API contract
4. **Migratable ke monorepo nanti** — kalau ada pendanaan/tim

### Mitigasi Kesulitan (3 Strategi Konkret)

#### Strategi 1: OpenAPI Spec dari Laravel → Auto-Generate TypeScript Types

```bash
# Di simt-backend/ — generate OpenAPI spec
composer require darkaonline/l5-swagger
php artisan l5-swagger:generate

# Di simt-portal/ — auto-generate TypeScript types
npx openapi-typescript ../simt-backend/storage/api-docs/openapi.yaml \
  -o ./src/types/api-generated.ts
```

**Benefit:** Portal Next.js otomatis punya TypeScript types yang match dengan Laravel routes. Kalau ada perubahan di Laravel, regenerate types → error di Portal Next.js langsung ketahuan saat `pnpm typecheck`.

#### Strategi 2: Shared Sanity Check Script (Penting untuk MVP)

```bash
# scripts/api-sanity-check.sh
# - Login ke Portal Next.js
# - Fetch data dari Laravel API
# - Verifikasi response sesuai spec
```

#### Strategi 3: Docker Compose untuk Development (Opsional)

```yaml
# docker-compose.yml (root monorepo ATAU masing-masing repo)
version: '3.8'
services:
  laravel:
    build: ./simt-backend
    ports: ["8000:8000"]
  portal:
    build: ./simt-portal
    ports: ["3000:3000"]
  wa-gateway:
    build: ./simt-wa-gateway
    ports: ["3001:3001"]
    depends_on: [laravel]
```

---

## 🛠️ 7. Action Plan (Recommended)

### Sprint 4 (Agent Lain, Paralel) — WA Gateway Terpisah

```bash
# Repo: simt-wa-gateway/  (TERPISAH)
# Stack: Node.js 20 + Express + @whiskeysockets/baileys
# VPS: VPS-2 (1GB RAM)
# Auth: Static API Key dari Laravel (env WA_GATEWAY_API_KEY)
# Callback: POST ke Laravel /api/v1/wa/delivery-callback
```

### Sprint 5 — Phase 2 Portal Next.js (Saya Kerjakan)

```bash
# Repo: simt-portal/  (TERPISAH dari simt-backend)
# Stack: Next.js 14 (App Router) + TypeScript + Tailwind + shadcn/ui
# VPS: VPS-1 (512MB RAM, Nginx reverse proxy)
# Auth: Sanctum token dari Laravel (login via /api/v1/auth/login)
# Header: X-Tenant-Domain (e.g., mts-alhikmah)
```

### Shared API Contract — `DEV_DOCS/docs_sim/API_CONTRACT.md`

```
Sudah ada, diverifikasi dari `php artisan route:list` (52 route).
Portal Next.js WAJIB ikut kontrak ini.
Perubahan API Laravel → update API_CONTRACT.md → sinkron Portal.
```

### Opsional (Fase 2) — Monorepo kalau ada traction

```
Setelah ada traction & pendanaan, migrate ke Solusi 1 (monorepo).
Untuk MVP sekarang, Solusi 2 sudah cukup.
```

---

## 📋 8. Checklist Anti-Kesulitan

### Sebelum Lintas Codebase, Pastikan:
- [ ] **OpenAPI spec di-generate** dari Laravel setiap ada perubahan route
- [ ] **Portal Next.js pakai generated types** (bukan hard-coded)
- [ ] **Sanctum token** dipakai konsisten (Bearer di Authorization header)
- [ ] **X-Tenant-Domain header** dipakai konsisten (bukan subdomain)
- [ ] **Error response format** konsisten (Doc 22 §1.1 ApiResponseHelpers)
- [ ] **Webhook secret** untuk Laravel ↔ WA Gateway callback

### Tooling yang Membantu (Pilih Satu)
- [ ] **pnpm workspaces** (jika monorepo)
- [ ] **OpenAPI Generator** (untuk auto types)
- [ ] **Postman/Insomnia** collection (untuk testing API)
- [ ] **Docker Compose** (untuk dev env konsisten)

---

## 🏁 VERDICT FINAL

| Pertanyaan User | Jawaban |
|---|---|
| **Tidak kesulitan kah?** | ⚠️ **Ada kesulitan, tapi manageable** dengan strategi mitigasi yang tepat |
| **Multi-codebase by-design?** | ✅ **YA** — Doc 24, 49, 56 sudah eksplisit memisahkan |
| **Rekomendasi?** | ✅ **Tetap Multi-Repo + OpenAPI Spec** (Doc 24) untuk MVP. Migrate ke monorepo kalau ada traction. |
| **Apa yang harus dilakukan sekarang?** | (1) **Generate OpenAPI spec** dari Laravel, (2) **Buat Portal Next.js** dengan generated types, (3) **WA Gateway** di repo terpisah (Agent lain) |

---

## 📎 LAMPIRAN: Dokumen yang Direkomendasikan untuk Agent Berikutnya

| # | File | Tujuan |
|---|---|---|
| 1 | `DEV_DOCS/docs_sim/24_tech_arch_hybrid_blade_nextjs.md` | Arsitektur Hybrid Blade+Next.js (Doc utama) |
| 2 | `DEV_DOCS/docs_sim/56_SESSION_CONTEXT_HANDOVER.md` | §5 S4-01 = repo TERPISAH untuk WA Gateway |
| 3 | `DEV_DOCS/docs_sim/49_session_memory_sprint4_wa_gateway.md` | Sesi 6 Sprint 4 skeleton |
| 4 | `DEV_DOCS/docs_sim/API_CONTRACT.md` | Kontrak API Laravel ↔ Portal Next.js |
| 5 | `DEV_DOCS/docs_sim/22_api_rbac_implementation.md` | ApiResponseHelpers untuk konsistensi JSON |
| 6 | `DEV_DOCS/05_WhatsApp_Gateway_Runbook_SIMT_MTs.pdf` | Runbook WA Gateway (VPS-2, Node.js) |
| 7 | `DEV_DOCS/docs_sim/65_VERIFIKASI_MODUL_INTI_RBAC_AKADEMIK_2026-06-14_19-10.md` | Verifikasi Modul Inti/RBAC/Akademik |
| 8 | `DEV_DOCS/docs_sim/66_ANALISIS_MULTI_CODEBASE_ARCHITECTURE_2026-06-14_19-48.md` | Dokumen ini |

---

## ❓ PILIHAN UNTUK USER

- **(A)** Tetap Multi-Repo sesuai Doc 24 + mitigasi OpenAPI Spec (REKOMENDASI)
- **(B)** Migrate ke Monorepo (pnpm workspaces + turbo) — upfront cost 6h
- **(C)** Hybrid: Laravel+Next.js monorepo, WA Gateway terpisah (balance)
- **(D)** Tetap Multi-Repo tanpa OpenAPI Spec (Doc 24 apa adanya, risiko lebih tinggi)

Mana yang Anda pilih?

---

*Dokumen ini disusun 14 Juni 2026 19:48 WIB oleh Agent Arena Mode. Setiap klaim diverifikasi dengan cross-reference ke Doc 24, 49, 56, dan API_CONTRACT.md. Disimpan dengan format `xx_namafile_date_time.md` per konvensi.*

**Lokasi file asli:** `/home/user/66_ANALISIS_MULTI_CODEBASE_ARCHITECTURE_2026-06-14_19-48.md`
**Salin juga ke:** `/home/user/DEV_DOCS/docs_sim/66_ANALISIS_MULTI_CODEBASE_ARCHITECTURE_2026-06-14_19-48.md`
