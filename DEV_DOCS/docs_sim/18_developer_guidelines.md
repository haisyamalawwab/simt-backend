# Panduan Developer & Alur Kerja Git (Developer Guidelines)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Disusun Oleh:** Tech Lead  

---

## 1. Pendahuluan
Dokumen ini menjadi aturan standar (SOP) bagi tim *developer* agar proses pengembangan SIMT MTs (MVP) dapat berjalan paralel, rapi, meminimalisir *merge conflict*, dan menjaga kualitas kode.

---

## 2. Pengaturan Lingkungan Lokal (Local Environment Setup)

Karena backend menggunakan Laravel 10 (PHP 8.2) dan frontend Vue.js 3, berikut standar setup di lokal masing-masing dev:

### 2.1. Backend (Laravel)
Disarankan menggunakan **Laravel Sail** (berbasis Docker) untuk menjamin kesamaan versi PHP dan PostgreSQL antar developer (Mac/Windows/Linux).
1. `git clone https://github.com/simt-org/simt-backend.git`
2. `cp .env.example .env` (Sesuaikan `DB_HOST=pgsql`)
3. `composer install`
4. `./vendor/bin/sail up -d`
5. `./vendor/bin/sail artisan key:generate`
6. `./vendor/bin/sail artisan migrate --seed` (Seeder wajib mengisi Role, Permission, dan akun Super Admin).

### 2.2. Frontend (Vue.js 3)
Membutuhkan Node.js v18 atau v20.
1. `git clone https://github.com/simt-org/simt-frontend.git`
2. `cp .env.example .env` (Set `VITE_API_BASE_URL="http://localhost/api/v1"`)
3. `npm install`
4. `npm run dev`

---

## 3. Standar Penulisan Kode (Coding Standards)

### 3.1. PHP (Backend)
- Mematuhi standar **PSR-12**.
- Menggunakan *Form Request Validation* alih-alih melakukan validasi langsung di Controller.
- Controller wajib tetap tipis (*Fat Model / Thin Controller* atau menggunakan pola *Service Class* untuk logika bisnis kompleks seperti Kalkulasi Rapor).
- Nama tabel DB: `snake_case_plural` (e.g., `students`, `student_scores`).
- Nama Model: `PascalCaseSingular` (e.g., `Student`, `StudentScore`).

### 3.2. Vue & Javascript (Frontend)
- Menggunakan **Composition API** (dengan sintaks `<script setup>`).
- Linter menggunakan bawaan `ESLint` + `Prettier`.
- State management menggunakan **Pinia** (pisahkan store misal: `useAuthStore`, `useStudentStore`).
- Nama komponen Vue menggunakan `PascalCase` (e.g., `StudentList.vue`, `BaseButton.vue`).
- Hindari penulisan *inline styling* CSS, gunakan class utilitas TailwindCSS secara penuh.

---

## 4. Alur Kerja Git (Git Workflow)

Proyek ini menggunakan model **Feature Branch Workflow** (varian sederhana dari Git Flow) karena kecepatan iterasi MVP.

### 4.1. Strategi Pencabangan (Branching)
- `main`: Branch *production-ready*. Hanya di-merge setelah UAT lulus. Kode di sini adalah yang berjalan di VPS *live*.
- `staging`: Branch utama untuk mengumpulkan semua fitur (*integration*). Dideploy ke server *staging*.
- `feature/*`: Branch untuk pengembangan fitur baru.
- `bugfix/*` atau `hotfix/*`: Branch untuk perbaikan bug.

### 4.2. Siklus Kerja Harian (Daily Cycle)
1. Dev menarik kode terbaru dari staging: `git checkout staging && git pull origin staging`.
2. Dev membuat branch fitur: `git checkout -b feature/create-student-api`.
3. Dev mengetik kode dan melakukan *commit* secara berkala.
4. Setelah fitur selesai, dev mem-*push* ke repo asal: `git push origin feature/create-student-api`.
5. Dev membuat **Pull Request (PR)** dari `feature/...` ke `staging` di Github/Gitlab.
6. Tech Lead (atau dev lain) melakukan **Code Review**.
7. Jika di-*approve*, branch di-*merge* ke `staging` dan branch fitur dihapus.

---

## 5. Konvensi Pesan Commit (Conventional Commits)

Agar *history repository* mudah dilacak, setiap *commit* wajib mengikuti format [Conventional Commits](https://www.conventionalcommits.org/):

**Format Dasar:** `<type>(<scope>): <subject>`

**Tipe (Type) yang diizinkan:**
- `feat`: Menambahkan fitur baru.
- `fix`: Memperbaiki bug.
- `docs`: Perubahan dokumentasi saja (seperti README).
- `style`: Perubahan yang tidak mempengaruhi logika (spasi, formatting, titik koma).
- `refactor`: Mengubah kode tanpa menambah fitur atau memperbaiki bug (misal merapikan nama variabel).
- `test`: Menambahkan atau memperbaiki *unit/feature tests*.
- `chore`: Perubahan pada proses *build* atau tools pembantu (misal: tambah library npm).

**Contoh yang Benar:**
- `feat(student): add api endpoint for student registration`
- `fix(auth): resolve login timeout issue`
- `style(ui): fix misaligned button on mobile view`

---

## 6. Penanganan Error (Error Handling & Logging)

**Backend:**
Jangan membiarkan kode menggunakan blok `try-catch` kosong. Gunakan fungsi log bawaan Laravel:
```php
try {
   // Logic...
} catch (\Exception $e) {
   Log::error('Gagal memproses SPP: ' . $e->getMessage(), ['user_id' => auth()->id()]);
   return response()->json(['success' => false, 'message' => 'Terjadi kesalahan internal'], 500);
}
```

**Frontend:**
Tangkap error *network* atau HTTP 500 secara global di *Axios Interceptor* dan tampilkan komponen *Toast / Snackbar* berwarna merah agar user mengerti ada masalah, jangan biarkan UI nge-*hang* / memuat tanpa batas (infinite loading).