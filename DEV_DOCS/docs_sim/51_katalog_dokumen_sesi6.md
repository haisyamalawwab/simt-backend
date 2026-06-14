# KATALOG DOKUMEN — Sesi 6: Sprint 4 WA Gateway Integration
## SIMT MTs — Daftar Lengkap File yang Dibuat

**Tanggal:** 13 Juni 2026  
**Sesi:** Ke-6  
**Fokus:** Integrasi OpenWA + Baileys sebagai WhatsApp Gateway SIMT MTs (Sprint 4 Skeleton)

---

## 📊 RINGKASAN KUANTITAS

| Kategori | Jumlah File | Total Baris Kode |
|---|---|---|
| TypeScript (NestJS) | 9 | ~1.400 |
| PHP (Laravel) | 5 | ~690 |
| Docker & Config | 4 | ~235 |
| Dokumentasi Markdown | 5 | ~1.850 |
| **TOTAL** | **23** | **~4.175** |

---

## 📁 STRUKTUR WORKSPACE SETIAP SESI INI

```
/home/user/
├── OpenWA/                              # [CLONE] Boilerplate asli dari GitHub
├── simt-wa-gateway/                     # [BARU] Adaptasi OpenWA untuk SIMT
│   ├── src/
│   │   ├── main.ts
│   │   ├── engine/adapters/baileys.adapter.ts
│   │   ├── modules/tenant/
│   │   ├── modules/notification/
│   │   └── laravel/                     # PHP files untuk integrasi
│   ├── Dockerfile
│   ├── docker-compose.yml
│   ├── package.json
│   ├── .env.example
│   ├── INTEGRATION_OPENWA_SIMT.md
│   ├── README.md
│   └── SPRINT4_SUMMARY.md
└── docs_sim/                            # [DOWNLOAD + BARU] Dokumen proyek
    ├── 00-48 (dari Google Drive)
    ├── 49_session_memory_sprint4_wa_gateway.md   # [BARU]
    └── 50_dev_report_sprint4_wa_gateway.md        # [BARU]
```

---

## 📋 DAFTAR LENGKAP FILE YANG DIBUAT DI SESI INI

### A. SOURCE CODE — TypeScript (NestJS 11 + Baileys)

#### A1. Entry Point

| # | File Path | Baris | Deskripsi |
|---|---|---|---|
| 1 | `simt-wa-gateway/src/main.ts` | ~120 | Entry point NestJS. Diadaptasi dari OpenWA `main.ts`. Perbedaan: konfigurasi SIMT (SIMT_API_URL, SIMT_API_KEY, SIMT_CALLBACK_SECRET), Swagger tags dipersempit ke tenant/sessions/messages/notification/health, info banner mencantumkan engine Baileys dan integrasi SIMT. |

#### A2. Engine Adapter — Baileys

| # | File Path | Baris | Deskripsi |
|---|---|---|---|
| 2 | `simt-wa-gateway/src/engine/adapters/baileys.adapter.ts` | ~510 | **File terpenting sesi ini.** Mengimplementasi `IWhatsAppEngine` (interface dari OpenWA) menggunakan `@whiskeysockets/baileys`. Fitur: (1) `initialize()` — load auth state dari disk, create WASocket, bind event handlers (connection.update, messages.upsert, messages.receipt.update); (2) `sendTextMessage()` — kirim teks via `sock.sendMessage(jid, {text})`; (3) `sendImageMessage/sendVideoMessage/sendAudioMessage/sendDocumentMessage()` — kirim media via buffer; (4) `sendLocationMessage/sendContactMessage/sendStickerMessage()` — extended messaging; (5) `replyToMessage()` — reply dengan quoted message; (6) `reactToMessage()` — reaksi emoji; (7) `handleConnectionUpdate()` — QR code generation, ready/disconnected/authenticating state transitions; (8) `handleMessageUpsert()` — konversi WAMessage → IncomingMessage; (9) `normalizePhone()` + `resolveMediaBuffer()` — helper utilities. Semua fitur yang tidak didukung Baileys (Labels, Channel, Status/Stories, Catalog) melempar error eksplisit. |

#### A3. Tenant Module

