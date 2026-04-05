<?php

namespace App\Exports;

use App\Models\Application;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ApplicantsExport
{
    public function download($id)
    {
        $templatePath = storage_path('app/templates/template.xlsx');

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // 👉 SINGLE applicant
        $a = Application::findOrFail($id);

        // =========================
        // BASIC INFO
        // =========================
        $sheet->setCellValue('D13', $a->name);
        $sheet->setCellValue('D14', $a->position_applied);
        $sheet->setCellValue('D15', $a->school_name);

        $sheet->setCellValue('I13', $a->current_position);
        $sheet->setCellValue('I14', $a->item_number);
        $sheet->setCellValue('I15', $a->sg_annual_salary);

        // =========================
        // LEVEL CHECKS
        // =========================
        $levels = array_map(function($lvl){
    return strtolower(trim($lvl));
}, $a->levels ?? []);

if (in_array('kindergarten', $levels)) {
    $sheet->setCellValue('D17', '✔');
}

if (in_array('elementary', $levels)) {
    $sheet->setCellValue('D18', '✔');
}

if (in_array('junior_high', $levels)) {
    $sheet->setCellValue('G17', '✔');
}

if (in_array('senior_high', $levels)) {
    $sheet->setCellValue('G18', '✔');
}

// =========================
// QS OF POSITION
// =========================
$level = $a->levels[0] ?? null;
$position = $a->position_applied;

$qsPosition = config("qs.$level.$position") ?? [];

// =========================
// QS OF APPLICANT
// =========================
$hasEducation = $a->educations->count() > 0;
$hasTraining = $a->trainings->count() > 0;
$hasExperience = $a->experiences->count() > 0;
$hasEligibility = $a->eligibilities->count() > 0;

$sheet->setCellValue('D22', $qsPosition['education'] ?? '');
$sheet->setCellValue('D23', $qsPosition['training'] ?? '');
$sheet->setCellValue('D24', $qsPosition['experience'] ?? '');
$sheet->setCellValue('D25', $qsPosition['eligibility'] ?? '');
$sheet->getStyle('D22:D25')->getAlignment()->setWrapText(true);

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
}