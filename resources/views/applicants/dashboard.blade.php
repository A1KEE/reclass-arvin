    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>

    <link rel="icon" href="{{ asset('images/DO-LOGO.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-applicants.css') }}?v={{ time() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>

    <!-- HEADER -->
    <div class="top-bar d-flex justify-content-between align-items-center flex-wrap">

        <div>
            <h5 class="mb-0">Applicant Dashboard</h5>
            <small>Welcome, {{ auth()->user()->name }}</small>
        </div>

        <div class="top-info">

            <div class="pst-box">
                <div>Philippine Standard Time</div>
                <div><strong id="pstDateTime"></strong></div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-light btn-sm">Logout</button>
            </form>

        </div>
    </div>

    <div class="mb-4">

    <div class="p-3 rounded-4 shadow-sm bg-white d-flex justify-content-between align-items-center flex-wrap">

        <!-- LEFT -->
        <div>
            <h5 class="fw-bold mb-1">📊 My Applications</h5>
            <small class="text-muted d-flex align-items-center gap-2" style="font-size:12px;">
                <span>Track your application progress in real time</span>

                <span class="refresh-box">
                    ⏳ Refreshing in 
                    <span id="refreshCounter" class="refresh-number">25</span>s
                </span>
            </small>
        </div>

        <!-- RIGHT BUTTONS -->
        <div class="d-flex gap-2 mt-2 mt-md-0">

            <a href="{{ route('change.password') }}"
               class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                🔒 Change Password
            </a>

            <div class="status-line">
                <span class="status-dot"></span>
                <span>Account status: Active</span>
            </div>

        </div>

    </div>

</div>

<!-- FULL WIDTH CONTAINER -->
<div class="container-fluid px-3">

<div class="row g-4">

@foreach($applications->sortByDesc('created_at')->values() as $index => $app)

@php
    $steps = ['pending', 'evaluated', 'approved'];
    $status = strtolower($app->status);

    $currentIndex = array_search($status, $steps);
    if ($currentIndex === false) $currentIndex = 0;

    $progress = ($currentIndex / (count($steps) - 1)) * 100;

    if ($status == 'pending') {
        $nextStep = 'Evaluate by HR Personnel';
    } elseif ($status == 'evaluated') {
        $nextStep = 'For Admin Approval';
    } elseif ($status == 'approved') {
        $nextStep = 'Approved';
    } else {
        $nextStep = 'Awaiting Submission';
    }
@endphp

<!-- CARD -->
<div class="col-12">

    <div class="app-card shadow-sm p-3
        @if($status === 'approved') border border-success @endif
    ">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-start flex-wrap">

            <div>
                <div class="fw-bold fs-6">
                    Application ID: {{ $app->id }}
                </div>

                <div class="text-muted small">✉ {{ $app->email }}</div>

                <div class="text-muted small">
                    🕒 {{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y - h:i A') }}
                </div>

                <!-- APPROVED BADGE -->
                @if($status === 'approved')
                    <span class="badge bg-success mt-2">
                        🎉 Ready to View
                    </span>
                @endif
            </div>

            <div class="text-end">

                <span class="badge
                    @if($status == 'pending') bg-warning
                    @elseif($status == 'evaluated') bg-info
                    @elseif($status == 'approved') bg-success
                    @else bg-secondary
                    @endif
                ">
                    {{ ucfirst($status) }}
                </span>

                <div class="small text-muted mt-1">
                    📌 {{ $nextStep }}
                </div>

                <!-- VIEW BUTTON -->
                <div class="mt-2">
                    @if($status === 'approved')
                        <a href="{{ url('/applicant/application/'.$app->id) }}"
                           class="btn btn-success btn-sm">
                            👁 View Application
                        </a>
                    @else
                        <button class="btn btn-outline-secondary btn-sm" disabled>
                            🔒 Not Available Yet
                        </button>
                    @endif
                </div>

            </div>

        </div>

        <!-- ALERT -->
        @if($status === 'approved')
        <div class="alert alert-success mt-3 mb-0 py-2">
            ✅ Your application is approved. You can now view full details.
        </div>
        @endif

        <hr>

        <!-- DETAILS -->
        <div class="row small text-muted">

            <div class="col-md-3">👨‍🏫 <strong>Current:</strong> {{ $app->current_position }}</div>
            <div class="col-md-3">📌 <strong>Applied:</strong> {{ $app->position_applied }}</div>
            <div class="col-md-3">📍 <strong>School:</strong> {{ $app->school_name }}</div>
            <div class="col-md-3">
                📊 <strong>Levels:</strong>
                {{ is_array($app->levels) ? implode(', ', $app->levels) : $app->levels }}
            </div>

        </div>

        <!-- LAZADA TRACKER -->
        <div class="mt-4">

            <!-- PROGRESS BAR -->
            <div style="height:6px; background:#e9ecef; border-radius:20px;">
                <div style="
                    height:6px;
                    width: {{ $progress }}%;
                    background:#28a745;
                    border-radius:20px;
                    transition:0.3s ease;
                "></div>
            </div>

            <!-- STEPS -->
            <div class="d-flex justify-content-between text-center mt-3">

                @foreach($steps as $i => $step)

                @php
                    $done = $i < $currentIndex;
                    $active = $i == $currentIndex;
                @endphp

                <div class="flex-fill"
                     onclick="handleTrackerClick('{{ $step }}', '{{ $status }}', {{ $app->id }})"
                     style="cursor:pointer;">

                    <!-- CIRCLE -->
                    <div style="
                        width:34px;
                        height:34px;
                        margin:auto;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:13px;
                        font-weight:bold;

                        @if($done)
                            background:#28a745;
                            color:white;
                        @elseif($active)
                            background:#ffc107;
                            color:black;
                        @else
                            background:#ddd;
                            color:#666;
                        @endif
                    ">
                        @if($done) ✔ @else {{ $i + 1 }} @endif
                    </div>

                    <div class="mt-1 small fw-semibold">
                        {{ ucfirst($step) }}
                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

</div>

@endforeach

</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function handleTrackerClick(step, status, id) {

        if (status === 'approved') {
            window.location.href = '/applicant/application/' + id;
            return;
        }
        if (status === 'pending') {
            Swal.fire({
                icon: 'info',
                title: 'Application Pending',
                text: 'Your application is under evaluation by HRMO.',
            });
        }
        if (status === 'evaluated') {
            Swal.fire({
                icon: 'info',
                title: 'For Approval',
                text: 'Your application is now for Admin approval.',
            });
        }
    }
    </script>
    <!-- SCRIPT -->
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

        document.getElementById('pstDateTime').textContent =
            now.toLocaleString('en-US', options);
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
    </script>
    <script>
    let seconds = 25;

    function startCountdown() {
        const counter = document.getElementById('refreshCounter');

        const interval = setInterval(() => {
            seconds--;

            if (counter) {
                counter.textContent = seconds;

                // 🔥 pulse animation every tick
                counter.classList.add('pulse');
                setTimeout(() => counter.classList.remove('pulse'), 300);
            }

            // 🔥 change text when near refresh
            if (seconds === 3) {
                counter.parentElement.innerHTML = "⚡ Refreshing in <span id='refreshCounter' class='refresh-number'>3</span>s";
            }

            if (seconds <= 0) {
                clearInterval(interval);
                location.reload();
            }

        }, 1000);
    }

    startCountdown();
    </script>
    </body>
    </html>