// ==========================
// EDIT EXPERIENCE JS
// MULTI-ENTRY EDIT MODAL WITH CERTIFICATE PREVIEW
// ==========================

// ==========================
// GLOBALS
// ==========================
let requiredYears = 0;

// ==========================
// EXPERIENCE LEVEL TABLE
// ==========================
// ==========================
// EXPERIENCE LEVEL TABLE (SYNCED WITH ADD EXPERIENCE)
// ==========================
const experienceLevels = [
    { level: 1, from: 0, to: 0.5 },
    { level: 2, from: 0.5, to: 1 },
    { level: 3, from: 1, to: 1.5 },
    { level: 4, from: 1, to: 2 },
    { level: 5, from: 1, to: 2.5 },
    { level: 6, from: 2, to: 3 },
    { level: 7, from: 2, to: 3.5 },
    { level: 8, from: 3, to: 4 },
    { level: 9, from: 3, to: 4.5 },
    { level: 10, from: 4, to: 5 },
    { level: 11, from: 4, to: 5.5 },
    { level: 12, from: 5, to: 6 },
    { level: 13, from: 5, to: 6.5 },
    { level: 14, from: 6, to: 6.5 },
    { level: 15, from: 6, to: 7 },
    { level: 16, from: 7, to: 7.5 },
    { level: 17, from: 7, to: 8 },
    { level: 18, from: 8, to: 8.5 },
    { level: 19, from: 8, to: 9 },
    { level: 20, from: 9, to: 9.5 },
    { level: 21, from: 9, to: 10 },
    { level: 22, from: 10, to: 10.5 },
    { level: 23, from: 10, to: 11 },
    { level: 24, from: 11, to: 11.5 },
    { level: 25, from: 11, to: 12 },
    { level: 26, from: 12, to: 12.5 },
    { level: 27, from: 12, to: 13 },
    { level: 28, from: 13, to: 13.5 },
    { level: 29, from: 13, to: 14 },
    { level: 30, from: 14, to: 14.5 },
    { level: 31, from: 14, to: 15 },
    { level: 32, from: 15, to: 999 }
];
// ==========================
// HELPER FUNCTIONS
// ==========================
function getLevelFromYears(years) {
    years = parseFloat(years);
    if (isNaN(years) || years < 0) return 1;
    const found = experienceLevels.find(l => years >= l.from && years < l.to);
    return found ? found.level : 31;
}

function calculateQSPoints(actualLevel, requiredLevel) {
    if (actualLevel <= requiredLevel) return 0;
    const diff = actualLevel - requiredLevel;
    if (diff >= 10) return 10;
    if (diff >= 8) return 8;
    if (diff >= 6) return 6;
    if (diff >= 4) return 4;
    if (diff >= 2) return 2;
    return 0;
}

function computeYears(start, end) {
    if (!start) return 0;
    const s = new Date(start);
    const e = end ? new Date(end) : new Date();

    if (e < s) return 0;

    let years = e.getFullYear() - s.getFullYear();
    let months = e.getMonth() - s.getMonth();
    let days = e.getDate() - s.getDate();

    if (days < 0) { 
        months--; 
    }
    if (months < 0) { 
        years--; 
        months += 12; 
    }

    return years + (months / 12);
}

