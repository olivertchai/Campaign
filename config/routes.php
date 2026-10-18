<?php

use App\Controllers\AuthenticationsController;
use App\Controllers\CampaignsController;
use App\Controllers\UsersController;
use Core\Router\Route;

// Authentication
Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');

Route::middleware('auth')->group(function () {
    // index
    Route::get('/', [CampaignsController::class, 'index'])->name('root');
    Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns.index');

    // Logout
    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

    //users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
});
