// ======================================================
// EDIT EDUCATION MODULE - WITH PROFESSIONAL EDUCATION
// ======================================================

// =====================================
// DEBUG HELPER (PARANG dd)
// =====================================
function dd(label, data) {
    console.log("========== " + label + " ==========");
    console.log(data);
}

// =====================================
// EDUCATION LEVELS MAPPING (ORIGINAL)
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
// PROFESSIONAL EDUCATION LEVELS (ADDED - HINDI NAALIS ANG ORIGINAL)
// =====================================
const professionalEditLevels = {
    "6 units of Professional Education": 32,
    "9 units of Professional Education": 33,
    "12 units of Professional Education": 34,
    "15 units of Professional Education": 35,
    "18 units of Professional Education (Required)": 36
};

// =====================================
// DEBUG: Verify Level Value
// =====================================
function debugLevelValue(text) {
    console.log("=== DEBUG LEVEL VALUE ===");
    console.log("Input text:", text);
    var value = getLevelValue(text);
    console.log("Returned value:", value);
    
    // Hanapin kung ano talaga ang value sa mapping
    for (var label in editEducationLevels) {
        if (label === text) {
            console.log("MATCH FOUND! Label:", label, "Value:", editEducationLevels[label]);
            break;
        }
    }
    
    return value;
}

// Gamitin ito sa console kung kailangan mag-debug
// debugLevelValue("15 units of Doctorate");
// =====================================
// GET LEVEL VALUE (FIXED - supports both)
// =====================================
function getLevelValue(text) {
    if (!text) return 0;
    
    // Check exact match sa original levels
    for (var label in editEducationLevels) {
        if (label === text) {
            return editEducationLevels[label];
        }
    }
    
    // Check exact match sa professional levels
    for (var label2 in professionalEditLevels) {
        if (label2 === text) {
            return professionalEditLevels[label2];
        }
    }
    
    // Fuzzy check for professional education
    if (text.toLowerCase().includes('professional education')) {
        var match = text.match(/(\d+)\s+units/);
        if (match) {
            var units = parseInt(match[1]);
            // Return corresponding level value
            for (var label3 in professionalEditLevels) {
                if (professionalEditLevels[label3] === units + 26) { // 32-36 range
                    return professionalEditLevels[label3];
                }
            }
        }
    }
    
    return 0;
}
// =====================================
// VALUE → TEXT HELPER (INCLUDING PROFESSIONAL)
// =====================================
function getTextFromValue(value) {
    if (!value && value !== 0) return null;
    
    let numValue = parseInt(value);
    
    // Check original levels
    for (let [label, val] of Object.entries(editEducationLevels)) {
        if (val === numValue) {
            return label;
        }
    }
    
    // Check professional levels
    for (let [label, val] of Object.entries(professionalEditLevels)) {
        if (val === numValue) {
            return label;
        }
    }
    
    return null;
}

// =====================================
// CHECK IF PROFESSIONAL EDUCATION
// =====================================
// =====================================
// CHECK IF PROFESSIONAL EDUCATION (IMPROVED)
// =====================================
function isProfessionalEducation(text) {
    if (!text) return false;
    var lowerText = text.toLowerCase();
    // Check for exact phrases
    return lowerText.includes('professional education') || 
           lowerText.includes('professional education (required)') ||
           (lowerText.includes('units of professional') && lowerText.includes('education'));
}

// =====================================
// CHECK IF HIGHER ATTAINMENT (Masters/Doctorate)
// =====================================
function isHigherAttainment(text) {
    if (!text) return false;
    var lowerText = text.toLowerCase();
    return lowerText.includes('masters') || lowerText.includes('doctorate') || lowerText.includes('doctor');
}

