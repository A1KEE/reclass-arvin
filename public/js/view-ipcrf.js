// view-ipcrf.js (may preview box gaya ng training)

function loadIpcrfFiles() {
    const container = $('#ipcrf_files_list');
    container.empty();
    
    console.log('IPCRF Data:', window.ipcrfData);
    
    if (window.ipcrfData && window.ipcrfData.length > 0) {
        window.ipcrfData.forEach(ipcrf => {
            // Clean file path (remove leading slashes)
            let filePath = ipcrf.file_path || '';
            if (filePath.startsWith('/')) {
                filePath = filePath.substring(1);
            }
            
            // Construct the full URL using the storage route
            const fileUrl = '/storage/' + filePath;
            const fileName = ipcrf.file_name || 'IPCRF Document';
            
            const ipcrfHtml = `
                <div class="list-group-item list-group-item-action ipcrf-item" 
                     data-url="${fileUrl}" 
                     data-name="${escapeHtml(fileName)}"
                     style="cursor: pointer;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf text-danger me-3 fa-lg"></i>
                        <div>
                            <strong>${escapeHtml(fileName)}</strong><br>
                            <small class="text-muted">${filePath}</small>
                        </div>
                    </div>
                </div>
            `;
            container.append(ipcrfHtml);
        });
        
        // Auto-select the first item
        $('.ipcrf-item').first().addClass('active bg-light');
        const firstUrl = $('.ipcrf-item').first().data('url');
        const firstName = $('.ipcrf-item').first().data('name');
        if (firstUrl) {
            $('#view_ipcrf_certificate_link')
                .attr('href', firstUrl)
                .removeClass('d-none');
            $('#ipcrf_certificate_iframe').attr('src', firstUrl);
            $('#view_ipcrf_certificate_preview').removeClass('d-none');
            $('#no_ipcrf_selected_text').addClass('d-none');
        }
        
        // Add click handler for each item
        $('.ipcrf-item').on('click', function() {
            $('.ipcrf-item').removeClass('active bg-light');
            $(this).addClass('active bg-light');
            
            const url = $(this).data('url');
            const name = $(this).data('name');
            
            $('#view_ipcrf_certificate_link')
                .attr('href', url)
                .removeClass('d-none');
            $('#ipcrf_certificate_iframe').attr('src', url);
            $('#view_ipcrf_certificate_preview').removeClass('d-none');
            $('#no_ipcrf_selected_text').addClass('d-none');
        });
        
    } else {
        container.html('<div class="list-group-item text-center text-muted py-4">No IPCRF files uploaded.</div>');
        $('#view_ipcrf_certificate_link').addClass('d-none');
        $('#view_ipcrf_certificate_preview').addClass('d-none');
        $('#no_ipcrf_selected_text').removeClass('d-none').text('No IPCRF files available');
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Open modal
$('#viewIpcrfBtn').on('click', function() {
    console.log('Opening IPCRF modal...');
    loadIpcrfFiles();
    $('#viewIpcrfModal').modal('show');
});

console.log('view-ipcrf.js loaded');