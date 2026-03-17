<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\Application;
use App\Models\Applicant;
use App\Models\School;
use App\Models\Training;
use App\Models\EducationFile;
use App\Models\PpstIndicator;

class ApplicationController extends Controller
{
    /* =====================================================
     | STORE FINAL APPLICANT (SUBMIT)
     ===================================================== */
 public function store(Request $request)
{
    // 1️⃣ Validate required fields
    $request->validate([
        'name' => 'required|string|max:255',
        'position_applied' => 'required|string',
        'levels' => 'required|array', // multiple levels
        'school_name' => 'required|string',
    ]);

    $position = $request->position_applied;
    $levels = $request->levels; // array of selected levels

    // 2️⃣ Prepare combined QS data for selected levels
    $qsCombined = [];
    foreach ($levels as $level) {
        $levelKey = strtolower($level); // ensure lowercase matches config keys
        $qsData = config("qs.$levelKey.$position", []);

        $qsCombined[$levelKey] = [
            'qs_position_education'   => $qsData['education'] ?? null,
            'qs_position_training'    => $qsData['training'] ?? null,
            'qs_position_experience'  => $qsData['experience'] ?? null,
            'qs_position_eligibility' => $qsData['eligibility'] ?? null,
        ];
    }

    // 3️⃣ Save Application
    Application::create([
        'name' => $request->name,
        'current_position' => $request->current_position,
        'position_applied' => $position,
        'item_number' => $request->item_number,
        'school_name' => $request->school_name,
        'sg_annual_salary' => $request->sg_annual_salary,
        'levels' => json_encode($levels),

        // QS combined per level
        'qs_data' => json_encode($qsCombined), // single JSON column for all selected levels

        // Applicant inputs & remarks (optional)
        'qs_applicant_education'   => $request->qs_applicant_education,
        'remarks_education'        => $request->remarks_education,
        'qs_applicant_training'    => $request->qs_applicant_training,
        'remarks_training'         => $request->remarks_training,
        'qs_applicant_experience'  => $request->qs_applicant_experience,
        'remarks_experience'       => $request->remarks_experience,
        'qs_applicant_eligibility' => $request->qs_applicant_eligibility,
        'remarks_eligibility'      => $request->remarks_eligibility,

        'status' => 'submitted',
        'last_activity_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Application submitted successfully.');
}
    /* =====================================================
     | EMAIL: UNQUALIFIED
     ===================================================== */
    public function notifyUnqualified(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'remarks' => 'required|array'
        ]);

        try {
            Mail::send([], [], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Application Status Notification')
                    ->setBody(
                        "Dear Applicant,<br><br>
                        After evaluating your qualifications, some requirements were not met.<br><br>
                        <strong>Remarks:</strong><br>" . implode('<br>', $request->remarks) . "<br><br>
                        You may review your application and reapply if applicable.<br><br>
                        Best regards,<br>HR Department",
                        'text/html'
                    );
            });

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Email failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Email failed to send. SMTP error: ' . $e->getMessage()
            ]);
        }
    }

    /* =====================================================
     | CREATE FORM
     ===================================================== */
  public function create(Request $request)
{
    $schools = School::orderBy('name')->get();

    $positions = [
        'Teacher I','Teacher II','Teacher III','Teacher IV',
        'Teacher V','Teacher VI','Teacher VII',
        'Master Teacher I','Master Teacher II',
        'Master Teacher III','Master Teacher IV','Master Teacher V'
    ];

    $level    = $request->get('level', 'elementary');
    $position = $request->get('position', 'Teacher I');

    $requiredHours = $this->requiredTrainingHours($level, $position);

    $qs = config('qs');
    $qsUnits = [];

    foreach ($qs as $levelKey => $positionsData) {
        foreach ($positionsData as $posTitle => $info) {

            if (isset($info['education'])) {

                if (preg_match('/(\d+)\s+professional\s+units/i', $info['education'], $m)) {
                    $qsUnits[strtolower($levelKey)][strtolower($posTitle)] = (int) $m[1];
                } else {
                    $qsUnits[strtolower($levelKey)][strtolower($posTitle)] = 0;
                }

            }

        }
    }

    $levelPPST = $this->mapPositionToDbLevel($position); // <- TAMA ito

    $ppstIndicators = collect(); // empty muna

    return view('applicants.create', compact(
        'schools',
        'positions',
        'level',
        'position',
        'requiredHours',
        'qsUnits',
        'ppstIndicators'
    ));
}

