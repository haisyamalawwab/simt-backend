# PEMETAAN MODUL DAN RBAC (ROLE-BASED ACCESS CONTROL)
## SISTEM INFORMASI MANAJEMEN TERPADU (SIMT) MTs/YAYASAN

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Status:** DRAFT  
**Penulis:** Tim Proyek SIMT MTs

---

## 1. PENDAHULUAN

### 1.1 Tujuan Dokumen

Dokumen ini bertujuan untuk:
- Memetakan setiap modul dan fitur ke role pengguna yang sesuai
- Mendefinisikan hak akses (permission) untuk setiap role
- Menyediakan matriks akses yang jelas untuk development
- Menjadi acuan dalam implementasi sistem keamanan

### 1.2 Metodologi RBAC

```
RBAC FRAMEWORK YANG DIGUNAKAN:

┌─────────────────────────────────────────────────────────────────────┐
│                         HIARARKI RBAC                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│   ROLE (Peran)                                                      │
│   ├── Berisi kumpulan permissions                                   │
│   ├── Diedit ke user berdasarkan tanggung jawab                    │
│   └── Contoh: Kepala Madrasah, Guru, TU                            │
│                                                                     │
│       ▼                                                             │
│                                                                     │
│   PERMISSION (Izin)                                                 │
│   ├── Berisi aksi pada resource tertentu                           │
│   ├── Format: ACTION + RESOURCE                                     │
│   └── Contoh: view_student, edit_nilai, approve_modul              │
│                                                                     │
│       ▼                                                             │
│                                                                     │
│   RESOURCE (Resource/Data)                                          │
│   ├── Data atau fitur yang diakses                                 │
│   └── Contoh: students, nilai, keuangan, rapor                     │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. DEFINISI ROLE

### 2.1 Daftar Role

```
┌─────────────────────────────────────────────────────────────────────┐
│                         DAFTAR ROLE                                 │
├──────┬────────────────────────────────┬─────────────────────────────┤
│ ID   │ Role                           │ Deskripsi                   │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R01  │ SUPER_ADMIN                    │ Admin sistem (developer)    │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R02  │ KEPALA_MADRASAH                │ Kepala Madrasah             │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R03  │ WAKA_KURIKULUM                 │ Wakil Kepala Bidang Kurikulum│
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R04  │ WAKA_KESISWAAN                 │ Wakil Kepala Bidang Kesiswaan│
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R05  │ WAKA_SARPRAS                   │ Wakil Kepala Sarana Prasarana│
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R06  │ GURU                           │ Guru Mata Pelajaran         │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R07  │ WALI_KELAS                     │ Wali Kelas                  │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R08  │ GURU_BK                        │ Guru Bimbingan Konseling    │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R09  │ GPK                            │ Guru Pendamping Khusus      │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R10  │ TAHFIZ                         │ Pembina/Pengajar Tahfiz     │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R11  │ TATA_USAHA                     │ Tata Usaha / Administrator  │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R12  │ BENDAHARA                      │ Bendahara Sekolah           │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R13  │ ORANG_TUA                      │ Orang Tua / Wali Murid      │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R14  │ SISWA                          │ Siswa                       │
├──────┼────────────────────────────────┼─────────────────────────────┤
│ R15  │ YAYASAN                        │ Pengurus Yayasan            │
└──────┴────────────────────────────────┴─────────────────────────────┘
```

### 2.2 Role Hierarchy

```
HIERARKI ROLE (Parent-Child):

                          ┌──────────────────┐
                          │   SUPER_ADMIN    │  ← Akses penuh sistem
                          └────────┬─────────┘
                                   │ inherits
               ┌───────────────────┼───────────────────┐
               │                   │                   │
               ▼                   ▼                   ▼
      ┌────────────────┐  ┌────────────────┐  ┌────────────────┐
      │ KEPALA_MADRASAH │  │     YAYASAN    │  │    TATA_USAHA  │
      └───────┬─────────┘  └────────────────┘  └───────┬─────────┘
              │                                       │
              │ inherits                              │ inherits
     ┌────────┼────────┐                    ┌─────────┼────────┐
     │        │        │                    │         │        │
     ▼        ▼        ▼                    ▼         ▼        ▼
┌────────┐ ┌───────┐ ┌────────┐    ┌────────────┐ ┌─────────┐ ┌───────────┐
│WAKA_KK │ │WAKA_KS│ │WAKA_SP │    │ BENDAHARA  │ │ WALI_K  │ │  GURU     │
└────────┘ └───────┘ └────────┘    └────────────┘ └─────────┘ └─────┬─────┘
                                                                  │     │
                                                                  ▼     ▼
                                                            ┌────────┐ ┌────────┐
                                                            │ GURU_BK│ │ GPK    │
                                                            └────────┘ └────────┘

┌───────────┐
│ ORANG_TUA │  ← Akses terbatas hanya untuk data anak sendiri
└─────┬─────┘
      │ no inheritance
      ▼
┌───────────┐
│   SISWA   │  ← Akses terbatas untuk dirinya sendiri
└───────────┘

