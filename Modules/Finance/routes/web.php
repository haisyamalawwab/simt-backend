<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;
use App\Http\Middleware\SetTenantFromUser;

// Modul Keuangan = PLUG & PLAY. Di-gate `module.active:Finance`:
// tenant tanpa langganan Finance → menu hilang + abort(403).
Route::middleware(['auth', SetTenantFromUser::class, 'module.active:Finance'])->group(function () {
    Route::get('/finance/bills', [FinanceController::class, 'bills'])->name('finance.bills');
    Route::post('/finance/bills/generate', [FinanceController::class, 'generateBills'])->name('finance.bills.generate');
    Route::post('/bills/{bill}/payment', [FinanceController::class, 'recordPayment'])->name('finance.payment.store');
    Route::get('/payments/{payment}/receipt', [FinanceController::class, 'printReceipt'])->name('finance.receipt');
    Route::post('/finance/reminders', [FinanceController::class, 'sendReminders'])->name('finance.reminders');
});
