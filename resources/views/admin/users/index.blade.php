@extends('layouts.admin')


@section('content')

<div class="card shadow-sm border-0">
    <div class="card-body">

        <h5 class="mb-3 fw-bold">@section('page-title')
👤 Users List
@endsection</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="usersTable">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{ $user->roles->pluck('name')->join(', ') }}
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
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
    $('#usersTable').DataTable();
});
</script>
@endpush