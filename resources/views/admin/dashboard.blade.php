@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">

    <h5 class="mb-4 fw-bold">@section('page-title')
📊 Admin Dashboard
@endsection</h5>

    <!-- STATS CARDS -->
    <div class="row stats-row gx-0">

        <!-- TOTAL -->
        <div class="col mb-2">
            <div class="card stat-card total-card">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6>Total Applications</h6>
                        <h2 class="counter" data-target="{{ $total }}">0</h2>
                        <span class="badge badge-dark">All Records</span>
                    </div>

                    <div class="icon-box">
                        <i class="bi bi-collection"></i>
                    </div>

                </div>
            </div>
        </div>

        <!-- PENDING -->
        <div class="col mb-2">
            <div class="card stat-card pending-card">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6>Pending</h6>
                        <h2 class="counter text-warning" data-target="{{ $pending }}">0</h2>
                        <span class="badge badge-warning">
                            {{ $total > 0 ? round(($pending / $total) * 100) : 0 }}%
                        </span>
                    </div>

                    <div class="icon-box">
                        <i class="bi bi-check-circle"></i>
                    </div>

                </div>
            </div>
        </div>

        <!-- DRAFT -->
        <div class="col mb-2">
            <div class="card stat-card draft-card">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6>Incomplete Information</h6>
                        <h2 class="counter text-danger" data-target="{{ $draft }}">0</h2>
                        <span class="badge badge-danger">
                            {{ $total > 0 ? round(($draft / $total) * 100) : 0 }}%
                        </span>
                    </div>

                    <div class="icon-box">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                </div>
            </div>
        </div>
        <!-- EVALUATED -->
<div class="col mb-2">
    <div class="card stat-card evaluated-card">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h6>Evaluated</h6>
                <h2 class="counter text-primary" data-target="{{ $evaluated }}">0</h2>
                <span class="badge badge-primary">
                    {{ $total > 0 ? round(($evaluated / $total) * 100) : 0 }}%
                </span>
            </div>

            <div class="icon-box">
                <i class="bi bi-clipboard-check"></i>
            </div>

        </div>
    </div>
</div>

<!-- APPROVED -->
<div class="col mb-2">
    <div class="card stat-card approved-card">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h6>Approved</h6>
                <h2 class="counter text-success" data-target="{{ $approved }}">0</h2>
                <span class="badge badge-success">
                    {{ $total > 0 ? round(($approved / $total) * 100) : 0 }}%
                </span>
            </div>

            <div class="icon-box">
                <i class="bi bi-patch-check"></i>
            </div>

        </div>
    </div>
</div>

    </div>

   <!-- POSITION CHART -->
<div class="card shadow-sm border-0 p-4 mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="mb-0">👨‍🏫 Applicants per Position</h5>
            <small class="text-muted">Teacher vs Master Teacher distribution</small>
        </div>

        <div class="d-flex gap-2">
            <!-- FILTER -->
            <select id="yearFilter" class="form-select form-select-sm">
                <option value="all">All</option>
                @foreach($years ?? [] as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <!-- EXPORT -->
            <button id="exportPDF" class="btn btn-sm btn-dark">
                Export PDF
            </button>
        </div>

    </div>

    <!-- CHART -->
    <div style="position: relative; height: 350px;">
        <canvas id="positionChart"></canvas>
    </div>

</div>

    <!-- MAIN CHART -->
    <div class="card shadow-sm border-0 p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">📈 Applications Overview</h5>
        </div>

        <canvas id="myChart" height="100"></canvas>
    </div>

</div>
@endsection

@push('scripts')

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // COUNTER ANIMATION
    // =========================
    // =========================
// COUNTER ANIMATION (SLOWER + SMOOTHER)
// =========================
const counters = document.querySelectorAll('.counter');

counters.forEach(counter => {
    counter.innerText = '0';

    const target = +counter.getAttribute('data-target');
    const duration = 1200; // ⬅️ mas mabagal (1.2s animation)
    const stepTime = 15;

    const steps = duration / stepTime;
    const increment = target / steps;

    let current = 0;

    const update = () => {
        current += increment;

        if (current < target) {
            counter.innerText = Math.floor(current);
            setTimeout(update, stepTime);
        } else {
            counter.innerText = target;
        }
    };

    update();
});

    // =========================
    // MAIN CHART
    // =========================
    const ctx = document.getElementById('myChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Pending', 'Draft', 'Evaluated', 'Approved'],
                datasets: [{
                    label: 'Applications',
                    data: [{{ $total }}, {{ $pending }}, {{ $draft }}, {{ $evaluated }}, {{ $approved }}],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const positionCtx = document.getElementById('positionChart');
    let positionChart;

    function loadChart(year = 'all') {

        const teacherTotal = {{ $teacherTotal }};
        const masterTotal = {{ $masterTotal }};

        const teacherData = {!! json_encode($teacherCounts) !!};
        const masterData = {!! json_encode($masterCounts) !!};

        const teacherLabels = {!! json_encode($teacherPositions) !!};
        const masterLabels = {!! json_encode($masterPositions) !!};

        if (positionChart) {
            positionChart.destroy();
        }

        positionChart = new Chart(positionCtx, {
            type: 'bar',
            data: {
                labels: [...teacherLabels, ...masterLabels],
                datasets: [
                    {
                        label: 'Teacher',
                        data: [
                            ...teacherData,
                            ...Array(masterData.length).fill(null)
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderRadius: 6
                    },
                    {
                        label: 'Master Teacher',
                        data: [
                            ...Array(teacherData.length).fill(null),
                            ...masterData
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderRadius: 6
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },

                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { autoSkip: false } }
                },

                plugins: {
                    legend: { position: 'top' },

                    tooltip: {
                        backgroundColor: '#1e1e2d',
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                const value = context.raw || 0;
                                const total = context.dataset.label === 'Teacher'
                                    ? teacherTotal
                                    : masterTotal;
                                const percent = total
                                    ? ((value / total) * 100).toFixed(1)
                                    : 0;
                                return `${context.dataset.label}: ${value} (${percent}%)`;
                            }
                        }
                    },

                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: '#000',
                        font: { weight: 'bold', size: 12 },
                        formatter: function(value, context) {
                            return value ? value : '';
                        }
                    }
                }
            },

            plugins: [ChartDataLabels]
        });
    }

    // INITIAL LOAD
    if (positionCtx) loadChart();

    // FILTER
    const filter = document.getElementById('yearFilter');
    if (filter) {
        filter.addEventListener('change', function () {
            const year = this.value;
            loadChart(year);
        });
    }

    // EXPORT PDF
    const exportBtn = document.getElementById('exportPDF');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            html2canvas(positionCtx.parentElement).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jspdf.jsPDF();
                pdf.addImage(imgData, 'PNG', 10, 10, 180, 100);
                pdf.save('Applicants_Report.pdf');
            });
        });
    }

});
</script>

@endpush