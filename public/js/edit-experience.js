// edit-experience.js

let currentExperienceFile = '';
let requiredYears = 0;

// Experience levels mapping
const experienceLevels = [
    { level: 1, from: 0, to: 0.5 },
    { level: 2, from: 0.5, to: 1 },
    { level: 3, from: 1, to: 1.5 },
    { level: 4, from: 1.5, to: 2 },
    { level: 5, from: 2, to: 2.5 },
    { level: 6, from: 2.5, to: 3 },
    { level: 7, from: 3, to: 3.5 },
    { level: 8, from: 3.5, to: 4 },
    { level: 9, from: 4, to: 4.5 },
    { level: 10, from: 4.5, to: 5 },
    { level: 11, from: 5, to: 5.5 },
    { level: 12, from: 5.5, to: 6 },
    { level: 13, from: 6, to: 6.5 },
    { level: 14, from: 6.5, to: 7 },
    { level: 15, from: 7, to: 7.5 },
    { level: 16, from: 7.5, to: 8 },
    { level: 17, from: 8, to: 8.5 },
    { level: 18, from: 8.5, to: 9 },
    { level: 19, from: 9, to: 9.5 },
    { level: 20, from: 9.5, to: 10 },
    { level: 21, from: 10, to: 10.5 },
    { level: 22, from: 10.5, to: 11 },
    { level: 23, from: 11, to: 11.5 },
    { level: 24, from: 11.5, to: 12 },
    { level: 25, from: 12, to: 12.5 },
    { level: 26, from: 12.5, to: 13 },
    { level: 27, from: 13, to: 13.5 },
    { level: 28, from: 13.5, to: 14 },
    { level: 29, from: 14, to: 14.5 },
    { level: 30, from: 14.5, to: 15 },
    { level: 31, from: 15, to: 999 }
];

function getLevelFromYears(years) {
    years = parseFloat(years);
    if (isNaN(years) || years < 0) return 1;
    const found = experienceLevels.find(level => years >= level.from && years < level.to);
    return found ? found.level : 31;
}

function calculateQSPoints(actualLevel, requiredLevel) {
    if (actualLevel <= requiredLevel) return 0;
    const levelDifference = actualLevel - requiredLevel;
    if (levelDifference >= 10) return 10;
    if (levelDifference >= 8) return 8;
    if (levelDifference >= 6) return 6;
    if (levelDifference >= 4) return 4;
    if (levelDifference >= 2) return 2;
    return 0;
}

function formatYearsMonths(decimalYears) {
    const years = Math.floor(decimalYears);
    const months = Math.round((decimalYears - years) * 12);
    if (years === 0 && months === 0) return 'Less than 1 month';
    let result = '';
    if (years > 0) result += `${years} year${years !== 1 ? 's' : ''}`;
    if (months > 0) result += `${result ? ' and ' : ''}${months} month${months !== 1 ? 's' : ''}`;
    return result;
}

function computeYearsFromDates(startDate, endDate) {
    if (!startDate) return 0;
    const start = new Date(startDate);
    const end = endDate ? new Date(endDate) : new Date();
    if (end < start) return 0;
    
    let years = end.getFullYear() - start.getFullYear();
    let months = end.getMonth() - start.getMonth();
    let days = end.getDate() - start.getDate();
    
    if (days < 0) {
        months--;
        days += new Date(end.getFullYear(), end.getMonth(), 0).getDate();
    }
    if (months < 0) {
        years--;
        months += 12;
    }
    
    return years + (months / 12) + (days / 365);
}

function loadRequiredYears() {
    const position = $('#position_applied').val();
    const level = $('#school_id').find(':selected').data('level');
    
    if (position && level && window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        requiredYears = parseFloat(window.qsConfig[level][position].experience_years) || 0;
    } else {
        requiredYears = 0;
    }
}