┌───────────┐
│  TAHFIZ   │  ← Inherits dari GURU + tambahan akses khusus tahfiz
└───────────┘
```

---

## 3. MATRIKS AKSES PER MODUL

### 3.1 Legenda

```
┌─────────────────────────────────────────────────────────────────────┐
│                           LEGENDA                                   │
├─────────────────────────────────────────────────────────────────────┤
│  R  = Read (Melihat data)                                          │
│  C  = Create (Membuat/Menambah data)                               │
│  U  = Update (Mengubah data)                                       │
│  D  = Delete (Menghapus data)                                      │
│  A  = Approve (Menyetujui/Merencanakan)                           │
│  X  = No Access (Tidak punya akses)                                │
├─────────────────────────────────────────────────────────────────────┤
│  OWN = Akses hanya untuk data sendiri/klien yang diampu             │
│  ALL = Akses untuk seluruh data (scope madrasah/yayasan)            │
│  DEP = Akses untuk data departemen/bidangnya saja                  │
│  SCH = Akses untuk data seluruh sekolah                             │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Modul Akademik

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MODUL AKADEMIK - MATRIKS AKSES                                                         │
├────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬─────┤
│ Fitur  │ KAMAD  │ WAKA_K │ WAKA_K │ WAKA_SP│ GURU   │WALI_KLS│ GURU_BK│  GPK  │ TU  │
│        │        │ KUR    │ SIS    │        │        │        │        │        │     │
├────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼─────┤
│        │        │        │        │        │        │        │        │        │     │
│ BIODATA SISWA                                                                      │
│ ├ Lihat semua    │  ALL  │  ALL  │  ALL  │  DEP  │  OWN  │  OWN  │  OWN  │ ALL │
│ ├ Tambah siswa   │   A   │   C   │   X   │   X   │   X   │   X   │   X   │  C  │
│ ├ Edit siswa     │   A   │   U   │   X   │   X   │   X   │   X   │   X   │  U  │
│ ├ Hapus siswa    │   A   │   D   │   X   │   X   │   X   │   X   │   X   │  D  │
│ └ Import EMIS    │   X   │   C   │   X   │   X   │   X   │   X   │   X   │  C  │
│                  │        │        │        │        │        │        │        │     │
│ ROMBEL & KELAS                                                                 │
│ ├ Lihat rombel   │  ALL  │  ALL  │  ALL  │  DEP  │  OWN  │  OWN  │  OWN  │ ALL │
│ ├ Setup rombel   │   A   │   C   │   X   │   X   │   X   │   X   │   X   │  C  │
│ ├ Atur naik kelas│   A   │   C   │   X   │   X   │   X   │   X   │   X   │  C  │
│ └ Mutasi         │   A   │   C   │   X   │   X   │   X   │   X   │   X   │  C  │
│                  │        │        │        │        │        │        │        │     │
│ JADWAL PELAJARAN                                                               │
│ ├ Lihat jadwal   │  ALL  │  ALL  │  ALL  │ OWN   │  OWN  │  OWN  │  OWN  │ ALL │
│ ├ Setup jadwal   │   A   │   C   │   X   │   X   │   X   │   X   │   X   │  C  │
│ ├ Generate auto  │   X   │   C   │   X   │   X   │   X   │   X   │   X   │  C  │
│ └ Perubahan      │   A   │   U   │   X   │   C   │   X   │   X   │   X   │  U  │
│                  │        │        │        │        │        │        │        │     │
│ PRESENSI                                                                       │
│ ├ Lihat presensi│  ALL  │  ALL  │  ALL  │ OWN   │  OWN  │  OWN  │  OWN  │ ALL │
│ ├ Input presensi│   X   │   X   │   X   │  C    │   C   │   C   │   X   │  X  │
│ ├ Rekap presensi│  ALL  │  ALL  │  ALL  │  DEP  │  OWN  │  OWN  │  OWN  │ ALL │
│ └ Validasi pres │   A   │   A   │   X   │   X   │   X   │   X   │   X   │  A  │
│                  │        │        │        │        │        │        │        │     │
│ PENILAIAN                                                                      │
│ ├ Lihat nilai   │  ALL  │  ALL  │   X   │ OWN   │  OWN  │  OWN  │  OWN  │ ALL │
│ ├ Input nilai   │   X   │   X   │   X   │  C    │   C   │   X   │   X   │  X  │
│ ├ Edit nilai    │   X   │   X   │   X   │  U    │   X   │   X   │   X   │  X  │
│ ├ Validasi nilai│   A   │   A   │   X   │   X   │   X   │   X   │   X   │  A  │
│ ├ Deskripsi auto│   R   │   R   │   X   │  R    │   R   │   R   │   R   │  R  │
│ └ Remidial      │   X   │   X   │   X   │  C    │   X   │   X   │   X   │  X  │
│                  │        │        │        │        │        │        │        │     │
│ E-RAPOR                                                                       │
│ ├ Lihat rapor   │  ALL  │  ALL  │   X   │  DEP  │  OWN  │  OWN  │  OWN  │ ALL │
│ ├ Generate rapor│   X   │   C   │   X   │   X   │   C   │   X   │   X   │  X  │
│ ├ Validasi rapor│   A   │   A   │   X   │   X   │   A   │   X   │   X   │  A  │
│ ├ Cetak rapor   │  ALL  │  ALL  │   X   │ OWN   │  OWN  │  OWN  │  OWN  │ ALL │
│ └ TTD digital   │   A   │   X   │   X   │   X   │   X   │   X   │   X   │  X  │
│                  │        │        │        │        │        │        │        │     │
│ MODUL AJAR                                                                    │
│ ├ Lihat modul   │  ALL  │  ALL  │   X   │ OWN   │  OWN  │   X   │   X   │ ALL │
│ ├ Upload modul  │   X   │   X   │   X   │  C    │   X   │   X   │   X   │  X  │
│ ├ Review modul  │   X   │   A   │   X   │   X   │   X   │   X   │   X   │  X  │
│ └ Approve modul │   A   │   A   │   X   │   X   │   X   │   X   │   X   │  X  │
│                  │        │        │        │        │        │        │        │     │
│ JURNAL MENGAJAR                                                               │
│ ├ Lihat jurnal  │  ALL  │  ALL  │   X   │ OWN   │  OWN  │  OWN  │   X   │ ALL │
│ ├ Isi jurnal    │   X   │   X   │   X   │  C    │   X   │   X   │   X   │  X  │
│ └ Monitoring    │   X   │   A   │   X   │   X   │   X   │   X   │   X   │  X  │
│                  │        │        │        │        │        │        │        │     │
│ BANK SOAL                                                                     │
│ ├ Lihat soal    │  ALL  │  ALL  │   X   │ OWN   │   X   │   X   │   X   │ ALL │
│ ├ Upload soal   │   X   │   X   │   X   │  C    │   X   │   X   │   X   │  X  │
│ └ Generate kisi │   X   │   X   │   X   │  C    │   X   │   X   │   X   │  X  │
│                  │        │        │        │        │        │        │        │     │
│ DASHBOARD AKADEMIK                                                           │
│ ├ View dashboard│  ALL  │  ALL  │   X   │ OWN   │  OWN  │   X   │   X   │ ALL │
│ └ Export report │  ALL  │  ALL  │   X   │ OWN   │  OWN  │   X   │   X   │ ALL │
│                  │        │        │        │        │        │        │        │     │
└─────────────────────────────────────────────────────────────────────────────────────┘

