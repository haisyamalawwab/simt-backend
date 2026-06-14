# DEV REPORT — SPRINT 4 SKELETON: WhatsApp Gateway Integration
## SIMT MTs — Integrasi OpenWA + Baileys

**Sprint:** 4 (Skeleton — 70%)  
**Tanggal:** 13 Juni 2026  
**Developer:** AI-Assisted (Founder)  
**Durasi:** 1 sesi  
**Referensi:** Doc 37 (PRD MVP), Doc 39 (Design MVP), Doc 46/48 (Session Memory), OpenWA v0.1.6

---

## 1. EXECUTIVE SUMMARY

Sprint 4 adalah **sprint paling berisiko** dalam roadmap MVP SIMT MTs (Doc 40 §8). Killer feature produk ini adalah **notifikasi WhatsApp otomatis** untuk presensi dan tagihan SPP — tanpa ini, produk tidak memiliki diferensiasi.

Pada sesi ini, skeleton WhatsApp Gateway telah dibangun dengan mengadaptasi boilerplate **OpenWA** (Open Source WhatsApp API Gateway by Yudhi Armyndharis) dan mengganti engine dari `whatsapp-web.js` (Puppeteer-based) ke **Baileys** (WebSocket-based) untuk mendukung deployment di VPS 1GB sesuai budget Doc 37 §3.

**Hasil utama:**
- ✅ 21 file kode dibuat (TypeScript + PHP + Docker + Config)
- ✅ Dokumentasi arsitektur 10 bab (`INTEGRATION_OPENWA_SIMT.md`)
- ✅ Baileys adapter mengimplementasi `IWhatsAppEngine` interface OpenWA
- ✅ Rate limiter + jitter anti-ban terimplementasi
- ✅ Laravel integration code (service + job + callback + routes + Blade)
- ⬜ Test suite dan UAT belum dijalankan (perlu `npm install` + koneksi WA nyata)

---

## 2. APA YANG DIBUAT (Deliverables)

### 2.1 OpenWA Clone (Boilerplate Asli)

| Aksi | Detail |
|---|---|
| Source | `https://github.com/rmyndharis/OpenWA` |
| Lokasi | `/home/user/OpenWA/` |
| Versi | v0.1.6 |
| Isi | NestJS 11, whatsapp-web.js, Dashboard React, 15+ modul, SQLite/PostgreSQL, Docker |

### 2.2 SIMT-WA-Gateway (Adaptasi dari OpenWA)

#### Core Files

| # | File | Baris | Keterangan |
|---|---|---|---|
| 1 | `src/main.ts` | ~120 | Entry point NestJS — adaptasi dari OpenWA `main.ts` dengan SIMT config |
| 2 | `src/engine/adapters/baileys.adapter.ts` | ~510 | **Engine utama** — mengimplementasi `IWhatsAppEngine` menggunakan `@whiskeysockets/baileys` |
| 3 | `package.json` | ~65 | Dependencies: Baileys, NestJS 11, TypeORM, SQLite, QR |
| 4 | `.env.example` | ~75 | Konfigurasi lengkap (database, engine, SIMT integration, rate limiting) |
| 5 | `Dockerfile` | ~45 | Multi-stage build (alpine, production-only deps) |
| 6 | `docker-compose.yml` | ~55 | VPS-2 deployment (768MB limit, health check, persistent volumes) |

#### Tenant Module (Baru — tidak ada di OpenWA)

| # | File | Baris | Keterangan |
|---|---|---|---|
| 7 | `src/modules/tenant/tenant.module.ts` | ~15 | NestJS module definition |
| 8 | `src/modules/tenant/entities/tenant.entity.ts` | ~75 | Entity: id, name, domain, status, waPhone, waConfig (JSON) |
| 9 | `src/modules/tenant/tenant.service.ts` | ~200 | Service: CRUD tenant, session start/stop/qr/status, onSessionConnected callback |
| 10 | `src/modules/tenant/tenant.controller.ts` | ~95 | Controller: 8 endpoints (CRUD + session management) |

