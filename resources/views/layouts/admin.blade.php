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

    <style>
        body {
            background: #f4f6f9;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            background: #1e1e2d;
            color: #fff;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: #cfcfcf;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #343a40;
            color: #fff;
        }

        /* BRAND */
        .sidebar .brand {
            font-size: 18px;
            font-weight: bold;
            padding: 15px 20px;
            background: #111;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .brand img {
            width: 28px;
            height: 28px;
        }

        /* MENU */
        .sidebar-menu {
            flex: 1;
        }

        /* FOOTER */
        .sidebar-footer {
            padding: 15px;
        }

        .user-card {
            background: #111;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }

        /* =========================
           COLLAPSE MODE
        ========================= */

        .sidebar-collapsed .sidebar {
            width: 70px;
        }

        .sidebar-collapsed .content {
            margin-left: 70px;
        }

        /* 🔥 HIDE ALL SIDEBAR TEXT ON COLLAPSE */
        .sidebar-collapsed .sidebar a span {
            display: none;
        }

        /* 🔥 RFTP HIDE ON COLLAPSE */
        .sidebar-collapsed .brand span {
            display: none;
        }

        .sidebar-collapsed .brand {
            justify-content: center;
        }

        /* 🔥 ADMIN NAME SMALLER (REQUESTED) */
        .user-info strong {
            font-size: 12px;
            font-weight: 600;
        }

        .user-info small {
            font-size: 11px;
            color: #aaa;
        }

        /* 🔥 KEEP ADMIN INFO SMALL BUT VISIBLE WHEN COLLAPSED */
        .sidebar-collapsed .user-info strong {
            font-size: 10px;
        }

        .sidebar-collapsed .user-info small {
            font-size: 9px;
        }

        /* KEEP FOOTER LAYOUT CLEAN */
        .sidebar-footer .btn {
            font-size: 12px;
            padding: 6px 10px;
        }

        .sidebar-collapsed .sidebar-footer .btn {
            font-size: 11px;
            padding: 6px 8px;
            width: 100%;
        }

        /* CONTENT */
        .content {
            margin-left: 240px;
            padding: 20px;
            transition: all 0.3s;
        }

        /* TOPBAR */
        .topbar {
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

        /* DARK MODE SAFE */
        body.dark-mode {
            background: #121212;
            color: #eee;
        }

        body.dark-mode .topbar {
            background: #1f1f1f;
            border-color: #333;
        }

        body.dark-mode .sidebar {
            background: #111;
        }

        body.dark-mode .card {
            background: #1e1e1e;
            color: #fff;
        }

        body.dark-mode a {
            color: #ccc;
        }
/* smooth transition */
.sidebar-user,
.logout-btn,
.admin-name,
.admin-role,
.logout-text {
    transition: all 0.3s ease;
}

/* COLLAPSED STATE */
.sidebar-collapsed .admin-name,
.sidebar-collapsed .admin-role,
.sidebar-collapsed .logout-text {
    display: none;
}

/* center icons when collapsed */
.sidebar-collapsed .sidebar-user {
    text-align: center;
}

/* logout icon only when collapsed */
.sidebar-collapsed .logout-btn {
    justify-content: center;
}

/* optional spacing fix */
.sidebar-collapsed .user-icon {
    font-size: 22px;
}
        @media(max-width: 768px){
            .sidebar {
                left: -240px;
            }
            .sidebar.show {
                left: 0;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
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

        <!-- DO NOT CHANGE THIS LINE (AS REQUESTED) -->
        <hr style="border-color:#444;">

        <a href="{{ route('admin.settings') }}">
            <i class="bi bi-gear"></i> <span>Settings</span>
        </a>
    </div>

    <!-- FOOTER -->
<div class="sidebar-footer">

    <div class="user-card text-white sidebar-user text-center">

        <div class="user-info mb-2">

            <div class="user-icon mb-1">
                👤
            </div>

            <div class="user-text">
                <strong class="admin-name">
                    {{ auth()->user()->name ?? 'Admin' }}
                </strong><br>

                <small class="admin-role">Administrator</small>
            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button class="btn btn-sm btn-danger btn-block logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                <span class="logout-text">Logout</span>
            </button>

        </form>

    </div>

</div>

</div>

<!-- CONTENT -->
<div class="content" id="content">

    <div class="topbar d-flex justify-content-between align-items-center">

        <button class="btn btn-sm btn-dark" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>

        <div class="text-end small">
           <div>
            <strong>Date:</strong> <span id="liveDate"></span>
        </div>
        <div>
            <strong>Time:</strong> <span id="liveTime"></span>
        </div>
        </div>

    </div>

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
    // =========================
// LIVE DATE & TIME
// =========================
function updateDateTime() {
    const now = new Date();

    // DATE FORMAT: March 29 2026
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const dateStr = now.toLocaleDateString('en-US', options);

    // TIME FORMAT: 9:19 PM
    let hours = now.getHours();
    let minutes = now.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;

    const timeStr = `${hours}:${minutes} ${ampm}`;

    document.getElementById('liveDate').textContent = dateStr;
    document.getElementById('liveTime').textContent = timeStr;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>

@stack('scripts')

</body>
</html>