<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class QSController extends Controller
{
    public function dashboard()
    {
        $applications = Application::latest()->get();

        // Always pass filteredNotifs
        $filteredNotifs = collect();

        return view('qs.dashboard', [
            'applications'   => $applications,
            'filteredNotifs' => $filteredNotifs
        ]);
    }

    public function show($id)
    {
        $application = Application::with([
            'educations',
            'trainings',
            'experiences',
            'eligibilities',
            'scores'
        ])->findOrFail($id);

        // Always pass filteredNotifs
        $filteredNotifs = collect();

        return view('qs.applicant-edit', [
            'application'    => $application,
            'filteredNotifs' => $filteredNotifs
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | OLD UPDATE DETAILS
    |--------------------------------------------------------------------------
    */

    public function updateDetails(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | EDUCATION
        |--------------------------------------------------------------------------
        */

        if ($request->has('educations')) {
            $application->educations()->delete();

            foreach ($request->educations as $edu) {
                if (!empty($edu['degree'])) {
                    $application->educations()->create([
                        'degree'          => $edu['degree'] ?? null,
                        'school'          => $edu['school'] ?? null,
                        'date_graduated'  => $edu['date_graduated'] ?? null,
                        'units'           => $edu['units'] ?? null,
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TRAINING
        |--------------------------------------------------------------------------
        */

        if ($request->has('trainings')) {
            $application->trainings()->delete();

            foreach ($request->trainings as $training) {
                if (!empty($training['title'])) {
                    $application->trainings()->create([
                        'title'       => $training['title'] ?? null,
                        'type'        => $training['type'] ?? null,
                        'hours'       => $training['hours'] ?? 0,
                        'start_date'  => $training['start_date'] ?? null,
                        'end_date'    => $training['end_date'] ?? null,
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXPERIENCE
        |--------------------------------------------------------------------------
        */

        if ($request->has('experiences')) {
            $application->experiences()->delete();

            foreach ($request->experiences as $experience) {
                if (!empty($experience['position'])) {
                    $application->experiences()->create([
                        'position'     => $experience['position'] ?? null,
                        'school'       => $experience['school'] ?? null,
                        'school_type'  => $experience['school_type'] ?? null,
                        'start_date'   => $experience['start_date'] ?? null,
                        'end_date'     => $experience['end_date'] ?? null,
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ELIGIBILITY
        |--------------------------------------------------------------------------
        */

        if ($request->has('eligibilities')) {
            $application->eligibilities()->delete();

            foreach ($request->eligibilities as $eligibility) {
                if (!empty($eligibility['eligibility_name'])) {
                    $application->eligibilities()->create([
                        'eligibility_name' => $eligibility['eligibility_name'] ?? null,
                        'expiry_date'      => $eligibility['expiry_date'] ?? null,
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | REMARKS
        |--------------------------------------------------------------------------
        */

        $application->update([
            'education_remarks'   => $request->education_remarks,
            'training_remarks'    => $request->training_remarks,
            'experience_remarks'  => $request->experience_remarks,
            'eligibility_remarks' => $request->eligibility_remarks,
        ]);

        return redirect()
            ->route('qs.applicants.show', $id)
            ->with('success', 'Applicant details updated successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | NEW MAIN UPDATE FOR QS EDITOR
    |--------------------------------------------------------------------------
    */

   public function updateApplication(Request $request, $id)
{
    $application = Application::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | EDUCATION
    |--------------------------------------------------------------------------
    */

    if ($request->has('educations')) {
        $application->educations()->delete();

        foreach ($request->educations as $edu) {
            if (!empty($edu['degree'])) {
                $application->educations()->create([
                    'degree'          => $edu['degree'] ?? null,
                    'school'          => $edu['school'] ?? null,
                    'date_graduated'  => $edu['date_graduated'] ?? null,
                    'units'           => $edu['units'] ?? null,
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TRAINING
    |--------------------------------------------------------------------------
    */

    if ($request->has('trainings')) {
        $application->trainings()->delete();

        foreach ($request->trainings as $training) {
            if (!empty($training['title'])) {
                $application->trainings()->create([
                    'title'       => $training['title'] ?? null,
                    'type'        => $training['type'] ?? null,
                    'hours'       => $training['hours'] ?? 0,
                    'start_date'  => $training['start_date'] ?? null,
                    'end_date'    => $training['end_date'] ?? null,
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EXPERIENCE
    |--------------------------------------------------------------------------
    */

    if ($request->has('experiences')) {
        $application->experiences()->delete();

        foreach ($request->experiences as $exp) {
            if (!empty($exp['position'])) {
                $application->experiences()->create([
                    'position'     => $exp['position'] ?? null,
                    'school'       => $exp['school'] ?? null,
                    'school_type'  => $exp['school_type'] ?? null,
                    'start_date'   => $exp['start_date'] ?? null,
                    'end_date'     => $exp['end_date'] ?? null,
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ELIGIBILITY
    |--------------------------------------------------------------------------
    */

    if ($request->has('eligibilities')) {
        $application->eligibilities()->delete();

        foreach ($request->eligibilities as $el) {
            if (!empty($el['eligibility_name'])) {
                $application->eligibilities()->create([
                    'eligibility_name' => $el['eligibility_name'] ?? null,
                    'expiry_date'      => $el['expiry_date'] ?? null,
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REMARKS UPDATE
    |--------------------------------------------------------------------------
    */

    $application->update([
        'education_remarks'   => $request->education_remarks,
        'training_remarks'    => $request->training_remarks,
        'experience_remarks'  => $request->experience_remarks,
        'eligibility_remarks' => $request->eligibility_remarks,
    ]);

    /*
    |--------------------------------------------------------------------------
    | TOTAL SCORE RECOMPUTATION (PINAKA IMPORTANTE)
    |--------------------------------------------------------------------------
    */

    // Kunin ang scores record
    $score = $application->scores;

     if ($score) {
        // Kunin ang lahat ng values mula sa request (para sumabay sa UI)
        $education = $request->input('comparative.education', 0);
        $training = $request->input('comparative.training', 0);
        $experience = $request->input('comparative.experience_points', 0);
        $performance = $request->input('comparative.performance', 0);
        $coi = $request->input('comparative.coi_score', 0);
        $ncoi = $request->input('comparative.ncoi_score', 0);
        $bei = $request->input('comparative.bei_score', 0);
        
        // Compute total tulad ng ginagawa ng UI
        $totalScore = $education + $training + $experience + $performance + $coi + $ncoi + $bei;
        
        // I-save ang total_score
        $score->total_score = $totalScore;
        
        // I-save din ang ibang fields kung gusto mo
        $score->coi_score = $coi;
        $score->ncoi_score = $ncoi;
        $score->bei_score = $bei;
        $score->performance_points = $performance;
        
        $score->save();
    }
    
    return redirect()
        ->route('qs.applicants.show', $id)
        ->with('success', 'Qualification Standards updated successfully!');
}

  public function updateEducation(Request $request, $id)
{
    try {
        $education = \App\Models\Education::findOrFail($id);
        
        // I-check kung may application relationship
        if (!$education->application) {
            return response()->json([
                'success' => false,
                'message' => 'No application found for this education record'
            ], 404);
        }
        
        $application = $education->application;
        
        // Get the selected value from dropdown
        $selectedValue = $request->units;
        
        // Education levels mapping (same as your JS)
        $educationLevels = [
            0 => "No Formal Education",
            1 => "Can Read and Write",
            2 => "Elementary Graduate",
            3 => "Junior High School (K to 12)",
            4 => "Senior High School (K to 12)",
            5 => "Completed 2 years in College",
            6 => "Bachelor's Degree",
            7 => "6 units of Masters Degree",
            8 => "9 units of Masters Degree",
            9 => "12 units of Masters Degree",
            10 => "15 units of Masters Degree",
            11 => "18 units of Masters Degree",
            12 => "21 units of Masters Degree",
            13 => "24 units of Masters Degree",
            14 => "27 units of Masters Degree",
            15 => "30 units of Masters Degree",
            16 => "33 units of Masters Degree",
            17 => "36 units of Masters Degree",
            18 => "39 units of Masters Degree",
            19 => "42 units of Masters Degree",
            20 => "CAR towards a Masters Degree",
            21 => "Masters Degree",
            22 => "3 units of Doctorate",
            23 => "6 units of Doctorate",
            24 => "9 units of Doctorate",
            25 => "12 units of Doctorate",
            26 => "15 units of Doctorate",
            27 => "18 units of Doctorate",
            28 => "21 units of Doctorate",
            29 => "24 units of Doctorate",
            30 => "CAR towards a Doctorate",
            31 => "Doctorate Degree (completed)"
        ];
        
        // Convert numeric to text
        $unitsToSave = $selectedValue;
        $userLevel = (int)$selectedValue;
        
        if (is_numeric($selectedValue) && isset($educationLevels[(int)$selectedValue])) {
            $unitsToSave = $educationLevels[(int)$selectedValue];
        }
        
        // Update education record
        $education->update([
            'degree' => $request->degree,
            'school' => $request->school,
            'date_graduated' => $request->date_graduated,
            'units' => $unitsToSave,
        ]);
        
        // Compute points based on your logic
        $position = strtolower($application->position_applied ?? '');
        $degreeName = strtolower($request->degree);
        
        // Check if Master Teacher
        $baseLevel = 6; // BASE_LEVEL for Teacher
        if (strpos($position, 'master teacher') !== false) {
            $baseLevel = 21; // MASTER_TEACHER_BASE_LEVEL
        }
        
        // Compute increment and points
        $increment = max(0, $userLevel - $baseLevel);
        
        // Get points based on increment
        $educationPoints = 0;
        if ($increment >= 10) $educationPoints = 10;
        elseif ($increment >= 8) $educationPoints = 8;
        elseif ($increment >= 6) $educationPoints = 6;
        elseif ($increment >= 4) $educationPoints = 4;
        elseif ($increment >= 2) $educationPoints = 2;
        else $educationPoints = 0;
        
        // Determine MET or NOT MET
        $educationRemarks = ($userLevel >= $baseLevel) ? "MET" : "NOT MET";
        
     // I-check kung may scores record
        $score = $application->scores;
        if (!$score) {
            $score = new \App\Models\Score();
            $score->application_id = $application->id;
            $score->education_points = $educationPoints;
            $score->education_remarks = $educationRemarks;
            $score->training_points = 0;
            $score->experience_points = 0;
            $score->performance_points = 0;
            $score->total_score = $educationPoints;
            $score->save();
        } else {
            // UPDATE EDUCATION LANG TALAGA
            $score->education_points = $educationPoints;
            $score->education_remarks = $educationRemarks;
            
            // RECOMPUTE TOTAL SCORE (para updated ang total)
            $score->total_score = ($score->education_points ?? 0) + 
                                ($score->training_points ?? 0) + 
                                ($score->experience_points ?? 0) + 
                                ($score->performance_points ?? 0) +
                                ($score->coi_score ?? 0) +
                                ($score->ncoi_score ?? 0) +
                                ($score->bei_score ?? 0);
            $score->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Education record updated successfully!',
            'points' => $educationPoints,
            'remarks' => $educationRemarks,
            'total_score' => $score->total_score,
            'user_level' => $userLevel,
            'base_level' => $baseLevel,
            'increment' => $increment
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating education: ' . $e->getMessage()
        ], 500);
    }
}

public function updateTraining(Request $request, $id)
{
    try {
        $training = \App\Models\Training::findOrFail($id);
        
        if (!$training->application) {
            return response()->json([
                'success' => false,
                'message' => 'No application found for this training record'
            ], 404);
        }
        
        $application = $training->application;
        
        // Update training record
        $training->update([
            'title' => $request->title,
            'type' => $request->type,
            'hours' => $request->hours ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        
        // ================================
        // 1. GET REQUIRED TRAINING HOURS FROM QS (based on position & level)
        // ================================
        $position = $application->position_applied;
        $levels = $application->levels; // array of levels: ['elementary', 'junior_high', etc.]
        
        $requiredHours = 0;
        $qsConfig = config('qs');
        
        // Hanapin ang required training hours sa QS config
        if ($levels && is_array($levels)) {
            foreach ($levels as $level) {
                if (isset($qsConfig[$level][$position]['training_hours'])) {
                    $requiredHours = (int) $qsConfig[$level][$position]['training_hours'];
                    break;
                }
            }
        }
        
        // ================================
        // 2. TRAINING LEVELS MAPPING (Table 2.b)
        // ================================
        $trainingLevels = [
            0 => 0,      // 0 hours
            8 => 1,      // 8 hours → Level 1
            16 => 2,     // 16 hours → Level 2
            24 => 3,     // 24 hours → Level 3
            32 => 4,     // 32 hours → Level 4
            40 => 5,     // 40 hours → Level 5
            48 => 6,     // 48 hours → Level 6
            56 => 7,     // 56 hours → Level 7
            64 => 8,     // 64 hours → Level 8
            72 => 9,     // 72 hours → Level 9
            80 => 10,    // 80 hours → Level 10
            88 => 11,    // 88 hours → Level 11
            96 => 12,    // 96 hours → Level 12
            104 => 13,   // 104 hours → Level 13
            112 => 14,   // 112 hours → Level 14
            120 => 15,   // 120 hours → Level 15
            128 => 16,   // 128 hours → Level 16
            136 => 17,   // 136 hours → Level 17
            144 => 18,   // 144 hours → Level 18
            152 => 19,   // 152 hours → Level 19
            160 => 20,   // 160 hours → Level 20
            168 => 21,   // 168 hours → Level 21
            176 => 22,   // 176 hours → Level 22
            184 => 23,   // 184 hours → Level 23
            192 => 24,   // 192 hours → Level 24
            200 => 25,   // 200 hours → Level 25
            208 => 26,   // 208 hours → Level 26
            216 => 27,   // 216 hours → Level 27
            224 => 28,   // 224 hours → Level 28
            232 => 29,   // 232 hours → Level 29
            240 => 30,   // 240 hours → Level 30
        ];
        
        function getTrainingLevel($hours) {
            $trainingLevels = [
                0 => 0, 8 => 1, 16 => 2, 24 => 3, 32 => 4, 40 => 5,
                48 => 6, 56 => 7, 64 => 8, 72 => 9, 80 => 10, 88 => 11,
                96 => 12, 104 => 13, 112 => 14, 120 => 15, 128 => 16,
                136 => 17, 144 => 18, 152 => 19, 160 => 20, 168 => 21,
                176 => 22, 184 => 23, 192 => 24, 200 => 25, 208 => 26,
                216 => 27, 224 => 28, 232 => 29, 240 => 30,
            ];
            
            $level = 0;
            foreach ($trainingLevels as $hour => $lvl) {
                if ($hours >= $hour) {
                    $level = $lvl;
                } else {
                    break;
                }
            }
            return $level;
        }
        
        // ================================
        // 3. FILTER NON-TEACHING RELEVANT TRAININGS
        // ================================
        $nonTeachingKeywords = [
            "administrative", "accounting", "finance", "management",
            "ict", "computer", "leadership", "seminar", "orientation", "workshop"
        ];
        
        function isTeachingRelevant($title) {
            if (!$title) return false;
            $title = strtolower($title);
            $keywords = ["administrative", "accounting", "finance", "management",
                         "ict", "computer", "leadership", "seminar", "orientation", "workshop"];
            foreach ($keywords as $kw) {
                if (strpos($title, $kw) !== false) return false;
            }
            return true;
        }
        
        // Kunin ang lahat ng trainings at i-compute ang total teaching-relevant hours
        $allTrainings = $application->trainings;
        $totalTeachingHours = 0;
        
        foreach ($allTrainings as $t) {
            if (isTeachingRelevant($t->title) && $t->hours > 0) {
                $totalTeachingHours += $t->hours;
            }
        }
        
        // ================================
        // 4. COMPUTE POINTS (2 points per level increment)
        // ================================
        $applicantLevel = getTrainingLevel($totalTeachingHours);
        $requiredLevel = getTrainingLevel($requiredHours);
        $increment = max(0, $applicantLevel - $requiredLevel);
        
        // 2 points per increment (max 10 points)
        $trainingPoints = min(10, $increment * 2);
        
        // Determine MET or NOT MET
        $trainingRemarks = ($totalTeachingHours >= $requiredHours && $requiredHours > 0) ? "MET" : "NOT MET";
        
        // ================================
        // 5. UPDATE SCORES
        // ================================
        $score = $application->scores;
        if ($score) {
            $score->training_points = $trainingPoints;
            $score->training_remarks = $trainingRemarks;
            
            // RECOMPUTE TOTAL SCORE
            $score->total_score = ($score->education_points ?? 0) + 
                                  ($score->training_points ?? 0) + 
                                  ($score->experience_points ?? 0) + 
                                  ($score->performance_points ?? 0) +
                                  ($score->coi_score ?? 0) +
                                  ($score->ncoi_score ?? 0) +
                                  ($score->bei_score ?? 0);
            $score->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Training updated successfully!',
            'required_hours' => $requiredHours,
            'required_level' => $requiredLevel,
            'total_hours' => $totalTeachingHours,
            'applicant_level' => $applicantLevel,
            'increment' => $increment,
            'points' => $trainingPoints,
            'remarks' => $trainingRemarks,
            'total_score' => $score->total_score ?? 0
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating training: ' . $e->getMessage()
        ], 500);
    }
}
public function getExperiences($applicationId)
{
    try {
        $application = Application::findOrFail($applicationId);
        $experiences = $application->experiences()->get();
        
        return response()->json([
            'success' => true,
            'experiences' => $experiences
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error loading experiences: ' . $e->getMessage()
        ], 500);
    }
}
public function updateExperience(Request $request, $id)
{
    try {
        $experience = \App\Models\Experience::findOrFail($id);
        
        if (!$experience->application) {
            return response()->json([
                'success' => false,
                'message' => 'No application found for this experience record'
            ], 404);
        }
        
        $application = $experience->application;
        
        // Update experience record
        $experience->update([
            'position' => $request->position,
            'school' => $request->school,
            'school_type' => $request->school_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        
        // ================================
        // 1. GET REQUIRED EXPERIENCE YEARS FROM QS
        // ================================
        $position = $application->position_applied;
        $levels = $application->levels;
        
        $requiredYears = 0;
        $qsConfig = config('qs');
        
        if ($levels && is_array($levels)) {
            foreach ($levels as $level) {
                if (isset($qsConfig[$level][$position]['experience_years'])) {
                    $requiredYears = (int) $qsConfig[$level][$position]['experience_years'];
                    break;
                }
            }
        }
        
        // ================================
        // 2. COMPUTE TOTAL EXPERIENCE YEARS
        // ================================
        $allExperiences = $application->experiences;
        $totalYears = 0;
        
        foreach ($allExperiences as $exp) {
            if ($exp->start_date) {
                $start = new \DateTime($exp->start_date);
                $end = $exp->end_date ? new \DateTime($exp->end_date) : new \DateTime();
                $diff = $start->diff($end);
                $years = $diff->y + ($diff->m / 12) + ($diff->d / 365);
                $totalYears += $years;
            }
        }
        
        // ================================
        // 3. COMPUTE POINTS (2 points per year increment?)
        // ================================
        $increment = max(0, floor($totalYears) - $requiredYears);
        $experiencePoints = min(10, $increment * 2);
        
        // Determine MET or NOT MET
        $experienceRemarks = ($totalYears >= $requiredYears && $requiredYears > 0) ? "MET" : "NOT MET";
        
        // ================================
        // 4. UPDATE SCORES
        // ================================
        $score = $application->scores;
        if ($score) {
            $score->experience_points = $experiencePoints;
            $score->experience_remarks = $experienceRemarks;
            
            // RECOMPUTE TOTAL SCORE
            $score->total_score = ($score->education_points ?? 0) + 
                                  ($score->training_points ?? 0) + 
                                  ($score->experience_points ?? 0) + 
                                  ($score->performance_points ?? 0) +
                                  ($score->coi_score ?? 0) +
                                  ($score->ncoi_score ?? 0) +
                                  ($score->bei_score ?? 0);
            $score->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Experience updated successfully!',
            'required_years' => $requiredYears,
            'total_years' => round($totalYears, 2),
            'increment' => $increment,
            'points' => $experiencePoints,
            'remarks' => $experienceRemarks,
            'total_score' => $score->total_score ?? 0
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating experience: ' . $e->getMessage()
        ], 500);
    }
}
}