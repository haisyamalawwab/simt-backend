# 📊 LAPORAN PENGEMBANGAN: ROLLBACK ENKRIPSI PII, RESTABILISASI MODEL CASTS, DAN IMPLEMENTASI GRACEFUL 403

**Tanggal:** 16 Juni 2026  
**Waktu:** 11:23 WIB (Local Time)  
**Status:** Completed  
**Nomor Dokumen:** 89_DEV_REPORT_SUPERADMIN_AUDIT_LOG_PII_ROLLBACK_AND_GRACEFUL_403_COMPLETED_2026-06-16  

---

## 1. Ringkasan Aktivitas Hari Ini

Hari ini, kami berfokus pada kelanjutan Sprint 5, penyesuaian arsitektur keamanan data untuk MVP, restabilisasi model-model Eloquent pasca-rekonstruksi casting, dan peningkatan kualitas User Experience (UX) saat menghadapi pembatasan hak akses (403 Forbidden). 

Seluruh rintangan teknis berupa pengecualian basis data (`QueryException`) dan kesalahan manipulasi waktu (`Carbon` on string) telah berhasil diselesaikan secara tuntas. Seluruh test suite (58 pengujian, 163 asersi) kini berjalan normal dan lulus 100%.

---

## 2. Rincian Pekerjaan & Kronologi Teknis

