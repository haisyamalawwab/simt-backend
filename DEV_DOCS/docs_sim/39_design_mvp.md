# DESIGN DOCUMENT — MVP EDITION (Arsitektur, ERD, API, UI Flow)
## SIMT MTs — MVP 3 Bulan / Rp 5 Juta

**Versi:** 1.0 | **Tanggal:** 12 Juni 2026 | **Status:** FINAL
**Referensi:** Doc 38 (SRS MVP), Doc 06, 21, 24–29

---

## 1. ARSITEKTUR DEPLOYMENT (1× VPS 2GB + 1× VPS 1GB)

```
                          INTERNET
                             │
                    ┌────────┴────────┐
                    │   Cloudflare    │  (DNS + SSL + proxy, FREE)
                    └────────┬────────┘
          ┌──────────────────┼───────────────────┐
          │  *.simt.id       │  app.simt.id      │
          ▼                  ▼                   │
┌─────────────────────────────────────────┐      │
│        VPS-1 (2GB) — PRODUCTION         │      │
│  ┌───────────────────────────────────┐  │      │
│  │ Nginx                             │  │      │
│  │  ├─ Laravel 10 (Blade Admin+API)  │  │      │
│  │  └─ Next.js (Portal Ortu, PM2)    │  │      │
│  ├───────────────────────────────────┤  │      │
│  │ MySQL 8 (single DB multi-tenant)  │  │      │
│  │ Redis (queue + cache + session)   │  │      │
│  │ Supervisor (queue:work notif WA)  │  │      │
│  └───────────────────────────────────┘  │      │
└───────────────────┬─────────────────────┘      │
                    │ HTTP internal (API key)    │
                    ▼                            │
┌─────────────────────────────────────────┐      │
│   VPS-2 (1GB) — WA GATEWAY (Baileys)    │◄─────┘ (dashboard QR scan)
│  Node.js: 1 proses, multi-session       │
│  /session/{tenant}/qr  /send  /status   │
│  Auth state per tenant di disk          │
└─────────────────────────────────────────┘
```

**Keputusan desain kunci:**
1. WA Gateway dipisah ke VPS kecil sendiri → jika Baileys crash/banned, production tidak ikut tumbang.
2. Next.js di-serve dari VPS yang sama (PM2) untuk hemat biaya; SSG+ISR ringan.
3. Redis dipakai 3 fungsi sekaligus (queue/cache/session) — hemat RAM.

---

## 2. ERD MVP (12 Tabel Inti)

```
┌──────────────┐       ┌──────────────────┐      ┌──────────────────┐
│   tenants    │1     *│  tenant_modules  │      │     invoices     │
│──────────────│───────│──────────────────│      │──────────────────│
│ id           │       │ tenant_id FK     │     *│ tenant_id FK     │
│ name         │───────│ module_code      │──────│ period, amount   │
│ domain (uniq)│1      │ active, until    │     1│ status, paid_at  │
│ status       │       └──────────────────┘      └──────────────────┘
└──────┬───────┘
       │1                    SPATIE (teams=true)
       │                ┌─────────────────────────┐
       │*               │ roles(team_id=tenant_id)│
┌──────┴───────┐        │ model_has_roles         │
│    users     │*──────*│ permissions             │
│──────────────│        └─────────────────────────┘
│ id, name     │
│ phone (uniq) │        ┌──────────────┐       ┌──────────────┐
│ password     │       1│ school_years │1     *│   classes    │
│ tenant_id FK │        │──────────────│───────│──────────────│
└──────┬───────┘        │ tenant_id FK │       │ tenant_id FK │
       │1 (wali)        │ name, active │       │ name, grade  │
       │                └──────────────┘       │ teacher_id FK│
       │*                                      └──────┬───────┘
┌──────┴────────────┐                                 │1
│  guardian_student │*        ┌──────────────┐        │*
│  (pivot)          │─────────│   students   │────────┘
│  user_id FK       │        *│──────────────│  (class_student pivot
│  student_id FK    │         │ id, tenant_id│   per school_year)
│  relation         │         │ nisn, nis    │
└───────────────────┘         │ name, gender │
                              │ status       │
                              └──────┬───────┘
                  ┌──────────────────┼──────────────────────┐
                  │*                 │*                     │*
        ┌─────────┴──────┐  ┌────────┴─────────┐  ┌─────────┴────────┐
        │  attendances   │  │     bills        │  │   payments       │
        │────────────────│  │──────────────────│  │──────────────────│
        │ tenant_id FK   │  │ tenant_id FK     │ 1│ tenant_id FK     │
        │ student_id FK  │  │ student_id FK    │──│ bill_id FK       │
        │ date           │  │ period (YYYY-MM) │ *│ amount, method   │
        │ status H/A/I/S/T│ │ amount, paid_amt │  │ receipt_no (uniq)│
        │ marked_by FK   │  │ status           │  │ recorded_by FK   │
        │ UNIQ(student,  │  └──────────────────┘  └──────────────────┘
        │      date)     │
        └────────┬───────┘     ┌────────────────────┐
                 │1           *│  wa_notifications  │
                 └─────────────│────────────────────│
                               │ tenant_id, to_phone│
                               │ type, payload      │
                               │ status, attempts   │
                               │ sent_at            │
                               └────────────────────┘
```

