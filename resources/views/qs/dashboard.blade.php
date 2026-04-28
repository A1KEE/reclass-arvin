@extends('layouts.admin')

@section('page-title', 'QS Editor Dashboard')

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">🏆 QS Editor Dashboard</h4>
        <span class="badge bg-primary">
            Total Applicants: {{ $applications->count() }}
        </span>
    </div>

    <div class="card shadow-sm border-0 p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="qsTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position Applied</th>
                        <th>Status</th>
                        <th>QS Check</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{ $app->id }}</td>
                        <td><strong>{{ $app->name }}</strong></td>
                        <td class="text-muted small">{{ $app->email }}</td>
                        <td>{{ $app->position_applied }}</td>
                        <td>
                            @if($app->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($app->status == 'evaluated')
                                <span class="badge bg-info">Evaluated</span>
                            @elseif($app->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($app->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">{{ $app->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if($app->education_remarks || $app->training_remarks || $app->experience_remarks || $app->eligibility_remarks)
                                <span class="badge bg-success">✓ Checked</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('qs.applicants.show', $app->id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit QS
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#qsTable').DataTable({
        pageLength: 10,
        ordering: true,
        responsive: true,
        language: {
            search: "🔍 Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ applicants",
            paginate: {
                previous: "Prev",
                next: "Next"
            }
        }
    });
});
</script>
@endpush