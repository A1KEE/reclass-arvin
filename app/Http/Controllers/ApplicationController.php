<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Applicant;
use App\Models\School;
use App\Models\Training;
use App\Models\EducationFile;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
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

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Email failed: '.$e->getMessage());
            return response()->json(['success' => false]);
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

        /* ===== READ QS UNITS (DISPLAY ONLY) ===== */
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

        return view('applicants.create', compact(
            'schools',
            'positions',
            'level',
            'position',
            'requiredHours',
            'qsUnits'
        ));
    }

    /* =====================================================
     | STORE APPLICANT + MULTIPLE EDUCATION PDF UPLOAD
     ===================================================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'position_applied'  => 'required|string',
            'station_school_id' => 'nullable|exists:schools,id',
            'current_position' => 'nullable|string',
            'item_number'       => 'nullable|string',
            'sg_annual_salary'  => 'nullable|string',
            'levels'            => 'nullable|array',
            'levels.*'          => 'string',
            'education_files.*' => 'nullable|mimes:pdf|max:5120',
        ]);

        $data['levels'] = $data['levels'] ?? [];

        $applicant = Applicant::create($data);

        if ($request->hasFile('education_files')) {
            foreach ($request->file('education_files') as $file) {

                $filename = time() . '_' . uniqid() . '.pdf';

                $path = $file->storeAs(
                    "education_pdfs/{$applicant->id}",
                    $filename,
                    'public'
                );

                EducationFile::create([
                    'applicant_id'  => $applicant->id,
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Applicant saved successfully.');
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
            return response()->json([
                'success' => true,
                'data' => $qsConfig[$level][$position]
            ]);
        }

        return response()->json(['success' => false]);
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
            'training_name'=> $request->training_name,
            'training_date'=> $request->training_date,
            'file_path'    => $path,
        ]);

        return response()->json(['status' => 'success']);
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

    /* =====================================================
     | AJAX: EXPERIENCE
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
}
