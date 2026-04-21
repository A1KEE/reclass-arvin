// ================================
// EDUCATION LEVELS → CSC UNITS (for dropdown)
// ================================
const educationLevels = {
    "No Formal Education": 0,
    "Can Read and Write": 1,
    "Elementary Graduate": 2,
    "Junior High School (K to 12)": 3,
    "Senior High School (K to 12)": 4,
    "Completed 2 years in College": 5,
    "Bachelor's Degree": 6,
    "6 units of Masters Degree": 7,
    "9 units of Masters Degree": 8,
    "12 units of Masters Degree": 9,
    "15 units of Masters Degree": 10,
    "18 units of Masters Degree": 11,
    "21 units of Masters Degree": 12,
    "24 units of Masters Degree": 13,
    "27 units of Masters Degree": 14,
    "30 units of Masters Degree": 15,
    "33 units of Masters Degree": 16,
    "36 units of Masters Degree": 17,
    "39 units of Masters Degree": 18,
    "42 units of Masters Degree": 19,
    "CAR towards a Masters Degree": 20,
    "Masters Degree": 21,
    "3 units of Doctorate": 22,
    "6 units of Doctorate": 23,
    "9 units of Doctorate": 24,
    "12 units of Doctorate": 25,
    "15 units of Doctorate": 26,
    "18 units of Doctorate": 27,
    "21 units of Doctorate": 28,
    "24 units of Doctorate": 29,
    "CAR towards a Doctorate": 30,
    "Doctorate Degree (completed)": 31
};

// ================================
// CSC EDUCATION POINTS ENGINE
// ================================
const BASE_LEVEL = 6; // Bachelor's Degree (for Teachers)
const MASTER_TEACHER_BASE_LEVEL = 21; // Master's Degree (for Master Teachers)

const positionRequiredLevel = {
    "teacher ii (elementary)": 6,
    "teacher iii (elementary)": 6,
    "teacher iv (elementary)": 6,
    "teacher v (elementary)": 6,
    "teacher vi (elementary)": 11,
    "teacher vii (elementary)": 11,
    "teacher ii (secondary)": 6,
    "teacher iii (secondary)": 6,
    "teacher iv (secondary)": 6,
    "teacher v (secondary)": 6,
    "teacher vi (secondary)": 11,
    "teacher vii (secondary)": 11,
    "master teacher i (elementary)": 21,
    "master teacher ii (elementary)": 21,
    "master teacher i (secondary)": 21,
    "master teacher ii (secondary)": 21,
};

// QS Education Units (if you have this from other file)
const qsEducationUnits = window.qsEducationUnits || {
    "elementary": {
        "teacher ii": 6,
        "teacher iii": 6,
        "teacher iv": 6,
        "teacher v": 6,
        "teacher vi": 11,
        "teacher vii": 11,
        "master teacher i": 21,
        "master teacher ii": 21
    },
    "secondary": {
        "teacher ii": 6,
        "teacher iii": 6,
        "teacher iv": 6,
        "teacher v": 6,
        "teacher vi": 11,
        "teacher vii": 11,
        "master teacher i": 21,
        "master teacher ii": 21
    }
};

// ================================
// CTP UNITS FOR NON EDUC DEGREE
// ================================
const ctpUnits = {
 "6 units of Professional Education": 7,
 "9 units of Professional Education": 8,
 "12 units of Professional Education": 9,
 "15 units of Professional Education": 10,
 "18 units of Professional Education (Required)": 11
};

