<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello-fpm', function () {
    return ['data' => 'world'];
});

Route::get('/users-fpm', function () {
    return User::query()->limit(100)->get();
});

Route::get('/users-octane', function () {
    return User::query()->limit(100)->get();
});

Route::get('/hello-octane', function () {
    return ['data' => 'world'];
});

use App\Http\Controllers\StatsController;

Route::prefix('stats')->group(function () {
    Route::get('/hello-fpm', [StatsController::class, 'helloFpm']);
    Route::get('/users-fpm', [StatsController::class, 'usersFpm']);
    Route::get('/users-octane', [StatsController::class, 'usersOctane']);
    Route::get('/hello-octane', [StatsController::class, 'helloOctane']);
    Route::post('/report', [StatsController::class, 'report']);
});
