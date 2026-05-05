<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return ['data' => 'world'];
});

Route::get('/users', function () {
    $users = User::query()->limit(100)->get();

    return $users;
});
