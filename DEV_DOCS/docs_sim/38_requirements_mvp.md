# SOFTWARE REQUIREMENTS SPECIFICATION (SRS) — MVP EDITION
## SIMT MTs — MVP 3 Bulan / Rp 5 Juta

**Versi:** 1.0 | **Tanggal:** 12 Juni 2026 | **Status:** FINAL
**Referensi:** Doc 37 (PRD MVP), Doc 05 (SRS Full), Doc 21–22, 27–29

---

## 1. FUNCTIONAL REQUIREMENTS

Notasi prioritas: **P0** = wajib MVP, **P1** = jika waktu tersedia, **P2** = post-MVP.

### FR-CORE — Platform & Multi-Tenancy

| ID | Requirement | Prioritas |
| :-- | :--- | :-: |
| FR-C01 | Sistem menyimpan semua tenant dalam 1 database; setiap tabel domain memiliki kolom `tenant_id` (indexed) dan dilindungi Laravel Global Scope. | P0 |
| FR-C02 | Identifikasi tenant via subdomain (`mts1.simt.id`) untuk web Blade dan header `X-Tenant-Domain` untuk API Next.js. | P0 |
| FR-C03 | RBAC menggunakan Spatie Permission mode `teams=true`; role bersifat per-tenant (`team_id = tenant_id`). Role bawaan: `superadmin`, `kepala_madrasah`, `tu`, `bendahara`, `guru`, `wali`. | P0 |
| FR-C04 | Satu user dapat memiliki role berbeda di tenant berbeda (kasus "Guru Ahmad"). | P0 |
| FR-C05 | Modul dikemas dengan `nwidart/laravel-modules`; tabel `tenant_modules` menentukan modul aktif. Modul nonaktif → menu Blade tersembunyi + endpoint API balas `403 MODULE_INACTIVE`. | P0 |
| FR-C06 | Panel Super-Admin Vendor: CRUD tenant, aktivasi/suspend, set modul, catat invoice & status pembayaran (prepaid semester). | P0 |
| FR-C07 | Tenant dengan invoice `overdue > 14 hari` otomatis berstatus `suspended` (read-only, banner peringatan). | P1 |
| FR-C08 | Audit log aksi sensitif (login, ubah pembayaran, hapus siswa). | P1 |
| SA-01 | Registrasi / Onboarding Tenant Baru: Membuat tenant baru, menginisialisasi modul default (Core, Student, Attendance, Finance), dan membuat akun administrator default tenant (`kepala_madrasah`) secara otomatis. | P0 |
| SA-02 | Manajemen Subskripsi & Modul: Mengubah status tenant (active, suspended, grace_read, dll) dan mengaktifkan/menonaktifkan modul per-tenant. | P0 |
| SA-03 | Dashboard Pemantauan Global (Lintas Tenant): Halaman dashboard khusus superadmin vendor untuk memantau total tenant, total pengguna, dan status operasional lintas tenant. | P0 |


### FR-SIS — Kesiswaan (Master Data)

| ID | Requirement | Prioritas |
| :-- | :--- | :-: |
| FR-S01 | CRUD Tahun Ajaran, Kelas (nama, tingkat, wali kelas), Siswa (NISN, NIS lokal, nama, JK, tgl lahir, status). | P0 |
| FR-S02 | Import siswa via Excel (template baku); validasi per baris, laporan error per baris, tidak ada partial-commit per file. | P0 |
| FR-S03 | Setiap siswa terhubung minimal 1 Wali; 1 Wali dapat memiliki >1 anak (lintas kelas, dalam tenant yang sama). | P0 |
| FR-S04 | Generate akun Wali massal: username = no. WA, password acak dikirim via WA. | P0 |
| FR-S05 | Promosi/mutasi kelas massal antar tahun ajaran. | P1 |

### FR-PRS — Presensi & Notifikasi WA (Killer Feature)

| ID | Requirement | Prioritas |
| :-- | :--- | :-: |
| FR-P01 | Guru menginput presensi per kelas per tanggal; default semua `Hadir`, guru hanya mengubah `Alpa/Izin/Sakit/Terlambat`. Target ≤60 detik/40 siswa. | P0 |
| FR-P02 | Presensi tersimpan unik per (siswa, tanggal); edit ulang menimpa dengan audit trail. | P0 |
| FR-P03 | WA Gateway self-hosted (Baileys, Node.js) per tenant: admin scan QR di dashboard; status sesi (Connected/Disconnected) terlihat real-time. | P0 |
| FR-P04 | Saat presensi disimpan, job notifikasi masuk Laravel Queue → dikirim ke WA Gateway → wali menerima pesan sesuai template status. | P0 |
| FR-P05 | Anti-ban: rate limit ≤10 pesan/menit/nomor, jeda acak 3–8 detik, retry maks. 3× dengan backoff; gagal permanen tercatat di log dashboard. | P0 |
| FR-P06 | Rekap presensi per siswa/kelas/bulan + export Excel. | P0 |
| FR-P07 | Pengaturan template pesan per tenant (variabel: `{nama}`, `{kelas}`, `{status}`, `{jam}`). | P1 |