LEGEND KOLOM:
KAMAD = Kepala Madrasah     WAKA_KK = Waka Kurikulum
WAKA_KS = Waka Kesiswaan    WAKA_SP = Waka Sarpras
GURU = Guru Mata Pelajaran  WALI_KLS = Wali Kelas
GURU_BK = Guru BK           GPK = Guru Pendamping Khusus
TU = Tata Usaha
```

### 3.3 Modul Kesiswaan

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MODUL KESISWAAN - MATRIKS AKSES                                                       │
├────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬─────┤
│ Fitur  │ KAMAD  │ WAKA_K │ WAKA_K │ WAKA_SP│ GURU   │WALI_KLS│ GURU_BK│  GPK  │ TU  │
│        │        │ KUR    │ SIS    │        │        │        │        │        │     │
├────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼─────┤
│        │        │        │        │        │        │        │        │        │     │
│ ORGANISASI SISWA                                                              │
│ ├ Lihat organisasi│ ALL │  DEP  │  ALL  │   X   │ OWN   │ OWN   │   X   │   X │ ALL │
│ ├ Kelola organisasi│  A  │   X   │   C   │   X   │   X   │   X   │   X   │   X │  C │
│ └ Assign penanggung│ A  │   X   │   C   │   X   │   C   │   X   │   X   │   X │  U │
│                  │        │        │        │        │        │        │        │     │
│ EKSKUL                                                                        │
│ ├ Lihat ekskul   │  ALL │  DEP  │  ALL  │  DEP  │ OWN   │ OWN   │   X   │   X │ ALL │
│ ├ Setup ekskul   │   A  │   X   │   C   │   X   │   X   │   X   │   X   │   X │  C │
│ ├ Pendaftaran   │   X  │   X   │   C   │   X   │   X   │   X   │   X   │   X │  C │
│ ├ Presensi ekskul│  X   │   X   │   X   │   X   │  C    │   X   │   X   │   X │  X │
│ └ Penilaian ekskul│ X   │   X   │   A   │   X   │  C    │   X   │   X   │   X │  X │
│                  │        │        │        │        │        │        │        │     │
│ PELANGGARAN                                                                   │
│ ├ Lihat pelanggaran│ALL │  DEP  │  ALL  │  DEP  │ OWN   │  OWN  │  OWN  │ OWN│ ALL │
│ ├ Input pelanggaran│ X  │   X   │   C   │   X   │  C    │  C    │  C    │  C │  C │
│ ├ Validasi        │   A │   X   │   A   │   X   │   X   │   X   │   X   │   X│  A │
│ ├ Konsekuensi     │   A │   X   │   C   │   X   │   X   │   X   │   X   │   X│  C │
│ └ Dashboard       │  ALL│  DEP  │  ALL  │  DEP  │ OWN   │  OWN  │  OWN  │ OWN│ ALL │
│                  │        │        │        │        │        │        │        │     │
│ PRESTASI                                                                      │
│ ├ Lihat prestasi │  ALL │  DEP  │  ALL  │   X   │ OWN   │  OWN  │   X   │   X │ ALL │
│ ├ Input prestasi │   X  │   X   │   C   │   X   │  C    │  C    │   X   │   X │  C │
│ ├ Generate sertifikat│ X│ X   │   C   │   X   │   X   │   X   │   X   │   X │  C │
│ └ Leaderboard    │  ALL │  DEP  │  ALL  │   X   │ OWN   │  OWN  │   X   │   X │ ALL │
│                  │        │        │        │        │        │        │        │     │
│ PERIZINAN                                                                     │
│ ├ Lihat izin     │  ALL │  DEP  │  ALL  │   X   │ OWN   │  OWN  │   X   │   X │ ALL │
│ ├ Request izin   │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  X │
│ ├ Approval izin  │   A  │   X   │   A   │   X   │   A   │   A   │   X   │   X │  A │
│ └ Notifikasi     │  ALL │  DEP  │  ALL  │   X   │  DEP  │  OWN  │   X   │   X │ ALL │
│                  │        │        │        │        │        │        │        │     │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.4 Modul Keuangan

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MODUL KEUANGAN - MATRIKS AKSES                                                        │
├────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬─────┤
│ Fitur  │ KAMAD  │ WAKA_K │ WAKA_K │ WAKA_SP│ GURU   │WALI_KLS│ GURU_BK│  GPK  │ TU  │
│        │        │ KUR    │ SIS    │        │        │        │        │        │ BEN │
├────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼─────┤
│        │        │        │        │        │        │        │        │        │     │
│ TAGIHAN                                                                    │
│ ├ Lihat tagihan │ ALL  │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│ ├ Setup komponen│  A   │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│ ├ Generate tagihan│  A  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│ ├ Diskon        │   A  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│ └ Custom tagihan│   A  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│                  │        │        │        │        │        │        │        │     │
│ PEMBAYARAN                                                                   │
│ ├ Lihat pembayaran│ALL  │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│ ├ Input manual  │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│ ├ Upload bukti  │   X  │   X   │   X   │   X   │   X   │   C   │   X   │   X │  X │
│ ├ Validasi      │   A  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  A │
│ ├ VA generation │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│ └ Payment gateway│  X  │   X   │   X   │   X   │   X   │   X   │   X   │   X │  A │
│                  │        │        │        │        │        │        │        │     │
│ TRANSAKSI                                                                     │
│ ├ Lihat transaksi│ ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│ ├ Rekap per siswa│ ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│ ├ Tunggakan     │  ALL │  DEP  │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│ └ Ekspor laporan│  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│                  │        │        │        │        │        │        │        │     │
│ LAPORAN                                                                      │
│ ├ Pemasukan     │  ALL │  DEP  │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│ ├ Arus kas      │  ALL │  DEP  │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│ ├ Buku besar    │  ALL │  DEP  │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│ ├ Neraca        │  ALL │  DEP  │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│ └ Laporan bulanan│ ALL │  DEP  │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│                  │        │        │        │        │        │        │        │     │
│ NOTIFIKASI                                                                   │
│ ├ Reminder jatuh tempo│ALL│ DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│ ├ Konfirmasi payment │ALL│ DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│ └ Alert tunggakan │  ALL │  DEP  │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│                  │        │        │        │        │        │        │        │     │
│ TABUNGAN                                                                      │
│ ├ Lihat tabungan │ ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X │ DEP│
│ ├ Setor          │   X │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│ ├ Tarik          │   X │   X   │   X   │   X   │   X   │   X   │   X   │   X │  C │
│ └ Mutasi         │   X │   X   │   X   │   X   │   X   │   X   │   X   │   X │ DEP│
│                  │        │        │        │        │        │        │        │     │
└─────────────────────────────────────────────────────────────────────────────────────┘

KOLOM BARU:
BEN = Bendahara
```

