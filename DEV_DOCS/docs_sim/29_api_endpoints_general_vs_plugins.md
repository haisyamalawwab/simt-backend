# Perancangan Detail: API Gateway & Kontrak Modul General vs Plug & Play
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 12:30 WIB  
**Disusun Oleh:** Senior Backend Engineer  

---

## 1. Pendahuluan
Dokumen ini merincikan spesifikasi *Endpoints API* (I/O Payload) berdasarkan ke-13 Modul yang dijabarkan dalam dokumen `Rancangan Fitur`. Pembagian API dipisahkan menjadi dua kategori besar: **General Modules (Wajib)** yang menjadi tulang punggung sekolah, dan **Plug & Play Modules (Opsional)** yang menjadi daya tarik komersial.

Setiap *request* di bawah ini diwajibkan menyertakan *header*:
- `Authorization: Bearer {token}`
- `X-Tenant-Domain: {tenant_domain}`

---

## 2. General Modules API (Wajib / Selalu Aktif)

### A. Modul Akademik / Kurikulum (Bab 1)

**1. GET `/api/v1/academic/lessons/schedule`**
- **Aktor:** Guru, Siswa, Orang Tua
- **Deskripsi:** Mendapatkan jadwal pelajaran. Jika diakses Guru, muncul jadwal dia mengajar. Jika diakses Siswa, muncul jadwal kelasnya.
- **Output:** Array berisikan hari, jam ke-, nama mapel, dan nama guru.

**2. POST `/api/v1/academic/teaching-journals`**
- **Aktor:** Guru Mapel
- **Deskripsi:** Mengisi Jurnal Mengajar Harian (terintegrasi dengan Modul Ajar).
- **Payload:**
  ```json
  {
    "schedule_id": 45,
    "tanggal": "2026-06-12",
    "materi": "Bab 2: Persamaan Kuadrat",
    "modul_ajar_id": 102, // Jika dikaitkan dengan Modul Ajar
    "hambatan": "Anak-anak kurang fokus karena jam terakhir.",
    "keterlaksanaan": "Selesai" // Selesai / Belum Selesai
  }
  ```

**3. GET `/api/v1/academic/assessments/report-card/{student_id}`**
- **Aktor:** Orang Tua, Siswa, Wali Kelas
- **Deskripsi:** Mengambil nilai e-Rapor akhir.
- **Output:** Objek berisi Data Diri, Nilai Sumatif per Mapel, Nilai Formatif, dan Status Naik Kelas.

### B. Modul Kesiswaan & SDM (Bab 2, 10, 11)

**1. POST `/api/v1/student-affairs/violations`**
- **Aktor:** Guru BK, Wali Kelas
- **Deskripsi:** Mencatat pelanggaran kedisiplinan dan poin minus.
- **Payload:**
  ```json
  {
    "student_id": 50,
    "kategori_pelanggaran_id": 3, // Misal: Membolos
    "poin_minus": 20,
    "tindak_lanjut": "Pemanggilan orang tua tahap 1"
  }
  ```

**2. GET `/api/v1/hr/staff-workloads`**
- **Aktor:** Kepala Madrasah, Waka
- **Deskripsi:** Melihat statistik beban jam mengajar guru (Dashboard SDM).

---

## 3. Plug & Play Modules API (Modul Opsional SaaS)

Modul-modul ini dilindungi oleh *Middleware* `module:nama_modul`. Jika sekolah tidak berlangganan, API akan mengembalikan `403 Forbidden`.

### A. Modul Tahfiz (Bab 3)
*Fokus: Memisahkan nilai teknis membaca (Tahsin) dengan ibadah harian (Ubudiyah).*

**1. POST `/api/v1/tahfiz/munaqosah/register`**
- **Aktor:** Guru Tahfiz
- **Deskripsi:** Mendaftarkan siswa untuk ujian tahfiz (Munaqosah).
- **Payload:**
  ```json
  {
    "student_id": 20,
    "kategori_ujian": "Juz 30",
    "penguji_id": 5, // ID Ustadz penguji
    "jadwal": "2026-06-20 08:00:00"
  }
  ```

