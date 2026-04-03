<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminFileController;
use App\Http\Controllers\ApplicantDashboardController;
use App\Models\Application;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (APPLICANT SIDE)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

// Root → Applicant Form
Route::get('/', function () {
    return redirect('/applicants/create');
});

// Applicant form page
Route::get('/applicants/create', [ApplicationController::class, 'create'])
    ->name('applicants.create');

// Submit application
Route::post('/applicants', [ApplicationController::class, 'store'])
    ->name('applicants.store');

// AJAX: Get QS / requirements
Route::get('/get-qs', [ApplicationController::class, 'getQS'])
    ->name('get.qs');

// AJAX: Experience requirement
Route::post('/qs/experience-requirement', [ApplicationController::class, 'experienceRequirement']);

// Notify unqualified applicants
Route::post('/notify-unqualified', [ApplicationController::class, 'notifyUnqualified'])
    ->name('applicants.notifyUnqualified');

// Load PPST data
Route::get('/load-ppst', [ApplicationController::class, 'loadPPST']);

Route::post('/check-email', [ApplicationController::class, 'checkEmail']);

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (ADMIN SIDE)
|--------------------------------------------------------------------------
*/

// 🔐 CHANGE PASSWORD (any logged user)
Route::middleware(['auth'])->group(function () {

    Route::get('/change-password', [ChangePasswordController::class, 'index'])
        ->name('change.password');

    Route::post('/change-password', [ChangePasswordController::class, 'update'])
        ->name('change.password.update');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (APPLICANT SIDE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:applicant'])->group(function () {

    Route::get('/applicant/dashboard', function () {

        $applications = Application::where('email', auth()->user()->email)
            ->latest()
            ->get();

        return view('applicants.dashboard', compact('applications'));

    })->name('applicant.dashboard');

});


// 🔥 ADMIN ONLY
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        Route::get('/applicants', [AdminController::class, 'applicants'])
            ->name('admin.applicants');

        Route::get('/applicants/{id}', [AdminController::class, 'show'])
            ->name('admin.applicants.show');

        Route::post('/applicants/{id}/status', [AdminController::class, 'updateStatus'])
            ->name('admin.applicants.status');

        Route::get('/settings', [AdminController::class, 'settings'])
            ->name('admin.settings');

        Route::put('/scores/{id}', [AdminController::class, 'update'])
            ->name('admin.scores.update');

        Route::get('/files', [AdminFileController::class, 'index'])
            ->name('admin.files.index');

        Route::get('/files/{folder}', [AdminFileController::class, 'show'])
            ->name('admin.files.show');

        Route::get('/files/{folder}/download', [AdminFileController::class, 'downloadZip'])
             ->name('admin.files.download');

         Route::get('/admin/ranking', [AdminController::class, 'ranking'])
            ->name('admin.ranking');

        // ========================
        // USERS CRUD 🔥
        // ========================
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');

        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');

        Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
});