@extends('layouts.app')

@section('title', 'Presensi Harian')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Presensi Siswa</h1>
            <p class="text-slate-500 mt-1">Lakukan presensi harian kelas. Notifikasi WhatsApp akan diantrikan otomatis ke wali murid untuk ketidakhadiran.</p>
        </div>
        @if($selectedClass)
        <div>
            <a href="{{ route('attendance.rekap', ['class_id' => $selectedClass, 'month' => now()->format('Y-m')]) }}" class="inline-flex items-center px-4 py-2 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-sm transition">
                <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5m11 0h.01M17 21h3a2 2 0 002-2v-3a2 2 0 00-2-2h-3M3 7h18M3 12h18M3 17h18" />
                </svg>
                Rekap Bulanan
            </a>
        </div>
        @endif
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
        <form id="attendance-filter-form" class="flex flex-col sm:flex-row gap-4" onsubmit="event.preventDefault(); navigateToGrid();">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Rombongan Belajar</label>
                <select name="class_id" id="class_id_select" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none bg-white font-medium text-slate-700" onchange="navigateToGrid()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->schoolYear->name ?? '-' }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-64">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Presensi</label>
                <input type="date" name="date" id="date_input" value="{{ $date }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none text-slate-700 font-medium" onchange="navigateToGrid()">
            </div>
        </form>
    </div>

    @if($students && $students->count())
    <!-- Interactive Presensi Grid -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Kelas Aktif</span>
                <h2 class="text-lg font-bold text-slate-900 mt-0.5">Rombel: {{ $students->first()->classes->first()->name ?? '' }}</h2>
            </div>
            <div class="text-xs font-medium text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg flex items-center">
                <svg class="w-3.5 h-3.5 text-blue-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Default status: <span class="font-bold text-emerald-600 ml-1">Hadir</span>. Ketuk kartu siswa untuk mengubah.
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-6" id="attendance-grid">
            @foreach($students as $s)
            @php
                $today = $s->attendance_today;
                $status = $today->status ?? 'H';
            @endphp
            <div class="border border-slate-150 rounded-xl p-4 flex items-center justify-between cursor-pointer select-none attendance-card hover:border-blue-400 hover:shadow-md hover:-translate-y-0.5 transition duration-200"
                 data-student-id="{{ $s->id }}"
                 data-status="{{ $status }}">
                <div class="space-y-1">
                    <div class="font-semibold text-slate-900">{{ $s->name }}</div>
                    <div class="text-xs font-mono text-slate-400">NIS: {{ $s->nis ?? '-' }}</div>
                </div>
                <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold transition shadow-sm
                    {{ $status === 'H' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                    {{ $status === 'A' ? 'bg-rose-50 text-rose-700 border border-rose-100' : '' }}
                    {{ in_array($status, ['I','S']) ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                    {{ $status === 'T' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}
                ">
                    @if($status === 'H')
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                    @elseif($status === 'A')
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                    @elseif(in_array($status, ['I','S']))
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                    @elseif($status === 'T')
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                    @endif
                    {{ \App\Models\Attendance::statusLabel($status) }}
                </span>
            </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm font-medium text-slate-600 flex items-center" id="status-text">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-400 mr-2"></span>
                Belum ada perubahan dilakukan
            </div>
            <button id="save-btn" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition" disabled onclick="saveAttendance()">
                Simpan Presensi
            </button>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center text-slate-400">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="text-lg font-semibold text-slate-700 block">Pilih Rombongan Belajar</span>
        <span class="text-sm text-slate-400 mt-1 block">Silakan pilih kelas dan tanggal terlebih dahulu untuk menampilkan data presensi siswa.</span>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function navigateToGrid() {
    const classId = document.getElementById('class_id_select').value;
    const date = document.getElementById('date_input').value;
    if (classId) {
        window.location.href = `{{ url('/attendance/class') }}/${classId}/${date}`;
    } else {
        window.location.href = `{{ url('/attendance') }}`;
    }
}

const statusOrder = ['H','A','I','S','T'];
const statusLabels = {H:'Hadir',A:'Alpa',I:'Izin',S:'Sakit',T:'Terlambat'};
const statusClasses = {
    H:'bg-emerald-50 text-emerald-700 border border-emerald-100',
    A:'bg-rose-50 text-rose-700 border border-rose-100',
    I:'bg-amber-50 text-amber-700 border border-amber-100',
    S:'bg-amber-50 text-amber-700 border border-amber-100',
    T:'bg-blue-50 text-blue-700 border border-blue-100',
};
const dotClasses = {
    H:'bg-emerald-500',
    A:'bg-rose-500',
    I:'bg-amber-500',
    S:'bg-amber-500',
    T:'bg-blue-500',
};

document.querySelectorAll('.attendance-card').forEach(card => {
    card.addEventListener('click', () => {
        let current = card.dataset.status;
        let nextIdx = (statusOrder.indexOf(current) + 1) % statusOrder.length;
        let next = statusOrder[nextIdx];
        card.dataset.status = next;
        
        const badge = card.querySelector('.status-badge');
        badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${dotClasses[next]} mr-1.5"></span>${statusLabels[next]}`;
        badge.className = 'status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold transition shadow-sm ' + statusClasses[next];
        
        document.getElementById('save-btn').disabled = false;
        const statusText = document.getElementById('status-text');
        statusText.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>Ada perubahan belum disimpan — Kirim notifikasi WA untuk ketidakhadiran.`;
    });
});

async function saveAttendance() {
    const btn = document.getElementById('save-btn');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    const records = Array.from(document.querySelectorAll('.attendance-card')).map(card => ({
        student_id: card.dataset.studentId,
        status: card.dataset.status,
    }));

    const res = await fetch('{{ route("attendance.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            class_id: {{ $selectedClass ?? 'null' }},
            date: '{{ $date }}',
            records,
        }),
    });

    const data = await res.json();
    if (data.success) {
        alert(data.message || 'Presensi berhasil disimpan.');
        location.reload();
    } else {
        alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
        btn.disabled = false;
        btn.textContent = 'Simpan Presensi';
    }
}
</script>
@endpush
