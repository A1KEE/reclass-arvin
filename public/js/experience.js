let experienceIndex = 0;
let requiredYears = 0;

// ==========================
// LOAD EXPERIENCE TABLE
// ==========================
function loadExperienceTable() {
    window.experienceIncrementTable = [
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
}

// ==========================
// GET LEVEL FROM YEARS
// ==========================
function getLevelFromYears(years) {
    if (!window.experienceIncrementTable) loadExperienceTable();
    
    const roundedYears = parseFloat(years.toFixed(2));
    
    for (let i = 0; i < window.experienceIncrementTable.length; i++) {
        const level = window.experienceIncrementTable[i];
        if (roundedYears >= level.from && roundedYears < level.to) {
            return level.level;
        }
    }
    
    if (roundedYears >= 15) return 32;
    return 1;
}

// ==========================
// CALCULATE QS POINTS (2,4,6,8,10 RULE)
// ==========================
function calculateQSPoints(actualLevel, requiredLevel) {
    if (actualLevel <= requiredLevel) {
        return 0;
    }
    
    const levelDifference = actualLevel - requiredLevel;
    let points = 0;
    
    // 2,4,6,8,10 rule
    if (levelDifference >= 10) {
        points = 10;
    } else if (levelDifference >= 8) {
        points = 8;
    } else if (levelDifference >= 6) {
        points = 6;
    } else if (levelDifference >= 4) {
        points = 4;
    } else if (levelDifference >= 2) {
        points = 2;
    }
    
    return points;
}

// ==========================
// FETCH QS EXPERIENCE REQUIREMENT
// ==========================
function loadExperienceQS() {
    const level = $('#appliedLevel').val();
    const position = $('#appliedPosition').val();

    // Check from qsConfig
    if (window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        const positionConfig = window.qsConfig[level][position];
        requiredYears = parseFloat(positionConfig.experience_years) || 0;
        
        const requiredLevel = getLevelFromYears(requiredYears);
        
        // DITO: Gamitin ang 'experience' text hindi lang years number
        const experienceText = positionConfig.experience || `${requiredYears} year(s)`;
        
        $('#expRequirementText').html(`
            <strong>${position}</strong><br>
            <small>Required: ${experienceText} (Level ${requiredLevel})</small>
        `);
        
        $('#saveExperienceBtn').prop('disabled', false);
        return;
    }

    // Fallback
    requiredYears = 0;
    $('#expRequirementText').html('Required Experience: —');
    $('#saveExperienceBtn').prop('disabled', true);
}

// ==========================
// ADD EXPERIENCE ROW
// ==========================
$('#addExperience').on('click', function() {
    const html = `
    <div class="col-md-6">
        <div class="experience-item card shadow-sm p-3 position-relative h-100 mb-3">
            <button type="button" class="btn btn-sm btn-outline-danger remove-experience position-absolute top-0 end-0 m-2" style="font-size:0.85rem; padding:0.2rem 0.4rem;">✖</button>

            <div class="mb-2">
                <label class="fw-bold">Position</label>
                <select name="experiences[${experienceIndex}][position]" class="form-control exp_position" required>
                    <option value="">Select Position</option>
                    <option>Teacher I</option>
                    <option>Teacher II</option>
                    <option>Teacher III</option>
                    <option>Teacher IV</option>
                    <option>Teacher V</option>
                    <option>Teacher VI</option>
                    <option>Teacher VII</option>
                    <option>Master Teacher I</option>
                    <option>Master Teacher II</option>
                    <option>Master Teacher III</option>
                </select>
            </div>

            <div class="mb-2">
                <label class="fw-bold">Start Date</label>
                <input type="date" name="experiences[${experienceIndex}][start]" class="form-control exp_start" required>
            </div>

            <div class="mb-2">
                <label class="fw-bold">End Date</label>
                <input type="date" name="experiences[${experienceIndex}][end]" class="form-control exp_end" required>
            </div>
        </div>
    </div>`;

    $('#experienceContainer').append(html);
    experienceIndex++;
});

// ==========================
// REMOVE EXPERIENCE ROW
// ==========================
$(document).on('click', '.remove-experience', function() {
    $(this).closest('.col-md-6').remove();
    computeExperienceTotal();
});

// ==========================
// COMPUTE TOTAL EXPERIENCE
// ==========================
function computeExperienceTotal() {
    let totalYears = 0;
    let allItemsValid = true;

    $('.experience-item').each(function() {
        const start = new Date($(this).find('.exp_start').val());
        const end = new Date($(this).find('.exp_end').val());

        if (!isNaN(start) && !isNaN(end) && end >= start) {
            let years = end.getFullYear() - start.getFullYear();
            let months = end.getMonth() - start.getMonth();
            let days = end.getDate() - start.getDate();
            
            if (days < 0) {
                months--;
                days += 30;
            }
            
            if (months < 0) { 
                years--; 
                months += 12; 
            }
            
            // Convert to decimal years
            const decimalYears = years + (months / 12) + (days / 365);
            totalYears += decimalYears;
        } else {
            allItemsValid = false;
        }
    });

    // Get levels
    const actualLevel = getLevelFromYears(totalYears);
    const requiredLevel = getLevelFromYears(requiredYears);
    
    // Calculate QS Points
    const qsPoints = calculateQSPoints(actualLevel, requiredLevel);
    const levelDifference = actualLevel - requiredLevel;
    
    // Update modal summary (like training)
    const modalSummary = $('#experience_summary_modal');
    if (totalYears > 0 && requiredYears > 0) {
        const status = totalYears >= requiredYears ? 
            '<span class="text-success fw-bold">MET</span>' : 
            '<span class="text-danger fw-bold">NOT MET</span>';
        
        modalSummary.html(`
            <div class="alert alert-info p-2">
                <strong>Experience Summary</strong><br>
                Total Years: ${totalYears.toFixed(2)} years<br>
                Applicant Level: ${actualLevel}<br>
                Required Years: ${requiredYears} year(s)<br>
                Required Level: ${requiredLevel}<br>
                Level Difference: ${levelDifference}<br>
                Status: ${status}<br>
                <strong>Points: ${qsPoints}</strong>
            </div>
        `);
    } else {
        modalSummary.html('<div class="alert alert-warning p-2">No experiences added or waiting for QS</div>');
    }

    // Return result
    return {
        totalYears: parseFloat(totalYears.toFixed(2)),
        actualLevel: actualLevel,
        requiredLevel: requiredLevel,
        levelDifference: levelDifference,
        qsPoints: qsPoints,
        isValid: allItemsValid
    };
}

// ==========================
// SAVE EXPERIENCE
// ==========================
// ==========================
// SAVE EXPERIENCE
// ==========================
$('#saveExperienceBtn').on('click', function() {
    const result = computeExperienceTotal();
    
    // Validate
    let incomplete = false;
    $('.experience-item').each(function() {
        const pos = $(this).find('.exp_position').val();
        const start = $(this).find('.exp_start').val();
        const end = $(this).find('.exp_end').val();
        
        if (!pos || !start || !end) {
            incomplete = true;
        }
    });
    
    if (incomplete || !result.isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete or Invalid Data',
            text: 'Please complete all fields with valid dates.'
        });
        return;
    }

    // Build summary for main table (POSITION, PERIOD, YEAR only) - like training
    let summary = '';
    let experienceList = [];
    
    $('.experience-item').each(function() {
        const pos = $(this).find('.exp_position').val();
        const start = $(this).find('.exp_start').val();
        const end = $(this).find('.exp_end').val();
        
        if (pos && start && end) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            let years = endDate.getFullYear() - startDate.getFullYear();
            let months = endDate.getMonth() - startDate.getMonth();
            if (months < 0) {
                years--;
                months += 12;
            }
            
            const formattedYears = years + (months / 12);
            
            // Format dates like "Jan 2020"
            const startFormatted = startDate.toLocaleDateString('en-US', { 
                month: 'short', 
                year: 'numeric' 
            });
            const endFormatted = endDate.toLocaleDateString('en-US', { 
                month: 'short', 
                year: 'numeric' 
            });
            
            experienceList.push({
                position: pos,
                period: `${startFormatted} - ${endFormatted}`,
                years: formattedYears.toFixed(2)
            });
        }
    });

    // Create simple list summary like training - WITH POINTS
    if (experienceList.length > 0) {
        summary = experienceList.map(exp => 
            `<div class="mb-1">
                <strong>${exp.position}</strong><br>
                <small>${exp.period} (${exp.years} years)</small>
            </div>`
        ).join('');
        
        summary += `<div class="mt-2 pt-2 border-top">
            <strong>Total: ${result.totalYears} years</strong><br>
            <strong class="text-primary">Points: ${result.qsPoints}</strong>
        </div>`;
    }

    // Update main table (like training)
    $('#experience_summary').html(summary || '<span class="text-muted">No experience added.</span>');
    
    // =========== IMPORTANT: HUWAG BAGUHIN ANG QS COLUMN ===========
    // Panatilihin ang QS requirement text sa QS column
    const level = $('#appliedLevel').val();
    const position = $('#appliedPosition').val();
    let qsExperienceText = '—';
    
    if (window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        qsExperienceText = window.qsConfig[level][position].experience || `${requiredYears} year(s)`;
    }
    
    $('#qs_experience').text(qsExperienceText); // "3 years of teaching experience"
    // =========== END IMPORTANT ===========
    
    // Update comparative points input
    $('input[name="comparative[experience]"]').val(result.qsPoints);
    
    // Update remark in main table
    const remark = $('#experience_remark');
    if (result.totalYears >= requiredYears) {
        remark.html('<span class="text-success fw-bold">MET</span>');
    } else {
        remark.html('<span class="text-danger fw-bold">NOT MET</span>');
    }

    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Experience Saved',
        html: `Successfully saved ${experienceList.length} experience(s)<br>Total Years: ${result.totalYears} years<br>Points: ${result.qsPoints}`,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    // Hide modal
    setTimeout(() => {
        bootstrap.Modal.getInstance(document.getElementById('experienceModal')).hide();
    }, 3100);
});

// ==========================
// AUTO COMPUTE WHEN DATES CHANGE
// ==========================
$(document).on('change', '.exp_start, .exp_end', computeExperienceTotal);

// ==========================
// RESET EXPERIENCE
// ==========================
function resetExperience() {
    $('#experienceContainer').empty();
    $('#experience_summary').html('<span class="text-muted">No experience added.</span>');
    $('#experience_remark').html('<span class="text-muted">Waiting for the QS</span>');
    $('#qs_experience').text('—');
    $('input[name="comparative[experience]"]').val('');
    experienceIndex = 0;
}

// ==========================
// MODAL INITIALIZATION
// ==========================
$(document).ready(function() {
    loadExperienceTable();
    
    // When modal opens
    $('#experienceModal').on('show.bs.modal', function() {
        loadExperienceQS();
        
        // Add modal summary area (like training)
        if ($('#experience_summary_modal').length === 0) {
            $('#experienceContainer').after(`
                <div id="experience_summary_modal" class="mt-3">
                    <div class="alert alert-warning p-2">
                        No experiences added or waiting for QS
                    </div>
                </div>
            `);
        } else {
            $('#experience_summary_modal').html('<div class="alert alert-warning p-2">No experiences added or waiting for QS</div>');
        }
    });
});