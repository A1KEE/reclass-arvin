<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\School;
use App\Models\PpstIndicator;

class AdminController extends Controller
{
   public function dashboard()
{
    // =========================
    // POSITIONS GROUPED
    // =========================
    $teacherPositions = [
        'Teacher I',
        'Teacher II',
        'Teacher III',
        'Teacher IV',
        'Teacher V',
        'Teacher VI',
        'Teacher VII',
    ];

    $masterPositions = [
        'Master Teacher I',
        'Master Teacher II',
        'Master Teacher III',
    ];

    // =========================
    // COUNTERS
    // =========================
    $teacherCounts = [];
    $masterCounts = [];

    $teacherTotal = 0;
    $masterTotal = 0;

    // Teacher loop
    foreach ($teacherPositions as $pos) {
        $count = Application::where('position_applied', $pos)->count();
        $teacherCounts[] = $count;
        $teacherTotal += $count;
    }

    // Master Teacher loop
    foreach ($masterPositions as $pos) {
        $count = Application::where('position_applied', $pos)->count();
        $masterCounts[] = $count;
        $masterTotal += $count;
    }

    // =========================
    // OVERALL STATS
    // =========================
    $total = Application::count();
    $pending = Application::where('status', 'pending')->count();
    $draft = Application::where('status', 'draft')->count();

    // =========================
    // RETURN VIEW
    // =========================
    return view('admin.dashboard', [
        'total' => $total,
        'pending' => $pending,
        'draft' => $draft,

        // labels
        'teacherPositions' => $teacherPositions,
        'masterPositions' => $masterPositions,

        // data
        'teacherCounts' => $teacherCounts,
        'masterCounts' => $masterCounts,

        // totals for percent
        'teacherTotal' => $teacherTotal,
        'masterTotal' => $masterTotal,
    ]);
}

    public function applicants()
    {
        $applicants = Application::latest()->get();
        return view('admin.applicants', compact('applicants'));
    }

public function show($id)
{
    $application = Application::with([
        'educations',
        'trainings',
        'experiences',
        'eligibilities',
        'ipcrfs',
        'ppstRatings',
        'scores'
    ])->findOrFail($id);

    $positions = [
        'Teacher I', 'Teacher II', 'Teacher III',
        'Teacher IV', 'Teacher V', 'Teacher VI', 'Teacher VII',
        'Master Teacher I', 'Master Teacher II', 'Master Teacher III'
    ];

    $schools = School::all();

    $ppstIndicators = PpstIndicator::orderBy('order')->get();

    return view('admin.view', [
        'application' => $application,
        'positions' => $positions,
        'schools' => $schools,
        'ppstIndicators' => $ppstIndicators,

        // 🔥 IMPORTANT: for JS adminData
        'adminData' => [
            'educations'   => $application->educations,
            'trainings'    => $application->trainings,
            'experiences'  => $application->experiences,
            'eligibilities'=> $application->eligibilities,
            'ipcrfs'       => $application->ipcrfs,
            'ppstRatings'  => $application->ppstRatings,
            'scores'       => $application->scores,
        ]
    ]);
}
    public function settings()
    {
        return view('admin.settings');
    }
}