| # | File Path | Baris | Deskripsi |
|---|---|---|---|
| 3 | `simt-wa-gateway/src/modules/tenant/tenant.module.ts` | ~15 | NestJS module definition. Import TypeOrmModule for Tenant entity, register TenantController + TenantService, export TenantService (dipakai NotificationModule). |
| 4 | `simt-wa-gateway/src/modules/tenant/entities/tenant.entity.ts` | ~75 | TypeORM entity. Kolom: `id` (PK, varchar 100 — e.g., 'mts-alhikmah'), `name`, `domain`, `status` (enum: prospect/contracted/active/suspended/terminated), `waNotifyPresent` (bool — kirim notif juga saat HADIR?), `waPhone`, `waPushName`, `waConnectedAt`, `waConfig` (JSON: rateLimitPerMinute=10, jitterMinMs=3000, jitterMaxMs=8000, maxRetries=3, retryBackoffMs=60000), `createdAt`, `updatedAt`. Computed: `sessionName` → `tenant:{id}`, `effectiveWaConfig` → merge default. |
| 5 | `simt-wa-gateway/src/modules/tenant/tenant.service.ts` | ~200 | Service layer. Method: `create()` — register tenant baru; `findAll/findOne/update/remove()` — CRUD; `getSessionStatus()` — gabungkan tenant + WA session status; `startSession()` — find-or-create session, start Baileys engine; `stopSession()` — disconnect + clear WA info; `getQrCode()` — ambil QR dari session service; `onSessionConnected()` — callback saat WA terhubung, update waPhone/waPushName/waConnectedAt. |
| 6 | `simt-wa-gateway/src/modules/tenant/tenant.controller.ts` | ~95 | REST API controller. Tag: `tenant`. Auth: `X-API-Key`. Endpoints: `POST /api/tenant` (create), `GET /api/tenant` (list), `GET /api/tenant/:tenantId` (detail), `DELETE /api/tenant/:tenantId` (hapus), `POST /api/tenant/:tenantId/session/start` (start WA session), `POST /api/tenant/:tenantId/session/stop` (stop session), `GET /api/tenant/:tenantId/session/qr` (QR code), `GET /api/tenant/:tenantId/session/status` (status). |

#### A4. Notification Module

| # | File Path | Baris | Deskripsi |
|---|---|---|---|
| 7 | `simt-wa-gateway/src/modules/notification/notification.module.ts` | ~15 | NestJS module definition. Register NotificationController + NotificationService + TenantRateLimiter, export NotificationService. |
| 8 | `simt-wa-gateway/src/modules/notification/notification.service.ts` | ~300 | **Service inti notifikasi.** Class `NotificationService`: (1) `sendSingle()` — validasi session aktif, normalisasi nomor HP (08xx→628xx), rate-limit check via `TenantRateLimiter.waitForSlot()`, kirim via `MessageService.sendText()`, kirim delivery callback ke Laravel; (2) `sendBulk()` — loop sendSingle per pesan, return BulkSendResult (total/sent/failed/duration); (3) `normalizePhone()` — 08xx→628xx, +62→62, 8xx→628xx, validasi regex `^628\d{7,12}$`; (4) `sendDeliveryCallback()` — POST ke SIMT_API_URL/wa/delivery-callback dengan X-Callback-Secret. Class `TenantRateLimiter` (Injectable): (1) `waitForSlot()` — counter per tenant per menit, jika exceed → tunggu reset window; (2) jitter delay 3-8 detik random (anti-ban); (3) `getStatus()` — inspect counter; (4) `reset()` — clear counter. |
| 9 | `simt-wa-gateway/src/modules/notification/notification.controller.ts` | ~55 | REST API controller. Tag: `notification`. Auth: `X-API-Key`. Endpoints: `POST /api/tenant/:tenantId/send` (kirim 1 pesan), `POST /api/tenant/:tenantId/send-bulk` (kirim banyak pesan rate-limited). DTO: `SendNotificationDto {to, text, type?, referenceId?}`, `SendBulkNotificationDto {messages[], batchId?}`. |

---

### B. SOURCE CODE — PHP (Laravel 13 Integration)

