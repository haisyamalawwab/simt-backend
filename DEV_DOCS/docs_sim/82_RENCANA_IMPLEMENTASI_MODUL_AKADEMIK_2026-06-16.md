# 📑 RENCANA IMPLEMENTASI: MENYELESAIKAN MODUL AKADEMIK
**Tanggal:** 16 Juni 2026  
**Status:** Draf (Menunggu Persetujuan)  
**Prioritas:** High (Production Ready)  
**Nomor Dokumen:** 82_RENCANA_IMPLEMENTASI_MODUL_AKADEMIK_2026-06-16

---

## 1. Pendahuluan
Dokumen ini merinci rencana teknis untuk menyelesaikan modul `Akademik` (pengelolaan Rombel, Mata Pelajaran, Input Nilai, dan Cetak E-Rapor) di sistem SIMT Backend. Struktur modul akan diselaraskan dengan standar **Plug & Play** (seperti modul `Finance`) dan standar penomoran DAPODIK/EMIS/RDM Kemenag.

---

## 2. Perubahan Struktur & Autoloading

### 2.1 Pendaftaran PSR-4 Autoload
Menambahkan namespace `Modules\Akademik` pada root [composer.json](file:///d:/laragon/www/simt-backend/composer.json) di bawah `autoload.psr-4`:
```json
"Modules\\Akademik\\": "Modules/Akademik/app/",
"Modules\\Akademik\\Database\\Seeders\\": "Modules/Akademik/database/seeders/"
```

### 2.2 Aktivasi Modul secara Global
Menambahkan status modul Akademik pada [modules_statuses.json](file:///d:/laragon/www/simt-backend/modules_statuses.json):
```json
"Akademik": true
```

### 2.3 Perapian File Modul
* Menghapus folder `Modules/Akademik/Routes` (uppercase).
* Membuat folder routes baru dalam huruf kecil: `Modules/Akademik/routes/`.
* Memperbarui [module.json](file:///d:/laragon/www/simt-backend/Modules/Akademik/module.json) untuk merujuk ke Provider di dalam subfolder `app/`:
```json
{
    "name": "Akademik",
    "alias": "akademik",
    "description": "Modul pengelolaan data akademik, kelas, dan mata pelajaran",
    "keywords": ["akademik", "kelas", "mata pelajaran"],
    "priority": 1,
    "providers": [
        "Modules\\Akademik\\Providers\\AkademikServiceProvider"
    ],
    "files": []
}
```

---

## 3. Pembuatan Service Providers & Routing

### 3.1 Service Providers
* **`AkademikServiceProvider.php`** (di `Modules/Akademik/app/Providers/`):
  Mendaftarkan `RouteServiceProvider` dan memuat folder views (`akademik::`).
* **`RouteServiceProvider.php`** (di `Modules/Akademik/app/Providers/`):
  Memetakan rute `web.php` dan `api.php` di bawah modul Akademik.

### 3.2 Rute Web (`routes/web.php`)
Rute dilindungi middleware group `['auth', SetTenantFromUser::class, 'module.active:Akademik']`:
```php
Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik.index');
Route::get('/akademik/classes', [AkademikController::class, 'classes'])->name('akademik.classes');
Route::post('/akademik/classes', [AkademikController::class, 'storeClass'])->name('akademik.classes.store');
Route::get('/akademik/subjects', [AkademikController::class, 'subjects'])->name('akademik.subjects');
Route::post('/akademik/subjects', [AkademikController::class, 'storeSubject'])->name('akademik.subjects.store');

Route::get('/akademik/grades', [GradeController::class, 'index'])->name('grades.index');
Route::get('/akademik/grades/create', [GradeController::class, 'create'])->name('grades.create');
Route::post('/akademik/grades', [GradeController::class, 'store'])->name('grades.store');
Route::get('/akademik/grades/rapor', [GradeController::class, 'rapor'])->name('grades.rapor');
Route::get('/akademik/grades/{grade}', [GradeController::class, 'show'])->name('grades.show');
Route::put('/akademik/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
```

---

## 4. Pemindahan & Konsolidasi Logic

### 4.1 Pemindahan GradeController
* Controller `GradeController.php` dipindahkan dari `Modules/Student/app/Http/Controllers/GradeController.php` ke `Modules/Akademik/app/Http/Controllers/GradeController.php`.
* Namespace diubah menjadi `Modules\Akademik\Http\Controllers`.
* Seluruh rujukan path view `grades.*` diubah menggunakan prefix namespace modul: `akademik::grades.*`.

### 4.2 Pembaruan AkademikController
* Menambahkan fungsi `storeClass(Request $request)` untuk memproses penambahan Rombongan Belajar baru.
* Menambahkan fungsi `storeSubject(Request $request)` untuk memproses penambahan Mata Pelajaran baru.

---

## 5. Penyediaan Premium Blade Views
Seluruh views diletakkan di `Modules/Akademik/resources/views/`:

1.  **`index.blade.php`:** Dashboard utama Akademik (Ringkasan Rombel & Mapel).
2.  **`classes.blade.php`:** Form & daftar Rombel.
3.  **`subjects.blade.php`:** Form & daftar Mapel.
4.  **`grades/index.blade.php`:** Daftar & filter nilai siswa per-jenis.
5.  **`grades/create.blade.php`:** Tabel input nilai massal sekelas.
6.  **`grades/show.blade.php`:** Detail data nilai siswa.
7.  **`grades/rapor.blade.php`:** Desain Rapor Digital (E-Rapor) interaktif.
8.  **`grades/rapor-pdf.blade.php`:** Layout print-friendly rapor format PDF.

---

## 6. Integrasi Seeding & Menu Sidebar

### 6.1 Aktivasi Otomatis untuk Tenant Demo
Memperbarui [DemoTenantSeeder.php](file:///d:/laragon/www/simt-backend/database/seeders/DemoTenantSeeder.php) dan [PitchingDemoSeeder.php](file:///d:/laragon/www/simt-backend/database/seeders/PitchingDemoSeeder.php) untuk mendaftarkan modul `Akademik` ke tabel `tenant_modules`.

### 6.2 Integrasi Sidebar
Menambahkan link menu "Akademik" pada layout sidebar [app.blade.php](file:///d:/laragon/www/simt-backend/resources/views/layouts/app.blade.php):
```blade
@if(app('currentTenant')?->hasModule('Akademik'))
    <a href="{{ route('akademik.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('akademik.*') || request()->routeIs('grades.*') ? 'bg-slate-700' : '' }}">Akademik</a>
@endif
```

---

## 7. Rencana Verifikasi

### 7.1 Automated Test (`tests/Feature/AkademikModuleTest.php`)
* Menguji hak akses rute Akademik (gated by `module.active:Akademik`).
* Menguji penyimpanan data Rombel baru (`SchoolClass`) & Mapel baru (`Subject`).
* Menguji input masal nilai siswa (`UH1`, `UTS`, `UAS`, dll).
* Menguji generasi rapor PDF.

### 7.2 Manual Test
* Menjalankan `php83 artisan test --filter=AkademikModuleTest`
* Menguji langsung di browser menggunakan salah satu akun demo.
