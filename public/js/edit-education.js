// ======================================================
// EDIT EDUCATION MODULE - FINAL FIXED VERSION
// ======================================================

// =====================================
// DEBUG HELPER (PARANG dd)
// =====================================
function dd(label, data) {
    console.log("========== " + label + " ==========");
    console.log(data);
}

// =====================================
// EDUCATION LEVELS MAPPING
// =====================================
const editEducationLevels = {
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

// =====================================
// TEXT → VALUE HELPER (SAVE TO DATABASE)
// =====================================
function getValueFromText(text) {
    if (!text) return null;

    let clean = text.toString().trim().toLowerCase();

    for (let key in editEducationLevels) {
        if (key.toLowerCase() === clean) {
            return editEducationLevels[key];
        }
    }

    return null;
}

// =====================================
// VALUE → TEXT HELPER (DISPLAY FROM DATABASE) - ITO ANG KULANG
// =====================================
function getTextFromValue(value) {
    if (!value && value !== 0) return null;
    
    // Convert to number if string
    let numValue = parseInt(value);
    
    for (let [label, val] of Object.entries(editEducationLevels)) {
        if (val === numValue) {
            return label;
        }
    }
    
    return null;
}

// =====================================
// BUILD DROPDOWN
// =====================================
// =====================================
// BUILD DROPDOWN - WITHOUT SELECTION
// =====================================
function buildEditUnitsDropdown() {
    let select = $('#edit_education_units_select');
    
    select.empty();
    select.append('<option value="">Select Education Level</option>');
    
    if (typeof editEducationLevels === 'undefined') {
        console.warn("editEducationLevels not found");
        return;
    }
    
    // I-build lang ang options, walang selected
    Object.entries(editEducationLevels).forEach(([label, value]) => {
        select.append(`<option value="${value}">${label}</option>`);
    });
    
    console.log("Dropdown built with", Object.keys(editEducationLevels).length, "options");
}

// =====================================
// CLICK EDIT BUTTON - FIX DROPDOWN SELECTION
// =====================================
$(document).on('click', '.edit-education-btn', function () {
    console.log("=== EDIT BUTTON CLICKED ===");
    
    let id = $(this).data('id');
    let degree = $(this).data('degree');
    let school = $(this).data('school');
    let date = $(this).data('date');
    let units = $(this).data('units'); // Ito ay text na "15 units of Masters Degree"
    
    console.log("Units from button:", units);
    
    let scores = window.adminData?.scores || {};
    
    // SET FORM VALUES
    $('#edit_education_id').val(id);
    $('#edit_education_name').val(degree);
    $('#edit_education_school').val(school);
    $('#edit_education_date').val(date);
    
    // REBUILD DROPDOWN
    buildEditUnitsDropdown();
    
    // PAGKATAPOS BUILD, I-SELECT ANG TAMANG OPTION
    setTimeout(function() {
        if (units) {
            // Hanapin ang option na ang text ay match sa units
            let found = false;
            $('#edit_education_units_select option').each(function() {
                if ($(this).text() === units) {
                    $(this).prop('selected', true);
                    found = true;
                    console.log("Selected option:", $(this).text());
                    return false;
                }
            });
            
            if (!found) {
                console.log("No matching option found for:", units);
            }
        }
    }, 100);
    
    // LEFT SIDE DISPLAY
    $('#edit_edu_degree_display').text(degree || '—');
    
    $('#edit_edu_status_display').html(
        scores.education_remarks
            ? `<span class="fw-bold text-success">${scores.education_remarks}</span>`
            : `<span class="text-muted">Waiting..</span>`
    );
    
    $('#edit_edu_points_display').text(
        (scores.education_points ?? 0) + ' pts'
    );
    
    // FILE DISPLAY
    let file = $(this).data('file');
    
    $('#view_certificate_link').addClass('d-none');
    $('#view_certificate_preview').addClass('d-none');
    $('#no_certificate_text').addClass('d-none');
    
    if (file && file !== '') {
        let fileUrl = `/storage/${file}`;
        $('#view_certificate_link').attr('href', fileUrl).removeClass('d-none');
        $('#certificate_iframe').attr('src', fileUrl);
        $('#view_certificate_preview').removeClass('d-none');
    } else {
        $('#no_certificate_text').removeClass('d-none');
    }
    
    // SHOW MODAL
    $('#editEducationModal').modal('show');
});