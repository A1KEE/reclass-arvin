    $(document).ready(function() {

        // ==========================
        // HELPERS
        // ==========================
        function getMappedPPSTPosition(value) {
            const map = {
                "Teacher I": "Teacher I – MT I",
                "Teacher II": "Teacher I – MT I",
                "Teacher III": "Teacher I – MT I",
                "Teacher IV": "Teacher I – MT I",
                "Teacher V": "Teacher I – MT I",
                "Teacher VI": "Teacher I – MT I",
                "Teacher VII": "Teacher I – MT I",
                "Master Teacher I": "Teacher I – MT I",
                "Master Teacher II": "Master Teacher II–III",
                "Master Teacher III": "Master Teacher II–III",
                "Master Teacher IV": "Master Teacher II–III",
                "Master Teacher V": "Master Teacher II–III"
            };
            return map[value] || value;
        }

        function loadPPSTMapped(value, callback) {
            loadPPST(getMappedPPSTPosition(value), callback);
        }

        // ==========================
        // PPST AJAX LOADER
        // ==========================
        function loadPPST(position, callback) {
            console.log("AJAX PPST POSITION:", position);

            $.ajax({
                url: "/load-ppst",
                type: "GET",
                data: { position: position },
                success: function (html) {
                    $("#ppst-table-container").html(html);
                    resetPPST();
                    if (typeof callback === 'function') callback();
                },
                error: function() {
                    $("#ppst-table-container").html(
                        '<div class="text-danger text-center p-4">Failed to load PPST</div>'
                    );
                }
            });
        }

        // ==========================
        // RESET FUNCTIONS
        // ==========================
        function resetPPST() {
            document.querySelectorAll('.ppst-checkbox, .ppst-checkbox-s').forEach(box => {
                box.checked = false;
                box.disabled = false;
            });
            document.getElementById("totalCOI_O").value = 0;
            document.getElementById("totalCOI_VS").value = 0;
            document.getElementById("totalNCOI_O").value = 0;
            document.getElementById("totalNCOI_VS").value = 0;

            const final = document.getElementById("finalRating");
            if(final) final.textContent = "-";
        }

        function resetEducationFields() {
            $('#education_name').val('');
            $('#education_units_select').val('');
            $('#education_units_other').val('');
            $('#education_units_other_container').addClass('d-none');
            $('#education_file').val('');
            $('#education_file_name').text('No file chosen').removeClass('text-success').addClass('text-muted');
            $('#education_summary').html('<span class="text-muted">No education added.</span>');
            $('#education_remark').html('<span class="text-muted">Waiting for The QS</span>');
            $('input[name="comparative[education]"]').val('');
            if(typeof showQSUnits === 'function') showQSUnits();
        }

        function resetTraining() {
            $('#trainingContainer').empty();
            trainingIndex = 1;
            $('#training_summary').html('<span class="text-muted">No trainings added.</span>');
            $('#modal_training_summary').html('<div class="alert alert-warning p-2">No trainings added</div>');
            $('#total_training_hours').text('0');
            $('input[name="comparative[training]"]').val('0');
            $('#training_remark').html('<span class="text-muted">Waiting for The QS</span>');
        }

        function resetExperience() {
            $('#experience_summary').html('<span class="text-muted">No experience added.</span>');
            $('#experience_remark').html('<span class="text-muted">Waiting for The QS</span>');
            $('input[name="comparative[experience]"]').val('0');
            $('#experienceContainer').empty();
        }

        function resetEligibility() {
            $('#eligibility_summary').html('<span class="text-muted">No eligibility added.</span>');
            $('#eligibility_remark').html('<span class="text-muted">Waiting for The QS</span>');
            $('input[name="comparative[eligibility]"]').val('0');
            $('#eligibilityContainer').empty();
        }

        function resetIPCRF() {
            $('#ipcrf_summary').html('<span class="text-muted">No IPCRF added.</span>');
            $('#ipcrf_remark').html('<span class="text-muted">Waiting for The QS</span>');
            $('input[name="comparative[ipcrf]"]').val('0');
            $('#ipcrfContainer').empty();
        }

        function resetAllQualifications() {
            if(typeof resetEducationFields === 'function') resetEducationFields();
            else { $('#education_name, #education_units_select, #education_units_other, #education_file').val(''); }

            if(typeof resetTraining === 'function') resetTraining();
            if(typeof resetExperience === 'function') resetExperience();
            if(typeof resetEligibility === 'function') resetEligibility();
            if(typeof resetIPCRF === 'function') resetIPCRF();
            if(typeof resetPPST === 'function') resetPPST();

            resetFormSections();
            
            Swal.fire({
                icon: 'info',
                title: 'All qualifications reset',
                text: 'Please re-enter your qualifications for the new position.',
                toast: true,
                position: 'top-end',
                timer: 2500,
                showConfirmButton: false
            });
        }

        // ==========================
        // HIGHLIGHT PERFORMANCE ROW
        // ==========================
        function highlightPerformanceRow() {
            const selected = $('#position_applied').val()?.trim() || '';
            const $rows = $('#performanceTable tbody tr');
            $rows.removeClass('highlight-row');
            if(!selected) return;

            $rows.each(function() {
                const dp = $(this).data('position');
                if(dp?.toString().trim() === selected) $(this).addClass('highlight-row');
            });
        }
        function resetFormSections() {
        // Hide the main performance / qualification container
        $('#performanceRequirements').hide();

        // Optional: hide nested sections individually kung may iba pang behavior
        $('#education-section').hide();
        $('#training-section').hide();
        $('#experience-section').hide();
        $('#eligibility-section').hide();
        $('#ipcrf-section').hide();
        $('#ppst-section').hide();
        $('#summary-section').hide();
    }

        // ==========================
        // INITIAL LOAD
        // ==========================
        function initialLoad() {
            const position = $('#position_applied').val();
            const school = $('#school_id').val();

            if(position && school && typeof loadQS === 'function') loadQS(position);
            if(position) {
                loadPPSTMapped(position, () => {
                    updateHeaderForPosition();
                    highlightRow();

                    // Auto-check school level
                    if(school) {
                        const level = $('#school_id').find(':selected').data('level');
                        $('input[name="levels[]"]').prop('checked', false);
                        if(level) $(`input[name="levels[]"][value="${level}"]`).prop('checked', true);
                    }
                });
            }
        }

        initialLoad();

        // ==========================
        // POSITION CHANGE WITH CONFIRMATION
        // ==========================
        $('#position_applied').off('change').on('change', function() {
            const newValue = $(this).val();
            const prevValue = $(this).data('previous') || "";

            if(!prevValue || prevValue === newValue) {
                $(this).data('previous', newValue);
                initialLoad();
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Position Changed',
                text: 'Changing position will reset all qualifications. Proceed?',
                showCancelButton: true,
                confirmButtonText: 'Yes, reset',
                cancelButtonText: 'No, keep current'
            }).then(result => {
                if(result.isConfirmed) {
                    resetAllQualifications();
                    $(this).data('previous', newValue);
                    if(typeof loadQS === 'function') loadQS(newValue);
                    if(typeof loadPPSTMapped === 'function') {
                        loadPPSTMapped(newValue, () => {
                            updateHeaderForPosition();
                            highlightPerformanceRow();
                        });
                    }
                } else {
                    $(this).val(prevValue).data('previous', prevValue);
                    if(typeof loadQS === 'function') loadQS(prevValue);
                }
            });
        });

        // ==========================
        // SCHOOL CHANGE
        // ==========================
        $('#school_id').off('change').on('change', function() {
            const level = $(this).find(':selected').data('level');
            $('input[name="levels[]"]').prop('checked', false);
            if(level) $(`input[name="levels[]"][value="${level}"]`).prop('checked', true);

            updateHeaderForPosition();
            highlightPerformanceRow();

            const position = $('#position_applied').val();
            if(position && typeof loadQS === 'function') loadQS(position);
        });

        // ==========================
        // PREVENT MANUAL LEVEL CHECKBOX CLICK
        // ==========================
        const $levelCheckboxes = $('input[name="levels[]"]');
        const $schoolSelect = $('#school_id');

        $levelCheckboxes.prop('checked', false);

        $levelCheckboxes.on('click', function(e) {
            if(!$schoolSelect.val()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Select School First',
                    text: 'Please select a school/station to automatically determine the level.',
                    confirmButtonColor: '#3085d6'
                });
                return false;
            }
            e.preventDefault();
            return false;
        });
    });