// =====================================
// CHECK IF NON-EDUCATION DEGREE
// =====================================
function isNonEducationDegree(degreeName) {
    if (!degreeName) return false;
    
    var text = degreeName.toLowerCase();
    
    var nonEducKeywords = ['bsit', 'it', 'information technology', 'computer science', 
                           'engineering', 'business', 'accountancy', 'nursing', 'psychology',
                           'bsba', 'bsa', 'criminology'];
    
    for (var i = 0; i < nonEducKeywords.length; i++) {
        if (text.includes(nonEducKeywords[i])) {
            return true;
        }
    }
    
    return false;
}
// =====================================
// GET LEVEL VALUE - FIXED (Ito ang pinaka-critical)
// =====================================
function getLevelValue(text) {
    if (!text) return 0;
    
    console.log("getLevelValue input:", text);
    
    // Check exact match sa original levels
    for (var label in editEducationLevels) {
        if (label === text) {
            console.log("Found in editEducationLevels:", label, "=", editEducationLevels[label]);
            return editEducationLevels[label];
        }
    }
    
    // Check exact match sa professional levels
    for (var label2 in professionalEditLevels) {
        if (label2 === text) {
            console.log("Found in professionalEditLevels:", label2, "=", professionalEditLevels[label2]);
            return professionalEditLevels[label2];
        }
    }
    
    console.log("No match found, returning 0");
    return 0;
}
// =====================================
// COMPUTE POINTS FROM INCREMENT (2,4,6,8,10)
// =====================================
function computePointsFromIncrement(increment) {
    if (increment >= 10) return 10;
    if (increment >= 8) return 8;
    if (increment >= 6) return 6;
    if (increment >= 4) return 4;
    if (increment >= 2) return 2;
    return 0;
}
// =====================================
// GET CURRENT POSITION - USING WINDOW VARIABLE
// =====================================
function getCurrentPosition() {
    var position = '';
    
    console.log("=== GET CURRENT POSITION ===");
    
    // 1. Check from window.currentPosition (set sa blade)
    if (window.currentPosition && window.currentPosition !== '') {
        position = window.currentPosition;
        console.log("✅ Position from window.currentPosition:", position);
        return position;
    }
    
    // 2. Check from window.applicantData
    if (window.applicantData && window.applicantData.position_applied) {
        position = window.applicantData.position_applied;
        console.log("✅ Position from applicantData:", position);
        return position;
    }
    
    // 3. Check from window.applicationData
    if (window.applicationData && window.applicationData.position_applied) {
        position = window.applicationData.position_applied;
        console.log("✅ Position from applicationData:", position);
        return position;
    }
    
    // 4. Check from hidden input
    if ($('#position_applied').length && $('#position_applied').val()) {
        position = $('#position_applied').val();
        console.log("✅ Position from #position_applied:", position);
        return position;
    }
    
    // 5. Default fallback - check page content
    var bodyText = $('body').text();
    if (bodyText.includes('Master Teacher')) {
        position = 'Master Teacher';
        console.log("⚠️ Position inferred from page:", position);
        return position;
    }
    
    console.log("❌ No position found!");
    return '';
}
// =====================================
// COMPUTE EDIT EDUCATION SCORE - FIXED INCREMENT
// =====================================
function computeEditEducationScore(degree, selectedText, position) {
    // Position ay passed as parameter na
    console.log("Position received:", position);
    
    var BASE_LEVEL_TEACHER = 6;
    var BASE_LEVEL_MASTER_TEACHER = 21;
    
    var levelValue = getLevelValue(selectedText);
    var isNonEduc = isNonEducationDegree(degree);
    var isProfEd = isProfessionalEducation(selectedText);
    var isHigher = isHigherAttainment(selectedText);
    
    // Check for Master Teacher using the passed position
    var positionLower = String(position || '').toLowerCase();
    var isMasterTeacher = positionLower.includes('master teacher');
    
    var baseLevel = isMasterTeacher ? BASE_LEVEL_MASTER_TEACHER : BASE_LEVEL_TEACHER;
    
    console.log("Is Master Teacher:", isMasterTeacher);
    console.log("Base Level:", baseLevel);
    
    var result = {
        remarks: "NOT MET",
        points: 0,
        details: ""
    };
    
    // =============================================
    // CASE 1: NON-EDUCATION DEGREE
    // =============================================
    if (isNonEduc) {
        console.log("NON-EDUCATION DEGREE PATH");
        
        // SUB-CASE A: Professional Education
        if (isProfEd) {
            var profUnits = 0;
            var match = selectedText.match(/(\d+)\s+units/);
            if (match) {
                profUnits = parseInt(match[1]);
            }
            console.log("Professional Units:", profUnits);
            
            if (profUnits === 18) {
                result.remarks = "MET";
                result.points = 0;
                result.details = "18 units Professional Education (MET with 0 points)";
            } else {
                result.remarks = "NOT MET";
                result.points = 0;
                result.details = profUnits + " units Professional Education - Need 18 units";
            }
            return result;
        }
        
        // SUB-CASE B: Higher Attainment (Masters/Doctorate)
        if (isHigher) {
            console.log("NON-EDUC + HIGHER ATTAINMENT PATH");
            // TAMANG increment calculation
            var increment = levelValue - baseLevel;
            if (increment < 0) increment = 0;
            console.log("Increment calculation:", levelValue, "-", baseLevel, "=", increment);
            
            var points = 0;
            if (increment >= 10) points = 10;
            else if (increment >= 8) points = 8;
            else if (increment >= 6) points = 6;
            else if (increment >= 4) points = 4;
            else if (increment >= 2) points = 2;
            
            result.remarks = (levelValue >= baseLevel) ? "MET" : "NOT MET";
            result.points = points;
            result.details = "Non-Educ with " + selectedText + " - Increment: +" + increment + " = " + points + " points";
            return result;
        }
        
        result.details = "Non-Education degree requires Professional Education (18 units) or Higher Attainment";
        return result;
    }
    
    // =============================================
    // CASE 2: EDUCATION DEGREE (Normal scoring)
    // =============================================
    console.log("EDUCATION DEGREE PATH");
    
    // TAMANG increment calculation
    var increment = levelValue - baseLevel;
    if (increment < 0) increment = 0;
    console.log("Increment calculation:", levelValue, "-", baseLevel, "=", increment);
    
    var points = 0;
    if (increment >= 10) points = 10;
    else if (increment >= 8) points = 8;
    else if (increment >= 6) points = 6;
    else if (increment >= 4) points = 4;
    else if (increment >= 2) points = 2;
    
    console.log("Points from increment:", points);
    
    result.remarks = (levelValue >= baseLevel) ? "MET" : "NOT MET";
    result.points = points;
    result.details = "Increment: +" + increment + " = " + points + " points";
    
    console.log("FINAL RESULT:", result);
    return result;
}
// =====================================
// CLICK EDIT BUTTON - WITH CORRECT POSITION
// =====================================
$(document).on('click', '.edit-education-btn', function () {
    console.log("=== EDIT BUTTON CLICKED ===");
    
    let id = $(this).data('id');
    let degree = $(this).data('degree');
    let school = $(this).data('school');
    let date = $(this).data('date');
    let units = $(this).data('units');
    let points = $(this).data('points') || 0;
    let remarks = $(this).data('remarks') || 'NOT MET';
    
    // SET FORM VALUES
    $('#edit_education_id').val(id);
    $('#edit_education_name').val(degree);
    $('#edit_education_school').val(school);
    $('#edit_education_date').val(date);
    
    // REBUILD DROPDOWN
    buildEditUnitsDropdown();
    
    // SELECT OPTION AND UPDATE DISPLAY
    setTimeout(function() {
        if (units) {
            let found = false;
            $('#edit_education_units_select option').each(function() {
                let optText = $(this).text();
                if (optText === units) {
                    $(this).prop('selected', true);
                    found = true;
                    console.log("Selected exact match:", optText);
                    return false;
                }
            });
            
            if (!found && units === "18 units of Professional Education") {
                $('#edit_education_units_select option').each(function() {
                    if ($(this).text() === "18 units of Professional Education (Required)") {
                        $(this).prop('selected', true);
                        found = true;
                        return false;
                    }
                });
            }
        }
        
        let selectedText = $('#edit_education_units_select option:selected').text();
        
        if (selectedText && selectedText !== "Select Education Level") {
            // ✅ TAMANG TAWAG - gamit ang points at remarks galing sa button
            updateEditDisplay(degree, selectedText, points, remarks);
        }
    }, 200);
    
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
    
    $('#editEducationModal').modal('show');
});
// =====================================
// UPDATE EDIT DISPLAY - WITH POSITION FROM WINDOW
// =====================================
function updateEditDisplay(degree, selectedText, pointsFromDb, remarksFromDb) {
    // ✅ Kunin ang position mula sa window variable
    var position = window.currentPosition || '';
    
    console.log("Position used for computation:", position);
    
    var computedResult = computeEditEducationScore(degree, selectedText, position);
    
    var finalRemarks = computedResult.remarks;
    var finalPoints = computedResult.points;
    
    var statusColor = finalRemarks === "MET" ? "success" : "danger";
    var pointsColor = finalPoints > 0 ? "primary" : "secondary";
    
    $('#edit_edu_degree_display').text(degree || '—');
    $('#edit_edu_level_display').text(selectedText || '—');
    $('#edit_edu_status_display').html(`
        <span class="fw-bold text-${statusColor}">${finalRemarks}</span>
        <small class="text-muted d-block">${computedResult.details}</small>
    `);
    $('#edit_edu_points_display').html(`
        <span class="fw-bold text-${pointsColor}">${finalPoints} pts</span>
    `);
    
    $('#edit_education_computed_remarks').val(finalRemarks);
    $('#edit_education_computed_points').val(finalPoints);
}
// =====================================
// BUILD DROPDOWN - WITH PROFESSIONAL EDUCATION OPTIONS
// =====================================
function buildEditUnitsDropdown() {
    let select = $('#edit_education_units_select');
    
    select.empty();
    select.append('<option value="">Select Education Level</option>');
    
    // Add original education levels
    select.append('<optgroup label="📚 Education & Higher Attainment">');
    Object.entries(editEducationLevels).forEach(([label, value]) => {
        select.append(`<option value="${value}">${label}</option>`);
    });
    select.append('</optgroup>');
    
    // Add Professional Education levels
    select.append('<optgroup label="🎓 Professional Education (Non-Education Degree)">');
    Object.entries(professionalEditLevels).forEach(([label, value]) => {
        select.append(`<option value="${value}" class="text-warning">${label}</option>`);
    });
    select.append('</optgroup>');
    
    console.log("Dropdown built with", Object.keys(editEducationLevels).length + Object.keys(professionalEditLevels).length, "options");
}

