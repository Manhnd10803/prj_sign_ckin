<?php

use App\Http\Controllers\CheckinController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CheckinController::class, 'index'])->name('checkin.index');
Route::get('/checkin', [CheckinController::class, 'create'])->name('checkin.create');
Route::post('/checkin', [CheckinController::class, 'store'])->name('checkin.submit');