#### Notification Module (Baru — tidak ada di OpenWA)

| # | File | Baris | Keterangan |
|---|---|---|---|
| 11 | `src/modules/notification/notification.module.ts` | ~15 | NestJS module definition |
| 12 | `src/modules/notification/notification.service.ts` | ~300 | **Service utama**: sendSingle, sendBulk, rateLimiter, normalizePhone, deliveryCallback |
| 13 | `src/modules/notification/notification.controller.ts` | ~55 | Controller: 2 endpoints (send, send-bulk) |

#### Laravel Integration (PHP — dipasang di simt-backend)

| # | File | Baris | Keterangan |
|---|---|---|---|
| 14 | `src/laravel/WaGatewayService.php` | ~180 | HTTP client ke WA Gateway: startSession, getQrCode, sendMessage, sendBulk |
| 15 | `src/laravel/ProcessWaNotification.php` | ~100 | Queue job: pop wa_notification → send → update status |
| 16 | `src/laravel/WaCallbackController.php` | ~90 | Webhook handler: terima delivery callback dari WA Gateway |
| 17 | `src/laravel/routes-wa-integration.php` | ~120 | Routes (web + API) + WaSessionApiController + WaConnectController |
| 18 | `src/laravel/wa-connect-blade.php` | ~200 | Blade view: QR scan untuk admin sekolah (AJAX polling) |

#### Documentation

| # | File | Baris | Keterangan |
|---|---|---|---|
| 19 | `INTEGRATION_OPENWA_SIMT.md` | ~600 | **Dokumen arsitektur utama** — 10 bab lengkap |
| 20 | `README.md` | ~200 | Panduan penggunaan gateway |
| 21 | `SPRINT4_SUMMARY.md` | ~100 | Ringkasan sprint 4 |

### 2.3 Dokumen Proyek (Download dari Google Drive)

| Aksi | Detail |
|---|---|
| Source | Google Drive `!!SIMSEKOLAH_MTS2026_EDUFUNESIA!!` |
| Lokasi | `/home/user/docs_sim/` |
| Jumlah | 49 file (Doc 00–48 + root files) |
| Format | Markdown, HTML, PDF |
| Total ukuran | ~1.2MB (text only) |

---

## 3. KEPUTUSAN TEKNIS & RASIONAL

### 3.1 Mengapa Fork OpenWA, Bukan Tulis dari Nol?

| Aspek | Tulis dari Nol | Fork + Adaptasi OpenWA |
|---|---|---|
| Waktu | ~20 jam | **~8 jam** |
| Engine interface | Harus desain sendiri | **Sudah ada** `IWhatsAppEngine` |
| Session management | Harus tulis sendiri | **Sudah ada** `SessionService` |
| Message handling | Harus tulis sendiri | **Sudah ada** `MessageService` |
| Database (TypeORM) | Setup dari nol | **Sudah dikonfigurasi** |
| Swagger docs | Setup dari nol | **Sudah ada** |
| Error handling | Tulis sendiri | **Filter + Interceptor sudah ada** |
| Health check | Tulis sendiri | **Sudah ada** |
| Plugin system | Tidak ada | **Bisa dipakai** untuk fitur post-MVP |

**Keputusan: Fork + Adaptasi.** Hemat ~60% waktu Sprint 4.

### 3.2 Mengapa Baileys, Bukan whatsapp-web.js?

| Metrik | whatsapp-web.js | Baileys | Dampak ke SIMT |
|---|---|---|---|
| RAM per session | 300–500MB | **30–50MB** | VPS 1GB bisa handle 5–10 tenant |
| Dependency | Puppeteer + Chrome | **WebSocket only** | Tidak perlu install Chrome di VPS |
| Startup time | 10–30 detik | **3–5 detik** | QR code lebih cepat muncul |
| Multi-session stability | Berat (multi-browser) | **Ringan (multi-socket)** | Stabil untuk 5+ sekolah |
| Compatibility | Chrome version sensitive | **WhatsApp protocol** | Lebih stabil jangka panjang |
| Cost | 0 (open source) | **0 (open source)** | Sama |