// =====================================
// LIVE UPDATE KAPAG NAGPALIT NG DROPDOWN
// =====================================
$(document).on('change', '#edit_education_units_select', function() {
    let degree = $('#edit_education_name').val();
    let selectedText = $(this).find('option:selected').text();
    let position = getCurrentPosition();
    
    if (selectedText && selectedText !== "Select Education Level" && degree) {
        // I-compute ang score gamit ang tamang position
        var scoreResult = computeEditEducationScore(degree, selectedText, position);
        
        // I-update ang display gamit ang computed values
        var statusColor = scoreResult.remarks === "MET" ? "success" : "danger";
        var pointsColor = scoreResult.points > 0 ? "primary" : "secondary";
        
        $('#edit_edu_degree_display').text(degree);
        $('#edit_edu_level_display').text(selectedText);
        $('#edit_edu_status_display').html(`
            <span class="fw-bold text-${statusColor}">${scoreResult.remarks}</span>
            <small class="text-muted d-block">${scoreResult.details}</small>
        `);
        $('#edit_edu_points_display').html(`
            <span class="fw-bold text-${pointsColor}">${scoreResult.points} pts</span>
        `);
        
        // I-store para sa pag-save
        $('#edit_education_computed_remarks').val(scoreResult.remarks);
        $('#edit_education_computed_points').val(scoreResult.points);
    }
});

