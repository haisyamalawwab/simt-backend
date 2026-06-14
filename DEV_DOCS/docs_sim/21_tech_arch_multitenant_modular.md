# Blueprint Arsitektur: Multi-Tenant SaaS & Modular Concept
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 10:00 WIB  
**Disusun Oleh:** IT Architect / Lead Backend Dev  

---

## 1. Konsep Dasar Multi-Tenant SaaS (Satu Sistem, Banyak Sekolah)

Mengingat anggaran server yang terbatas (VPS RAM 2GB), kita **TIDAK** menggunakan pendekatan *Database-per-Tenant*. Kita akan menggunakan **Single Database Multi-Tenancy (Row-Level Isolation)**. 

Artinya, semua data sekolah berada di 1 database PostgreSQL yang sama, namun dipisahkan secara ketat (isolasi) menggunakan kolom `tenant_id`.

### 1.1. Identifikasi Tenant (Tenant Resolution)
Bagaimana sistem tahu user sedang mengakses sekolah yang mana?
- **Via Subdomain (Frontend):** Klien mengakses `mts-assalam.simt.id`.
- **Via Header (Backend API):** Frontend Vue.js akan mengirimkan header `X-Tenant-Domain: mts-assalam.simt.id` pada setiap request API.
- Middleware Laravel (`IdentifyTenantMiddleware`) akan membaca header ini, mencari sekolah di tabel `tenants`, lalu menyimpan sesi ID sekolah tersebut ke dalam *Global Scope* Laravel.

### 1.2. Tabel `tenants` (Data Master Sekolah)
```sql
CREATE TABLE tenants (
    id UUID PRIMARY KEY,
    domain VARCHAR(100) UNIQUE, -- cth: mts-assalam
    name VARCHAR(255), -- cth: MTs Assalam
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP
);
```
*(Setiap tabel lain di sistem seperti `users`, `students`, `classes`, `invoices` WAJIB memiliki kolom `tenant_id` yang me-reference ke tabel ini).*

### 1.3. Keamanan Data (Global Scopes Laravel)
Untuk mencegah insiden *"Siswa sekolah A bocor ke sistem sekolah B"*, kita membuat *Trait* `BelongsToTenant` di Laravel:
```php
trait BelongsToTenant {
    protected static function bootBelongsToTenant() {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (app()->has('currentTenant')) {
                $builder->where('tenant_id', app('currentTenant')->id);
            }
        });
    }
}
```
*Developer cukup memanggil `use BelongsToTenant;` di setiap Model, dan Laravel otomatis memfilter data sesuai sekolah yang sedang login.*

---

## 2. Konsep Modular (Plug and Play Modules)

Untuk memastikan fitur bisa dinyala-matikan (misal: Sekolah A beli modul SPP, Sekolah B tidak beli), kita meninggalkan struktur *default* Laravel (`app/Http/Controllers`) dan beralih ke **Modular Monolith** menggunakan *package* `nwidart/laravel-modules`.

### 2.1. Struktur Direktori Modular
Setiap modul akan bertindak sebagai *"Mini-Laravel"* yang memiliki Controller, Model, Migration, dan Route masing-masing:
```text
/simt-backend
 ├── /Modules
 │   ├── /Core           (Modul Wajib: Tenant, User, Auth, RBAC)
 │   ├── /Academic       (Siswa, Guru, Rombel, Presensi)
 │   ├── /Assessment     (Nilai Formatif, Sumatif, e-Rapor)
 │   ├── /Finance        (Tagihan, Pembayaran SPP)
 │   └── /Tahfiz         (Modul Ekstra masa depan)
```

### 2.2. Implementasi Plug & Play (Tenant Feature Flags)
Kita membuat tabel `tenant_modules` untuk mengontrol akses:
```sql
CREATE TABLE tenant_modules (
    tenant_id UUID,
    module_name VARCHAR(50), -- cth: 'Finance'
    is_enabled BOOLEAN DEFAULT false,
    PRIMARY KEY (tenant_id, module_name)
);
```

### 2.3. Eksekusi Flag di API
Pada *Middleware* Laravel, kita cek apakah tenant berhak mengakses *endpoint* modul tersebut:
```php
public function handle($request, Closure $next, $moduleName) {
    $tenant = app('currentTenant');
    if (!$tenant->hasModule($moduleName)) {
        return response()->json(['message' => 'Modul ini tidak diaktifkan untuk institusi Anda. Silakan hubungi Sales.'], 403);
    }
    return $next($request);
}
```
*Developer membungkus routing modul Finance dengan middleware `module:Finance`.*