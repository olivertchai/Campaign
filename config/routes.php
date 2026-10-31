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

    // Create
    Route::get('/pages/campaign/new', [CampaignsController::class, 'new'])->name('campaigns.new');
    Route::post('/pages/campaign', [CampaignsController::class, 'create'])->name('campaigns.create');

    // Retrieve
    Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/page/{page}', [CampaignsController::class, 'index'])->name('campaigns.paginate');
    Route::get('/campaigns/{id}', [CampaignsController::class, 'show'])->name('campaigns.show');

    // Update
    Route::get('/campaigns/{id}/edit', [CampaignsController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{id}', [CampaignsController::class, 'update'])->name('campaigns.update');

    // Delete
    Route::delete('/campaigns/{id}', [CampaignsController::class, 'destroy'])->name('campaigns.destroy');

    // Logout
    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

    //users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
});
