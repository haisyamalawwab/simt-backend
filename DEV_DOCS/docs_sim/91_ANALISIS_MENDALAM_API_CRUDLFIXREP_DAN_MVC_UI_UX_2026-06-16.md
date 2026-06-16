# Analisis Mendalam Hak Akses User RBAC, Siklus CRUDLFIXRep, dan Arsitektur MVC UI/UX

Analisis mendalam mengenai pemetaan hak akses peran (RBAC) dan siklus hidup pemrosesan data **CRUDLFIXRep (Create, Read, Update, Delete, List, Import, Export, Report)** di tingkat API dan Web MVC, serta evaluasi estetika antarmuka UI/UX pada repositori **SIMT Backend**.

---

## 1. Pendahuluan

Dokumen ini disusun untuk mengevaluasi secara kritis arsitektur keamanan akses pengguna dan pola standarisasi manipulasi data pada SIMT Backend. Di era sistem berbasis multi-tenant SaaS, pemisahan hak akses yang granular (RBAC) wajib dipadukan dengan siklus pemrosesan data yang aman, efisien, dan ramah pengguna (UI/UX).

Analisis ini menyisir implementasi nyata pada kode sumber untuk membuktikan keselarasan antara desain teoritis dan realisasi teknis, khususnya pada modul **Student (Kesiswaan)**, **Finance (Keuangan)**, **Akademik (Nilai & Rapor)**, dan **Notification (WhatsApp Gateway)**.

---

## 2. Hak Akses User RBAC & Kebijakan Otorisasi Lintas-Tenant

SIMT Backend memadukan pustaka *Spatie Laravel Permission* dengan sistem *Tenancy Context* berbasis singleton. Struktur ini memungkinkan pembatasan hak akses yang elastis dan aman lintas-tenant.

```
                  ┌─────────────────────────────────────┐
                  │          SaaS SUPERADMIN            │  ← tenant_id = null
                  └──────────────────┬──────────────────┘  ← bypass tenant checks
                                     │
                 ┌───────────────────┴───────────────────┐
                 ▼                                       ▼
    ┌─────────────────────────┐             ┌─────────────────────────┐
    │     Tenant A Admin      │             │     Tenant B Admin      │  ← team_id = tenant_id
    ├─────────────────────────┤             ├─────────────────────────┤  ← Spatie team scope
    │ - Guru A (Role: Guru)   │             │ - Guru B (Role: Guru)   │
    │ - Wali A (Role: Wali)   │             │ - Wali B (Role: Wali)   │
    └─────────────────────────┘             └─────────────────────────┘
```