function isEducationDegree(degree) {

    const text = degree.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim();

    // 🔥 EXACT EDUC KEYWORDS ONLY (STRICT)
    const educKeywords = [
        'beed',
        'bsed',
        'bachelor of elementary education',
        'bachelor of secondary education',
        'elementary education',
        'secondary education',
        'teacher education',
        'education degree'
    ];

    // 🔥 NON-EDUC KEYWORDS (HIGH PRIORITY)
    const nonEducKeywords = [
        'information technology',
        'computer science',
        'engineering',
        'business',
        'accountancy',
        'management',
        'technology',
        'science'
    ];

    // ❌ CHECK NON-EDUC FIRST
    for (let word of nonEducKeywords) {
        if (text.includes(word)) {
            return false;
        }
    }

    // ✅ CHECK EDUC ONLY IF EXACT MATCH
    for (let word of educKeywords) {
        if (text.includes(word)) {
            return true;
        }
    }

    // MASTER / DOCTOR (auto valid)
    if (/(phd|edd|doctor of|doctorate)/.test(text)) return true;
    if (/(master of|masters of|maed|med|master in)/.test(text)) return true;

    return false;
}
// ================================
// GET EDUCATION POINTS - FIXED POINTS SYSTEM (2,4,6,8,10)
// ================================
function getEducationPoints(increment) {
    // FIXED POINTS: 2, 4, 6, 8, 10 ONLY!
    // Minimum 2 increments to get points
    
    if (increment >= 10) return 10;     // Increments 10+ = 10 points
    if (increment >= 8)  return 8;      // Increments 8-9 = 8 points
    if (increment >= 6)  return 6;      // Increments 6-7 = 6 points
    if (increment >= 4)  return 4;      // Increments 4-5 = 4 points
    if (increment >= 2)  return 2;      // Increments 2-3 = 2 points
    return 0;                           // Increments 0-1 = 0 points
}

// ================================
// COMPUTE EDUCATION POINTS - WITH DIFFERENT BASE LEVELS AND DEGREE TYPE DETECTION
// ================================
function computeEducationPoints(position, selectedUnitsValue, degreeName = '') {

    const userLevel = parseInt(selectedUnitsValue) || 0;
    const positionKey = position.toLowerCase();
    const requiredLevel = positionRequiredLevel[positionKey] || BASE_LEVEL;

    let baseLevel = BASE_LEVEL;
    let positionType = "Teacher";

    if (positionKey.includes('master teacher')) {
        baseLevel = MASTER_TEACHER_BASE_LEVEL;
        positionType = "Master Teacher";
    }

    let degreeType = "Unknown";

    const bachelorPattern = /(bachelor|baccalaureate|bed|beed|bsed|bse|education|teaching)/i;

    const masterPattern = /(master|ma|ms|med|maed|m\.?ed|master of education|master in)/i;

    const doctorPattern = /(doctor|phd|edd|doctorate|d\.?ed|dba|dsc|dphil)/i;

    if (doctorPattern.test(degreeName)) {
        degreeType = "Doctorate";
    } else if (masterPattern.test(degreeName)) {
        degreeType = "Master's";
    } else if (bachelorPattern.test(degreeName)) {
        degreeType = "Bachelor's";
    }

    // =====================================================
    // NON-EDUCATION DEGREE LOGIC (FINAL FIX)
    // =====================================================
    if (!isEducationDegree(degreeName)) {

        const ctpValue = parseInt($('#ctp_units_select').val()) || 0;

        // BELOW 18 UNITS PROFESSIONAL EDUCATION
        // NOT MET + 0 POINTS
        if (ctpValue < 11) {
            return {
                userLevel,
                requiredLevel,
                baseLevel: BASE_LEVEL,
                positionType,
                degreeType: "Non-Education",
                increment: 0,
                points: 0
            };
        }

        // 18 UNITS PROFESSIONAL EDUCATION
        // STATUS = handled separately in evaluateEducation()
        // POINTS = follow normal Education Degree scoring

        const increment = Math.max(0, userLevel - BASE_LEVEL);
        const points = getEducationPoints(increment);

        return {
            userLevel,
            requiredLevel,
            baseLevel: BASE_LEVEL,
            positionType,
            degreeType: "Non-Education + Professional Education",
            increment,
            points
        };
    }

    // =====================================================
    // EDUCATION DEGREE LOGIC (UNCHANGED)
    // =====================================================
    const increment = Math.max(0, userLevel - baseLevel);
    const points = getEducationPoints(increment);

    return {
        userLevel,
        requiredLevel,
        baseLevel,
        positionType,
        degreeType,
        increment,
        points
    };
}

