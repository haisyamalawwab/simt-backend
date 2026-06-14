# Dokumen Strategi Pengujian (Test Strategy)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Lead Quality Assurance / Senior Developer  

---

## 1. Pendahuluan
Dokumen ini menguraikan strategi pengujian untuk memastikan SIMT MTs (SaaS MVP) memenuhi standar kualitas, fungsionalitas, dan performa sebelum *go-live*. Mengingat tenggat waktu pengembangan yang ketat (3 bulan) dan budget minimalis, pendekatan pengujian difokuskan pada **Risk-Based Testing** (pengujian berbasis risiko) pada modul-modul paling kritis (Autentikasi, Manajemen Nilai, dan SPP).

## 2. Tujuan Pengujian
1. Memastikan fitur inti (MVP) berjalan dengan lancar tanpa *blocker*.
2. Memverifikasi bahwa aturan bisnis (contoh: kalkulasi nilai akhir, validasi pembayaran SPP) akurat 100%.
3. Mencegah *regression bugs* saat rilis berkala dengan *automated testing* untuk backend.
4. Menyiapkan sistem yang ramah pengguna yang diverifikasi melalui UAT (User Acceptance Testing) oleh pengguna nyata di sekolah.

---

## 3. Tingkatan Pengujian (Test Levels)

Karena ini proyek *lean*, kita akan membaginya ke dalam 3 level prioritas:

### 3.1. Unit Testing (Automated - Developer)
- **Fokus:** Menguji logika bisnis terisolasi (Service classes, Helper, Kalkulator nilai).
- **Tools:** Pest PHP / PHPUnit (Backend). Vitest (Frontend - opsional untuk MVP, difokuskan pada utility).
- **Cakupan Minimal:** 
  - Formula perhitungan nilai Rapor (Formatif + Sumatif).
  - Pengecekan status tagihan SPP.
  - Role & Permission Matrix middleware.

### 3.2. Integration & Feature Testing (Automated - Developer)
- **Fokus:** Menguji interaksi antar komponen (API request ke Database, respons HTTP API).
- **Tools:** Pest PHP Feature Tests (menggunakan In-Memory SQLite atau Testing Database di PostgreSQL).
- **Cakupan Minimal:**
  - Alur otentikasi (Login berhasil/gagal, proteksi rute).
  - CRUD Siswa & Guru (Pembuatan entitas beserta relasinya).
  - Proses input nilai oleh Guru (validasi range nilai 0-100).
  - End-to-end pembuatan tagihan bulanan SPP otomatis.

### 3.3. Manual Exploratory Testing & E2E (Manual - QA/Dev)
- **Fokus:** Pengalaman pengguna (UI/UX), bug visual pada browser, serta *cross-device layout* (terutama untuk Parent Portal yang sering diakses di Mobile).
- **Prosedur:**
  - Menggunakan matriks browser (Chrome, Firefox, Safari (iOS)) di desktop dan mobile.
  - Memastikan *responsiveness* dari TailwindCSS berjalan baik.
  - Memvalidasi *upload* file (foto siswa, logo rapor).

### 3.4. User Acceptance Testing (UAT - End Users)
- **Fokus:** Memvalidasi bahwa aplikasi memenuhi kebutuhan operasional sekolah (Guru, TU, Kepala Sekolah).
- **Detail:** (Lihat *12_uat_plan.md* untuk skenario detail).

---

## 4. Lingkungan Pengujian (Test Environments)

Siklus pengujian akan melewati 3 lingkungan terpisah:
1. **Local Environment:** Mesin pengembang (menggunakan Laravel Sail/Docker). Automasi Unit & Feature test dijalankan secara lokal.
2. **Staging Environment:** 
   - URL: `staging.simt.school.id`
   - Server VPS terpisah (atau disatukan dengan production menggunakan database dan direktori web terpisah untuk menghemat budget).
   - Di sinilah QA/Dev melakukan Exploratory testing dan demonstrasi ke klien.
3. **Production Environment:** 
   - Sistem *live* dengan data sesungguhnya. Hanya dilakukan *Sanity Testing* singkat sesaat setelah deployment.

---

## 5. Kriteria Masuk dan Keluar (Entry & Exit Criteria)

### 5.1. Kriteria Masuk (Mulai Testing di Staging)
- *Code freeze* untuk modul yang bersangkutan (Sprint selesai).
- Semua Automated Unit & Feature Test (Backend) memberikan status `PASS`.
- Tidak ada error kompilasi di Frontend (`npm run build` sukses).

### 5.2. Kriteria Keluar (Go-Live / UAT Ready)
- 100% Skenario uji kritis (P0) dan Major (P1) lolos tanpa bug terbuka.
- Bug *minor* (kosmetik/P2) diperbolehkan maksimum 5 isu terbuka, asalkan ada *workaround* dan tidak mengganggu alur utama.
- Sistem stabil di lingkungan Staging selama minimal 3x24 jam tanpa *crash*.

---

## 6. Pengelolaan Cacat (Defect Management)
Karena proyek MVP tidak menggunakan Jira/Trello premium, pelacakan bug akan difokuskan menggunakan **GitHub Issues** (atau GitLab Issues).

**Alur Bug:**
1. Bug ditemukan (diberi tag `bug`, label prioritas `P0`, `P1`, `P2`).
2. Disertai bukti: *Screenshot/Video*, *Console log*, URL, dan langkah reproduksi (*Steps to Reproduce*).
3. Dev mengubah status ke `In Progress` saat diperbaiki.
4. Kode di-*push*, Issue ditutup (`Closed`).
5. Tester melakukan Re-Test di Staging. Jika gagal, issue di-*reopen*.

---

## 7. Performance & Security Testing Sederhana
- **Security:** *Dependency scanning* rutin dan cek OWASP top 10 secara manual (terutama Broken Access Control, mencoba akses URL admin menggunakan akun siswa).
- **Performance:** Memastikan TTFB (Time to First Byte) backend di bawah 500ms dan *Lighthouse Score* halaman Vue.js > 80 untuk performance. (Diukur menggunakan Chrome DevTools).

*Strategi ini memastikan dengan budget minimum, aplikasi tetap tangguh secara sistematis tanpa beban birokrasi QA yang memberatkan.*