| # | File Path | Baris | Deskripsi |
|---|---|---|---|
| 10 | `simt-wa-gateway/src/laravel/WaGatewayService.php` | ~180 | **Laravel HTTP client ke WA Gateway.** Method: `startSession($tenantId)` — POST /tenant/{id}/session/start; `stopSession($tenantId)` — POST /session/stop; `getQrCode($tenantId)` — GET /session/qr; `getSessionStatus($tenantId)` — GET /session/status; `sendMessage($tenantId, $to, $text, $type, $referenceId)` — POST /send; `sendBulk($tenantId, $messages, $batchId)` — POST /send-bulk; `createTenant($tenantId, $name, $domain)` — POST /tenant; `deleteTenant($tenantId)` — DELETE /tenant/{id}; `isHealthy()` — GET /health. Private: `get()`, `post()`, `delete()` — wrapper Http facade dengan X-API-Key header. |
| 11 | `simt-wa-gateway/src/laravel/ProcessWaNotification.php` | ~100 | **Queue job Laravel.** Implements `ShouldQueue`. Config: `$tries = 3`, `$backoff = 60`, `$timeout = 120`. Method `handle()`: (1) Find WaNotification by ID; (2) Skip jika sudah sent; (3) Update status=processing; (4) Call WaGatewayService::sendMessage(); (5) Jika sukses → update status=sent, wa_message_id, sent_at; (6) Jika gagal → update status=failed, last_error; re-throw untuk trigger retry. Method `failed()`: set status=permanently_failed, Log::critical. |
| 12 | `simt-wa-gateway/src/laravel/WaCallbackController.php` | ~90 | **Webhook handler menerima delivery callback dari WA Gateway.** Route: `POST /api/v1/wa/delivery-callback`. Method `handle()`: (1) Verify X-Callback-Secret header; (2) Validate payload (tenantId, referenceId, messageId, status, timestamp, error); (3) Find WaNotification by referenceId; (4) Update status berdasarkan callback: sent/delivered/read/failed; (5) Return JSON `{success: true}`. Status mapping: sent → update wa_message_id + sent_at; delivered → delivered_at; read → read_at; failed → last_error. |
| 13 | `simt-wa-gateway/src/laravel/routes-wa-integration.php` | ~120 | **Laravel routes + API controller + Blade controller.** Web routes: `GET /wa/connect` (middleware: auth, tenant.web, module:core) → WaConnectController@index. API routes (prefix v1, middleware auth:sanctum): `GET /wa/session-status`, `POST /wa/session/start`, `POST /wa/session/stop`, `GET /wa/session/qr`, `POST /wa/notify-present`. Callback route: `POST /v1/wa/delivery-callback` (no auth, verified by X-Callback-Secret). Class `WaSessionApiController`: proxy ke WaGatewayService, inject Tenancy untuk auto-resolve tenantId. Class `WaConnectController`: return view `core::wa-connect`. |
| 14 | `simt-wa-gateway/src/laravel/wa-connect-blade.php` | ~200 | **Blade view untuk QR scan.** UI: (1) Status card — indikator dot (hijau=konek, kuning=menunggu, abu=belum), info nomor/nama/waktu; (2) QR area — render QR image, refresh otomatis 20 detik; (3) Connected area — centang hijau + nomor; (4) Tombol Hubungkan/Putuskan; (5) Pengaturan — checkbox "Kirim notif saat Hadir". JavaScript: `checkStatus()` — polling status, update UI; `startSession()` — POST start; `stopSession()` — POST stop; `fetchQR()` — GET qr, render via Google Charts API; `renderQR()` — embed QR image; `updateNotifyPresent()` — POST setting. |

---

### C. DOCKER & KONFIGURASI

