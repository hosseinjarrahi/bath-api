<?php
use Illuminate\Support\Facades\Route;
use Modules\Service\Controllers\ServiceController;

Route::post('/service/passenger-form', [ServiceController::class, 'saveForm']);

Route::post('/service/choose-service-item', [ServiceController::class, 'chooseServiceItem']);

Route::get('/service/analyse', [ServiceController::class, 'analyse']);

Route::post('/service/checkout', [ServiceController::class, 'checkout']);

Route::get('/passenger/policy', [ServiceController::class, 'policy']);

Route::get('/passenger/reservable-service-items', [ServiceController::class, 'reservableServiceItems']);

Route::get('/service/report', [ServiceController::class, 'report']);

Route::get('/service/should-checkout', [ServiceController::class, 'shouldCheckout']);
