<?php

use Illuminate\Support\Facades\Route;

Route::get('/locale.json', [\App\Http\Controllers\SupportController::class, 'getLocalization']);
Route::get('/update.json', [\App\Http\Controllers\SupportController::class, 'addLocalization']);
Route::get('/routes.json', [\App\Http\Controllers\SupportController::class, 'getRoutes']);
Route::get('/load.json', [\App\Http\Controllers\SupportController::class, 'loadJson']);

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect(\route('login'));
    })->name('login');
    Route::get('login', [\App\Http\Controllers\Backend\AuthController::class, 'login'])->name('login');
    Route::post('login', [\App\Http\Controllers\Backend\AuthController::class, 'doLogin'])->name('login.submit');
});
Route::middleware([\App\Http\Middleware\AuthMiddleware::class, \App\Http\Middleware\LogActivity::class])->group(function () {
    Route::get('/app/{any?}', [\App\Http\Controllers\Backend\DashboardController::class, 'singleApp'])
        ->where('any', '.*')->name('home');
    Route::get('logout', [\App\Http\Controllers\Backend\AuthController::class, 'logout'])->name('logout');

    Route::prefix('api')->group(function () {
        Route::post('file_upload', [\App\Http\Controllers\FileController::class, 'fileUpload']);
        Route::post('general', [\App\Http\Controllers\SupportController::class, 'getGeneralData']);
        Route::post('configurations', [\App\Http\Controllers\SupportController::class, 'appConfigurations']);
        Route::get('app_notification', [\App\Http\Controllers\SupportController::class, 'appNotification']);
        Route::get('dashboard', [\App\Http\Controllers\SupportController::class, 'appDashboard']);
        Route::get('activities', [\App\Http\Controllers\SupportController::class, 'userActivities']);



        Route::resource('settings', \App\Http\Controllers\SettingController::class);
        Route::resource('profile', \App\Http\Controllers\Backend\AuthController::class);
        Route::resource('users', \App\Http\Controllers\Backend\UserController::class);

        Route::resource('modules', \App\Http\Controllers\Backend\RBAC\ModuleController::class);
        Route::resource('/roles', \App\Http\Controllers\Backend\RBAC\RoleController::class);
        Route::resource('/module_permissions', \App\Http\Controllers\Backend\RBAC\RoleModuleController::class);
        Route::resource('/role_permissions', \App\Http\Controllers\Backend\RBAC\RolePermissionController::class);
    });
});