### 3.5 Modul Tahfiz

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MODUL TAHFIZ - MATRIKS AKSES (UNIQUE ISLAMIC)                                        │
├────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬─────┤
│ Fitur  │ KAMAD  │ WAKA_K │ WAKA_K │ WAKA_SP│ GURU   │WALI_KLS│ GURU_BK│  GPK  │TAHFZ│
│        │        │ KUR    │ SIS    │        │        │        │        │        │     │
├────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼─────┤
│        │        │        │        │        │        │        │        │        │     │
│ PROGRAM TAHFIZ                                                                 │
│ ├ Lihat program│ ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ ├ Setup target │   A │   C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Kurikulum tahfiz│ A│  C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ └ Jadwal tahfiz │   A │   C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│                  │        │        │        │        │        │        │        │     │
│ PENILAIAN HAFALAN                                                             │
│ ├ Lihat setoran│ ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ ├ Input setoran│   X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Penilaian bacaan│ X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Catatan murajaah│ X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ └ Nilai ubudiyah│   X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│                  │        │        │        │        │        │        │        │     │
│ MONITORING                                                                     │
│ ├ Progress siswa│ ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ ├ Grafik perkembangan│ALL│DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ ├ Konsistensi murajaah│ALL│DEP │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ └ Warning target│  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│                  │        │        │        │        │        │        │        │     │
│ UJIAN TAHFIZ                                                                   │
│ ├ Jadwal ujian  │   A │   C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Input nilai   │   X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Sertifikat    │   A │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ └ Integrasi rapor│  X  │   C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│                  │        │        │        │        │        │        │        │     │
│ MUNAQOSAH                                                                     │
│ ├ Pendaftaran   │   X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Jadwal        │   A │   C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Penilaian juri │   X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Generate sertifikat│ A│ X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ └ Database hafalan│ ALL│  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│                  │        │        │        │        │        │        │        │     │
│ TILAWATI                                                                      │
│ ├ Level tilawati│  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ ├ Penilaian     │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ └ Progress      │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│                  │        │        │        │        │        │        │        │     │
│ DASHBOARD TAHFIZ                                                              │
│ ├ Rekap progress│ ALL  │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ └ Statistik     │ ALL  │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│                  │        │        │        │        │        │        │        │     │
└─────────────────────────────────────────────────────────────────────────────────────┘