function formatYears(y) {
    const yrs = Math.floor(y);
    const m = Math.round((y - yrs) * 12);
    if (yrs === 0 && m === 0) return '0 yrs';
    if (yrs === 0) return `${m} month${m !== 1 ? 's' : ''}`;
    if (m === 0) return `${yrs} year${yrs !== 1 ? 's' : ''}`;
    return `${yrs} year${yrs !== 1 ? 's' : ''} and ${m} month${m !== 1 ? 's' : ''}`;
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
// ==========================
// LOAD REQUIRED YEARS FROM QS CONFIG
// ==========================
// ==========================
// LOAD REQUIRED YEARS - GAMITIN ANG SAVED VALUE
// ==========================
// ==========================
// LOAD REQUIRED YEARS - GAMITIN ANG SAVED VALUE
// ==========================
function loadRequiredYears() {
    // ✅ Gamitin ang saved required years mula sa database/qs config
    if (window.savedExperience && window.savedExperience.required_years !== undefined) {
        requiredYears = window.savedExperience.required_years;
        $('#edit_required_years_display').text(requiredYears);
        console.log('Using saved required_years:', requiredYears);
        updateSummary();
        return;
    }
    
    // Fallback kung walang saved value (kunin sa qsConfig)
    const position = $('#position_applied').val();
    const level = getSelectedLevel();
    
    if (window.qsConfig && level && position && window.qsConfig[level] && window.qsConfig[level][position]) {
        requiredYears = parseFloat(window.qsConfig[level][position].experience_years) || 0;
    } else {
        requiredYears = 0;
    }
    
    $('#edit_required_years_display').text(requiredYears);
    updateSummary();
}

// ==========================
// GENERATE POSITION HTML BASED ON SCHOOL TYPE
// ==========================
function getPositionHtml(schoolType, currentPosition, index) {
    if (schoolType === 'Public') {
        return `
            <label class="form-label fw-semibold">
                <i class="fas fa-user-tie me-1 text-primary"></i>Position
            </label>
            <select class="form-select exp_position" data-exp-index="${index}" required>
                <option value="">Select Position</option>
                <option value="Teacher I" ${currentPosition === 'Teacher I' ? 'selected' : ''}>Teacher I</option>
                <option value="Teacher II" ${currentPosition === 'Teacher II' ? 'selected' : ''}>Teacher II</option>
                <option value="Teacher III" ${currentPosition === 'Teacher III' ? 'selected' : ''}>Teacher III</option>
                <option value="Teacher IV" ${currentPosition === 'Teacher IV' ? 'selected' : ''}>Teacher IV</option>
                <option value="Teacher V" ${currentPosition === 'Teacher V' ? 'selected' : ''}>Teacher V</option>
                <option value="Teacher VI" ${currentPosition === 'Teacher VI' ? 'selected' : ''}>Teacher VI</option>
                <option value="Teacher VII" ${currentPosition === 'Teacher VII' ? 'selected' : ''}>Teacher VII</option>
                <option value="Master Teacher I" ${currentPosition === 'Master Teacher I' ? 'selected' : ''}>Master Teacher I</option>
                <option value="Master Teacher II" ${currentPosition === 'Master Teacher II' ? 'selected' : ''}>Master Teacher II</option>
                <option value="Master Teacher III" ${currentPosition === 'Master Teacher III' ? 'selected' : ''}>Master Teacher III</option>
                <option value="Master Teacher IV" ${currentPosition === 'Master Teacher IV' ? 'selected' : ''}>Master Teacher IV</option>
                <option value="Master Teacher V" ${currentPosition === 'Master Teacher V' ? 'selected' : ''}>Master Teacher V</option>
            </select>
        `;
    } else {
        return `
            <label class="form-label fw-semibold">
                <i class="fas fa-user-tie me-1 text-primary"></i>Position
            </label>
            <input type="text" 
                   class="form-control exp_position" 
                   value="${escapeHtml(currentPosition)}"
                   placeholder="Enter Position (ex. Science Teacher)"
                   required>
        `;
    }
}

// ==========================
// BUILD EDIT ROW FOR EXISTING EXPERIENCE (NO REMOVE BUTTON)
// ==========================
function buildEditRow(exp, index) {
    const startDateFormatted = exp.start_date ? 
        new Date(exp.start_date).toISOString().split('T')[0] : '';
    const endDateFormatted = exp.end_date ? 
        new Date(exp.end_date).toISOString().split('T')[0] : '';
    
    const certificateUrl = exp.certificate_url || null;
    const hasCertificate = !!certificateUrl;
    const schoolType = exp.school_type || '';
    const currentPosition = exp.position || '';
    
    return `
    <div class="experience-item card border mb-3" data-exp-id="${exp.id || ''}" data-exp-index="${index}">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0 fw-semibold text-warning">
                <i class="fas fa-briefcase me-2"></i>Experience #${index + 1}
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <!-- SCHOOL TYPE -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-building me-1 text-primary"></i>School Type
                    </label>
                    <select class="form-select exp_school_type" data-exp-index="${index}" required>
                        <option value="">Select school type</option>
                        <option value="Public" ${schoolType === 'Public' ? 'selected' : ''}>Public School</option>
                        <option value="Private" ${schoolType === 'Private' ? 'selected' : ''}>Private School</option>
                    </select>
                </div>
                
                <!-- SCHOOL NAME -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-school me-1 text-primary"></i>School Name
                    </label>
                    <input type="text" 
                           class="form-control exp_school" 
                           value="${escapeHtml(exp.school || '')}"
                           placeholder="Enter school name"
                           required>
                </div>
                
                <!-- POSITION (dynamic based on school type) -->
                <div class="col-md-12 position-wrapper">
                    ${getPositionHtml(schoolType, currentPosition, index)}
                </div>
                
                <!-- START DATE -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>Start Date
                    </label>
                    <input type="date" 
                           class="form-control exp_start" 
                           value="${startDateFormatted}"
                           required>
                </div>
                
                <!-- END DATE -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar-check me-1 text-primary"></i>End Date
                    </label>
                    <input type="date" 
                           class="form-control exp_end" 
                           value="${endDateFormatted}">
                    <small class="text-muted">Leave blank if Present</small>
                </div>
            </div>
            
            <!-- VIEW CERTIFICATE SECTION -->
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

// ==========================
// HANDLE SCHOOL TYPE CHANGE (Dynamic Position Field)
// ==========================
function handleSchoolTypeChange(selectElement) {
    const container = $(selectElement).closest('.experience-item');
    const schoolType = $(selectElement).val();
    const positionWrapper = container.find('.position-wrapper');
    const currentPosition = container.find('.exp_position').val() || '';
    const expIndex = container.data('exp-index');
    
    if (schoolType === 'Public') {
        positionWrapper.html(`
            <label class="form-label fw-semibold">
                <i class="fas fa-user-tie me-1 text-primary"></i>Position
            </label>
            <select class="form-select exp_position" data-exp-index="${expIndex}" required>
                <option value="">Select Position</option>
                <option value="Teacher I" ${currentPosition === 'Teacher I' ? 'selected' : ''}>Teacher I</option>
                <option value="Teacher II" ${currentPosition === 'Teacher II' ? 'selected' : ''}>Teacher II</option>
                <option value="Teacher III" ${currentPosition === 'Teacher III' ? 'selected' : ''}>Teacher III</option>
                <option value="Teacher IV" ${currentPosition === 'Teacher IV' ? 'selected' : ''}>Teacher IV</option>
                <option value="Teacher V" ${currentPosition === 'Teacher V' ? 'selected' : ''}>Teacher V</option>
                <option value="Teacher VI" ${currentPosition === 'Teacher VI' ? 'selected' : ''}>Teacher VI</option>
                <option value="Teacher VII" ${currentPosition === 'Teacher VII' ? 'selected' : ''}>Teacher VII</option>
                <option value="Master Teacher I" ${currentPosition === 'Master Teacher I' ? 'selected' : ''}>Master Teacher I</option>
                <option value="Master Teacher II" ${currentPosition === 'Master Teacher II' ? 'selected' : ''}>Master Teacher II</option>
                <option value="Master Teacher III" ${currentPosition === 'Master Teacher III' ? 'selected' : ''}>Master Teacher III</option>
                <option value="Master Teacher IV" ${currentPosition === 'Master Teacher IV' ? 'selected' : ''}>Master Teacher IV</option>
                <option value="Master Teacher V" ${currentPosition === 'Master Teacher V' ? 'selected' : ''}>Master Teacher V</option>
            </select>
        `);
    } else if (schoolType === 'Private') {
        positionWrapper.html(`
            <label class="form-label fw-semibold">
                <i class="fas fa-user-tie me-1 text-primary"></i>Position
            </label>
            <input type="text" 
                   class="form-control exp_position" 
                   value="${escapeHtml(currentPosition)}"
                   placeholder="Enter Position (ex. Science Teacher)"
                   required>
        `);
    }
}

// ==========================
// COMPUTE TOTAL YEARS
// ==========================
function computeTotalYears() {
    let total = 0;
    
    $('.experience-item').each(function() {
        const start = $(this).find('.exp_start').val();
        const end = $(this).find('.exp_end').val();
        total += computeYears(start, end);
    });
    
    return parseFloat(total.toFixed(2));
}
// ==========================
// UPDATE SUMMARY DISPLAY
// ==========================
function updateSummary() {
    const totalYears = computeTotalYears();
    const actualLevel = getLevelFromYears(totalYears);
    const requiredLevel = getLevelFromYears(requiredYears);
    
    // ✅ Compute points para sa live preview
    const computedPoints = calculateQSPoints(actualLevel, requiredLevel);
    const computedStatus = totalYears >= requiredYears ? 'MET' : 'NOT MET';

       // 🔴 DEBUG
    console.log('=== UPDATE SUMMARY ===');
    console.log('totalYears:', totalYears);
    console.log('requiredYears:', requiredYears);
    console.log('actualLevel:', actualLevel);
    console.log('requiredLevel:', requiredLevel);
    console.log('computedPoints:', computedPoints);
    console.log('======================');
    
    // I-display ang live computed values (para makita ni QS editor ang magiging puntos)
    $('#edit_total_years_display').text(formatYears(totalYears));
    $('#edit_required_years_display').text(requiredYears);
    $('#edit_points_display').text(computedPoints + ' pts');
    
    const statusClass = computedStatus === 'MET' ? 'text-success fw-bold' : 'text-danger fw-bold';
    $('#edit_status_display').html(`<span class="${statusClass}">${computedStatus}</span>`);
}
// ==========================
// LOAD ALL EXPERIENCES TO MODAL
// ==========================
function loadExperiencesToModal() {
    const experiences = $('#editExperienceBtn').data('experiences');
    const container = $('#editExperiencesContainer');
    
    container.empty();
    
    // I-load ang required years mula sa QS config (live)
    loadRequiredYears();
    
    if (!experiences || experiences.length === 0) {
        container.html(`<div class="alert alert-info text-center">No experience records found.</div>`);
        return;
    }
    
    experiences.forEach((exp, index) => {
        container.append(buildEditRow(exp, index));
    });
    
    // I-compute ang initial summary
    updateSummary();
}

// ==========================
// SAVE ALL EXPERIENCES
// ==========================
function saveAllExperiences() {
    let payload = [];
    let hasError = false;
    let errorMessage = '';
    
    $('.experience-item').removeClass('border-danger');
    
    $('.experience-item').each(function() {
        const expId = $(this).data('exp-id');
        const school = $(this).find('.exp_school').val();
        const schoolType = $(this).find('.exp_school_type').val();
        const position = $(this).find('.exp_position').val();
        const startDate = $(this).find('.exp_start').val();
        const endDate = $(this).find('.exp_end').val();
        
        if (!position || !school || !schoolType || !startDate) {
            hasError = true;
            $(this).addClass('border-danger');
            errorMessage = 'Please fill in all required fields.';
            return false;
        }
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            hasError = true;
            $(this).addClass('border-danger');
            errorMessage = 'End date cannot be earlier than start date.';
            return false;
        }
        
        payload.push({
            id: expId,
            position: position,
            school: school,
            school_type: schoolType,
            start_date: startDate,
            end_date: endDate || null
        });
    });
    
    if (hasError) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete or Invalid Data',
            text: errorMessage
        });
        return;
    }
    
    Swal.fire({
        title: 'Updating...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
    url: '/qs/update-experiences',
    method: 'POST',
    data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        application_id: $('#application_id').val(), // IDAGDAG ITO
        experiences: payload
    },
        success: function(response) {
    if (response.data) {
        // ✅ I-update ang saved values
        window.savedExperience = {
            points: response.data.points,
            remarks: response.data.remarks,
            required_years: response.data.required_years
        };
        
        // I-update agad ang display
        $('#edit_points_display').text(response.data.points + ' pts');
        const statusClass = response.data.remarks === 'MET' ? 'text-success fw-bold' : 'text-danger fw-bold';
        $('#edit_status_display').html(`<span class="${statusClass}">${response.data.remarks}</span>`);
        $('#edit_required_years_display').text(response.data.required_years);
    }
    
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        location.reload();
    });
}
    });
}

// ==========================
// EVENT LISTENERS
// ==========================

// Open modal when Edit button is clicked
$(document).on('click', '#editExperienceBtn', function(e) {
    e.preventDefault();
    loadExperiencesToModal();
    $('#editExperienceModal').modal('show');
});

// Handle school type change (dynamic position field)
$(document).on('change', '.exp_school_type', function() {
    handleSchoolTypeChange(this);
});

// Update summary when dates change
$(document).on('change', '.exp_start, .exp_end', function() {
    updateSummary();
});

// Save button click
$(document).on('click', '#updateExperienceBtn', function(e) {
    e.preventDefault();
    saveAllExperiences();
});

// Recalculate when modal is shown
$('#editExperienceModal').on('shown.bs.modal', function() {
    loadRequiredYears();
    updateSummary();
});

console.log('MULTI EDIT EXPERIENCE JS LOADED (No Remove Button, Dynamic Position)');