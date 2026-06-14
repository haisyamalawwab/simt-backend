# Analisis & Perancangan: Integrasi Payment Gateway (Xendit/Midtrans) untuk SaaS Multi-Tenant
**Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs)**

**Versi:** 1.0  
**Tanggal/Waktu:** 12 Juni 2026 / 13:00 WIB  
**Disusun Oleh:** System Architect & Financial API Specialist  

---

## 1. Pendahuluan
Berdasarkan dokumen rancangan Bab 4 (Modul Keuangan), wali murid dituntut dapat melihat tagihan bulanan (SPP, Uang Gedung) dan melakukan pembayaran secara *online*. 

Dalam konteks aplikasi *SaaS Multi-Tenant* (Satu sistem dipakai banyak madrasah), integrasi Payment Gateway (PG) memunculkan tantangan hukum dan arsitektural: **Ke rekening siapa uang bermuara?**

### 1.1. Model Arsitektur PG untuk SaaS
Terdapat dua model integrasi untuk SaaS:
1. **Aggregator (Platform Model - cth: xenPlatform / Midtrans Iris):** Semua uang SPP dari ratusan madrasah masuk ke satu rekening sentral milik perusahaan pembuat SIMT MTs, lalu didistribusikan (settlement) ke tiap sekolah. *Risiko: Membutuhkan izin Bank Indonesia (Lisensi Transfer Dana) yang mahal dan rumit.*
2. **Bring-Your-Own-Account (BYOA Model):** Setiap madrasah mendaftar akun Midtrans/Xendit mereka sendiri. Mereka kemudian memasukkan *API Key* milik sekolah ke dalam pengaturan SIMT MTs. Uang SPP langsung masuk ke rekening madrasah tanpa mampir ke platform kita.

**Keputusan Desain:** Demi mematuhi regulasi finansial Indonesia dan menekan biaya (konsep *Lean MVP*), kita akan menggunakan model **BYOA (Bring Your Own Account)**.

---

## 2. Skema Basis Data (Keamanan Kredensial & Transaksi)

### 2.1. Tabel `tenant_payment_gateways`
Karena setiap madrasah menggunakan *API Key* mereka sendiri, kita wajib menyimpannya secara terenkripsi (AES-256 bawaan Laravel `Crypt::encryptString`) agar jika database bocor, API Key keuangan sekolah tetap aman.

```sql
CREATE TABLE tenant_payment_gateways (
    tenant_id UUID PRIMARY KEY,
    provider VARCHAR(50), -- 'midtrans' atau 'xendit'
    is_production BOOLEAN DEFAULT false,
    server_key TEXT, -- (Encrypted)
    client_key TEXT, -- (Encrypted)
    webhook_secret TEXT -- (Encrypted) Untuk verifikasi callback
);
```

### 2.2. Modifikasi Tabel `invoices` (Tagihan)
Kita membutuhkan kolom *tracking* referensi dari pihak ke-3 (PG).
```sql
ALTER TABLE invoices
ADD COLUMN pg_order_id VARCHAR(100) UNIQUE, -- cth: INV-MTSA-202607-001
ADD COLUMN pg_payment_url TEXT, -- Link Snap Midtrans / Xendit Invoice
ADD COLUMN pg_payment_status VARCHAR(50) DEFAULT 'PENDING';
```

---

## 3. Alur Kerja (Sequence Flow) Pembayaran Online

1. **Inisiasi (Portal Orang Tua):** Orang tua melihat tagihan Rp 150.000 di Next.js dan menekan tombol **"Bayar Online"**.
2. **Request Checkout (API Laravel):** 
   - Laravel melihat `tenant_id` dari _request_.
   - Laravel mengambil `server_key` Xendit/Midtrans milik sekolah tersebut (didekripsi).
   - Laravel menembak API pembuat tagihan ke peladen PG menggunakan *Server Key* tersebut. `pg_order_id` diset menggunakan kombinasi Tenant ID + Invoice ID agar unik secara global.
3. **Response Checkout:** PG mengembalikan *Payment URL* (misal: halaman Midtrans Snap). Laravel menyimpannya di DB dan meneruskannya ke Next.js.
4. **Pembayaran:** Orang tua diarahkan ke *Payment URL*, lalu mentransfer via *Virtual Account* BSI/BCA atau QRIS.
5. **Callback / Webhook (Background Process):** PG mengirimkan sinyal ke _endpoint webhook_ publik Laravel bahwa `pg_order_id` tertentu sudah "LUNAS" (Settled).
6. **Validasi & Notifikasi:** Laravel memvalidasi *Signature* Webhook, mengubah status `invoice` menjadi LUNAS, lalu memicu *Job* untuk menembakkan notifikasi WhatsApp ke HP Orang Tua dan Bendahara Sekolah.

