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

        <div class="status-line">
            <span class="status-dot"></span>
            <span>Account status: Active</span>
        </div>
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

            <a href="/applicants/create"
               class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
               ➕ Create New Application
            </a>

            <a href="{{ route('change.password') }}"
               class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
               🔒 Change Password
            </a>

        </div>

    </div>

</div>

<div class="row g-3">
@foreach($applications->sortByDesc('created_at')->values() as $index => $app)

@php
    $steps = ['pending', 'evaluated', 'approved'];
    $status = strtolower($app->status);

    $currentIndex = array_search($status, $steps);
    if ($currentIndex === false) $currentIndex = 0;

   $nextStep = 'Awaiting Submission';

   if ($status == 'pending') {
    $nextStep = 'Evaluate by HR Personnel';
    } elseif ($status == 'evaluated') {
        $nextStep = 'For Admin Approval';
    } elseif ($status == 'approved') {
        $nextStep = 'Approved';
    }
@endphp

<div class="col-md-6">

    <div class="app-card">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-start">

            <div>
                <div class="fw-bold">
                    Application #{{ $index + 1 }} - {{ $app->name }}
                </div>

                <div class="small-text">✉ {{ $app->email }}</div>

                <div class="small-text mt-1">
                    🕒 Applied: {{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y - h:i A') }}
                </div>
            </div>

            <div class="text-end">

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

                <div class="mt-1 small-text">
                    📌 {{ $nextStep }}
                </div>

            </div>

        </div>

        <hr>

        <!-- DETAILS -->
        <div class="field">👨‍🏫 <strong>Current Position:</strong> {{ $app->current_position }}</div>
        <div class="field">📌 <strong>Position Applied:</strong> {{ $app->position_applied }}</div>
        <div class="field">📍 <strong>School:</strong> {{ $app->school_name }}</div>
        <div class="field">📊 <strong>Levels:</strong> {{ is_array($app->levels) ? implode(', ', $app->levels) : $app->levels }}</div>

        <!-- TRACKER -->
        <div class="lazada-tracker mt-4">

    <div class="tracker-line">
        <div class="tracker-progress"
             style="width: {{ ($currentIndex / 2) * 100 }}%">
        </div>
    </div>

    @foreach(['pending','evaluated','approved'] as $i => $step)

    @php
        $isDone = $i <= $currentIndex;
        $isCurrent = $i == $currentIndex;
    @endphp

    <div class="tracker-step"
        onclick="handleTrackerClick('{{ $step }}', '{{ $status }}', {{ $app->id }})"
        style="cursor:pointer;">

        <div class="circle
            @if($isDone) done
            @endif

            @if($isCurrent && $step == 'pending') pending
            @elseif($isCurrent && $step == 'evaluated') evaluated
            @elseif($isCurrent && $step == 'approved') approved
            @endif
        ">

            @if($isDone)
                ✔
            @endif

        </div>

        <div class="label text-capitalize">{{ $step }}</div>
    </div>

    @endforeach

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