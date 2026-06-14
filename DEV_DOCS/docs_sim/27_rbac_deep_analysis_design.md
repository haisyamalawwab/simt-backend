# Analisis Mendalam & Perancangan RBAC (Role-Based Access Control)
**Berdasarkan Dokumen "Rancangan Fitur SIMT MTs (13 Modul)"**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 11:30 WIB  
**Disusun Oleh:** System Analyst & Tech Lead  

---

## 1. Pendahuluan
Dokumen ini membedah rancangan fitur dari dokumen `.docx` yang berisi 13 Modul Sistem Informasi Manajemen Terpadu MTs. Tujuannya adalah menerjemahkan kebutuhan operasional madrasah tersebut ke dalam arsitektur keamanan akses menggunakan kombinasi **Spatie Laravel Permission** dan **Laravel Policies** pada lingkungan *Multi-Tenant SaaS*.

---

## 2. Definisi Aktor (10 Peran Utama)

Merujuk pada dokumen, terdapat 10 aktor yang akan berinteraksi dengan sistem. Di dalam *database*, aktor ini akan direpresentasikan sebagai *Roles*:

1. **`kepala_madrasah`**: Memiliki hak akses *Super-View* (Dashboard eksekutif, disposisi e-office, monitoring semua bidang) dan eksekutor E-Signature.
2. **`waka`** (Wakil Kepala): Membutuhkan akses manajerial tingkat menengah (Misal Waka Kurikulum menyetujui Modul Ajar).
3. **`guru`** (Guru Mapel): Akses pengelolaan pembelajaran di kelas yang diajarnya (Jurnal, Nilai, Presensi, Upload Modul Ajar).
4. **`wali_kelas`**: Peran tambahan (add-on) bagi guru untuk mengakses rekap presensi kelasnya, nilai akhir, dan cetak E-Rapor.
5. **`guru_bk`**: Akses eksklusif ke Modul Kesiswaan & Modul BK (Catatan konseling, pelanggaran, surat pemanggilan).
6. **`gpk_inklusi`** (Guru Pendamping Khusus): Akses eksklusif ke Modul Inklusi (Input PPI, catatan asesmen khusus ABK).
7. **`guru_tahfiz`**: Akses ke Modul Tahfiz (Monitoring hafalan, penilaian ubudiyah).
8. **`tata_usaha`**: Admin operasional harian (Modul Keuangan/SPP, Sarpras, SDM/Kepegawaian, E-Office surat menyurat).
9. **`orang_tua`**: Akses *Read-Only* (Dashboard Portal) untuk melihat anak spesifik.
10. **`siswa`**: Akses *Read-Only* (Jadwal, E-Learning, Bank Soal).

---

## 3. Matriks Izin (Permissions) Spatie per Modul

Penamaan *Permissions* akan menggunakan standar `modul.fitur.aksi`. Berikut adalah hasil ekstraksi dari 13 Modul di dokumen:

### Modul 1: Akademik / Kurikulum
- `akademik.jadwal.view`, `akademik.jadwal.manage`
- `akademik.presensi.input` (Guru), `akademik.presensi.rekap` (Waka/Kepsek)
- `akademik.nilai.input`, `akademik.rapor.generate` (Wali Kelas)
- `akademik.modul_ajar.upload` (Guru)
- `akademik.modul_ajar.approve` (Waka Kurikulum)

### Modul 2 & 10: Kesiswaan & Ekstrakurikuler
- `kesiswaan.pelanggaran.input`, `kesiswaan.prestasi.input`
- `kesiswaan.ekskul.manage`, `kesiswaan.ekskul.nilai`

### Modul 3: Tahfiz
- `tahfiz.program.manage`
- `tahfiz.hafalan.input` (Guru Tahfiz)
- `tahfiz.munaqosah.manage`

### Modul 4: Keuangan
- `keuangan.tagihan.generate` (TU)
- `keuangan.pembayaran.terima` (TU Kasir)
- `keuangan.laporan.view` (Kepsek/TU)

### Modul 7: Inklusi
- `inklusi.ppi.create` (GPK)
- `inklusi.ppi.approve` (Waka/Kepsek)
- `inklusi.asesmen.upload` (GPK)

### Modul 8: Sarana & Prasarana
- `sarpras.inventaris.manage` (TU)
- `sarpras.peminjaman.approve` (Waka Sarpras / Kepsek)

### Modul 9: Bimbingan Konseling (BK)
- `bk.konseling.input` (Guru BK - *Sangat Rahasia*)
- `bk.tes_psikologi.view` 

### Modul 11: SDM / Kepegawaian
- `sdm.presensi.manage` (TU)
- `sdm.kinerja.input` (Kepsek/Waka)

### Modul 12: Perpustakaan
- `perpus.katalog.manage`
- `perpus.sirkulasi.manage` (Pustakawan/TU)

### Modul 13: E-Office / Administrasi Pimpinan
- `eoffice.surat.manage` (TU)
- `eoffice.disposisi.create` (Kepala Madrasah)
- `eoffice.esign.use` (Kepala Madrasah - membutuhkan PIN / OTP)

