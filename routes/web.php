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

// Validate uploaded education PDF
Route::post('/validate-education-file', function(Request $request){
    if(!$request->hasFile('education_file')) {
        return response()->json(['status'=>'invalid']);
    }

    $file = $request->file('education_file');

    if($file->getClientOriginalExtension() !== 'pdf'){
        return response()->json(['status'=>'invalid']);
    }

    $parser = new Parser();
    $pdf = $parser->parseFile($file->getPathname());
    $text = strtolower($pdf->getText());

    // Keywords for matching
    $bachelorKeywords = [
        'bachelor of education',
        'bachelor of secondary education',
        'bsed','beed','b.ed','b.s.ed',
        'bachelor in secondary education'
    ];

    $masterKeywords = [
        'master of education',
        'master of arts in education',
        'maed','m.ed','master’s degree',
        'educational leadership','educational management'
    ];

    $invalidKeywords = [
        'loan','co-borrower','housing','agreement','receipt',
        'invoice','contract','salary','certificate of employment'
    ];

    if(preg_match('/'.implode('|',$invalidKeywords).'/',$text)){
        return response()->json(['status'=>'invalid']);
    } elseif(preg_match('/'.implode('|',$masterKeywords).'/',$text)){
        return response()->json(['status'=>'valid','level'=>'master']);
    } elseif(preg_match('/'.implode('|',$bachelorKeywords).'/',$text)){
        return response()->json(['status'=>'valid','level'=>'bachelor']);
    } else {
        return response()->json(['status'=>'invalid']);
    }
});