---

## 4. Desain Kontrak API (Plug & Play PG Module)

### A. Endpoint Request Pembayaran Online
**POST `/api/v1/finance/invoices/{invoice_id}/checkout-online`**
- **Aktor:** Orang Tua (via Portal Next.js)
- **Logika Sistem:**
  1. Cek apakah madrasah (`tenant_id`) sudah mengatur Kredensial PG. Jika belum, *return 400 Bad Request* "Sekolah belum mengaktifkan pembayaran online".
  2. Dekripsi Server Key.
  3. Panggil API Midtrans (Snap) / Xendit (Invoice).
- **Response (200 OK):**
  ```json
  {
    "success": true,
    "message": "Link pembayaran berhasil dibuat.",
    "data": {
       "invoice_id": 450,
       "pg_order_id": "INV-MTSA-202607-001",
       "payment_url": "https://app.sandbox.midtrans.com/snap/v2/vtweb/xxxxx"
    }
  }
  ```

### B. Endpoint Webhook (Pencegat Callback dari PG)
Endpoint ini bersifat **PUBLIC** (tidak dikunci Sanctum/Auth), karena peladen Midtrans/Xendit yang akan memanggilnya secara otomatis. Namun, endpoint ini sangat rentan dicurangi (*Spoofing*).

**POST `/api/v1/webhooks/payment/midtrans`**
- **Tantangan Resolusi Tenant:** Karena peladen Midtrans memanggil API publik, Laravel tidak tahu ini uang untuk sekolah yang mana.
- **Solusi Pintar:** Laravel harus membaca parameter `order_id` di dalam body Webhook (contoh: `INV-MTSA-202607-001`). Dari kode `MTSA` atau melalui pencarian `order_id` di tabel `invoices`, Laravel bisa mengetahui `tenant_id`-nya.

**Logika Keamanan Webhook (Wajib Diimplementasikan):**
```php
public function handleMidtransWebhook(Request $request) {
    $payload = $request->all();
    $orderId = $payload['order_id'];

    // 1. Cari invoice & tenant-nya
    $invoice = Invoice::where('pg_order_id', $orderId)->firstOrFail();
    $tenantPgConfig = TenantPaymentGateway::find($invoice->tenant_id);

    // 2. Verifikasi Signature Key (Wajib agar tidak ada hacker yang memalsukan lunas)
    $serverKey = Crypt::decryptString($tenantPgConfig->server_key);
    $calculatedSignature = hash('sha512', $orderId . $payload['status_code'] . $payload['gross_amount'] . $serverKey);

    if ($calculatedSignature !== $payload['signature_key']) {
        abort(403, 'Invalid Signature');
    }

    // 3. Proses Status
    if ($payload['transaction_status'] == 'settlement' || $payload['transaction_status'] == 'capture') {
        $invoice->update(['status' => 'LUNAS']);
        
        // Pemicu Notifikasi WA
        event(new InvoicePaidOnline($invoice));
    }

    return response()->json(['message' => 'OK']);
}
```

---

## 5. UI/UX Pertimbangan Khusus (Orang Tua & TU)

1. **Dashboard Tata Usaha (Blade):**
   Pada menu Keuangan, TU harus bisa membedakan tagihan yang dibayar tunai di loket (Tangan TU) vs Pembayaran Otomatis via PG. Tambahkan *Badge* hijau bertuliskan **"Otomatis (Midtrans/QRIS)"** di riwayat transaksi.
2. **Biaya Admin (MDR):**
   Payment Gateway mengenakan biaya (misal QRIS 0.7%, atau VA Rp 4.000). 
   Di sistem, Kepala Madrasah harus memiliki opsi (di menu Pengaturan Keuangan):
   - *Bebankan biaya admin ke Orang Tua* (Total Tagihan SPP + Rp 4.000).
   - *Disedot dari Tagihan* (Sekolah menanggung biaya admin).
3. **Kadaluarsa Link (Expiry):**
   Atur masa aktif *Payment URL* maksimal 24 Jam. Jika orang tua tidak jadi mentransfer, URL akan hangus dan mereka bisa meng-klik "Bayar Online" ulang di keesokan harinya untuk men-*generate* VA baru.