---

## 4. Analisis Kasus Kompleks (Contextual Authorization)

Dalam sistem sekolah, RBAC biasa tidak cukup. Jika `Guru A` memiliki izin `akademik.nilai.input`, **dia tidak boleh bisa mengubah nilai kelas `Guru B`**. 
Di sinilah **Laravel Policies** menjembatani Spatie Permissions.

### Skenario 1: Multi-Peran (Guru + Wali Kelas + Pembina Ekskul)
Dalam Spatie, 1 User dapat memiliki banyak role.
```php
$user->assignRole(['guru', 'wali_kelas', 'pembina_ekskul']);
```
Sistem frontend/menu akan otomatis memunculkan modul *Rapor* dan *Ekskul* di sidebar user ini karena gabungan role tersebut.

### Skenario 2: Otorisasi Kepemilikan (Ownership Policy)
Kita menggunakan *Policy* di Laravel untuk mengecek kepemilikan kelas.

```php
// app/Policies/ClassroomPolicy.php
public function inputNilai(User $user, Classroom $classroom)
{
    // Pastikan dia punya hak akses input nilai
    if (!$user->hasPermissionTo('akademik.nilai.input')) return false;

    // Pastikan dia adalah pengampu mapel di kelas tersebut
    $isPengampu = $classroom->teachers()->where('user_id', $user->id)->exists();
    
    return $isPengampu;
}
```

### Skenario 3: Isolasi Modul BK dan Inklusi
Data BK (Kasus Anak) dan Inklusi (IQ/Psikologi) sangat sensitif.
- Guru biasa hanya bisa melihat bahwa anak tersebut berstatus *Inklusi*, tapi **tidak bisa** melihat dokumen Tes IQ-nya. 
- *Policy*: Hanya user dengan *role* `guru_bk`, `gpk_inklusi`, atau `kepala_madrasah` yang bisa mengakses *endpoint* `/api/v1/inklusi/asesmen/{id}`.

---

## 5. Implementasi Teknis di Blade & API

### 5.1. Filter Tampilan di Blade (Admin)
Mencegah tombol atau menu muncul jika tidak memiliki izin, menggunakan sintaks bawaan Spatie:

```html
@can('akademik.modul_ajar.approve')
    <x-ui.button variant="primary" wire:click="approveModul">Setujui Modul</x-ui.button>
@endcan

@role('kepala_madrasah')
    <a href="/eoffice/disposisi">Disposisi Surat</a>
@endrole
```

### 5.2. Filter Eksekusi di API (Next.js / Middleware)
Gunakan kombinasi `middleware` dari Spatie dan `authorize` dari Laravel pada Controller:

```php
// Di dalam route API
Route::post('/surat/disposisi', [EofficeController::class, 'storeDisposisi'])
    ->middleware('permission:eoffice.disposisi.create');

// Di dalam Controller (Contextual)
public function cetakRapor(Request $request, Classroom $classroom) {
    // Mengecek Policy (Apakah dia benar Wali Kelas dari kelas ini?)
    $this->authorize('cetakRapor', $classroom);
    
    // Logic cetak PDF
}
```

---

## 6. Seeder Dasar (Initialization Script)

Bagi *Developer*, ini adalah script *Seeder* untuk membangun fondasi RBAC berdasarkan dokumen `docx`:

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// 1. Buat Permissions (Looping dari array)
$permissions = [
    'akademik.nilai.input', 'akademik.rapor.generate', 'akademik.modul_ajar.upload', 'akademik.modul_ajar.approve',
    'inklusi.ppi.create', 'inklusi.ppi.approve',
    'eoffice.disposisi.create', 'eoffice.esign.use',
    // ... dst sesuai matriks Bab 3
];

foreach ($permissions as $perm) {
    Permission::firstOrCreate(['name' => $perm]);
}

// 2. Buat Roles & Assign Permissions
$roleKepsek = Role::firstOrCreate(['name' => 'kepala_madrasah']);
$roleKepsek->givePermissionTo(Permission::all()); // Super Admin untuk Tenant ini

$roleGuru = Role::firstOrCreate(['name' => 'guru']);
$roleGuru->givePermissionTo([
    'akademik.jadwal.view', 'akademik.presensi.input', 'akademik.nilai.input', 'akademik.modul_ajar.upload'
]);

$roleWakaKurikulum = Role::firstOrCreate(['name' => 'waka']);
$roleWakaKurikulum->givePermissionTo([
    'akademik.modul_ajar.approve' // Waka bisa approve modul ajar
]);

$roleGPK = Role::firstOrCreate(['name' => 'gpk_inklusi']);
$roleGPK->givePermissionTo(['inklusi.ppi.create', 'inklusi.asesmen.upload']);
```

---
*Dengan analisis di atas, tim pengembang memiliki batas yang jelas antara kapan menggunakan **Spatie (untuk mengecek "Role Apa Kamu?")** dan kapan menggunakan **Laravel Policy (untuk mengecek "Ini Kelas Siapa?")** dalam mewujudkan ke-13 Modul secara aman.*