private function mapPositionToDbLevel($position)
{
    $map = [

        'Teacher I'   => 'Teacher I – MT I',
        'Teacher II'  => 'Teacher I – MT I',
        'Teacher III' => 'Teacher I – MT I',
        'Teacher IV'  => 'Teacher I – MT I',
        'Teacher V'   => 'Teacher I – MT I',
        'Teacher VI'  => 'Teacher I – MT I',
        'Teacher VII' => 'Teacher I – MT I',
        'Master Teacher I' => 'Teacher I – MT I',

        'Master Teacher II'  => 'Master Teacher II–III',
        'Master Teacher III' => 'Master Teacher II–III',
        'Master Teacher IV'  => 'Master Teacher II–III',
        'Master Teacher V'   => 'Master Teacher II–III',
    ];

    return $map[$position] ?? 'Teacher I – MT I';
}

public function loadPPST(Request $request)
{
    $position = $request->position;

    $level = $this->mapPositionToDbLevel($position);

    $ppstIndicators = PpstIndicator::where('position_level', $level)
        ->orderBy('order')
        ->get();

    return view('applicants.ppst-table', compact('ppstIndicators'));
}
    /* =====================================================
     | STORE TRAINING
     ===================================================== */
    public function storeTraining(Request $request)
    {
        $request->validate([
            'training_name' => 'required|string|max:255',
            'training_date' => 'required|date',
            'training_file' => 'required|mimes:pdf|max:5120',
            'applicant_id'  => 'required|integer|exists:applicants,id',
        ]);

        $file = $request->file('training_file');

        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->training_name);
        $fileName = $safeName . '_' . $request->training_date . '.pdf';

        $path = $file->storeAs(
            "applicants/{$request->applicant_id}/training",
            $fileName,
            'public'
        );

        Training::create([
            'applicant_id'  => $request->applicant_id,
            'training_name' => $request->training_name,
            'training_date' => $request->training_date,
            'file_path'     => $path,
        ]);

        return response()->json(['status' => 'success']);
    }

    /* =====================================================
     | AJAX: FETCH QS
     ===================================================== */
    public function getQS(Request $request)
    {
        $level    = strtolower($request->get('level'));
        $position = $request->get('position');

        $qsConfig = config('qs');

        if (isset($qsConfig[$level][$position])) {
            $data = $qsConfig[$level][$position];
            return response()->json([
                'success' => true,
                'data' => [
                    'education'        => $data['education'] ?? null,
                    'training'         => $data['training'] ?? null,
                    'training_hours'   => $data['training_hours'] ?? 0,
                    'experience'       => $data['experience'] ?? null,
                    'experience_years' => $data['experience_years'] ?? 0,
                    'eligibility'      => $data['eligibility'] ?? null,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No QS found for this position and level'
        ]);
    }

    /* =====================================================
     | EXPERIENCE REQUIREMENT
     ===================================================== */
    public function experienceRequirement(Request $request)
    {
        $request->validate([
            'level'    => 'required|string',
            'position' => 'required|string',
        ]);

        $qs = config('qs');

        if (!isset($qs[$request->level][$request->position])) {
            return response()->json(['label' => '—', 'years' => 0]);
        }

        $data = $qs[$request->level][$request->position];

        return response()->json([
            'label' => $data['experience'],
            'years' => $data['experience_years'] ?? 0
        ]);
    }

    /* =====================================================
     | HELPER: TRAINING HOURS
     ===================================================== */
    private function requiredTrainingHours($level, $position)
    {
        $qs = config('qs');
        $level = strtolower($level);

        return $qs[$level][$position]['training_hours'] ?? 0;
    }

}