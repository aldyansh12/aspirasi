<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SiswaController;


/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check()) {
        $user = auth()->user();

        return match ($user->roles) {
            'admin', 'super_admin' => redirect()->route('admin.dashboard'),
            'siswa'               => redirect()->route('siswa.dashboard'),
            default               => redirect()->route('login'),
        };
    }

    return redirect()->route('login');

})->name('home');


/*
|--------------------------------------------------------------------------
| GUEST ONLY (LOGIN + OTP)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    | Login
    */
    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');


    /*
    | OTP
    */
    Route::get('/otp', [OtpController::class, 'index'])   // tampilkan form
        ->name('otp.form');

    Route::post('/otp', [OtpController::class, 'verify']) // verifikasi
        ->name('otp.verify');
});


/*
|--------------------------------------------------------------------------
| AUTH ONLY
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});


/*
|--------------------------------------------------------------------------
| SISWA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:siswa'])->group(function () {

    Route::get('/dashboard', [SiswaController::class, 'dashboard'])
        ->name('siswa.dashboard');

    Route::get('/aspirasi', [SiswaController::class, 'aspirasisaya'])
        ->name('siswa.aspirasisaya');

    Route::get('/aspirasi/create', [SiswaController::class, 'buataspirasi'])
        ->name('siswa.buataspirasi');

    Route::post('/aspirasi', [SiswaController::class, 'storeAspirasi'])
        ->name('siswa.aspirasi.store');

    Route::get('/aspirasi/{id}', [SiswaController::class, 'showAspirasi'])
        ->name('siswa.aspirasi.show');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/feedback', [FeedbackController::class, 'index'])
            ->name('admin.feedback');

        Route::get('/feedback/{id}', [FeedbackController::class, 'show'])
            ->name('admin.feedback.show');

        Route::post('/feedback/{id}/reply', [FeedbackController::class, 'reply'])
            ->name('admin.feedback.reply');

        Route::post('/feedback/{id}/status', [FeedbackController::class, 'updateStatus'])
            ->name('admin.feedback.status');

        Route::get('/log', [LogController::class, 'index'])
            ->name('admin.log');

        /*
        | Users
        */
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('admin.users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('admin.users.store');

        Route::get('/users/{id}/edit', [UserController::class, 'edit'])
            ->name('admin.users.edit');

        Route::put('/users/{id}', [UserController::class, 'update'])
            ->name('admin.users.update');
        /*
        | Penugasan & Manajemen Personil (Consolidated)
        */
        Route::prefix('category-assignments')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CategoryAssignmentController::class, 'index'])
                ->name('admin.category-assignments.index');
                
            // Person Management
            Route::post('/person', [\App\Http\Controllers\Admin\CategoryAssignmentController::class, 'storePerson'])
                ->name('admin.category-assignments.store-person');
            Route::put('/person/{id}', [\App\Http\Controllers\Admin\CategoryAssignmentController::class, 'updatePerson'])
                ->name('admin.category-assignments.update-person');
            Route::delete('/person/{id}', [\App\Http\Controllers\Admin\CategoryAssignmentController::class, 'destroyPerson'])
                ->name('admin.category-assignments.destroy-person');
                
            // Assignments
            Route::post('/{pj_id}/assign', [\App\Http\Controllers\Admin\CategoryAssignmentController::class, 'updateAssignments'])
                ->name('admin.category-assignments.update-assignments');

            // Category Management
            Route::post('/category', [\App\Http\Controllers\Admin\CategoryAssignmentController::class, 'storeCategory'])
                ->name('admin.category-assignments.store-category');
            Route::put('/category/{id}', [\App\Http\Controllers\Admin\CategoryAssignmentController::class, 'updateCategory'])
                ->name('admin.category-assignments.update-category');
        });
    });
