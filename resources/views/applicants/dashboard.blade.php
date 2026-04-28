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
        $nextStep = 'Approved by admin';
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
            ✅ Your application passed the initial screening and is now approved for the next application process or classroom observation.
        </div>
        @endif

        <hr>

     <!-- APPLICATION DETAILS -->
<div class="application-details mt-3">

    <div class="detail-box">
        <div class="detail-label">👨‍🏫 Current Position</div>
        <div class="detail-value">{{ $app->current_position }}</div>
    </div>

    <div class="detail-box">
        <div class="detail-label">📌 Position Applied</div>
        <div class="detail-value">{{ $app->position_applied }}</div>
    </div>

    <div class="detail-box">
        <div class="detail-label">📍 School Assignment</div>
        <div class="detail-value">{{ $app->school_name }}</div>
    </div>

    <div class="detail-box">
        <div class="detail-label">📚 Teaching Level</div>
        <div class="detail-value">
            {{ is_array($app->levels) ? implode(', ', $app->levels) : ucfirst($app->levels) }}
        </div>
    </div>

</div>

    
    <!-- LAZADA STYLE TRACKER -->
<div class="mt-4">

    <div class="tracker-wrapper">

        <!-- BLUE LINE BACKGROUND -->
        <div class="tracker-line-bg"></div>

        <!-- ACTIVE BLUE LINE -->
        <div class="tracker-line-active"
             style="width: {{ $progress }}%;">
        </div>

        <!-- STEPS -->
        <div class="tracker-steps">

            @foreach($steps as $i => $step)

            @php
                $done = $i < $currentIndex;
                $active = $i == $currentIndex;
            @endphp

            <div class="tracker-step"
                 onclick="handleTrackerClick('{{ $step }}', '{{ $status }}', {{ $app->id }})">

                <!-- CIRCLE -->
                <div class="
                    tracker-circle
                    @if($done) done
                    @elseif($active) active
                    @endif
                ">

                    @if($done)
                        ✓
                    @else
                        {{ $i + 1 }}
                    @endif

                </div>

                <!-- LABEL -->
                <div class="tracker-label">
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