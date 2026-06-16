<?php

namespace Modules\Attendance\Exports;

use App\Models\SchoolClass;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceRecapExport implements FromView, WithTitle, ShouldAutoSize
{
    protected SchoolClass $class;
    protected string $month;

    public function __construct(SchoolClass $class, string $month)
    {
        $this->class = $class;
        $this->month = $month;
    }

    public function view(): View
    {
        $start = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $this->month)->endOfMonth();
        $students = $this->class->students()->where('status', 'active')->get();

        $attendances = Attendance::where('class_id', $this->class->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->groupBy('student_id');

        $students->each(function ($student) use ($attendances) {
            $student->monthly = $attendances->get($student->id, collect())->keyBy(fn ($a) => $a->date->format('Y-m-d'));
        });

        return view('attendance::rekap_excel', [
            'class' => $this->class,
            'month' => $this->month,
            'students' => $students,
            'start' => $start,
            'daysInMonth' => $start->daysInMonth
        ]);
    }

    public function title(): string
    {
        return 'Rekap Presensi - ' . $this->class->name;
    }
}
