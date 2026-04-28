<?php


use App\Http\Controllers\Api\ScannerProjectSyncController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScannerCallbackController;
Route::post('/scanner/projects/sync', [ScannerProjectSyncController::class, 'store']);


Route::post('/scanner/callback', [ScannerCallbackController::class, 'handle']);

