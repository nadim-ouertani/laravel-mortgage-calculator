<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Mortgage loan calculator API routes
Route::prefix('loans')->group(function () {
    Route::post('/calculate', [LoanController::class, 'calculateLoan']);
    Route::get('/{id}', [LoanController::class, 'show']);
    Route::delete('/{id}', [LoanController::class, 'destroy']);
});
