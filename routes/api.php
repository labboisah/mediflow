<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Sync Routes
|--------------------------------------------------------------------------
|
| Bidirectional sync between local hospital server and online platform
|
*/
Route::prefix('v1/sync')->middleware('auth.sync.token')->group(function () {
    // Receive sync records from remote server
    Route::post('/records', [SyncController::class, 'receiveSyncRecords']);
    Route::post('/batch', [SyncController::class, 'receiveBatchSync']);
    
    // Retrieve pending syncs (for pull-based sync if needed)
    Route::get('/pending', [SyncController::class, 'getPendingSyncs']);
    Route::get('/status/{syncUuid}', [SyncController::class, 'getSyncStatus']);
    
    // Health check
    Route::get('/health', [SyncController::class, 'healthCheck']);
});

Route::middleware('auth.sync.token')->get('/v1/health', [SyncController::class, 'healthCheck']);
