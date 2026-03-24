<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'total' => Application::count(),
            'submitted' => Application::where('status', 'submitted')->count(),
            'draft' => Application::where('status', 'draft')->count(),
        ]);
    }

    public function applicants()
    {
        $applicants = Application::latest()->get();
        return view('admin.applicants', compact('applicants'));
    }

    public function show($id)
    {
        $applicant = Application::findOrFail($id);
        return view('admin.view', compact('applicant'));
    }
    public function settings()
    {
        return view('admin.settings');
    }
}