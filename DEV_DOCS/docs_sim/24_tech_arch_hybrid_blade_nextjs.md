# Arsitektur Hibrida SaaS: Laravel API & Web Berdampingan
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 10:45 WIB  
**Disusun Oleh:** IT Architect  

---

## 1. Konsep Arsitektur Hibrida (Web + Headless API)

Dengan perubahan *Tech Stack*, sistem SIMT MTs (MVP) kini berperan ganda di dalam satu *codebase* Laravel (Monolithic):
1. **Sebagai Aplikasi Web Tradisional (SSR):** Melayani rute web (`routes/web.php`) yang mengembalikan tampilan HTML menggunakan **Laravel Blade Partials**. Diperuntukkan bagi **Admin, Staf TU, Kepala Sekolah, dan Guru**.
2. **Sebagai Headless API:** Melayani rute API (`routes/api.php`) yang mengembalikan JSON. Diperuntukkan bagi **Frontend Next.js/React** yang diakses oleh **Orang Tua/Wali Murid**.

### 1.1. Resolusi Tenant di Dua Dunia (Multi-Tenancy)
Sistem *Single-Database Multi-Tenant* harus tahu sekolah mana yang sedang diakses, dengan cara berbeda:
- **Dunia Blade (Admin):** Resolusi didapat dari URL/Subdomain (`https://admin.mts-assalam.simt.id`) atau saat proses *Login Session*. Middleware web akan menyuntikkan `tenant_id` ke Global Scope.
- **Dunia Next.js (Ortu):** Resolusi didapat dari HTTP Header `X-Tenant-Domain` yang dikirim Next.js saat meminta data via API.

### 1.2. Pemisahan Autentikasi
- **Admin/Guru (Blade):** Menggunakan bawaan Laravel Session Auth & Cookies. Akses menggunakan form login standar Laravel.
- **Orang Tua (Next.js):** Menggunakan **Laravel Sanctum (Token-Based)**. Saat orang tua login via Next.js, API mengembalikan *Bearer Token* yang akan disimpan di *browser/http-only cookie* Next.js.

---

## 2. Struktur Modul "Plug & Play" (nwidart/laravel-modules)

Setiap Modul (misal: `Academic`, `Finance`) harus menyediakan dua jenis rute dan antarmuka.

### Contoh Struktur Modul Finance:
```text
/Modules/Finance
 ├── /Entities (Model)
 ├── /Http
 │   ├── /Controllers
 │   │   ├── /Web      (Controller untuk Blade Admin)
 │   │   └── /API      (Controller khusus untuk respon JSON ke Next.js)
 ├── /Resources
 │   └── /views        (File .blade.php khusus Finance)
 ├── /Routes
 │   ├── web.php       (Rute halaman Admin TU)
 │   └── api.php       (Rute API Tagihan untuk Orang Tua)
```

### 2.1. Keuntungan Pendekatan Ini:
Jika sekolah MTs B tidak membeli Modul *Finance*, kita cukup mematikan flag modul tersebut. Secara otomatis, Rute Admin TU (Blade) maupun endpoint API untuk Orang Tua di Next.js akan memblokir akses dan mengembalikan respon *403 Forbidden* secara serentak.