### A. Hirarki Peran (Role Hierarchy) & Konteks Multi-Tenant
Sesuai rancangan [03_pemetaan_modul_rbac.md](file:///d:/laragon/www/simt-backend/DEV_DOCS/docs_sim/03_pemetaan_modul_rbac.md), hak akses dipecah menjadi beberapa kelompok peran:
1.  **SaaS Superadmin (`superadmin`)**:
    *   *Konteks*: Bebas dari batasan tenant (`tenant_id = null`).
    *   *Wewenang*: Memiliki hak akses penuh untuk mengelola pendaftaran tenant baru, mengaktifkan/menonaktifkan modul berbayar, dan memantau audit log global lintas-tenant.
2.  **Tenant Administrators (`admin_sekolah`, `kepala_madrasah`, `tata_usaha`)**:
    *   *Konteks*: Terikat pada satu `tenant_id` melalui isolasi Spatie `team_id`.
    *   *Wewenang*: Melakukan manajemen operasional harian sekolah. Admin sekolah memiliki wewenang penuh atas seluruh fitur di sekolahnya, sementara Kepala Madrasah memiliki akses pemantauan (view-only) tingkat eksekutif.
3.  **Tenant End-Users (`guru`, `bendahara`, `wali`)**:
    *   *Konteks*: Hak akses spesifik dan kontekstual.
    *   *Wewenang*: Guru hanya dapat memproses kelas/nilai mereka sendiri. Wali murid berada di lapisan terluar dengan pembatasan hak akses hanya untuk data anak kandungnya saja.

### B. Otorisasi Kontekstual & Pencegahan IDOR (Insecure Direct Object Reference)
Sistem membedakan otorisasi statis ("Role apa kamu?") dengan otorisasi kontekstual ("Apakah resource ini milikmu?"). Hal ini krusial untuk mencegah kebocoran data sensitif antar pengguna di tenant yang sama.

*   **Kasus Otorisasi Guru**:
    Pada [AttendanceController.php](file:///d:/laragon/www/simt-backend/Modules/Attendance/app/Http/Controllers/AttendanceController.php#L30-L36), meskipun seorang user memiliki role `guru` dan memiliki izin `view_attendance`, kueri database dibatasi:
    ```php
    if ($user->hasRole('guru')) {
        $classes = SchoolClass::where('teacher_id', $user->id)->get(); // Hanya kelas miliknya
    }
    ```
*   **Kasus Otorisasi Wali Murid (Pencegahan IDOR)**:
    Pada API tagihan [FinanceApiController.php](file:///d:/laragon/www/simt-backend/Modules/Finance/app/Http/Controllers/FinanceApiController.php#L45-L53) dan API nilai [AkademikApiController.php](file:///d:/laragon/www/simt-backend/Modules/Akademik/app/Http/Controllers/AkademikApiController.php#L18-L29), sistem memverifikasi relasi wali-anak secara eksplisit sebelum menyajikan data:
    ```php
    $isGuardian = $user->guardianStudents()->where('student_id', $student->id)->exists();
    if (!$isGuardian) {
        return response()->json(['success' => false, 'message' => 'Access Denied'], 403);
    }
    ```
    Mekanisme ini mencegah wali murid memodifikasi parameter ID di URL (misal `/api/v1/students/99/bills`) untuk mengintip tagihan siswa lain.

---

## 3. Siklus Hidup Pemrosesan Data CRUDLFIXRep

SIMT Backend menerapkan standar pemrosesan data komprehensif yang disingkat sebagai **CRUDLFIXRep**:

### C — Create (Pembuatan Data)
*   **Logika Single Creation**: Pembuatan data tunggal yang menyertakan relasi otomatis. Contohnya pada [StudentController.php](file:///d:/laragon/www/simt-backend/Modules/Student/app/Http/Controllers/StudentController.php#L83-L95), saat membuat siswa baru, sistem secara otomatis mengecek nomor handphone wali, membuatkan akun `User` bertipe role `wali` jika belum terdaftar, memicu pembuatan password acak, dan menghubungkan keduanya dalam tabel pivot.
*   **Logika Bulk/Mass Generation**: Digunakan pada pembuatan tagihan bulanan massal di [FinanceController.php](file:///d:/laragon/www/simt-backend/Modules/Finance/app/Http/Controllers/FinanceController.php#L41-L98). Sistem melakukan iterasi terhadap seluruh siswa aktif dalam satu transaksi, menyisipkan tagihan, dan secara opsional mengantrikan job WhatsApp reminder ke dalam queue Redis/database secara real-time.

### R — Read (Pembacaan Data)
*   **Detail View**: Pembacaan data detail terproteksi. Pada data pembayaran keuangan, method `printReceipt` memanggil parser HTML-to-PDF (`dompdf`) untuk merender kwitansi resmi secara on-the-fly untuk diunduh oleh wali murid.
*   **API Response Integrity**: Seluruh API endpoint mengembalikan format JSON standar yang konsisten (`success`, `message`, `data`).

### U — Update (Pembaruan Data)
*   **Logika Status Synchronization**: Pembaruan data diikuti sinkronisasi status bisnis otomatis. Contohnya pada [FinanceController.php](file:///d:/laragon/www/simt-backend/Modules/Finance/app/Http/Controllers/FinanceController.php#L284-L293), saat tagihan diperbarui (`updateBill`), sistem memanggil method internal `$bill->updateStatus()` untuk menghitung ulang sisa tunggakan (`amount - paid_amount`) dan menyesuaikan status pembayaran menjadi `paid`, `partial`, atau `unpaid` di database secara konsisten.

### D — Delete (Penghapusan Data)
*   **Logika Safety Constraints**: Mencegah kerusakan integritas data akibat penghapusan tidak sengaja. Pada [FinanceController.php](file:///d:/laragon/www/simt-backend/Modules/Finance/app/Http/Controllers/FinanceController.php#L296-L305), penghapusan tagihan dibatasi ketat:
    ```php
    if ($bill->status !== 'unpaid') {
        return back()->with('error', 'Tagihan yang sudah dibayar tidak dapat dihapus.');
    }
    ```
    Tagihan yang berstatus `partial` (dibayar sebagian) atau `paid` (lunas) dikunci di tingkat aplikasi untuk melindungi riwayat kas.

### L — List (Daftar Data & Datatable)
*   **Pagination & Prevent N+1 Queries**: Seluruh daftar data menggunakan standard paginasi Laravel (`paginate(50)`) untuk mereduksi beban memori server. Query selalu mengikutsertakan relasi menggunakan eager loading, misalnya:
    ```php
    $query = Bill::with('student')->orderBy('period', 'desc');
    ```
*   **HMAC Blind Index Search**: Untuk kolom sensitif terenkripsi seperti NISN, pencarian data di [StudentController.php](file:///d:/laragon/www/simt-backend/Modules/Student/app/Http/Controllers/StudentController.php#L20-L28) tidak menggunakan kueri `LIKE %...%` langsung (karena data di database harus aman), melainkan melakukan pencarian eksak menggunakan indeks blind hash (`nisn_bindex`):
    ```php
    $hashedSearch = hash_hmac('sha256', $search, config('app.key'));
    $query->where('nisn_bindex', $hashedSearch);
    ```

### F — Filter (Penyaringan Konteks)
*   **Tenant Scoping**: Filter bawaan wajib di tingkat framework melalui global scope `BelongsToTenant`.
*   **Dynamic Request Filters**: Index views menerima parameter dinamis (`class_id`, `status`, `period`, `method`) yang secara fleksibel disatukan dalam query builder Eloquent.
*   **Cross-DB Date Standardization**: Kueri rentang tanggal disesuaikan agar kompatibel baik di MySQL maupun SQLite untuk kemudahan unit testing (misalnya menggunakan range kueri `whereBetween` alih-alih fungsi MySQL `DATE_FORMAT` yang tidak didukung SQLite).

### I — Import (Impor Data Masal)
Siklus impor data masal menggunakan pola **3-Step Import Wizard** yang kokoh, diterapkan pada [StudentImportService.php](file:///d:/laragon/www/simt-backend/app/Services/StudentImportService.php):

```
 ┌───────────────────────┐      ┌───────────────────────┐      ┌───────────────────────┐
 │   STEP 1: Upload      │      │   STEP 2: Preview     │      │   STEP 3: Commit      │
 ├───────────────────────┤      ├───────────────────────┤      ├───────────────────────┤
 │ - Baca file Excel     │      │ - Tampilkan baris     │      │ - Validasi Token Cache│
 │ - Validasi baris data │ ───&gt; │   sukses &amp; error      │ ───&gt; │ - Mulai DB Transaction│
 │ - Simpan di cache     │      │ - Pengguna meninjau   │      │ - Simpan, kirim WA    │
 │   (UUID Token, 30m)   │      │   data sebelum simpan │      │ - Hapus Cache         │
 └───────────────────────┘      └───────────────────────┘      └───────────────────────┘
```

1.  **Step 1: Upload & Validate**: Membaca file, melakukan validasi kelayakan NIS/Kelas/No Telepon, menandai baris error, dan menyimpan baris yang valid ke dalam Cache memory selama 30 menit berbasis UUID Token.
2.  **Step 2: Preview**: Menyajikan tabel pratinjau yang memisahkan baris sukses dengan baris gagal (lengkap dengan jenis kerusakannya, misal: "NIS sudah ada") agar pengguna dapat mengoreksi file Excel sebelum disimpan.
3.  **Step 3: Commit**: Ketika pengguna menekan tombol "Simpan", token dikirim ke server. Server membuka transaksi database tunggal (`DB::transaction`), mengimpor data, membuat akun wali secara otomatis, mengantrikan notifikasi WA, dan menghapus cache. Jika terjadi kegagalan sistem ditengah jalan, seluruh transaksi di-rollback secara utuh.

### X — Export (Ekspor Data)
*   **Excel Export**: Memanfaatkan pustaka `Maatwebsite\Excel` dengan membagi tanggung jawab penyiapan kueri ke dalam kelas eksportir khusus seperti `BillsRecapExport` dan `AttendanceRecapExport`.
*   **PDF Export**: Menggunakan wrapper `barryvdh/laravel-dompdf` untuk menghasilkan dokumen berformat A4 dengan tata letak portrait yang rapi (seperti pencetakan Rapor Siswa dan Kwitansi Pembayaran).

### Rep — Report (Pelaporan & Analisis)
*   **Dashboard Analytics**: Menyajikan data statistik ringkasan operasional. Contohnya pada dashboard keuangan, sistem menyajikan total pendapatan, total piutang tumpukan, rasio kelunasan pembayaran, serta grafik kas 5 pembayaran terbaru.
*   **Dynamic Scoring Models**: Modul Akademik menerapkan model pelaporan nilai rapor berbasis rumus bobot persentase:
    $$\text{Nilai Pengetahuan} = (25\% \times \text{Rata UH}) + (15\% \times \text{Rata Tugas}) + (30\% \times \text{UTS}) + (30\% \times \text{UAS})$$
    $$\text{Nilai Keterampilan} = (25\% \times \text{Rata UH}) + (40\% \times \text{Praktik}) + (10\% \times \text{Rata Tugas}) + (25\% \times \text{UAS})$$
    Sistem juga melakukan konversi nilai akhir menjadi predikat huruf (A, B, C, D) di tingkat server secara dinamis untuk menjamin keaslian data.

---

## 4. Evaluasi Estetika Antarmuka MVC UI/UX

Desain antarmuka SIMT Backend telah ditingkatkan untuk memberikan kesan modern, bersih, dan berkelas tinggi (*premium grade*), menjauhi tampilan bawaan AI yang kaku.

### A. Desain Premium Gelap (Premium Dark Mode Login)
Halaman [login.blade.php](file:///d:/laragon/www/simt-backend/resources/views/auth/login.blade.php) dirancang ulang total menggunakan:
*   **Background Glow Mesh**: Efek pendaran cahaya dinamis yang melayang di latar belakang gelap (*dark slate*).
*   **Interactive Ripple Buttons**: Tombol masuk yang mengeluarkan efek ripple saat diklik serta scale-up mikro-animasi pada hover.
*   **Autofill Demo Panel**: Panel interaktif untuk mengisi formulir secara instan menggunakan data akun demo terdaftar (Superadmin, Admin, Guru, Wali) untuk mempermudah evaluasi produk.

### B. Layout Kartu & Grid Modern (Card-based Layout)
Halaman manajemen data seperti edit nilai [show.blade.php](file:///d:/laragon/www/simt-backend/Modules/Akademik/resources/views/grades/show.blade.php) menggunakan pendekatan *glassmorphism* dan kartu bersih:
*   Penerapan kartu rounded tinggi (`rounded-2xl shadow-sm border border-slate-100`) untuk memisahkan fokus konten.
*   Tipografi menggunakan font modern *Plus Jakarta Sans* dengan hirarki teks yang jelas (kontras tinggi antara judul abu-abu tua `text-slate-900` dengan label abu-abu muda `text-slate-400 font-semibold uppercase tracking-wider`).
*   Form input didesain dengan latar belakang abu-abu ultra-terang (`bg-slate-50`) yang berubah menjadi putih bersih dengan border biru tebal saat aktif (`focus:bg-white focus:border-blue-500 transition duration-200`).

### C. Live Tracing & AJAX Polling
Untuk fitur real-time seperti WhatsApp log di halaman **WA Connect** dan **WA Tools**:
*   Sistem menghindari memuat ulang halaman secara kasar (*hard refresh*).
*   Menggunakan AJAX polling berdurasi 5 detik untuk memuat konten partial (`table-rows` dan `incoming-feed`) ke dalam DOM secara mulus.
*   Dilengkapi mockup smartphone interaktif yang secara instan memformat teks Markdown editor (EasyMDE) ke dalam balon pesan hijau WhatsApp asli secara real-time.

---

## 5. Kesimpulan & Rekomendasi Standardisasi

SIMT Backend berhasil menunjukkan arsitektur otorisasi RBAC dan manipulasi data CRUDLFIXRep yang solid dan siap produksi (*production-ready*). Namun, terdapat beberapa catatan evaluasi penting untuk peningkatan berkelanjutan:

1.  **Sentralisasi Otorisasi Kepemilikan (Ownership)**: Pengecekan relasi seperti "apakah kelas ini diampu guru ini" atau "apakah siswa ini anak wali ini" sebaiknya dipindahkan seluruhnya dari controller ke **Laravel Policies** agar lebih deklaratif dan tidak rentan terlewat pada endpoint baru.
2.  **Standardisasi Respon API**: Respons API pada `StudentApiController` dan `FinanceApiController` sudah sangat baik dengan pembungkus format helper standard. Konsistensi kode status HTTP (200, 201, 403, 422) harus dipertahankan di seluruh modul plugin baru.
3.  **UI/UX Konsistensi**: Gaya visual premium dark mode yang telah diterapkan pada halaman login dan WA Connect perlu diturunkan secara bertahap ke seluruh menu dashboard internal sekolah agar memiliki keselarasan identitas visual (*brand identity*) yang utuh.

---
*Dokumen Analisis CRUDLFIXRep & UI/UX disiapkan secara faktual berdasarkan analisis struktur source code per 16 Juni 2026.*
