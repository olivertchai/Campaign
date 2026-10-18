<?php

use App\Controllers\AuthenticationsController;
use Core\Router\Route;

// Authentication
Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');

Route::middleware('auth')->group(function () {
    // index
    Route::get('/', [CampaignsController::class, 'index'])->name('root');
    
    // Logout
    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');
});
