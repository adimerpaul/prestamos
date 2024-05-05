<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/me', [\App\Http\Controllers\UserController::class, 'me']);
    Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout']);

    Route::get('/clients', [\App\Http\Controllers\ClientController::class, 'index']);
    Route::post('/clients', [\App\Http\Controllers\ClientController::class, 'store']);
    Route::put('/clients/{id}', [\App\Http\Controllers\ClientController::class, 'update']);
    Route::delete('/clients/{id}', [\App\Http\Controllers\ClientController::class, 'delete']);
    Route::get('/clients/search', [\App\Http\Controllers\ClientController::class, 'search']);

    Route::get('/loans', [\App\Http\Controllers\LoanController::class, 'index']);
    Route::post('/loans', [\App\Http\Controllers\LoanController::class, 'store']);
    Route::put('/loanDescriptionUpdate/{id}', [\App\Http\Controllers\LoanController::class, 'loanDescriptionUpdate']);
    Route::put('/loansAnular/{id}', [\App\Http\Controllers\LoanController::class, 'loansAnular']);
    Route::get('/loans/show/{id}', [\App\Http\Controllers\LoanController::class, 'show']);
    Route::get('/loans/nextId', [\App\Http\Controllers\LoanController::class, 'nextId']);
});
//compromiso
Route::get('/compromiso/{loan_id}', [\App\Http\Controllers\ReportController::class, 'compromiso']);
Route::get('/plan/{loan_id}', [\App\Http\Controllers\ReportController::class, 'plan']);