**Aturan wajib:** SEMUA tabel domain ber-`tenant_id` (indexed, composite index dengan kolom query utama, mis. `(tenant_id, date)` pada attendances) + Global Scope `BelongsToTenant` trait.

---

## 3. DESAIN API (Ringkas — kontrak penuh ikut pola Doc 17/29)

**Base:** `https://api.simt.id/v1` | Auth: Sanctum Bearer | Tenant: header `X-Tenant-Domain`

| Method & Path | Deskripsi | Role |
| :--- | :--- | :--- |
| `POST /auth/login` | Login (phone+password) → token | semua |
| `GET /me/children` | Daftar anak milik wali | wali |
| `GET /students/{id}/attendances?month=` | Riwayat presensi anak | wali |
| `GET /students/{id}/bills` | Tagihan & pembayaran anak | wali |
| `POST /classes/{id}/attendances` | Simpan presensi 1 kelas (bulk) | guru |
| `GET /classes/{id}/attendances?date=` | Lihat/edit presensi | guru |
| `POST /students/import` | Upload Excel siswa | tu |
| `POST /bills/generate` | Generate tagihan bulanan | bendahara |
| `POST /bills/{id}/payments` | Catat pembayaran → kwitansi | bendahara |
| `POST /wa/remind-arrears` | Kirim WA pengingat tunggakan | bendahara |
| `GET /wa/session/status` · `GET /wa/session/qr` | Status & QR WA tenant | admin tenant |

**Error envelope:** `{ "success": false, "code": "MODULE_INACTIVE", "message": "..." }` — kode khusus: `MODULE_INACTIVE` (403), `TENANT_SUSPENDED` (402), `TENANT_NOT_FOUND` (400).

**WA Gateway internal API (VPS-2, dilindungi static API key):**
`POST /session/{tenantId}/start` · `GET /session/{tenantId}/qr` · `POST /send {tenantId, to, message}` · `GET /session/{tenantId}/status`

---

## 4. UI / SCREEN FLOW

### 4.1 Admin & Guru (Laravel Blade + Tailwind, mobile-first)

```
Login ─► Dashboard (per role)
          ├─ [Guru]      Pilih Kelas ─► Grid Presensi (default Hadir,
          │                              tap siswa = ganti status) ─► SIMPAN
          │                              └─► toast "Notif WA diantrikan ✓"
          ├─ [TU]        Siswa ─► (List / Tambah / Import Excel wizard 3 step:
          │                       Upload → Preview+Error → Commit)
          ├─ [Bendahara] SPP ─► (Generate Tagihan / Catat Bayar ─► PDF kwitansi
          │                      / Rekap Tunggakan ─► [Kirim WA Pengingat])
          ├─ [Kepala]    Dashboard ringkas (kehadiran hari ini, kas bulan ini)
          └─ [Admin]     Pengaturan ─► WA Connect (QR live status),
                                       Template Pesan, Users & Roles
```

### 4.2 Portal Ortu (Next.js PWA)

```
Login (No. WA + password)
  ─► Home: [Selector Anak ▾] + Kartu status hari ini ("Hadir, 06:52")
       ├─ Tab Presensi : kalender bulanan (hijau=H, merah=A, kuning=I/S)
       ├─ Tab Tagihan  : kartu tunggakan + riwayat bayar + tombol unduh kwitansi
       └─ Tab Lainnya  : Nilai (Segera Hadir), Tahfiz (Segera Hadir), Logout
```

### 4.3 Super-Admin Vendor (Blade, subdomain `panel.simt.id`)

```
Tenants ─► Tambah Tenant (nama, domain, modul aktif, paket)
        ─► Detail: status invoice (prepaid semester), suspend/aktifkan,
                   toggle modul (Plug & Play), statistik pemakaian WA
```

---

## 5. SEQUENCE: PRESENSI → NOTIFIKASI WA (alur kritis)

```
Guru          Laravel              Redis Queue        WA Gateway       Wali
 │ POST bulk    │                      │                  │              │
 ├─────────────►│ validate+upsert      │                  │              │
 │   201 ✓      │ dispatch NotifJob ──►│                  │              │
 │◄─────────────┤ (1 job / siswa       │                  │              │
 │              │  non-Hadir + Hadir*) │  worker pop      │              │
 │              │                      ├─────────────────►│ POST /send   │
 │              │                      │   rate-limit     ├─────────────►│ 📱
 │              │                      │   3–8s jitter    │ ack          │
 │              │   update status ◄────┴──────────────────┤              │
 │              │   wa_notifications: sent / failed(retry≤3, backoff)    │
```
*Notif "Hadir" dapat dimatikan per tenant untuk hemat kuota pesan & risiko ban.

---

## 6. STANDAR KODE (ringkas, detail ikut Doc 18)

- Module skeleton nwidart: `Modules/{Core,Student,Attendance,Finance}` — masing-masing punya `Routes/`, `Http/`, `Models/`, `Database/`.
- Trait `BelongsToTenant`: global scope + auto-fill `tenant_id` saat create.
- Test wajib per modul: 1 test isolasi tenant + 1 test happy-path.
- Konvensi commit: `feat(attendance): ...`, 1 PR per fitur kecil (walau solo — disiplin riwayat).