| # | File Path | Baris | Deskripsi |
|---|---|---|---|
| 15 | `simt-wa-gateway/Dockerfile` | ~45 | Multi-stage build. Stage 1 (builder): node:22-alpine, `npm ci`, copy source, `npm run build`. Stage 2 (production): node:22-alpine, install wget (healthcheck), `npm ci --only=production`, copy dist from builder, create data directories, EXPOSE 2785, HEALTHCHECK wget ke /api/health, CMD node dist/main.js. |
| 16 | `simt-wa-gateway/docker-compose.yml` | ~55 | Single service `wa-gateway`. Port 2785. Volumes: wa-data (/app/data), wa-sessions (/app/data/sessions). Environment: NODE_ENV, PORT, DATABASE_TYPE=sqlite, ENGINE_TYPE=baileys, SESSION_DATA_PATH, SIMT_API_URL, SIMT_API_KEY, SIMT_CALLBACK_SECRET, rate limit defaults, BAILEYS_LOG_LEVEL. Deploy: memory limit 768MB, reservation 256MB. Healthcheck: wget setiap 30s. Logging: json-file, max 10m, max 3 files. |
| 17 | `simt-wa-gateway/package.json` | ~65 | NPM package. Name: simt-wa-gateway. Scripts: build, start:dev, dev, test. Dependencies: @nestjs/common, core, platform-express, swagger, throttler, typeorm, **@whiskeysockets/baileys** (^6.7.8), class-validator, class-transformer, helmet, pino, qrcode, sqlite3, typeorm, uuid. DevDependencies: @nestjs/cli, testing, typescript, jest, ts-jest. |
| 18 | `simt-wa-gateway/.env.example` | ~75 | Konfigurasi lengkap. Bagian: APPLICATION (PORT, NODE_ENV), DATABASE (sqlite default + postgres optional), WHATSAPP ENGINE (baileys, session path, log level), SIMT INTEGRATION (api url, api key, callback secret), RATE LIMITING (10/min, jitter 3-8s, max retries 3, backoff 60s), CORS, LOGGING. |

---

### D. DOKUMENTASI MARKDOWN

| # | File Path | Baris | Deskripsi |
|---|---|---|---|
| 19 | `simt-wa-gateway/INTEGRATION_OPENWA_SIMT.md` | ~600 | **Dokumen arsitektur utama.** 10 bab: (1) Executive Summary — keputusan fork OpenWA + ganti engine Baileys, tabel perbandingan arsitektur OpenWA vs SIMT-WA-Gateway; (2) Arsitektur Deployment — diagram VPS-1 + VPS-2 + alur data presensi→WA; (3) Baileys Engine Adapter — perbandingan whatsapp-web.js vs Baileys (RAM, dependency, multi-session), interface adapter, fitur yang didukung/tidak didukung; (4) Modul Tenant — entity schema, tenant↔session mapping, API endpoints; (5) Modul Notification — alur pengiriman, rate limiting + jitter, retry exponential backoff, template pesan (6 jenis); (6) Laravel Integration — WaGatewayService, ProcessWaNotification job, routes; (7) Konfigurasi OpenWA untuk SIMT — .env, docker compose VPS-2; (8) Task Breakdown Sprint 4 — 10 task dengan estimasi jam; (9) Risiko & Mitigasi — 5 risiko; (10) File Structure SIMT-WA-Gateway — peta direktori lengkap. |
| 20 | `simt-wa-gateway/README.md` | ~200 | Panduan penggunaan gateway. Isi: Fitur untuk SIMT MTs (tabel 7 fitur), arsitektur diagram (Laravel ↔ Gateway), quick start (Docker + Local dev), API endpoints (3 grup: tenant management, WA session, notification), integrasi dengan Laravel (3 langkah konfigurasi + contoh kode), template pesan (6 template), keamanan (5 aspek), perbandingan Baileys vs whatsapp-web.js, Sprint 4 task list. |
| 21 | `simt-wa-gateway/SPRINT4_SUMMARY.md` | ~100 | Ringkasan singkat sprint 4. Isi: file yang dibuat (tabel 21 file), arsitektur diagram, task yang selesai (7), task yang belum (3), setup sandbox untuk testing (4 langkah curl), catatan penting (5 item). |
| 22 | `docs_sim/49_session_memory_sprint4_wa_gateway.md` | ~350 | **Session memory — titik masuk WAJIB sesi berikutnya.** 7 bab: (1) Ringkasan Eksekutif — 5 hal yang diselesaikan; (2) Keputusan Arsitektur — 12 keputusan baru + 8 warisan; (3) Peta Repo & File — struktur 3 repo; (4) Setup Sandbox — instruksi Laravel + Gateway; (5) Status Sprint — tabel S1-S6 + detail S4; (6) Langkah Selanjutnya — prioritas S3→S4→S5; (7) Bug & Pitfall — 3 kritis + 3 sedang + 4 minor. |
| 23 | `docs_sim/50_dev_report_sprint4_wa_gateway.md` | ~600 | **Dev report formal Sprint 4.** 10 bab: (1) Executive Summary; (2) Deliverables — 3 kategori (OpenWA clone, SIMT-WA-Gateway 21 file, dokumen 49 file); (3) Keputusan Teknis — 4 tabel perbandingan (fork vs nol, Baileys vs wwebjs, rate limiter gateway vs Laravel, modul yang dihapus); (4) Arsitektur Detail — 4 diagram (alur data, tenant mapping, rate limiting, retry); (5) API Contract — 11 gateway endpoints + 1 webhook + entity schema SQL; (6) Metrik & Target — DoD S4 + budget impact; (7) Risiko — 6 risiko; (8) File Manifest — daftar lengkap workspace; (9) Langkah Selanjutnya — 3 prioritas; (10) Konfigurasi Laravel — services.php + .env. |

