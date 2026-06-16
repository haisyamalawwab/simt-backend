<?php

use Illuminate\Support\Facades\Route;
use Modules\Akademik\Http\Controllers\AkademikController;
use Modules\Akademik\Http\Controllers\GradeController;
use App\Http\Middleware\SetTenantFromUser;

Route::middleware(['auth', SetTenantFromUser::class, 'module.active:Akademik'])->group(function () {
    // Dashboard Akademik
    Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik.index')->middleware('permission:view_akademik');

    // Rombel (Classes)
    Route::get('/akademik/classes', [AkademikController::class, 'classes'])->name('akademik.classes')->middleware('permission:view_akademik');
    Route::post('/akademik/classes', [AkademikController::class, 'storeClass'])->name('akademik.classes.store')->middleware('permission:manage_akademik');

    // Mapel (Subjects)
    Route::get('/akademik/subjects', [AkademikController::class, 'subjects'])->name('akademik.subjects')->middleware('permission:view_akademik');
    Route::post('/akademik/subjects', [AkademikController::class, 'storeSubject'])->name('akademik.subjects.store')->middleware('permission:manage_akademik');

    // Nilai (Grades)
    Route::get('/akademik/grades', [GradeController::class, 'index'])->name('grades.index')->middleware('permission:view_akademik');
    Route::get('/akademik/grades/create', [GradeController::class, 'create'])->name('grades.create')->middleware('permission:manage_grades');
    Route::post('/akademik/grades', [GradeController::class, 'store'])->name('grades.store')->middleware('permission:manage_grades');
    Route::get('/akademik/grades/rapor', [GradeController::class, 'rapor'])->name('grades.rapor')->middleware('permission:view_akademik');
    Route::get('/akademik/grades/{grade}', [GradeController::class, 'show'])->name('grades.show')->middleware('permission:view_akademik');
    Route::put('/akademik/grades/{grade}', [GradeController::class, 'update'])->name('grades.update')->middleware('permission:manage_grades');
});
