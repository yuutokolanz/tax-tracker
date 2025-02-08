<?php

use App\Controllers\AccountantsController;
use App\Controllers\AuthenticationsController;
use App\Controllers\ClientsController;
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

    // Declarations
        //Crud
        Route::get('/declarations/new', [DeclarationsController::class, 'new'])->name('declarations.new');
        Route::post('/declarations', [DeclarationsController::class, 'create'])->name('declarations.create');

        Route::delete('/declarations/{id}', [DeclarationsController::class, 'destroy'])->name('declarations.destroy');

    Route::get('/declarations/pending', [DeclarationsController::class, 'pending'])->name('declarations.pending');
    Route::get('/declarations/finished', [DeclarationsController::class, 'finished'])->name('declarations.finished');
    Route::get('/declarations/my', [DeclarationsController::class, 'my'])->name('declarations.my');

    Route::get('/declarations/show/{id}', [DeclarationsController::class, 'show'])->name('declarations.show');

    // Clients Crud
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');

        //Create
    Route::get('/clients/new', [ClientsController::class, 'new'])->name('clients.new');
    Route::post('/clients', [ClientsController::class, 'create'])->name('clients.create');

        //Retrive
    Route::get('/clients/{id}', [ClientsController::class, 'show'])->name('clients.show');

        //Update
    Route::get('/clients/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{id}', [ClientsController::class, 'update'])->name('clients.update');

        //Delete
    Route::delete('/clients/{id}', [ClientsController::class, 'destroy'])->name('clients.destroy');

    // Supervisor Routes
    $supervisorMiddleware = new RouteWrapperMiddleware('role_supervisor', 2);
    $supervisorMiddleware->group(function () {
        Route::get('/declarations/all', [DeclarationsController::class, 'all'])->name('declarations.all');
        Route::get('/accountants/all', [AccountantsController::class, 'all'])->name('accountants.all');
    });

    // Admin Routes
    $adminMiddleware = new RouteWrapperMiddleware('role_admin', 3);
    $adminMiddleware->group(function () {
        Route::get('/accountants/new', [AccountantsController::class, 'new'])->name('accountants.new');
        Route::post('/accountants', [AccountantsController::class, 'create'])->name('accountants.create');
    });
});
