<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::redirect('/', '/events');

/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/

// Resource Controller
Route::resource('events', EventController::class);

// Đăng ký sự kiện
Route::post('/events/{event}/register', [EventController::class, 'register'])
    ->middleware('auth')
    ->name('events.register');

// Hủy đăng ký
Route::delete('/events/{event}/unregister', [EventController::class, 'unregister'])
    ->middleware('auth')
    ->name('events.unregister');

/*
|--------------------------------------------------------------------------
| Temporary Login (Demo)
|--------------------------------------------------------------------------
*/

// Fake login (chỉ dùng để demo)
Route::get('/fake-login/{id?}', function ($id = 1) {
    Auth::loginUsingId($id);

    return redirect()->route('events.index');
})->name('fake-login');

// Logout
Route::get('/logout', function () {
    Auth::logout();

    return redirect()->route('events.index');
})->name('logout');

// Trang login tạm thời
Route::view('/login', 'auth.login')->name('login');
