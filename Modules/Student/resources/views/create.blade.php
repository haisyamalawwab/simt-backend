@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('students.index') }}" class="p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 shadow-sm transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Tambah Siswa</h1>
            <p class="text-slate-500 mt-1">Lengkapi data profil siswa dan wali di bawah ini.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl bg-red-50 text-red-800 p-4 border border-red-100 text-sm">
            <div class="flex">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <span class="font-bold">Terjadi kesalahan input:</span>
                    <ul class="list-disc pl-5 mt-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('students.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
        @csrf
        
        <div>
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Biodata Siswa</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Masukkan nama lengkap siswa">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Kelamin</label>
                    <select name="gender" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none bg-white">
                        <option value="">- Pilih Jenis Kelamin -</option>
                        <option value="L" @selected(old('gender')==='L')>Laki-laki</option>
                        <option value="P" @selected(old('gender')==='P')>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas</label>
                    <select name="class_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none bg-white">
                        <option value="">- Belum Ditentukan -</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('class_id')==$class->id)>{{ $class->name }} ({{ $class->schoolYear->name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIS (Nomor Induk Siswa)</label>
                    <input name="nis" value="{{ old('nis') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Contoh: 12456">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NISN</label>
                    <input name="nisn" value="{{ old('nisn') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Contoh: 009887122">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tempat Lahir</label>
                    <input name="birth_place" value="{{ old('birth_place') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Contoh: Jakarta">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Masukkan alamat lengkap rumah">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Biodata Wali Murid <span class="text-xs font-normal text-slate-500">(Opsional)</span></h3>
            <p class="text-xs text-blue-600 bg-blue-50/50 border border-blue-100 p-3 rounded-xl mb-4 leading-relaxed">
                <strong>Catatan:</strong> Jika nomor WA diisi, sistem akan otomatis mendaftarkan akun wali murid agar wali murid dapat mengakses Portal Wali Murid (Next.js) dengan nomor tersebut.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap Wali</label>
                    <input name="guardian_name" value="{{ old('guardian_name') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Contoh: Budi Santoso">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp Wali</label>
                    <input name="guardian_phone" value="{{ old('guardian_phone') }}" placeholder="Contoh: 08123456789" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
            <a href="{{ route('students.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
