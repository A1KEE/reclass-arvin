<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\Experience;

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
            'scores',
            'ipcrfs'
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
public function updateTrainings(Request $request)
{
    try {
        $trainings = $request->trainings;
        $applicationId = $request->application_id;
        
        if (!$applicationId) {
            return response()->json(['success' => false, 'message' => 'Application ID is required'], 400);
        }
        
        $application = \App\Models\Application::findOrFail($applicationId);
        
        // Update each training
        foreach ($trainings as $trainingData) {
            if (isset($trainingData['id']) && $trainingData['id']) {
                $training = \App\Models\Training::find($trainingData['id']);
                if ($training) {
                    $training->update([
                        'title' => $trainingData['title'],
                        'type' => $trainingData['type'],
                        'hours' => $trainingData['hours'] ?? 0,
                        'start_date' => $trainingData['start_date'],
                        'end_date' => $trainingData['end_date'],
                    ]);
                }
            }
        }
        
        // ✅ REFRESH APPLICATION PARA MAKUHA ANG UPDATED TRAININGS
        $application = $application->fresh();
        
        // Get required hours
        $position = $application->position_applied;
        $school = $application->school;
        $level = $school ? strtolower($school->level_type) : 'elementary';
        
        $requiredHours = 0;
        $qsConfig = config('qs');
        if (isset($qsConfig[$level][$position]['training_hours'])) {
            $requiredHours = (int) $qsConfig[$level][$position]['training_hours'];
        }
        
        // Non-teaching keywords
        $nonTeachingKeywords = [
            "administrative", "accounting", "finance", "management",
            "ict", "computer", "leadership", "seminar", "orientation", "workshop"
        ];
        
        $isTeachingRelevant = function($title) use ($nonTeachingKeywords) {
            if (empty($title)) return false;
            $title = strtolower($title);
            foreach ($nonTeachingKeywords as $kw) {
                if (strpos($title, $kw) !== false) return false;
            }
            return true;
        };
        
        // Level mapping
        $getTrainingLevel = function($hours) {
            if ($hours >= 240) return 31;
            if ($hours >= 232) return 30;
            if ($hours >= 224) return 29;
            if ($hours >= 216) return 28;
            if ($hours >= 208) return 27;
            if ($hours >= 200) return 26;
            if ($hours >= 192) return 25;
            if ($hours >= 184) return 24;
            if ($hours >= 176) return 23;
            if ($hours >= 168) return 22;
            if ($hours >= 160) return 21;
            if ($hours >= 152) return 20;
            if ($hours >= 144) return 19;
            if ($hours >= 136) return 18;
            if ($hours >= 128) return 17;
            if ($hours >= 120) return 16;
            if ($hours >= 112) return 15;
            if ($hours >= 104) return 14;
            if ($hours >= 96) return 13;
            if ($hours >= 88) return 12;
            if ($hours >= 80) return 11;
            if ($hours >= 72) return 10;
            if ($hours >= 64) return 9;
            if ($hours >= 56) return 8;
            if ($hours >= 48) return 7;
            if ($hours >= 40) return 6;
            if ($hours >= 32) return 5;
            if ($hours >= 24) return 4;
            if ($hours >= 16) return 3;
            if ($hours >= 8) return 2;
            return 1;
        };
        
        // ✅ COMPUTE TOTAL TEACHING HOURS (GAMIT ANG UPDATED TRAININGS)
        $allTrainings = $application->trainings;
        $totalTeachingHours = 0;
        
        foreach ($allTrainings as $t) {
            if ($isTeachingRelevant($t->title) && $t->hours > 0) {
                $totalTeachingHours += $t->hours;
            }
        }
        
        // Compute points
        $applicantLevel = $getTrainingLevel($totalTeachingHours);
        $requiredLevel = $getTrainingLevel($requiredHours);
        $increment = max(0, $applicantLevel - $requiredLevel);
        
        $trainingPoints = 0;
        if ($increment >= 10) $trainingPoints = 10;
        elseif ($increment >= 8) $trainingPoints = 8;
        elseif ($increment >= 6) $trainingPoints = 6;
        elseif ($increment >= 4) $trainingPoints = 4;
        elseif ($increment >= 2) $trainingPoints = 2;
        
        $trainingRemarks = ($totalTeachingHours >= $requiredHours && $requiredHours > 0) ? "MET" : "NOT MET";
        
        // Update scores
        $score = $application->scores;
        if ($score) {
            $score->training_points = $trainingPoints;
            $score->training_remarks = $trainingRemarks;
            $score->save();
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_hours' => $totalTeachingHours,
                'points' => $trainingPoints,
                'remarks' => $trainingRemarks
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Update Trainings Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
public function updateExperiences(Request $request)
{
    try {
        \Log::info('Update Experiences Called', $request->all()); // Debug log
        
        $experiences = $request->experiences;
        $applicationId = $request->application_id;
        
        if (!$applicationId && isset($experiences[0]['application_id'])) {
            $applicationId = $experiences[0]['application_id'];
        }
        
        // Update each experience
        foreach ($experiences as $expData) {
            if (isset($expData['id']) && $expData['id']) {
                $experience = Experience::find($expData['id']);  // Dapat gumana na ito
                if ($experience) {
                    $experience->update([
                        'position' => $expData['position'],
                        'school' => $expData['school'],
                        'school_type' => $expData['school_type'],
                        'start_date' => $expData['start_date'],
                        'end_date' => $expData['end_date'],
                    ]);
                }
            }
        }
        
        // ==========================
        // RECOMPUTE SCORES (OPTIONAL)
        // ==========================
        if ($applicationId) {
            $this->recomputeExperienceScores($applicationId);
        }
        
        return response()->json(['success' => true]);
        
    } catch (\Exception $e) {
        \Log::error('Update Experience Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage()
        ], 500);
    }
}

// Optional: Function to recompute scores
private function recomputeExperienceScores($applicationId)
{
    try {
        $experiences = Experience::where('application_id', $applicationId)->get();
        
        $totalYears = 0;
        foreach ($experiences as $exp) {
            if ($exp->start_date) {
                $start = new \DateTime($exp->start_date);
                $end = $exp->end_date ? new \DateTime($exp->end_date) : new \DateTime();
                
                if ($end >= $start) {
                    $diff = $start->diff($end);
                    $yearsDecimal = $diff->y + ($diff->m / 12) + ($diff->d / 365);
                    $totalYears += $yearsDecimal;
                }
            }
        }
        
        $totalYears = round($totalYears, 2);
        
        // Get required years from application's position and school
        $application = Application::find($applicationId);
        $position = $application->position_applied;
        
        // Get school level - adjust as needed
        $level = 'elementary'; // Default, adjust based on your logic
        
        $requiredYears = 0;
        $qsConfig = config('qs');
        
        if ($qsConfig && isset($qsConfig[$level][$position])) {
            $requiredYears = floatval($qsConfig[$level][$position]['experience_years'] ?? 0);
        }
        
        $actualLevel = $this->getLevelFromYears($totalYears);
        $requiredLevel = $this->getLevelFromYears($requiredYears);
        $points = $this->calculateExperiencePoints($actualLevel, $requiredLevel);
        $remarks = $totalYears >= $requiredYears ? 'MET' : 'NOT MET';
        
        // Update scores
        $scores = \DB::table('application_scores')->where('application_id', $applicationId)->first();
        
        if ($scores) {
            $totalScore = ($scores->education_points ?? 0) + 
                         ($scores->training_points ?? 0) + 
                         $points + 
                         ($scores->performance_points ?? 0);
            
            \DB::table('application_scores')
                ->where('application_id', $applicationId)
                ->update([
                    'experience_points' => $points,
                    'experience_remarks' => $remarks,
                    'total_score' => $totalScore,
                    'updated_at' => now(),
                ]);
        }
        
        return true;
        
    } catch (\Exception $e) {
        \Log::error('Recompute Scores Error: ' . $e->getMessage());
        return false;
    }
}

private function getLevelFromYears($years)
{
    $experienceLevels = [
        ['level' => 1, 'from' => 0, 'to' => 0.5],
        ['level' => 2, 'from' => 0.5, 'to' => 1],
        ['level' => 3, 'from' => 1, 'to' => 1.5],
        ['level' => 4, 'from' => 1.5, 'to' => 2],
        ['level' => 5, 'from' => 2, 'to' => 2.5],
        ['level' => 6, 'from' => 2.5, 'to' => 3],
        ['level' => 7, 'from' => 3, 'to' => 3.5],
        ['level' => 8, 'from' => 3.5, 'to' => 4],
        ['level' => 9, 'from' => 4, 'to' => 4.5],
        ['level' => 10, 'from' => 4.5, 'to' => 5],
        ['level' => 11, 'from' => 5, 'to' => 5.5],
        ['level' => 12, 'from' => 5.5, 'to' => 6],
        ['level' => 13, 'from' => 6, 'to' => 6.5],
        ['level' => 14, 'from' => 6.5, 'to' => 7],
        ['level' => 15, 'from' => 7, 'to' => 7.5],
        ['level' => 16, 'from' => 7.5, 'to' => 8],
        ['level' => 17, 'from' => 8, 'to' => 8.5],
        ['level' => 18, 'from' => 8.5, 'to' => 9],
        ['level' => 19, 'from' => 9, 'to' => 9.5],
        ['level' => 20, 'from' => 9.5, 'to' => 10],
        ['level' => 21, 'from' => 10, 'to' => 10.5],
        ['level' => 22, 'from' => 10.5, 'to' => 11],
        ['level' => 23, 'from' => 11, 'to' => 11.5],
        ['level' => 24, 'from' => 11.5, 'to' => 12],
        ['level' => 25, 'from' => 12, 'to' => 12.5],
        ['level' => 26, 'from' => 12.5, 'to' => 13],
        ['level' => 27, 'from' => 13, 'to' => 13.5],
        ['level' => 28, 'from' => 13.5, 'to' => 14],
        ['level' => 29, 'from' => 14, 'to' => 14.5],
        ['level' => 30, 'from' => 14.5, 'to' => 15],
        ['level' => 31, 'from' => 15, 'to' => 999]
    ];
    
    $years = floatval($years);
    if ($years < 0) return 1;
    
    foreach ($experienceLevels as $level) {
        if ($years >= $level['from'] && $years < $level['to']) {
            return $level['level'];
        }
    }
    
    return 31;
}

private function calculateExperiencePoints($actualLevel, $requiredLevel)
{
    if ($actualLevel <= $requiredLevel) {
        return 0;
    }
    
    $diff = $actualLevel - $requiredLevel;
    
    if ($diff >= 10) return 10;
    if ($diff >= 8) return 8;
    if ($diff >= 6) return 6;
    if ($diff >= 4) return 4;
    if ($diff >= 2) return 2;
    
    return 0;
}
private function getSchoolLevel($schoolName)
{
    // Adjust this based on how you determine school level
    // This is just an example - modify according to your logic
    $school = School::where('name', $schoolName)->first();
    if ($school) {
        return strtolower($school->level ?? 'elementary');
    }
    return 'elementary';
}
public function updateEligibility(Request $request, $id)
{
    try {
        $eligibility = \App\Models\Eligibility::with('application')->find($id);
        
        if (!$eligibility) {
            return response()->json([
                'success' => false,
                'message' => 'Eligibility record not found'
            ], 404);
        }
        
        if (!$eligibility->application) {
            return response()->json([
                'success' => false,
                'message' => 'No application found'
            ], 404);
        }
        
        $application = $eligibility->application;
        
        // Update eligibility record
        $eligibility->update([
            'eligibility_name' => $request->eligibility_name,
            'expiry_date' => $request->expiry_date,
        ]);
        
        // Use the MANUAL status from dropdown (QS Editor's evaluation)
        $eligibilityRemarks = $request->eligibility_remarks;
        
        // Update scores
        $score = $application->scores;
        if ($score) {
            $score->eligibility_remarks = $eligibilityRemarks;
            
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
            'message' => 'Eligibility updated successfully!',
            'remarks' => $eligibilityRemarks,
            'total_score' => $score->total_score ?? 0
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating eligibility: ' . $e->getMessage()
        ], 500);
    }
}
}