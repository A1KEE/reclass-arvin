@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">

    <h4 class="mb-4 fw-bold">📊 Admin Dashboard</h4>

    <!-- STATS CARDS -->
    <div class="row">

        <!-- TOTAL -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Applications</h6>
                        <h2 class="fw-bold">{{ $total }}</h2>
                        <span class="badge badge-dark">All Records</span>
                    </div>
                    <i class="bi bi-collection" style="font-size: 40px;"></i>
                </div>
            </div>
        </div>

        <!-- PENDING -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Pending</h6>
                        <h2 class="fw-bold text-success">{{ $pending }}</h2>
                        <span class="badge badge-success">
                            {{ $total > 0 ? round(($pending / $total) * 100) : 0 }}%
                        </span>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 40px;"></i>
                </div>
            </div>
        </div>

        <!-- DRAFT -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Draft</h6>
                        <h2 class="fw-bold text-danger">{{ $draft }}</h2>
                        <span class="badge badge-danger">
                            {{ $total > 0 ? round(($draft / $total) * 100) : 0 }}%
                        </span>
                    </div>
                    <i class="bi bi-pencil-square" style="font-size: 40px;"></i>
                </div>
            </div>
        </div>

    </div>

     <!-- Applicant Position -->
     <div class="card shadow-sm border-0 p-4 mt-4">
    <h5 class="mb-3">👨‍🏫 Applicants per Position</h5>
    <canvas id="positionChart" height="100"></canvas>
</div>

    <!-- CHART CARD -->
    <div class="card shadow-sm border-0 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">📈 Applications Overview</h5>
        </div>

        <canvas id="myChart" height="100"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('myChart');

    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total', 'Pending', 'Draft'],
            datasets: [{
                label: 'Applications',
                data: [{{ $total }}, {{ $pending }}, {{ $draft }}],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

});
const positionCtx = document.getElementById('positionChart');

if (positionCtx) {
    new Chart(positionCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($positionCounts)) !!},
            datasets: [{
                label: 'Applicants',
                data: {!! json_encode(array_values($positionCounts)) !!},
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}
</script>

@endpush