KOLOM BARU:
TAHFZ = Tahfiz/Pembina Tahfiz
```

### 3.6 Modul Inklusi (PDBK)

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MODUL INKLUSI (PDBK) - MATRIKS AKSES                                                 │
├────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬─────┤
│ Fitur  │ KAMAD  │ WAKA_K │ WAKA_K │ WAKA_SP│ GURU   │WALI_KLS│ GURU_BK│  GPK  │ TU  │
│        │        │ KUR    │ SIS    │        │        │        │        │        │     │
├────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼─────┤
│        │        │        │        │        │        │        │        │        │     │
│ IDENTIFIKASI ABK                                                           │
│ ├ Screening siswa│ ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│ ├ Input hasil   │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  X │
│ ├ Kategori ABK  │   A  │   C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Upload observasi│ X  │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  X │
│ └ Database ABK  │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │   X   │ALL │
│                  │        │        │        │        │        │        │        │     │
│ PROGRAM PPI                                                                  │
│ ├ Lihat PPI     │ ALL  │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│ ├ Buat PPI      │   A  │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  C │
│ ├ Edit PPI      │   A  │   U   │   X   │   X   │   X   │   X   │   X   │   U   │  U │
│ ├ Approve PPI   │   A  │   A   │   X   │   X   │   X   │   A   │   X   │   X   │  X │
│ └ Monitoring    │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│                  │        │        │        │        │        │        │        │     │
│ PENDAMPINGAN GPK                                                             │
│ ├ Jadwal        │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│ ├ Catatan sesi  │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  X │
│ ├ Strategi      │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  X │
│ └ Progress      │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│                  │        │        │        │        │        │        │        │     │
│ ASESMEN                                                                      │
│ ├ Input tes IQ  │   X  │   X   │   X   │   X   │   X   │   X   │   C   │   X   │  X │
│ ├ Upload hasil  │   X  │   X   │   X   │   X   │   X   │   X   │   C   │   X   │  X │
│ ├ Bakat minat   │   X  │   X   │   X   │   X   │   X   │   X   │   C   │   X   │  X │
│ ├ Grafik        │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│ └ Rekomendasi   │   X  │   X   │   X   │   X   │   X   │   X   │   C   │   X   │  X │
│                  │        │        │        │        │        │        │        │     │
│ ADAPTASI                                                                      │
│ ├ Modifikasi tugas│ X  │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  X │
│ ├ Penyesuaian asemen│ X │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  X │
│ ├ Akomodasi     │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  X │
│ └ Progress      │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│                  │        │        │        │        │        │        │        │     │
│ KOMUNIKASI ORANG TUA                                                        │
│ ├ Progress mingguan│ALL │ DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│ ├ Home program  │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   C   │  X │
│ └ Jadwal konsultasi│ALL │ DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│                  │        │        │        │        │        │        │        │     │
│ DASHBOARD INKLUSI                                                           │
│ ├ Statistik ABK│  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│ ├ Progress layanan│ALL │ DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│ └ GPK workload │  ALL │  DEP  │   X   │   X   │   X   │ OWN   │   X   │  OWN  │ALL │
│                  │        │        │        │        │        │        │        │     │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.7 Modul E-Office

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MODUL E-OFFICE - MATRIKS AKSES                                                        │
├────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┬─────┤
│ Fitur  │ KAMAD  │ WAKA_K │ WAKA_K │ WAKA_SP│ GURU   │WALI_KLS│ GURU_BK│  GPK  │ TU  │
│        │        │ KUR    │ SIS    │        │        │        │        │        │     │
├────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┼─────┤
│        │        │        │        │        │        │        │        │        │     │
│ SURAT MASUK/KELUAR                                                          │
│ ├ Lihat surat  │ ALL  │  DEP  │  DEP  │  DEP  │ OWN   │ OWN   │ OWN   │ OWN   │ALL │
│ ├ Input surat masuk│ X │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Generate surat keluar│ X│ X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Nomor surat   │   X  │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ └ Disposisi     │   A  │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  A │
│                  │        │        │        │        │        │        │        │     │
│ DISPOSISI DIGITAL                                                            │
│ ├ Lihat disposisi│ALL  │  DEP  │  DEP  │  DEP  │ OWN   │ OWN   │ OWN   │ OWN   │ALL │
│ ├ Buat instruksi│   C  │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  X │
│ ├ Tindak lanjut│   U  │   U   │   U   │   U   │   U   │   U   │   U   │   U   │  U │
│ └ Monitoring    │   A  │   A   │   A   │   A   │   X   │   X   │   X   │   X   │  A │
│                  │        │        │        │        │        │        │        │     │
│ KALENDER & AGENDA                                                            │
│ ├ Lihat kalender│ ALL │  ALL  │  ALL  │  ALL  │ ALL   │  ALL  │  ALL  │  ALL  │ALL │
│ ├ Setup agenda  │   A  │   C   │   C   │   C   │   X   │   X   │   X   │   X   │  C │
│ ├ Reminder      │  ALL │  ALL  │  ALL  │  ALL  │ ALL   │  ALL  │  ALL  │  ALL  │ALL │
│ └ Konfirmasi    │   A  │   X   │   X   │   X   │   C   │   C   │   X   │   X   │  C │
│                  │        │        │        │        │        │        │        │     │
│ E-SIGNATURE                                                                  │
│ ├ TTD digital   │   C  │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  X │
│ ├ Validasi      │   C  │   X   │   X   │   X   │   X   │   X   │   X   │   X   │  X │
│ ├ QR verification│R    │   R   │   R   │   R   │  R    │   R   │   R   │   R   │  R │
│ └ Log TTD       │  ALL │  DEP  │  DEP  │  DEP  │ OWN   │  OWN  │  OWN  │  OWN  │ALL │
│                  │        │        │        │        │        │        │        │     │
│ CLOUD STORAGE                                                                │
│ ├ Akses storage│ ALL  │  DEP  │  DEP  │  DEP  │ OWN   │  OWN  │  OWN  │  OWN  │ALL │
│ ├ Upload file  │   C  │   C   │   C   │   C   │   C   │   C   │   C   │   C   │  C │
│ ├ Delete file  │   A  │   U   │   U   │   U   │   X   │   X   │   X   │   X   │  A │
│ └ Folder permission│A  │   C   │   C   │   C   │   X   │   X   │   X   │   X   │  C │
│                  │        │        │        │        │        │        │        │     │
│ ARSIP AKREDITASI                                                            │
│ ├ Upload dokumen│   C  │   C   │   C   │   C   │   C   │   X   │   X   │   X   │  C │
│ ├ Mapping instrumen│ A │   C   │   X   │   X   │   X   │   X   │   X   │   X   │  C │
│ ├ Tracking status│ ALL │  DEP  │  DEP  │  DEP  │   X   │   X   │   X   │   X   │ALL │
│ └ View dokumen  │ ALL  │  DEP  │  DEP  │  DEP  │  DEP  │  DEP  │  DEP  │  DEP  │ALL │
│                  │        │        │        │        │        │        │        │     │
│ MONITORING PROGRAM KERJA                                                   │
│ ├ Input program│   C  │   C   │   C   │   C   │   X   │   X   │   X   │   X   │  C │
│ ├ Timeline     │  ALL │  ALL  │  ALL  │  ALL  │  DEP  │  DEP  │  DEP  │  DEP  │ALL │
│ ├ Progress     │   U  │   U   │   U   │   U   │   U   │   U   │   U   │   U   │  U │
│ └ Evaluasi     │   A  │   A   │   A   │   A   │   X   │   X   │   X   │   X   │  A │
│                  │        │        │        │        │        │        │        │     │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.8 Modul Portal

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MODUL PORTAL - MATRIKS AKSES                                                           │
├────────┬────────────────────────┬────────────────┬────────────────┬───────────────────┤
│ Fitur  │ ORANG_TUA              │ SISWA          │ GURU           │ CATATAN           │
├────────┼────────────────────────┼────────────────┼────────────────┼───────────────────┤
│        │                        │                │                │                   │
│ MONITORING AKADEMIK                                                           │
│ ├ Nilai anak       │   OWN   │   OWN    │   X    │ Ort: anak sendiri│
│ ├ Presensi         │   OWN   │   OWN    │   X    │ Ort: anak sendiri│
│ ├ Jadwal           │   OWN   │   OWN    │   X    │ Jadwal kelas    │
│ ├ Tugas/PR         │   OWN   │   OWN    │   X    │                 │
│ └ Catatan guru     │   OWN   │   OWN    │   C    │ Guru buat catatan│
│                    │        │          │        │                   │
│ MONITORING TAHFIZ                                                            │
│ ├ Hafalan          │   OWN   │   OWN    │   X    │ Ort: anak sendiri│
│ ├ Catatan tahfiz   │   OWN   │   OWN    │   C    │ Tahfiz buat     │
│ └ Jadwal munaqosah │   OWN   │   OWN    │   X    │                 │
│                    │        │          │        │                   │
│ MONITORING KESISWAAN                                                         │
│ ├ Pelanggaran      │   OWN   │   OWN    │   C    │ Guru input      │
│ ├ Prestasi         │   OWN   │   OWN    │   C    │ Guru input      │
│ └ Catatan BK       │   OWN   │   OWN    │   C    │ BK buat         │
│                    │        │          │        │                   │
│ MONITORING KEUANGAN                                                         │
│ ├ Tagihan          │   OWN   │   X      │   X    │                 │
│ ├ Pembayaran       │   OWN   │   X      │   X    │                 │
│ └ History          │   OWN   │   X      │   X    │                 │
│                    │        │          │        │                   │
│ KOMUNIKASI                                                                     │
│ ├ Pesan guru       │   C    │   C      │   C    │                 │
│ ├ Pengumuman       │   R    │   R      │   R    │ broadcast       │
│ ├ Konsultasi       │   C    │   X      │   C    │                 │
│ └ Notifikasi WA    │   R    │   R      │   R    │ auto-send       │
│                    │        │          │        │                   │
│ AKSI                                                                           │
│ ├ Request izin     │   C    │   C      │   X    │                 │
│ ├ Upload bukti byr │   C    │   X      │   X    │                 │
│ ├ Konfirmasi       │   C    │   X      │   X    │                 │
│ └ Feedback/respon  │   C    │   C      │   C    │                 │
│                    │        │          │        │                   │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. HIERARKI AKSES DATA (SCOPE)

### 4.1 Scope Definition

```
┌─────────────────────────────────────────────────────────────────────┐
│                         SCOPE LEVELS                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ 1. PERSONAL (P)                                                     │
│    → Akses hanya untuk data diri sendiri                            │
│    → Contoh: Siswa lihat nilai dirinya sendiri                     │
│                                                                     │
│ 2. ANAK (A)                                                         │
│    → Akses untuk data anak yang diampu                             │
│    → Contoh: Orang tua lihat nilai anak kandung                   │
│                                                                     │
│ 3. KELAS (K)                                                        │
│    → Akses untuk seluruh siswa di kelasnya                         │
│    → Contoh: Wali kelas lihat seluruh siswa kelasnya              │
│                                                                     │
│ 4. DEPARTEMEN (D)                                                   │
│    → Akses untuk data di bidang/departemennya                      │
│    → Contoh: Waka Kurikulum akses data akademik                   │
│                                                                     │
│ 5. SEKOLAH (S)                                                      │
│    → Akses untuk seluruh data di madrasah                          │
│    → Contoh: Kepala Madrasah lihat semua data                      │
│                                                                     │
│ 6. YAYASAN (Y)                                                      │
│    → Akses untuk seluruh data di semua MTs dalam yayasan           │
│    → Contoh: Yayasan lihat data semua sekolah                      │
│                                                                     │
│ 7. SISTEM (SYS)                                                     │
│    → Akses penuh untuk seluruh sistem                              │
│    → Contoh: Super Admin akses semua data                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.2 Scope per Role

