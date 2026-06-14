# Blueprint Implementasi: API Driven & RBAC Multi-Tenant
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 10:15 WIB  
**Disusun Oleh:** Senior Backend Developer  

---

## 1. Arsitektur API Driven (Headless Backend)

Karena Laravel hanya bertugas melayani data (tanpa *blade views*), kita harus menerapkan standar API ketat sejak *commit* pertama.

### 1.1. Base Response Controller (Macro / Trait)
Agar struktur JSON *frontend* konsisten, kita membuat trait `ApiResponseHelpers`:
```php
trait ApiResponseHelpers {
    public function respondSuccess($data, $message = 'Success', $code = 200) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    public function respondError($message = 'Error', $errors = [], $code = 400) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }
}
```

### 1.2. API Resources (Transformer)
Jangan pernah me-return objek Model Eloquent secara langsung ke Frontend (bahaya kebocoran kolom sensitif seperti `password` atau `tenant_id`). Gunakan **Laravel API Resources**.
```php
// Modules/Academic/Transformers/StudentResource.php
public function toArray($request) {
    return [
        'id'          => $this->id,
        'nisn'        => $this->nisn,
        'name'        => $this->name,
        // tenant_id sengaja TIDAK direturn agar tidak terekspos.
        'created_at'  => $this->created_at->format('Y-m-d H:i:s'),
    ];
}
```

---

## 2. Implementasi RBAC dalam Lingkungan Multi-Tenant

Ini adalah bagian paling menantang: **Role dan Permission harus dipisahkan berdasarkan Tenant**. 
Jika "Guru Budi" adalah Admin di *MTs Assalam*, dia belum tentu Admin (atau bahkan terdaftar) di *MTs Al-Hidayah*.

Kita akan menggunakan *package* standar industri: `spatie/laravel-permission`.

### 2.1. Konfigurasi Spatie Team / Tenant Feature
Spatie memiliki fitur rahasia bernama **"Teams"**. Dalam konteks proyek kita, `team_id` disamakan dengan `tenant_id`.

**Langkah Konfigurasi:**
1. Buka `config/permission.php`.
2. Ubah konfigurasi `teams` menjadi `true`:
   ```php
   'teams' => true,
   ```
3. Saat *Tenant Middleware* berjalan, segera set `team_id` Spatie agar sesuai dengan `tenant_id` sekolah yang sedang diakses:
   ```php
   // Di dalam TenantMiddleware
   $tenantId = app('currentTenant')->id;
   setPermissionsTeamId($tenantId);
   ```

### 2.2. Struktur Basis Data RBAC
Dengan mengaktifkan *teams*, struktur *database* Spatie akan memiliki kolom tambahan `team_id` di tabel penghubung (`model_has_roles`, `model_has_permissions`, `roles`).

Sehingga 1 entitas User Budi (`user_id: 1`) bisa memiliki Role "Kepala Sekolah" di `team_id: 1` (MTs A), dan Role "Guru Biasa" di `team_id: 2` (MTs B).

### 2.3. Eksekusi Pengecekan Otorisasi di API (Policies & Gates)
Di dalam setiap *Controller*, developer tidak perlu pusing memikirkan *tenant*. Cukup gunakan fungsi bawaan Spatie (karena `team_id` sudah di-set di level *middleware*).

```php
// Modules/Finance/Http/Controllers/BillingController.php
public function generate(Request $request) {
    // Spatie otomatis mengecek apakah user ini punya hak akses 'generate-billing' di TENANT ini.
    if (!auth()->user()->hasPermissionTo('generate-billing')) {
        return $this->respondError('Anda tidak memiliki hak akses keuangan di sekolah ini.', [], 403);
    }
    
    // Lakukan proses billing...
}
```

### 2.4. Seeding Default Roles (Saat Onboarding Tenant Baru)
Saat ada sekolah baru yang mendaftar, kita harus menggunakan *Job* atau *Observer* untuk menyuntikkan Role standar (Super Admin, TU, Guru, Kepsek) *khusus* untuk `tenant_id` sekolah tersebut.
```php
// Conto logic pembuatan sekolah baru
public function onboardNewSchool($schoolData) {
    $tenant = Tenant::create($schoolData);
    
    setPermissionsTeamId($tenant->id);
    
    // Buat Roles (akan tersimpan dengan team_id milik $tenant->id)
    Role::create(['name' => 'admin_tu']);
    Role::create(['name' => 'guru_mapel']);
    
    // Assign user pendaftar pertama sebagai Admin di Tenant ini
    $user->assignRole('admin_tu');
}
```
*Dengan arsitektur ini, kode backend sangat "DRY" (Don't Repeat Yourself) dan kokoh terhadap insiden lintas-data antar sekolah.*