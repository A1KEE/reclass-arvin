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
        html += `
            <div class="mb-2 p-2 border rounded">
                <strong>${edu.degree ?? 'N/A'}</strong><br>
                ${edu.school ?? 'N/A'}<br>
                <small>
                    ${edu.date_graduated ?? 'N/A'} | 
                    Units: ${edu.units ?? 0}
                </small>
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