```
┌─────────────────────────────────────────────────────────────────────┐
│                     SCOPE PER ROLE                                  │
├──────────────────┬──────────────────────────────────────────────────┤
│ Role             │ Data Scope                                      │
├──────────────────┼──────────────────────────────────────────────────┤
│ SUPER_ADMIN      │ SYS: Semua data semua sekolah                   │
├──────────────────┼──────────────────────────────────────────────────┤
│ KEPALA_MADRASAH  │ S: Semua data di madrasahnya                    │
├──────────────────┼──────────────────────────────────────────────────┤
│ YAYASAN          │ Y: Semua data di semua MTs dalam yayasan         │
├──────────────────┼──────────────────────────────────────────────────┤
│ WAKA_KURIKULUM   │ D: Data akademik di madrasahnya                  │
├──────────────────┼──────────────────────────────────────────────────┤
│ WAKA_KESISWAAN   │ D: Data kesiswaan di madrasahnya                 │
├──────────────────┼──────────────────────────────────────────────────┤
│ WAKA_SARPRAS     │ D: Data sarpras di madrasahnya                   │
├──────────────────┼──────────────────────────────────────────────────┤
│ GURU             │ D/K: Data mapel yang diampu + kelas yang diajar  │
├──────────────────┼──────────────────────────────────────────────────┤
│ WALI_KELAS       │ K: Semua siswa di kelasnya                       │
├──────────────────┼──────────────────────────────────────────────────┤
│ GURU_BK          │ D: Data konseling seluruh madrasah               │
├──────────────────┼──────────────────────────────────────────────────┤
│ GURU_PIK          │ K: Siswa inklusi yang diampu                    │
├──────────────────┼──────────────────────────────────────────────────┤
│ TAHFIZ           │ D/K: Data tahfiz seluruh / kelas tertentu        │
├──────────────────┼──────────────────────────────────────────────────┤
│ TATA_USAHA       │ S: Akses broad di madrasah (admin function)      │
├──────────────────┼──────────────────────────────────────────────────┤
│ BENDAHARA        │ D: Data keuangan di madrasahnya                  │
├──────────────────┼──────────────────────────────────────────────────┤
│ ORANG_TUA        │ A: Data anak kandung/wali yang diampu            │
├──────────────────┼──────────────────────────────────────────────────┤
│ SISWA            │ P: Data dirinya sendiri                          │
└──────────────────┴──────────────────────────────────────────────────┘
```

