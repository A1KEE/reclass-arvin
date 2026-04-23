<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\PpstIndicator;
use App\Models\School;

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
        $application = Application::with([
            'educations',
            'trainings',
            'experiences',
            'eligibilities',
            'scores',
            'ppstRatings'
        ])->findOrFail($id);

        // Security check
        if ($application->email !== auth()->user()->email) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | QUALIFICATION STANDARDS (QS)
        |--------------------------------------------------------------------------
        | FIX:
        | Same logic as AdminController
        | Uses:
        |   level + position
        | instead of:
        |   position only
        */

        $qsConfig = config('qs');

        // levels can be array or json string
        $levels = is_array($application->levels)
            ? $application->levels
            : json_decode($application->levels, true);

        // get first selected level only
        $level = $levels[0] ?? null;

        // clean position
        $positionApplied = trim($application->position_applied);

        // default fallback
        $qs = [
            'education'  => 'No QS Found',
            'training'   => 'No QS Found',
            'experience' => 'No QS Found',
            'eligibility'=> 'No QS Found',
        ];

        // actual QS lookup
        if ($level && $positionApplied && isset($qsConfig[$level][$positionApplied])) {
            $qs = $qsConfig[$level][$positionApplied];
        }

        /*
        |--------------------------------------------------------------------------
        | PPST POSITION LEVEL
        |--------------------------------------------------------------------------
        */

        if (str_contains($positionApplied, 'Master Teacher')) {
            $positionLevel = 'Master Teacher II–III';
        } else {
            $positionLevel = 'Teacher I – MT I';
        }

        $ppstIndicators = PpstIndicator::where('position_level', $positionLevel)
            ->orderBy('domain')
            ->orderBy('order')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PPST RATINGS
        |--------------------------------------------------------------------------
        */

        $ratings = $application->ppstRatings
            ->whereIn('ppst_indicator_id', $ppstIndicators->pluck('id'))
            ->pluck('rating', 'ppst_indicator_id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | FINAL RESULT
        |--------------------------------------------------------------------------
        */

        $finalResult = optional($application->scores)->final_result;

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('applicants.view', [
            'application' => $application,

            'positions' => [
                'Teacher I',
                'Teacher II',
                'Teacher III',
                'Teacher IV',
                'Teacher V',
                'Teacher VI',
                'Teacher VII',
                'Master Teacher I',
                'Master Teacher II',
                'Master Teacher III'
            ],

            'schools' => School::all(),

            'ppstIndicators' => $ppstIndicators,

            'ratings' => $ratings,

            'finalResult' => $finalResult,

            'qs' => $qs,
        ]);
    }
}