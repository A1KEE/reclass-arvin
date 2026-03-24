@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">

    <h4 class="mb-3 fw-bold">👥 Applicants</h4>

    <div class="card shadow-sm border-0 p-3">

        <div class="table-responsive">
            <table id="applicantsTable" class="table table-hover table-bordered">

                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Position Applied</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($applicants as $a)
                    <tr>
                        <td>{{ $a->name }}</td>
                        <td>{{ $a->position_applied }}</td>

                        <!-- STATUS BADGE -->
                        <td>
                            @if($a->status == 'submitted')
                                <span class="badge badge-success">Submitted</span>
                            @elseif($a->status == 'draft')
                                <span class="badge badge-secondary">Draft</span>
                            @else
                                <span class="badge badge-dark">{{ $a->status }}</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.applicants.show', $a->id) }}"
                               class="btn btn-sm btn-primary">
                                View
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
$(document).ready(function () {

    $('#applicantsTable').DataTable({
        responsive: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        ordering: true,
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