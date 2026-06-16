<?php

use Illuminate\Support\Facades\Route;
use Modules\Student\Http\Controllers\StudentController;
use App\Http\Middleware\IdentifyTenant;
use App\Http\Middleware\SetTenantFromUser;

Route::middleware(['auth', SetTenantFromUser::class, 'module.active:Student'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index')->middleware('permission:view_students');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create')->middleware('permission:create_students');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store')->middleware('permission:create_students');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit')->middleware('permission:edit_students');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update')->middleware('permission:edit_students');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy')->middleware('permission:delete_students');
    Route::get('/students/import', [StudentController::class, 'importForm'])->name('students.import.form')->middleware('permission:import_students');
    Route::post('/students/import/upload', [StudentController::class, 'importUpload'])->name('students.import.upload')->middleware('permission:import_students');
    Route::post('/students/import/commit', [StudentController::class, 'importCommit'])->name('students.import.commit')->middleware('permission:import_students');
});
