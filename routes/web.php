<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SplitBillController;
use App\Http\Controllers\ScanBillController;

/*
|--------------------------------------------------------------------------
| Split Bill Routes
|--------------------------------------------------------------------------
*/
Route::post('/scan-bill', [ScanBillController::class, 'scan'])->middleware('throttle:10,1');
// HALAMAN AWAL → FORM MENU
Route::get('/', [SplitBillController::class, 'showMenuForm']);

Route::get('/menu', [SplitBillController::class, 'showMenuForm']);

// SUBMIT MENU → PILIH ORANG
Route::post('/menu', [SplitBillController::class, 'submitMenu']);

// HITUNG HASIL SPLIT BILL
Route::post('/calculate', [SplitBillController::class, 'calculate']);
