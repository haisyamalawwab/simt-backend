# Analisis & Perancangan: Modular MVC (Plug & Play) dan Input/Output API
**Berdasarkan Dokumen "Rancangan Fitur SIMT MTs (13 Modul)"**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 12:00 WIB  
**Disusun Oleh:** System/API Architect  

---

## 1. Pemetaan 13 Modul ke dalam Arsitektur `nwidart/laravel-modules`

Dokumen *Rancangan Fitur* mendeskripsikan 13 fungsi bisnis sekolah yang sangat luas. Jika disatukan dalam satu folder `app/` standar Laravel, kode akan menjadi *Monolith Spageti*. Oleh karena itu, kita memecahnya menjadi **Independent Modules (MVC)**. 

Setiap modul memiliki `Model`, `View` (Blade), `Controller`, dan `Routes` masing-masing.

### Klasifikasi Modul (Mandatory vs Plug & Play)

**A. Core & Mandatory Modules (Selalu Aktif)**
1. `Core` (Menangani Tenant, RBAC Spatie, Auth Login).
2. `Academic` (Mencakup Bab 1: Kurikulum, Jadwal, Presensi, Rapor, Modul Ajar).
3. `Student` (Mencakup Bab 2 & 10: Biodata Siswa, Ekskul).
4. `HR` (Mencakup Bab 11: SDM / Kepegawaian).

**B. Plug & Play Modules (Bisa dibeli/diaktifkan terpisah oleh Madrasah)**
5. `Tahfiz` (Bab 3: Hafalan, Ubudiyah, Munaqosah).
6. `Finance` (Bab 4: Tagihan SPP, Pembayaran).
7. `Inclusion` (Bab 7: ABK, GPK, PPI, Tes IQ) & `Counseling` (Bab 9: BK).
8. `EOffice` (Bab 13: Arsip, Surat, Disposisi, E-Signature).
9. `Library` (Bab 12: Perpustakaan).
10. `Facility` (Bab 8: Sarpras & Inventaris).

---

## 2. Mekanisme "Plug and Play" (Feature Toggling)

### 2.1. Desain Database
Tabel `tenant_modules` menyimpan status langganan suatu madrasah terhadap modul Plug & Play.
```sql
CREATE TABLE tenant_modules (
    tenant_id UUID,
    module_slug VARCHAR(50), -- cth: 'tahfiz', 'eoffice', 'inklusi'
    is_active BOOLEAN DEFAULT false,
    expired_at DATE,
    PRIMARY KEY (tenant_id, module_slug)
);
```

### 2.2. Middleware Pencegat (API & Web)
Kita buat sebuah *Middleware* bernama `CheckModuleEnabled`.
```php
public function handle($request, Closure $next, $moduleSlug) {
    $tenant = app('currentTenant');
    
    // Cek di cache/database apakah tenant ini mengaktifkan modul $moduleSlug
    if (!$tenant->isModuleActive($moduleSlug)) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => "Modul {$moduleSlug} belum diaktifkan atau masa langganan habis."
            ], 403); // 403 Forbidden
        }
        abort(403, "Modul {$moduleSlug} belum diaktifkan.");
    }

    return $next($request);
}
```
*Implementasi di Routes:*
```php
Route::middleware(['auth:sanctum', 'tenant', 'module:tahfiz'])->group(function () {
    // Semua I/O API Tahfiz diletakkan di sini
});
```

---

## 3. Komunikasi Antar-Modul (Inter-Module Communication)

Karena arsitektur ini terisolasi, Modul A tidak boleh melakukan kueri (*query*) langsung ke _database_ Modul B, karena Modul B bisa saja dalam keadaan "Mati/Disable". 

**Contoh Kasus (Dokumen Bab 1 & 7):** Modul `Academic` ingin membuat Rapor, dan jika anak tersebut ABK (Modul `Inclusion` Aktif), Rapor harus melampirkan "Program Pembelajaran Individual (PPI)".

**Solusi: Event & Listener / Service Interface**
1. Saat kalkulasi Rapor, `Academic` memicu event: `event(new RaporGenerating($student))`.
2. Jika modul `Inclusion` aktif, pendengar (*Listener*) di modul tersebut merespons event itu dan menyuntikkan (inject) nilai/data PPI ke dalam payload rapor. Jika modul mati, tidak terjadi apa-apa, dan sistem tidak akan *crash*.

