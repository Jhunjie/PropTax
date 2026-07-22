<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Livewire\UserProperties;

Route::view('/', 'welcome')->name('home');

Route::get('/', function () {
    return view('welcome'); // or wherever your landing page view lives
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user-properties-table', UserProperties::class)->name('user-properties-table');
});

require __DIR__.'/settings.php';
