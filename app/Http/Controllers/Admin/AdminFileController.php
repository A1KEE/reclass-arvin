<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\File;

class AdminFileController extends Controller
{
    public function index()
    {
        $basePath = storage_path('app/public/applications');

        $folders = collect(glob($basePath.'/*'))
            ->filter(fn($path) => is_dir($path))
            ->map(function ($folder) {
                return [
                    'folder' => basename($folder),
                    'files' => collect(glob($folder.'/*'))
                        ->map(fn($file) => basename($file))
                        ->toArray()
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
}