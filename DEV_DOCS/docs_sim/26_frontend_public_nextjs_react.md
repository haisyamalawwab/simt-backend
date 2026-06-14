# Standar UI/UX User: Next.js/React Headless (Parent Portal)
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 11:15 WIB  
**Disusun Oleh:** Frontend Architect  

---

## 1. Pendahuluan
Aplikasi yang diakses oleh Orang Tua/Wali Murid dibangun menggunakan **Next.js (App Router)**. Tujuannya adalah menghadirkan antarmuka berbasis komponen React yang terasa seperti aplikasi *Native Mobile*, ringan, interaktif, dan mudah dirilis sebagai PWA (Progressive Web App). Next.js ini akan mengkonsumsi REST API yang disediakan oleh *Backend Laravel*.

---

## 2. Struktur Proyek & Konfigurasi Next.js

```text
/simt-parent-portal (Next.js)
 ├── /app
 │   ├── /login
 │   │   └── page.tsx        (Halaman Login NISN + PIN)
 │   ├── /(dashboard)
 │   │   ├── layout.tsx      (Bottom Navigation Bar Layout)
 │   │   ├── page.tsx        (Beranda - Rekap Absensi Anak)
 │   │   ├── /billing        (Halaman Tagihan SPP)
 │   │   └── /scores         (Halaman Nilai Ujian)
 │   └── globals.css         (Tailwind directives)
 ├── /components
 │   ├── /ui                 (Reusable React Components e.g. Button, Card)
 │   └── /layout             (Header, BottomNav)
 ├── /lib
 │   ├── apiClient.ts        (Konfigurasi Axios)
 │   └── auth.ts             (Manajemen Cookie/Session Token)
```

---

## 3. Komunikasi dengan API & Resolusi Tenant

Ini adalah bagian paling kritis. Karena API bersifat Multi-Tenant, Next.js harus selalu memberitahu Laravel API *sekolah mana* yang datanya mau diambil.

### 3.1. Penentuan Tenant di Next.js
Next.js akan membaca domain tempat aplikasi dijalankan (misal: `mts-assalam.simt.id`). 
Domain ini akan diselipkan sebagai *Custom Header* saat melakukan _fetching_ ke API sentral Laravel.

### 3.2. Konfigurasi Axios / API Client (`lib/apiClient.ts`)
```typescript
import axios from 'axios';

// API Sentral Backend Laravel
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'https://api.simt.id/api/v1';

const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
});

// Request Interceptor: Otomatis tambahkan Token Auth dan Header Tenant
apiClient.interceptors.request.use((config) => {
    // 1. Ambil domain saat ini (misal via utilitas window.location)
    if (typeof window !== 'undefined') {
        config.headers['X-Tenant-Domain'] = window.location.hostname;
    }
    
    // 2. Ambil token Sanctum dari LocalStorage / Cookies
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    
    return config;
});

export default apiClient;
```

---

## 4. Alur Autentikasi (Server Components vs Client Components)

Karena menggunakan Next.js 13+ App Router, kita harus membedakan rendering halaman.

1. **Client Components (`'use client'`):** Digunakan untuk form login (membutuhkan aksi `onClick` dan _state_ validasi form).
2. **Server Components (Default):** Digunakan untuk mengambil data tagihan (`/billing`) secara langsung dari server saat halaman di-*request*. Ini menghemat kuota internet HP orang tua karena _payload_ data lebih kecil (yang dikirim sudah berupa HTML React).

### Contoh Fetching Data (Server Component)
```tsx
import { cookies } from 'next/headers';

async function getBillingData(tenantDomain: string, token: string) {
  const res = await fetch('https://api.simt.id/api/v1/finance/billing', {
    headers: {
        'X-Tenant-Domain': tenantDomain,
        'Authorization': `Bearer ${token}`
    }
  });
  return res.json();
}

export default async function BillingPage() {
  // Ambil token dari cookie Next.js Server
  const cookieStore = cookies();
  const token = cookieStore.get('auth_token')?.value;
  // (Penyederhanaan: Domain tenant idealnya diambil dari middleware request header)
  
  const data = await getBillingData('mts-assalam.simt.id', token!);

  return (
    <div className="p-4 mb-20"> {/* Margin bawah untuk BottomNav */}
      <h1 className="text-xl font-bold">Tagihan SPP</h1>
      {data.success ? (
         <BillingList bills={data.data} />
      ) : (
         <p>Gagal memuat tagihan.</p>
      )}
    </div>
  );
}
```

---

## 5. UI Layout (Mobile-First Paradigm)

- **Grid/Layout:** Wajib menggunakan maksimal lebar layar `max-w-md` (± 448px) yang dipusatkan (`mx-auto`). Walaupun dibuka di Laptop, tampilan akan tetap berwujud *potrait* layaknya layar HP.
- **Navigasi Utama:** Bukan *Sidebar*, melainkan **Bottom Tab Navigation** yang menempel di bawah layar (`fixed bottom-0 w-full`).
- **State Management:** Menggunakan *React Context* sederhana (Zustand) untuk menyimpan profil siswa yang sedang aktif.