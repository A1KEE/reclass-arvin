<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reclassification - Applicant Form</title>

      <!-- Favicon - DO LOGO -->
    <link rel="icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    
    <!-- Alternative sizes -->
    <link rel="apple-touch-icon" href="{{ asset('images/DO-LOGO.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
</head>
<body>
  
  
<div class="container">

    <!-- Top reference + automatic "For ..." -->
    <div class="doc-top">
        <div class="left">DBM-DepEd JC 01, s.2025_Form No. 2-A</div>
        <!-- This will update automatically based on selected position + level -->
        <div class="right" id="forPosition">For —</div>
    </div>
<br>
    <div class="header">
        <!-- Put your logo in public/images/depEd-logo.png or change the src -->
       <img src="{{ asset('images/depEd-logo.png') }}" alt="DepEd Logo" style="height: 80px;">

        <div class="dep-rc-title">Republika ng Pilipinas</div>
        <div class="dep-sub">Department of Education</div>
<br>
        <h5 class="fw-bold">RECLASSIFICATION FORM FOR TEACHING POSITIONS (RFTP)</h5>
    </div>

    <div class="card form-card p-4">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="applicantForm" action="{{ route('applicants.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name:</label>
                    <input name="name" id="name" class="form-control" placeholder="Ex.Juan D. Cruz" value="{{ old('name') }}">
                </div>

             <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Current Position:</label>
                <select id="current_position" name="current_position" class="form-select">
                <option value="">-- Select Current Position --</option>

                @foreach($positions as $p)
                <option value="{{ $p }}">{{ $p }}</option>
                @endforeach

                </select>
                </div>
            
                <div class="col-md-2 mb-3">
                  <label class="form-label fw-bold">Step:</label>
                  <select id="step" class="form-select">

                  <option value="">Step</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>

                  </select>
                  </div>

                  </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold">Position Applied:</label>
                  <select id="position_applied" name="position_applied" class="form-select">
                  <option value="">-- Select Position Applied --</option>
                  </select>
                  </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Item No. of Current Position:</label>
                    <input type="text" name="item_number" class="form-control" value="{{ old('item_number') }}">
                </div>
            </div>

         <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Station / School:</label>
        <select id="school_id" name="school_name" class="form-select">
            <option value="">-- Select School --</option>

            @php
                $groupedSchools = $schools->groupBy('level_type');
            @endphp

            {{-- Kindergarten Schools --}}
            @if(isset($groupedSchools['kindergarten']))
                <optgroup label="Kindergarten Schools">
                    @foreach($groupedSchools['kindergarten'] as $school)
                        <option value="{{ $school->name }}" data-level="kindergarten"
                            {{ old('school_name') == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif

            {{-- Elementary Schools --}}
            @if(isset($groupedSchools['elementary']))
                <optgroup label="Elementary Schools">
                    @foreach($groupedSchools['elementary'] as $school)
                        <option value="{{ $school->name }}" data-level="elementary"
                            {{ old('school_name') == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif

            {{-- Junior High Schools --}}
            @if(isset($groupedSchools['junior_high']))
                <optgroup label="Junior High Schools">
                    @foreach($groupedSchools['junior_high'] as $school)
                        <option value="{{ $school->name }}" data-level="junior_high"
                            {{ old('school_name') == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif

            {{-- Senior High Schools --}}
            @if(isset($groupedSchools['senior_high']))
                <optgroup label="Senior High Schools">
                    @foreach($groupedSchools['senior_high'] as $school)
                        <option value="{{ $school->name }}" data-level="senior_high"
                            {{ old('school_name') == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif
        </select>
    </div>

                <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">SG/Annual Salary:</label>
               <input 
                type="text"
                id="salary"
                name="sg_annual_salary"
                class="form-control"
                readonly
                placeholder="Auto computed from position and step">
                </div>
            </div>

    <div class="mb-3">
  <label class="form-label fw-bold">Level:</label>
  <div class="row ps-5">  {{-- ps-5 ≈ 3rem (~1 inch) --}}
    <div class="col-md-6">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="kindergarten" id="levelKindergarten" {{ in_array('kindergarten', old('levels', [])) ? 'checked' : '' }}>
        <label class="form-check-label" for="levelKindergarten">Kindergarten</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="elementary" id="levelElementary">
        <label class="form-check-label" for="levelElementary">Elementary</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="junior_high" id="levelJuniorHigh">
        <label class="form-check-label" for="levelJuniorHigh">Junior High School</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="senior_high" id="levelSeniorHigh">
        <label class="form-check-label" for="levelSeniorHigh">Senior High School</label>
      </div>
    </div>
  </div>
</div>

    <!-- QS TABLE -->
<hr class="mt-2">
<h5 class="text-left fw-bold mt-3">I. QUALIFICATION STANDARDS</h5>

<table class="table table-bordered mt-3 text-center align-middle">
    <thead>
        <tr>
            <th>Elements</th>
            <th>QS of the Position</th>
            <th>QS of the Applicant</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
       <tr id="education-row">
    <td>Education</td>
    <td id="qs_education">—</td>
    <td>
        <button type="button" class="btn btn-sm btn-primary add-education-btn">
    ➕ Add Education
</button>

        <div id="education_summary" class="mt-2 small text-muted">
            No Education Added.
        </div>
    </td>
    <td id="education_remark"><span class="text-muted">Waiting for the QS</span></td>
     <!-- Hidden input for submission -->
    <input type="hidden" name="qs_applicant_education" id="input_qs_education" value="">
    <input type="hidden" name="remarks_education" id="input_remarks_education" value="">
</tr>
       <tr>
           <td>Training</td>
           <td id="qs_training">—</td>
           <td>
               <button type="button"
                       class="btn btn-sm btn-primary add-training-btn">
                   ➕ Add Training
               </button>

               <div id="training_summary" class="mt-2 small text-muted">
                   No trainings added.
               </div>
           </td>
           <td id="training_remark"><span class="text-muted">Waiting for the QS</span></td>

           <input type="hidden" name="qs_applicant_training" id="input_qs_training" value="">
           <input type="hidden" name="remarks_training" id="input_remarks_training" value=""> 
       </tr>

     <tr>
    <td>Experience</td>
    <td id="qs_experience">—</td>
    <td>
        <button type="button"
                class="btn btn-sm btn-primary add-experience-btn">
            ➕ Add Experience
        </button>

        <div id="experience_summary" class="mt-2 small text-muted">
            No experience added.
        </div>
    </td>
    <td id="experience_remark">
        <span class="text-muted">Waiting for the QS</span>
    </td>

    <input type="hidden" name="qs_applicant_experience" id="input_qs_experience" value="">
    <input type="hidden" name="remarks_experience" id="input_remarks_experience" value="">
</tr>

    <tr>
    <td>Eligibility</td>
    <td id="qs_eligibility">—</td>
    <td>
        <button type="button"
                class="btn btn-sm btn-primary add-eligibility-btn">
            ➕ Add Eligibility
        </button>

        <div id="eligibility_summary" class="mt-2 small text-muted">
            No eligibility added.
        </div>
    </td>
    <td id="eligibility_remark">
        <span class="text-muted">Waiting for the QS</span>
    </td>

    <input type="hidden" name="qs_applicant_eligibility" id="input_qs_eligibility" value="">
    <input type="hidden" name="remarks_eligibility" id="input_remarks_eligibility" value="">
</tr>
    </tbody>
</table>


   <div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted fst-italic mb-0">
    Note: Indicate the QS of the Position Applied for based on the CSC-Approved QS
  </p>
  <!-- <button type="button" id="checkQSBtn" class="btn btn-primary">
    Evaluate All Qualifications
  </button> -->
</div>

    <!-- PERFORMANCE REQUIREMENTS -->
     <div id="performanceRequirements" style="display:none;">
    <h5 class="text-left fw-bold mt-4">II. PERFORMANCE REQUIREMENTS</h5>
    <div class="d-flex justify-content-between align-items-center mt-2">
  <p class="mb-0">
    1. Copy of duly approved IPCRF for the school year immediately preceeding the application.
    <span id="ipcrfStatus" class="ms-2 d-none text-success fw-semibold">
      <i class="bi bi-check-circle-fill me-1"></i> Uploaded
    </span>
  </p>

  <button type="button" class="btn btn-sm btn-outline-primary"
      data-bs-toggle="modal" data-bs-target="#ipcrfModal">
      View/Upload IPCRF
  </button>
</div>
    <p>2. The applicant must meet the following performance requirements depending on the position applied for.</p>

    <table class="table table-bordered mt-3 text-center align-middle" id="performanceTable">
        <thead>
            <tr>
                <th>Position Applied</th>
                <th>Performance Requirements</th>
            </tr>
        </thead>
        <tbody>
            <tr data-position="Teacher II">
                <td>Teacher II</td>
                <td>
                    At least 6 Proficient COIs at Very Satisfactory; and<br>
                    At least 4 Proficient NCOIs at Very Satisfactory
                </td>
            </tr>
            <tr data-position="Teacher III">
                <td>Teacher III</td>
                <td>
                    At least 12 Proficient COIs at Very Satisfactory; and<br>
                    At least 8 Proficient NCOIs at Very Satisfactory
                </td>
            </tr>
            <tr data-position="Teacher IV">
                <td>Teacher IV</td>
                <td>
                    21 Proficient COIs at Very Satisfactory; and<br>
                    16 Proficient NCOIs at Very Satisfactory
                </td>
            </tr>
            <tr data-position="Teacher V">
                <td>Teacher V</td>
                <td>
                    At least 6 Proficient COIs at Outstanding; and<br>
                    At least 4 Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Teacher VI">
                <td>Teacher VI</td>
                <td>
                    At least 12 Proficient COIs at Outstanding; and At least 4 Proficient NCOIs 
                    at Very Satisfactory and 4 Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Teacher VII">
                <td>Teacher VII</td>
                <td>
                    At least 18 Proficient COIs at Outstanding; and At least 6 Proficient NCOIs 
                    at Very Satisfactory and 6 Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Master Teacher I">
                <td>Master Teacher I</td>
                <td>
                    21 Proficient COIs at Outstanding; and 8 Proficient NCOIs at Very Satisfactory 
                    and 8 Proficient NCOIs at Outstanding
                </td>
            </tr>
             <tr data-position="Master Teacher II">
                <td>Master Teacher II</td>
                <td>
                    At least 10 Highly Proficient COIs at Outstanding; and At least 5 Highly Proficient NCOIs at Very Satisfactory 
                    and 5 Highly Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Master Teacher III">
                <td>Master Teacher III</td>
                <td>
                    21 Highly Proficient COIs at Outstanding; and At least 8 Highly Proficient NCOIs at Very Satisfactory 
                    and 8 Highly Proficient NCOIs at Outstanding
                </td>
            </tr>
        </tbody>
    </table>

 <div id="ppst-container">
    @include('applicants.ppst-table')
</div>


{{-- ========================================================= --}}
{{-- III. COMPARATIVE ASSESSMENT RESULT --}}
{{-- ========================================================= --}}
<hr class="mt-5 mb-4">
<h5 class="fw-bold text-uppercase">III. Comparative Assessment Result</h5>

<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr>
        <th>Education</th>
        <th>Training</th>
        <th>Experience</th>
        <th>Performance</th>
        <th>Classroom Observable Indicators</th>
        <th>Non-Classroom Observable Indicators</th>
        <th>Behavioral Events Interview (BEI)</th>
        <th>Total Score</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="number" name="comparative[education]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="number" name="comparative[training]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="number" name="comparative[experience]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="number" name="comparative[performance]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[classroom]" 
                 class="form-control form-control-sm text-center" 
                 id="comparativeClassroom"
                 value="0"
                 readonly>
        </td>
        <td><input type="number" name="comparative[non_classroom]" 
                 class="form-control form-control-sm text-center"
                 id="comparativeNonClassroom"
                 value="0"
                 readonly>
        </td>
        <td><input type="number" name="comparative[BEI]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[total]" class="form-control form-control-sm text-center"></td>
      </tr>
    </tbody>
  </table>
</div>


<div class="row text-center mb-5 mt-5">
    <div class="col-md-6">
        <p class="fw-semibold mb-1">Conforme:</p>
        <br><br>
        <p id="teacherApplicant" class="fw-bold text-decoration-underline mb-0">{{ old('name') }}</p>
        <p class="small mb-0">Teacher Applicant</p>
    </div>

    <div class="col-md-6">
        <p class="fw-semibold mb-1">Attested by:</p>
        <br><br>
        <p class="fw-bold text-decoration-underline">ERNEST JOSEPH C. CABRERA</p>
        <p class="small mb-0">HRMPSB Chair</p>
    </div>
</div>

{{-- ========================================================= --}}
{{-- IV. DEPED SCHOOLS DIVISION OFFICE ACTION --}}
{{-- ========================================================= --}}
<h5 class="fw-bold text-uppercase">IV. DepEd Schools Division Office Action</h5>

<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr>
        <th>Reclassification of Position</th>
        <th>From</th>
        <th>Salary Grade</th>
        <th>To</th>
        <th>Salary Grade</th>
        <th>Date Processed</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Reclassification of Position</td>
        <td><input type="text" id="from_position" name="division[from_position]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="text" id="from_grade" name="division[from_grade]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="text" id="to_position" name="division[to_position]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="text" id="to_grade" name="division[to_grade]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="date" name="division[date_processed]" class="form-control form-control-sm text-center"></td>
        <td><input type="text" name="division[remarks]" class="form-control form-control-sm text-center"></td>
      </tr>
    </tbody>
  </table>
</div>

<!-- ROW 1 : Evaluated (Right) -->
<div class="row mb-4">
  <div class="col-md-6"></div>
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Evaluated by:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">MA. CLARINDA L. OMO</p>
    <p class="small mb-0">Administrative Officer IV (HRMO)</p>
  </div>
</div>

<!-- ROW 2 : Certified (Left) -->
<div class="row mb-4">
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">MARK ANGELO S. ENRIQUEZ, JD</p>
    <p class="small mb-0">Administrative Officer V (Admin Services)</p>
  </div>
  <div class="col-md-6"></div>
</div>

<!-- ROW 3 : Recommending (Center) -->
<div class="row text-center mb-4">
  <div class="col-md-12">
    <p class="fw-semibold mb-1">Recommending Approval:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">NOEL D. BAGANO</p>
    <p class="small mb-0">Schools Division Superintendent</p>
  </div>
</div>



{{-- ========================================================= --}}
{{-- V. DEPED REGIONAL OFFICE ACTION --}}
{{-- ========================================================= --}}
<h5 class="fw-bold text-uppercase">V. DepEd Regional Office Action</h5>

<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr>
        <th>Reclassification of Position</th>
        <th>From</th>
        <th>Salary Grade</th>
        <th>To</th>
        <th>Salary Grade</th>
        <th>Date Processed</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Reclassification of Position</td>
        <td><input type="text" name="regional[from_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[from_grade]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[to_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[to_grade]" class="form-control form-control-sm"></td>
        <td><input type="date" name="regional[date_processed]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[remarks]" class="form-control form-control-sm"></td>
      </tr>
    </tbody>
  </table>
</div>

<!-- ROW 1 : Evaluated (Right) -->
<div class="row mb-4">
  <div class="col-md-6"></div>
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Evaluated by:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1"></p>
    <p class="small mb-0">Teachers Credential Evaluator</p>
  </div>
</div>

<!-- ROW 2 : Certified (Left) -->
<div class="row mb-4">
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">Atty. JOYLYN P. DUNLUAN</p>
    <p class="small mb-0">Chief, Administrative Division</p>
  </div>
  <div class="col-md-6"></div>
</div>

<!-- ROW 3 : Approved (Center) -->
<div class="row text-center mb-4">
  <div class="col-md-12">
    <p class="fw-semibold mb-1">Approved:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">JOCELYN DR. ANDAYA</p>
    <p class="small mb-0">Regional Director, NCR</p>
    <p class="small mb-0">Concurrent Officer-In-Charge, Office of the Assistant Secretary for Operations</p>
  </div>
</div>

<input type="hidden" name="qs_position_education" id="input_qs_position_education">
<input type="hidden" name="qs_position_training" id="input_qs_position_training">
<input type="hidden" name="qs_position_experience" id="input_qs_position_experience">
<input type="hidden" name="qs_position_eligibility" id="input_qs_position_eligibility">

<!-- === PRINT & SUBMIT BUTTONS === -->
<div class="text-center my-4">
    <button type="button" class="btn btn-secondary me-2" onclick="window.print()">🖨️ Print</button>
    <button type="submit" form="applicantForm" class="btn btn-success">💾 Submit</button>
</div>
        </form>
    </div>
</div>
</div> <!-- /.container -->

<!-- EDUCATION MODAL -->

<div class="modal fade" id="educationModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
  <!-- Modal Header -->
  <div class="modal-header bg-primary text-white">
    <h6 class="modal-title fw-bold">
      <i class="fas fa-graduation-cap me-2"></i>Add Education
    </h6>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
  </div>

  <!-- Modal Body -->
  <div class="modal-body p-4">
    <div class="row g-4">

      <!-- ================= LEFT SIDE ================= -->
      <!-- RUBRICS + SUMMARY -->
      <div class="col-md-4 d-flex">
        <div class="education-rubrics-sticky w-100">

          <!-- RUBRICS CARD -->
          <div class="card border-0 shadow-sm mb-3 border-light-green flex-fill">
            <div class="card-header bg-light-green">
              <h6 class="mb-0 fw-semibold text-green">
                <i class="fas fa-list-check me-2"></i>Education Rubrics
              </h6>
            </div>
            <div class="card-body small">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <strong>Bachelor’s Degree</strong><br>
                  Required for Teacher I–V
                </li>
                <li class="list-group-item">
                  <strong>Master’s Degree Units</strong><br>
                  Required for Teacher VI–VII
                </li>
                <li class="list-group-item">
                  <strong>Master’s Degree</strong><br>
                  Required for Master Teacher
                </li>
                <li class="list-group-item text-muted">
                  Points are based on excess units earned
                </li>
              </ul>
            </div>
          </div>

          <!-- SUMMARY CARD -->
          <div class="card border-0 shadow-sm border-light-green">
            <div class="card-body bg-light-green">
              <strong>Education Summary</strong><br>
              Degree: <span id="edu_degree_display">—</span><br>
              Education Level: <span id="edu_level_display">—</span><br>
              Status: <span id="edu_status_display" class="text-muted">Waiting</span>
            </div>
          </div>

        </div>
      </div>

      <!-- ================= RIGHT SIDE ================= -->
      <!-- EDUCATION FORM -->
      <div class="col-md-8">

        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-light py-3">
            <h6 class="mb-0 fw-semibold text-primary">
              Education Information
            </h6>
          </div>

          <div class="card-body p-4">
            <div class="row g-4">

              <!-- DEGREE -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-book me-2 text-primary"></i>
                  Degree
                </label>
                <input type="text"
                       id="education_name"
                       class="form-control education-modal-border"
                       placeholder="e.g. Bachelor of Secondary Education"
                       required>
              </div>

              <!-- SCHOOL GRADUATED -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-school me-2 text-primary"></i>
                  School Graduated
                </label>
                <input type="text"
                       id="education_school"
                       class="form-control education-modal-border"
                       placeholder="e.g. University of Manila"
                       required>
              </div>

              <!-- DATE GRADUATED -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-calendar-alt me-2 text-primary"></i>
                  Date Graduated
                </label>
                <input type="date"
                       id="education_date"
                       class="form-control education-modal-border"
                       required>
              </div>

              <!-- EDUCATION LEVEL -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-certificate me-2 text-primary"></i>
                  Highest Educational Attainment
                </label>
                <select id="education_units_select"
                        class="form-select education-modal-border">
                  <option value="">Select Education Level</option>
                </select>
              </div>

              <!-- OTHER UNITS -->
              <div class="col-md-12 d-none" id="education_units_other_container">
                <label class="form-label fw-semibold">
                  <i class="fas fa-hashtag me-2 text-primary"></i>
                  Specify Units
                </label>
                <input type="number"
                       id="education_units_other"
                       class="form-control education-modal-border"
                       min="0"
                       placeholder="Enter number of units">
              </div>

              <!-- CERTIFICATE UPLOAD -->
              <div class="col-md-12">
                <label class="form-label fw-semibold">
                  <i class="fas fa-file-pdf me-2 text-danger"></i>
                  Certificate (PDF)
                </label>

                <div class="education-modal-border-dashed rounded-3 p-4 text-center bg-light"
                     style="cursor:pointer"
                     onclick="document.getElementById('education_file').click()">

                  <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>

                  <input type="file"
                         id="education_file"
                         class="d-none"
                         accept="application/pdf"
                         required>

                  <span class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-upload me-2"></i>
                    Choose PDF File
                  </span>

                  <div class="education-modal-form-text mt-2">
                    Max file size: 10MB (PDF only)
                  </div>

                  <div id="education_file_name"
                       class="fw-semibold text-muted mt-2">
                    No file chosen
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal Footer -->
  <div class="modal-footer">
    <button type="button"
            class="btn btn-success"
            id="saveEducation">
      <i class="bi bi-check-circle me-1"></i>
      Save Education
    </button>

    <button type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
      Cancel
    </button>
  </div>

</div>
  </div>
</div>


<!-- TRAINING MODAL -->
<div class="modal fade" id="trainingModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-chalkboard-teacher me-2"></i>Training / Seminars Attended
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">

          <!-- ================= LEFT SIDE ================= -->
          <!-- RUBRICS + SUMMARY (30%) -->
          <div class="col-md-4">
            <div class="training-summary-sticky">

              <!-- TRAINING RUBRICS -->
              <div class="card border-0 shadow-sm mb-3 border-light-green">
                <div class="card-header bg-light-green">
                  <h6 class="mb-0 fw-semibold text-green">
                    <i class="fas fa-list-check me-2"></i>Training Rubrics
                  </h6>
                </div>
                <div class="card-body small training-card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <strong>Face-to-Face Training</strong><br>
                      8 hours per day
                    </li>
                    <li class="list-group-item">
                      <strong>Online / Virtual Training</strong><br>
                      3 hours per day (As per DepEd guidelines and legal basis for online training)
                    </li>
                    <li class="list-group-item text-muted">
                      Points are based on total accumulated hours
                    </li>
                  </ul>
                </div>
              </div>

              <!-- TRAINING SUMMARY -->
              <div id="modal_training_summary">
                <div class="alert bg-light-green border-light-green mb-0 p-3">
                  <strong>Training Summary</strong><br>
                  Required Hours: <span id="required_hours_display">0</span> hours<br>
                  Current Total: <span id="total_training_hours">0</span> hours<br>
                  Status: <span class="text-muted">No trainings added</span><br>
                  Points: <span class="fw-bold">0</span>
                </div>
              </div>

            </div>
          </div>

          <!-- ================= RIGHT SIDE ================= -->
          <!-- TRAINING FORM (70%) -->
          <div class="col-md-8">

            <div id="trainingContainer">
              <!-- JS will inject training items here -->
            </div>

            <!-- ADD TRAINING BUTTON -->
            <div class="mb-3 mt-2">
              <button type="button" class="btn btn-outline-primary btn-sm" id="addTraining">
                ➕ Add Another Training
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer d-flex justify-content-end">
        <button type="button" class="btn btn-success" id="saveTraining">
          <i class="bi bi-check-circle me-1"></i> Save Training
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>

    </div>
  </div>
</div>

<!-- EXPERIENCE MODAL -->
<div class="modal fade" id="experienceModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title fw-bold">Add Work Experience</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-0 d-flex">

        <!-- LEFT SIDE: RUBRICS + SUMMARY (30%) -->
        <div class="col-md-4 p-4 border-end" style="flex: 0 0 30%; max-width: 30%;">
          <div class="experience-summary-sticky">

            <!-- EXPERIENCE RUBRICS -->
            <div class="card border-0 shadow-sm mb-3 border-light-green">
              <div class="card-header bg-light-green">
                <h6 class="mb-0 fw-semibold text-green">
                  <i class="fas fa-list-check me-2"></i>Experience Rubrics
                </h6>
              </div>
              <div class="card-body small">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <strong>1-5 years</strong><br>
                    Level 1-10
                  </li>
                  <li class="list-group-item">
                    <strong>6-10 years</strong><br>
                    Level 11-20
                  </li>
                  <li class="list-group-item">
                    <strong>11-15 years</strong><br>
                    Level 21-32
                  </li>
                </ul>
              </div>
            </div>

            <!-- EXPERIENCE SUMMARY -->
            <div id="experience_summary_modal">
              <div class="alert bg-light-green border-light-green mb-0 p-3">
                <strong>Experience Summary</strong><br>
                Required Years: <span id="required_exp_years_display">0</span><br>
                Current Total: <span id="total_exp_years_display">0</span><br>
                Status: <span class="text-muted">No experiences added</span><br>
                QS Points: <span class="fw-bold">0</span>
              </div>
            </div>

          </div>
        </div>

        <!-- RIGHT SIDE: FORM (70%) SCROLLABLE -->
        <div class="col-md-8 p-4" style="flex: 1; max-height: 70vh; overflow-y: auto;">

          <!-- EXPERIENCE CONTAINER -->
          <div id="experienceContainer" class="mb-3 row g-3"></div>

          <!-- ADD EXPERIENCE BUTTON -->
          <div class="mb-3">
            <button type="button" class="btn btn-outline-primary btn-sm" id="addExperience">
              ➕ Add Another Experience
            </button>
          </div>

          <!-- FINAL SUMMARY (for dynamic updates) -->
          <div id="experience_summary" class="mt-3"></div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="saveExperienceBtn">
          <i class="bi bi-check-circle me-1"></i> Save Experience
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

 <!-- ELIGIBILITY MODAL -->
<div class="modal fade" id="eligibilityModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title">Add Eligibility</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body d-flex p-0">

      <!-- LEFT SIDE: RUBRICS + SUMMARY -->
<div class="col-md-4 p-4 border-end" style="flex: 0 0 30%; max-width: 30%;">
  <div class="eligibility-summary-sticky">

    <!-- ELIGIBILITY RUBRICS -->
    <div class="card shadow-sm mb-3 border-light-green bg-light-green">
      <div class="card-header text-green fw-bold">
        <i class="fas fa-list-check me-2"></i>Eligibility Rubrics
      </div>
      <div class="card-body small text-dark">
    <ul class="list-group list-group-flush">
      <li class="list-group-item">RA 1080 (Teacher)</li>
      <li class="list-group-item">Expired ID → Upload renewal receipt or renewed ID</li>
      <li class="list-group-item">Accepted formats: PDF, Word, Images</li>
    </ul>
  </div>
</div>

    <!-- ELIGIBILITY SUMMARY -->
    <div id="eligibility_summary_modal" class="card shadow-sm border-light-green bg-light-green">
      <div class="card-body p-3 text-green">
        <strong>Eligibility Summary</strong><br>
        Status: <span class="badge bg-secondary">Waiting...</span><br>
        Selected Eligibility: <span class="fw-bold">—</span>
      </div>
    </div>

  </div>
</div>

        <!-- RIGHT SIDE: FORM (SCROLLABLE) -->
        <div class="col-md-8 p-4" style="flex: 1; max-height: 70vh; overflow-y: auto;">

          <div class="row g-3">

            <!-- Eligibility Dropdown -->
            <div class="col-12">
              <div class="card p-3 shadow-sm border-0">
                <label class="form-label fw-bold text-primary">Eligibility Name</label>
                <select id="eligibilityInput" class="form-select">
                  <option value="">Select Eligibility</option>
                  <option value="LET">LET</option>
                  <option value="PBET">PBET</option>
                  <option value="MAGNA CARTA">MAGNA CARTA</option>
                </select>
                <small class="form-text text-muted">Select eligibility as required by QS.</small>
              </div>
            </div>

            <!-- ID Expiry -->
            <div class="col-12">
              <div class="card p-3 shadow-sm border-0">
                <label class="form-label fw-bold text-primary">ID Expiry Date</label>
                <input type="date" id="eligibilityExpiry" class="form-control">
                <small class="form-text text-muted">Enter expiry date. If expired, upload renewal proof.</small>
              </div>
            </div>

            <!-- Attachment -->
            <div class="col-12">
              <div class="card p-3 shadow-sm border-0">
                <label class="form-label fw-bold text-primary">Attachment (PDF, Word, Image)</label>
                <input type="file" id="eligibilityAttachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                <small class="form-text text-muted">
                  Upload scanned ID. If expired, attach official receipt or renewed ID.
                </small>
              </div>
            </div>

          </div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="saveEligibilityBtn" onclick="saveEligibility()">
          <i class="bi bi-check-circle me-1"></i> Save Eligibility
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<!-- Hidden inputs for QS -->
<input type="hidden" id="appliedPosition" value="Teacher V">
<input type="hidden" id="appliedLevel" value="kindergarten">


<div class="modal fade" id="ipcrfModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white border-0">
        <h6 class="modal-title">Upload IPCRF Files</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">

        <p id="ipcrfInstruction" class="text-muted mb-4"></p>

        <div class="row g-3" id="ipcrfContainer">
          <!-- IPCRF cards will render here dynamically -->
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveIpcrfBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- QS Premium Loading Overlay -->
<div id="qsLoadingOverlay">
    <div class="qs-loader-wrapper">
        <div class="loader"></div>
        <div class="qs-loading-text">
            Please wait while we load your Qualification Standards
        </div>
    </div>
</div>
  


  <!-- SCRIPTS - UPDATED VERSION -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.10.2/lottie.min.js"></script>

  <!-- GLOBAL VARIABLES -->
  <script>
      // ITO ANG IMPORTANTE: Check kung tama ang structure
      window.qsConfig = @json(config('qs') ?? []);
      
      // Debug logging
      console.log('=== QS CONFIG DEBUG ===');
      console.log('Full config:', window.qsConfig);
      console.log('Selected Level:', '{{ $selectedLevel ?? "kindergarten" }}');
      console.log('Selected Position:', '{{ $selectedPosition ?? "Teacher III" }}');
      console.log('=== END DEBUG ===');
      
      window.requiredTrainingHours = {{ $requiredTrainingHours ?? 0 }};
      window.requiredTrainingLevel = {{ $requiredTrainingLevel ?? 0 }};
      // Other globals
      window.requiredExperienceYears = {{ $requiredYears ?? 0 }};
      window.qsEducationUnits = @json($qsUnits ?? []);
  </script>

  <!-- LOAD JS FILES -->
  <script src="{{ asset('js/ipcrf.js') }}"></script>
  <script src="{{ asset('js/auto-check-qs.js') }}"></script>
  <script src="{{ asset('js/experience.js') }}"></script>
  <script src="{{ asset('js/education-points.js') }}"></script>
  <script src="{{ asset('js/training.js') }}"></script>
  <script src="{{ asset('js/eligibility.js') }}"></script>
  <script src="{{ asset('js/dataprivacy.js') }}"></script>
  <script src="{{ asset('js/ppstlegend.js') }}"></script>
  <script src="{{ asset('js/indicators.js') }}"></script>

  <script src="{{ asset('js/mapping-sg.js') }}"></script>
  <script src="{{ asset('js/position-ranking.js') }}"></script>
  <script src="{{ asset('js/position-change.js') }}"></script>

  <script>
  function tryAutoEvaluate() {
      const remarks = [
          $('#education_remark').text(),
          $('#training_remark').text(),
          $('#experience_remark').text(),
          $('#eligibility_remark').text()
      ];

      if (remarks.every(r => r.includes('MET') || r.includes('NOT MET'))) {
          autoCheckQS();
      }
  }

  // call after each save
  $('#saveEducation, #saveTraining, #saveExperienceBtn, #saveEligibilityBtn')
      .on('click', () => setTimeout(tryAutoEvaluate, 500));
  </script>

  <script>
    $(document).ready(function() {

    // === SUCCESS / ERROR ALERTS (SERVER FEEDBACK) ===
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Saved Successfully!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
      });
    @endif

    @if ($errors->any())
      Swal.fire({
        icon: 'warning',
        title: 'Incomplete Form!',
        html: `
          <ul style="text-align:left;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        `,
        confirmButtonColor: '#d33'
      });
    @endif
  });
  </script>
  <script>
  /* ================================
    UTILITY FUNCTIONS
  ================================ */

  /* Return level display text */
  function getLevelDisplay(levelKey) {
      if (!levelKey) return '';
      if (levelKey === 'elementary' || levelKey === 'kindergarten') return 'Elementary';
      if (levelKey === 'junior_high') return 'Junior High';
      if (levelKey === 'senior_high') return 'Senior High';
      return '';
  }

  /* Determine selected level (FROM SCHOOL ONLY) */
  function getSelectedLevel() {
      let schoolLevel = $('#school_id').find(':selected').data('level');
      return schoolLevel ? schoolLevel : '';
  }

  /* Update top-right header */
  function updateHeaderForPosition() {
      let pos = $('#position_applied').val() ? $('#position_applied').val().trim() : '';
      let levelKey = getSelectedLevel();
      let levelText = getLevelDisplay(levelKey);

      if (pos) {
          $('#forPosition').text(
              levelText ? `For ${pos} (${levelText})` : `For ${pos}`
          );
      } else {
          $('#forPosition').text('For —');
      }
  }
  /* ================================
    QS LOADER (MANUAL ONLY)
  ================================ */
  function loadQS(position) {
      let level = getSelectedLevel();

      if (!position) {
          $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
          return;
      }

      showQSLoading(); // show loader

      $.ajax({
          url: '{{ route("get.qs") }}',
          type: 'GET',
          data: { position: position, level: level },
          success: function(response) {
              if (response.success) {
                  $('#qs_education').text(response.data.education);
                  $('#qs_training').text(response.data.training);
                  $('#qs_experience').text(response.data.experience);
                  $('#qs_eligibility').text(response.data.eligibility);
              } else {
                  $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
              }
          },
          error: function() {
              $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
          },
          complete: function() {
              hideQSLoading(5000); // 5000ms = 5 seconds delay
          }
      });
  }
  </script>

  <script>
  document.addEventListener('DOMContentLoaded', function () {

      let formChanged = false;

      const form = document.querySelector('form'); // or #createForm

      if (!form) return;

      // Detect ANY input change
      form.querySelectorAll('input, textarea, select').forEach(el => {
          el.addEventListener('change', () => formChanged = true);
          el.addEventListener('input', () => formChanged = true);
      });

      // Browser warning on refresh / close / back
      window.addEventListener('beforeunload', function (e) {
          if (!formChanged) return;

          e.preventDefault();
          e.returnValue = ''; // REQUIRED
      });

      // OPTIONAL: remove warning on successful submit
      form.addEventListener('submit', function () {
          formChanged = false;
      });

  });
  </script>
  <!-- JS for live update -->
  <script>
      const nameInput = document.getElementById('name');
      const teacherApplicant = document.getElementById('teacherApplicant');

      nameInput.addEventListener('input', function() {
          teacherApplicant.textContent = this.value;
      });
  </script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {

      function isFormFilled() {
          const name = document.getElementById('name').value.trim();
          const currentPosition = document.querySelector('select[name="current_position"]').value;
          const positionApplied = document.getElementById('position_applied').value;
          const itemNumber = document.querySelector('input[name="item_number"]').value.trim();
          const school = document.getElementById('school_id').value;
          const sgSalary = document.querySelector('input[name="sg_annual_salary"]').value;
          const levels = document.querySelectorAll('input[name="levels[]"]:checked');

          return name && currentPosition && positionApplied && itemNumber && school && sgSalary && levels.length > 0;
      }

      function handleModal(buttonClass, modalId) {
          const btn = document.querySelector(buttonClass);
          btn.addEventListener('click', function() {
              if(!isFormFilled()) {
                  Swal.fire({
                      icon: 'warning',
                      title: 'Oops!',
                      text: 'Please fill out all required fields in the form above before adding Qualifications Standard.',
                      confirmButtonText: 'OK',
                      timer: 5000
                  });
              } else {
                  const modal = new bootstrap.Modal(document.getElementById(modalId));
                  modal.show();
              }
          });
      }

      // Attach handlers for all four buttons
      handleModal('.add-education-btn', 'educationModal');
      handleModal('.add-training-btn', 'trainingModal');
      handleModal('.add-experience-btn', 'experienceModal');
      handleModal('.add-eligibility-btn', 'eligibilityModal');

  });
  </script>
  <script>
  $(document).ready(function() {
      // Auto-initialize experience if QS data is available
      if (window.requiredExperienceYears > 0) {
          if (typeof window.experienceModule !== 'undefined') {
              window.experienceModule.initializeExperienceFromQS(window.requiredExperienceYears);
          }
      }
  });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {

      lottie.loadAnimation({
          container: document.getElementById('qsLottie'),
          renderer: 'svg',
          loop: true,
          autoplay: true,
          path: 'https://assets9.lottiefiles.com/packages/lf20_usmfx6bp.json'
      });

  });

  function showQSLoading() {
      $('#qsLoadingOverlay').addClass('active');
  }

  function hideQSLoading(delay = 5000) { // default 5 seconds
      setTimeout(() => {
          $('#qsLoadingOverlay').removeClass('active');
      }, delay);
  }
  </script>
  <script>
  $(document).ready(function(){

      function loadPPST(position){

          if(!position){
              $('#ppst-container').html(
                  '<div class="text-muted text-center p-4">Select Position to load PPST</div>'
              );
              return;
          }

          $.ajax({
              url: "/load-ppst",
              type: "GET",
              data: { position: position },
              success: function(response){

                  $('#ppst-container').html(response);

              },
              error: function(){

                  $('#ppst-container').html(
                      '<div class="text-danger text-center p-4">Failed to load PPST</div>'
                  );

              }
          });

      }

      // auto load kapag may selected position
      let initialPosition = $('#position_applied').val();

      if(initialPosition){
          loadPPST(initialPosition);
      }

      // kapag nag change ang dropdown
      $('#position_applied').on('change', function(){

          let position = $(this).val();

          loadPPST(position);

      });

  });
  </script>
</body>
</html>

