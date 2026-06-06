<?php

use App\Http\Controllers\ForecastController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('forecast.index');

    Route::get('/refresh', [StockController::class, 'refresh_stock'])
        ->name('forecast.refresh');

    Route::get('/calibration', function () {
        if (auth()->user()->role !== 'technician') {
            abort(403);
        }

        return view('calibration');
    })->name('calibration');

    Route::post('/calibration', [StockController::class, 'calibration']);

    Route::get('/report', function () {
        if (auth()->user()->role !== 'technician') {
            abort(403);
        }

        return view('report');
    });

    Route::post('/report', [HistoryController::class, 'store_report']);

    Route::get('/history', function () {
        return view('history', [
            'histories' => collect(),
        ]);
    });

    Route::get('/history/filter', [HistoryController::class, 'filter_history'])
        ->name('history.filter');

    Route::delete('/logout', [SessionController::class, 'destroy']);
});