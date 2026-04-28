<!-- EDIT ELIGIBILITY MODAL -->
<div class="modal fade" id="editEligibilityModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
      <!-- Modal Header -->
      <div class="modal-header bg-warning text-dark">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-edit me-2"></i>Edit Eligibility
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">

          <!-- LEFT SIDE - RUBRICS + SUMMARY -->
          <div class="col-md-4 d-flex border-end pe-3">
            <div class="eligibility-rubrics-sticky w-100 d-flex flex-column gap-2">
              
              <!-- RUBRICS CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-header">
                  <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-list-check me-2"></i>Eligibility Rubrics
                  </h6>
                </div>
                <div class="card-body p-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item small">
                      <strong>LET</strong><br>
                      <span class="text-muted">Licensure Examination for Teachers</span>
                    </li>
                    <li class="list-group-item small">
                      <strong>PBET</strong><br>
                      <span class="text-muted">Professional Board Examination for Teachers</span>
                    </li>
                    <li class="list-group-item small">
                      <strong>MAGNA CARTA</strong><br>
                      <span class="text-muted">Magna Carta for Public School Teachers</span>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- SUMMARY CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-body p-2">
                  <strong class="d-block mb-1">Eligibility Summary</strong>
                  <div class="small">
                    Name: <span id="edit_eligibility_name_display">—</span><br>
                    Expiry: <span id="edit_eligibility_expiry_display">—</span><br>
                    Status: <span id="edit_eligibility_status_display" class="text-muted">Waiting..</span><br>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - EDIT FORM -->
          <div class="col-md-8">
            <input type="hidden" id="edit_eligibility_id">
            <input type="hidden" id="edit_eligibility_current_remarks">
            
            <!-- ELIGIBILITY INFO CARD -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold text-warning">
                  <i class="fas fa-id-card me-2"></i>Edit Eligibility Information
                </h6>
              </div>
              <div class="card-body p-3">
                <div class="row g-3">
                  <!-- ELIGIBILITY NAME (DROPDOWN) -->
                  <div class="col-md-12">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-certificate me-1 text-primary"></i>Eligibility Name
                    </label>
                    <select id="edit_eligibility_name" class="form-select" required>
                      <option value="">Select Eligibility</option>
                      <option value="LET">LET</option>
                      <option value="PBET">PBET</option>
                      <option value="MAGNA CARTA">MAGNA CARTA</option>
                    </select>
                  </div>
                </div>

                <div class="row g-3 mt-2">
                  <!-- EXPIRY DATE -->
                  <div class="col-md-12">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-calendar-alt me-1 text-primary"></i>Expiry Date
                    </label>
                    <input type="date"
                           id="edit_eligibility_expiry"
                           class="form-control">
                    <small class="text-muted">Leave empty if no expiry</small>
                  </div>
                </div>

                <!-- STATUS DROPDOWN (MANUAL SELECTION) -->
                <div class="row g-3 mt-3">
                  <div class="col-md-12">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-flag-checkered me-1 text-primary"></i>Evaluation Status
                    </label>
                    <select id="edit_eligibility_status" class="form-select" required>
                      <option value="MET">✅ MET - Eligible</option>
                      <option value="NOT MET">❌ NOT MET - Not Eligible</option>
                    </select>
                    <small class="text-muted">Based on your evaluation of the uploaded reciept</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- VIEW CERTIFICATE -->
            <div class="card shadow-sm border-0 mt-3">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold text-primary">
                  <i class="fas fa-eye me-2"></i>View Certificate
                </h6>
              </div>

              <div class="card-body p-3 text-center">
                <a id="view_eligibility_certificate_link"
                   href="#"
                   target="_blank"
                   class="btn btn-outline-primary btn-sm d-none">
                   <i class="fas fa-external-link-alt me-1"></i>Open Certificate
                </a>

                <div id="view_eligibility_certificate_preview"
                     class="mt-2 border rounded p-2 bg-light d-none">
                  <iframe id="eligibility_certificate_iframe"
                          src=""
                          style="width:100%; height:300px; border:none;">
                  </iframe>
                </div>

                <div id="no_eligibility_certificate_text" class="text-muted">
                  No certificate uploaded
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="updateEligibilityBtn">
          <i class="fas fa-save me-1"></i>Update Eligibility
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>