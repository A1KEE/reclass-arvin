@extends('layouts.admin')

@section('content')

<div class="container-fluid mt-3">

<h4 class="mb-4">
@section('page-title')
🏆 Top Applicants Ranking
@endsection
</h4>

<div class="card shadow-sm border-0 p-3">

    <!-- 🔥 FILTER DROPDOWN -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <label class="fw-bold me-2">Filter by Position:</label>

            <select id="positionFilter" class="form-select form-select-sm d-inline-block" style="width: 220px;">
                @php
                    $positions = $applications->pluck('position_applied')->unique()->filter()->values();
                @endphp

                @foreach($positions as $pos)
                    <option value="{{ $pos }}">{{ $pos }}</option>
                @endforeach
            </select>
        </div>

        <div class="text-muted small">
            Dynamic ranking per position
        </div>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle" id="rankingTable">

            <thead class="table-dark">
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Total Score</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($applications as $app)
                <tr data-position="{{ $app->position_applied }}">
                    <td class="rank-cell"></td>

                    <td>{{ $app->name }}</td>

                    <td class="text-muted small">{{ $app->email }}</td>

                    <td>{{ $app->position_applied ?? 'N/A' }}</td>

                    <td class="score-cell">
                        <strong>{{ $app->total_score ?? 0 }}</strong>
                    </td>

                    <td>
                        @if(empty($app->total_score))
                            <span class="text-secondary">● NOT EVALUATED</span>
                        @elseif($app->final_result == 'qualified')
                            <span class="text-success">● Qualified</span>
                        @else
                            <span class="text-danger">● Not Qualified</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {

    let table = $('#rankingTable').DataTable({
        ordering: false, // 🔥 IMPORTANT para di masira ranking
        pageLength: 10
    });

    // 🔥 GET UNIQUE POSITIONS
    let positions = [];
    $('#rankingTable tbody tr').each(function() {
        let pos = $(this).data('position');
        if(pos && !positions.includes(pos)) {
            positions.push(pos);
        }
    });

    // 🔥 SET DEFAULT (Teacher II OR FIRST AVAILABLE)
    let defaultPosition = positions.includes('Teacher II') ? 'Teacher II' : positions[0];
    $('#positionFilter').val(defaultPosition);

    // 🔥 FILTER FUNCTION
    function filterTable(position) {

        $('#rankingTable tbody tr').hide();

        let visibleRows = [];

        $('#rankingTable tbody tr').each(function() {
            if($(this).data('position') === position) {
                $(this).show();
                visibleRows.push($(this));
            }
        });

        // 🔥 SORT BY SCORE DESC
        visibleRows.sort(function(a, b) {
            let scoreA = parseFloat($(a).find('.score-cell').text()) || 0;
            let scoreB = parseFloat($(b).find('.score-cell').text()) || 0;
            return scoreB - scoreA;
        });

        // 🔥 RE-APPEND SORTED ROWS
        let tbody = $('#rankingTable tbody');
        visibleRows.forEach(row => tbody.append(row));

        // 🔥 RANKING RESET PER POSITION
        let rank = 1;
        let prevScore = null;
        let displayRank = 1;

        visibleRows.forEach(function(row) {

            let score = parseFloat($(row).find('.score-cell').text()) || 0;

            if(prevScore !== null && score === prevScore) {
                displayRank = displayRank; // same rank
            } else {
                displayRank = rank;
            }

            let rankText = '';

            if(displayRank == 1) rankText = '🥇';
            else if(displayRank == 2) rankText = '🥈';
            else if(displayRank == 3) rankText = '🥉';
            else rankText = '#' + displayRank;

            $(row).find('.rank-cell').html(rankText);

            prevScore = score;
            rank++;
        });

    }

    // 🔥 INITIAL LOAD
    filterTable(defaultPosition);

    // 🔥 ON CHANGE
    $('#positionFilter').on('change', function() {
        filterTable($(this).val());
    });

});
</script>
@endpush