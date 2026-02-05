// training.js
let trainingIndex = 1;
let requiredTrainingHours = typeof requiredHours !== 'undefined' ? requiredHours : 0;
let requiredTrainingLevel = typeof requiredLevel !== 'undefined' ? requiredLevel : 0;

// ==========================
// TRAINING LEVEL TABLE MAPPING (from Table 2.b)
// ==========================
const trainingLevels = [
    { from: 0, to: 8, level: 1 },
    { from: 8, to: 16, level: 2 },
    { from: 16, to: 24, level: 3 },
    { from: 24, to: 32, level: 4 },
    { from: 32, to: 40, level: 5 },
    { from: 40, to: 48, level: 6 },
    { from: 48, to: 56, level: 7 },
    { from: 56, to: 64, level: 8 },
    { from: 64, to: 72, level: 9 },
    { from: 72, to: 80, level: 10 },
    { from: 80, to: 88, level: 11 },
    { from: 88, to: 96, level: 12 },
    { from: 96, to: 104, level: 13 },
    { from: 104, to: 112, level: 14 },
    { from: 112, to: 120, level: 15 },
    { from: 120, to: 128, level: 16 },
    { from: 128, to: 136, level: 17 },
    { from: 136, to: 144, level: 18 },
    { from: 144, to: 152, level: 19 },
    { from: 152, to: 160, level: 20 },
    { from: 160, to: 168, level: 21 },
    { from: 168, to: 176, level: 22 },
    { from: 176, to: 184, level: 23 },
    { from: 184, to: 192, level: 24 },
    { from: 192, to: 200, level: 25 },
    { from: 200, to: 208, level: 26 },
    { from: 208, to: 216, level: 27 },
    { from: 216, to: 224, level: 28 },
    { from: 224, to: 232, level: 29 },
    { from: 232, to: 240, level: 30 },
    { from: 240, to: Infinity, level: 31 }
];

// ==========================
// GET QUALIFICATION LEVEL FROM HOURS
// ==========================
function getQualificationLevel(hours) {
    hours = parseFloat(hours);
    if (isNaN(hours) || hours < 0) return 0;
    
    const found = trainingLevels.find(level => hours >= level.from && hours < level.to);
    return found ? found.level : 31; // default to level 31 if more than 240
}

// ==========================
// FIXED POINTS SYSTEM FOR TRAINING (same as education)
// ==========================
function getTrainingPoints(increment) {
    // FIXED POINTS: 2, 4, 6, 8, 10 ONLY!
    // Minimum 2 increments to get points
    // Floors to next lower available point value
    
    if (increment >= 10) return 10;     // Increments 10+ = 10 points
    if (increment >= 8)  return 8;      // Increments 8-9 = 8 points
    if (increment >= 6)  return 6;      // Increments 6-7 = 6 points
    if (increment >= 4)  return 4;      // Increments 4-5 = 4 points
    if (increment >= 2)  return 2;      // Increments 2-3 = 2 points
    return 0;                           // Increments 0-1 = 0 points
}

// ==========================
// COMPUTE TOTAL HOURS + LEVEL + POINTS
// ==========================
function computeTotalHours() {
    let total = 0;
    let trainingList = [];

    $('.training-item').each(function () {
        let hours = parseFloat($(this).find('input[name*="[hours]"]').val());
        let title = $(this).find('input[name*="[title]"]').val();
        
        if (!isNaN(hours)) {
            total += hours;
            if (title) {
                trainingList.push(`${title} (${hours} hrs)`);
            }
        }
    });

    $('#total_training_hours').text(total);
    
    // Get applicant's qualification level
    const applicantLevel = getQualificationLevel(total);
    
    // Calculate increments: applicant level - required level
    const increments = Math.max(0, applicantLevel - requiredTrainingLevel);
    
    // Get points using fixed points system
    const trainingPoints = getTrainingPoints(increments);
    
    // Update the training points input field
    $('input[name="comparative[training]"]').val(trainingPoints);
    
    // Update modal summary (detailed)
    const modalSummary = $('#modal_training_summary');
    if (total > 0 && requiredTrainingHours > 0) {
        const status = total >= requiredTrainingHours ? 
            '<span class="text-success fw-bold">MET</span>' : 
            '<span class="text-danger fw-bold">NOT MET</span>';
        
        modalSummary.html(`
            <div class="alert alert-info p-2">
                <strong>Training Summary</strong><br>
                Total Hours: ${total} hours<br>
                Applicant Level: ${applicantLevel}<br>
                Required Hours: ${requiredTrainingHours} hours<br>
                Required Level: ${requiredTrainingLevel}<br>
                Increments: ${increments}<br>
                Status: ${status}<br>
                <strong>Points: ${trainingPoints}</strong>
            </div>
        `);
    } else {
        modalSummary.html('<div class="alert alert-warning p-2">No trainings added or waiting for QS</div>');
    }

    // Update form table summary (simple list only)
    const tableSummary = $('#training_summary');
    if (trainingList.length > 0) {
        tableSummary.html(trainingList.map(t => `• ${t}`).join('<br>'));
    } else {
        tableSummary.html('<span class="text-muted">No trainings added.</span>');
    }

    const remark = $('#training_remark');
    if (total === 0 || requiredTrainingHours === 0) {
        remark.html('<span class="text-muted">Waiting for the QS</span>');
        $('input[name="comparative[training]"]').val('');
        return;
    }

    if (total >= requiredTrainingHours) {
        remark.html('<span class="text-success fw-bold">MET</span>');
    } else {
        remark.html('<span class="text-danger fw-bold">NOT MET</span>');
        $('input[name="comparative[training]"]').val('0');
    }
}

