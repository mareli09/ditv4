<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IT\ITDashboardController;
use App\Http\Controllers\IT\ITUserController;
use App\Http\Controllers\CESO\CESOActivityController;
use App\Http\Controllers\CESO\ProjectController;
use App\Http\Controllers\CommunityActivityController;
use App\Http\Controllers\WebsiteContentController;


Route::get('/', function () {
    $contents = DB::table('website_contents')->pluck('value', 'key');
    return view('welcome', compact('contents'));
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
    /*
    Route::get('/community/activities', function() {
        return view('community.activities');
    })->name('community.activities');
    */
    Route::get('/community/activities', [CommunityActivityController::class, 'index'])
        ->name('community.activities');

    Route::get('/community/my-activities', [CommunityActivityController::class, 'myActivities'])
        ->name('community.my-activities');

    Route::get('/community/profile', function () {
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

    // CESO projects
    Route::get('/ceso/projects', [\App\Http\Controllers\CESO\ProjectController::class, 'index'])
        ->name('ceso.projects.index');

    Route::get('/ceso/projects/create', [\App\Http\Controllers\CESO\ProjectController::class, 'create'])
        ->name('ceso.projects.create');

    Route::post('/ceso/projects', [\App\Http\Controllers\CESO\ProjectController::class, 'store'])
        ->name('ceso.projects.store');

    Route::get('/ceso/projects/archived', [\App\Http\Controllers\CESO\ProjectController::class, 'archivedIndex'])
        ->name('ceso.projects.archived');

    Route::get('/ceso/projects/{project}', [\App\Http\Controllers\CESO\ProjectController::class, 'show'])
        ->name('ceso.projects.show');

    Route::get('/ceso/projects/{project}/edit', [\App\Http\Controllers\CESO\ProjectController::class, 'edit'])
        ->name('ceso.projects.edit');

    Route::put('/ceso/projects/{project}', [\App\Http\Controllers\CESO\ProjectController::class, 'update'])
        ->name('ceso.projects.update');

    Route::post('/ceso/projects/{project}/archive', [\App\Http\Controllers\CESO\ProjectController::class, 'archive'])
        ->name('ceso.projects.archive');

    Route::post('/ceso/projects/{project}/restore', [\App\Http\Controllers\CESO\ProjectController::class, 'restore'])
        ->name('ceso.projects.restore');


    // Community feedback route
    Route::get('/community/feedback', [CESOActivityController::class, 'communityFeedbackForm'])->name('community.feedback.form');
    Route::post('/community/feedback', [CESOActivityController::class, 'submitCommunityFeedback'])->name('community.feedback.submit');

    // Community joining activities
    Route::post('/community/activities/{activity}/join', [CommunityActivityController::class, 'join'])->name('community.activities.join');

    // Community submitting feedback
    Route::post('/community/activities/{activity}/feedback', [CommunityActivityController::class, 'submitFeedback'])->name('community.activities.feedback');

    // Community viewing activity details
    Route::get('/community/activities/{activity}', [CommunityActivityController::class, 'show'])->name('community.activities.show');

    // Route for sentiment explanation
    Route::post('/activities/{activity}/feedback/{feedback}/explain', [CESOActivityController::class, 'explainSentiment'])->name('ceso.activities.feedback.explain');

    // Route for community's joined activities
    Route::get('/community/my-activities', [CommunityActivityController::class, 'myActivities'])->name('community.my-activities');

    // Website Content Management
    Route::get('/ceso/website', [WebsiteContentController::class, 'index'])
        ->name('ceso.website.index');

    Route::put('/ceso/website', [WebsiteContentController::class, 'update'])
        ->name('ceso.website.update');
    Route::put('/ceso/website/hero', [WebsiteContentController::class, 'updateHero'])->name('ceso.website.hero.update');
    Route::put('/ceso/website/about', [WebsiteContentController::class, 'updateAbout'])->name('ceso.website.about.update');
    Route::put('/ceso/website/news', [WebsiteContentController::class, 'updateNews'])->name('ceso.website.news.update');
    Route::put('/ceso/website/cta', [WebsiteContentController::class, 'updateCTA'])->name('ceso.website.cta.update');
    Route::put('/ceso/website/contact', [WebsiteContentController::class, 'updateContact'])->name('ceso.website.contact.update');
    Route::put('/ceso/website/footer', [WebsiteContentController::class, 'updateFooter'])->name('ceso.website.footer.update');

    Route::get('/create-test-table', function () {
        \Illuminate\Support\Facades\DB::statement('CREATE TABLE IF NOT EXISTS website_contents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(255) UNIQUE,
            `value` TEXT,
            carousel_images JSON,
            about_description TEXT,
            mission TEXT,
            vision TEXT,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL
        );');

        return 'Test table created successfully.';
    });
});