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

        <!-- SUBMITTED -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Submitted</h6>
                        <h2 class="fw-bold text-success">{{ $submitted }}</h2>
                        <span class="badge badge-success">
                            {{ $total > 0 ? round(($submitted / $total) * 100) : 0 }}%
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
            labels: ['Total', 'Submitted', 'Draft'],
            datasets: [{
                label: 'Applications',
                data: [{{ $total }}, {{ $submitted }}, {{ $draft }}],
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
</script>
@endpush