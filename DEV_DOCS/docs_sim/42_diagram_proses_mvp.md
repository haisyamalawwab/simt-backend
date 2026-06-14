# DIAGRAM PROSES — MVP SIMT MTs
## Business Process & System Flow Diagrams (Mermaid)

**Versi:** 1.0 | **Tanggal:** 12 Juni 2026 | **Doc:** 42
**Catatan:** Render di GitHub/VS Code (Mermaid). Versi visual interaktif: lihat `41_visualisasi_konsep_mvp.html`.

---

## 1. Proses Bisnis End-to-End: Akuisisi → Go-Live → Cash-In

```mermaid
flowchart LR
    A[📣 Pitching Kepsek\nDemo Notif WA live] --> B{Deal?}
    B -- Tidak --> A2[Follow-up\nmaks. 2x lalu drop]
    B -- Ya --> C[✍️ TTD MoU Doc 36\n+ materai]
    C --> D[🧾 Invoice Prepaid:\nSetup 1jt + Sewa semester 1,2jt]
    D --> E{Dibayar?}
    E -- Belum --> E2[⛔ TIDAK go-live\nNo free trial penuh]
    E -- Lunas --> F[📥 Import Excel siswa\n+ generate akun wali]
    F --> G[📱 TU scan QR WA\nself-hosted Baileys]
    G --> H[🎓 Training TU & Guru\njasa sesuai tarif Doc 36]
    H --> I[🚀 GO-LIVE]
    I --> J[💰 Bulan 1: BEP\nsetelah 5 sekolah]
    J --> K[🔁 Renewal semester\n+ upsell add-on Fase 2]
```

---

## 2. Proses Harian Inti: Presensi → Notifikasi WA (Killer Feature)

```mermaid
sequenceDiagram
    autonumber
    actor G as Guru (HP)
    participant L as Laravel (VPS-1)
    participant Q as Redis Queue
    participant W as WA Gateway Baileys (VPS-2)
    actor O as Orang Tua

    G->>L: POST /classes/{id}/attendances (bulk, default Hadir)
    L->>L: Validasi + upsert UNIQ(student,date) + audit marked_by
    L->>Q: Dispatch SendWaNotification (per siswa)
    L-->>G: 201 ✓ "Notifikasi diantrikan"
    loop Worker (rate-limit 10/menit, jitter 3–8 dtk)
        Q->>W: POST /send {tenantId, to, message} (API key)
        alt Sesi WA Connected
            W-->>O: 📱 "Ananda Fulan HADIR pukul 06.52"
            W-->>Q: ack sent
            Q->>L: update wa_notifications = sent
        else Gagal / Disconnected
            Q->>Q: retry ≤3x (backoff)
            Q->>L: status = failed → tampil di dashboard admin
        end
    end
```

---

## 3. Proses Keuangan: SPP Manual + Kwitansi + Pengingat Tunggakan

```mermaid
flowchart TD
    A[Bendahara: Generate tagihan\nbulanan massal per tingkat] --> B[(bills\nstatus: unpaid)]
    C[Ortu bayar tunai/transfer\nke rekening SEKOLAH] --> D[TU catat pembayaran\nparsial diperbolehkan]
    D --> E{Lunas?}
    E -- Ya --> F[bills.status = paid]
    E -- Parsial --> G[bills.status = partial]
    D --> H[🧾 Kwitansi PDF otomatis\nKW/tenant/tahun/seq]
    H --> I[Terlihat di Portal Ortu\n+ unduh PDF]
    B --> J[Rekap tunggakan per kelas]
    J --> K{TU trigger pengingat?}
    K -- Ya --> L[📨 WA pengingat sopan\nvia queue + rate-limit]
    K -- Tidak --> J
    style C fill:#064e3b,color:#d1fae5
    style H fill:#451a03,color:#fef3c7
```
> 💡 Uang SPP **tidak pernah** lewat rekening vendor (mitigasi risiko hukum, ref. Doc 30/36). Payment Gateway BYOA = add-on Fase 2.

---

## 4. Proses Onboarding Tenant Baru (Target: aktif < 1 hari kerja)

