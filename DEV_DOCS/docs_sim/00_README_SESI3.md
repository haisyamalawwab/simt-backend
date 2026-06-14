# SESI 3 — FASE EKSEKUSI MVP (12 Juni 2026)
## SIMT MTs — Paket Dokumen MVP "3 Bulan / Modal Rp 5 Juta"

Folder ini melanjutkan Sesi 2 (Doc 20–36). Fase Pre-Implementation resmi DITUTUP;
dokumen di sini adalah acuan tunggal **eksekusi 90 hari**.

| Doc | File | Isi | Audiens |
| :-: | :--- | :--- | :--- |
| 37 | `37_prd_mvp_3bulan_5juta.md` | PRD MVP: scope 4 modul, budget plan Rp 5 jt, success metrics, risiko | Semua |
| 38 | `38_requirements_mvp.md` | SRS MVP: FR/NFR prioritas P0–P2, constraints, 4 acceptance gate | Developer |
| 39 | `39_design_mvp.md` | Design: arsitektur 2-VPS, ERD 12 tabel, API, UI flow, sequence WA | Developer |
| 40 | `40_task_breakdown_sprint_mvp.md` | Task: 6 sprint × 2 minggu, 278 jam, gate per sprint, exit criteria | Developer |
| 41 | `41_visualisasi_konsep_mvp.html` | Infografis konsep (B2B2C, arsitektur, budget, BEP, moat) — buka di browser | Investor/Client |
| 42 | `42_diagram_proses_mvp.md` | 8 diagram proses Mermaid (bisnis & sistem) + Gantt 90 hari | Dev/Client |
| 43 | `43_visualisasi_peta_dokumen_drive.html` | Peta visual seluruh 43 dokumen folder Drive + relasi & audiens | Semua |

### Keputusan kunci yang DIWARISI dari Sesi 2 (tidak dinegosiasi ulang)
- Hybrid Rendering: Blade (Admin/Guru) + Next.js (Portal Ortu)
- Single-Database Multi-Tenant (`tenant_id` + Global Scope)
- Plug & Play `nwidart/laravel-modules` · RBAC Spatie Teams
- B2B2C Rp 2.000/siswa/bln · minimum Rp 200rb/bln · prepaid 1 semester
- Zero-Cost WA (Baileys, sekolah scan QR sendiri) · MoU Doc 36

### Langkah berikutnya (Sesi 4)
1. AI-Assisted Coding: skeleton repo Laravel + Next.js (mengikuti Sprint 1, Doc 40)
2. Migrations nyata: `users`, `tenants`, `students`, `attendances`
3. Setup server Ubuntu VPS (ditunda hingga ada calon pilot serius — aturan kas Doc 37 §3)
