# 📊 RENCANA IMPLEMENTASI: MODUL KEUANGAN (SPP), RIWAYAT PEMBAYARAN, DAN PERSYARATAN NON-FUNGSIONAL (NFR)

**Tanggal:** 16 Juni 2026  
**Waktu:** 09:34 WIB (Local Time)  
**Status:** Draft (Awaiting Approval)  
**Prioritas:** High  
**Nomor Dokumen:** 86_PLAN_DOCS_IMPLEMENTASI_FINANCE_DAN_NFR_2026-06-16_09-34  

---

## 1. Ringkasan Tujuan
Dokumen ini menyajikan analisis mendalam, desain arsitektur, dan langkah-langkah implementasi untuk:
1. Penyelesaian sisa fitur fungsional **Modul Keuangan (SPP)** (Fitur TU-05 & OR-04).
2. Sinkronisasi dokumen persyaratan untuk fungsionalitas **Superadmin** (SA-01, SA-02, SA-03).
3. Penerapan persyaratan Non-Fungsional (**Non-Functional Requirements / NFR**):
   * Enkripsi Data Sensitif (AES-256) untuk kepatuhan UU PDP.
   * Pencatatan Audit Log otomatis untuk aktivitas write/sensitif.
   * Konfigurasi Backup Otomatis Harian database di production.

---

## 2. Analisis Kesenjangan (Gap Analysis) & Kesiapan Kode

Berdasarkan pemeriksaan langsung pada repositori backend saat ini, berikut adalah status kesiapan kode dibandingkan dengan daftar tugas Sprint 5:

| ID | Fitur / Task | Status di Kode | Celah (Gap) & Rencana Tindakan | Kesiapan |
| :--- | :--- | :--- | :--- | :---: |
| **F-01** | Model `Bill`, `Payment`, & Migrasi | **Sudah Ada** | Tabel `bills` dan `payments` sudah dibuat di batch migrasi sebelumnya. Model `App\Models\Bill` dan `App\Models\Payment` sudah terdefinisi dengan relasi tenant. | 100% |
| **F-02** | CRUD Tagihan SPP | **Parsial** | Form mass-generate tagihan dan list filter sudah ada di `FinanceController`. Celah: Belum ada CRUD individual (tambah manual per siswa, edit nominal/jatuh tempo tagihan tertentu, atau hapus tagihan jika salah generate). | 60% |
| **F-03** | Pencatatan Pembayaran + Validasi | **Sudah Ada** | Rute `finance.payment.store` (`FinanceController@recordPayment`) dan modal bayar di `bills.blade.php` sudah berfungsi dengan validasi nominal, metode (cash/transfer), dan no kwitansi otomatis. | 100% |
| **F-04** | Kwitansi PDF (DomPDF) | **Sudah Ada** | Cetak kwitansi via `FinanceController@printReceipt` memanggil view `finance::receipt` menggunakan DomPDF. Celah minor: layout view `receipt.blade.php` masih menggunakan Flexbox CSS yang kurang didukung dengan baik oleh DomPDF, perlu direfaktor ke Table Layout agar posisinya konsisten. | 90% |
| **F-05** | Laporan Rekap Penerimaan Bulanan (Excel + PDF) | **Parsial** | Ekspor Excel via `BillsRecapExport` sudah selesai. Celah: Rekapitulasi bulanan dalam format PDF untuk Kepala Madrasah/Bendahara belum diimplementasikan. | 70% |
| **F-06** | Dashboard Keuangan Sederhana | **Belum Ada** | Belum ada tampilan dashboard ringkasan kas masuk, kas tertunggak, dan rasio lunas bulan berjalan. | 0% |
| **F-07** | Hook Notifikasi WA | **Parsial** | Pengiriman WA pengingat tagihan massal (`SendWaNotification`) sudah terintegrasi. Celah: Notifikasi WA konfirmasi pembayaran berhasil (kwitansi digital) ke wali murid setelah pembayaran dicatat belum ada. | 50% |
| **F-08** | Testing & Integrasi Modul Student | **Sudah Ada** | Test suite `Tests\Feature\FinanceModuleTest` lengkap dengan 9 test cases isolasi tenant, gating modul, dan pembayaran. | 100% |

---

