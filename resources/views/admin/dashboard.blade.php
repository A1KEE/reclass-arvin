@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">📊 Admin Dashboard</h3>

    <div class="row text-center">
        <div class="col-md-4">
            <div class="card shadow p-3">
                <h5>Total</h5>
                <h2>{{ $total }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h5>Submitted</h5>
                <h2>{{ $submitted }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h5>Draft</h5>
                <h2>{{ $draft }}</h2>
            </div>
        </div>
    </div>

    <!-- CHART -->
    <div class="card mt-4 p-4">
        <canvas id="myChart"></canvas>
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
        }
    });

});
</script>
@endpush