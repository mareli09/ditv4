<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IT\ITDashboardController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/contact', function () {
    return back()->with('success', 'Message sent successfully!');
})->name('contact.send');

use App\Http\Controllers\CommunityController;

Route::get('/community/register', [CommunityController::class, 'create'])
    ->name('community.register');

Route::post('/community/register', [CommunityController::class, 'store'])
    ->name('community.store');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.attempt');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/it/dashboard', [ITDashboardController::class, 'index'])
        ->name('it.dashboard');

    Route::get('/it/users', function () {
        return "User Management Page";
    })->name('it.users');

});