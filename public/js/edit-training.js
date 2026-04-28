// edit-training.js

let requiredTrainingHours = window.requiredTrainingHours || 0;
let currentTrainingRemarks = '';
let currentTrainingFile = '';

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
    if (isNaN(hours) || hours < 0) return 0;
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

function loadTrainingQS() {
    const position = $('#position_applied').val();
    const level = $('#school_id').find(':selected').data('level');
    
    if (position && level && window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        requiredTrainingHours = parseFloat(window.qsConfig[level][position].training_hours) || 0;
    } else {
        requiredTrainingHours = 0;
    }
}

function updateEditTrainingSummary() {
    const title = $('#edit_training_title').val() || '—';
    const hours = parseFloat($('#edit_training_hours').val()) || 0;
    
    $('#edit_training_title_display').text(title);
    $('#edit_training_hours_display').text(hours);
    
    // Gamitin ang currentTrainingRemarks mula sa database
    let statusText = currentTrainingRemarks || 'Waiting for the QS';
    let statusClass = 'text-muted';
    
    if (statusText === 'MET') {
        statusClass = 'text-success fw-bold';
    } else if (statusText === 'NOT MET') {
        statusClass = 'text-danger fw-bold';
    }
    
    // I-compute ang points
    const applicantLevel = getQualificationLevel(hours);
    const requiredLevel = getQualificationLevel(requiredTrainingHours);
    const increments = Math.max(0, applicantLevel - requiredLevel);
    const trainingPoints = getTrainingPoints(increments);
    
    $('#edit_training_status_display').html(`<span class="${statusClass}">${statusText}</span>`);
    $('#edit_training_points_display').text(`${trainingPoints} pts`);
}

// Helper function para gumawa ng tamang storage URL
function getStorageUrl(filePath) {
    if (!filePath) return null;
    // Remove any leading slashes
    let cleanPath = filePath.replace(/^\/+/, '');
    // Construct the full URL
    return '/storage/' + cleanPath;
}

// ==========================
// EDIT TRAINING BUTTON HANDLER
// ==========================
$(document).on('click', '.edit-training-btn', function() {
    const id = $(this).data('id');
    const title = $(this).data('title');
    const type = $(this).data('type');
    const hours = $(this).data('hours');
    const startDate = $(this).data('start_date');
    const endDate = $(this).data('end_date');
    const filePath = $(this).data('file');
    const remarks = $(this).data('remarks');
    
    console.log('Training ID:', id);
    console.log('File Path:', filePath);
    
    currentTrainingRemarks = remarks || 'Waiting for the QS';
    currentTrainingFile = filePath;
    
    // Load QS requirements
    loadTrainingQS();
    
    // Fill modal fields
    $('#edit_training_id').val(id);
    $('#edit_training_title').val(title || '');
    $('#edit_training_type').val(type || '');
    $('#edit_training_start_date').val(startDate || '');
    $('#edit_training_end_date').val(endDate || '');
    $('#edit_training_current_remarks').val(currentTrainingRemarks);
    
    // Compute hours
    if (type && startDate && endDate) {
        const computedHours = computeTrainingHours(type, startDate, endDate);
        $('#edit_training_hours').val(computedHours);
    } else {
        $('#edit_training_hours').val(hours || 0);
    }
    
    // I-display ang certificate sa modal (gaya ng education)
    if (filePath) {
        const fullUrl = getStorageUrl(filePath);
        console.log('Full URL:', fullUrl);
        
        if (fullUrl) {
            $('#view_training_certificate_link')
                .attr('href', fullUrl)
                .removeClass('d-none');
            $('#training_certificate_iframe').attr('src', fullUrl);
            $('#view_training_certificate_preview').removeClass('d-none');
            $('#no_training_certificate_text').addClass('d-none');
        } else {
            $('#view_training_certificate_link').addClass('d-none');
            $('#view_training_certificate_preview').addClass('d-none');
            $('#no_training_certificate_text').removeClass('d-none');
        }
    } else {
        $('#view_training_certificate_link').addClass('d-none');
        $('#view_training_certificate_preview').addClass('d-none');
        $('#no_training_certificate_text').removeClass('d-none');
    }
    
    // Update summary
    updateEditTrainingSummary();
    
    // Show modal
    $('#editTrainingModal').modal('show');
});

// Auto-compute when fields change
$(document).on('change', '#edit_training_type, #edit_training_start_date, #edit_training_end_date', function() {
    const type = $('#edit_training_type').val();
    const startDate = $('#edit_training_start_date').val();
    const endDate = $('#edit_training_end_date').val();
    
    if (type && startDate && endDate) {
        const hours = computeTrainingHours(type, startDate, endDate);
        $('#edit_training_hours').val(hours);
    }
    
    updateEditTrainingSummary();
});

$(document).on('input', '#edit_training_title', function() {
    updateEditTrainingSummary();
});

// ==========================
// UPDATE TRAINING BUTTON
// ==========================
$('#updateTrainingBtn').on('click', function() {
    const id = $('#edit_training_id').val();
    const title = $('#edit_training_title').val();
    const type = $('#edit_training_type').val();
    const hours = $('#edit_training_hours').val();
    const startDate = $('#edit_training_start_date').val();
    const endDate = $('#edit_training_end_date').val();
    
    if (!title || !type || !startDate || !endDate) {
        Swal.fire('Error', 'All fields are required', 'warning');
        return;
    }
    
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: `/qs/training/update/${id}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            title: title,
            type: type,
            hours: hours,
            start_date: startDate,
            end_date: endDate
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('Success!', 'Training updated successfully', 'success')
                    .then(() => {
                        location.reload();
                    });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
});