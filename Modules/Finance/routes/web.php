<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;
use App\Http\Middleware\SetTenantFromUser;

// Modul Keuangan = PLUG & PLAY. Di-gate `module.active:Finance`:
// tenant tanpa langganan Finance → menu hilang + abort(403).
Route::middleware(['auth', SetTenantFromUser::class, 'module.active:Finance'])->group(function () {
    // [2026-06-16 | AG] Dashboard Keuangan
    Route::get('/finance/dashboard', [FinanceController::class, 'dashboard'])->name('finance.dashboard')->middleware('permission:view_bills');

    // Tagihan SPP
    Route::get('/finance/bills', [FinanceController::class, 'bills'])->name('finance.bills')->middleware('permission:view_bills');
    Route::post('/finance/bills/generate', [FinanceController::class, 'generateBills'])->name('finance.bills.generate')->middleware('permission:create_bills');
    
    // [2026-06-16 | AG] CRUD Tagihan SPP Individual
    Route::post('/finance/bills/store-single', [FinanceController::class, 'storeSingleBill'])->name('finance.bills.store-single')->middleware('permission:create_bills');
    Route::put('/finance/bills/{bill}', [FinanceController::class, 'updateBill'])->name('finance.bills.update')->middleware('permission:create_bills');
    Route::delete('/finance/bills/{bill}', [FinanceController::class, 'destroyBill'])->name('finance.bills.destroy')->middleware('permission:create_bills');

    // Pembayaran & Kwitansi
    Route::post('/bills/{bill}/payment', [FinanceController::class, 'recordPayment'])->name('finance.payment.store')->middleware('permission:record_payment');
    Route::get('/payments/{payment}/receipt', [FinanceController::class, 'printReceipt'])->name('finance.receipt')->middleware('permission:print_receipt');
    
    // [2026-06-16 | AG] Riwayat Pembayaran Global (Buku Kas Harian)
    Route::get('/finance/payments', [FinanceController::class, 'paymentsHistory'])->name('finance.payments.history')->middleware('permission:view_bills');

    // Laporan & Ekspor
    Route::get('/finance/bills/export', [FinanceController::class, 'exportBills'])->name('finance.bills.export')->middleware('permission:view_bills');
    Route::get('/finance/reports', [FinanceController::class, 'reports'])->name('finance.reports')->middleware('permission:view_bills');
    Route::get('/finance/reports/pdf', [FinanceController::class, 'exportPdfReports'])->name('finance.reports.pdf')->middleware('permission:view_bills');
    
    Route::post('/finance/reminders', [FinanceController::class, 'sendReminders'])->name('finance.reminders')->middleware('permission:send_reminders');
});
