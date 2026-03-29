// Admin Education: Populate modal when Edit button clicked
$(document).on('click', '.edit-education-btn', function() {
    let id = $(this).data('id');
    let degree = $(this).data('degree');
    let school = $(this).data('school');
    let date = $(this).data('date');
    let units = $(this).data('units');

    $('#edit_education_id').val(id);
    $('#education_name').val(degree);
    $('#education_school').val(school);
    $('#education_date').val(date);
    $('#education_units_select').val(units);
    $('#education_file').val(''); // clear file input

    // I-trigger ang pag-update ng dropdown at summary (para mag-refresh ang units)
    $('#education_units_select').trigger('change');
    $('#education_name').trigger('input');

    $('#educationModal').modal('show');
});

// Kapag nag-save, magdagdag ng hidden input para sa ID (para malaman ng server kung update)
$(document).on('click', '#saveEducation', function(e) {
    // Hintayin munang tumakbo ang original saveEducation (mula sa education-points.js)
    setTimeout(function() {
        let eduId = $('#edit_education_id').val();
        if (eduId) {
            // Hanapin ang huling idinaragdag na hidden input group at lagyan ng ID
            // Ang original saveEducation ay gumagawa ng hidden inputs na may name na "educations[XXX][degree]" etc.
            // Kailangan nating i-inject ang ID sa loob ng parehong group.
            let lastIndex = $('input[name^="educations["]').last().attr('name');
            if (lastIndex) {
                let match = lastIndex.match(/educations\[(\d+)\]/);
                if (match) {
                    let idx = match[1];
                    // Magdagdag ng hidden input para sa ID
                    let idInput = `<input type="hidden" name="educations[${idx}][id]" value="${eduId}">`;
                    $('#educationHiddenInputs').append(idInput);
                }
            }
        }
        // I-reset ang edit ID para sa susunod
        $('#edit_education_id').val('');
    }, 100);
});