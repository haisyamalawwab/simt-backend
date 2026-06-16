---
name: nwidart-module-management
description: >
  Panduan wajib saat menambah, mengaktifkan, atau menonaktifkan modul nwidart/laravel-modules
  di project SIMT Backend. Berisi pelajaran dari bug produksi dan checklist aman.
  Gunakan skill ini setiap kali ada operasi yang berkaitan dengan Modules/, modules_statuses.json,
  atau composer.json autoload di project ini.
---

# Nwidart Module Management — SIMT Backend

## 📜 Protokol Dokumentasi & Riwayat Pembaruan (Penting untuk Agentic AI)

> 🔒 **ATURAN MUTLAK:** Jangan pernah menghapus atau menimpa (overwrite) baris-baris sejarah pada dokumen laporan pengembangan (`dev report`) atau perencanaan (`plan docs`) sebelumnya. 
> 
> Setiap kali ada pembaruan, revisi fitur, atau perbaikan bug:
> 1. **Selalu pertahankan konten asli** dokumen/bagian sebelumnya tanpa modifikasi destruktif.
> 2. **Tambahkan log baru (append)** di bawahnya (misalnya, buat sub-bagian *Catatan Revisi / Revision History*).
> 
> Protokol ini sangat krusial bagi AI Agent lainnya agar dapat membangun konteks secara utuh, melacak keputusan arsitektur di masa lalu, dan mencegah hilangnya alasan teknis (*decision rationale*) di balik kode yang diimplementasikan.

---

## ⚠️ Pelajaran Penting (dari Bug Produksi 2026-06-14)

> **Setiap kali menambah modul nwidart baru dan mengaktifkannya di `modules_statuses.json`,
> WAJIB jalankan `composer dump-autoload` agar namespace-nya masuk ke classmap PHP.**

Jika langkah ini dilewati, Laravel akan **crash saat startup** dengan error:
```
Class "Modules\NamaModul\Providers\NamaModulServiceProvider" not found
```
Error ini terjadi karena nwidart membaca `modules_statuses.json` dan mencoba
me-load provider modul — tetapi PHP tidak tahu letak file-nya karena classmap
belum diperbarui.

---

## Checklist Wajib Saat Menambah Modul Baru

### 1. Buat modul via artisan
```bash
php83 artisan module:make NamaModul
```

### 2. Daftarkan namespace di root `composer.json`
Tambahkan ke bagian `autoload.psr-4`:
```json
"Modules\\NamaModul\\": "Modules/NamaModul/app/",
"Modules\\NamaModul\\Database\\Seeders\\": "Modules/NamaModul/database/seeders/"
```

### 3. Aktifkan modul di `modules_statuses.json`
```json
{
    "Core": true,
    "Student": true,
    "Attendance": true,
    "Finance": true,
    "Notification": true,
    "NamaModul": true
}
```

### 4. ⚡ WAJIB — Regenerate autoload classmap
```bash
# Matikan artisan serve dulu jika sedang berjalan
# (agar tidak mengunci file dan menyebabkan composer hang)
php83 D:\laragon\bin\composer\composer.phar dump-autoload --optimize --no-scripts
```

### 5. Restart server
```bash
php83 artisan serve --port=8000
```

---

## Troubleshooting: `composer dump-autoload` Menggantung

Jika `composer dump-autoload` tidak selesai dan menggantung:

**Penyebab:** `artisan serve` masih berjalan dan mengunci proses PHP,
sehingga script `post-autoload-dump` (`php artisan package:discover`) tidak bisa jalan.

**Solusi:**
```powershell
# 1. Kill semua proses PHP yang berjalan
taskkill /f /im php.exe

# 2. Jalankan dump-autoload TANPA scripts
php83 D:\laragon\bin\composer\composer.phar dump-autoload --optimize --no-scripts

# 3. Restart server
php83 artisan serve --port=8000
```

---

## Modul yang Terdaftar di Project Ini

| Modul | Path | Status Default |
|---|---|---|
| `Core` | `Modules/Core/app/` | ✅ Selalu aktif |
| `Student` | `Modules/Student/app/` | ✅ Aktif |
| `Attendance` | `Modules/Attendance/app/` | ✅ Aktif |
| `Finance` | `Modules/Finance/app/` | ✅ Aktif |
| `Notification` | `Modules/Notification/app/` | ✅ Aktif |
| `Akademik` | `Modules/Akademik/app/` | ✅ Aktif |

---

## Catatan Arsitektur

- **Modul Core** = mandatory, tidak pernah dinonaktifkan via `modules_statuses.json`
- **Modul lainnya** = Plug & Play, bisa diaktifkan/nonaktifkan per tenant via tabel `tenant_modules`
- Guard autoload ini berlaku untuk **semua environment** (local Windows, WSL, Linux server)

---

## Riwayat Pembaruan & Hardening Modul

### Pembaruan Modul Akademik (16 Juni 2026)
1. **Penyelesaian Modul:**
   - Modul `Akademik` diselesaikan dengan fitur manajemen Rombel, Mapel, Input Nilai Massal, dan Rapor PDF (menggunakan Barryvdh/DomPDF).
   - Diaktifkan secara global di `modules_statuses.json` dan didaftarkan pada psr-4 autoload di root `composer.json`.
2. **Optimasi Platform Composer:**
   - Dependensi Laravel 11 memerlukan PHP >= 8.3.0, sedangkan CLI Windows default menggunakan PHP 8.2.30.
   - Solusi: Menjalankan composer secara eksplisit menggunakan binary PHP 8.3:
     ```bash
     php83 D:\composer\composer.phar dump-autoload --optimize
     ```
3. **REST API Driven & Security Hardening:**
   - Menambahkan `AkademikApiController` untuk rute API `/api/v1/students/{student}/grades` dan `/api/v1/students/{student}/rapor` bagi Portal Ortu (Next.js).
   - Menerapkan pembatasan role `wali` hanya dapat mengakses data akademik siswa miliknya sendiri (ownership verification).
   - Memperkuat validasi lintas tenant pada middleware `IdentifyTenant` & `SetTenantFromUser` (memeriksa kesesuaian user session `tenant_id` dengan subdomain request context).
