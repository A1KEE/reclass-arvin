<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\ApplicationStatusMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessApplicationSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $application;
    public $ppstResult;

    public $tries = 5;      // retry 5x
    public $backoff = 10;   // 10 sec delay retry

    public function __construct($application, $ppstResult)
    {
        $this->application = $application;
        $this->ppstResult = $ppstResult;
    }

    public function handle()
    {
        $passwordToSend = null;

        // =========================
        // CREATE USER (IF QUALIFIED)
        // =========================
        if ($this->ppstResult === 'met') {

            $existingUser = User::where('email', $this->application->email)->first();

            if (!$existingUser) {

                $defaultPassword = Str::random(8);

                User::create([
                    'name' => $this->application->name,
                    'email' => $this->application->email,
                    'password' => Hash::make($defaultPassword),
                    'application_id' => $this->application->id,
                    'must_change_password' => 1
                ]);

                $passwordToSend = $defaultPassword;
            }
        }

        // =========================
        // SEND EMAIL
        // =========================
        Mail::to($this->application->email)
            ->send(new ApplicationStatusMail(
                $this->application,
                $this->ppstResult,
                $passwordToSend
            ));
    }
}