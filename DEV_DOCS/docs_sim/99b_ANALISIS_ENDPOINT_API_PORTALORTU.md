Saya akan memeriksa endpoint API yang ada untuk dukungan portal orang tua, khususnya untuk data per-siswa (nilai/absensi/SPP).

Baik, saya akan menganalisis endpoint API yang sudah ada dan memberikan ringkasan untuk dukungan aplikasi simt-portalortu.

## 📋 Analisis Endpoint API untuk Portal Orang Tua

### ✅ Endpoint yang SUDAH TERSEDIA:

#### **1. Autentikasi**
- ✅ `POST /api/v1/auth/parent-login` - Login wali murid
- ✅ `POST /api/v1/auth/student-login` - Login siswa
- ✅ `GET /api/v1/me` - Info user/siswa yang login
- ✅ `GET /api/v1/me/children` - Daftar anak kandung (untuk wali)
- ✅ `POST /api/v1/logout` - Logout

#### **2. Dashboard & Data Siswa**
- ✅ `GET /api/v1/portal/students/{student}/dashboard` - Dashboard lengkap untuk wali
  - Profile siswa
  - Ringkasan presensi (bulan aktif)
  - Ringkasan nilai (dengan filter tipe: PENGETAHUAN/KETERAMPILAN/UTS/UAS/SIKAP)
  - Ringkasan pembayaran SPP
  - Pengumuman sekolah

- ✅ `GET /api/v1/portal/students/{student}/student-dashboard` - Dashboard extended untuk siswa
  - Semua data di atas PLUS:
  - Jadwal pelajaran
  - Pelanggaran (violations)
  - Prestasi (achievements)
  - Progres Tahfiz (jika modul aktif)

#### **3. Detail Nilai Per Mata Pelajaran**
- ✅ `GET /api/v1/portal/students/{student}/subjects/{subject}/grade-details`
  - Detail nilai per kategori (TUGAS, HARIAN, UTS, UAS, AKHIR)
  - Rata-rata per kategori

#### **4. Presensi (dari AttendanceApiController)**
- ✅ `GET /api/v1/students/{student}/attendance` (sudah ada, tapi belum terdaftar di routes)
  - Filter by month (format: YYYY-MM)
  - Status presensi harian dengan label

#### **5. Tagihan SPP (dari StudentApiController)**
- ✅ `GET /api/v1/students/{student}/bills` (sudah ada, tapi belum terdaftar di routes)
  - Daftar tagihan dengan status
  - Remaining amount

---

### 🔴 Endpoint yang BELUM TERDAFTAR di routes/api.php:

Beberapa endpoint sudah dibuat di Controller tapi **belum terdaftar di routes**:

```php
// AttendanceApiController::index
GET /api/v1/students/{student}/attendance

// StudentApiController::bills  
GET /api/v1/students/{student}/bills
```

---

### 📊 Ringkasan Cakupan Data Per Siswa:

| Fitur | Endpoint | Status | Catatan |
|-------|----------|--------|---------|
| **Login Wali** | ✅ Ada | ✅ Terdaftar | `POST /api/v1/auth/parent-login` |
| **Login Siswa** | ✅ Ada | ✅ Terdaftar | `POST /api/v1/auth/student-login` |
| **Daftar Anak** | ✅ Ada | ✅ Terdaftar | `GET /api/v1/me/children` |
| **Dashboard Wali** | ✅ Ada | ✅ Terdaftar | `GET /api/v1/portal/students/{id}/dashboard` |
| **Dashboard Siswa** | ✅ Ada | ✅ Terdaftar | `GET /api/v1/portal/students/{id}/student-dashboard` |
| **Detail Nilai** | ✅ Ada | ✅ Terdaftar | `GET /api/v1/portal/students/{id}/subjects/{subjectId}/grade-details` |
| **Presensi Bulanan** | ✅ Ada | ❌ **Belum Terdaftar** | Perlu tambah route |
| **Tagihan SPP** | ✅ Ada | ❌ **Belum Terdaftar** | Perlu tambah route |

---

### 🎯 Rekomendasi Tindakan:

#### **Option 1: Registrasi endpoint yang belum terdaftar (MINIMAL)**

Tambahkan 2 route berikut ke `routes/api.php`:

```php
Route::middleware(['auth:sanctum', IdentifyTenant::class, 'check.tenant.access'])->group(function () {
    // ... existing routes ...
    
    // Presensi per siswa
    Route::get('/v1/students/{student}/attendance', 
        [AttendanceApiController::class, 'index']);
    
    // Tagihan per siswa  
    Route::get('/v1/students/{student}/bills', 
        [StudentApiController::class, 'bills']);
});
```

#### **Option 2: Gunakan endpoint dashboard yang sudah lengkap (REKOMENDASI)**

Endpoint dashboard (`/dashboard` dan `/student-dashboard`) **sudah menyediakan semua data** yang dibutuhkan:
- Presensi bulan aktif (dengan smart fallback)
- Nilai dengan filter tipe
- Pembayaran SPP
- Plus jadwal, pelanggaran, prestasi, tahfiz

**Kelebihan:**
- ✅ Single request untuk semua data
- ✅ Sudah terdaftar dan berfungsi
- ✅ Performance lebih baik (mengurangi jumlah request)
- ✅ Data konsisten dalam satu transaksi

**Aplikasi simt-portalortu bisa:**
1. Load dashboard sekali saat login
2. Cache data lokal
3. Refresh on-demand atau pull-to-refresh

---

### 📝 Kesimpulan:

**Status: SUDAH SIAP untuk simt-portalortu ✅**

Backend Laravel sudah menyediakan endpoint lengkap per-siswa untuk:
- ✅ Nilai (dengan detail breakdown)
- ✅ Absensi (dengan smart period selection)
- ✅ SPP (dengan status pembayaran)
- ✅ Plus bonus: jadwal, pelanggaran, prestasi, tahfiz

**Yang perlu dilakukan:**
1. ✅ **TIDAK ADA** - Endpoint dashboard sudah lengkap dan terdaftar
2. ⚠️ **OPSIONAL** - Register 2 endpoint granular (attendance & bills) jika apps butuh endpoint terpisah

**Rekomendasi:**
Gunakan endpoint dashboard yang sudah ada karena sudah optimal dan lengkap. Hanya tambahkan endpoint granular jika ada kebutuhan spesifik untuk load partial data.

---

Apakah Anda ingin saya **registrasi 2 endpoint yang belum terdaftar** (attendance & bills), atau sudah cukup dengan endpoint dashboard yang ada?