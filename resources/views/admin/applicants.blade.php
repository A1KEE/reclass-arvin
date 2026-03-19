@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3>👥 Applicants</h3>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position Applied</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($applicants as $a)
            <tr>
                <td>{{ $a->name }}</td>
                <td>{{ $a->position_applied }}</td>
                <td>{{ $a->status }}</td>
                <td>
                    <a href="{{ route('admin.applicants.show', $a->id) }}" class="btn btn-sm btn-primary">
                        View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection