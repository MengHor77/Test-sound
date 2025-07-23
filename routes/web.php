<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AudioController;

Route::get('/convert', [AudioController::class, 'index'])->name('convert.index');

Route::post('/convert', [AudioController::class, 'convert'])->name('convert.process');

Route::get('/download/{filename}', [AudioController::class, 'download'])->name('convert.download');
