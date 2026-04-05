@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">

    <h3 class="mb-3 fw-bold">@section('page-title')
👥 Applicants
@endsection</h3>

    <div class="card shadow-sm border-0 p-3">

        <div class="table-responsive">
            <table id="applicantsTable" class="table table-bordered table-striped table-sm">

               <thead class="thead-light">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Current Position</th>
        <th>Position Applied</th>
        <th>Item Number</th>
        <th>School</th>
        <th>Salary</th>
        <th>Levels</th>
        <th>Status</th>
        <th>Created</th>
        <th width="120">Action</th>
    </tr>
</thead>

                <tbody>
@foreach($applicants as $a)
<tr>
    <td>{{ $a->id }}</td>
    <td>{{ $a->name }}</td>
    <td>{{ $a->current_position }}</td>
    <td>{{ $a->position_applied }}</td>
    <td>{{ $a->item_number }}</td>
    <td>{{ $a->school_name }}</td>
    <td>{{ $a->sg_annual_salary }}</td>

    <!-- LEVELS (JSON) -->
    <td>
    {{ implode(', ', $a->levels ?? []) }}
</td>

    <!-- STATUS BADGE -->
    <td>
    @if($a->status == 'pending')
        <span class="badge badge-warning">Pending</span>
    @elseif($a->status == 'approved')
        <span class="badge badge-success">Approved</span>
    @elseif($a->status == 'rejected')
        <span class="badge badge-danger">Rejected</span>
    @elseif($a->status == 'draft')
        <span class="badge badge-secondary">Draft</span>
    @else
        <span class="badge badge-dark">{{ $a->status }}</span>
    @endif
</td>

    <td>{{ $a->created_at }}</td>

  <td class="text-nowrap">
    <div class="d-flex align-items-center gap-4">

        <!-- VIEW -->
        <a href="{{ route('admin.applicants.show', $a->id) }}"
           class="btn btn-sm btn-primary d-flex align-items-center justify-content-center"
           style="width:32px; height:32px;"
           title="View Applicant">
            <i class="fas fa-eye"></i>
        </a>

        <!-- DOWNLOAD EXCEL -->
        <a href="{{ route('admin.applicants.export', $a->id) }}"
           class="btn btn-sm btn-success d-flex align-items-center justify-content-center"
           style="width:32px; height:32px;"
           title="Download Excel">
            <i class="fas fa-file-excel"></i>
        </a>

        <!-- SUPER ADMIN ONLY -->
        @role('super_admin')

            @if($a->status == 'evaluated')

                <!-- APPROVE -->
                <form method="POST" action="{{ route('superadmin.applicants.approve', $a->id) }}">
                    @csrf
                    <button type="button"
                            class="btn btn-sm btn-success d-flex align-items-center justify-content-center btnApprove"
                            style="width:32px; height:32px;"
                            title="Approve">
                        <i class="fas fa-check"></i>
                    </button>
                </form>

                <!-- REJECT -->
                <form method="POST" action="{{ route('superadmin.applicants.reject', $a->id) }}">
                    @csrf
                    <button type="button"
                            class="btn btn-sm btn-danger d-flex align-items-center justify-content-center btnReject"
                            style="width:32px; height:32px;"
                            title="Reject">
                        <i class="fas fa-times"></i>
                    </button>
                </form>

            @else

                @php
                    $label = 'Not Ready';

                    if($a->status == 'pending'){
                        $label = 'Need Evaluation';
                    } elseif($a->status == 'approved' || $a->status == 'rejected'){
                        $label = 'Completed';
                    } elseif($a->status == 'draft'){
                        $label = 'Incomplete';
                    }
                @endphp

                <span class="badge bg-secondary" style="font-size:11px;">
                    {{ $label }}
                </span>

            @endif

        @endrole

    </div>
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
let table;

$(document).ready(function () {

    table = $('#applicantsTable').DataTable({
        responsive: true,
        autoWidth: false,
        scrollX: true,
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
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

@if(session('info'))
<script>
Swal.fire({
    icon: 'info',
    title: 'Notice',
    text: "{{ session('info') }}",
});
</script>
@endif
<script>
    $(document).ready(function(){

    // APPROVE
    $('.btnApprove').click(function(){
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Approve Applicant?',
            text: "This action will finalize the application.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // REJECT
    $('.btnReject').click(function(){
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Reject Applicant?',
            text: "This cannot be undone.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, Reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

});
</script>
<script>
const urlParams = new URLSearchParams(window.location.search);
const highlightId = urlParams.get('highlight');

if (highlightId) {
    const row = document.getElementById('app-' + highlightId);
    if (row) {
        row.style.backgroundColor = '#fff3cd';
    }
}
</script>
@endpush