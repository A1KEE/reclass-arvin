<!-- VIEW IPCRF MODAL (kaparehas ng training - may preview box) -->
<div class="modal fade" id="viewIpcrfModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
      <!-- Modal Header -->
      <div class="modal-header bg-info text-white">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-file-alt me-2"></i>IPCRF Files
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">

          <!-- LEFT SIDE - LIST OF IPCRF FILES -->
          <div class="col-md-5">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-light">
                <h6 class="mb-0 fw-semibold">
                  <i class="fas fa-list me-2"></i>Uploaded IPCRF Documents
                </h6>
              </div>
              <div class="card-body p-0">
                <div id="ipcrf_files_list" class="list-group list-group-flush">
                  <div class="list-group-item text-center text-muted py-4">
                    Loading IPCRF files...
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - PREVIEW BOX -->
          <div class="col-md-7">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold text-primary">
                  <i class="fas fa-eye me-2"></i>Document Preview
                </h6>
              </div>

              <div class="card-body p-3 text-center">
                <!-- PDF VIEW LINK -->
                <a id="view_ipcrf_certificate_link"
                   href="#"
                   target="_blank"
                   class="btn btn-outline-primary btn-sm d-none">
                   <i class="fas fa-external-link-alt me-1"></i>Open IPCRF file
                </a>

                <!-- PREVIEW BOX -->
                <div id="view_ipcrf_certificate_preview"
                     class="mt-2 border rounded p-2 bg-light d-none">
                  <iframe id="ipcrf_certificate_iframe"
                          src=""
                          style="width:100%; height:400px; border:none;">
                  </iframe>
                </div>

                <!-- NO FILE SELECTED -->
                <div id="no_ipcrf_selected_text" class="text-muted">
                  Select a file from the left to preview
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>