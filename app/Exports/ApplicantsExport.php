<?php

namespace App\Exports;

use App\Models\Application;
use App\Models\PpstIndicator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ApplicantsExport
{
    public function download($id)
    {
        // =========================
        // LOAD APPLICATION
        // =========================
        $a = Application::with([
            'educations',
            'trainings',
            'experiences',
            'eligibilities',
            'scores',
            'ppstRatings'
        ])->findOrFail($id);

        $score = $a->scores;

        // KEY RATINGS
        $ratings = $a->ppstRatings->keyBy('ppst_indicator_id');

        // =========================
        // DETERMINE POSITION LEVEL
        // =========================
        $appliedPosition = trim($a->position_applied);

        $positionLevel = 'Teacher I – MT I';
        $templateType  = 'RFTP (Teacher I–VII–MT I)';

        if (in_array($appliedPosition, ['Master Teacher II', 'Master Teacher III'])) {
            $positionLevel = 'Master Teacher II–III';
            $templateType  = 'RFTP (MT II–III)';
        }

        elseif (in_array($appliedPosition, ['Master Teacher IV', 'Master Teacher V'])) {
            $positionLevel = 'Master Teacher IV–V';
            $templateType  = 'RFTP (MT IV–V)';
        }

        // =========================
        // TEMPLATE CONFIG
        // =========================
        $templateConfig = [
            'Teacher I – MT I' => [
                'file' => 'RFTP_TI_MTI.xlsx',
                'startRow' => 45,
            ],
            'Master Teacher II–III' => [
                'file' => 'RFTP_MTII.xlsx',
                'startRow' => 45, // adjust if needed
            ],
            'Master Teacher IV–V' => [
                'file' => 'RFTP_MTIV_V.xlsx',
                'startRow' => 45, // optional
            ],
        ];

        $config = $templateConfig[$positionLevel] ?? $templateConfig['Teacher I – MT I'];

        $templatePath = storage_path('app/templates/' . $config['file']);

        if (!file_exists($templatePath)) {
            abort(500, 'Template file not found: ' . $config['file']);
        }

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $startRow = $config['startRow'];

        // =========================
        // BASIC INFO
        // =========================
        $sheet->setCellValue('D13', $a->name);
        $sheet->setCellValue('D14', $a->position_applied);
        $sheet->setCellValue('D15', $a->school_name);

        $sheet->setCellValue('I13', $a->current_position);
        $sheet->setCellValue('I14', $a->item_number);
        $sheet->setCellValue('I15', $a->sg_annual_salary);

        $sheet->setCellValue('B100', $a->name);

        // =========================
        // SG MAP
        // =========================
        $sgMap = [
            "Teacher I" => 11,
            "Teacher II" => 12,
            "Teacher III" => 13,
            "Teacher IV" => 14,
            "Teacher V" => 15,
            "Teacher VI" => 16,
            "Teacher VII" => 17,
            "Master Teacher I" => 18,
            "Master Teacher II" => 19,
            "Master Teacher III" => 20,
            "Master Teacher IV" => 21,
            "Master Teacher V" => 22,
        ];

        $currentPosition = trim($a->current_position);

        $currentSG = $sgMap[$currentPosition] ?? 0;
        $appliedSG = $sgMap[$appliedPosition] ?? 0;

        $sheet->setCellValue('C106', $currentPosition);
        $sheet->setCellValue('E106', $currentSG);

        $sheet->setCellValue('F106', $appliedPosition);
        $sheet->setCellValue('G106', $appliedSG);

        // =========================
        // LEVEL CHECKS
        // =========================
        $levels = array_map(fn($lvl) => strtolower(trim($lvl)), $a->levels ?? []);

        if (in_array('kindergarten', $levels)) $sheet->setCellValue('D17', '✔');
        if (in_array('elementary', $levels)) $sheet->setCellValue('D18', '✔');
        if (in_array('junior_high', $levels)) $sheet->setCellValue('G17', '✔');
        if (in_array('senior_high', $levels)) $sheet->setCellValue('G18', '✔');

        // =========================
        // TITLE
        // =========================
      $level = is_array($a->levels) ? ($a->levels[0] ?? '') : $a->levels;

        $title = 'For ' . $appliedPosition;

        if (!empty($level)) {
            $title .= ' (' . ucfirst($level) . ')';
        }

        $sheet->setCellValue('H2', $title);

        // =========================
        // PPST INDICATORS
        // =========================
        $indicators = PpstIndicator::whereRaw('TRIM(position_level) = ?', [$positionLevel])
            ->orderBy('domain')
            ->orderBy('order')
            ->get();

        foreach ($indicators as $i => $indicator) {

            $row = $startRow + $i;

            $rating = $ratings[$indicator->id]->rating ?? null;

            // clear
            $sheet->setCellValue('H' . $row, '');
            $sheet->setCellValue('I' . $row, '');
            $sheet->setCellValue('J' . $row, '');

            if ($rating === 'O') {
                $sheet->setCellValue('H' . $row, '✔');
            }

            if ($rating === 'VS') {
                $sheet->setCellValue('I' . $row, '✔');
            }

            if ($rating === 'S') {
                $sheet->setCellValue('J' . $row, '✔');
            }
        }

        // =========================
        // QS
        // =========================
        $qsPosition = config("qs.$level.$appliedPosition") ?? [];

        $sheet->setCellValue('D22', $qsPosition['education'] ?? '');
        $sheet->setCellValue('D23', $qsPosition['training'] ?? '');
        $sheet->setCellValue('D24', $qsPosition['experience'] ?? '');
        $sheet->setCellValue('D25', $qsPosition['eligibility'] ?? '');

        $sheet->getStyle('D22:D25')->getAlignment()->setWrapText(true);

        // =========================
        // SUMMARY
        // =========================
        $sheet->setCellValue('G22', $this->formatEducation($a));
        $sheet->setCellValue('G23', $this->formatTraining($a));
        $sheet->setCellValue('G24', $this->formatExperience($a));
        $sheet->setCellValue('G25', $this->formatEligibility($a));

        $sheet->getStyle('G22:G25')->getAlignment()->setWrapText(true);

        foreach (range(22, 25) as $r) {
            $sheet->getRowDimension($r)->setRowHeight(-1);
        }

        // =========================
        // REMARKS
        // =========================
        $sheet->setCellValue('I22', $score->education_remarks ?? 'N/A');
        $sheet->setCellValue('I23', $score->training_remarks ?? 'N/A');
        $sheet->setCellValue('I24', $score->experience_remarks ?? 'N/A');
        $sheet->setCellValue('I25', $score->eligibility_remarks ?? 'N/A');

        // =========================
        // PPST SUMMARY
        // =========================
        $sheet->setCellValue('H88', $score->coi_outstanding ?? 0);
        $sheet->setCellValue('H89', $score->coi_very_satisfactory ?? 0);
        $sheet->setCellValue('H90', $score->ncoi_outstanding ?? 0);
        $sheet->setCellValue('H91', $score->ncoi_very_satisfactory ?? 0);
        $sheet->setCellValue('H92', strtoupper($score->final_result ?? 'N/A'));

        // =========================
        // SCORES
        // =========================
        $sheet->setCellValue('C96', $score->education_points ?? 0);
        $sheet->setCellValue('D96', $score->training_points ?? 0);
        $sheet->setCellValue('E96', $score->experience_points ?? 0);
        $sheet->setCellValue('F96', $score->performance_points ?? 0);

        $sheet->setCellValue('G96', $score->coi_score ?? 0);
        $sheet->setCellValue('H96', $score->ncoi_score ?? 0);
        $sheet->setCellValue('I96', $score->bei_score ?? 0);
        $sheet->setCellValue('J96', $score->total_score ?? 0);

        // =========================
        // DOWNLOAD
        // =========================
        $filename = 'Applicant_' . $a->name . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    // =========================
    // FORMATTERS
    // =========================
    private function formatEducation($a)
    {
        if (!$a->educations->count()) return 'No Education Added.';

        $text = '';
        foreach ($a->educations as $edu) {
            $text .= $edu->degree . "\n";
            $text .= $edu->school . "\n";
            $text .= ($edu->date_graduated ?? '-') . ' | Units: ' . ($edu->units ?? '-') . "\n\n";
        }
        return trim($text);
    }

    private function formatTraining($a)
    {
        if (!$a->trainings->count()) return 'No Training Added.';

        $text = '';
        foreach ($a->trainings as $t) {
            $text .= ($t->title ?? 'Training') . "\n";
            $text .= ($t->type ?? '-') . "\n";
            $text .= ($t->hours ?? 0) . ' hrs | ' . ($t->start_date ?? '') . ' - ' . ($t->end_date ?? '') . "\n\n";
        }
        return trim($text);
    }

    private function formatExperience($a)
    {
        if (!$a->experiences->count()) return 'No Experience Added.';

        $text = '';
        foreach ($a->experiences as $exp) {
            $text .= $exp->position . "\n";
            $text .= $exp->school . ' (' . $exp->school_type . ')' . "\n";
            $text .= $exp->start_date . ' - ' . ($exp->end_date ?? 'Present') . "\n\n";
        }
        return trim($text);
    }

    private function formatEligibility($a)
    {
        if (!$a->eligibilities->count()) return 'No Eligibility Added.';

        $text = '';
        foreach ($a->eligibilities as $el) {
            $text .= ($el->eligibility_name ?? 'Eligibility') . "\n";
            $text .= 'Expiry: ' . ($el->expiry_date ?? '-') . "\n\n";
        }
        return trim($text);
    }
}