## 3. Rincian Perubahan yang Diusulkan (Proposed Changes)

### 3.1 CRUD Tagihan SPP Individual (F-02)
Kami akan menambahkan modal dan aksi pada halaman `bills.blade.php` untuk:
* Membuat tagihan baru secara individual untuk satu siswa tertentu.
* Mengedit tagihan yang berstatus `unpaid` (untuk mengubah nominal atau tanggal jatuh tempo).
* Menghapus tagihan jika terjadi kesalahan input/generate.

**File yang dimodifikasi:**
* `Modules/Finance/routes/web.php`
* `Modules/Finance/app/Http/Controllers/FinanceController.php`
* `Modules/Finance/resources/views/bills.blade.php`

### 3.2 Laporan Keuangan SPP & Riwayat Pembayaran (TU-05, OR-04, F-05, F-06)
Kami akan membuat sub-fitur laporan dan dashboard keuangan di dalam modul Finance:
* **Dashboard Keuangan:** Menampilkan kartu metrik kas terkumpul, sisa piutang/tunggakan, dan rasio efektivitas penagihan bulanan.
* **Riwayat Pembayaran Global:** Halaman log transaksi harian (`/finance/payments`) untuk menampilkan daftar pembayaran kronologis dari semua siswa, lengkap dengan pencatatnya dan tombol cetak ulang kwitansi.
* **Ekspor PDF Rekapitulasi Bulanan:** Cetak ringkasan kas bulanan berformat PDF yang bersih dan formal (menggunakan Table Layout murni demi kestabilan rendering DomPDF).

**File yang dimodifikasi / dibuat:**
* [NEW] `Modules/Finance/resources/views/reports.blade.php`
* [NEW] `Modules/Finance/resources/views/payments_history.blade.php`
* [NEW] `Modules/Finance/resources/views/exports/recap_pdf.blade.php`
* `Modules/Finance/app/Http/Controllers/FinanceController.php`
* `Modules/Finance/routes/web.php`

### 3.3 Enkripsi Data Sensitif (NFR-Sec-01)
Untuk mematuhi UU Perlindungan Data Pribadi (UU PDP):
* Kami mengusulkan enkripsi at-rest pada kolom alamat (`address`) di model `Student` menggunakan `encrypted` model cast Laravel.
* **Analisis & Mitigasi:** Kolom nomor telepon (`phone`) pada model `User` akan tetap disimpan dalam plain text demi kelancaran pencarian login credentials dan integrasi cepat dengan WA Gateway.

**File yang dimodifikasi:**
* `app/Models/Student.php` (tambahkan cast `address => encrypted`)

### 3.4 Sistem Audit Log Otomatis (NFR-Sec-02)
Kami akan mengimplementasikan pencatatan log aktivitas write untuk mendeteksi perubahan data.
* **Membuat tabel database `audit_logs`:**
  ```php
  Schema::create('audit_logs', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('tenant_id')->nullable();
      $table->unsignedBigInteger('user_id')->nullable();
      $table->string('event'); // created, updated, deleted, login
      $table->string('auditable_type');
      $table->unsignedBigInteger('auditable_id');
      $table->json('old_values')->nullable();
      $table->json('new_values')->nullable();
      $table->string('ip_address', 45)->nullable();
      $table->string('user_agent', 255)->nullable();
      $table->timestamp('created_at')->useCurrent();
  });
  ```
* **Membuat Trait `App\Traits\Auditable`:** Trait ini akan secara otomatis mendengarkan model event (booting) dan menyimpan rekam jejak payload ke tabel `audit_logs`.

**File yang dimodifikasi / dibuat:**
* [NEW] `database/migrations/2026_06_16_000001_create_audit_logs_table.php`
* [NEW] `app/Traits/Auditable.php`
* `app/Models/Student.php` (memakai trait `Auditable`)
* `app/Models/Bill.php` (memakai trait `Auditable`)
* `app/Models/Payment.php` (memakai trait `Auditable`)

### 3.5 Backup Otomatis Harian (NFR-Ops-01)
Kami akan mengaktifkan fitur backup otomatis database ke penyimpanan lokal/object storage.
* **Artisan Command `simt:backup-db`:** Command ini akan mengeksekusi utility `mysqldump` terkompresi `.sql.gz` dan menyimpannya di folder `storage/app/backups`.
* **Pruning:** Menghapus file backup lama yang berusia >14 hari secara otomatis untuk menghemat ruang disk.
* **Scheduler:** Menjadwalkan tugas backup harian pada pukul 02:00 WIB.

