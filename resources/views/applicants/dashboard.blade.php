@php
    $applications = $applications ?? collect();

    // NEXT STEP LOGIC (GLOBAL)
    $nextStep = 'Awaiting Submission';

    if ($applications->count() > 0) {
        $latest = $applications->sortByDesc('created_at')->first();
        $status = strtolower($latest->status ?? 'pending');

        if ($status == 'pending') {
            $nextStep = 'Evaluate by HR Personnel';
        } elseif ($status == 'evaluated') {
            $nextStep = 'For Admin Approval';
        } elseif ($status == 'approved') {
            $nextStep = 'Final Processing';
        } elseif ($status == 'completed') {
            $nextStep = 'Completed';
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Applicant Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f0f4ff;
    }

    .top-bar {
        background: #0d1f5f;
        color: white;
        padding: 14px 20px;
    }

    .card-box {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .btn-primary {
        background-color: #0d1f5f;
        border-color: #0d1f5f;
    }

    .btn-primary:hover {
        background-color: #0a1845;
    }

    .top-info {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .pst-box {
        text-align: right;
        font-size: 12px;
        opacity: 0.9;
        line-height: 1.2;
    }

    /* GREEN DOT STATUS */
    .status-line {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        margin-top: 3px;
        opacity: 0.95;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #28a745;
        border-radius: 50%;
        box-shadow: 0 0 6px #28a745;
    }

    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .top-info {
            justify-content: flex-start;
        }

        .pst-box {
            text-align: left;
        }
    }
</style>

<!-- HEADER -->
<div class="top-bar d-flex justify-content-between align-items-center">

    <!-- LEFT -->
    <div>
        <h5 class="mb-0">Applicant Dashboard</h5>
        <small>Welcome, {{ auth()->user()->name }}</small>

        <!-- STATUS UNDER WELCOME -->
        <div class="status-line">
            <span class="status-dot"></span>
            <span>Account status: Active</span>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="top-info">

        <!-- DATE & TIME -->
        <div class="pst-box">
            <div>Philippine Standard Time</div>
            <div><strong id="pstDateTime"></strong></div>
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-light btn-sm">
                Logout
            </button>
        </form>

    </div>

</div>

<div class="container py-4">

    <style>
        .main-dashboard-card {
            border: none;
            border-radius: 18px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: 0.25s ease-in-out;
        }

        .main-dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(0,0,0,0.12);
        }

        .next-step-title {
            font-size: 13px;
            color: #6c757d;
        }

        .next-step-value {
            font-size: 18px;
            font-weight: 700;
        }

        .btn-soft {
            border-radius: 10px;
            padding: 8px 14px;
        }

        .divider {
            width: 1px;
            background: #e9ecef;
            margin: 0 20px;
        }

        @media (max-width: 768px) {
            .divider {
                display: none;
            }
        }
    </style>

    <div class="main-dashboard-card">

        <div class="row align-items-center">

            <!-- LEFT: NEXT STEP -->
            <div class="col-md-4">

                <div class="next-step-title">📌 Next Step</div>

                <div class="next-step-value text-warning">
                    {{ $nextStep }}
                </div>

                <small class="text-muted">
                    Track your application progress in real-time
                </small>

            </div>

            <!-- DIVIDER -->
            <div class="col-md-1 d-none d-md-flex justify-content-center">
                <div class="divider"></div>
            </div>

            <!-- RIGHT: ACTION BUTTONS -->
            <div class="col-md-7">

                <div class="d-flex flex-wrap gap-2 justify-content-md-end justify-content-start mt-3 mt-md-0">

                    <a href="/applicants/create" class="btn btn-primary btn-soft">
                        ➕ Fill Application
                    </a>

                    <a href="#" class="btn btn-outline-primary btn-soft">
                        👁 View Application
                    </a>

                    <a href="{{ route('change.password') }}" class="btn btn-outline-secondary btn-soft">
                        🔒 Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- APPLICATION LIST -->
    <div class="container mt-4">

        <h4 class="mb-4 fw-bold">📊 My Applications</h4>

        <style>
            .app-container {
                margin-bottom: 18px;
            }

            .app-card {
                border: none;
                border-radius: 16px;
                padding: 18px;
                transition: all 0.25s ease-in-out;
                background: #fff;
            }

            .app-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 28px rgba(0,0,0,0.12);
            }

            .small-text {
                font-size: 12px;
                color: #6c757d;
            }

            .field {
                margin-bottom: 6px;
            }

            .timeline {
                display: flex;
                justify-content: space-between;
                margin-top: 14px;
                position: relative;
                padding: 0 5px;
            }

            .timeline::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 2px;
                background: #e0e0e0;
                z-index: 0;
            }

            .step {
                background: #fff;
                z-index: 1;
                text-align: center;
                font-size: 11px;
                padding: 5px 8px;
                border-radius: 50px;
                border: 2px solid #ddd;
            }

            .step.active {
                border-color: #0d6efd;
                background: #0d6efd;
                color: #fff;
                font-weight: 600;
            }

            .badge-status {
                font-size: 11px;
                padding: 6px 10px;
                border-radius: 50px;
            }
        </style>

        @foreach($applications as $app)

            @php
                $steps = ['pending', 'evaluated', 'approved', 'completed'];
                $status = strtolower($app->status);
                $currentIndex = array_search($status, $steps);
                if ($currentIndex === false) $currentIndex = 0;
            @endphp

            <div class="app-container">

                <div class="app-card shadow-sm">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="fw-bold">{{ $app->name }}</div>
                            <div class="small-text">✉ {{ $app->email }}</div>
                            <div class="small-text mt-1">
                                🕒 Applied: {{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y - h:i A') }}
                            </div>
                        </div>

                        <span class="badge badge-status
                            @if($status == 'pending') bg-warning
                            @elseif($status == 'evaluated') bg-info
                            @elseif($status == 'approved') bg-success
                            @elseif($status == 'completed') bg-primary
                            @else bg-secondary
                            @endif
                        ">
                            {{ ucfirst($app->status) }}
                        </span>

                    </div>

                    <hr>

                    <!-- FIELDS -->
                    <div class="field">👨‍🏫 <strong>Current Position:</strong> {{ $app->current_position }}</div>
                    <div class="field">📌 <strong>Position Applied For:</strong> {{ $app->position_applied }}</div>
                    <div class="field">📍 <strong>School Name:</strong> {{ $app->school_name }}</div>
                    <div class="field">📊 <strong>Levels:</strong> {{ is_array($app->levels) ? implode(', ', $app->levels) : $app->levels }}</div>

                    <!-- TIMELINE -->
                    <div class="timeline mt-3">

                        @foreach(['Pending','Evaluated','Approved','Completed'] as $i => $step)

                            <div class="step {{ $i <= $currentIndex ? 'active' : '' }}">
                                ● {{ $step }}
                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>
<script>
  function updateDateTime() {
    const now = new Date();

    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: '2-digit',
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    };

    const formatted = now.toLocaleString('en-US', options);

    document.getElementById('pstDateTime').textContent = formatted;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>
</body>
</html>