### B. Modul E-Office / Administrasi Pimpinan (Bab 13)
*Fokus: Digitalisasi dokumen (paperless) dan Tanda Tangan Digital Kepala Madrasah.*

**1. POST `/api/v1/eoffice/documents/upload`**
- **Aktor:** Tata Usaha, Kepala Madrasah
- **Deskripsi:** Mengunggah arsip akreditasi, SK, atau sertifikat.
- **Payload (Form-Data):** `file (PDF)`, `kategori (Akreditasi/SK)`, `judul`.

**2. POST `/api/v1/eoffice/e-signature/sign`**
- **Aktor:** Kepala Madrasah
- **Deskripsi:** Membubuhkan *QR Code Signature* pada sebuah dokumen Rapor/Surat Tugas.
- **Payload:**
  ```json
  {
    "document_type": "rapor", // rapor, surat_keluar, sk
    "document_id": 105,
    "pin": "654321" // Divalidasi sebelum QR di-generate di PDF
  }
  ```

### C. Modul Inklusi & BK (Bab 7 & 9)
*Fokus: Penanganan khusus ABK dan psikologi siswa. Data ini highly-restricted.*

**1. POST `/api/v1/inclusion/ppi/evaluate`**
- **Aktor:** GPK Inklusi
- **Deskripsi:** Mengevaluasi Program Pembelajaran Individual tiap akhir bulan.
- **Payload:**
  ```json
  {
    "ppi_id": 15,
    "progress_percentage": 75,
    "catatan_evaluasi": "Siswa sudah bisa merespons nama saat dipanggil.",
    "rekomendasi_bulan_depan": "Latih kontak mata langsung."
  }
  ```

**2. POST `/api/v1/counseling/cases`**
- **Aktor:** Guru BK
- **Deskripsi:** Membuat rekam jejak konseling kasus anak.
- **Payload:**
  ```json
  {
    "student_id": 44,
    "jenis_kasus": "Bullying",
    "hasil_konseling": "Anak merasa tertekan oleh teman sekelas.",
    "referral_psikolog": true // Boolean rujukan ahli luar
  }
  ```

### D. Modul Perpustakaan (Bab 12)
*Fokus: Otomatisasi Sirkulasi.*

**1. POST `/api/v1/library/circulation/borrow`**
- **Aktor:** Pustakawan / Admin TU
- **Deskripsi:** Memproses peminjaman buku menggunakan ID buku (Barcode).
- **Payload:**
  ```json
  {
    "buku_id": 890,
    "peminjam_type": "student", // atau "teacher"
    "peminjam_id": 12,
    "tanggal_harus_kembali": "2026-06-19" // Otomatis +7 hari
  }
  ```

### E. Modul Keuangan (Bab 4)
*Fokus: Pemasukan uang SPP.*

**1. POST `/api/v1/finance/invoices/pay`**
- **Aktor:** Orang Tua (via Portal) atau Admin TU.
- **Deskripsi:** Input konfirmasi pembayaran (manual/transfer).
- **Payload:**
  ```json
  {
    "invoice_id": 450,
    "metode_pembayaran": "transfer_bank",
    "bank_tujuan": "BSI",
    "bukti_transfer_url": "https://storage.simt.id/receipts/xyz123.jpg"
  }
  ```

---

## 4. Mekanisme Notifikasi WhatsApp (Green API/Wablas)
Semua aksi kritis dari API General maupun Plug & Play akan memicu *Queue Job* asinkron untuk mengirim pesan WhatsApp ke Orang Tua.
**Trigger Points:**
- Siswa absen Alpa (`api/v1/academic/attendance`).
- SPP terbit (`api/v1/finance/invoices`).
- Panggilan BK (`api/v1/counseling/cases`).