function buildUnitsDropdown(requiredLevel = 0) {

    const educSelect = $('#education_units_select');
    const ctpSelect = $('#ctp_units_select');
    const ctpContainer = $('#ctp_container');

    const degreeName = ($('#education_name').val() || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s]/g, '');

    const currentEducValue = educSelect.val();
    const currentCtpValue = ctpSelect.val();

    educSelect.empty();
    ctpSelect.empty();

    educSelect.append('<option value="">Select Education Level</option>');
    ctpSelect.append('<option value="">Select CTP Units</option>');

    const isMaster = /\b(master|master's|maed|med|ms|ma)\b/i.test(degreeName);
    const isDoctor = /\b(phd|edd|doctor of|doctorate)\b/i.test(degreeName);
    const isEduc = isEducationDegree(degreeName);

    // =====================================
    // NON EDUCATION DEGREE
    // =====================================
    if (degreeName && !isEduc && !isMaster && !isDoctor) {

        ctpContainer.removeClass('d-none');

        Object.entries(ctpUnits).forEach(([label, value]) => {
            ctpSelect.append(`<option value="${value}">${label}</option>`);
        });

        if (currentCtpValue) {
            ctpSelect.val(currentCtpValue);
        }

        const ctpValue = parseInt(ctpSelect.val()) || 0;

        // below 18 units
        if (ctpValue < 11) {
            educSelect.prop('disabled', true);
            return;
        }

        // 18 units = open higher attainment
        educSelect.prop('disabled', false);

        Object.entries(educationLevels)
            .filter(([label, value]) => value >= 7)
            .forEach(([label, value]) => {
                educSelect.append(`<option value="${value}">${label}</option>`);
            });

        if (currentEducValue) {
            educSelect.val(currentEducValue);
        }

        return;
    }

    // =====================================
    // EDUCATION DEGREE NORMAL
    // =====================================
    ctpSelect.val('');
    educSelect.prop('disabled', false);
    ctpContainer.addClass('d-none');

    Object.entries(educationLevels)
        .filter(([label, value]) => value >= requiredLevel)
        .forEach(([label, value]) => {
            educSelect.append(`<option value="${value}">${label}</option>`);
        });

    if (currentEducValue) {
        educSelect.val(currentEducValue);
    }
}
// ================================
// GET FINAL UNITS (INCLUDING NON-EDUC + HIGHER ATTAINMENT)
// ================================
function getFinalUnits() {

    const name = ($('#education_name').val() || '').trim();

    // =====================================
    // NON-EDUC DEGREE
    // =====================================
    if (!isEducationDegree(name)) {

        const ctpValue = parseInt($('#ctp_units_select').val()) || 0;
        const educUnits = parseInt($('#education_units_select').val()) || 0;

        // BELOW 18 units → use CTP value
        if (ctpValue < 11) {
            return ctpValue;
        }

        // EXACT 18 units only
        // no higher attainment selected yet
        if (ctpValue >= 11 && !educUnits) {
            return BASE_LEVEL; // IMPORTANT FIX → 6 not 11
        }

        // with Masters / Doctorate selected
        return educUnits;
    }

    // =====================================
    // EDUC DEGREE
    // =====================================
    return parseInt($('#education_units_select').val()) || 0;
}