---

## 5. ATURAN KHUSUS

### 5.1 Override Rules

```
┌─────────────────────────────────────────────────────────────────────┐
│                       OVERRIDE RULES                                │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ OR-01: Orang tua hanya bisa lihat data ANAKNYA                     │
│        → Sistem Wajib validasi hubungan (father/mother/guardian)   │
│        → Tidak bisa lihat data siswa lain meskipun satu kelas      │
│                                                                     │
│ OR-02: Siswa hanya bisa lihat data DIRINYA                         │
│        → Tidak bisa lihat nilai teman sekelas                      │
│        → Tidak bisa edit data diri sendiri (kecuali kontak)        │
│                                                                     │
│ OR-03: Guru hanya bisa edit nilai MAPEL YANG DIAMPU                │
│        → Tidak bisa edit nilai mapel guru lain                     │
│        → Validasi: guru_mapel.kelas_id + mapel_id                   │
│                                                                     │
│ OR-04: Wali kelas bisa input presensi untuk kelasnya               │
│        → Tidak bisa input presensi kelas lain                      │
│                                                                     │
│ OR-05: GPK hanya bisa edit data siswa INKLUSI yang diampu          │
│        → Tidak bisa edit siswa non-inklusi                         │
│                                                                     │
│ OR-06: Kepala Madrasah bisa APPROVE semua dokumen                   │
│        → Tapi tidak bisa mengedit isi dokumen                      │
│                                                                     │
│ OR-07: TU bisa input data tapi tidak bisa delete tanpa approval     │
│        → Delete hanya dengan konfirmasi KAMAD                      │
│                                                                     │
│ OR-08: Bendahara bisa konfirmasi pembayaran                         │
│        → Tapi tidak bisa modify tagihan tanpa otorisasi            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.2 Segregation of Duties (SoD)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SEGREGATION OF DUTIES                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ SoD-01: Input vs Approval                                           │
│         → Guru input nilai → tidak bisa approve nilai sendiri       │
│         → Approval oleh Waka Kurikulum atau Kepala Madrasah         │
│                                                                     │
│ SoD-02: Payment vs Reconciliation                                   │
│         → TU/Bendahara input transaksi → tidak bisa approve sendiri │
│         → Approval oleh Kepala Madrasah                             │
│                                                                     │
│ SoD-03: Configuration vs Transaction                                │
│         → Super Admin setup konfigurasi → tidak bisa input transaksi│
│         → Operator input transaksi → tidak bisa ubah konfigurasi    │
│                                                                     │
│ SoD-04: Request vs Approval Budget                                  │
│         → Guru/Waka request → KAMAD approve                         │
│         → KAMAD request → YAYASAN approve                           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 6. IMPLEMENTASI RBAC

### 6.1 Database Schema (Simplified)

```sql
-- Role Definition
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100),
    description TEXT,
    parent_role_id INT REFERENCES roles(id),
    scope_level ENUM('PERSONAL','ANAK','KELAS','DEPARTEMEN','SEKOLAH','YAYASAN','SISTEM'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Permission Definition
CREATE TABLE permissions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    resource VARCHAR(50) NOT NULL,
    action ENUM('create','read','update','delete','approve') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Role-Permission Mapping
CREATE TABLE role_permissions (
    role_id INT REFERENCES roles(id) ON DELETE CASCADE,
    permission_id INT REFERENCES permissions(id) ON DELETE CASCADE,
    scope_type ENUM('OWN','ALL','DEP','SCH') DEFAULT 'OWN',
    PRIMARY KEY (role_id, permission_id)
);

-- User-Role Assignment
CREATE TABLE user_roles (
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    role_id INT REFERENCES roles(id) ON DELETE CASCADE,
    scope_id INT, -- untuk limit scope (misal: kelas_id tertentu)
    scope_type VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id)
);

-- Access Log (for audit)
CREATE TABLE access_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    resource VARCHAR(100),
    action VARCHAR(50),
    resource_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 6.2 Middleware Implementation (Pseudo-code)

```javascript
// RBAC Middleware Example (Node.js/Express)

const checkPermission = (resource, action) => {
    return async (req, res, next) => {
        try {
            const userId = req.user.id;
            const userRoles = await getUserRoles(userId);
            const userScope = await getUserScope(userId);
            
            // Check each role
            for (const role of userRoles) {
                const hasPermission = await db.query(`
                    SELECT * FROM role_permissions rp
                    JOIN permissions p ON rp.permission_id = p.id
                    WHERE rp.role_id = $1 
                    AND p.resource = $2 
                    AND p.action = $3
                `, [role.id, resource, action]);
                
                if (hasPermission.rows.length > 0) {
                    // Check scope
                    const scopeType = hasPermission.rows[0].scope_type;
                    const resourceOwner = await getResourceOwner(resource, req.params.id);
                    
                    if (validateScope(scopeType, userScope, resourceOwner)) {
                        return next(); // Allowed
                    }
                }
            }
            
            return res.status(403).json({ 
                error: 'Access denied. Insufficient permissions.' 
            });
        } catch (error) {
            return res.status(500).json({ error: 'Authorization error' });
        }
    };
};

// Example usage in routes
app.post('/api/nilai', 
    authenticate, 
    checkPermission('nilai', 'create'),
    nilaiController.create
);
```