**File yang dimodifikasi / dibuat:**
* [NEW] `app/Console/Commands/BackupDatabase.php`
* `app/Console/Kernel.php` (mendaftarkan schedule backup)

### 3.6 Sinkronisasi Requirement Document (SA-01 sd SA-03)
Menambahkan requirement Superadmin fungsional ke dokumen prasyarat:
* **SA-01:** Registrasi / Onboarding Tenant Baru (CRUD Tenant & modul default).
* **SA-02:** Manajemen Status & Subskripsi Modul (Aktivasi/Suspensi subskripsi tenant & modul).
* **SA-03:** Dashboard Pemantauan Global (Lintas tenant statistik pengguna & database).

**File yang dimodifikasi:**
* `DEV_DOCS/docs_sim/05_requirements_srs.md`
* `DEV_DOCS/docs_sim/38_requirements_mvp.md`

---

## 4. Estimasi Kerja Realistis (Sprint 5)

| Task ID | Nama Kegiatan / Fitur | Estimasi Awal | Estimasi Sisa Kerja (Jam) | Keterangan / Justifikasi |
| :--- | :--- | :---: | :---: | :--- |
| **F-01** | Buat model Bill & Payment + migration | 6 | **0** | Sudah selesai 100%. |
| **F-02** | CRUD Tagihan SPP (Fitur edit & hapus individual) | 10 | **4** | Sisa implementasi form edit nominal/jatuh tempo dan aksi hapus manual. |
| **F-03** | Form pencatatan pembayaran + validasi | 8 | **0** | Sudah selesai 100%. |
| **F-04** | Generate kwitansi PDF | 6 | **2** | Refaktorisasi layout PDF kwitansi dari Flexbox ke Table Layout untuk kompatibilitas DomPDF. |
| **F-05** | Laporan rekap penerimaan bulanan (Excel + PDF) | 8 | **4** | Sisa pembuatan visual laporan PDF rekap penerimaan per bulan. |
| **F-06** | Dashboard keuangan sederhana | 7 | **4** | Pembuatan view ringkasan total penerimaan vs tunggakan berjalan. |
| **F-07** | Hook notifikasi WA saat pembayaran berhasil | 6 | **3** | Pembuatan notifikasi WA otomatis konfirmasi sukses bayar ke wali. |
| **F-08** | Testing & integrasi modul | 4 | **2** | Menambahkan test case baru untuk Audit Log, Enkripsi, dan Laporan. |
| **NFR-1** | Audit Log & Enkripsi data PII | - | **4** | Pembuatan tabel `audit_logs`, Trait `Auditable`, dan setup `encrypted` cast. |
| **NFR-2** | Setup Backup Otomatis | - | **3** | Pembuatan Artisan command `simt:backup-db` dan setup scheduler harian. |
| **DOC-1** | Sinkronisasi Requirement Document (SA-01 sd SA-03) | - | **1** | Update berkas `.md` dokumen persyaratan di folder `DEV_DOCS`. |
| | **TOTAL** | **49** | **24 jam** | **Dapat diselesaikan dalam 3-4 hari kerja.** |

---

## 5. Rencana Verifikasi

### 5.1 Pengujian Otomatis
1. Menjalankan migrasi baru untuk audit logs:
   ```powershell
   php83 artisan migrate
   ```
2. Menjalankan test suite untuk memastikan tidak ada regresi:
   ```powershell
   php83 artisan test
   ```
3. Menulis test case baru khusus untuk memverifikasi fungsionalitas audit logs dan enkripsi field model.

### 5.2 Pengujian Manual
1. Merekam transaksi pembayaran SPP dan memverifikasi data perubahan terekam sempurna di tabel `audit_logs`.
2. Mengecek apakah field `address` di database `students` tersimpan dalam bentuk string terenkripsi.
3. Menjalankan command `php83 artisan simt:backup-db` secara manual untuk memastikan file `.sql.gz` terbentuk di `storage/app/backups/`.
