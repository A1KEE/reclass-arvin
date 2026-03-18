<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

// Redirect root to applicant form
Route::get('/', function() {
    return redirect('/applicants/create');
});

// Show applicant creation form
Route::get('/applicants/create', [ApplicationController::class, 'create'])
    ->name('applicants.create');

// Submit final applicant form
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

Route::get('/load-ppst', [ApplicationController::class, 'loadPPST']);