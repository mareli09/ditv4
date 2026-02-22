<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IT\ITDashboardController;
use App\Http\Controllers\IT\ITUserController;
use App\Http\Controllers\CESO\CESOActivityController;


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

    Route::prefix('it/users')->name('it.users.')->group(function () {
        Route::get('/', [ITUserController::class, 'index'])->name('index');
        Route::get('/create', [ITUserController::class, 'create'])->name('create');
        Route::post('/', [ITUserController::class, 'store'])->name('store');
        Route::post('import', [ITUserController::class, 'import'])->name('import');
        Route::get('sample-csv', [ITUserController::class, 'sampleCsv'])->name('sample-csv');
        Route::get('/{user}/edit', [ITUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [ITUserController::class, 'update'])->name('update');
        Route::post('/{user}/activate', [ITUserController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [ITUserController::class, 'deactivate'])->name('deactivate');
        Route::post('/{user}/restore', [ITUserController::class, 'restore'])->name('restore');
        Route::delete('/{user}', [ITUserController::class, 'destroy'])->name('destroy');
    });

    Route::get('/community/dashboard', [\App\Http\Controllers\Community\CommunityDashboardController::class, 'index'])
        ->name('community.dashboard');

    Route::get('/community/activities', function() {
        return view('community.activities');
    })->name('community.activities');

    Route::get('/community/my-activities', function() {
        return view('community.my-activities');
    })->name('community.my-activities');

    Route::get('/community/profile', function() {
        return view('community.profile');
    })->name('community.profile');

    // CESO staff routes
    Route::get('/ceso/dashboard', [\App\Http\Controllers\CESO\CESODashboardController::class, 'index'])
        ->name('ceso.dashboard');

    // CESO activities
    Route::get('/ceso/activities', [\App\Http\Controllers\CESO\CESOActivityController::class, 'index'])
        ->name('ceso.activities.index');

    Route::get('/ceso/activities/create', [\App\Http\Controllers\CESO\CESOActivityController::class, 'create'])
        ->name('ceso.activities.create');

    Route::post('/ceso/activities', [\App\Http\Controllers\CESO\CESOActivityController::class, 'store'])
        ->name('ceso.activities.store');

    Route::get('/ceso/activities/archived', [\App\Http\Controllers\CESO\CESOActivityController::class, 'archivedIndex'])
        ->name('ceso.activities.archived');

    Route::get('/ceso/activities/{activity}', [\App\Http\Controllers\CESO\CESOActivityController::class, 'analyzeFeedback'])
        ->name('ceso.activities.show');

    Route::get('/ceso/activities/{activity}/edit', [\App\Http\Controllers\CESO\CESOActivityController::class, 'edit'])
        ->name('ceso.activities.edit');

    Route::put('/ceso/activities/{activity}', [\App\Http\Controllers\CESO\CESOActivityController::class, 'update'])
        ->name('ceso.activities.update');

    Route::post('/ceso/activities/{activity}/archive', [\App\Http\Controllers\CESO\CESOActivityController::class, 'archive'])
        ->name('ceso.activities.archive');

    Route::post('/ceso/activities/{activity}/restore', [\App\Http\Controllers\CESO\CESOActivityController::class, 'restore'])
        ->name('ceso.activities.restore');

    Route::post('/ceso/activities/{activity}/feedback', [\App\Http\Controllers\CESO\CESOActivityController::class, 'feedback'])
        ->name('ceso.activities.feedback');

    Route::get('/ceso/activities/{activity}/analyze', [\App\Http\Controllers\CESO\CESOActivityController::class, 'analyzeFeedback'])
        ->name('ceso.activities.analyze');


    // Community feedback route
    Route::get('/community/feedback', [CESOActivityController::class, 'communityFeedbackForm'])->name('community.feedback.form');
    Route::post('/community/feedback', [CESOActivityController::class, 'submitCommunityFeedback'])->name('community.feedback.submit');

});