### 2.1 Implementasi Awal & Rencana Kerja (10:10 WIB)
* Berdasarkan dokumen [88_PLAN_DOCS_SUPERADMIN_AUDIT_LOG_ENCRYPTION_AND_BACKUP_2026-06-16_10-10.md](file:///d:/laragon/www/simt-backend/DEV_DOCS/docs_sim/88_PLAN_DOCS_SUPERADMIN_AUDIT_LOG_ENCRYPTION_AND_BACKUP_2026-06-16_10-10.md), kami merancang penambahan fitur Superadmin, Audit Log UI, Enkripsi PII Student (dengan blind index), dan Spatie Backup.

### 2.2 Penyesuaian Kebijakan Enkripsi MVP (Rollback)
* **Latar Belakang:** Penerapan enkripsi PII kompleks menggunakan custom cast (`app/Casts/EncryptedDate.php` dan `encrypted`) menimbulkan kendala `DecryptException` pada data lama yang belum terenkripsi. Selain itu, enkripsi mempersulit pengujian kueri langsung pada basis data (tidak human-readable) yang krusial selama fase MVP.
* **Tindakan Rollback:**
  1. Menghapus file migrasi enkripsi `2026_06_16_000002_encrypt_student_sensitive_fields.php`.
  2. Menghapus direktori `app/Casts` secara menyeluruh.
  3. Mengembalikan struktur model `Student` ke mode normal (menyimpan data sebagai plain text).
  4. Menghapus listener blind index pada event saving `Student`.

### 2.3 Restabilisasi Eloquent Casts & Perbaikan Bug Berantai
Penghapusan seluruh cast properti berimbas pada hilangnya konfigurasi type casting bawaan Laravel. Hal ini menyebabkan kegagalan berantai pada integrasi query dan format visual:

1. **Kesalahan Konversi Array ke String (`QueryException`):**
   * *Masalah:* Menyimpan data array (seperti `payload` di `wa_notifications` dan `old_values`/`new_values` di `audit_logs`) memicu error query PDO karena Laravel mencoba mengirim PHP array mentah tanpa diserialisasi ke JSON.
   * *Solusi:* Mengembalikan properti bawaan `$casts` untuk field bertipe JSON/array (`'payload' => 'array'` pada `WaNotification` dan `'old_values' => 'array'`, `'new_values' => 'array'` pada `AuditLog`).

2. **Kesalahan Call Method Carbon Pada String (`Error: Call to a member function format() on string`):**
   * *Masalah:* Field tanggal (seperti `birth_date` pada `Student`, `date` pada `Attendance`) diakses sebagai string biasa alih-alih objek Carbon, sehingga pemanggilan `->format()` dan `->setTimezone()` di view dan controller patah.
   * *Solusi:* Mengembalikan standard casts bawaan Laravel (`'birth_date' => 'date'` pada `Student`, `'date' => 'date:Y-m-d'` pada `Attendance`, serta field-field pada `Bill`, `Payment`, `SchoolYear`, `Tenant`, dan `TenantModule`).

3. **Gagal Simpan Deskripsi Nilai (`Grade`):**
   * *Masalah:* Test `guru_can_save_mass_grades` gagal karena nilai deskripsi disimpan sebagai `null` di database.
   * *Solusi:* Menambahkan kembali kolom `'description'` ke dalam properti `$fillable` pada model `Grade.php` agar dapat diisi saat pemanggilan `Grade::create()` / `Grade::update()`.

### 2.4 Peningkatan Tampilan Keamanan (Graceful 403 Page)
* **Masalah:** Halaman error 403 bawaan Laravel/Spatie terkesan kaku dan menakutkan bagi pengguna awam ("User does not have the right permissions.").
* **Solusi:** Membuat custom view mandiri di [403.blade.php](file:///d:/laragon/www/simt-backend/resources/views/errors/403.blade.php):
  * **Salinan Lebih Manusiawi:** Menggunakan pendekatan bersahabat ("Akses Terbatas: Halaman Memerlukan Akses Khusus") dan memberi penjelasan informatif alih-alih bahasa sistem yang kaku.
  * **Desain Visual Estetis:** Dibangun dengan Tailwind CSS, font *Plus Jakarta Sans*, warna latar gradient lembut (indigo/blue/slate), serta dekorasi glassmorphism.
  * **Mikro-Animasi:** Animasi melayang (*floating*) pada ilustrasi kunci emas SVG dan ring pulsing yang membuatnya terlihat premium dan hidup.
  * **Fungsionalitas Navigasi:** Tombol interaktif dinamis yang mendeteksi status `@auth` untuk memandu pengguna kembali ke Dashboard atau Beranda utama, serta opsi "Kembali ke Halaman Sebelumnya".

---

## 3. Daftar File yang Diperbarui

Berikut adalah ringkasan repositori file yang dibuat/diubah hari ini:

| File Path | Perubahan / Tindakan |
| :--- | :--- |
| **[Announcement.php](file:///d:/laragon/www/simt-backend/app/Models/Announcement.php)** | Restorasi standard casts (`is_pinned`, `published_at`, `expires_at`). |
| **[Attendance.php](file:///d:/laragon/www/simt-backend/app/Models/Attendance.php)** | Restorasi standard casts (`date`, `arrival_time`). |
| **[AuditLog.php](file:///d:/laragon/www/simt-backend/app/Models/AuditLog.php)** | Restorasi standard casts (`old_values`, `new_values`, `created_at`). |
| **[Bill.php](file:///d:/laragon/www/simt-backend/app/Models/Bill.php)** | Restorasi standard casts (`amount`, `paid_amount`, `discount`, `due_date`). |
| **[Grade.php](file:///d:/laragon/www/simt-backend/app/Models/Grade.php)** | Restorasi standard casts (`score`) & memasukkan `'description'` ke `$fillable`. |
| **[Invoice.php](file:///d:/laragon/www/simt-backend/app/Models/Invoice.php)** | Restorasi standard casts (`amount`, `paid_at`). |
| **[Payment.php](file:///d:/laragon/www/simt-backend/app/Models/Payment.php)** | Restorasi standard casts (`amount`, `payment_date`). |
| **[SchoolYear.php](file:///d:/laragon/www/simt-backend/app/Models/SchoolYear.php)** | Restorasi standard casts (`start_date`, `end_date`, `is_active`). |
| **[Student.php](file:///d:/laragon/www/simt-backend/app/Models/Student.php)** | Restorasi standard cast (`birth_date`), pembersihan custom encryption casts. |
| **[Tenant.php](file:///d:/laragon/www/simt-backend/app/Models/Tenant.php)** | Restorasi standard casts (`settings`, `activated_at`, `grace_until`). |
| **[TenantModule.php](file:///d:/laragon/www/simt-backend/app/Models/TenantModule.php)** | Restorasi standard casts (`active`, `active_until`). |
| **[User.php](file:///d:/laragon/www/simt-backend/app/Models/User.php)** | Restorasi standard casts (`email_verified_at`, `password`, `is_active`, `last_login_at`). |
| **[WaNotification.php](file:///d:/laragon/www/simt-backend/app/Models/WaNotification.php)** | Restorasi standard casts (`payload`, `attempts`, `sent_at`). |
| **[403.blade.php](file:///d:/laragon/www/simt-backend/resources/views/errors/403.blade.php)** | **[NEW]** Halaman kustom 403 Forbidden dengan gaya modern premium. |
| **[FinanceModuleTest.php](file:///d:/laragon/www/simt-backend/tests/Feature/FinanceModuleTest.php)** | Mengubah `student_address_is_encrypted_in_database` menjadi `student_address_is_stored_in_plain_text` untuk validasi penyimpanan plain text di database. |
| **[connect.blade.php](file:///d:/laragon/www/simt-backend/Modules/Notification/resources/views/connect.blade.php)** | Patch parsing string Carbon (`\Carbon\Carbon::parse`). |
| **[incoming-feed.blade.php](file:///d:/laragon/www/simt-backend/Modules/Notification/resources/views/partials/incoming-feed.blade.php)** | Patch parsing string Carbon (`\Carbon\Carbon::parse`). |
| **[table-rows.blade.php](file:///d:/laragon/www/simt-backend/Modules/Notification/resources/views/partials/table-rows.blade.php)** | Patch parsing string Carbon (`\Carbon\Carbon::parse`). |
| **[audit_logs.blade.php](file:///d:/laragon/www/simt-backend/Modules/Core/resources/views/super/audit_logs.blade.php)** | Patch parsing string Carbon (`\Carbon\Carbon::parse`). |

---

## 4. Hasil Verifikasi & Pengujian

Kami menjalankan pengujian penuh pada backend menggunakan PHP 8.3 CLI:
```powershell
php83 artisan test
```

### Hasil Akhir Uji:
* **Total Pengujian:** 58 Passed
* **Total Asersi:** 163 Passed
* **Status Build:** 🟢 Green / Success

Sistem saat ini dalam keadaan sangat stabil, aman, dan siap untuk tahap pengembangan/peninjauan berikutnya.