---

## 🔗 RELASI ANTAR DOKUMEN

```
Doc 46 (Session Memory S3)
    │
    ▼
Doc 48 (Session Memory S3 Completion)  ◄── Konteks S3 yang belum selesai
    │
    ├──► Doc 49 (Session Memory S4 — BARU)  ◄── Titik masuk sesi berikutnya
    │       │
    │       └──► INTEGRATION_OPENWA_SIMT.md  ◄── Detail arsitektur
    │               │
    │               ├──► baileys.adapter.ts  ◄── Implementasi engine
    │               ├──► tenant.*.ts         ◄── Modul tenant
    │               ├──► notification.*.ts   ◄── Modul notifikasi
    │               └──► laravel/*.php       ◄── Integrasi Laravel
    │
    └──► Doc 50 (Dev Report S4 — BARU)  ◄── Laporan formal
            │
            └──► SPRINT4_SUMMARY.md  ◄── Ringkasan singkat
                    │
                    └──► README.md  ◄── Panduan penggunaan

Doc 37 (PRD MVP) ──────► Referensi bisnis (scope, budget, killer feature)
Doc 39 (Design MVP) ───► Referensi arsitektur (2-VPS, ERD, API, UI flow)
Doc 40 (Task Breakdown) ► Referensi sprint plan (6 sprint × 2 minggu)
```

---

## 📈 PROGRESS KUMULATIF DOKUMEN PROYEK

| Doc # | File | Dibuat Di | Ukuran |
|---|---|---|---|
| 00 | `00_README_SESI3.md` | Sesi 3 | 1.8 KB |
| 01 | `01_analisis_kelayakan.md` | Sesi 1 | 44 KB |
| 02 | `02_analisis_kebutuhan.md` | Sesi 1 | 71 KB |
| 03 | `03_pemetaan_modul_rbac.md` | Sesi 2 | 79 KB |
| 04 | `04_prd_sim_mts.md` | Sesi 2 | 96 KB |
| 05 | `05_requirements_srs.md` | Sesi 2 | 107 KB |
| 06 | `06_design_system_erd.md` | Sesi 2 | 92 KB |
| 07 | `07_roadmap_tasks_sprint.md` | Sesi 2 | 107 KB |
| 08 | `08_project_charter.md` | Sesi 2 | 51 KB |
| 09 | `09_software_architecture.md` | Sesi 2 | 95 KB |
| 10-20 | `10-20_*.md` | Sesi 2 | ~40 KB total |
| 21-36 | `21-36_*.md` | Sesi 2 | ~80 KB total |
| 37 | `37_prd_mvp_3bulan_5juta.md` | Sesi 3 | 10 KB |
| 38-43 | `38-43_*.md` + `.html` | Sesi 3 | ~60 KB total |
| 44 | `44_sprint1_execution_report.md` | Sesi 4 | 5.5 KB |
| 45 | `45_sprint2_execution_report.md` | Sesi 4 | 6 KB |
| 46 | `46_session_memory_handover_sprint3.md` | Sesi 4 | 10 KB |
| 47 | `47_sprint5_reconstruction_report.md` | Sesi 5 | 7 KB |
| 48 | `48_session_memory_handover_sprint3_completion.md` | Sesi 5 | 13 KB |
| **49** | **`49_session_memory_sprint4_wa_gateway.md`** | **Sesi 6** | **~12 KB** |
| **50** | **`50_dev_report_sprint4_wa_gateway.md`** | **Sesi 6** | **~20 KB** |

---

*Dokumen ini berfungsi sebagai indeks/katalog resmi semua file yang dibuat pada Sesi 6 (13 Juni 2026). Untuk detail konten masing-masing file, lihat file langsung di workspace.*
