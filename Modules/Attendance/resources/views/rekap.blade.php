@extends('layouts.app')

@section('title', 'Rekap Presensi')

@section('content')
@php
    $start = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
    $daysInMonth = $start->daysInMonth;
@endphp
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="flex items-center space-x-3">
            <a href="{{ route('attendance.index', ['class_id' => $class->id, 'date' => now()->toDateString()]) }}" class="p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Rekap Presensi Bulanan</h1>
                <p class="text-slate-500 mt-1">Kelas: <span class="font-semibold text-slate-700">{{ $class->name }}</span> • Periode: <span class="font-semibold text-slate-700">{{ $start->translatedFormat('F Y') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            <div class="w-64">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Bulan</label>
                <input type="month" name="month" value="{{ $month }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none text-slate-700 font-semibold bg-white">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold shadow-sm transition">
                    Tampilkan
                </button>
                <a href="{{ route('attendance.rekap.export', ['class_id' => $class->id, 'month' => $month]) }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Excel
                </a>
            </div>
        </form>
    </div>

    <!-- Table Recap -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold text-left">
                        <th class="px-4 py-3 sticky left-0 bg-slate-50 shadow-[1px_0_0_0_rgba(0,0,0,0.05)] z-10 w-44">Nama Siswa</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            <th class="px-1 py-3 text-center w-8">{{ $d }}</th>
                        @endfor
                        <th class="px-2 py-3 text-center bg-emerald-50 text-emerald-800 font-bold border-l border-emerald-100 w-8">H</th>
                        <th class="px-2 py-3 text-center bg-rose-50 text-rose-800 font-bold border-l border-rose-100 w-8">A</th>
                        <th class="px-2 py-3 text-center bg-amber-50 text-amber-800 font-bold border-l border-amber-100 w-8">I</th>
                        <th class="px-2 py-3 text-center bg-amber-50 text-amber-800 font-bold border-l border-amber-100 w-8">S</th>
                        <th class="px-2 py-3 text-center bg-blue-50 text-blue-800 font-bold border-l border-blue-100 w-8">T</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($students as $s)
                        @php $counts = ['H'=>0,'A'=>0,'I'=>0,'S'=>0,'T'=>0]; @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-2.5 font-semibold text-slate-900 sticky left-0 bg-white shadow-[1px_0_0_0_rgba(0,0,0,0.05)] z-10 whitespace-nowrap">{{ $s->name }}</td>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $dateKey = $start->copy()->day($d)->format('Y-m-d');
                                    $rec = $s->monthly[$dateKey] ?? null;
                                    $st = $rec->status ?? null;
                                    if ($st) $counts[$st]++;
                                    
                                    $bg = match($st) {
                                        'H' => 'bg-emerald-50 text-emerald-700 font-bold',
                                        'A' => 'bg-rose-50 text-rose-700 font-bold',
                                        'I','S' => 'bg-amber-50 text-amber-700 font-bold',
                                        'T' => 'bg-blue-50 text-blue-700 font-bold',
                                        default => 'text-slate-300',
                                    };
                                @endphp
                                <td class="px-1 py-2.5 text-center {{ $bg }}">{{ $st ?? '·' }}</td>
                            @endfor
                            <td class="px-2 py-2.5 text-center font-bold bg-emerald-50/50 text-emerald-700 border-l border-emerald-100/50">{{ $counts['H'] }}</td>
                            <td class="px-2 py-2.5 text-center font-bold bg-rose-50/50 text-rose-700 border-l border-rose-100/50">{{ $counts['A'] }}</td>
                            <td class="px-2 py-2.5 text-center font-bold bg-amber-50/50 text-amber-700 border-l border-amber-100/50">{{ $counts['I'] }}</td>
                            <td class="px-2 py-2.5 text-center font-bold bg-amber-50/50 text-amber-700 border-l border-amber-100/50">{{ $counts['S'] }}</td>
                            <td class="px-2 py-2.5 text-center font-bold bg-blue-50/50 text-blue-700 border-l border-blue-100/50">{{ $counts['T'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 6 }}" class="px-6 py-10 text-center text-slate-400">
                                Tidak ada data siswa untuk kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Legends -->
    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex flex-wrap gap-4 text-xs font-medium text-slate-500">
        <div class="flex items-center"><span class="w-5 h-5 rounded bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center font-bold mr-2">H</span> Hadir</div>
        <div class="flex items-center"><span class="w-5 h-5 rounded bg-rose-50 border border-rose-100 text-rose-700 flex items-center justify-center font-bold mr-2">A</span> Alpa</div>
        <div class="flex items-center"><span class="w-5 h-5 rounded bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center font-bold mr-2">I</span> Izin</div>
        <div class="flex items-center"><span class="w-5 h-5 rounded bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center font-bold mr-2">S</span> Sakit</div>
        <div class="flex items-center"><span class="w-5 h-5 rounded bg-blue-50 border border-blue-100 text-blue-700 flex items-center justify-center font-bold mr-2">T</span> Terlambat</div>
        <div class="flex items-center"><span class="w-5 h-5 text-slate-300 flex items-center justify-center font-bold mr-2">·</span> Belum ada data</div>
    </div>
</div>
@endsection