// ================================
// SHOW QS UNITS (Filter dropdown based on requirement)
// ================================
function showQSUnits() {
    const level = $('#school_level').val();
    const position = $('#position_applied').val().toLowerCase();
    
    // Get required level from qsEducationUnits or default
    let requiredLevel = BASE_LEVEL;
    
    if (qsEducationUnits && qsEducationUnits[level]) {
        // Try exact match first
        if (qsEducationUnits[level][position] !== undefined) {
            requiredLevel = qsEducationUnits[level][position];
        } else {
            // Try partial match
            const positionKey = Object.keys(qsEducationUnits[level]).find(key => 
                key.toLowerCase().includes(position) || position.includes(key.toLowerCase())
            );
            if (positionKey) {
                requiredLevel = qsEducationUnits[level][positionKey];
            }
        }
    }

    // For Master Teacher positions, ensure minimum is Master's Degree (21)
    if (position.includes('master teacher') && requiredLevel < 21) {
        requiredLevel = 21;
    }

    buildUnitsDropdown(requiredLevel);
}

// ================================
// EVALUATE EDUCATION (MET/NOT MET) - WITH EXPANDED PATTERNS
// ================================
function evaluateEducation() {
    const level = $('#school_level').val();
    const position = ($('#position_applied').val() || '').toLowerCase();
    const education = ($('#education_name').val() || '').trim().toLowerCase();

    const units = getFinalUnits(); // 🔥 SINGLE SOURCE OF TRUTH

    // ================================
    // WAITING STATE
    // ================================
    if (!education || !units) {
        $('#modal_education_remark').html('<span class="text-muted">Waiting for the QS</span>');
        return;
    }

    let isDegreeValid = false;

    // ================================
    // DEGREE PATTERNS
    // ================================
    const bachelorPattern = /(bachelor|baccalaureate|bs|ba|beed|bsed|education|teaching)/i;

    const masterPattern = /(master|ma|ms|med|maed|m\.?ed)/i;

    const doctorPattern = /(doctor|phd|edd|doctorate)/i;

    // ================================
    // DEGREE VALIDATION
    // ================================
    if (position.includes('master teacher')) {
        isDegreeValid = masterPattern.test(education) || doctorPattern.test(education);
    } else if (position.includes('teacher')) {
        isDegreeValid = bachelorPattern.test(education) || masterPattern.test(education) || doctorPattern.test(education);
    }

    const requiredLevel = positionRequiredLevel[position] || BASE_LEVEL;

    // ================================
    // 🔥 NON-EDUC DEGREE → CTP RULE (FINAL FIX)
    // ================================
    if (!isEducationDegree(education) && position.includes('teacher')) {

        const ctpValue = parseInt($('#ctp_units_select').val()) || 0;

        console.log("CTP VALUE:", ctpValue); // 🔥 DEBUG

       if (ctpValue >= 11) {
        const educUnits = parseInt($('#education_units_select').val()) || 0;

        if (educUnits >= 6) {
            $('#modal_education_remark').html('<span class="text-success fw-bold">MET</span>');
        } else {
            $('#modal_education_remark').html('<span class="text-danger fw-bold">NOT MET</span>');
        }
    }

        return; // ✅ STOP HERE (IMPORTANT)
    }

    // ================================
    // NORMAL EDUC DEGREE LOGIC
    // ================================
    if (isDegreeValid && units >= requiredLevel) {
        $('#modal_education_remark').html('<span class="text-success fw-bold">MET</span>');
    } else {
        $('#modal_education_remark').html('<span class="text-danger fw-bold">NOT MET</span>');
    }
}