// =====================================
// LIVE UPDATE KAPAG NAG-TYPE SA DEGREE NAME
// =====================================
$(document).on('input', '#edit_education_name', function() {
    let degree = $(this).val();
    let selectedText = $('#edit_education_units_select option:selected').text();
    let position = getCurrentPosition();
    
    if (selectedText && selectedText !== "Select Education Level" && degree) {
        var scoreResult = computeEditEducationScore(degree, selectedText, position);
        
        var statusColor = scoreResult.remarks === "MET" ? "success" : "danger";
        var pointsColor = scoreResult.points > 0 ? "primary" : "secondary";
        
        $('#edit_edu_degree_display').text(degree);
        $('#edit_edu_level_display').text(selectedText);
        $('#edit_edu_status_display').html(`
            <span class="fw-bold text-${statusColor}">${scoreResult.remarks}</span>
            <small class="text-muted d-block">${scoreResult.details}</small>
        `);
        $('#edit_edu_points_display').html(`
            <span class="fw-bold text-${pointsColor}">${scoreResult.points} pts</span>
        `);
        
        $('#edit_education_computed_remarks').val(scoreResult.remarks);
        $('#edit_education_computed_points').val(scoreResult.points);
    }
});

// =====================================
// SIGURADUHIN NA TAMA ANG DISPLAY PAG MODAL SHOWN
// =====================================
$(document).on('shown.bs.modal', '#editEducationModal', function() {
    let degree = $('#edit_education_name').val();
    let selectedText = $('#edit_education_units_select option:selected').text();
    let position = getCurrentPosition();
    
    if (degree && selectedText && selectedText !== "Select Education Level") {
        var scoreResult = computeEditEducationScore(degree, selectedText, position);
        
        var statusColor = scoreResult.remarks === "MET" ? "success" : "danger";
        var pointsColor = scoreResult.points > 0 ? "primary" : "secondary";
        
        $('#edit_edu_degree_display').text(degree);
        $('#edit_edu_level_display').text(selectedText);
        $('#edit_edu_status_display').html(`
            <span class="fw-bold text-${statusColor}">${scoreResult.remarks}</span>
            <small class="text-muted d-block">${scoreResult.details}</small>
        `);
        $('#edit_edu_points_display').html(`
            <span class="fw-bold text-${pointsColor}">${scoreResult.points} pts</span>
        `);
        
        $('#edit_education_computed_remarks').val(scoreResult.remarks);
        $('#edit_education_computed_points').val(scoreResult.points);
    }
});
