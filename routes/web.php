<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SplitBillController;

/*
|--------------------------------------------------------------------------
| Split Bill Routes
|--------------------------------------------------------------------------
*/

// HALAMAN AWAL → FORM MENU
Route::get('/', [SplitBillController::class, 'showMenuForm']);

// SUBMIT MENU → PILIH ORANG
Route::post('/menu', [SplitBillController::class, 'submitMenu']);

// HITUNG HASIL SPLIT BILL
Route::post('/calculate', [SplitBillController::class, 'calculate']);