**Keputusan: Baileys.** Selaras dengan PRD MVP Doc 37 §2.1 "Zero-Cost WA (Baileys, sekolah scan QR sendiri)" dan budget VPS 1GB Doc 37 §3.

### 3.3 Mengapa Rate Limiter di Gateway Side?

Alternatif: rate limiter di Laravel (queue throttle).

| Aspek | Laravel-side | Gateway-side |
|---|---|---|
| Kontrol granular | Per queue, bukan per tenant | **Per tenant** (configurable) |
| Jitter | Tidak ada built-in | **Built-in 3-8s random delay** |
| Response feedback | Tidak tahu apakah terkirim | **Immediate success/failure** |
| Anti-ban | Lemah | **Kuat** (delay antar pesan di engine level) |

**Keputusan: Gateway-side.** Lebih dekat ke engine, lebih reliable anti-ban.

### 3.4 Modul yang Dihapus dari OpenWA

| Modul OpenWA | Alasan Dihapus | Alternatif SIMT |
|---|---|---|
| Dashboard (React) | MVP tidak butuh UI terpisah | QR via Laravel Blade |
| Channel/Newsletter | Baileys tidak support, MVP tidak butuh | Post-MVP |
| Status/Stories | Baileys tidak support, MVP tidak butuh | Post-MVP |
| Catalog | Baileys tidak support, MTs bukan Business WA | Tidak relevan |
| Labels | Baileys tidak support, MVP tidak butuh | Post-MVP |
| Contact API | MVP tidak butuh contact management | — |
| Group API (extended) | MVP hanya butuh basic | Basic via Baileys |
| Plugins API | MVP tidak butuh plugin marketplace | Post-MVP |
| Docker module | MVP tidak butuh container management | — |
| Stats module | MVP tidak butuh analytics | Post-MVP |
| Infra module | MVP tidak butuh infra management | — |
| Queue (BullMQ) | MVP pakai rate limiter sederhana | Built-in rate limiter |
| Audit module | MVP tidak butuh audit trail | Post-MVP |
| Settings module | MVP pakai env vars | — |
| Events (WebSocket) | MVP tidak butuh real-time dashboard | Post-MVP |

---

## 4. ARSITEKTUR DETAIL

### 4.1 Alur Data: Presensi → Notifikasi WA

```
Guru          Laravel              Redis Queue        WA Gateway         Wali
 │ POST /class │                      │                  │                │
 │ /attendance │                      │                  │                │
 ├────────────►│ 1. Validate+upsert   │                  │                │
 │   201 ✓     │ 2. Create            │                  │                │
 │◄────────────┤    WaNotification    │                  │                │
 │             │    (status: pending) │                  │                │
 │             │ 3. Dispatch          │                  │                │
 │             │    ProcessWaNotif ───►│ 4. Worker pop    │                │
 │             │                      │ 5. HTTP POST     │                │
 │             │                      │ /tenant/{id}/send│                │
 │             │                      ├─────────────────►│ 6. Rate check  │
 │             │                      │                  │ 7. Jitter 3-8s │
 │             │                      │                  │ 8. Baileys     │
 │             │                      │                  │    send()      │
 │             │                      │                  ├───────────────►│ 📱
 │             │                      │                  │ 9. ack + msgId │
 │             │                      │                  │                │
 │             │                      │ 10. HTTP 200     │                │
 │             │                      │◄──────────────────┤                │
 │             │ 11. Update notif     │                  │                │
 │             │     status=sent ◄────┤                  │                │
 │             │                      │                  │                │
 │             │                      │ 12. Callback     │                │
 │             │                      │     (async)      │                │
 │             │ 13. Delivery ack ◄───┤◄──────────────────┤                │
 │             │     status=delivered │                  │                │
```

