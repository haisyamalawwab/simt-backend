<?php

namespace Modules\Akademik\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
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
        $classes = SchoolClass::with('schoolYear')->paginate(10);
        return view('akademik::classes', compact('classes'));
    }

    /**
     * Manajemen Mata Pelajaran
     */
    public function subjects()
    {
        $subjects = Subject::paginate(10);
        return view('akademik::subjects', compact('subjects'));
    }
}
