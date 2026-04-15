<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\School;
use App\Models\PpstIndicator;
use App\Models\ApplicationScore;
use App\Exports\ApplicantsExport;

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
    $evaluated = Application::where('status', 'evaluated')->count();
    $approved = Application::where('status', 'approved')->count();

    $user = auth()->user();

    $notifications = collect();

   if ($user->hasRole('admin')) {
    $filteredNotifs = Application::where('status', 'pending')
        ->where('admin_is_read', 0)
        ->where('super_admin_is_read', 0) // 🔥 ADD
        ->latest()
        ->get();
} elseif ($user->hasRole('super_admin')) {
    $filteredNotifs = Application::where('status', 'evaluated')
        ->where('super_admin_is_read', 0)
        ->latest()
        ->get();
}

$newPending = $filteredNotifs; // ✅ FIX
$newPendingCount = $filteredNotifs->count();
    // =========================
    // RETURN VIEW
    // =========================
    return view('admin.dashboard', [
        'newPending' => $newPending,
        'newPendingCount' => $newPendingCount,
        'filteredNotifs' => $filteredNotifs,
        'total' => $total,
        'pending' => $pending,
        'draft' => $draft,
        'evaluated' => $evaluated,
        'approved' => $approved,

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

        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $notifications = Application::where('status', 'pending')
                ->where('admin_is_read', 0)
                ->latest()
                ->get();
        } elseif ($user->hasRole('super_admin')) {
            $notifications = Application::where('status', 'evaluated')
                ->where('super_admin_is_read', 0)
                ->latest()
                ->get();
        } else {
            $notifications = collect();
        }

       return view('admin.applicants', compact(
            'applicants',
            'notifications'
        ))->with('filteredNotifs', $notifications);
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

    $finalResult = optional($application->scores)->final_result;

    $positions = [
        'Teacher I', 'Teacher II', 'Teacher III',
        'Teacher IV', 'Teacher V', 'Teacher VI', 'Teacher VII',
        'Master Teacher I', 'Master Teacher II', 'Master Teacher III'
    ];

    $schools = School::all();

    // Detect position group
    // Detect position group
    if (str_contains($application->position_applied, 'Master Teacher')) {
        $positionLevel = 'Master Teacher II–III';
    } else {
        $positionLevel = 'Teacher I – MT I';
    }

    // Get only matching indicators
    $ppstIndicators = PpstIndicator::where('position_level', $positionLevel)
        ->orderBy('domain')
        ->orderBy('order')
        ->get();
    $ratings = $application->ppstRatings
    ->whereIn('ppst_indicator_id', $ppstIndicators->pluck('id'))
    ->pluck('rating', 'ppst_indicator_id')
    ->toArray();

    $coi_O = 0;
$coi_VS = 0;
$ncoi_O = 0;
$ncoi_VS = 0;

foreach ($ppstIndicators as $indicator) {

    $rating = $ratings[$indicator->id]->rating ?? null;

    if ($indicator->indicator_type === 'COI') {
        if ($rating === 'O') $coi_O++;
        if ($rating === 'VS') $coi_VS++;
    }

    if ($indicator->indicator_type === 'NCOI') {
        if ($rating === 'O') $ncoi_O++;
        if ($rating === 'VS') $ncoi_VS++;
    }
}
    return view('admin.view', [
        'application' => $application,
        'positions' => $positions,
        'schools' => $schools,
        'ppstIndicators' => $ppstIndicators,
        'ratings' => $ratings,
        'coi_O' => $coi_O,
        'coi_VS' => $coi_VS,
        'ncoi_O' => $ncoi_O,
        'ncoi_VS' => $ncoi_VS,
        'finalResult' => $finalResult,

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
public function update(Request $request, $id)
{
    $score = ApplicationScore::firstOrCreate(
        ['application_id' => $id]
    );

    $score->education_points   = $request->comparative['education'] ?? 0;
    $score->training_points    = $request->comparative['training'] ?? 0;
    $score->experience_points  = $request->comparative['experience_points'] ?? 0;
    $score->performance_points = $request->comparative['performance'] ?? 0;

    $score->coi_score  = $request->comparative['coi_score'] ?? 0;
    $score->ncoi_score = $request->comparative['ncoi_score'] ?? 0;
    $score->bei_score  = $request->comparative['bei_score'] ?? 0;

    $score->total_score = $request->comparative['total'] ?? 0;
    $score->save();

    // 🔥 AUTO UPDATE STATUS
    $application = Application::findOrFail($id);
    $application->status = 'evaluated';

    // reset both so correct ang notif flow
    $application->admin_is_read = 1; 
    $application->super_admin_is_read = 0;

    $application->last_activity_at = now();
    $application->save();

    return back()->with('success', 'Scores updated and application evaluated!');
}
    public function settings()
    {
        return view('admin.settings');
    }

    public function ranking()
{
    $applications = Application::with('scores')
        ->join('application_scores', 'applications.id', '=', 'application_scores.application_id')
        ->orderByDesc('application_scores.total_score')
        ->select('applications.*', 'application_scores.total_score', 'application_scores.final_result')
        ->get();

    $applications = $applications->map(function ($app, $index) {
        $app->rank = $index + 1;
        return $app;
    });

    return view('admin.ranking', compact('applications'));
}
public function finalApprove($id)
{
    if (!auth()->user()->hasRole('super_admin')) {
        abort(403);
    }

    $app = Application::findOrFail($id);

    if ($app->status !== 'evaluated') {
        return back()->with('info', 'Only evaluated applications can be approved.');
    }

    $app->status = 'approved';
    $app->save();

    return back()->with('success', 'Applicant approved successfully.');
}
public function finalReject($id)
{
    if (!auth()->user()->hasRole('super_admin')) {
        abort(403);
    }

    $app = Application::findOrFail($id);

    if ($app->status !== 'evaluated') {
        return back()->with('info', 'Only evaluated applications can be rejected.');
    }

    $app->status = 'rejected';
    $app->save();

    return back()->with('success', 'Applicant rejected.');
}
public function export($id)
{
    $application = Application::findOrFail($id);

    // 🔥 ONLY restrict if APPLICANT
    if (auth()->user()->hasRole('applicant')) {
        if ($application->email !== auth()->user()->email) {
            abort(403);
        }
    }

    return (new ApplicantsExport())->download($id);
}
public function markAsRead($id)
{
    $app = Application::find($id);

    if ($app) {

        if (auth()->user()->hasRole('admin')) {
            $app->admin_is_read = 1;
        }

        if (auth()->user()->hasRole('super_admin')) {
            $app->super_admin_is_read = 1;
        }

        $app->save();
    }

    return response()->json(['success' => true]);
}
public function markAllAsRead()
{
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        Application::where('status', 'pending')
            ->where('admin_is_read', 0)
            ->update(['admin_is_read' => 1]);
    }

    if ($user->hasRole('super_admin')) {
        Application::where('status', 'evaluated')
            ->where('super_admin_is_read', 0)
            ->update(['super_admin_is_read' => 1]);
    }

    return response()->json(['success' => true]);
}
}

