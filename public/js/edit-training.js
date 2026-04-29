// ==========================
// EDIT TRAINING JS - MULTI-ENTRY VERSION
// ==========================

let requiredTrainingHours = 0;

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

function getQualificationLevel(hours) {
    hours = parseFloat(hours);
    if (isNaN(hours) || hours < 0) return 1;
    const found = trainingLevels.find(level => hours >= level.from && hours < level.to);
    return found ? found.level : 31;
}

function getTrainingPoints(increment) {
    if (increment >= 10) return 10;
    if (increment >= 8) return 8;
    if (increment >= 6) return 6;
    if (increment >= 4) return 4;
    if (increment >= 2) return 2;
    return 0;
}

function computeTrainingHours(type, startDate, endDate) {
    if (!type || !startDate || !endDate) return 0;
    const start = new Date(startDate);
    const end = new Date(endDate);
    const dayCount = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
    if (type === 'Face-to-Face') return dayCount * 8;
    if (type === 'Online') return dayCount * 3;
    return 0;
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function loadRequiredTrainingHours() {
    // ✅ Una, gamitin ang saved required hours mula sa database
    if (window.savedTraining && window.savedTraining.required_hours !== undefined) {
        requiredTrainingHours = window.savedTraining.required_hours;
        $('#edit_required_hours_display').text(requiredTrainingHours);
        console.log('Using saved required_hours:', requiredTrainingHours);
        return;
    }
    
    // Fallback: kunin mula sa qsConfig
    const position = $('#position_applied').val();
    const level = getSelectedLevel();
    
    if (window.qsConfig && level && position && window.qsConfig[level] && window.qsConfig[level][position]) {
        requiredTrainingHours = parseFloat(window.qsConfig[level][position].training_hours) || 0;
    } else {
        requiredTrainingHours = 0;
    }
    
    $('#edit_required_hours_display').text(requiredTrainingHours);
}

function buildTrainingEditRow(training, index) {
    const startDateFormatted = training.start_date ? 
        new Date(training.start_date).toISOString().split('T')[0] : '';
    const endDateFormatted = training.end_date ? 
        new Date(training.end_date).toISOString().split('T')[0] : '';
    
    const certificateUrl = training.certificate_url || null;
    const hasCertificate = !!certificateUrl;
    
    return `
    <div class="training-item card border mb-3" data-training-id="${training.id || ''}">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0 fw-semibold text-warning">
                <i class="fas fa-chalkboard-teacher me-2"></i>Training #${index + 1}
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-graduation-cap me-1 text-primary"></i>Training Title
                    </label>
                    <input type="text" 
                           class="form-control training_title" 
                           value="${escapeHtml(training.title || '')}"
                           placeholder="Enter training title"
                           required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-chalkboard-user me-1 text-primary"></i>Training Type
                    </label>
                    <select class="form-select training_type" required>
                        <option value="">Select training type</option>
                        <option value="Face-to-Face" ${training.type === 'Face-to-Face' ? 'selected' : ''}>Face-to-Face</option>
                        <option value="Online" ${training.type === 'Online' ? 'selected' : ''}>Online</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-clock me-1 text-primary"></i>No. of Hours
                    </label>
                    <input type="number" 
                           class="form-control training_hours bg-light" 
                           value="${training.hours || 0}"
                           readonly>
                    <small class="text-muted">Automatically computed from dates</small>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>Start Date
                    </label>
                    <input type="date" 
                           class="form-control training_start_date" 
                           value="${startDateFormatted}"
                           required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar-check me-1 text-primary"></i>End Date
                    </label>
                    <input type="date" 
                           class="form-control training_end_date" 
                           value="${endDateFormatted}"
                           required>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 fw-semibold text-primary">
                        <i class="fas fa-eye me-2"></i>View Certificate
                    </h6>
                </div>
                <div class="card-body p-3 text-center">
                    ${hasCertificate ? `
                        <a href="${certificateUrl}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-pdf me-1"></i>Open Certificate
                        </a>
                        <div class="mt-2 border rounded p-2 bg-light">
                            <iframe src="${certificateUrl}" style="width:100%; height:300px; border:none;"></iframe>
                        </div>
                    ` : `
                        <div class="text-muted">No certificate uploaded</div>
                    `}
                </div>
            </div>
        </div>
    </div>
    `;
}
function computeTotalHours() {
    let total = 0;
    $('.training-item').each(function() {
        const hours = parseFloat($(this).find('.training_hours').val()) || 0;
        const title = $(this).find('.training_title').val() || '';
        
        // ✅ Filter: Non-teaching keywords should be excluded
        if (isTeachingRelevant(title)) {
            total += hours;
        }
    });
    return total;
}

// ==========================
// IS TEACHING RELEVANT?
// ==========================
function isTeachingRelevant(title) {
    if (!title) return false;
    const nonTeachingKeywords = [
        "administrative", "accounting", "finance", "management",
        "ict", "computer", "leadership", "seminar", "orientation", "workshop"
    ];
    const titleLower = title.toLowerCase();
    for (let kw of nonTeachingKeywords) {
        if (titleLower.includes(kw)) return false;
    }
    return true;
}

function updateTrainingSummary() {
    const totalHours = computeTotalHours();  // Ito ay nagfa-filter na ng non-teaching
    const actualLevel = getQualificationLevel(totalHours);
    const requiredLevel = getQualificationLevel(requiredTrainingHours);
    const points = getTrainingPoints(actualLevel - requiredLevel);
    const status = totalHours >= requiredTrainingHours ? 'MET' : 'NOT MET';
    
    // Live update ng display
    $('#edit_total_hours_display').text(totalHours);
    $('#edit_required_hours_display').text(requiredTrainingHours);
    $('#edit_training_points_display').text(points + ' pts');
    
    const statusClass = status === 'MET' ? 'text-success fw-bold' : 'text-danger fw-bold';
    $('#edit_training_status_display').html(`<span class="${statusClass}">${status}</span>`);
    
    // Debug
    console.log('Live Update - Total Hours:', totalHours, 'Points:', points);
}
function loadTrainingsToModal() {
    const trainings = $('#editTrainingBtn').data('trainings');
    const container = $('#editTrainingsContainer');
    
    container.empty();
    loadRequiredTrainingHours();
    
    if (!trainings || trainings.length === 0) {
        container.html(`<div class="alert alert-info text-center">No training records found.</div>`);
        return;
    }
    
    trainings.forEach((training, index) => {
        container.append(buildTrainingEditRow(training, index));
    });
    
    // ✅ Live update agad pagka-load
    updateTrainingSummary();
}
function saveAllTrainings() {
    let payload = [];
    let hasError = false;
    
    $('.training-item').removeClass('border-danger');
    
    $('.training-item').each(function() {
        const id = $(this).data('training-id');
        const title = $(this).find('.training_title').val();
        const type = $(this).find('.training_type').val();
        const startDate = $(this).find('.training_start_date').val();
        const endDate = $(this).find('.training_end_date').val();
        
        let hours = 0;
        if (type && startDate && endDate) {
            hours = computeTrainingHours(type, startDate, endDate);
        }
        
        if (!title || !type || !startDate || !endDate) {
            hasError = true;
            $(this).addClass('border-danger');
            return false;
        }
        
        payload.push({ 
            id: id, 
            title: title, 
            type: type, 
            hours: hours, 
            start_date: startDate, 
            end_date: endDate 
        });
    });
    
    if (hasError) {
        Swal.fire('Warning', 'Please fill in all required fields.', 'warning');
        return;
    }
    
    // ✅ DEBUG: Ipakita ang payload sa console
    console.log('Saving trainings payload:', payload);
    console.log('Application ID:', $('#application_id').val());
    
    Swal.fire({ 
        title: 'Updating...', 
        allowOutsideClick: false, 
        didOpen: () => Swal.showLoading() 
    });
    
    $.ajax({
        url: '/qs/trainings/update',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            application_id: $('#application_id').val(),
            trainings: payload
        },
        success: function(response) {
            console.log('AJAX Success Response:', response);
            
            if (response.success) {
                if (response.data) {
                    console.log('Points from server:', response.data.points);
                    window.savedTraining = {
                        points: response.data.points,
                        remarks: response.data.remarks,
                        required_hours: response.data.required_hours
                    };
                }
                
                Swal.fire('Success!', 'All training records updated.', 'success')
                    .then(() => location.reload());
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr);
            console.error('Response Text:', xhr.responseText);
            Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
        }
    });
}
// ==========================
// LIVE UPDATE - Kapag nagta-type sa training title
// ==========================
$(document).on('input', '.training_title', function() {
    updateTrainingSummary();
});

// ==========================
// LIVE UPDATE - Kapag nagbabago ng type o dates
// ==========================
$(document).on('change', '.training_type, .training_start_date, .training_end_date', function() {
    const container = $(this).closest('.training-item');
    const type = container.find('.training_type').val();
    const startDate = container.find('.training_start_date').val();
    const endDate = container.find('.training_end_date').val();
    
    if (type && startDate && endDate) {
        const hours = computeTrainingHours(type, startDate, endDate);
        container.find('.training_hours').val(hours);
    }
    updateTrainingSummary();
});

// ==========================
// OPEN MODAL
// ==========================
$(document).on('click', '#editTrainingBtn', function(e) {
    e.preventDefault();
    loadTrainingsToModal();
    $('#editTrainingModal').modal('show');
});

// ==========================
// SAVE ALL
// ==========================
$(document).on('click', '#updateAllTrainingsBtn', function(e) {
    e.preventDefault();
    saveAllTrainings();
});

// ==========================
// MODAL SHOWN
// ==========================
$('#editTrainingModal').on('shown.bs.modal', function() {
    loadRequiredTrainingHours();
    updateTrainingSummary();
});

console.log('MULTI EDIT TRAINING JS LOADED');