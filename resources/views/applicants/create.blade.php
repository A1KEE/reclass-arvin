<head>
    <meta charset="utf-8">
    <title>Reclassification - Applicant Form</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <link rel="stylesheet" href="{{ asset('css/ipcrf.css') }}">


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

        <form id="applicantForm"
      action="{{ route('applicants.store') }}"
      method="POST"
      enctype="multipart/form-data">

            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name:</label>
                    <input name="name" id="name" class="form-control" placeholder="Ex.Juan D. Cruz" value="{{ old('name') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Current Position:</label>
                    <select name="current_position" class="form-select">
                        <option value="">-- Select Current Position --</option>
                        @foreach($positions as $p)
                            <option value="{{ $p }}" {{ old('current_position') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Position Applied:</label>
                    <select id="position_applied" name="position_applied" class="form-select">
                        <option value="">-- Select Position Applied --</option>
                        @foreach($positions as $p)
                            <option value="{{ $p }}" {{ old('position_applied') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
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
                    <select id="school_id" name="station_school_id" class="form-select">
                        <option value="">-- Select School --</option>

                        {{-- Kindergarten Schools --}}
    <optgroup label="Kindergarten Schools">
        @php
            $kindergarten = [
                'Canumay West Elementary School',
                                    'Dalandanan Elementary School',
                                    'Lingunan Elementary School',
                                    'Malinta Elementary School',
                                    'Roberta de Jesus Elementary School',
                                    'Canumay East Elementary School',
                                    'Lawang Bato Elementary School',
                                    'Punturin ES',
                                    'Disiplina Village Elementary School',
                                    'Pinalagad Elementary School',
                                    'Apolonio Casimiro Elementary School',
                                    'Antonio M. Serapio Elementary School',
                                    'Andres Mariano Elementary School',
                                    'Sitio Sto. Rosario Elementary School',
                                    'Maysan Elementary School',
                                    'Parada Elementary School',
                                    'Gen. T. de Leon Elementary School',
                                    'Apolonia F. Rafael Elementary School',
                                    'Paso de Blas Elementary School',
                                    'Silvestre Lazaro Elementary School',
                                    'Santos Encarnacion Elementary School',
                                    'Santiago A. de Guzman Elementary School',
                                    'Arcadio F. Deato Elementary School',
                                    'Andres Fernando Elementary School',
                                    'Coloong Elementary School',
                                    'Isla Elementary School',
                                    'P. R. Sandiego ES',
                                    'Paltok Elementary School',
                                    'Pasolo Elementary School',
                                    'Pio Valenzuela Elementary School',
                                    'Rincon Elementary School',
                                    'Tagalag Elementary School',
                                    'Luis Francisco Elementary School',
                                    'Wawang Pulo Elementary School',
                                    'Bitik Elementary School',
                                    'Caruhatan East Elementary School',
                                    'Caruhatan West Elementary School',
                                    'Constantino Elementary School',
                                    'Marulas Central School',
                                    'San Miguel Heights Elementary School',
                                    'Serrano Elementary School',
            ];
        @endphp
        @foreach($kindergarten as $school)
            <option value="{{ $school }}" data-level="kindergarten">{{ $school }}</option>
        @endforeach
    </optgroup>

                        <!-- Elementary -->
                        <optgroup label="Elementary Schools">
                            @php
                                $elementary = [
                                    'Canumay West Elementary School',
                                    'Dalandanan Elementary School',
                                    'Lingunan Elementary School',
                                    'Malinta Elementary School',
                                    'Roberta de Jesus Elementary School',
                                    'Canumay East Elementary School',
                                    'Lawang Bato Elementary School',
                                    'Punturin ES',
                                    'Disiplina Village Elementary School',
                                    'Pinalagad Elementary School',
                                    'Apolonio Casimiro Elementary School',
                                    'Antonio M. Serapio Elementary School',
                                    'Andres Mariano Elementary School',
                                    'Sitio Sto. Rosario Elementary School',
                                    'Maysan Elementary School',
                                    'Parada Elementary School',
                                    'Gen. T. de Leon Elementary School',
                                    'Apolonia F. Rafael Elementary School',
                                    'Paso de Blas Elementary School',
                                    'Silvestre Lazaro Elementary School',
                                    'Santos Encarnacion Elementary School',
                                    'Santiago A. de Guzman Elementary School',
                                    'Arcadio F. Deato Elementary School',
                                    'Andres Fernando Elementary School',
                                    'Coloong Elementary School',
                                    'Isla Elementary School',
                                    'P. R. Sandiego ES',
                                    'Paltok Elementary School',
                                    'Pasolo Elementary School',
                                    'Pio Valenzuela Elementary School',
                                    'Rincon Elementary School',
                                    'Tagalag Elementary School',
                                    'Luis Francisco Elementary School',
                                    'Wawang Pulo Elementary School',
                                    'Bitik Elementary School',
                                    'Caruhatan East Elementary School',
                                    'Caruhatan West Elementary School',
                                    'Constantino Elementary School',
                                    'Marulas Central School',
                                    'San Miguel Heights Elementary School',
                                    'Serrano Elementary School',
                                ];
                            @endphp
                            @foreach($elementary as $school)
                                <option value="{{ $school }}" data-level="elementary">{{ $school }}</option>
                            @endforeach
                        </optgroup>

                        <!-- Junior High -->
                       <optgroup label="Junior High Schools">
                            @php
                                $junior = [
                                    'Bagbaguin National High School-JHS',
                                    'Caruhatan National High School-JHS',
                                    'Gen. T. De Leon National High School-JHS',
                                    'Justice Eliezer Delos Santos National High School-JHS',
                                    'Mapulang Lupa National High School-JHS',
                                    'Maysan National High School-JHS',
                                    'Paso De Blas National High School-JHS',
                                    'Parada National High School-JHS',
                                    'Sitero Francisco Memorial National High School-JHS',
                                    'Valenzuela National High School-JHS',
                                    'Arkong Bato National High School-JHS',
                                    'Dalandanan National High School-JHS',
                                    'Malanday National High School-JHS',
                                    'Malinta National High School-JHS',
                                    'Polo National High School-JHS',
                                    'Valenzuela City School of Mathematics and Science-JHS',
                                    'Veinte Reales National High School-JHS',
                                    'Wawangpulo National High School-JHS',
                                    'Bignay National High School-JHS',
                                    'Canumay East National High School-JHS',
                                    'Canumay West National High School-JHS',
                                    'Disiplina Village - Bignay National High School-JHS',
                                    'Lawang Bato National High School-JHS',
                                    'Lingunan National High School-JHS',
                                    'Vicente P. Trinidad National High School-JHS'
                                ];
                            @endphp

                            @foreach($junior as $school)
                                <option value="{{ $school }}" data-level="junior_high">{{ $school }}</option>
                            @endforeach
                        </optgroup>


                        <!-- Senior High -->
                        <optgroup label="Senior High Schools">
                            @php
                                $senior = [
                                    'Arkong Bato National High School-SHS',
                                    'Bignay National High School-SHS',
                                    'Dalandanan National High School-SHS',
                                    'Lawang Bato National High School-SHS',
                                    'Lingunan National High School-SHS',
                                    'Malanday National High School-SHS',
                                    'Malinta National High School-SHS',
                                    'Polo National High School-SHS',
                                    'Punturin National High School-SHS',
                                    'Valenzuela City School of Mathematics and Science-SHS',
                                    'Vicente P. Trinidad National High School-SHS',
                                    'Wawang Pulo National High School-SHS',
                                    'Caruhatan National High School-SHS',
                                    'Gen. T. De Leon National High School-SHS',
                                    'Maysan National High School-SHS',
                                    'Ugong SHS',
                                    'Mapulang Lupa National High School-SHS',
                                    'Parada National High School-SHS',
                                    'Paso De Blas National High School-SHS',
                                    'Sitero Francisco Memorial National High School-SHS',
                                    'Valenzuela National High School-SHS'
                                ];
                            @endphp
                            @foreach($senior as $school)
                                <option value="{{ $school }}" data-level="senior_high">{{ $school }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">SG/Annual Salary:</label>
                <input 
                    type="number" 
                    name="sg_annual_salary" 
                    class="form-control" 
                    value="{{ old('sg_annual_salary') }}" 
                    placeholder="Enter SG or Annual Salary">
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
</tr>
       <tr>
           <td>Training</td>
           <td id="qs_training">—</td>
           <td>
               <button type="button"
                       class="btn btn-sm btn-primary add-training-btn">
                   ➕ Add Trainings
               </button>

               <div id="training_summary" class="mt-2 small text-muted">
                   No trainings added.
               </div>
           </td>
           <td id="training_remark"><span class="text-muted">Waiting for the QS</span></td>
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

    {{-- ========================================================= --}}
{{-- III. SUMMARY OF THE ACHIEVEMENT OF PPST INDICATORS --}}
{{-- ========================================================= --}}
<hr class="mt-4 mb-4">
<h5 class="fw-bold text-uppercase" id="ppst-summary">Summary of the Achievement of PPST Indicators</h5>

<p class="text-muted mb-3 fst-italic">
  *Put a (/) mark if the applicant meets the required PPST indicators; if not, put an (X) mark in both the "O" and "VS" columns.
</p>


<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle">
    <thead class="text-center">
      {{-- ============ Domain 1 ============ --}}
      <tr>
        <th colspan="2">Domain/Strand/Indicators</th>
        <th style="width:10%">O</th>
        <th style="width:10%">VS</th>
      </tr>
      <tr>
        <th style="width:8%">No.</th>
        <th>Domain 1. Content Knowledge and Pedagogy</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>

      
      @php
      $domain1 = [
        '1.1.2 Apply knowledge of content within and across curriculum teaching areas.',
        '1.2.2 Use research-based knowledge and principles of teaching and learning to enhance professional practice.',
        '1.3.2 Ensure the positive use of ICT to facilitate the teaching and learning process.',
        '1.4.2 Use a range of teaching strategies that enhance learner achievement in literacy and numeracy skills.',
        '1.5.2 Apply a range of teaching strategies to develop critical and creative thinking, as well as other higher-order thinking skills.',
        '1.6.2 Display proficient use of Mother Tongue, Filipino and English to facilitate teaching and learning.',
        '1.7.2 Use effective verbal and non-verbal classroom communication strategies to support learner understanding, participation, engagement and achievement.',
      ];
      @endphp
      @foreach ($domain1 as $i => $indicator)
      <tr>
        <td class="text-center">{{ $i+1 }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $i+1 }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $i+1 }}][VS]" value="1"></td>
      </tr>
      @endforeach

     {{-- ============ Domain 2 ============ --}}
<tr class="fw-semibold">
  <th></th>
  <td colspan="3">Domain 2. Learning Environment</td>
</tr>

@php
  $domain2 = [
    '2.1.2 Establish safe and secure learning environments to enhance learning through the consistent implementation of policies, guidelines and procedures.',
    '2.2.2 Maintain learning environments that promote fairness, respect and care to encourage learning.',
    '2.3.2 Manage classroom structure to engage learners, individually or in groups, in meaningful exploration, discovery and hands-on activities within a range of physical learning environments.',
    '2.4.2 Maintain supportive learning environments that nurture and inspire learners to participate, cooperate and collaborate in continued learning.',
    '2.5.2 Apply a range of successful strategies that maintain learning environments that motivate learners to work productively by assuming responsibility for their own learning.',
    '2.6.2 Manage learner behavior constructively by applying positive and non-violent discipline to ensure learning-focused environments.',
  ];
@endphp

@foreach ($domain2 as $i => $indicator)
  @php 
    $num = $i + 8;  /* 8–13 */
    $isCOI = in_array($num, [1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,23,24]);
    $rowClass = $isCOI ? 'row-coi' : 'row-ncoi';
  @endphp

  <tr class="{{ $rowClass }}">
    <td class="text-center">{{ $num }}</td>
    <td>{{ $indicator }}</td>
    <td class="text-center">
      <input type="checkbox" name="ppst[{{ $num }}][O]" value="1">
    </td>
    <td class="text-center">
      <input type="checkbox" name="ppst[{{ $num }}][VS]" value="1">
    </td>
  </tr>
@endforeach



      {{-- ============ Domain 3 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 3. Diversity of Learners</td>
      </tr>
      @php
      $domain3 = [
        '3.1.2 Use differentiated, developmentally appropriate learning experiences to address learners’ gender, needs, strengths, interests and experiences.',
        '3.2.2 Establish a learner-centered culture by using teaching strategies that respond to learners’ linguistic, cultural, socio-economic and religious backgrounds.',
        '3.3.2 Design, adapt and implement teaching strategies that are responsive to learners with disabilities, giftedness and talents.',
        '3.4.2 Plan and deliver teaching strategies that are responsive to the special educational needs of learners in difficult circumstances, including: geographic isolation; chronic illness; displacement due to armed conflict, urban resettlement or disasters; child abuse and child labor practices. ',
        '3.5.2 Adapt and use culturally appropriate teaching strategies to address the needs of learners from indigenous groups.',
      ];
      @endphp
      @foreach ($domain3 as $i => $indicator)
      @php $num = $i + 14; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 4 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 4. Curriculum and Planning</td>
      </tr>
      @php
      $domain4 = [
        '4.1.2 Plan, manage and implement developmentally sequenced teaching and learning processes to meet curriculum requirements and varied teaching contexts.',
        '4.2.2 Set achievable and appropriate learning outcomes that are aligned with learning competencies.',
        '4.3.2 Adapt and implement learning programs that ensure relevance and responsiveness to the needs of all learners. ',
        '4.4.2 Participate in collegial discussions that use teacher and learner feedback to enrich teaching practice.',
        '4.5.2 Select, develop, organize and use appropriate teaching and learning resources, including ICT, to address learning goals.',
      ];
      @endphp
      @foreach ($domain4 as $i => $indicator)
      @php $num = $i + 19; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 5 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 5.  Assessment and Reporting</td>
      </tr>
      @php
      $domain5 = [
        '5.1.2.  Design, select, organize and use diagnostic, formative, and summative assessment strategies consistent with curriculum requirements',
        '5.2.2 Monitor and evaluate learner progress and achievement using learner attainment data.',
        '5.3.2 Use strategies for providing timely, accurate and constructive feedback to improve learner performance. ',
        '5.4.2 Communicate promptly and clearly the learners’ needs, progress and achievement to key stakeholders, including parents/guardians.',
        '5.5.2 Utilize assessment data to inform the modification of teaching and learning practices and programs.',
      ];
      @endphp
      @foreach ($domain5 as $i => $indicator)
      @php $num = $i + 24; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 6 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 6. Community Linkages and Professional Engagement</td>
      </tr>
      @php
      $domain6 = [
        '6.1.2 Maintain learning environments that are responsive to community contexts.',
        '6.2.2 Build relationships with parents/guardians and the wider school community to facilitate involvement in the educative process.',
        '6.3.2 Review regularly personal teaching practice using existing laws and regulations that apply to the teaching profession and the responsibilities specified in the Code of Ethics for Professional Teachers.',
        '6.4.2 Comply with and implement school policies and procedures consistently to foster harmonious relationships with learners, parents, and other stakeholders.',
      ];
      @endphp
      @foreach ($domain6 as $i => $indicator)
      @php $num = $i + 29; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 7 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 7. Personal Growth and Professional Development</td>
      </tr>
      @php
      $domain7 = [
        '7.1.2 Apply a personal philosophy of teaching that is learner-centered.',
        '7.2.2 Adopt practices that uphold the dignity of teaching as a profession by exhibiting qualities such as caring attitude, respect and integrity.',
        '7.3.2 Participate in professional networks to share knowledge and to enhance practice.',
        '7.4.2 Develop a personal professional improvement plan based on reflection of one’s practice and ongoing professional learning.',
        '7.5.2 Set professional development goals based on the Philippine Professional Standards for Teachers.',
      ];
      @endphp
      @foreach ($domain7 as $i => $indicator)
      @php $num = $i + 33; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ======= Totals OF O AND VS ======= --}}
      <!-- TOTAL COI -->
<tr class="fw-semibold">
  <td colspan="2" class="text-center">Total Number of COI with Outstanding</td>
  <td colspan="2">
    <input type="number" id="totalCOI" name="total_coi" readonly
           class="form-control form-control-sm d-inline-block text-center"
           style="width:80px; font-weight:normal; font-size:0.9rem">
  </td>
</tr>

<!-- TOTAL NCOI -->
<tr class="fw-semibold">
  <td colspan="2" class="text-center">Total Number of NCOI with Outstanding</td>
  <td colspan="2">
    <input type="number" id="totalNCOI" name="total_ncoi" readonly
           class="form-control form-control-sm d-inline-block text-center"
           style="width:80px; font-weight:normal; font-size:0.9rem">
  </td>
</tr>
    </tbody>
  </table>
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
        <th>Total Score</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="number" name="comparative[education]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="number" name="comparative[training]" class="form-control form-control-sm text-center" readonly></td>
        <td><input type="number" name="comparative[experience]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[performance]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[classroom]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[non_classroom]" class="form-control form-control-sm text-center"></td>
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
        <td><input type="text" name="division[from_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[from_grade]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[to_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[to_grade]" class="form-control form-control-sm"></td>
        <td><input type="date" name="division[date_processed]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[remarks]" class="form-control form-control-sm"></td>
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
    <p class="small mb-0">Administrative Officer IV</p>
  </div>
</div>

<!-- ROW 2 : Certified (Left) -->
<div class="row mb-4">
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">CARMELITA D. MATUS</p>
    <p class="small mb-0">Administrative Officer V</p>
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
    <p class="fw-bold text-decoration-underline mb-1">Teachers Credential Evaluator</p>
  </div>
</div>

<!-- ROW 2 : Certified (Left) -->
<div class="row mb-4">
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">Chief, Administrative Division</p>
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
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">Add Education</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="card shadow-sm p-3">
          <div class="row g-3">

            <!-- Degree / Education -->
            <div class="col-md-6">
              <label class="fw-bold">Degree / Education</label>
              <input type="text" id="education_name" class="form-control" placeholder="Enter degree or qualification" required>
            </div>

            <!-- Highest Educational Attainment -->
            <div class="col-md-6 position-relative">
              <label for="education_units_select" class="fw-bold">Highest Educational Attainment</label>
              <select id="education_units_select" class="form-select">
                <option value="">Select Education Level</option>
                <!-- Options populated by JS -->
              </select>

              <!-- Hidden input for "Others" units -->
              <input type="number" id="education_units_other" class="form-control mt-2 d-none" placeholder="Enter custom units (e.g., 32)">
              
              <small class="text-muted d-block mt-1">CSC Qualification Level (0–31)</small>
            </div>

            <!-- Certificate Upload -->
            <div class="mt-3">
              <label class="fw-bold">Certificate (PDF)</label>
              <input type="file" id="education_file" class="form-control" accept="application/pdf">
              <div id="education_preview" class="mt-1 small text-muted">No file uploaded.</div>
            </div>

          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer d-flex justify-content-end">
        <button type="button" class="btn btn-success" id="saveEducation">
          <i class="bi bi-check-circle me-1"></i> Save Education
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancel </button>
      </div>

    </div>
  </div>
</div>


<!-- TRAINING MODAL -->
<div class="modal fade" id="trainingModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">Training / Seminars Attended</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">

        <div id="trainingContainer">
          <div class="training-item card shadow-sm mb-3 p-3 position-relative">
            <button type="button" class="btn btn-sm btn-outline-danger remove-training position-absolute top-0 end-0 m-2"
                    style="font-size:0.85rem; padding:0.2rem 0.4rem;">✖</button>

            <div class="row g-3">
              <div class="col-md-4">
                <label class="fw-bold">Training Title</label>
                <input type="text" name="trainings[0][title]" class="form-control" placeholder="Enter title" required>
              </div>

              <div class="col-md-4">
                <label class="fw-bold">Training Type</label>
                <select name="trainings[0][type]" class="form-select training_type" required>
                  <option value="">Select Type</option>
                  <option value="Face-to-Face">Face-to-Face</option>
                  <option value="Online">Online</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="fw-bold">No. of Hours</label>
                <input type="number" name="trainings[0][hours]" class="form-control training_hours" readonly>
                <div class="form-text text-muted">Automatically computed from start and end dates.</div>
              </div>
            </div>

            <div class="row g-3 mt-2">
              <div class="col-md-6">
                <label class="fw-bold">Start Date</label>
                <input type="date" name="trainings[0][start_date]" class="form-control training_date" required>
              </div>
              <div class="col-md-6">
                <label class="fw-bold">End Date</label>
                <input type="date" name="trainings[0][end_date]" class="form-control training_date" required>
              </div>
            </div>

            <div class="mt-2">
              <label class="fw-bold">Certificate (PDF)</label>
              <input type="file" name="trainings[0][file]" class="form-control training_file" accept="application/pdf" required>
            </div>
          </div>
        </div>

        <!-- ADD TRAINING BUTTON -->
        <div class="mb-3 mt-2">
          <button type="button" class="btn btn-outline-primary btn-sm" id="addTraining">
            ➕ Add Another Training
          </button>
        </div>

        <!-- DETAILED SUMMARY (NEW SECTION) -->
        <div id="modal_training_summary" class="mt-3">
          <div class="alert alert-info p-2">
            <strong>Training Summary</strong><br>
            Required Hours: <span id="required_hours_display">0</span> hours<br>
            Required Level: <span id="required_level_display">0</span><br>
            Status: <span class="text-muted">No trainings added</span><br>
            <strong>Points: 0</strong>
          </div>
        </div>

        <!-- SIMPLE SUMMARY -->
        <div class="mt-4 card p-3 shadow-sm">
          <div class="row">
            <div class="col-md-6">
              <strong>Total Hours:</strong> <span id="total_training_hours">0</span>
            </div>
            <div class="col-md-6">
              <strong>Status:</strong> <span id="training_remark" class="text-muted">Waiting for the QS</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer (Buttons switched) -->
      <div class="modal-footer d-flex justify-content-end">
        <button type="button" class="btn btn-success" id="saveTraining">
          <i class="bi bi-check-circle me-1"></i> Save Trainings
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
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">Add Work Experience</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">

        <!-- ALERT FOR REQUIRED EXPERIENCE -->
        <div class="alert alert-info text-center">
          <span id="expRequirementText">Required Experience: —</span>
        </div>

        <!-- EXPERIENCE CONTAINER (Two-Column Card Layout) -->
        <div id="experienceContainer" class="mb-3 row g-3"></div>

        <!-- ADD EXPERIENCE BUTTON -->
        <div class="mb-3">
          <button type="button" class="btn btn-outline-primary btn-sm" id="addExperience">
            ➕ Add Another Experience
          </button>
        </div>

        <!-- SUMMARY DISPLAY -->
        <div class="mt-4 card p-3 shadow-sm">
          <div class="row">
            <div class="col-md-3">
              <strong>Total Years:</strong><br>
              <span id="totalExperienceYears" class="h4">0.00</span>
            </div>
            <div class="col-md-3">
              <strong>Increment Level:</strong><br>
              <span id="incrementLevel" class="h4">0</span>
            </div>
            <div class="col-md-6">
              <strong>Range:</strong><br>
              <span id="experienceRange">—</span><br>
              <div id="experience_remark" class="mt-1 text-muted">Waiting for QS</div>
            </div>
          </div>
        </div>

        <!-- FINAL SUMMARY (Will be filled after save) -->
        <div id="experience_summary" class="mt-3"></div>

        <!-- HIDDEN APPLIED POSITION / LEVEL FOR QS REFERENCE -->
        <input type="hidden" id="appliedLevel" value="{{ $selectedLevel ?? 'kindergarten' }}">
        <input type="hidden" id="appliedPosition" value="{{ $selectedPosition ?? 'Teacher III' }}">

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="saveExperienceBtn">
          <i class="bi bi-check-circle me-1"></i> Save Experiences
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

 <!-- ELIGIBILITY MODAL -->
<div class="modal fade" id="eligibilityModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Add Eligibility</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">

        <!-- QS Requirement -->
        <div class="alert alert-info text-center">
          <span id="eligibilityRequirementText">Required Eligibility: —</span>
        </div>

        <div class="row g-3">
          <!-- Eligibility Dropdown -->
          <div class="col-12 col-md-6">
            <div class="card p-3 shadow-sm h-100">
              <label class="form-label fw-bold">Eligibility Name</label>
              <select id="eligibilityInput" class="form-select">
                <option value="">Select Eligibility</option>
                <option value="LET">LET</option>
                <option value="PBET">PBET</option>
                <option value="MAGNA CARTA">MAGNA CARTA</option>
              </select>
              <small class="form-text text-muted">
                Please select the eligibility as required by the QS above.
              </small>
            </div>
          </div>

          <!-- ID Expiry -->
          <div class="col-12 col-md-6">
            <div class="card p-3 shadow-sm h-100">
              <label class="form-label fw-bold">ID Expiry Date</label>
              <input type="date" id="eligibilityExpiry" class="form-control">
              <small class="form-text text-muted">Enter the expiry date of your eligibility ID.</small>
            </div>
          </div>

          <!-- Attachment -->
          <div class="col-12">
            <div class="card p-3 shadow-sm">
              <label class="form-label fw-bold">Attachment (PDF, Word, Image)</label>
              <input type="file" id="eligibilityAttachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
              <small class="form-text text-muted">
                Attach a scanned copy or photo of your ID. If recently renewed, attach the official receipt or renewed ID.
              </small>
            </div>
          </div>
        </div>

        <!-- Summary -->
        <!-- <div class="mt-4 card p-3 shadow-sm">
          <strong>Summary:</strong>
          <div id="eligibility_summary" class="mt-2"></div>
          <div id="eligibility_remark" class="mt-1 text-muted">Waiting for the QS</div>
        </div> -->

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
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload IPCRF Files</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p id="ipcrfInstruction" class="text-muted mb-3"></p>
        <div class="row g-3" id="ipcrfContainer"></div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveIpcrfBtn">Save</button>
      </div>
    </div>
  </div>
</div>


<!-- SCRIPTS - UPDATED VERSION -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    
    // Other globals
    window.requiredExperienceYears = {{ $requiredYears ?? 0 }};
    window.qsEducationUnits = @json($qsUnits ?? []);
    window.requiredHours = {{ $requiredHours ?? 0 }};
</script>

<!-- LOAD JS FILES -->
<script src="{{ asset('js/experience.js') }}"></script>
<script src="{{ asset('js/education-points.js') }}"></script>
<script src="{{ asset('js/training.js') }}"></script>

<script>
let uploadedIPCRFs = [];
let savedIPCRFs = [];

// ---------- IPCRF FUNCTIONS ----------
const ipcrfContainer = document.getElementById('ipcrfContainer');
const ipcrfInstruction = document.getElementById('ipcrfInstruction');

function getRequiredIPCRFs(position) {
  position = position.trim().toLowerCase();
  if (position === 'teacher i' || position === 'teacher ii') return 2;
  return 3;
}

function renderIPCRFBoxes() {
  const position = document.getElementById('position_applied')?.value || 'Teacher II';
  const required = getRequiredIPCRFs(position);

  ipcrfInstruction.textContent = `Please upload ${required} IPCRF file${required > 1 ? 's' : ''}.`;

  ipcrfContainer.innerHTML = '';
  uploadedIPCRFs = savedIPCRFs.length ? [...savedIPCRFs] : Array(required).fill(null);

  for (let i = 0; i < required; i++) {
    const col = document.createElement('div');
    col.className = 'col-md-4';

    col.innerHTML = `
      <div class="card p-3 border-dashed h-100 text-center" style="cursor:pointer;">
        <i class="bi bi-file-earmark-pdf-fill ipcrf-icon"></i>
        <strong>IPCRF ${i + 1}</strong>
        <input type="file" class="d-none" id="ipcrf_file${i}" accept=".pdf">
        <div class="mt-2" id="preview${i}">
          <small class="text-muted">Drop or click to upload</small>
        </div>
        <div class="mt-2 d-flex justify-content-center gap-2">
          <button class="btn btn-sm btn-outline-primary d-none" id="view${i}">View</button>
          <button class="btn btn-sm btn-outline-danger d-none" id="remove${i}">Remove</button>
        </div>
      </div>
    `;

    ipcrfContainer.appendChild(col);

    const card = col.querySelector('.card');
    const input = col.querySelector('input');
    const previewEl = col.querySelector(`#preview${i} small`);
    const viewBtn = col.querySelector(`#view${i}`);
    const removeBtn = col.querySelector(`#remove${i}`);

    // If file already uploaded
    if (uploadedIPCRFs[i]) {
      previewEl.textContent = uploadedIPCRFs[i].name;
      previewEl.classList.remove('text-muted');
      viewBtn.classList.remove('d-none');
      removeBtn.classList.remove('d-none');
    }

    // Click card → open file dialog
    card.addEventListener('click', () => input.click());

    // File selected
    input.addEventListener('change', e => {
      const file = e.target.files[0];
      if (!file) return;
      uploadedIPCRFs[i] = file;
      previewEl.textContent = file.name;
      previewEl.classList.remove('text-muted');
      viewBtn.classList.remove('d-none');
      removeBtn.classList.remove('d-none');
    });

    // View button
    viewBtn.onclick = e => {
      e.stopPropagation();
      const file = uploadedIPCRFs[i];
      if (file) window.open(URL.createObjectURL(file), '_blank');
    };

    // Remove button
    removeBtn.onclick = e => {
      e.stopPropagation();
      uploadedIPCRFs[i] = null;
      previewEl.textContent = 'Drop or click to upload';
      previewEl.classList.add('text-muted');
      viewBtn.classList.add('d-none');
      removeBtn.classList.add('d-none');
    };
  }
}

// ---------- Save IPCRFs ----------
document.getElementById('saveIpcrfBtn').addEventListener('click', () => {
  const position = document.getElementById('position_applied')?.value || 'Teacher II';
  const required = getRequiredIPCRFs(position);

  if (uploadedIPCRFs.filter(f => f).length < required) {
    Swal.fire('Missing file', `Please upload all ${required} required IPCRFs.`, 'warning');
    return;
  }

  savedIPCRFs = [...uploadedIPCRFs];

  const status = document.getElementById('ipcrfStatus');
  status.classList.remove('d-none');
  status.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Uploaded`;

  Swal.fire({ icon: 'success', title: 'IPCRFs Saved', timer: 1200, showConfirmButton: false }).then(() => {
    bootstrap.Modal.getInstance(document.getElementById('ipcrfModal')).hide();

    // === SHOW PERFORMANCE REQUIREMENTS AFTER ALL MET ===
    const perfDiv = document.getElementById('performanceRequirements');
    if (perfDiv) perfDiv.style.display = 'block';
    // Scroll to the section for user convenience
    perfDiv.scrollIntoView({ behavior: 'smooth' });
  });
});

// ---------- Reset on Position Change ----------
$(document).ready(function () {
  $('#position_applied').on('change', function () {

    // Reset all QS fields (education, training, experience, eligibility)
    $('#education_name').val('');
    $('#education_units_select').val('');
    $('#education_units_other').val('').addClass('d-none');
    $('#education_file').val('');
    $('#education_preview').html('No file uploaded.');
    $('#education_summary').html('<span class="text-muted">No education added.</span>');
    $('#education_remark').html('<span class="text-muted">Waiting for QS</span>');

    if (typeof trainingIndex !== 'undefined') trainingIndex = 1;
    $('#trainingContainer').empty();
    $('#training_summary').html('<span class="text-muted">No trainings added.</span>');
    $('#total_training_hours').text('0');
    $('#training_remark').html('<span class="text-muted">Waiting for QS</span>');

    if (typeof resetExperience === 'function') resetExperience();
    else {
      $('#experience_summary').html('<span class="text-muted">No experience added.</span>');
      $('#experience_remark').html('<span class="text-muted">Waiting for QS</span>');
    }

    if (typeof resetEligibility === 'function') resetEligibility();
    else {
      $('#eligibility_summary').html('<span class="text-muted">No eligibility added.</span>');
      $('#eligibility_remark').html('<span class="text-muted">Waiting for QS</span>');
    }

    // Reset IPCRFs
    uploadedIPCRFs = [];
    savedIPCRFs = [];
    $('#ipcrfStatus').addClass('d-none').html('');

    // Hide performance requirements again
    $('#performanceRequirements').hide();

    Swal.fire({
      icon: 'info',
      title: 'Position Changed',
      text: 'All qualifications were reset because QS changed.',
      toast: true,
      position: 'top-end',
      timer: 3000,
      showConfirmButton: false
    });
  });
});

// ---------- Show Modal ----------
document.getElementById('ipcrfModal')
  .addEventListener('show.bs.modal', renderIPCRFBoxes);

// ---------- AUTO CHECK QS (existing) ----------
function autoCheckQS() {

  Swal.fire({
    title: 'Evaluating Qualifications',
    html: `<p>Please wait while we check your records<span id="dots"></span></p>`,
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      const dotsEl = document.getElementById('dots');
      let dots = '';
      let interval = setInterval(() => {
        dots = dots.length < 3 ? dots + '.' : '';
        dotsEl.textContent = dots;
      }, 500);

      // Simulate 7 seconds evaluation
      setTimeout(() => {
        clearInterval(interval);
        Swal.close();

        // === CHECK REMARKS ===
        const educationRemark  = document.getElementById('education_remark')?.innerText || '';
        const trainingRemark   = document.getElementById('training_remark')?.innerText || '';
        const experienceRemark = document.getElementById('experience_remark')?.innerText || '';
        const eligibilityRemark= document.getElementById('eligibility_remark')?.innerText || '';

        const remarks = [educationRemark, trainingRemark, experienceRemark, eligibilityRemark];
        const anyNotMet = remarks.some(r => r.toLowerCase().includes('not met'));

        // === ALL MET ===
        if (!anyNotMet) {
          Swal.fire({
            icon: 'success',
            title: 'All Requirements MET',
            text: 'You are eligible to proceed to the next step.',
            confirmButtonText: 'Continue'
          }).then(() => {
            document.getElementById('submitApplication')?.removeAttribute('disabled');

            // === SHOW PERFORMANCE REQUIREMENTS ===
            const perfDiv = document.getElementById('performanceRequirements');
            if (perfDiv) perfDiv.style.display = 'block';
            perfDiv.scrollIntoView({ behavior: 'smooth' });

            const ipcrfModalEl = document.getElementById('ipcrfModal');
            if (ipcrfModalEl) new bootstrap.Modal(ipcrfModalEl).show();
          });
          return;
        }

        // === NOT MET ===
        Swal.fire({
          icon: 'info',
          title: 'Some Requirements NOT MET',
          html: `
            <p>Some of your qualifications did not meet the standards.</p>
            <p>Please enter your email for notification.</p>
            <input type="email" id="depedEmail" class="swal2-input" placeholder="Your Email">
          `,
          showCancelButton: true,
          confirmButtonText: 'Submit & Notify',
          cancelButtonText: 'Review'
        }).then((result) => {
          if (!result.isConfirmed) return;
          const email = document.getElementById('depedEmail').value;
          if (!email) return;

          fetch('/notify-unqualified', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email, remarks })
          })
          .then(res => res.json())
          .then(res => {
            if (!res.success) throw new Error();
            Swal.fire({
              icon: 'success',
              title: 'Email Sent!',
              text: `A formal notice has been sent to ${email}`,
              timer: 2500,
              showConfirmButton: false
            }).then(() => {
              document.getElementById('applicantForm')?.reset();
              $('#performanceRequirements').hide(); // hide again after reset
            });
          })
          .catch(() => Swal.fire('Error', 'Failed to send email.', 'error'));
        });

      }, 7000);
    }
  });
}
</script>
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
let eligibilityRequired = '';
let eligibilitySaved = false;

/* ==========================
   LOAD QS ELIGIBILITY
========================== */
function loadEligibilityQS() {
    const position = document.getElementById('appliedPosition').value;

    // Load requirement from QS data
    eligibilityRequired = qsData[position]?.eligibility || '—';
    document.getElementById('eligibilityRequirementText').innerText =
        `Required Eligibility: ${eligibilityRequired}`;

    // Reset remark if not saved
    if (!eligibilitySaved) {
        document.getElementById('eligibility_remark').innerHTML =
            '<span class="text-muted">Waiting for the QS</span>';
    }
}

/* ==========================
   SAVE ELIGIBILITY
========================== */
function saveEligibility() {
    const name = document.getElementById('eligibilityInput').value.trim();
    const expiry = document.getElementById('eligibilityExpiry').value;
    const file = document.getElementById('eligibilityAttachment').files[0];

    if (!name || !expiry) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete',
            text: 'Eligibility name and expiry date are required.'
        });
        return;
    }

    const today = new Date();
    const expiryDate = new Date(expiry);
    let status = '';

    // Determine MET / NOT MET
    if (expiryDate >= today) {
        status = 'MET';
    } else if (file) {
        status = 'MET';
    } else {
        status = 'NOT MET';
    }

    // Update summary
    let summary = `<strong>${name}</strong><br>`;
    summary += `<small>Valid Until: ${expiry}</small>`;
    if (file) summary += `<br><small>Attachment: ${file.name}</small>`;
    document.getElementById('eligibility_summary').innerHTML = summary;

    // Update remark
    document.getElementById('eligibility_remark').innerHTML =
        `<span class="${status.startsWith('MET') ? 'text-success' : 'text-danger'} fw-bold">${status}</span>`;

    eligibilitySaved = true;

    Swal.fire({
        icon: 'success',
        title: 'Eligibility Saved',
        text: `Eligibility status: ${status}`,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2200
    });

    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('eligibilityModal')).hide();
}

/* ==========================
   RESET ELIGIBILITY
========================== */
function resetEligibility() {
    document.getElementById('eligibilityInput').value = '';
    document.getElementById('eligibilityExpiry').value = '';
    document.getElementById('eligibilityAttachment').value = '';

    document.getElementById('eligibility_summary').innerHTML = '';
    document.getElementById('eligibility_remark').innerHTML =
        '<span class="text-muted">Waiting for the QS</span>';

    eligibilitySaved = false;
}

/* ==========================
   RESET WHEN POSITION CHANGES
========================== */
$('#position_applied').on('change', function () {
    $('#appliedPosition').val($(this).val());
    resetEligibility();

    Swal.fire({
        icon: 'info',
        title: 'Position Changed',
        text: 'Eligibility has been reset.',
        timer: 1500,
        showConfirmButton: false
    });
});

/* ==========================
   LOAD QS ON MODAL OPEN
========================== */
document.getElementById('eligibilityModal')
    .addEventListener('show.bs.modal', loadEligibilityQS);

/* ==========================
   FILE UPLOAD TOAST
========================== */
$('#eligibilityAttachment').on('change', function () {
    const file = this.files[0];
    if (!file) return;

    Swal.fire({
        icon: 'success',
        title: 'File Selected',
        text: file.name,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    });
});
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

/* Highlight Performance Requirements Row */
function highlightPerformanceRow() {
    let selected = $('#position_applied').val() ? $('#position_applied').val().trim() : '';
    const $rows = $('#performanceTable tbody tr');

    $rows.removeClass('highlight-row');

    if (!selected) return;

    $rows.each(function() {
        const dp = $(this).data('position');
        if (dp && dp.toString().trim() === selected) {
            $(this).addClass('highlight-row');
        }
    });
}

/* ================================
   DOCUMENT READY
================================ */
$(document).ready(function() {

    const $levelCheckboxes = $('input[name="levels[]"]');
    const $schoolSelect = $('#school_id');

    // Ensure all unchecked initially
    $levelCheckboxes.prop('checked', false);

    /* BLOCK MANUAL CLICKING + SWEETALERT */
    $levelCheckboxes.on('click', function(e) {

        // If no school selected yet
        if (!$schoolSelect.val()) {
            e.preventDefault();

            Swal.fire({
                icon: 'info',
                title: 'Select School First',
                text: 'Please select a school/station to automatically determine the level.',
                confirmButtonColor: '#3085d6'
            });

            return false;
        }

        // Even if school is selected, still block manual checking
        e.preventDefault();
        return false;
    });

    /* SCHOOL CHANGE → AUTO-CHECK LEVEL */
    $('#school_id').on('change', function() {
        let level = $(this).find(':selected').data('level');

        // Reset all
        $levelCheckboxes.prop('checked', false);

        // Auto-check ONLY by system
        if (level) {
            $(`input[name="levels[]"][value="${level}"]`).prop('checked', true);
        }

        updateHeaderForPosition();
        highlightPerformanceRow();

        // Refresh QS
        $('#position_applied').trigger('change');
    });

    /* POSITION CHANGE → AJAX QS */
    $('#position_applied').on('change', function() {
        let position = $(this).val();
        let level = getSelectedLevel();

        if (position) {
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
                }
            });
        } else {
            $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
        }

        updateHeaderForPosition();
        highlightPerformanceRow();
    });

    // Initial load
    updateHeaderForPosition();
    highlightPerformanceRow();

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

  // ===============================
  // CHECKBOX REFERENCES
  // ===============================
  const oBoxes  = document.querySelectorAll('input[name^="ppst"][name$="[O]"]');
  const vsBoxes = document.querySelectorAll('input[name^="ppst"][name$="[VS]"]');

  const totalCOI  = document.getElementById("totalCOI");
  const totalNCOI = document.getElementById("totalNCOI");

  // ===============================
  // COI / NCOI REFERENCES
  // ===============================
  const coiNumbers  = [1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,23,24];
  const ncoiNumbers = [2,20,21,22,25,26,27,28,29,30,31,32,33,34,35,36,37];

  // ===============================
  // APPLY ROW COLOR (NO CHECK NEEDED)
  // ===============================
  document.querySelectorAll("table tbody tr").forEach(row => {
    const firstCell = row.querySelector("td:first-child");
    if (!firstCell) return;

    const num = parseInt(firstCell.innerText.trim(), 10);
    if (isNaN(num)) return;

    if (coiNumbers.includes(num)) row.classList.add("row-coi");
    if (ncoiNumbers.includes(num)) row.classList.add("row-ncoi");
  });

  // ===============================
  // O vs VS LOCKING (MUTUAL EXCLUSIVE)
  // ===============================
  function syncOVS(num) {
    const o  = document.querySelector(`input[name='ppst[${num}][O]']`);
    const vs = document.querySelector(`input[name='ppst[${num}][VS]']`);
    if (!o || !vs) return;

    if (o.checked) {
      vs.checked = false;
      vs.disabled = true;
    } else {
      vs.disabled = false;
    }

    if (vs.checked) {
      o.checked = false;
      o.disabled = true;
    } else {
      o.disabled = false;
    }
  }

  // ===============================
  // UPDATE TOTALS (O ONLY)
  // ===============================
  function updateTotals() {
    // Total Number of COI with Outstanding
    const coiCount = coiNumbers.reduce((acc, num) => {
      const o = document.querySelector(`input[name='ppst[${num}][O]']`);
      return acc + (o && o.checked ? 1 : 0);
    }, 0);

    // Total Number of NCOI with Outstanding
    const ncoiCount = ncoiNumbers.reduce((acc, num) => {
      const o = document.querySelector(`input[name='ppst[${num}][O]']`);
      return acc + (o && o.checked ? 1 : 0);
    }, 0);

    totalCOI.value  = coiCount;
    totalNCOI.value = ncoiCount;
  }

  // ===============================
  // EVENT LISTENERS
  // ===============================
  [...oBoxes, ...vsBoxes].forEach(box => {
    box.addEventListener("change", function () {
      const match = this.name.match(/ppst\[(\d+)\]/);
      if (match) syncOVS(match[1]);
      updateTotals();
    });
  });

  // Initialize totals on page load
  updateTotals();

});
</script>

<!-- DATA PRIVACY -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  // Check if user already accepted Data Privacy
  if (!localStorage.getItem("dataPrivacyAccepted")) {

    Swal.fire({
      title: "Data Privacy Notice",
      icon: "info",
      html: `
        <p style="text-align:justify; font-size:14px;">
          By accessing and using this system, you acknowledge and agree that
          all personal information and documents you provide shall be collected,
          processed, stored, and protected in accordance with the
          <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>.
        </p>
        <p style="text-align:justify; font-size:14px;">
          Your information will be used solely for legitimate purposes related
          to application evaluation, verification, and record management, and
          will not be shared with unauthorized parties.
        </p>
        <p style="text-align:justify; font-size:13px; font-style:italic;">
          By clicking <strong>"I Agree"</strong>, you voluntarily consent to the
          collection and processing of your personal data.
        </p>
      `,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonText: "I Agree",
      confirmButtonColor: "#198754"
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.setItem("dataPrivacyAccepted", "yes");
      }
    });

  }
});
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
<!-- UPLOAD PDF FILE EDUCATION -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('education_file');
    const previewDiv = document.getElementById('education_preview');

    fileInput.addEventListener('change', function() {
        previewDiv.innerHTML = ''; // Clear previous preview

        if (fileInput.files.length === 0) return;

        const list = document.createElement('ul');
        list.classList.add('list-group');

        Array.from(fileInput.files).forEach(file => {
            // SweetAlert notification
            Swal.fire({
                icon: 'success',
                title: 'File Selected',
                text: file.name,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            // Show preview in list
            const item = document.createElement('li');
            item.classList.add('list-group-item');
            item.textContent = file.name;
            list.appendChild(item);
        });

        previewDiv.appendChild(list);
    });
});
</script>
<script>
$(document).ready(function () {

  $('#position_applied').on('change', function () {

    // =========================
    // EDUCATION RESET
    // =========================
    $('#education_name').val('');
    $('#education_units_select').val('');
    $('#education_units_other').val('').addClass('d-none');
    $('#education_file').val('');
    $('#education_preview').html('No file uploaded.');
    $('#education_summary').html('<span class="text-muted">No education added.</span>');
    $('#education_remark').html('<span class="text-muted">Waiting for QS</span>');

    // =========================
    // TRAINING RESET
    // =========================
    if (typeof trainingIndex !== 'undefined') trainingIndex = 1;
    $('#trainingContainer').empty();
    $('#training_summary').html('<span class="text-muted">No trainings added.</span>');
    $('#total_training_hours').text('0');
    $('#training_remark').html('<span class="text-muted">Waiting for QS</span>');

    // =========================
    // EXPERIENCE RESET
    // =========================
    if (typeof resetExperience === 'function') {
      resetExperience();
    } else {
      $('#experience_summary').html('<span class="text-muted">No experience added.</span>');
      $('#experience_remark').html('<span class="text-muted">Waiting for QS</span>');
    }

    // =========================
    // ELIGIBILITY RESET
    // =========================
    if (typeof resetEligibility === 'function') {
      resetEligibility();
    } else {
      $('#eligibility_summary').html('<span class="text-muted">No eligibility added.</span>');
      $('#eligibility_remark').html('<span class="text-muted">Waiting for QS</span>');
    }

    // =========================
    // IPCRF RESET
    // =========================
    if (typeof uploadedIPCRFs !== 'undefined') uploadedIPCRFs = [];
    if (typeof savedIPCRFs !== 'undefined') savedIPCRFs = [];
    $('#ipcrfStatus').addClass('d-none').html('');

    Swal.fire({
      icon: 'info',
      title: 'Position Changed',
      text: 'All qualifications were reset because QS changed.',
      toast: true,
      position: 'top-end',
      timer: 3000,
      showConfirmButton: false
    });

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
document.addEventListener("DOMContentLoaded", function() {
    // Trigger when the section scrolls into view
    const ppstSection = document.getElementById('ppst-summary');

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                // Show SweetAlert info only once
                Swal.fire({
                    title: 'PPST Indicators Legend',
                    html: `
                      <div style="text-align:left; margin-top:1rem; margin-bottom:2rem; display:flex; flex-direction:column; gap:0.5rem;">
    <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:4px; height:24px; background-color:yellow;"></div>
        <span style="font-weight:bold; color:yellow;">COI</span> - Classroom Observation Indicators
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:4px; height:24px; background-color:green;"></div>
        <span style="font-weight:bold; color:green;">NCOI</span> - Non-Classroom Observation Indicators
    </div>

    <p style="margin-top:0.5rem; color:#555; font-size:0.9rem;">
        These indicators summarize your professional achievements. Please review them carefully after submitting your IPCRF.
    </p>
</div>


                    `,
                    icon: 'info',
                    confirmButtonText: 'Got it!',
                    timer: 10000,
                    timerProgressBar: true
                });
                observer.unobserve(ppstSection); // so it shows only once
            }
        });
    }, { threshold: 0.5 }); // trigger when 50% visible

    observer.observe(ppstSection);
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
</body>
</html>

