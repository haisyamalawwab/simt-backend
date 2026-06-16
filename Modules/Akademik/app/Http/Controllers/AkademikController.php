<?php

namespace Modules\Akademik\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;

class AkademikController extends Controller
{
    /**
     * Dashboard Akademik - Ringkasan Rombel & Mapel
     */
    public function index()
    {
        $classes = SchoolClass::with('schoolYear')->get();
        $subjects = Subject::all();
        
        return view('akademik::index', compact('classes', 'subjects'));
    }

    /**
     * Manajemen Rombongan Belajar (Rombel)
     */
    public function classes()
    {
        $classes = SchoolClass::with(['schoolYear', 'teacher'])->paginate(10);
        $teachers = User::role('guru')->get();
        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        return view('akademik::classes', compact('classes', 'teachers', 'schoolYears'));
    }

    /**
     * Simpan Rombel Baru
     */
    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'grade' => 'required|string|max:10',
            'school_year_id' => 'required|exists:school_years,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        SchoolClass::create($validated);

        return redirect()->route('akademik.classes')
            ->with('success', 'Rombongan belajar berhasil ditambahkan.');
    }

    /**
     * Manajemen Mata Pelajaran
     */
    public function subjects()
    {
        $subjects = Subject::with(['class', 'teacher'])->paginate(10);
        $classes = SchoolClass::orderBy('grade')->orderBy('name')->get();
        $teachers = User::role('guru')->get();
        return view('akademik::subjects', compact('subjects', 'classes', 'teachers'));
    }

    /**
     * Simpan Mapel Baru
     */
    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'school_class_id' => 'required|exists:school_classes,id',
            'teacher_id' => 'nullable|exists:users,id',
            'hours_per_week' => 'required|integer|min:1',
            'category' => 'required|in:UMUM,AGAMA_ISLAM,MUATAN_LOKAL,PENGEMBANGAN_DIRI,EKSTRAKURIKULER',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Subject::create($validated);

        return redirect()->route('akademik.subjects')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }
}
