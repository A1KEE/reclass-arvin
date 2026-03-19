@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3>👤 Applicant Details</h3>

    <div class="card p-4 mt-3">

        <div class="mb-3">
            <label class="fw-bold">Name:</label>
            <input type="text" class="form-control" value="{{ $applicant->name }}" readonly>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Position Applied:</label>
            <input type="text" class="form-control" value="{{ $applicant->position_applied }}" readonly>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Status:</label>
            <input type="text" class="form-control" value="{{ $applicant->status }}" readonly>
        </div>

        <a href="{{ route('admin.applicants') }}" class="btn btn-secondary">⬅ Back</a>

    </div>

</div>
@endsection