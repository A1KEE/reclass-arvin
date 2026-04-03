<?php

namespace App\Http\Controllers;

use App\Models\Application;

class ApplicantDashboardController extends Controller
{
    public function index()
    {
        $applications = Application::where('email', auth()->user()->email)
            ->latest()
            ->get();

        return view('applicants.dashboard', compact('applications'));
    }
}