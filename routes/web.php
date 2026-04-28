<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminFileController;
use App\Http\Controllers\ApplicantDashboardController;
use App\Http\Controllers\QSController;
use App\Models\Application;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (APPLICANT SIDE)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/applicants/create');
});

Route::get('/applicants/create', [ApplicationController::class, 'create'])
    ->name('applicants.create');

Route::post('/applicants', [ApplicationController::class, 'store'])
    ->name('applicants.store');

Route::get('/get-qs', [ApplicationController::class, 'getQS'])
    ->name('get.qs');

Route::post('/qs/experience-requirement', [ApplicationController::class, 'experienceRequirement']);

Route::post('/notify-unqualified', [ApplicationController::class, 'notifyUnqualified'])
    ->name('applicants.notifyUnqualified');

Route::get('/ppst/load-applicant', [ApplicationController::class, 'loadPPSTApplicant'])
    ->name('applicant.ppst.load');

Route::post('/check-email', [ApplicationController::class, 'checkEmail']);

Route::get('/applicant/application/{id}', [ApplicantDashboardController::class, 'view'])
    ->name('applicant.view');

/*
|--------------------------------------------------------------------------
| CHANGE PASSWORD (ALL USERS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/change-password', [ChangePasswordController::class, 'index'])
        ->name('change.password');

    Route::post('/change-password', [ChangePasswordController::class, 'update'])
        ->name('change.password.update');
});

/*
|--------------------------------------------------------------------------
| APPLICANT DASHBOARD (USING CONTROLLER - FIXED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:applicant'])->group(function () {

    Route::get('/applicant/dashboard', [ApplicantDashboardController::class, 'index'])
        ->name('applicant.dashboard');

    Route::get('/applicant/export/{id}', [AdminController::class, 'export'])
    ->name('applicant.export');

});

/*
|--------------------------------------------------------------------------
| QS EDITOR ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('qs')
    ->middleware(['auth', 'role:qs_editor'])
    ->name('qs.')
    ->group(function () {

        Route::get('/dashboard', [QSController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/applicants/{id}', [QSController::class, 'show'])
            ->name('applicants.show');

        Route::put('/applicants/{id}/update-details', [QSController::class, 'updateDetails'])
            ->name('applicants.update-details');

        // ✅ ADD THIS
        Route::put('/application/{id}/update',
            [QSController::class, 'updateApplication'])
            ->name('application.update');

        Route::post('/education/update/{id}', [QSController::class, 'updateEducation'])
             ->name('education.update');

        // Training update route (para sa QS Editor)
         Route::put('/training/update/{id}', [QSController::class, 'updateTraining'])
            ->name('training.update');
           
        Route::get('/experiences/{applicationId}', [QSController::class, 'getExperiences'])
             ->name('experiences');
       
             Route::put('/experience/update/{id}', [QSController::class, 'updateExperience'])
            ->name('experience.update');

    });

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'role:admin|super_admin|qs_editor'])
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
            ->middleware('role:super_admin')
            ->name('admin.ranking');

        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users');

        Route::post('/users', [UserController::class, 'store'])
            ->name('admin.users.store');

        Route::put('/users/{id}', [UserController::class, 'update'])
            ->name('admin.users.update');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])
            ->name('admin.users.delete');

        Route::get('/admin/applicants/export/{id}', [AdminController::class, 'export'])
            ->name('admin.applicants.export');

        Route::get('/ppst/load', [ApplicationController::class, 'loadPPSTAdmin'])
            ->name('admin.ppst.load');

        Route::post('/notifications/read/{id}', [AdminController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [AdminController::class, 'markAllAsRead']);

        Route::post('/education/update', [AdminController::class, 'updateEducation'])
        ->name('education.update');
    
        Route::post('/education/delete', [AdminController::class, 'deleteEducation'])
            ->name('education.delete');
    });

/*
|--------------------------------------------------------------------------
| SUPER ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('superadmin')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('superadmin.dashboard');

        Route::get('/applicants', [AdminController::class, 'applicants'])
            ->name('superadmin.applicants');

        Route::get('/applicants/{id}', [AdminController::class, 'show'])
            ->name('superadmin.applicants.show');

        Route::post('/applicants/{id}/approve', [AdminController::class, 'finalApprove'])
            ->name('superadmin.applicants.approve');

        Route::post('/applicants/{id}/reject', [AdminController::class, 'finalReject'])
            ->name('superadmin.applicants.reject');
            
        Route::post('/notifications/read/{id}', [AdminController::class, 'markAsRead']);
        Route::post('/superadmin/notifications/read-all', [AdminController::class, 'markAllAsRead']);
    });
