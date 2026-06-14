# DRAFT PERJANJIAN KERJASAMA (MoU) PENYEDIAAN LAYANAN SIMT MTs
*(Dokumen ini dirancang dengan prinsip transparansi penuh, melindungi batasan pekerjaan Vendor, sekaligus mengamankan hak serta privasi data milik Sekolah).*

**Nomor:** [Nomor Surat / Kontrak]  
**Tanggal:** [Tanggal Penandatanganan]  

Pada hari ini, [Hari] tanggal [Tanggal] bulan [Bulan] tahun [Tahun], bertempat di [Lokasi Penandatanganan], kami yang bertanda tangan di bawah ini:

**1. [Nama Lengkap Vendor/Perwakilan]**  
Bertindak untuk dan atas nama **[Nama Perusahaan / Tim Pengembang]**, berkedudukan di [Alamat Vendor]. Selanjutnya disebut sebagai **PIHAK PERTAMA (Penyedia Layanan)**.

**2. [Nama Lengkap Kepala Sekolah]**  
Bertindak untuk dan atas nama **MTs [Nama Sekolah]**, berkedudukan di [Alamat Sekolah]. Selanjutnya disebut sebagai **PIHAK KEDUA (Klien / Pengguna Layanan)**.

Kedua belah pihak secara bersama-sama disebut "PARA PIHAK". PARA PIHAK sepakat untuk mengikatkan diri dalam Perjanjian Kerjasama Penyediaan Layanan Perangkat Lunak (*Software as a Service* / SaaS) Sistem Informasi Manajemen Terpadu Madrasah Tsanawiyah (SIMT MTs) dengan ketentuan sebagai berikut:

---

### PASAL 1: RUANG LINGKUP LAYANAN (KETENTUAN SEWA)
1. PIHAK PERTAMA menyediakan hak guna (lisensi sewa) perangkat lunak SIMT MTs berbasis *Cloud* (Web) kepada PIHAK KEDUA. Layanan ini bukan merupakan jual-beli putus *source code* (kode sumber).
2. Fitur yang termasuk dalam layanan dasar (Core) adalah: Manajemen Data Siswa/Guru, Manajemen Rombel, Presensi Harian, Portal Orang Tua (Web-App), dan Modul Integrasi Notifikasi WhatsApp.
3. Perjanjian ini bersifat **SaaS (Penyewaan Sistem Bersama)**. PIHAK KEDUA memahami bahwa PIHAK PERTAMA tidak menerima permintaan pembuatan fitur khusus (*Custom Development*), perubahan hierarki *database*, atau perubahan antarmuka pengguna (UI/UX) di luar dari modul *Plug & Play* standar yang disediakan.

### PASAL 2: HAK DAN KEWAJIBAN PARA PIHAK
**A. Hak dan Kewajiban PIHAK PERTAMA (Vendor)**
1. Berhak menerima pembayaran Biaya Setup, Biaya Sewa Berlangganan, dan Biaya Pelatihan tepat waktu.
2. Berkewajiban menjaga agar peladen (*Server*) dapat diakses secara maksimal (Uptime 99%) kecuali saat *Maintenance* rutin yang akan diinformasikan sebelumnya.
3. Berkewajiban memfasilitasi *Import* data awal (Siswa & Guru) dari *template* Excel ke dalam sistem satu kali pada masa awal kontrak (Fase *Onboarding*).

**B. Hak dan Kewajiban PIHAK KEDUA (Sekolah)**
1. Berhak menggunakan modul yang telah disewa selama masa berlangganan aktif.
2. Berkewajiban melakukan pembayaran biaya layanan sesuai termin yang disepakati tanpa penundaan.
3. Berkewajiban menyediakan dan menautkan (Scan QR) nomor WhatsApp aktif milik Madrasah untuk pengiriman notifikasi otomatis, serta mematuhi batasan *Anti-Spam* dari Meta.
4. Berkewajiban menunjuk 1 (satu) orang Staf/Operator sekolah sebagai Koordinator IT (PIC) yang bertugas berkoordinasi dengan PIHAK PERTAMA.

### PASAL 3: KEPEMILIKAN DAN PERLINDUNGAN DATA (UU PDP)
1. **Kepemilikan Kode:** Seluruh *Source Code*, desain antarmuka, dan hak kekayaan intelektual aplikasi SIMT MTs adalah mutlak milik PIHAK PERTAMA.
2. **Kepemilikan Data:** Seluruh data siswa, guru, presensi, rapor, dan transaksi keuangan adalah **Mutlak Milik PIHAK KEDUA**. 
3. **Kerahasiaan & Enkripsi:** PIHAK PERTAMA menerapkan enkripsi standar industri (termasuk enkripsi kredensial API sekolah) dan menjamin tidak akan menyebarkan, menjual, atau menyalahgunakan data pribadi milik PIHAK KEDUA sesuai dengan mandat **UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi**.
4. **Data Ekstraksi (Anti-Data Hostage):** Apabila PIHAK KEDUA berhenti berlangganan/memutus kontrak, PIHAK PERTAMA wajib memberikan salinan (*Export Excel/CSV*) seluruh data milik sekolah kepada PIHAK KEDUA secara cuma-cuma, dan menghancurkan (menghapus permanen) data tersebut dari *Server* PIHAK PERTAMA paling lambat 30 hari pasca pemutusan.

### PASAL 4: SKEMA BIAYA, TERMIN PEMBAYARAN & PAJAK
Sistem pembayaran menggunakan skema Prabayar (*Prepaid*) guna memastikan stabilitas operasional peladen.

