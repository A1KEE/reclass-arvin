<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\PpstIndicator;

class ApplicantDashboardController extends Controller
{
    public function index()
    {
        $applications = Application::where('email', auth()->user()->email)
            ->latest()
            ->get();

        return view('applicants.dashboard', compact('applications'));
    }

public function view($id)
{
    $application = \App\Models\Application::with([
        'educations',
        'trainings',
        'experiences',
        'eligibilities',
        'scores'
    ])->findOrFail($id);

    if ($application->email !== auth()->user()->email) {
        abort(403);
    }

   if (str_contains($application->position, 'Master Teacher')) {
    $positionLevel = 'Master Teacher II–III';
    } else {
        $positionLevel = 'Teacher I – MT I';
    }

    $ppstIndicators = PpstIndicator::where('position_level', $positionLevel)
        ->orderBy('domain')
        ->orderBy('order')
        ->get();

    $ratings = $application->ppstRatings
        ->whereIn('ppst_indicator_id', $ppstIndicators->pluck('id'))
        ->pluck('rating', 'ppst_indicator_id')
        ->toArray();

    // ✅ FINAL RESULT
    $finalResult = optional($application->scores)->final_result;

    return view('applicants.view', [
        'application' => $application,
        'positions' => [
            'Teacher I','Teacher II','Teacher III','Teacher IV',
            'Teacher V','Teacher VI','Teacher VII',
            'Master Teacher I','Master Teacher II','Master Teacher III'
        ],
        'schools' => \App\Models\School::all(),
        'ppstIndicators' => $ppstIndicators, // 🔥 FIXED
        'ratings' => $ratings,
        'finalResult' => $finalResult
    ]);
}
}