### FR-KEU — Keuangan-Lite (SPP)

| ID | Requirement | Prioritas |
| :-- | :--- | :-: |
| FR-K01 | Setup komponen tagihan per tahun ajaran (SPP bulanan, nominal per tingkat/kelas); generate tagihan bulanan massal. | P0 |
| FR-K02 | TU mencatat pembayaran manual (tanggal, jumlah, metode tunai/transfer); pembayaran parsial didukung. | P0 |
| FR-K03 | Kwitansi PDF bernomor otomatis (`KW/{tenant}/{tahun}/{seq}`) setelah pembayaran dicatat. | P0 |
| FR-K04 | Rekap tunggakan per siswa/kelas + pengiriman WA pengingat (manual trigger oleh TU, bukan otomatis harian). | P0 |
| FR-K05 | Dashboard kas: total terkumpul vs tunggakan bulan berjalan. | P1 |

### FR-POR — Portal Orang Tua (Next.js PWA)

| ID | Requirement | Prioritas |
| :-- | :--- | :-: |
| FR-O01 | Login wali via no. WA + password (Sanctum token); switch antar anak jika >1. | P0 |
| FR-O02 | Halaman riwayat presensi anak (kalender bulanan berwarna). | P0 |
| FR-O03 | Halaman tagihan & riwayat pembayaran SPP. | P0 |
| FR-O04 | PWA installable (manifest + service worker, cache statis). | P1 |
| FR-O05 | Placeholder menu "Nilai" & "Tahfiz" dengan label "Segera Hadir" (upsell visual). | P2 |

---

## 2. NON-FUNCTIONAL REQUIREMENTS

| ID | Kategori | Requirement |
| :-- | :--- | :--- |
| NFR-01 | Performance | P95 < 500 ms untuk halaman CRUD pada VPS 2GB, 5 tenant, 500 siswa, 20 user concurrent. |
| NFR-02 | Performance | Antrian WA mampu memproses 500 notifikasi < 30 menit (dengan rate-limit anti-ban). |
| NFR-03 | Availability | Uptime ≥ 99%/bulan; maintenance window Minggu 00:00–03:00 WIB. |
| NFR-04 | Security | Isolasi tenant diuji otomatis (test cross-tenant access = 0 kebocoran); password bcrypt; Sanctum token expiry 30 hari. |
| NFR-05 | Security | HTTPS wajib (Let's Encrypt); rate limit login 5×/menit; header keamanan standar. |
| NFR-06 | Data | Backup DB otomatis harian (mysqldump → object storage gratis/Google Drive), retensi 14 hari; restore drill 1× sebelum go-live. |
| NFR-07 | Data | Export Excel seluruh data tenant tersedia on-demand (klausul Anti-Hostage Data, Doc 36). |
| NFR-08 | Usability | Admin Blade responsive (guru absen dari HP); Bahasa Indonesia; istilah madrasah (Wali Kelas, TU, dst.). |
| NFR-09 | Maintainability | 1 repo backend (Laravel modular) + 1 repo portal (Next.js); CI minimal: lint + test isolasi tenant. |
| NFR-10 | Cost | Total infra ≤ Rp 450.000/bulan (VPS 2GB + 1GB). Tidak ada layanan berbayar lain. |

---

## 3. CONSTRAINTS & ASSUMPTIONS

**Constraints:** budget Rp 5jt hard cap; 1 developer; 12 minggu kalender; stack terkunci (Laravel 10 + MySQL + Redis + Blade, Next.js 14, Baileys); tanpa WA API resmi; tanpa payment gateway.

**Assumptions:** sekolah punya ≥1 HP Android untuk sesi WA; data siswa tersedia dalam Excel; sekolah membayar prepaid 1 semester sebelum go-live (Doc 32); koneksi internet sekolah memadai untuk web ringan.

---

## 4. ACCEPTANCE CRITERIA RINGKAS (Gate UAT)

1. Skenario E2E: Import 100 siswa → guru absen → wali terima WA < 5 menit → TU catat SPP → kwitansi PDF → wali lihat di portal. **Lolos tanpa error.**
2. Test isolasi: user tenant A tidak dapat membaca/menulis data tenant B via UI maupun API (manipulasi ID/header). **0 kebocoran.**
3. Matikan modul Keuangan di tenant demo → menu hilang & API 403. **Plug & Play terbukti.**
4. Restore backup ke server kosong < 60 menit. **DR terbukti.**
