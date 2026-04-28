$(document).ready(function () {

    console.log("=== ADMIN LOAD START ===");
    console.log("ADMIN DATA:", window.adminData);

    if (!window.adminData) {
        console.warn("No admin data found.");
        return;
    }

    loadEducations();
    loadTrainings();
    loadExperiences();
    loadEligibilities();
    loadScores();

    console.log("=== ADMIN LOAD COMPLETE ===");
});

// =========================
// EDUCATION
// =========================
// Sa loadEducations() function, gamitin mo directly ang units dahil text na siya
function loadEducations() {
    let container = document.getElementById("education_summary");
    if (!container) return;
    
    let data = window.adminData.educations ?? [];
    
    if (!data.length) {
        container.innerHTML = `<div class="text-muted">No education records found.</div>`;
        return;
    }
    
    let html = "";
    
    data.forEach(edu => {
        // I-assume na ang edu.units ay TEXT na (e.g., "15 units of Masters Degree")
        let unitsText = edu.units || '';
        
        console.log("Education record:", edu.degree, "Units:", unitsText);
        
        html += `
            <div class="mb-2 p-2 border rounded">
                <strong>${edu.degree ?? 'N/A'}</strong><br>
                ${edu.school ?? 'N/A'}<br>
                <small>
                    ${edu.date_graduated ?? 'N/A'} | 
                    <strong>Units: ${unitsText}</strong>
                </small>
                <button class="btn btn-sm btn-warning mt-2 edit-education-btn"
                    data-id="${edu.id}"
                    data-degree="${edu.degree}"
                    data-school="${edu.school}"
                    data-date="${edu.date_graduated}"
                    data-units="${unitsText}"
                    data-file="${edu.file_path ?? ''}">
                    Edit
                </button>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// =========================
// TRAINING
// =========================
function loadTrainings() {

    let container = document.getElementById("training_summary");
    if (!container) return;

    let data = window.adminData.trainings ?? [];

    console.log("TRAININGS DEBUG:", data);

    if (!data.length) {
        container.innerHTML = `<div class="text-muted">No training records found.</div>`;
        return;
    }

    let html = "";

    data.forEach(tr => {
        html += `
            <div class="mb-2 p-2 border rounded">
                <strong>${tr.title ?? 'Training'}</strong><br>
                <small>
                    Type: ${tr.type ?? '-'}<br>
                    ${tr.start_date ?? ''} → ${tr.end_date ?? ''}<br>
                    Hours: ${tr.hours ?? 0}
                </small>
            </div>
        `;
    });

    container.innerHTML = html;
}


// =========================
// EXPERIENCE
// =========================
function loadExperiences() {

    let container = document.getElementById("experience_summary");
    if (!container) return;

    let data = window.adminData.experiences ?? [];

    console.log("EXPERIENCE DEBUG:", data);

    if (!data.length) {
        container.innerHTML = `<div class="text-muted">No experience records found.</div>`;
        return;
    }

    let html = "";

    data.forEach(exp => {

        let position = exp.position || exp.job_title || 'N/A';
        let school = exp.school || exp.company || '';
        let type = exp.school_type || '';
        let start = exp.start_date || '';
        let end = exp.end_date || 'Present';

        html += `
            <div class="mb-2 p-2 border rounded">
                <strong>${position}</strong><br>
                ${school} ${type ? `(${type})` : ''}<br>
                <small>${start} - ${end}</small>
            </div>
        `;
    });

    container.innerHTML = html;
}

// =========================
// ELIGIBILITY
// =========================
function loadEligibilities() {

    let container = document.getElementById("eligibility_summary");
    if (!container) return;

    let data = window.adminData.eligibilities ?? [];

    console.log("ELIGIBILITY DEBUG:", data);

    if (!data.length) {
        container.innerHTML = `<div class="text-muted">No eligibility records found.</div>`;
        return;
    }

    let html = "";

    data.forEach(el => {
        html += `
            <div class="mb-2 p-2 border rounded">
                <strong>${el.eligibility_name ?? 'Eligibility'}</strong><br>
                <small>
                    Expiry: ${el.expiry_date ?? '-'}
                </small>
            </div>
        `;
    });

    container.innerHTML = html;
}


// =========================
// SCORES
// =========================
function loadScores() {

    if (!window.adminData || !window.adminData.scores) {
        console.warn("No scores found.");
        return;
    }

    let scores = window.adminData.scores;

    $('#education_points').val(scores.education_points ?? 0);
    $('#training_points').val(scores.training_points ?? 0);
    $('#experience_points').val(scores.experience_points ?? 0);
    $('#performance_points').val(scores.performance_points ?? 0);

    $('#education_remark').html(scores.education_remarks || '<span class="text-muted">Waiting for QS</span>');
    $('#training_remark').html(scores.training_remarks || '<span class="text-muted">Waiting for QS</span>');
    setTimeout(() => {
    $('#experience_remark').html(
        scores.experience_remarks 
        ? `<span class="text-muted">${scores.experience_remarks}</span>`
        : '<span class="text-muted">Waiting for QS</span>'
    );
}, 200);
    setTimeout(() => {
        $('#eligibility_remark').html(
            scores.eligibility_remarks 
                ? `<span class="text-muted">${scores.eligibility_remarks}</span>`
                : '<span class="text-muted">Waiting for QS</span>'
        );
    }, 200);


    $('#remarksEducation').val(scores.education_remarks || '');
    $('#remarksTraining').val(scores.training_remarks || '');
    $('#remarksExperience').val(scores.experience_remarks || '');

    console.log("Scores loaded successfully.");

    setTimeout(() => {
    console.log("FORCED LOAD...");
    loadExperiences();
    loadEligibilities();
}, 1000);
}

$('#updateEducationBtn').on('click', function () {
    let id = $('#edit_education_id').val();
    let selectedValue = $('#edit_education_units_select').val();
    let selectedText = $('#edit_education_units_select option:selected').text();
    
    let payload = {
        degree: $('#edit_education_name').val(),
        school: $('#edit_education_school').val(),
        date_graduated: $('#edit_education_date').val(),
        units: selectedValue,
        _token: $('meta[name="csrf-token"]').attr('content')
    };
    
    $.ajax({
        url: `/qs/education/update/${id}`,
        type: 'POST',
        data: payload,
        success: function (res) {
            if (res.success) {
                let message = res.message;
                if (res.points !== undefined) {
                    message += `\nPoints: ${res.points}\nRemarks: ${res.remarks}`;
                }
                
                Swal.fire('Success', message, 'success');
                
                // I-update ang points display
                if (res.points !== undefined) {
                    $('#edit_edu_points_display').text(res.points + ' pts');
                    $('#education_points').val(res.points);
                    
                    let remarksHtml = res.remarks === 'MET' 
                        ? '<span class="fw-bold text-success">MET</span>'
                        : '<span class="fw-bold text-danger">NOT MET</span>';
                    $('#edit_edu_status_display').html(remarksHtml);
                    $('#education_remark').html(remarksHtml);
                    $('#remarksEducation').val(res.remarks);
                }
                
                // I-update ang display sa table
                let $educationDiv = $(`.edit-education-btn[data-id="${id}"]`).closest('.mb-2');
                if ($educationDiv.length) {
                    $educationDiv.find('strong').text(payload.degree);
                    $educationDiv.find('strong').next().text(payload.school);
                    $educationDiv.find('small').html(`
                        ${payload.date_graduated} | Units: ${selectedText}
                    `);
                    
                    let $button = $educationDiv.find('.edit-education-btn');
                    $button.data('degree', payload.degree);
                    $button.data('school', payload.school);
                    $button.data('date', payload.date_graduated);
                    $button.data('units', selectedText);
                    $button.attr('data-degree', payload.degree);
                    $button.attr('data-school', payload.school);
                    $button.attr('data-date', payload.date_graduated);
                    $button.attr('data-units', selectedText);
                }
                
                $('#editEducationModal').modal('hide');
            }
        },
        error: function(xhr) {
            let errorMsg = 'Failed to update education record';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            Swal.fire('Error', errorMsg, 'error');
            console.error(xhr.responseText);
        }
    });
});