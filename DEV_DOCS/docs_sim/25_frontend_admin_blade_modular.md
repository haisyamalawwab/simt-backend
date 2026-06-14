# Standar UI/UX Admin: Modular Blade & Reusable Components
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 11:00 WIB  
**Disusun Oleh:** Lead Frontend / Fullstack Dev  

---

## 1. Pendahuluan
Bagi pengguna internal sekolah (Guru, TU, Kepala Sekolah), kita membangun Dashboard Admin yang di-render di *server* (SSR) menggunakan **Laravel Blade**. Untuk menghindari duplikasi kode HTML, kita menggunakan konsep **Blade Components (Anonymous & Class-Based)** yang terinspirasi dari komponen React/Vue.

Stack pendukung interaktivitas: **Alpine.js** (untuk animasi ringan/modal) dan **Tailwind CSS**.

---

## 2. Struktur Komponen Reusable (Global Partials)

Komponen UI yang dipakai berulang di seluruh modul (Tombol, Input, Modal, Tabel) disimpan di direktori global: `/resources/views/components/`

### 2.1. Komponen Layout (`<x-admin-layout>`)
Membungkus seluruh halaman Admin dengan Sidebar dan Topbar.
**Penggunaan di dalam Modul:**
```html
<x-admin-layout>
    <x-slot:title>Daftar Siswa</x-slot:title>

    <!-- Konten Spesifik Modul -->
    <div class="p-6">
        <x-ui.table :headers="['NISN', 'Nama', 'Kelas', 'Aksi']">
           <!-- loop data siswa di sini -->
        </x-ui.table>
    </div>
</x-admin-layout>
```

### 2.2. Komponen UI Partials (`<x-ui.*>`)
Membuat direktori `resources/views/components/ui/`.
- `<x-ui.button>`: Tombol standar dengan varian warna Tailwind.
- `<x-ui.input>`: Form input dengan *error validation state*.
- `<x-ui.modal>`: Modal popup (dikendalikan via Alpine.js `x-data="{ open: false }"`).
- `<x-ui.alert>`: Notifikasi sukses/error (Flash Message).

**Contoh Definisi `<x-ui.button>`:**
```html
{{-- resources/views/components/ui/button.blade.php --}}
@props(['variant' => 'primary', 'type' => 'submit'])

@php
    $baseClasses = "inline-flex items-center px-4 py-2 border rounded-md font-semibold text-sm transition";
    $colors = [
        'primary' => 'bg-emerald-600 text-white hover:bg-emerald-700',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $colors[$variant]]) }}>
    {{ $slot }}
</button>
```

---

## 3. Isolasi Views Berdasarkan Modul (Modular Views)

File antarmuka spesifik bisnis tidak boleh diletakkan di `/resources/views`. Harus diletakkan di dalam folder `Resources/views` milik masing-masing Modul nWidart.

### Contoh Pemanggilan Views dalam Controller Modul Akademik:
```php
// Modules/Academic/Http/Controllers/Web/StudentController.php
public function index() {
    $students = Student::paginate(20);
    // 'academic::' adalah namespace otomatis dari laravel-modules
    return view('academic::students.index', compact('students'));
}
```

### 4. Interaktivitas Dinamis (Tanpa Vue)
Karena tidak lagi menggunakan Vue untuk Admin, interaksi dinamis seperti Form pencarian langsung atau Tab-switching ditangani menggunakan **Alpine.js**.

**Contoh Dropdown Aksi dengan Alpine:**
```html
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open">Aksi</button>
    <div x-show="open" @click.outside="open = false" class="absolute bg-white shadow">
        <a href="#">Edit</a>
        <a href="#">Hapus</a>
    </div>
</div>
```
*Pendekatan ini menjamin halaman termuat sangat cepat (TTFB rendah), SEO-friendly, dan sangat murah untuk dihosting di VPS 2GB karena tidak memakan memori komputasi Javascript yang besar di sisi klien.*