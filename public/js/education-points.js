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
    
    // Determine base level based on position
    let baseLevel = BASE_LEVEL; // Default: Bachelor's (6)
    let positionType = "Teacher";
    
    if (positionKey.includes('master teacher')) {
        baseLevel = MASTER_TEACHER_BASE_LEVEL; // Master's Degree (21)
        positionType = "Master Teacher";
    }
    
    // Determine degree type for better logging
    let degreeType = "Unknown";
    
    // EXPANDED DEGREE PATTERNS
    const bachelorPattern = /(bachelor|bachelor's|baccalaureate|bs|ba|bed|beed|b\.?ed|b\.?a|b\.?s|bse|bst|bsc|ab|bsed|bat|blis|bpa|bped|bsee|bsie|bsem|bshm|bsit|bsn|bspa|bsrt|bssw|bsba|bscs|bsis|bsmath|bsstat|bstm|bsmt|bscpe|bsce|bsae|bsme|bsche|bsee|bsn|bsp|bspt|bsot|bsmls|bspharm|bspsych|bssoc|bscrim|bspolsci|bsed|elementary education|secondary education|teacher education|education degree|teaching degree)/i;
    
    const masterPattern = /(master|master's|ma|ms|m\.a|m\.s|med|maed|m\.?ed|master of arts|master of science|master of education|master of teaching|master in education|master in teaching|msed|mst|mba|mpa|mha|mhm|mhr|mim|mib|mfa|mdes|march|mlis|mdiv|mth|mts|mph|msp|mstat|mfin|macc|mtax|mem|meng|mse|msc|msi|msm|msn|msw|mpm|mppm|mppa|mpp|mrp|mcrp|musm|mupa|mup|murp|mcp|mcj|ml|llm|mcl|mcr|mdr|mdm|mhm|mhrm|mib|mim|min|mip|mir|mis|mit|mkt|ml|mm|mmc|mmet|mme|mmed|mmgt|mmis|mmpa|mms|mnce|mns|mnt|mnut|mpe|mped|mph|mphe|mpil|mpl|mpr|mps|mpt|mrp|mrs|msa|msba|msc|msce|mscs|msd|mse|msec|msed|msf|msg|msh|msi|msis|msit|msm|msme|msn|mso|msp|mss|mssc|mssw|mst|msta|msts|msw|mth|mts|mtax|mte|mtech|mtm|mts|mtt|mu|mup|mur|mus|mva|mvd|mvs|mwd|my)/i;
    
    const doctorPattern = /(doctor|doctorate|ph\.?d|phd|edd|ed\.?d|dr\.|d\.?ed|dma|dba|deng|dsc|dphil|jd|md|dmd|dds|dvm|pharm\.?d|psyd|dnp|dpt|dot|dlitt|dmus|dsocsc|dtech|darch|jur\.?d|sc\.?d|ll\.?d|th\.?d|div|st\.?d|drph|drphil|drrer\.?nat|dr\.?ing|dr\.?med|dr\.?jur|dr\.?phil|dr\.?rer\.?nat|dr\.?sc|dr\.?tech|doctoral|doctor of philosophy|doctor of education)/i;
    
    if (doctorPattern.test(degreeName)) {
        degreeType = "Doctorate";
    } else if (masterPattern.test(degreeName)) {
        degreeType = "Master's";
    } else if (bachelorPattern.test(degreeName)) {
        degreeType = "Bachelor's";
    }
    
    // Increment = how many levels ABOVE the base level
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

// ================================
// BUILD UNITS DROPDOWN
// ================================
function buildUnitsDropdown(requiredLevel = 0) {
    const select = $('#education_units_select');
    select.empty();
    select.append('<option value="">Select Education Level</option>');

    // Populate options based on CSC units mapping
    Object.entries(educationLevels)
        .filter(([label, value]) => value >= requiredLevel)
        .forEach(([label, value]) => {
            select.append(`<option value="${value}">${label}</option>`);
        });

    select.append('<option value="others">Others</option>');
}

// ================================
// GET FINAL UNITS (INCLUDING OTHERS)
// ================================
function getFinalUnits() {
    const sel = $('#education_units_select').val();
    if (sel === 'others') {
        return parseInt($('#education_units_other').val()) || 0;
    }
    return parseInt(sel) || 0;
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
    const position = $('#position_applied').val().toLowerCase();
    const education = $('#education_name').val().trim().toLowerCase();
    const selectedUnits = getFinalUnits();

    if (!education) {
        $('#education_remark').html('<span class="text-muted">Waiting for QS</span>');
        return;
    }

    let isDegreeValid = false;
    
    // EXPANDED BACHELOR'S PATTERNS
    const bachelorPattern = /(bachelor|bachelor's|baccalaureate|bs|ba|bed|beed|b\.?ed|b\.?a|b\.?s|bse|bst|bsc|ab|bsed|bat|blis|bpa|bped|bsee|bsie|bsem|bshm|bsit|bsn|bspa|bsrt|bssw|bsba|bscs|bsis|bsmath|bsstat|bstm|bsmt|bscpe|bsce|bsae|bsme|bsche|bsee|bsn|bsp|bspt|bsot|bsmls|bspharm|bspsych|bssoc|bscrim|bspolsci|bsed|elementary education|secondary education|teacher education|education degree|teaching degree)/i;
    
    // EXPANDED MASTER'S PATTERNS
    const masterPattern = /(master|master's|ma|ms|m\.a|m\.s|med|maed|m\.?ed|master of arts|master of science|master of education|master of teaching|master in education|master in teaching|msed|mst|mba|mpa|mha|mhm|mhr|mim|mib|mfa|mdes|march|mlis|mdiv|mth|mts|mph|msp|mstat|mfin|macc|mtax|mem|meng|mse|msc|msi|msm|msn|msw|mpm|mppm|mppa|mpp|mrp|mcrp|musm|mupa|mup|murp|mcp|mcj|ml|llm|mcl|mcr|mdr|mdm|mhm|mhrm|mib|mim|min|mip|mir|mis|mit|mkt|ml|mm|mmc|mmet|mme|mmed|mmgt|mmis|mmpa|mms|mnce|mns|mnt|mnut|mpe|mped|mph|mphe|mpil|mpl|mpr|mps|mpt|mrp|mrs|msa|msba|msc|msce|mscs|msd|mse|msec|msed|msf|msg|msh|msi|msis|msit|msm|msme|msn|mso|msp|mss|mssc|mssw|mst|msta|msts|msw|mth|mts|mtax|mte|mtech|mtm|mts|mtt|mu|mup|mur|mus|mva|mvd|mvs|mwd|my)/i;
    
    // EXPANDED DOCTORATE PATTERNS
    const doctorPattern = /(doctor|doctorate|ph\.?d|phd|edd|ed\.?d|dr\.|d\.?ed|dma|dba|deng|dsc|dphil|jd|md|dmd|dds|dvm|pharm\.?d|psyd|dnp|dpt|dot|dlitt|dmus|dsocsc|dtech|darch|jur\.?d|sc\.?d|ll\.?d|th\.?d|div|st\.?d|drph|drphil|drrer\.?nat|dr\.?ing|dr\.?med|dr\.?jur|dr\.?phil|dr\.?rer\.?nat|dr\.?sc|dr\.?tech|doctoral|doctor of philosophy|doctor of education)/i;

    if (position.includes('master teacher')) {
        isDegreeValid = masterPattern.test(education) || doctorPattern.test(education);
    } else if (position.includes('teacher')) {
        isDegreeValid = bachelorPattern.test(education) || masterPattern.test(education) || doctorPattern.test(education);
    }

    const requiredLevel = positionRequiredLevel[position] || BASE_LEVEL;
    const userLevel = parseInt(selectedUnits) || 0;

    if (isDegreeValid && userLevel >= requiredLevel) {
        $('#education_remark').html(`<span class="text-success fw-bold">MET</span>`);
    } else {
        $('#education_remark').html(`<span class="text-danger fw-bold">NOT MET</span>`);
    }
}

// ================================
// INITIALIZE DROPDOWN ON MODAL SHOW
// ================================
$(document).on('show.bs.modal', '#educationModal', function() {
    setTimeout(function() {
        showQSUnits();
    }, 100);
});

// ================================
// EVENT LISTENERS
// ================================
$(document).ready(function() {
    console.log("Education script loaded");
    
    // Initialize dropdown
    setTimeout(function() {
        showQSUnits();
    }, 500);

    // DROPDOWN "OTHERS" TOGGLE
    $('#education_units_select').on('change', function() {
        if ($(this).val() === 'others') {
            $('#education_units_other').removeClass('d-none');
        } else {
            $('#education_units_other').addClass('d-none').val('');
        }
        evaluateEducation();
    });

    // EDUCATION NAME CHANGE
    $('#education_name').on('input', function() {
        evaluateEducation();
    });

    // POSITION OR LEVEL CHANGE
    $('#position_applied, #school_level').on('change', function() {
        // Reset fields
        $('#education_name').val('');
        $('#education_units_select').val('');
        $('#education_units_other').val('').addClass('d-none');
        $('#education_file').val('');
        $('#education_preview').text('No file uploaded.');
        $('#education_summary').html('<span class="text-muted">No education added.</span>');
        $('#education_remark').html('<span class="text-muted">Waiting for QS</span>');
        $('input[name="comparative[education]"]').val('');

        // Rebuild filtered dropdown
        showQSUnits();

        Swal.fire({
            icon: 'info',
            title: 'Position Changed',
            text: 'Please re-enter your education.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
    });

    // FILE UPLOAD PREVIEW
    $('#education_file').on('change', function() {
        const file = this.files[0];
        if (!file) return;
        $('#education_preview').text(file.name);
        Swal.fire({
            icon: 'success',
            title: 'File Selected',
            text: file.name,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });

    // SAVE EDUCATION
    $('#saveEducation').on('click', function() {
        const name = $('#education_name').val().trim();
        const selectedUnitsValue = getFinalUnits();
        const file = $('#education_file')[0].files[0];
        const position = $('#position_applied').val();

        if (!name || !file || !selectedUnitsValue) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete',
                text: 'Please enter education, select education level, and upload certificate.'
            });
            return;
        }

        // Get selected label for display
        const selectedLabel = $('#education_units_select option:selected').text();
        
        // Compute points - PASS THE DEGREE NAME TOO
        const result = computeEducationPoints(position, selectedUnitsValue, name);
        $('input[name="comparative[education]"]').val(result.points);

        // Show summary
        $('#education_summary').html(`
            <strong>${name}</strong><br>
            Degree Type: ${result.degreeType}<br>
            Education Level: ${selectedLabel}<br>
            Position Type: ${result.positionType}<br>
            <em>${file.name}</em><br><br>
            <small>
            Qualification Level: ${result.userLevel}<br>
            Required Level: ${result.requiredLevel}<br>
            Base Level for ${result.positionType}: ${result.baseLevel}<br>
            Increment (vs Base): ${result.increment}<br>
            <strong>Points: ${result.points}</strong>
            </small>
        `);

        $('#educationModal').modal('hide');
        evaluateEducation();

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