### 4.2 Tenant ↔ Session Mapping

```
Laravel (simt-backend)              WA Gateway (simt-wa-gateway)
┌──────────────────────┐            ┌─────────────────────────────────┐
│ tenants table         │            │ tenants table (SQLite)          │
│ ┌──────────────────┐  │            │ ┌───────────────────────────┐   │
│ │ id: mts-alhikmah │  │   sync     │ │ id: mts-alhikmah          │   │
│ │ name: MTs Al-H   │──┼───────────►│ │ name: MTs Al-H           │   │
│ │ wa_notify_pres: 0 │  │  (create) │ │ waConfig: {rate:10,...}   │   │
│ └──────────────────┘  │            │ └───────────────────────────┘   │
│                        │            │                                  │
│ wa_notifications table │            │ sessions table                   │
│ ┌──────────────────┐  │            │ ┌───────────────────────────┐   │
│ │ id: 1             │  │  send      │ │ name: tenant:mts-alhikmah│   │
│ │ to_phone: 628...  │──┼───────────►│ │ status: ready            │   │
│ │ status: pending   │  │  (HTTP)    │ │ phone: 628...            │   │
│ │ type: attendance  │  │            │ └───────────────────────────┘   │
│ └──────────────────┘  │            │                                  │
│                        │            │ Baileys auth state               │
│                        │            │ ./data/sessions/                 │
│                        │            │   tenant:mts-alhikmah/           │
│                        │            │     creds.json                   │
│                        │            │     app-state-sync-key-1.json    │
│                        │            │     session-1.json               │
└──────────────────────┘            └─────────────────────────────────┘
```

### 4.3 Rate Limiting & Anti-Ban Strategy

```
                  Tenant Rate Limiter
                  ┌──────────────────────────────────────┐
                  │ Window: 60 detik                      │
                  │ Limit: 10 pesan/window (configurable) │
                  │                                       │
Request #1  ─────►│ ✅ Allow → jitter 5.2s → send         │
Request #2  ─────►│ ✅ Allow → jitter 3.8s → send         │
Request #3  ─────►│ ✅ Allow → jitter 7.1s → send         │
...                │ ...                                   │
Request #10 ─────►│ ✅ Allow → jitter 4.5s → send         │
Request #11 ─────►│ ⏳ Wait → 42s remaining → then allow  │
                  └──────────────────────────────────────┘
                  
                  Jitter Distribution (3-8 detik)
                  ┌────────────────────────────────┐
                  │  ░░░░░░░░░░░░░░░░░░░░░░░░░░░  │
                  │  3s                        8s  │
                  │  Random uniform distribution     │
                  │  Meniru jeda ketikan manusia     │
                  └────────────────────────────────┘
```

### 4.4 Retry Strategy

```
Attempt 1 ──► Gagal ──► Backoff 60s ──► Attempt 2 ──► Gagal ──► Backoff 120s ──► Attempt 3
                                                                                        │
                                                                                 Gagal ──► permanently_failed
                                                                                 Sukses ──► status = sent
```

---

## 5. API CONTRACT

### 5.1 Gateway Endpoints (dipanggil oleh Laravel)

| Method | Path | Body | Response | Keterangan |
|---|---|---|---|---|
| `POST` | `/api/tenant` | `{id, name, domain?}` | Tenant | Register tenant baru |
| `GET` | `/api/tenant` | — | Tenant[] | List semua tenant |
| `GET` | `/api/tenant/:id` | — | Tenant | Detail tenant |
| `DELETE` | `/api/tenant/:id` | — | 204 | Hapus tenant + stop session |
| `POST` | `/api/tenant/:id/session/start` | — | TenantSessionStatus | Start WA session → QR |
| `POST` | `/api/tenant/:id/session/stop` | — | TenantSessionStatus | Stop WA session |
| `GET` | `/api/tenant/:id/session/qr` | — | `{qrCode, status}` | QR code untuk scan |
| `GET` | `/api/tenant/:id/session/status` | — | TenantSessionStatus | Status koneksi WA |
| `POST` | `/api/tenant/:id/send` | `{to, text, type?, referenceId?}` | SendResult | Kirim 1 pesan |
| `POST` | `/api/tenant/:id/send-bulk` | `{messages[], batchId?}` | BulkSendResult | Kirim banyak (rate-limited) |
| `GET` | `/api/health` | — | `{status: "ok"}` | Health check |