// ================================
// UPDATE EDUCATION SUMMARY LIVE (UPDATED FOR NEW MODAL DESIGN)
// ================================
function updateEducationSummaryLive() {

    const name = $('#education_name').val().trim();
    const position = ($('#position_applied').val() || '').toLowerCase();

    if (!position) return;

    const isEduc = isEducationDegree(name);

    // 🔥 GET REAL VALUE
    const units = getFinalUnits();

    // 🔥 GET LABEL
    let unitsLabel = '—';

    if (!isEduc) {
        unitsLabel = $('#ctp_units_select option:selected').text();
    } else {
        unitsLabel = $('#education_units_select option:selected').text();
    }

    const result = computeEducationPoints(position, units, name);

    $('#edu_degree_display').text(name || '—');
    $('#edu_level_display').text(unitsLabel || '—');
    $('#edu_points_display').text(`${result.points} points`);

    let status = 'WAITING';

    if (!name || !units) {
        status = 'WAITING';
    } else {
        if (!isEduc && position.includes('teacher')) {
            const ctpValue = parseInt($('#ctp_units_select').val()) || 0;
            status = ctpValue >= 11 ? 'MET' : 'NOT MET';
        } else {
            status = result.userLevel >= result.requiredLevel ? 'MET' : 'NOT MET';
        }
    }

    if (status === 'MET') {
        $('#edu_status_display')
            .text('MET')
            .removeClass('text-muted text-danger')
            .addClass('text-success fw-bold');
    } else if (status === 'NOT MET') {
        $('#edu_status_display')
            .text('NOT MET')
            .removeClass('text-muted text-success')
            .addClass('text-danger fw-bold');
    } else {
        $('#edu_status_display')
            .text('Waiting..')
            .removeClass('text-success text-danger')
            .addClass('text-muted');
    }

    console.log("UNITS:", units); // 🔥 DEBUG


    // ✅ Always render Non-Educ degree notice
    const notice = !isEducationDegree(name) && position.toLowerCase().includes('teacher')
        ? `<div class="alert alert-warning p-2 mb-2">
                Non-Education degrees require 
                <strong>18 units Professional Education (CTP)</strong>.
           </div>`
        : '';

    $('#modal_education_summary').html(`
        ${notice}
        <div class="alert alert-info p-2 border-primary-subtle">
            <strong>Education Summary</strong><br>
            Degree: ${name}<br>
            Education Level: ${unitsLabel}<br>
            Status: ${
                status === 'MET'
                    ? '<span class="text-success fw-bold">MET</span>'
                    : '<span class="text-danger fw-bold">NOT MET</span>'
            }<br>
            Score: <span class="fw-bold ${result.points > 0 ? 'text-primary' : 'text-muted'}">
    ${result.points} ${result.points === 1 ? 'point' : 'points'}
</span>
        </div>
    `);
}

// ================================
// INITIALIZE DROPDOWN ON MODAL SHOW
// ================================
$(document).on('shown.bs.modal', '#educationModal', function() {
    setTimeout(function() {
        const currentName = $('#education_name').val().trim();

        // 🔹 Rebuild dropdown lang kung wala pang value o degree name nagbago
        if (currentName) {
            showQSUnits();   // automatic detect Education / Non-Education
        }

        // 🔹 Update summary sa lahat ng cases
        updateEducationSummaryLive();
    }, 100);
});

