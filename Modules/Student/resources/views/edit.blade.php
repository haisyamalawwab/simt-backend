@extends('layouts.app')

@section('title', 'Edit Siswa')

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
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Siswa</h1>
            <p class="text-slate-500 mt-1">Ubah informasi biodata siswa dan kelas di bawah ini.</p>
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

    <form action="{{ route('students.update', $student) }}" method="POST" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Biodata Siswa</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name', $student->name) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Masukkan nama lengkap siswa">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Kelamin</label>
                    <select name="gender" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none bg-white">
                        <option value="">- Pilih Jenis Kelamin -</option>
                        <option value="L" @selected(old('gender', $student->gender)==='L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $student->gender)==='P')>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIS (Nomor Induk Siswa)</label>
                    <input name="nis" value="{{ old('nis', $student->nis) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Contoh: 12456">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NISN</label>
                    <input name="nisn" value="{{ old('nisn', $student->nisn) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Contoh: 009887122">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tempat Lahir</label>
                    <input name="birth_place" value="{{ old('birth_place', $student->birth_place) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Contoh: Jakarta">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', optional($student->birth_date)->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none" placeholder="Masukkan alamat lengkap rumah">{{ old('address', $student->address) }}</textarea>
                </div>
            </div>
        </div>

        @if($student->guardians->count())
        <div class="border-t border-slate-100 pt-5">
            <h3 class="text-sm font-bold text-slate-800 mb-2">Akun Wali Terkait</h3>
            <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-4 space-y-2">
                @foreach($student->guardians as $g)
                    <div class="flex items-center text-sm text-slate-700">
                        <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-semibold text-slate-900 mr-1">{{ $g->name }}</span> 
                        <span class="text-slate-500 mr-2">({{ $g->pivot->relation ?? 'Wali' }})</span> 
                        <span class="text-slate-400">•</span> 
                        <span class="ml-2 font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded">{{ $g->phone }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
            <a href="{{ route('students.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
