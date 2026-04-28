@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Application #{{ $application->id }}</h2>
    
    <form action="{{ route('qs.applicants.update', $application->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <h3>Education</h3>
        <textarea name="education_remarks" class="form-control">{{ $application->education_remarks }}</textarea>
        
        <h3>Training</h3>
        <textarea name="training_remarks" class="form-control">{{ $application->training_remarks }}</textarea>
        
        <h3>Experience</h3>
        <textarea name="experience_remarks" class="form-control">{{ $application->experience_remarks }}</textarea>
        
        <h3>Eligibility</h3>
        <textarea name="eligibility_remarks" class="form-control">{{ $application->eligibility_remarks }}</textarea>
        
        <button type="submit" class="btn btn-primary mt-3">Update QS</button>
    </form>
</div>
@endsection