// ==========================
// ADD TRAINING ROW (SINGLE ROW FORMAT)
// ==========================
$('#addTraining').on('click', function () {
    let html = `
    <div class="training-item card shadow-sm mb-3 p-3 position-relative">
        <button type="button" class="btn btn-sm btn-outline-danger remove-training position-absolute top-0 end-0 m-2"
                style="font-size:0.85rem; padding:0.2rem 0.4rem;">✖</button>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="fw-bold">Training Title</label>
                <input type="text" name="trainings[${trainingIndex}][title]" class="form-control" placeholder="Enter title" required>
            </div>

            <div class="col-md-4">
                <label class="fw-bold">Training Type</label>
                <select name="trainings[${trainingIndex}][type]" class="form-select training_type" required>
                    <option value="">Select Type</option>
                    <option value="Face-to-Face">Face-to-Face</option>
                    <option value="Online">Online</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="fw-bold">No. of Hours</label>
                <input type="number" name="trainings[${trainingIndex}][hours]" class="form-control training_hours" readonly>
                <div class="form-text text-muted">Automatically computed from start and end dates.</div>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="fw-bold">Start Date</label>
                <input type="date" name="trainings[${trainingIndex}][start_date]" class="form-control training_date" required>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">End Date</label>
                <input type="date" name="trainings[${trainingIndex}][end_date]" class="form-control training_date" required>
            </div>
        </div>

        <div class="mt-2">
            <label class="fw-bold">Certificate (PDF)</label>
            <input type="file" name="trainings[${trainingIndex}][file]" class="form-control training_file" accept="application/pdf" required>
        </div>
    </div>`;

    $('#trainingContainer').append(html);
    trainingIndex++;
});

// ==========================
// REMOVE TRAINING ROW
// ==========================
$(document).on('click', '.remove-training', function () {
    $(this).closest('.training-item').remove();
    computeTotalHours();
});

// ==========================
// AUTO HOURS BASED ON TYPE AND DATE
// ==========================
$(document).on('change', '.training_type, .training_date', function () {
    const container = $(this).closest('.training-item');
    const type = container.find('.training_type').val();
    const start = container.find('input[name*="[start_date]"]').val();
    const end   = container.find('input[name*="[end_date]"]').val();

    if (!type || !start || !end) return;

    const startDate = new Date(start);
    const endDate   = new Date(end);
    const dayCount = Math.floor((endDate - startDate) / (1000*60*60*24)) + 1;

    let hours = 0;
    if (type === 'Face-to-Face') hours = dayCount * 8;
    else if (type === 'Online') hours = dayCount * 3;

    container.find('.training_hours').val(hours);
    computeTotalHours();
});

// ==========================
// FILE SELECT TOAST
// ==========================
$(document).on('change', '.training_file', function () {
    const file = this.files[0];
    if (!file) return;

    Swal.fire({
        icon: 'success',
        title: 'Certificate Selected',
        text: file.name,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });
});

