<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Radiology\InvestigationController;
use App\Http\Controllers\Radiology\RequestController;
use App\Http\Controllers\Radiology\ParameterController;

Route::middleware(['auth', 'verified', 'role:radiologist'])
->prefix('radiology')
->name('radiology.')
->namespace('radiology')
->group(function () {
    
    Route::name('requests.')
    ->prefix('requests')
    ->group(function () {
        Route::get('/', [RequestController::class, 'index'])->name('index');
        Route::get('/{investigationRequest}/show', [RequestController::class, 'show'])->name('show');
        Route::get('/{investigationRequest}/create-result', [RequestController::class, 'createResult'])->name('createResult');
        Route::post('/{investigationRequest}/store-result', [RequestController::class, 'storeResult'])->name('storeResult');
        Route::get('/{investigationRequest}/edit-result', [RequestController::class, 'editResult'])->name('editResult');
        Route::put('/{investigationRequest}/update-result', [RequestController::class, 'updateResult'])->name('updateResult');
    });
    
    Route::name('investigations.')
    ->prefix('investigations')
    ->group(function () {
        Route::get('/', [InvestigationController::class, 'index'])->name('index');
        Route::delete('/{investigation}/destroy', [InvestigationController::class, 'destroy'])->name('destroy');
        Route::get('/create', [InvestigationController::class, 'create'])->name('create');
        Route::post('/store', [InvestigationController::class, 'store'])->name('store');
        Route::get('/{investigation}/edit', [InvestigationController::class, 'edit'])->name('edit');
        Route::put('/{investigation}/update', [InvestigationController::class, 'update'])->name('update');
    
        Route::name('parameters.')
        ->prefix('{investigation}/parameters')
        ->group(function () {
            Route::get('/', [ParameterController::class, 'index'])->name('index');
            Route::get('/create', [ParameterController::class, 'create'])->name('create');
            Route::post('/store', [ParameterController::class, 'store'])->name('store');
            Route::get('{parameter}/edit', [ParameterController::class, 'edit'])->name('edit');
            Route::put('{parameter}/update', [ParameterController::class, 'update'])->name('update');
            Route::delete('{parameter}/destroy', [ParameterController::class, 'destroy'])->name('destroy');
        });
    });
});