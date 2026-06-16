# Walkthrough: Integrasi PortalOrtu & REST API Backend (Termasuk Offline Sync Revision)

**Tanggal:** 16 Juni 2026  
**Status:** Completed (Production Ready)  
**Nomor Dokumen:** 96_WALKTHROUGH_INTEGRASI_PORTALORTU_DAN_REST_API_BACKEND_2026-06-16  

Laporan ini mendokumentasikan penyelesaian integrasi REST API antara parent/student portal (`simt-portalortu`) dengan Laravel backend (`simt-backend`), serta penyesuaian tipe data primary key untuk mendukung sinkronisasi database offline (*local-first*).

---

## 🛠️ Perubahan yang Dilakukan

### 1. Migrasi & Skema Database (String Primary Keys)
Untuk mendukung sinkronisasi offline di mana ID di-generate secara lokal di SQLite PortalOrtu (tipe CUID/string), tabel-tabel baru pada backend didefinisikan menggunakan string primary key sepanjang 50 karakter pada [2026_06_16_000002_create_portal_ortu_tables.php](file:///d:/laragon/www/simt-backend/database/migrations/2026_06_16_000002_create_portal_ortu_tables.php):
*   **schedules**: `id` (string primary key), `tenant_id`, `class_id`, `subject_id`, `day_of_week`, `start_period`, `end_period`, `teacher_id`.
*   **student_violations**: `id` (string primary key), `tenant_id`, `student_id`, `date`, `category`, `description`, `points`, `action`, `recorded_by`.
*   **student_achievements**: `id` (string primary key), `tenant_id`, `student_id`, `date`, `title`, `category`, `level`, `ranking`, `description`, `certificate`.
*   **tahfiz_records**: `id` (string primary key), `tenant_id`, `student_id`, `date`, `surah`, `ayah_start`, `ayah_end`, `type`, `score`, `fluency`, `note`, `recorded_by`.
*   **grade_details**: `id` (string primary key), `tenant_id`, `student_id`, `subject_id`, `category`, `title`, `score`, `weight`, `date`, `note`.
*   **students (Alter)**: Penambahan kolom login portal dan informasi orang tua (`photo`, `father_name`, `father_phone`, `mother_name`, `mother_phone`, `parent_email`, `student_password`).

### 2. Konfigurasi Model Eloquent (CUID/UUID String Support)
Kelima model Eloquent di bawah ini diperbarui untuk menonaktifkan auto-incrementing integer key dan secara otomatis men-generate UUID string pada event `creating` jika model dibuat langsung dari backend:
- [Schedule.php](file:///d:/laragon/www/simt-backend/app/Models/Schedule.php)
- [StudentViolation.php](file:///d:/laragon/www/simt-backend/app/Models/StudentViolation.php)
- [StudentAchievement.php](file:///d:/laragon/www/simt-backend/app/Models/StudentAchievement.php)
- [TahfizRecord.php](file:///d:/laragon/www/simt-backend/app/Models/TahfizRecord.php)
- [GradeDetail.php](file:///d:/laragon/www/simt-backend/app/Models/GradeDetail.php)

Konfigurasi model ditambahkan seperti berikut:
```php
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
```

### 3. Implementasi REST API Controller
[PortalOrtuApiController.php](file:///d:/laragon/www/simt-backend/Modules/Core/app/Http/Controllers/PortalOrtuApiController.php) menyajikan endpoint REST API aman (terproteksi auth:sanctum & isolasi multi-tenant) yang mengembalikan respon terstruktur sesuai kebutuhan PortalOrtu Next.js:
*   `studentLogin`: Autentikasi siswa (NIS + Kata sandi).
*   `parentLogin`: Autentikasi wali murid (Email + Password Spatie User).
*   `dashboard`: Agregasi data dashboard wali murid (profil anak, rincian presensi pintar bulanan, ringkasan nilai, dan status tagihan).
*   `studentDashboard`: Agregasi dashboard siswa (termasuk jadwal pelajaran mingguan, pelanggaran, prestasi, dan hafalan tahfiz).
*   `gradeDetails`: Rincian detil nilai per mata pelajaran (Tugas, Harian, UTS, UAS, Akhir).

---

## 🧪 Hasil Pengujian & Verifikasi

### 1. Migrasi & Seeder Database
Perintah `php83 artisan migrate:fresh --seed` berjalan sukses 100%. Data demo untuk 100 siswa, 100 wali murid, 3,200 data nilai, 2,000 data detail nilai, 200 data tahfiz, dan presensi berhasil ditambahkan menggunakan primary key bertipe UUID string.

### 2. Unit & Feature Testing
Rangkaian unit test di [PortalOrtuApiTest.php](file:///d:/laragon/www/simt-backend/tests/Feature/PortalOrtuApiTest.php) dijalankan menggunakan PHP 8.3:
```powershell
php83 artisan test --filter=PortalOrtuApiTest
```
**Hasil:**
```
  PASS  Tests\Feature\PortalOrtuApiTest
  ✓ student can login with correct credentials                                                                  47.61s  
  ✓ student login fails with incorrect password                                                                 11.93s  
  ✓ parent can login with correct credentials                                                                   12.19s  
  ✓ authenticated parent can access child dashboard                                                             11.78s  
  ✓ parent cannot access other students dashboard idor                                                          11.90s  
  ✓ authenticated student can access own student dashboard                                                      12.11s  
  ✓ student cannot access other students student dashboard                                                      11.82s  
  ✓ can view grade details                                                                                      13.01s  

  Tests:    8 passed (64 assertions)
  Duration: 132.49s
```
Semua 8 pengujian fungsionalitas login, otorisasi kepemilikan data (anti-IDOR), multi-tenant isolation, dashboard data payload, dan grade details berhasil PASS 100%.