### 6.3 Frontend Permission Directive (Vue.js Example)

```javascript
// Vue Permission Directive

Vue.directive('can', {
    inserted(el, binding) {
        const { action, resource } = binding.value;
        const userPermissions = store.getters['user/permissions'];
        
        if (!userPermissions.includes(`${action}_${resource}`)) {
            el.style.display = 'none';
            // atau redirect/log
        }
    }
});

// Usage in template
<button v-can="{ action: 'create', resource: 'nilai' }">
    Tambah Nilai
</button>

// Conditional rendering
v-if="$can('approve', 'rapor')"
```

---

## 7. AUDIT TRAIL

### 7.1 Events yang Di-log

```
┌─────────────────────────────────────────────────────────────────────┐
│                      AUDIT TRAIL EVENTS                             │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ DATA CHANGES (Write Operations):                                    │
│ ├── CREATE: Semua operasi create data                              │
│ ├── UPDATE: Semua operasi update data                              │
│ ├── DELETE: Semua operasi delete data                              │
│ └── APPROVE: Semua operasi approval/disposisi                      │
│                                                                     │
│ AUTHENTICATION EVENTS:                                              │
│ ├── LOGIN: User login ke sistem                                    │
│ ├── LOGOUT: User logout                                             │
│ ├── FAILED_LOGIN: Attempt login gagal                               │
│ ├── PASSWORD_CHANGE: Password dirubah                               │
│ └── SESSION_EXPIRE: Session timeout                                 │
│                                                                     │
│ SENSITIVE OPERATIONS:                                               │
│ ├── EXPORT_DATA: Export data dalam jumlah besar                    │
│ ├── VIEW_SENSITIVE: Lihat data sensitif (NIK, salary)              │
│ ├── CHANGE_PERMISSION: Ubah hak akses user lain                    │
│ ├── BULK_OPERATION: Operasi bulk (delete/update banyak)            │
│ └── SYSTEM_CONFIG: Ubah konfigurasi sistem                         │
│                                                                     │
│ INTEGRATION EVENTS:                                                 │
│ ├── SYNC_EMIS: Sinkronisasi dengan EMIS                           │
│ ├── SYNC_DAPODIK: Sinkronisasi dengan DAPODIK                     │
│ ├── EXPORT_RDM: Export data ke RDM                                 │
│ └── PAYMENT_CONFIRM: Konfirmasi pembayaran                         │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 7.2 Log Format

```json
{
    "timestamp": "2026-06-12T10:30:45Z",
    "event_id": "uuid-v4",
    "user_id": 12345,
    "user_name": "Ahmad Fauzi",
    "user_role": "guru",
    "ip_address": "192.168.1.100",
    "action": "UPDATE",
    "resource": "nilai",
    "resource_id": 67890,
    "resource_name": "Nilai Matematika - Kelas 7A",
    "changes": {
        "before": { "nilai": 75 },
        "after": { "nilai": 78 }
    },
    "reason": "Koreksi input nilai",
    "session_id": "abc123"
}
```

---

## 8. KESIMPULAN

### 8.1 Ringkasan RBAC

```
┌─────────────────────────────────────────────────────────────────────┐
│                   RINGKASAN RBAC                                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ TOTAL ROLE: 15 roles                                                │
│ TOTAL PERMISSION: ~100 permissions                                 │
│ TOTAL FEATURES: 200+ features                                      │
│                                                                     │
│ ROLE HIERARCHY: Yes (inheritance)                                   │
│ MULTI-ROLE PER USER: Yes (support multiple roles)                  │
│ SCOPE-BASED ACCESS: Yes (personal to sistem)                       │
│ AUDIT TRAIL: Yes (comprehensive logging)                           │
│ SoD IMPLEMENTATION: Yes                                             │
│                                                                     │
│ EXAMPLE SCENARIOS:                                                  │
│ ├── Guru bisa input nilai tapi tidak bisa approve                  │
│ ├── TU bisa input data tapi tidak bisa delete tanpa approval       │
│ ├── Orang tua hanya bisa lihat data anaknya sendiri                │
│ └── Kepala Madrasah bisa approve semua tapi tidak bisa edit        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 next Steps

```
NEXT STEPS:
├── 1. Review matriks akses dengan stakeholder
├── 2. Finalisasi role definition
├── 3. Validasi scope rules
├── 4. Implementasi di development
├── 5. Testing akses per role
└── 6. User acceptance testing
```

---

## LAMPIRAN

### A. Quick Reference Card

```
┌─────────────────────────────────────────────────────────────────────┐
│                 QUICK REFERENCE - PERMISSION CODES                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ AUTHENTICATION:                                                     │
│ - login, logout, reset_password, change_password                   │
│                                                                     │
│ STUDENT:                                                            │
│ - create_student, read_student, update_student, delete_student     │
│ - import_student, export_student                                   │
│                                                                     │
│ ACADEMIC:                                                           │
│ - create_nilai, read_nilai, update_nilai, delete_nilai             │
│ - approve_nilai, export_nilai                                      │
│ - create_rapor, read_rapor, approve_rapor, print_rapor             │
│ - create_jadwal, read_jadwal, update_jadwal                        │
│                                                                     │
│ FINANCIAL:                                                          │
│ - create_tagihan, read_tagihan, update_tagihan                     │
│ - create_pembayaran, read_pembayaran, confirm_pembayaran           │
│ - read_laporan_keuangan, export_laporan_keuangan                   │
│                                                                     │
│ etc...                                                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

*Dokumen ini merupakan bagian dari paket dokumentasi proyek SIMT MTs*
*Versi: 1.0 | Tanggal: 12 Juni 2026*