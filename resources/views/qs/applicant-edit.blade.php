@extends('layouts.admin')  {{-- Use qs layout, not admin --}}

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit Applicant Details: {{ $application->name }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('qs.applicants.update-details', $application->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Your edit form here -->
                
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection