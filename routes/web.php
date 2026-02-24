<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IT\ITDashboardController;
use App\Http\Controllers\IT\ITUserController;
use App\Http\Controllers\CESO\CESOActivityController;
use App\Http\Controllers\CESO\ProjectController;
use App\Http\Controllers\CommunityActivityController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentActivityController;
use App\Http\Controllers\Faculty\FacultyDashboardController;
use App\Http\Controllers\Faculty\FacultyActivityController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffActivityController;
use App\Http\Controllers\WebsiteContentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SetupController;


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

    Route::get('/community/profile', [ProfileController::class, 'communityShow'])
        ->name('community.profile');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');

    Route::put('/profile', [ProfileController::class, 'updateProfile'])
        ->name('profile.update');

    // IT profile
    Route::get('/it/profile', [ProfileController::class, 'itShow'])
        ->name('it.profile');

    // CESO profile
    Route::get('/ceso/profile', [ProfileController::class, 'cesoShow'])
        ->name('ceso.profile');

    // Student Portal Routes
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');

    Route::get('/student/activities', [StudentActivityController::class, 'index'])
        ->name('student.activities');

    Route::get('/student/my-activities', [StudentActivityController::class, 'myActivities'])
        ->name('student.my-activities');

    Route::get('/student/activities/{activity}', [StudentActivityController::class, 'show'])
        ->name('student.activities.show');

    Route::post('/student/activities/{activity}/join', [StudentActivityController::class, 'join'])
        ->name('student.activities.join');

    Route::post('/student/activities/join-with-code', [StudentActivityController::class, 'joinWithCode'])
        ->name('student.activities.join-with-code');

    Route::post('/student/activities/{activity}/feedback', [StudentActivityController::class, 'submitFeedback'])
        ->name('student.activities.feedback');

    Route::get('/student/profile', [ProfileController::class, 'studentShow'])
        ->name('student.profile');

    Route::post('/student/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('student.profile.update-password');

    Route::post('/student/profile', [ProfileController::class, 'updateProfile'])
        ->name('student.profile.update');

    // Faculty Portal Routes
    Route::get('/faculty/dashboard', [FacultyDashboardController::class, 'index'])
        ->name('faculty.dashboard');

    Route::get('/faculty/activities', [FacultyActivityController::class, 'index'])
        ->name('faculty.activities');

    Route::get('/faculty/my-activities', [FacultyActivityController::class, 'myActivities'])
        ->name('faculty.my-activities');

    Route::get('/faculty/activities/{activity}', [FacultyActivityController::class, 'show'])
        ->name('faculty.activities.show');

    Route::post('/faculty/activities/{activity}/join', [FacultyActivityController::class, 'join'])
        ->name('faculty.activities.join');

    Route::post('/faculty/activities/join-with-code', [FacultyActivityController::class, 'joinWithCode'])
        ->name('faculty.activities.join-with-code');

    Route::post('/faculty/activities/{activity}/feedback', [FacultyActivityController::class, 'submitFeedback'])
        ->name('faculty.activities.feedback');

    Route::get('/faculty/profile', [ProfileController::class, 'facultyShow'])
        ->name('faculty.profile');

    Route::post('/faculty/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('faculty.profile.update-password');

    Route::post('/faculty/profile', [ProfileController::class, 'updateProfile'])
        ->name('faculty.profile.update');

    // Staff Portal Routes
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    Route::get('/staff/activities', [StaffActivityController::class, 'index'])
        ->name('staff.activities');

    Route::get('/staff/my-activities', [StaffActivityController::class, 'myActivities'])
        ->name('staff.my-activities');

    Route::get('/staff/activities/{activity}', [StaffActivityController::class, 'show'])
        ->name('staff.activities.show');

    Route::post('/staff/activities/{activity}/join', [StaffActivityController::class, 'join'])
        ->name('staff.activities.join');

    Route::post('/staff/activities/join-with-code', [StaffActivityController::class, 'joinWithCode'])
        ->name('staff.activities.join-with-code');

    Route::post('/staff/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('staff.profile.update-password');

    Route::post('/staff/profile', [ProfileController::class, 'updateProfile'])
        ->name('staff.profile.update');

    Route::post('/staff/activities/{activity}/feedback', [StaffActivityController::class, 'submitFeedback'])
        ->name('staff.activities.feedback');

    Route::get('/staff/profile', [ProfileController::class, 'staffShow'])
        ->name('staff.profile');

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
    
    Route::post('/community/activities/join-with-code', [CommunityActivityController::class, 'joinWithCode'])->name('community.activities.join-with-code');

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

    // CESO announcements
    Route::get('/ceso/announcements', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'index'])
        ->name('ceso.announcements.index');

    Route::get('/ceso/announcements/create', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'create'])
        ->name('ceso.announcements.create');

    Route::post('/ceso/announcements', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'store'])
        ->name('ceso.announcements.store');

    Route::get('/ceso/announcements/{announcement}', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'show'])
        ->name('ceso.announcements.show');

    Route::get('/ceso/announcements/{announcement}/edit', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'edit'])
        ->name('ceso.announcements.edit');

    Route::put('/ceso/announcements/{announcement}', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'update'])
        ->name('ceso.announcements.update');

    Route::post('/ceso/announcements/{announcement}/archive', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'archive'])
        ->name('ceso.announcements.archive');

    Route::post('/ceso/announcements/{announcement}/restore', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'restore'])
        ->name('ceso.announcements.restore');

    Route::delete('/ceso/announcements/{announcement}', [\App\Http\Controllers\CESO\CESOAnnouncementController::class, 'destroy'])
        ->name('ceso.announcements.destroy');

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

// Database setup routes
Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
Route::post('/setup/add-entry-codes', [SetupController::class, 'addEntryCodeColumn'])->name('setup.add-entry-codes');

// Public announcements routes
Route::get('/announcements', [AnnouncementController::class, 'index'])
    ->name('announcements.index');

Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
    ->name('announcements.show');