**Auth:** Semua endpoint membutuhkan header `X-API-Key`.

### 5.2 Laravel Webhook Endpoint (dipanggil oleh Gateway)

| Method | Path | Body | Keterangan |
|---|---|---|---|
| `POST` | `/api/v1/wa/delivery-callback` | `{tenantId, referenceId, messageId?, status, timestamp?, error?}` | Delivery status update |

**Auth:** Header `X-Callback-Secret`.

### 5.3 Entity Schemas

```sql
-- tenants (WA Gateway SQLite)
CREATE TABLE tenants (
  id              VARCHAR(100) PRIMARY KEY,  -- e.g., 'mts-alhikmah'
  name            VARCHAR(255) NOT NULL,
  domain          VARCHAR(255),
  status          VARCHAR(50) DEFAULT 'prospect',
  wa_notify_present BOOLEAN DEFAULT FALSE,
  wa_phone        VARCHAR(30),
  wa_push_name    VARCHAR(255),
  wa_connected_at DATETIME,
  wa_config       TEXT,  -- JSON: {rateLimitPerMinute, jitterMinMs, jitterMaxMs, maxRetries, retryBackoffMs}
  created_at      DATETIME,
  updated_at      DATETIME
);
```

---

## 6. METRIK & TARGET

### 6.1 Definition of Done Sprint 4

| Metrik | Target | Status |
|---|---|---|
| WA Gateway bisa start/stop session per tenant | ✅ | ✅ Kode ada |
| Admin sekolah bisa scan QR dari dashboard Laravel | ✅ | 🟡 Skeleton Blade |
| Kirim 100 notifikasi WA dengan delivery rate ≥95% | ≥95% | ⬜ Perlu testing |
| Rate-limit: ≤10 pesan/menit per tenant + jitter 3-8s | ✅ | ✅ Kode ada |
| Retry: pesan gagal otomatis retry ≤3× dengan backoff | ✅ | ✅ Kode ada |
| Delivery status callback mengupdate wa_notifications | ✅ | ✅ Kode ada |
| Memory usage ≤256MB untuk 5 tenant session | ≤256MB | ⬜ Perlu profiling |
| Test suite: ≥15 test hijau | ≥15 | ⬜ Perlu npm install |
| Docker build + deploy ke VPS-2 | ✅ | ✅ Kode ada |

### 6.2 Budget Impact

| Pos Biaya | Budgeted (Doc 37 §3) | Actual | Selisih |
|---|---|---|---|
| VPS 1GB (staging/WA gateway) | Rp 300.000 | Rp 0 (belum sewa) | -Rp 300.000 |
| Nomor WA khusus + paket data | Rp 350.000 | Rp 0 (belum beli) | -Rp 350.000 |
| **Sisa budget untuk S4** | **Rp 650.000** | **Rp 0** | **Rp 650.000** tersisa |

> Catatan: VPS + nomor WA baru disewa saat ada calon pilot serius (aturan kas Doc 37 §3). Development di laptop lokal dulu.

---

## 7. RISIKO YANG TERIDENTIFIKASI