function updateEditExperienceSummary() {
    const position = $('#edit_experience_position').val() || '—';
    const startDate = $('#edit_experience_start_date').val();
    const endDate = $('#edit_experience_end_date').val();
    
    let years = computeYearsFromDates(startDate, endDate);
    years = parseFloat(years.toFixed(2));
    
    $('#edit_experience_position_display').text(position);
    $('#edit_experience_years_display').text(formatYearsMonths(years));
    $('#edit_experience_years_computed').val(formatYearsMonths(years));
    
    // Compute points and status
    const actualLevel = getLevelFromYears(years);
    const requiredLevel = getLevelFromYears(requiredYears);
    const points = calculateQSPoints(actualLevel, requiredLevel);
    const remarks = (years >= requiredYears && requiredYears > 0) ? "MET" : "NOT MET";
    
    let statusHtml = '';
    let statusClass = '';
    
    if (requiredYears <= 0) {
        statusHtml = 'Waiting for QS';
        statusClass = 'text-muted';
    } else if (remarks === 'MET') {
        statusHtml = 'MET';
        statusClass = 'text-success fw-bold';
    } else {
        statusHtml = 'NOT MET';
        statusClass = 'text-danger fw-bold';
    }
    
    $('#edit_experience_status_display').html(`<span class="${statusClass}">${statusHtml}</span>`);
    $('#edit_experience_points_display').text(`${points} pts`);
}

// ==========================
// EDIT EXPERIENCE BUTTON HANDLER
// ==========================
$(document).on('click', '.edit-single-experience-btn', function() {
    const id = $(this).data('id');
    const position = $(this).data('position');
    const school = $(this).data('school');
    const schoolType = $(this).data('school_type');
    const startDate = $(this).data('start_date');
    const endDate = $(this).data('end_date');
    const filePath = $(this).data('file');
    
    currentExperienceFile = filePath;
    
    // Load required years
    loadRequiredYears();
    
    // Fill modal fields
    $('#edit_experience_id').val(id);
    $('#edit_experience_position').val(position || '');
    $('#edit_experience_school').val(school || '');
    $('#edit_experience_school_type').val(schoolType || '');
    $('#edit_experience_start_date').val(startDate || '');
    $('#edit_experience_end_date').val(endDate || '');
    
    // Compute and display years
    let years = computeYearsFromDates(startDate, endDate);
    years = parseFloat(years.toFixed(2));
    $('#edit_experience_years_computed').val(formatYearsMonths(years));
    
    // Display certificate
    if (filePath) {
        let cleanPath = filePath.replace(/^\/+/, '');
        const fullUrl = '/storage/' + cleanPath;
        $('#view_experience_certificate_link')
            .attr('href', fullUrl)
            .removeClass('d-none');
        $('#experience_certificate_iframe').attr('src', fullUrl);
        $('#view_experience_certificate_preview').removeClass('d-none');
        $('#no_experience_certificate_text').addClass('d-none');
    } else {
        $('#view_experience_certificate_link').addClass('d-none');
        $('#view_experience_certificate_preview').addClass('d-none');
        $('#no_experience_certificate_text').removeClass('d-none');
    }
    
    // Update summary
    updateEditExperienceSummary();
    
    // Show modal
    $('#editExperienceModal').modal('show');
});

// Auto-compute when dates change
$(document).on('change', '#edit_experience_start_date, #edit_experience_end_date', function() {
    const startDate = $('#edit_experience_start_date').val();
    const endDate = $('#edit_experience_end_date').val();
    
    let years = computeYearsFromDates(startDate, endDate);
    years = parseFloat(years.toFixed(2));
    $('#edit_experience_years_computed').val(formatYearsMonths(years));
    
    updateEditExperienceSummary();
});

$(document).on('input', '#edit_experience_position', function() {
    updateEditExperienceSummary();
});

// ==========================
// UPDATE EXPERIENCE BUTTON
// ==========================
$('#updateExperienceBtn').on('click', function() {
    const id = $('#edit_experience_id').val();
    const position = $('#edit_experience_position').val();
    const school = $('#edit_experience_school').val();
    const schoolType = $('#edit_experience_school_type').val();
    const startDate = $('#edit_experience_start_date').val();
    const endDate = $('#edit_experience_end_date').val();
    
    if (!position || !school || !schoolType || !startDate) {
        Swal.fire('Error', 'Please fill in all required fields', 'warning');
        return;
    }
    
    if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
        Swal.fire('Error', 'End date cannot be before start date', 'warning');
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
        url: `/qs/experience/update/${id}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            position: position,
            school: school,
            school_type: schoolType,
            start_date: startDate,
            end_date: endDate
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    html: `Experience updated!<br>Total Years: ${response.total_years} yrs<br>Status: ${response.remarks}<br>Points: ${response.points}`,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
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

// Open modal from blade button
$('#openEditExperienceModalBtn').on('click', function() {
    // This will be triggered when you click "Edit Experiences" button
    // We need to load the first experience or show list?
    // For now, we'll show a message
    Swal.fire('Info', 'Please click the edit button on a specific experience record', 'info');
});

console.log('edit-experience.js loaded');