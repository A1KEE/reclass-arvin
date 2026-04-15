<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Models\Application;

class AdminFileController extends Controller
{
public function index()
{
    $basePath = storage_path('app/public/applications');

    $folders = collect(glob($basePath.'/*'))
    ->filter(fn($path) => is_dir($path))
    ->map(function ($folder) {

        $folderName = basename($folder);

        // ✅ GET ID FROM END OF FOLDER NAME
        preg_match('/(\d+)$/', $folderName, $matches);
        $appId = $matches[1] ?? null;

        $application = Application::find($appId);

        return [
            'folder' => $folderName,

            'files' => collect(glob($folder.'/*'))
                ->map(fn($file) => basename($file))
                ->toArray(),

            // ✅ SAFE CHECK
            'position' => $application ? $application->position_applied : 'Others'
        ];
    });

    return view('admin.files.index', compact('folders'));
}

   public function show($folder)
{
    $path = storage_path("app/public/applications/{$folder}");

    $files = collect(glob($path.'/**/*'))
        ->filter(fn($item) => is_file($item))
        ->map(function ($file) use ($folder) {

            return [
                'name' => basename($file),
                'url' => asset('storage/applications/'.$folder.'/'.str_replace(storage_path("app/public/applications/{$folder}/"), '', $file))
            ];
        });

    return view('admin.files.show', compact('folder', 'files'));
    }
    public function downloadZip($folder)
    {
        $path = storage_path("app/public/applications/{$folder}");

        if (!File::exists($path)) {
            abort(404);
        }

        $zipName = $folder . '.zip';
        $zipPath = storage_path("app/public/{$zipName}");

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            $files = File::allFiles($path);

            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function extractPosition($folderName)
{
    $positions = [
        'Teacher I',
        'Teacher II',
        'Teacher III',
        'Teacher IV',
        'Teacher V',
        'Teacher VI',
        'Teacher VII',
        'Master Teacher I',
        'Master Teacher II',
        'Master Teacher III',
    ];

    foreach ($positions as $pos) {
        if (stripos($folderName, $pos) !== false) {
            return $pos;
        }
    }

    return 'Others'; // fallback
}
}