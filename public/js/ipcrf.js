$(function () {

let uploadedIPCRFs = [];
let removedIndexes = [];

const ipcrfContainer = document.getElementById('ipcrfContainer');
const ipcrfInstruction = document.getElementById('ipcrfInstruction');
const ipcrfModalEl = document.getElementById('ipcrfModal');
const statusEl = document.getElementById('ipcrfStatus');
const perfDiv = document.getElementById('performanceRequirements');

// =====================
// PALAGING 3 BOXES
// =====================

function getRequiredIPCRFs() {
    return 3;
}

function formatSize(bytes){
    return (bytes / (1024*1024)).toFixed(2) + ' MB';
}

// =====================
// 🔥 IPCRF SYNC (ADDED FIX)
// =====================
function syncIPCRFToForm(){
    const container = document.getElementById('ipcrfContainerInputs');
    container.innerHTML = '';

    uploadedIPCRFs.forEach((file, index) => {
        if(file && !removedIndexes.includes(index)){

            const dt = new DataTransfer();
            dt.items.add(file);

            const input = document.createElement('input');
            input.type = 'file';
            input.name = `ipcrf_files[${index}][file]`;
            input.files = dt.files;

            const title = document.createElement('input');
            title.type = 'hidden';
            title.name = `ipcrf_files[${index}][title]`;
            title.value = `IPCRF ${index+1}`;

            container.appendChild(input);
            container.appendChild(title);
        }
    });
}

// =====================
// STATUS
// =====================

function updateIPCRFStatus(){
    const count = uploadedIPCRFs.filter(f => f !== null && !removedIndexes.includes(uploadedIPCRFs.indexOf(f))).length;
    
    if(!statusEl) return;
    
    statusEl.classList.remove('d-none');
    
    if(count === 0){
        statusEl.innerHTML = `<i class="bi bi-exclamation-circle-fill text-danger me-1"></i>
        <span class="text-danger">No IPCRF uploaded (at least 1 required)</span>`;
    } else {
        statusEl.innerHTML = `<i class="bi bi-check-circle-fill text-success me-1"></i>
        <span class="text-success">${count} IPCRF file(s) uploaded</span>`;
    }
}

// =====================
// RENDER BOXES
// =====================

function renderIPCRFBoxes(){
    const required = getRequiredIPCRFs();
    const currentYear = new Date().getFullYear();
    const years = [currentYear-2, currentYear-1, currentYear];

    ipcrfInstruction.textContent = `Upload 1-3 IPCRF files (PDF only, max 5MB each). At least one is required.`;

    ipcrfContainer.innerHTML = '';

    if(uploadedIPCRFs.length === 0){
        uploadedIPCRFs = Array(required).fill(null);
    }

    if(removedIndexes.length === 0){
        removedIndexes = [];
    }

    for(let i = 0; i < required; i++){
        const col = document.createElement('div');
        col.className = 'col-md-4';

        col.innerHTML = `
        <div class="ipcrf-upload-card">
            <div class="ipcrf-upload-body">
                <i class="bi bi-cloud-arrow-up ipcrf-main-icon"></i>
                <div class="ipcrf-title">IPCRF ${years[i]}</div>
                <input type="file" class="d-none" id="ipcrf_file${i}" accept=".pdf">
                <div class="ipcrf-preview mt-2 small text-muted" id="preview${i}">
                    Click or drag PDF here
                </div>
                <div class="ipcrf-actions mt-2 d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-sm btn-outline-primary d-none" id="view${i}">View</button>
                    <button type="button" class="btn btn-sm btn-outline-danger d-none" id="remove${i}">Remove</button>
                </div>
            </div>
        </div>`;

        ipcrfContainer.appendChild(col);

        const card = col.querySelector('.ipcrf-upload-card');
        const input = col.querySelector('input');
        const preview = col.querySelector(`#preview${i}`);
        const viewBtn = col.querySelector(`#view${i}`);
        const removeBtn = col.querySelector(`#remove${i}`);
        const icon = col.querySelector('.ipcrf-main-icon');

        function handleFileUpload(file, index){
            if(!file) return;

            if(file.type !== 'application/pdf'){
                Swal.fire({icon:'error', title:'Invalid File', text:'PDF files only!'});
                return;
            }

            if(file.size > 5 * 1024 * 1024){
                Swal.fire({icon:'warning', title:'File too large', text:'Maximum 5MB only!'});
                return;
            }

            uploadedIPCRFs[index] = file;

            const removedPos = removedIndexes.indexOf(index);
            if(removedPos > -1) removedIndexes.splice(removedPos, 1);

            preview.innerHTML = `
                <span title="${file.name}">
                    ${file.name.length > 20 ? file.name.substring(0, 18) + '...' : file.name}
                </span><br>
                <small>${formatSize(file.size)}</small>
            `;

            preview.classList.remove('text-muted');
            viewBtn.classList.remove('d-none');
            removeBtn.classList.remove('d-none');
            card.classList.add('uploaded');
            icon.classList.replace('bi-cloud-arrow-up', 'bi-check-circle-fill');

            updateIPCRFStatus();

            syncIPCRFToForm(); // 🔥 ADDED FIX
        }

        card.addEventListener('click', () => input.click());

        input.addEventListener('change', e => {
            if(e.target.files[0]) handleFileUpload(e.target.files[0], i);
        });

        viewBtn.onclick = (e) => {
            e.stopPropagation();
            const file = uploadedIPCRFs[i];
            if(file){
                window.open(URL.createObjectURL(file));
            }
        };

        removeBtn.onclick = (e) => {
            e.stopPropagation();

            uploadedIPCRFs[i] = null;
            removedIndexes.push(i);

            preview.innerHTML = 'Click or drag PDF here';
            preview.classList.add('text-muted');

            viewBtn.classList.add('d-none');
            removeBtn.classList.add('d-none');
            card.classList.remove('uploaded');

            updateIPCRFStatus();

            syncIPCRFToForm(); // 🔥 ADDED FIX
        };
    }

    updateIPCRFStatus();
}

// =====================
// VALIDATE
// =====================

function validateIPCRF(){
    const validFiles = uploadedIPCRFs.filter((file, index) => 
        file !== null && !removedIndexes.includes(index)
    ).length;

    if(validFiles === 0){
        Swal.fire({
            icon: 'warning',
            title: 'IPCRF Required!',
            text: 'Please upload at least one (1) IPCRF file.'
        });
        return false;
    }

    return true;
}

// =====================
// MODAL
// =====================

let ipcrfInitialized = false;

if(ipcrfModalEl){
    ipcrfModalEl.addEventListener('show.bs.modal', () => {
        renderIPCRFBoxes();
        ipcrfInitialized = true;
    });
}

// =====================
// SUBMIT
// =====================

$('#applicantForm').on('submit', function(e){
    e.preventDefault();

    if (!validateIPCRF()) return;

    syncIPCRFToForm(); // 🔥 IMPORTANT

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,

        success: function(){
            Swal.fire('Success', 'Submitted!', 'success');
        },

        error: function(xhr){
            Swal.fire('Error', 'Server error', 'error');
        }
    });
});

});