| # | Risiko | Probabilitas | Dampak | Mitigasi | Status |
|---|---|---|---|---|---|
| R1 | Baileys di-banned Meta | Sedang | **KRITIS** — killer feature mati | Rate-limit + jitter + nomor milik sekolah + klausul MoU Doc 36 | ⚠️ Monitoring |
| R2 | Memory leak Baileys | Sedang | Tinggi — VPS crash | Docker memory limit 768MB + health check + auto-restart | ⬜ Perlu profiling |
| R3 | Baileys versi baru breaking change | Rendah | Sedang | Lock version di package.json | ✅ Locked |
| R4 | VPS-2 down, queue menumpuk | Sedang | Sedang | Redis queue persistent + worker resume otomatis | ✅ Arsitektur ok |
| R5 | Laravel codebase tidak ikut snapshot | **Tinggi** | **Tinggi** — harus rekonstruksi | Doc 46/48 berisi instruksi setup ulang | ⚠️ Perlu re-download ZIP |
| R6 | QR code expired saat admin scan | Rendah | Sedang | Auto-refresh QR setiap 20s di Blade | ✅ Kode ada |

---

## 8. FILE MANIFEST (Complete List)

### `/home/user/OpenWA/` (Boilerplate — 73 file dari git)
- Di-clone dari `https://github.com/rmyndharis/OpenWA`
- Tidak dimodifikasi — hanya referensi

### `/home/user/simt-wa-gateway/` (Adaptasi — 21 file baru)

```
simt-wa-gateway/
├── INTEGRATION_OPENWA_SIMT.md          # Dokumen arsitektur (10 bab)
├── README.md                           # Panduan penggunaan
├── SPRINT4_SUMMARY.md                  # Ringkasan sprint
├── package.json                        # Dependencies
├── .env.example                        # Konfigurasi
├── Dockerfile                          # Multi-stage build
├── docker-compose.yml                  # VPS-2 deployment
└── src/
    ├── main.ts                         # Entry point
    ├── engine/
    │   └── adapters/
    │       └── baileys.adapter.ts      # Baileys engine adapter
    ├── modules/
    │   ├── tenant/
    │   │   ├── tenant.module.ts
    │   │   ├── entities/
    │   │   │   └── tenant.entity.ts
    │   │   ├── tenant.service.ts
    │   │   └── tenant.controller.ts
    │   └── notification/
    │       ├── notification.module.ts
    │       ├── notification.service.ts  # Rate-limit + jitter + retry
    │       └── notification.controller.ts
    └── laravaan/
        ├── WaGatewayService.php        # HTTP client
        ├── ProcessWaNotification.php   # Queue job
        ├── WaCallbackController.php    # Webhook handler
        ├── routes-wa-integration.php   # Routes
        └── wa-connect-blade.php        # QR scan view
```

### `/home/user/docs_sim/` (Dokumen proyek — 49 file dari Google Drive)

