<?php

use Illuminate\Support\Facades\Route;

Route::get('/locale.json', [\App\Http\Controllers\SupportController::class, 'getLocalization']);
Route::get('/routes.json', [\App\Http\Controllers\SupportController::class, 'getRoutes']);

Route::middleware('guest')->group(function (){
    Route::get('/', function (){return redirect(\route('login'));})->name('login');
    Route::get('login', [\App\Http\Controllers\Backend\AuthController::class, 'login'])->name('login');
    Route::post('login', [\App\Http\Controllers\Backend\AuthController::class, 'doLogin'])->name('login.submit');
});
Route::middleware(\App\Http\Middleware\AuthCheckMiddleware::class)->group(function (){
    Route::get('/app/{any?}', [\App\Http\Controllers\Backend\DashboardController::class, 'singleApp'])
        ->where('any', '.*')->name('home');
    Route::get('logout', [\App\Http\Controllers\Backend\AuthController::class, 'logout'])->name('logout');

    Route::prefix('api')->group(function (){
        Route::post('general', [\App\Http\Controllers\SupportController::class, 'getGeneralData']);
        Route::post('configurations', [\App\Http\Controllers\SupportController::class, 'appConfigurations']);

        Route::post('profile', [\App\Http\Controllers\SupportController::class, 'profileUpdate']);

        Route::resource('users', \App\Http\Controllers\Backend\UserController::class);
        Route::resource('modules', \App\Http\Controllers\Backend\RBAC\ModuleController::class);
    });
});