```mermaid
flowchart LR
    subgraph Vendor [Panel Super-Admin Vendor]
        A[Create Tenant\nnama + subdomain] --> B[Set modul aktif\nPlug & Play toggle]
        B --> C[Catat invoice prepaid\nstatus: paid]
    end
    subgraph Sekolah [Admin Sekolah]
        D[Login pertama\nganti password] --> E[Import Excel siswa\nwizard 3 langkah]
        E --> F[Generate akun wali\nkredensial via WA]
        F --> G[Scan QR WhatsApp\ndi halaman WA Connect]
    end
    C --> D
    G --> H{Status sesi:\nConnected?}
    H -- Ya --> I[✅ Tenant LIVE]
    H -- Tidak --> G
```

---

## 5. State Diagram: Siklus Hidup Tenant (Penegakan Cash-in-Advance)

```mermaid
stateDiagram-v2
    [*] --> Prospect : Pitching
    Prospect --> Contracted : MoU ditandatangani
    Contracted --> Active : Invoice prepaid LUNAS
    Active --> GraceRead : Invoice renewal overdue 1–14 hari\n(banner peringatan)
    GraceRead --> Active : Bayar
    GraceRead --> Suspended : Overdue > 14 hari\n(read-only)
    Suspended --> Active : Pelunasan
    Suspended --> Terminated : > 60 hari / putus kontrak
    Terminated --> [*] : Export Excel diserahkan\n(Anti-Hostage Data, Doc 36)\nData dihapus setelah 90 hari
```

---

## 6. Proses Isolasi Multi-Tenant per Request (Keamanan Inti)

```mermaid
flowchart TD
    A[Request masuk] --> B{Sumber?}
    B -- "Blade (subdomain mts1.simt.id)" --> C[Middleware IdentifyTenant\nresolve dari subdomain]
    B -- "API (Next.js)" --> D[Header X-Tenant-Domain\n+ Bearer token Sanctum]
    C --> E{Tenant ditemukan\n& status active?}
    D --> E
    E -- Tidak --> F[400 TENANT_NOT_FOUND /\n402 TENANT_SUSPENDED]
    E -- Ya --> G[Set tenant context\n+ Spatie team_id = tenant_id]
    G --> H{Modul endpoint\naktif utk tenant?}
    H -- Tidak --> I[403 MODULE_INACTIVE]
    H -- Ya --> J{Role punya permission?\nSpatie Teams}
    J -- Tidak --> K[403 FORBIDDEN]
    J -- Ya --> L[Eksekusi query\nGlobal Scope BelongsToTenant\nWHERE tenant_id = ? otomatis]
    L --> M[Response]
    style L fill:#064e3b,color:#d1fae5
```

---

## 7. Proses Mitigasi Insiden WA Banned (Runbook Ringkas)

```mermaid
flowchart TD
    A[🔔 Alert: sesi WA tenant X\ndisconnected/banned] --> B{Penyebab?}
    B -- Logout biasa --> C[Minta TU re-scan QR\n< 5 menit selesai]
    B -- Banned Meta --> D[Aktifkan klausul liabilitas\nMoU Doc 36 pasal WA]
    D --> E[Sekolah siapkan nomor WA baru\nnomor milik sekolah, bukan vendor]
    E --> F[Reset auth state tenant\ndi WA Gateway]
    F --> G[Re-scan QR + uji kirim 1 pesan]
    G --> H[Review rate-limit:\nturunkan ke 6 pesan/menit\nmatikan notif 'Hadir' sementara]
    H --> I[Catat insiden + lapor sekolah\nrespon < 4 jam kerja]
```

---

## 8. Gantt 90 Hari (Sprint 1–6)

```mermaid
gantt
    title Roadmap Eksekusi MVP (Juni–September 2026)
    dateFormat  YYYY-MM-DD
    axisFormat  %d/%m
    section Bulan 1 — Foundation
    S1 Core+Tenancy+RBAC      :s1, 2026-06-15, 14d
    S2 Kesiswaan+Import Excel :s2, after s1, 14d
    section Bulan 2 — Killer Feature
    S3 Presensi UI+Rekap      :s3, after s2, 14d
    Mulai Pitching (paralel)  :crit, p1, after s2, 28d
    S4 WA Gateway Baileys     :crit, s4, after s3, 14d
    section Bulan 3 — Monetize
    S5 SPP+Portal Ortu        :s5, after s4, 14d
    S6 UAT+Go-Live+Onboarding :crit, s6, after s5, 14d
    Invoice Cair → BEP 💰     :milestone, after s6, 0d
```