```
docs_sim/
├── 00_README_SESI3.md
├── 01_analisis_kelayakan.md
├── 02_analisis_kebutuhan.md
├── 03_pemetaan_modul_rbac.md
├── 04_prd_sim_mts.md
├── 05_requirements_srs.md
├── 06_design_system_erd.md
├── 07_roadmap_tasks_sprint.md
├── 08_project_charter.md
├── 09_software_architecture.md
├── 10_security_plan.md
├── 11_test_strategy.md
├── 12_uat_plan.md
├── 13_deployment_plan.md
├── 14_user_manual.md
├── 15_disaster_recovery.md
├── 16_operations_manual.md
├── 17_api_contract_design.md
├── 18_developer_guidelines.md
├── 19_ui_ux_screen_flow.md
├── 20_slide_deck_kickoff.md
├── 21_tech_arch_multitenant_modular.md
├── 22_api_rbac_implementation.md
├── 23_dev_execution_roadmap.md
├── 24_tech_arch_hybrid_blade_nextjs.md
├── 25_frontend_admin_blade_modular.md
├── 26_frontend_public_nextjs_react.md
├── 27_rbac_deep_analysis_design.md
├── 28_modular_mvc_api_design.md
├── 29_api_endpoints_general_vs_plugins.md
├── 30_payment_gateway_integration.md
├── 31_micro_saas_critical_swot_analysis.md
├── 32_b2b2c_pricing_roi_analysis.md
├── 33_pessimistic_scenario_survival_plan.md
├── 34_pitching_strategy_hybrid.md
├── 35_max_profit_strategy_timeline.md
├── 36_draft_kontrak_kerjasama_saas.md
├── 37_prd_mvp_3bulan_5juta.md
├── 38_requirements_mvp.md
├── 39_design_mvp.md
├── 40_task_breakdown_sprint_mvp.md
├── 41_visualisasi_konsep_mvp.html
├── 42_diagram_proses_mvp.md
├── 43_visualisasi_peta_dokumen_drive.html
├── 44_sprint1_execution_report.md
├── 45_sprint2_execution_report.md
├── 46_session_memory_handover_sprint3.md
├── 47_sprint5_reconstruction_report.md
├── 48_session_memory_handover_sprint3_completion.md
├── 49_session_memory_sprint4_wa_gateway.md   # ← BARU (sesi ini)
├── ANALISIS_SIMT_MTs_Survey_Market_Check.md
├── DEV-REPORT-SPRINT1-2-LARAVEL.md
└── dev-report-sprint1-2-nodeexpress.md
```

---

## 9. LANGKAH SELANUTNYA (Prioritas Berurutan)

### 🔴 Prioritas 1: Selesaikan Sprint 3 (Presensi) — DITUNDA sejak Sesi 5

Target gate: "Guru absen 1 kelas (40 siswa) ≤ 60 detik dari HP"

| ID | Task | Estimasi |
|---|---|---|
| S3-02b | Grid presensi UX: JS tap toggle H→A→I→S→T | 3 jam |
| S3-05 | Rekap bulanan export Excel (maatwebsite/excel) | 2 jam |
| S3-06 | Dashboard kepala: % kehadiran + tren 7 hari | 2 jam |
| S3-08 | AttendanceModuleTest (±9 test) | 3 jam |

### 🔴 Prioritas 2: Selesaikan Sprint 4 (WA Gateway)

| ID | Task | Estimasi | Prasyarat |
|---|---|---|---|
| S4-07 | `npm install` + test suite gateway | 4 jam | npm install berhasil |
| S4-08 | QR scan Blade final + route integration | 3 jam | Laravel codebase ada |
| S4-10 | UAT: 100 WA notif dari data demo | 2 jam | Server berjalan + nomor WA test |

### 🟡 Prioritas 3: Sprint 5 (Keuangan SPP + Portal Ortu)
- Menunggu S3 + S4 selesai

---

## 10. KONFIGURASI LARAVEL YANG PERLU DITAMBAHKAN

```php
// config/services.php — tambahkan:
'wa_gateway' => [
    'url' => env('WA_GATEWAY_URL', 'http://localhost:2785/api'),
    'api_key' => env('WA_GATEWAY_API_KEY', 'dev-api-key'),
    'callback_secret' => env('WA_GATEWAY_CALLBACK_SECRET'),
    'timeout' => env('WA_GATEWAY_TIMEOUT', 30),
],
```

```env
# .env — tambahkan:
WA_GATEWAY_URL=http://localhost:2785/api
WA_GATEWAY_API_KEY=dev-api-key-change-in-production
WA_GATEWAY_CALLBACK_SECRET=dev-callback-secret-change-in-production
WA_GATEWAY_TIMEOUT=30
```

---

*Dokumen ini merupakan Dev Report untuk Sprint 4 (Skeleton) SIMT MTs. Seluruh keputusan di §3 bersifat FINAL kecuali ada blocker teknis nyata. Untuk konteks lengkap, baca: Doc 46/48 (session memory sebelumnya), Doc 40 (sprint plan), INTEGRATION_OPENWA_SIMT.md (arsitektur detail).*