// ==========================
// SAVE TRAINING WITH FILE VALIDATION
// ==========================
$('#saveTraining').on('click', function () {
    let trainingList = [];
    let count = 0;
    let missingFile = false;

    $('.training-item').each(function () {
        const title = $(this).find('input[name*="[title]"]').val();
        const hours = $(this).find('input[name*="[hours]"]').val();
        const file  = $(this).find('input[type="file"]')[0].files[0];
        const type = $(this).find('.training_type').val();
        const start = $(this).find('input[name*="[start_date]"]').val();
        const end = $(this).find('input[name*="[end_date]"]').val();

        if (!file) missingFile = true;

        if (title && hours && file) {
            count++;
            trainingList.push({
                title: title,
                hours: hours,
                type: type,
                start: start,
                end: end
            });
        }
    });

    if (count === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Training Added',
            text: 'Please add at least one training.'
        });
        return;
    }

    if (missingFile) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Certificate',
            text: 'Please upload PDF certificates for all trainings.'
        });
        return;
    }

    // Calculate totals
    let totalHours = 0;
    trainingList.forEach(t => totalHours += parseInt(t.hours));
    const applicantLevel = getQualificationLevel(totalHours);
    const increments = Math.max(0, applicantLevel - requiredTrainingLevel);
    const trainingPoints = getTrainingPoints(increments);
    const status = totalHours >= requiredTrainingHours ? 
        '<span class="text-success fw-bold">MET</span>' : 
        '<span class="text-danger fw-bold">NOT MET</span>';

    // Update modal summary (detailed)
    $('#modal_training_summary').html(`
        <div class="alert alert-info p-2">
            <strong>Training Summary</strong><br>
            Total Hours: ${totalHours} hours<br>
            Applicant Level: ${applicantLevel}<br>
            Required Hours: ${requiredTrainingHours} hours<br>
            Required Level: ${requiredTrainingLevel}<br>
            Increments: ${increments}<br>
            Status: ${status}<br>
            <strong>Points: ${trainingPoints}</strong>
        </div>
    `);

    // Update form table summary (simple list)
    $('#training_summary').html(trainingList.map(t => `• ${t.title} (${t.hours} hrs)`).join('<br>'));

    $('#trainingModal').modal('hide');
    computeTotalHours();

    Swal.fire({
        icon: 'success',
        title: 'Training Saved',
        html: `Successfully saved ${trainingList.length} training(s)<br>Total Hours: ${totalHours} hours<br>Points: ${trainingPoints}`,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
});

// ==========================
// UPDATE QS DATA FROM SERVER
// ==========================
function updateTrainingQS(requiredHours, requiredLevel) {
    requiredTrainingHours = parseInt(requiredHours || 0);
    requiredTrainingLevel = parseInt(requiredLevel || 0);
    
    // Reset the training points when QS changes
    $('input[name="comparative[training]"]').val('');
    
    computeTotalHours();
}

// ==========================
// AUTO RESET ON POSITION / SCHOOL CHANGE
// ==========================
$('#position_applied, #school_id').on('change', function () {
    $('#trainingContainer').empty();
    trainingIndex = 1;

    $('#training_summary').html('<span class="text-muted">No trainings added.</span>');
    $('#modal_training_summary').html('<div class="alert alert-warning p-2">No trainings added</div>');
    $('#total_training_hours').text('0');
    $('input[name="comparative[training]"]').val('');
    $('#training_remark').html('<span class="text-muted">Waiting for QS</span>');

    Swal.fire({
        icon: 'info',
        title: 'Training Reset',
        text: 'Position or school changed. Please re-enter trainings.',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500
    });

    let position = $('#position_applied').val();
    let level    = $('#school_id').find(':selected').data('level');

    if (!position || !level) return;

    // Fetch QS data including training hours AND required level
    $.get('/get-qs', { level, position }, function (res) {
        if (res.success) {
            const requiredHours = parseInt(res.data.training_hours || 0);
            const requiredLevel = getQualificationLevel(requiredHours);
            updateTrainingQS(requiredHours, requiredLevel);
        }
    });
});

// ==========================
// INIT ON LOAD
// ==========================
$(document).ready(function () {
    // Initialize required level based on initial required hours
    const initialRequiredLevel = getQualificationLevel(requiredTrainingHours);
    requiredTrainingLevel = initialRequiredLevel;
    
    // Set initial modal summary
    if (requiredTrainingHours > 0) {
        $('#modal_training_summary').html(`
            <div class="alert alert-info p-2">
                <strong>Training Summary</strong><br>
                Required Hours: ${requiredTrainingHours} hours<br>
                Required Level: ${requiredTrainingLevel}<br>
                Status: <span class="text-muted">No trainings added</span><br>
                <strong>Points: 0</strong>
            </div>
        `);
    } else {
        $('#modal_training_summary').html('<div class="alert alert-warning p-2">Waiting for QS requirements</div>');
    }
    
    computeTotalHours();
});