---

## 4. Perancangan API Input/Output (Khusus Modul Unik)

Berdasarkan *file* dokumen, berikut adalah kontrak I/O (*Input/Output*) dari Modul *Add-on* unggulan Madrasah.

### A. Modul Tahfiz (Penilaian Hafalan & Ubudiyah)
**Endpoint:** `POST /api/v1/tahfiz/mutabaah`
**Aktor:** Guru Tahfiz
**Input (Request Body):**
```json
{
  "student_id": 145,
  "tanggal": "2026-06-12",
  "hafalan": {
    "surah_awal": "Al-Baqarah", "ayat_awal": 1,
    "surah_akhir": "Al-Baqarah", "ayat_akhir": 10,
    "nilai_kelancaran": 85,
    "nilai_tajwid": 90
  },
  "ubudiyah": {
    "dhuha": true,
    "tahajud": false,
    "jamaah_zuhur": true
  },
  "catatan_guru": "Tajwid perlu diperbaiki pada hukum ikhfa."
}
```
**Output (Response 201 Created):**
```json
{
  "success": true,
  "message": "Mutaba'ah berhasil disimpan."
}
```

### B. Modul Inklusi (Program Pembelajaran Individual / PPI)
**Endpoint:** `POST /api/v1/inclusion/ppi`
**Aktor:** GPK (Guru Pendamping Khusus)
**Input (Request Body):**
```json
{
  "student_id": 201,
  "semester_id": 2,
  "kategori_kebutuhan": "Autism Spectrum Disorder",
  "target_individual": "Anak mampu duduk tenang selama 20 menit dalam kelas.",
  "strategi_diferensiasi": "Diberikan ear-muff saat suasana kelas bising.",
  "akomodasi_asesmen": "Ujian diberikan dalam bentuk lisan, bukan tulisan.",
  "home_program": "Orang tua melatih fokus kontak mata di rumah."
}
```

### C. Modul E-Office (Disposisi Pimpinan)
**Endpoint:** `POST /api/v1/eoffice/disposisi`
**Aktor:** Kepala Madrasah
**Input (Request Body):**
```json
{
  "surat_masuk_id": 12,
  "tujuan_disposisi_role": "waka_kurikulum",
  "instruksi": "Tolong segera tindak lanjuti undangan MGMP ini, siapkan guru pengganti.",
  "deadline": "2026-06-15 12:00:00",
  "require_esignature": true,
  "pin_esignature": "123456" 
}
```
*(Catatan: `pin_esignature` divalidasi langsung oleh API untuk memverifikasi Tanda Tangan Digital sebelum disposisi disimpan dan notifikasi WA dikirim).*

### D. Modul Dashboard Orang Tua (Output Aggregation)
Portal ortu (Next.js) tidak memanggil API per modul, melainkan mengakses satu endpoint agregasi (*Gateway*). 
**Endpoint:** `GET /api/v1/parent/dashboard?nisn=1234567890`
**Output (Response 200 OK):**
```json
{
  "success": true,
  "data": {
    "akademik": {
       "kehadiran_persen": 95,
       "jadwal_besok": ["Matematika", "Fiqih"]
    },
    "keuangan": {
       "status": "Tunggakan",
       "total_tunggakan": 150000
    },
    "tahfiz": { // Object ini akan HILANG jika tenant tidak membeli Modul Tahfiz
       "surah_terakhir": "Al-Baqarah: 10"
    }
  }
}
```

---

## 5. Strategi Deployment & Pemeliharaan Modul

Dengan arsitektur ini, kode sumber `Modul EOffice` dan `Modul Tahfiz` tetap ada di dalam server semua Madrasah. Namun:
- Tidak membebani kueri database (Tabel akan kosong atau tidak pernah diakses jika fitur *disabled*).
- Menghilangkan *UI Clutter*. Di *Frontend (Blade)*, semua navigasi dibungkus dengan pengecekan `if(app('currentTenant')->isModuleActive('tahfiz'))`, sehingga menu "Tahfiz" akan otomatis hilang (tersembunyi) dari _Sidebar_ TU dan Guru jika madrasah belum berlangganan.