<?php

use Illuminate\Support\Facades\Route;

Route::get('/locale.json', [\App\Http\Controllers\SupportController::class, 'getLocalization']);

Route::middleware('guest')->group(function (){
    Route::get('/', function (){return redirect(\route('login'));})->name('login');
    Route::get('login', [\App\Http\Controllers\Backend\AuthController::class, 'login'])->name('login');
    Route::post('login', [\App\Http\Controllers\Backend\AuthController::class, 'doLogin'])->name('login.submit');
});
Route::middleware(\App\Http\Middleware\AuthCheckMiddleware::class)->group(function (){
    Route::get('/app/{any?}', [\App\Http\Controllers\Backend\DashboardController::class, 'singleApp'])
        ->where('any', '.*')->name('home');
    Route::get('logout', [\App\Http\Controllers\Backend\AuthController::class, 'logout'])->name('logout');
    Route::get('/billing_info',[\App\Http\Controllers\BillingController::class, 'getBillingInfo']);


    Route::prefix('api')->group(function (){
        Route::post('general', [\App\Http\Controllers\SupportController::class, 'getGeneralData']);
        Route::post('configurations', [\App\Http\Controllers\SupportController::class, 'appConfigurations']);

        Route::post('profile', [\App\Http\Controllers\SupportController::class, 'profileUpdate']);

        Route::resource('users', \App\Http\Controllers\Backend\UserController::class);
        Route::resource('modules', \App\Http\Controllers\Backend\RBAC\ModuleController::class);
        Route::resource('meter_type', \App\Http\Controllers\MeterTypeController::class);
        Route::resource('customer_information',\App\Http\Controllers\CustomerController::class);
        Route::resource('bill_information',\App\Http\Controllers\BillInformationController::class);
        Route::resource('meter_reading',\App\Http\Controllers\MeterReadingController::class);
        Route::resource('overdue_bills',\App\Http\Controllers\OverdueBillController::class);
        Route::resource('customer_area',\App\Http\Controllers\AreaController::class);
        Route::resource('solar_plant',\App\Http\Controllers\SolarPlantController::class);
        Route::resource('generator',\App\Http\Controllers\GeneratorController::class);
        Route::resource('manual_bill_entry',\App\Http\Controllers\ManualBillEntryController::class);
        Route::resource('bulk_bill_generation',\App\Http\Controllers\BulkBillGenerationController::class);
        Route::resource('add_meter',\App\Http\Controllers\MeterController::class);
        Route::resource('add_meter',\App\Http\Controllers\MeterController::class);
        Route::resource('tariff_rate',\App\Http\Controllers\TariffAndRateController::class);
        Route::resource('tariff_rate',\App\Http\Controllers\TariffAndRateController::class);
        Route::resource('staff',\App\Http\Controllers\StaffController::class);
    });
});
