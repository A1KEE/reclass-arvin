// edit-eligibility.js

let currentEligibilityFile = '';
let currentEligibilityRemarks = '';

function updateEligibilitySummary() {
    const name = $('#edit_eligibility_name').val() || '—';
    const expiry = $('#edit_eligibility_expiry').val() || 'No expiry';
    const status = $('#edit_eligibility_status').val() || 'Waiting';
    
    $('#edit_eligibility_name_display').text(name);
    $('#edit_eligibility_expiry_display').text(expiry);
    
    let statusClass = 'text-muted';
    if (status === 'MET') {
        statusClass = 'text-success fw-bold';
    } else if (status === 'NOT MET') {
        statusClass = 'text-danger fw-bold';
    }
    
    $('#edit_eligibility_status_display').html(`<span class="${statusClass}">${status}</span>`);
}

// ==========================
// EDIT ELIGIBILITY BUTTON HANDLER
// ==========================
$(document).on('click', '.edit-eligibility-btn', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const expiry = $(this).data('expiry');
    const filePath = $(this).data('file');
    const remarks = $(this).data('remarks');
    
    console.log('Edit button clicked - ID:', id, 'Name:', name, 'Remarks:', remarks);
    
    currentEligibilityFile = filePath;
    currentEligibilityRemarks = remarks || 'Waiting for QS';
    
    // Fill modal fields
    $('#edit_eligibility_id').val(id);
    $('#edit_eligibility_name').val(name || '');
    $('#edit_eligibility_expiry').val(expiry || '');
    $('#edit_eligibility_status').val(remarks === 'MET' ? 'MET' : 'NOT MET');
    $('#edit_eligibility_current_remarks').val(currentEligibilityRemarks);
    
    // Display certificate
    if (filePath) {
        let cleanPath = filePath.replace(/^\/+/, '');
        const fullUrl = '/storage/' + cleanPath;
        $('#view_eligibility_certificate_link')
            .attr('href', fullUrl)
            .removeClass('d-none');
        $('#eligibility_certificate_iframe').attr('src', fullUrl);
        $('#view_eligibility_certificate_preview').removeClass('d-none');
        $('#no_eligibility_certificate_text').addClass('d-none');
    } else {
        $('#view_eligibility_certificate_link').addClass('d-none');
        $('#view_eligibility_certificate_preview').addClass('d-none');
        $('#no_eligibility_certificate_text').removeClass('d-none');
    }
    
    updateEligibilitySummary();
    $('#editEligibilityModal').modal('show');
});

// Update summary when fields change
$(document).on('change', '#edit_eligibility_name', function() {
    updateEligibilitySummary();
});

$(document).on('change', '#edit_eligibility_expiry', function() {
    updateEligibilitySummary();
});

$(document).on('change', '#edit_eligibility_status', function() {
    updateEligibilitySummary();
});

// ==========================
// UPDATE ELIGIBILITY BUTTON
// ==========================
$('#updateEligibilityBtn').on('click', function() {
    const id = $('#edit_eligibility_id').val();
    const name = $('#edit_eligibility_name').val();
    const expiry = $('#edit_eligibility_expiry').val();
    const status = $('#edit_eligibility_status').val();
    
    console.log('Update button clicked - ID:', id, 'Name:', name, 'Status:', status);
    
    if (!name) {
        Swal.fire('Error', 'Please select an eligibility', 'warning');
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
        url: `/qs/eligibility/update/${id}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            eligibility_name: name,
            expiry_date: expiry,
            eligibility_remarks: status
        }),
        success: function(response) {
            console.log('Success response:', response);
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    html: `Eligibility updated successfully!<br>Status: ${response.remarks}`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            console.log('Error status:', xhr.status);
            console.log('Error response:', xhr.responseText);
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
});

console.log('edit-eligibility.js loaded');