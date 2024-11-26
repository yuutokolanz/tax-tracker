<?php

use App\Controllers\AccountantsController;
use App\Controllers\AuthenticationsController;
use App\Controllers\DeclarationsController;
use App\Controllers\HomeController;
use Core\Router\Route;
use Core\Router\RouteWrapperMiddleware;

// Authentication
Route::get('/login', [AuthenticationsController::class, 'new'])->name('accountants.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('accountants.authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthenticationsController::class, 'logout'])->name('accountants.logout');
    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::get('/declarations/new', [DeclarationsController::class, 'new'])->name('declarations.new');
    Route::get('/declarations/pending', [DeclarationsController::class, 'pending'])->name('declarations.pending');
    Route::get('/declarations/finished', [DeclarationsController::class, 'finished'])->name('declarations.finished');
    Route::get('/declarations/my', [DeclarationsController::class, 'my'])->name('declarations.my');

    Route::get('/declarations/show/{id}', [DeclarationsController::class, 'show'])->name('declarations.show');

    // Supervisor Routes
    $supervisorMiddleware = new RouteWrapperMiddleware('role_supervisor', 2);
    $supervisorMiddleware->group(function () {
        Route::get('/declarations/all', [DeclarationsController::class, 'all'])->name('declarations.all');
        Route::get('/accountants/all', [AccountantsController::class, 'all'])->name('accountants.all');
    });

    $adminMiddleware = new RouteWrapperMiddleware('role_admin', 3);
    $adminMiddleware->group(function () {
        Route::get('/accountants/new', [AccountantsController::class, 'new'])->name('accountants.new');
        Route::post('/accountants', [AccountantsController::class, 'create'])->name('accountants.create');
    });
});