// ================================
// EVENT LISTENERS (UPDATED FOR NEW MODAL DESIGN)
// ================================
$(document).ready(function() {
    console.log("Education script loaded");
    
    // Initialize dropdown
    setTimeout(function() {
        showQSUnits();
    }, 500);

    // DROPDOWN "OTHERS" TOGGLE (UPDATED FOR NEW DESIGN)
    $('#education_units_select').on('change', function() {
        evaluateEducation();
        updateEducationSummaryLive();
    });

    // EDUCATION NAME CHANGE
    $('#education_name').on('input', function() {
        showQSUnits(); // rebuild dropdown depending on degree type
        evaluateEducation();
        updateEducationSummaryLive();
    });

    // ✅ CTP DROPDOWN LISTENER (FIX)
    $('#ctp_units_select').on('change', function() {

        // 🔥 REBUILD DROPDOWN EVERY CHANGE
        showQSUnits();

        evaluateEducation();
        updateEducationSummaryLive();
    });

    // LIVE UPDATE SUMMARY (like Training)
    $('#education_name, #education_units_select, #education_units_other').on('input change', function() {
        updateEducationSummaryLive();
    });

    // FILE UPLOAD PREVIEW (UPDATED FOR NEW DESIGN)
    $('#education_file').on('change', function() {
        const file = this.files[0];
        if (!file) {
            $('#education_file_name').text('No file chosen').removeClass('text-success').addClass('text-muted');
            return;
        }
        
        // Check file type
        if (!file.type.includes('pdf')) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: 'Please select a PDF file only.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $(this).val('');
            $('#education_file_name').text('No file chosen').removeClass('text-success').addClass('text-muted');
            return;
        }
        
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Maximum file size is 10MB.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $(this).val('');
            $('#education_file_name').text('No file chosen').removeClass('text-success').addClass('text-muted');
            return;
        }
        
        $('#education_file_name').text(file.name).removeClass('text-muted').addClass('text-success');
        
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

    // SAVE EDUCATION
$('#saveEducation').on('click', function() {

    const name = $('#education_name').val().trim();
    const school = $('#education_school').val().trim();
    const date = $('#education_date').val();
    const file = $('#education_file')[0].files[0];
    const position = ($('#position_applied').val() || '').toLowerCase();

    // =========================
    // 🔥 GET CORRECT UNITS + LABEL (FIXED)
    // =========================
    let units;
    let unitsLabel;

  if (!isEducationDegree(name)) {

    const ctpValue = parseInt($('#ctp_units_select').val()) || 0;
    const educValue = parseInt($('#education_units_select').val()) || 0;

    // if may selected na higher attainment (Masters/Doctorate)
    if (ctpValue >= 11 && educValue > 0) {
        units = educValue;
        unitsLabel = $('#education_units_select option:selected').text();
    } 
    // if Professional Education only
    else {
        units = ctpValue;
        unitsLabel = $('#ctp_units_select option:selected').text();
    }
    } else {
        units = parseInt($('#education_units_select').val()) || 0;
        unitsLabel = $('#education_units_select option:selected').text();
    }
    $('#input_education_units').val(unitsLabel);   

    // =========================
    // VALIDATION
    // =========================
    if (!name || !units || !file) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete',
            text: 'Please complete all fields',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }

    // =========================
    // COMPUTE POINTS
    // =========================
    const result = computeEducationPoints(position, units, name);

    // =========================
    // 🔥 COMPUTE REMARKS (FINAL FIX)
    // =========================
    let remarkText = '';

    // NON-EDUC (CTP RULE)
   if (!isEducationDegree(name) && position.includes('teacher')) {

    const ctpValue = parseInt($('#ctp_units_select').val()) || 0;

    // STATUS must be based on CTP only
    remarkText = ctpValue >= 11 ? 'MET' : 'NOT MET';
}
    // NORMAL EDUC
    else {
        remarkText = result.userLevel >= result.requiredLevel ? 'MET' : 'NOT MET';
    }

    // =========================
    // SET HIDDEN INPUTS (FOR DB)
    // =========================
    $('#education_points').val(result.points);
    $('#remarksEducation').val(remarkText);

    // =========================
    // UI STATUS
    // =========================
    let status = remarkText === 'MET'
        ? '<span class="text-success fw-bold">MET</span>'
        : '<span class="text-danger fw-bold">NOT MET</span>';

    // =========================
    // 🔥 UPDATE SUMMARY (FIXED LABEL)
    // =========================
    $('#education_summary').html(`
        <small><strong>${name}</strong></small><br>
        <small>${school}</small><br>
        <small>${date}</small><br>
        <small>${unitsLabel}</small>
    `);

    // =========================
    // SET VALUES FOR OTHER PARTS
    // =========================
    $('input[name="comparative[education]"]').val(result.points);
    $('#input_qs_applicant_education').val(`${name} (${unitsLabel})`);

    // Update UI remark
    $('#education_remark').html(status);

    // =========================
    // CLOSE MODAL
    // =========================
    $('#educationModal').modal('hide');

    // =========================
    // SUCCESS MESSAGE
    // =========================
    Swal.fire({
        icon: 'success',
        title: 'Education Saved',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

});
});