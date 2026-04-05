<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/DO-LOGO.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="brand">
        <img src="{{ asset('images/DO-LOGO.png') }}" alt="DO LOGO">
        <span>Reclassification</span>
    </div>

    <!-- MENU -->
    <div class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.applicants') }}">
            <i class="bi bi-people"></i> <span>Applicants</span>
        </a>

        <a href="{{ route('admin.files.index') }}">
             <i class="bi bi-folder"></i> <span>Application Files</span>
        </a>

        @role('super_admin')
        <a href="{{ route('admin.ranking') }}">
            <i class="bi bi-trophy"></i> <span>Ranking</span>
        </a>
        @endrole

         @role('super_admin')
        <a href="{{ route('admin.users') }}">
            <i class="bi bi-person-badge"></i> <span>Users</span>
        </a>
        @endrole

        <!-- DO NOT CHANGE THIS LINE (AS REQUESTED) -->
        <hr style="border-color:#444;">

        <a href="{{ route('admin.settings') }}">
            <i class="bi bi-gear"></i> <span>Settings</span>
        </a>
    </div>

    <!-- FOOTER -->
<div class="sidebar-footer">

    @php
    $name = auth()->user()->name ?? 'Admin';
    $words = explode(' ', $name);
    $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
@endphp

<div class="user-card sidebar-user d-flex align-items-center gap-2">

    <!-- AVATAR -->
    <div class="avatar-circle">
        {{ $initials }}
    </div>

    <!-- USER INFO -->
    <div class="user-text">
        <div class="admin-name">
            {{ $name }}
        </div>
        <small class="admin-role">
    {{ auth()->user()->hasRole('super_admin') ? 'Approver' : (auth()->user()->hasRole('admin') ? 'HRMO / Evaluator' : 'Administrator') }}
</small>
    </div>

</div>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="btn btn-sm btn-danger btn-block mt-2">
        <i class="bi bi-box-arrow-right"></i>
        <span class="logout-text">Logout</span>
    </button>
</form>
    </div>
</div>
</div>

<!-- CONTENT -->
<div class="content" id="content">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <!-- LEFT -->
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-dark" id="toggleSidebar">
                <i class="bi bi-list"></i>
            </button>

            <h6 class="mb-0 fw-bold" id="pageTitle">
                @yield('page-title', 'Dashboard')
            </h6>
        </div>

       <!-- RIGHT -->
<div class="d-flex align-items-center">

    <!-- DATE -->
    <div class="text-right small mr-3">
        <div>Philippine Standard Time:</div>
        <div><strong id="pstDateTime"></strong></div>
    </div>

    <!-- 🔔 BELL -->
    <div class="position-relative">
        <button class="btn p-2 notif-btn" id="notifBell">
            <i class="bi bi-bell"></i>

            @if(isset($newPendingCount) && $newPendingCount > 0)
                <span class="badge badge-danger position-absolute"
                      style="top:0; right:0;">
                    {{ $newPendingCount }}
                </span>
            @endif
        </button>
    </div>

</div>

    </div> <!-- ✅ CLOSED TOPBAR -->

    <!-- PAGE CONTENT -->
    <div class="mt-3">
        @yield('content')
    </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>

<script>
const toggleBtn = document.getElementById('toggleSidebar');
const body = document.body;
const sidebar = document.getElementById('sidebar');

toggleBtn.addEventListener('click', function () {
    body.classList.toggle('sidebar-collapsed');
    sidebar.classList.toggle('show');

    setTimeout(() => {
        if (typeof table !== "undefined") {
            table.columns.adjust();
        }
        window.dispatchEvent(new Event('resize'));
    }, 300);
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let theme = localStorage.getItem('theme') || 'light';
    if(theme === 'dark'){
        document.body.classList.add('dark-mode');
    }
});
</script>
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
<script>
document.getElementById('notifBell').addEventListener('click', function () {
    $('#notifModal').modal('show');
});

// ✅ MARK AS READ (FIXED AJAX)
function markAsRead(id) {
    fetch('/superadmin/notifications/read/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {

            // remove notif item
            const el = document.getElementById('notif-' + id);
            if (el) el.remove();

            // optional: reload to update badge count
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error marking as read:', error);
    });
}
function markAllAsRead() {
    fetch('/superadmin/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => console.error(err));
}
</script>

<div class="modal fade" id="notifModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-right">
    <div class="modal-content">

      <div class="modal-header">
        <h6 class="modal-title">🔔 New Applications</h6>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

     {{-- BODY --}}
<div class="modal-body notif-body">

    @forelse($newPending as $app)
        <div id="notif-{{ $app->id }}" class="border-bottom mb-2 pb-2">

            <div class="d-flex justify-content-between align-items-start">

                <!-- LEFT -->
                <div>
                    <strong>
                        {{ $app->name }} ({{ $app->position_applied }})
                    </strong><br>

                    <small class="text-muted">
                        Applied: {{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y h:i A') }}
                    </small>
                </div>

                <!-- RIGHT -->
                <div>
                    <button onclick="markAsRead({{ $app->id }})"
                            class="btn btn-sm btn-light">
                        ✔
                    </button>
                </div>

            </div>

        </div>
    @empty
        <p class="text-muted text-center">No new applications</p>
    @endforelse

</div>

{{-- ✅ FOOTER --}}
@if($newPendingCount > 0)
<div class="notif-footer d-flex">

    <!-- VIEW ALL -->
    <a href="{{ route('admin.applicants') }}"
       class="btn btn-sm btn-outline-secondary w-50 mr-1">
        View All
    </a>

    <!-- MARK ALL -->
    <button onclick="markAllAsRead()"
            class="btn btn-sm btn-primary w-50 ml-1">
        Mark all
    </button>

</div>
@endif
    </div>
  </div>
</div>
@stack('scripts')

</body>
</html>