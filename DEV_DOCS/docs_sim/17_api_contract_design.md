# Desain Kontrak API (API Contract Design)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Senior Backend Developer  

---

## 1. Standar Umum (API Standard)

- **Base URL:** `https://api.simt.school.id/api/v1`
- **Format Pertukaran Data:** `application/json`
- **Autentikasi:** Laravel Sanctum (Menggunakan header `Authorization: Bearer <token>` atau HTTP-Only Cookies untuk klien SPA).
- **Pagination:** Standar format Laravel Pagination (`current_page`, `data`, `first_page_url`, `last_page`, `total`).

### 1.1. Standar Respons Berhasil (Success Format)
```json
{
  "success": true,
  "message": "Pesan keberhasilan (opsional)",
  "data": { ... } // Berisi objek atau array data
}
```

### 1.2. Standar Respons Gagal (Error Format)
```json
{
  "success": false,
  "message": "Pesan utama error",
  "errors": {
      "field_name": ["Detail pesan error validasi"]
  } // Terutama untuk HTTP 422
}
```

### 1.3. HTTP Status Codes
- `200 OK` - Berhasil mengambil atau memperbarui data.
- `201 Created` - Entitas baru berhasil dibuat (POST).
- `204 No Content` - Berhasil menghapus data (DELETE).
- `400 Bad Request` - Format permintaan salah.
- `401 Unauthorized` - Token tidak valid atau tidak ada (belum login).
- `403 Forbidden` - User sudah login, tapi tidak punya hak akses (RBAC).
- `404 Not Found` - Endpoint atau ID entitas tidak ditemukan.
- `422 Unprocessable Entity` - Gagal validasi input (Validation Error).
- `500 Internal Server Error` - Terjadi bug di sisi server.

---

## 2. Modul Autentikasi & Profil

### 2.1. POST `/auth/login`
**Deskripsi:** Mendapatkan token autentikasi.
**Request Body:**
```json
{
  "email": "guru@mts.sch.id",
  "password": "password123",
  "device_name": "Chrome_Windows" // Opsional untuk token based
}
```
**Response (200 OK):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "1|abcdefg...",
    "user": {
      "id": 1,
      "name": "Ahmad Guru",
      "email": "guru@mts.sch.id",
      "role": "guru_mapel"
    }
  }
}
```

### 2.2. POST `/auth/logout`
**Deskripsi:** Mencabut (revoke) token pengguna saat ini.
**Header:** `Authorization: Bearer <token>`
**Response:** `200 OK`

### 2.3. GET `/auth/me`
**Deskripsi:** Mengambil detail profil dan *permissions* pengguna yang sedang login.

---

## 3. Modul Manajemen Data Master

### 3.1. GET `/students`
**Deskripsi:** Mengambil daftar siswa dengan pagination, *search*, dan *filter*.
**Query Params:** `?page=1&search=budi&kelas_id=2`
**Response (200 OK):** Mengembalikan data paginate standar Laravel.

### 3.2. POST `/students`
**Deskripsi:** Mendaftarkan siswa baru.
**Request Body:**
```json
{
  "nisn": "0012345678",
  "name": "Budi Santoso",
  "gender": "L",
  "birth_date": "2010-05-15",
  "parent_name": "Bapak Budi",
  "parent_phone": "08123456789"
}
```

### 3.3. GET `/classes`
**Deskripsi:** Mengambil daftar rombongan belajar (Rombel).

### 3.4. POST `/classes/{id}/assign-students`
**Deskripsi:** Memasukkan array ID siswa ke dalam sebuah kelas.
**Request Body:**
```json
{
  "student_ids": [15, 20, 22]
}
```

---

## 4. Modul Akademik & Penilaian

### 4.1. GET `/attendance/classes/{class_id}?date=2026-07-20`
**Deskripsi:** Mengambil daftar absensi siswa di suatu kelas pada tanggal tertentu. Jika belum diabsen, kembalikan daftar siswa dengan status default (H).

### 4.2. POST `/attendance/classes/{class_id}`
**Deskripsi:** Menyimpan presensi harian (Mass Update).
**Request Body:**
```json
{
  "date": "2026-07-20",
  "attendances": [
    {"student_id": 1, "status": "H"},
    {"student_id": 2, "status": "S", "note": "Surat dokter"}
  ]
}
```

### 4.3. POST `/assessments/scores`
**Deskripsi:** Menginput nilai formatif/sumatif (Mass Update).
**Request Body:**
```json
{
  "class_id": 2,
  "subject_id": 5,
  "assessment_type": "formatif", // atau 'sumatif'
  "name": "Ulangan Harian 1",
  "scores": [
    {"student_id": 1, "score": 85},
    {"student_id": 2, "score": 70}
  ]
}
```

---

## 5. Modul Keuangan (SPP)

### 5.1. POST `/billing/generate`
**Deskripsi:** Membuat tagihan otomatis untuk 1 kelas di bulan tertentu.
**Request Body:**
```json
{
  "class_id": 2,
  "month": 7,
  "year": 2026,
  "amount": 100000,
  "description": "SPP Bulan Juli"
}
```

### 5.2. POST `/billing/payments`
**Deskripsi:** TU/Kasir menginput pembayaran manual (tunai).
**Request Body:**
```json
{
  "bill_id": 145,
  "payment_method": "tunai",
  "amount_paid": 100000
}
```

---

## 6. Modul Portal Orang Tua

*(Endpoint publik / read-only terbatas untuk wali murid, menggunakan kredensial NISN & PIN).*

### 6.1. GET `/parent-portal/attendance`
**Deskripsi:** Mengambil rekap kehadiran anak sebulan terakhir.

### 6.2. GET `/parent-portal/billing`
**Deskripsi:** Melihat tagihan belum lunas anak.

---
*Catatan untuk Dev Frontend: Gunakan Axios Interceptor untuk otomatis menangkap status `401 Unauthorized` dan mengarahkan pengguna (redirect) kembali ke halaman `/login` serta menghapus token/cookie.*