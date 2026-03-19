<h1>APPLICATION DETAILS</h1>

<h3>{{ $application->name }}</h3>
<p>Position: {{ $application->position_applied }}</p>
<p>Status: {{ $application->status }}</p>

<hr>

<h4>Educations</h4>
@foreach($application->educations as $edu)
    <p>{{ $edu->school_name ?? 'N/A' }}</p>
@endforeach

<hr>

<h4>Experience</h4>
@foreach($application->experiences as $exp)
    <p>{{ $exp->position ?? 'N/A' }}</p>
@endforeach

<hr>

<!-- 🔥 THIS IS WHERE YOU PUT THE FORM -->
<form method="POST" action="{{ route('admin.applicants.status', $application->id) }}">
    @csrf

    <button type="submit" name="status" value="approved">
        Approve
    </button>

    <button type="submit" name="status" value="rejected">
        Reject
    </button>
</form>