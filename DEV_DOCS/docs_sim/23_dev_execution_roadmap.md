# Eksekusi Teknis: Roadmap Implementasi Backend (Developer Guide)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 10:30 WIB  
**Disusun Oleh:** Tech Lead  

---

## 1. Pendahuluan
Dokumen ini adalah *"Cheat Sheet"* atau contekan eksekusi hari demi hari bagi *Backend Developer* untuk mulai membangun fondasi *API Multi-Tenant SaaS* berdasarkan dokumen *21* dan *22*.

---

## 2. Fase 1: Inisialisasi Proyek (Day 1)

### 2.1. Instalasi Laravel & Package Inti
```bash
composer create-project laravel/laravel simt-backend "10.*"
cd simt-backend

# Instal Spatie RBAC
composer require spatie/laravel-permission

# Instal Nwidart Modules (Arsitektur Modular)
composer require nwidart/laravel-modules

# Publish konfigurasi
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```

### 2.2. Setup Single-Database Tenancy (Manual / Lightweight)
Karena `stancl/tenancy` kadang *overkill*, kita bisa setup mandiri untuk *Single DB*:
1. Buat *migration* `create_tenants_table`.
2. Buat *migration* modifikasi ke `users` (tambahkan nullable `tenant_id` jika user bisa terikat sekolah secara default, atau gunakan tabel pivot `tenant_user` jika user bisa mengajar di banyak sekolah. Untuk MVP: letakkan `tenant_id` langsung di `users`).

### 2.3. Konfigurasi Spatie (Team / Tenant Mode)
Buka `config/permission.php`:
```php
'teams' => true,
```
Buka *migration file* bawaan spatie yang baru dipublish (ada di `database/migrations/xxx_create_permission_tables.php`). Pastikan di tabel `roles`, `model_has_permissions`, dll terdapat baris:
```php
$table->unsignedBigInteger($teamForeignKey)->nullable(); // Jika belum ada, uncomment.
```
Lalu jalankan `php artisan migrate`.

---

## 3. Fase 2: Pembangunan Modul "Core" (Day 2)

Modul *Core* bertugas menangani Autentikasi, Identifikasi Tenant, dan Manajemen RBAC.

### 3.1. Buat Modul Core
```bash
php artisan module:make Core
```

### 3.2. Buat Tenant Middleware
Di dalam Modul Core (`Modules/Core/Http/Middleware/IdentifyTenant.php`):
```php
public function handle(Request $request, Closure $next)
{
    $domain = $request->header('X-Tenant-Domain');
    if (!$domain) {
        return response()->json(['message' => 'Missing Tenant Header'], 400);
    }

    $tenant = Tenant::where('domain', $domain)->first();
    if (!$tenant) {
        return response()->json(['message' => 'Tenant not found'], 404);
    }

    // Bind ke service container Laravel
    app()->instance('currentTenant', $tenant);

    // Set Spatie Team ID agar query role spesifik ke sekolah ini
    setPermissionsTeamId($tenant->id);

    return $next($request);
}
```

### 3.3. Terapkan Global Scope (BelongsToTenant Trait)
Buat trait di `Modules/Core/Traits/BelongsToTenant.php` (Lihat Dokumen 21).

---

## 4. Fase 3: Pembangunan Modul Akademik & Keuangan (Day 3-4)

### 4.1. Buat Modul Akademik
```bash
php artisan module:make Academic
php artisan module:make-model Student Academic -m -c -r
```

### 4.2. Modifikasi Model Student
```php
namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\BelongsToTenant;

class Student extends Model
{
    use BelongsToTenant; // <-- INI WAJIB ADA!

    protected $fillable = ['tenant_id', 'nisn', 'name', /* dll */];

    // Otomatis isi tenant_id saat menyimpan
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (app()->has('currentTenant')) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }
}
```

### 4.3. Aturan Routing di dalam Modul
Pastikan semua route API (`Modules/Academic/Routes/api.php`) dibungkus oleh Middleware Tenant dan Auth Sanctum:
```php
Route::middleware(['auth:sanctum', 'tenant.identify'])->prefix('v1/academic')->group(function() {
    Route::get('/students', [StudentController::class, 'index']);
});
```

---

## 5. Fase 4: Validasi Modul & Deployment Preparation (Day 5)

Setiap *developer* yang membuat PR (Pull Request) **wajib** di-review berdasarkan *checklist* ini:
1. Apakah *Model* yang baru dibuat sudah *use BelongsToTenant*? (Fatal jika lupa).
2. Apakah validasi *FormRequest* mengecek keunikan data berdasarkan Tenant? (Contoh: NISN unik per sekolah, bukan unik seluruh tabel).
   - Penulisan Rule Unique: `Rule::unique('students')->where('tenant_id', app('currentTenant')->id)`
3. Apakah fitur dibungkus otorisasi Spatie (`$user->hasPermissionTo()`)?

*Dengan mengikuti pedoman 4 hari ini, fondasi SaaS akan terbangun dengan kokoh.*