1. **Biaya Setup (Satu Kali):** Rp 1.000.000 (Satu Juta Rupiah) dibayarkan di awal penandatanganan kontrak untuk inisialisasi akun *tenant*, pengaturan peladen khusus, dan *import* data awal.
2. **Biaya Sewa Sistem (SaaS):** Dihitung berdasarkan jumlah siswa aktif, yaitu **Rp 2.000 / Siswa / Bulan**.
3. **Batas Minimum Tagihan:** Apabila hasil kali jumlah siswa kurang dari Rp 200.000, maka PIHAK KEDUA sepakat dikenakan tagihan operasional minimum sebesar **Rp 200.000 / Bulan**.
4. **Termin Pembayaran Sewa:** Tagihan sewa ditagihkan secara rapel untuk **1 Semester (6 Bulan) di depan**. (*Contoh untuk tagihan minimum: Rp 200.000 x 6 = Rp 1.200.000 dibayar sebelum masuk hari pertama masuk sekolah di semester baru*).
5. Segala bentuk beban pajak (seperti PPh/PPN jika badan hukum) atau biaya administrasi antar-bank ditanggung oleh PIHAK KEDUA kecuali disepakati lain.

### PASAL 5: PELATIHAN, SOSIALISASI, DAN PENDAMPINGAN (SERVICES)
PIHAK PERTAMA menyediakan 1x buku panduan digital (*PDF User Manual*) secara **gratis**. Apabila PIHAK KEDUA membutuhkan kunjungan tatap muka, pendampingan langsung, atau rapat sosialisasi, berlaku ketentuan biaya layanan profesional (Services) berikut:

1. **Pelatihan Staf TU & Guru (Online/Zoom):** **Rp 250.000 / Sesi** (Maksimal 2 Jam). Termasuk demonstrasi cara input nilai dan penggunaan sistem absensi.
2. **Pelatihan Tatap Muka & Pendampingan Harian (On-Site):** Dikenakan biaya transportasi dan jasa pendampingan sebesar **Rp 500.000 / Hari Kunjungan** (Maksimal 5 Jam Kerja), belum termasuk tiket perjalanan apabila lokasi Madrasah berada di luar kota domisili PIHAK PERTAMA.
3. **Sosialisasi Wali Murid:** Apabila PIHAK PERTAMA diundang hadir secara tatap muka (misal: Rapat Komite) untuk menyosialisasikan penggunaan Portal Aplikasi Orang Tua, dikenakan honorarium narasumber sebesar **Rp 500.000 / Acara**.
*Seluruh biaya jasa layanan pada Pasal 5 ini harus dibayarkan selambat-lambatnya 3 (tiga) hari sebelum jadwal pelaksanaan kegiatan dilakukan.*

### PASAL 6: PEMBATASAN TANGGUNG JAWAB (LIMITATION OF LIABILITY)
1. PIHAK PERTAMA menggunakan modul integrasi pengiriman WhatsApp yang diakses melalui pemindaian akun PIHAK KEDUA secara independen (*Self-Hosted Node/Baileys*). Apabila nomor WhatsApp PIHAK KEDUA ditangguhkan (diblokir) oleh pihak penyedia layanan WhatsApp/Meta, hal tersebut sepenuhnya **di luar tanggung jawab PIHAK PERTAMA**.
2. PIHAK PERTAMA tidak bertanggung jawab atas kesalahan data (*Human Error*) yang diakibatkan oleh pihak PIHAK KEDUA (seperti kesalahan pengisian nilai, salah menentukan status bayar SPP, atau penghapusan data secara sengaja).

### PASAL 7: SANKSI, SUSPENSI LAYANAN, DAN PEMUTUSAN
1. PIHAK PERTAMA akan menerbitkan *Invoice* perpanjangan sewa (Termin Semester) selambatnya 14 Hari sebelum masa aktif semester berjalan habis.
2. Apabila PIHAK KEDUA belum menyelesaikan pembayaran hingga melewati masa tenggang (*Grace Period*) 14 Hari sejak semester baru dimulai, PIHAK PERTAMA memiliki kewenangan sepihak untuk melakukan **Penangguhan Sementara (Suspend)** pada pengiriman Notifikasi WhatsApp dan akses Portal Orang Tua.
3. Penangguhan sebagaimana diatur dalam ayat (2) TIDAK akan menghapus data milik PIHAK KEDUA dan akses *Dashboard* TU akan tetap terbuka (*Read Only*).
4. Pemutusan kontrak dapat dilakukan oleh salah satu pihak dengan pemberitahuan tertulis selambat-lambatnya 30 (tiga puluh) hari sebelumnya.

### PASAL 8: PENYELESAIAN SENGKETA
Apabila terjadi perbedaan penafsiran atau perselisihan dalam pelaksanaan perjanjian ini, PARA PIHAK sepakat untuk menyelesaikannya secara musyawarah untuk mufakat secara kekeluargaan demi menjaga iklim pendidikan yang baik.

---

Demikian Perjanjian Kerjasama ini dibuat dengan iktikad baik, dalam 2 (dua) rangkap asli yang bermaterai cukup, masing-masing memiliki kekuatan pembuktian hukum yang sama.

**Dibuat di:** [Lokasi]  
**Tanggal:** [Tanggal]  

| **PIHAK PERTAMA** | **PIHAK KEDUA** |
| :---: | :---: |
| *(Tanda Tangan & Materai Rp 10.000)* | *(Tanda Tangan & Materai Rp 10.000)* |
| **[Nama Penyedia / Vendor]** | **[Nama Kepala Madrasah]** |
| *Vendor SIMT MTs* | *Kepala Madrasah* |