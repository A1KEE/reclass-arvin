<!-- Add these hidden fields inside your edit modal -->
<input type="hidden" id="edit_education_computed_remarks">
<input type="hidden" id="edit_education_computed_points">
<!-- EDIT EDUCATION MODAL -->
<div class="modal fade" id="editEducationModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
      <!-- Modal Header -->
      <div class="modal-header bg-warning text-dark">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-edit me-2"></i>Edit Education
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">

          <!-- LEFT SIDE - RUBRICS + SUMMARY -->
          <div class="col-md-4 d-flex border-end pe-3">
            <div class="education-rubrics-sticky w-100 d-flex flex-column gap-2">
              <!-- RUBRICS CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-header">
                  <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-list-check me-2"></i>Education Rubrics
                  </h6>
                </div>
                <div class="card-body p-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item small">
                      <strong>Bachelor's Degree</strong><br>
                      <span class="text-muted">Required for Teacher I–V</span>
                    </li>
                    <li class="list-group-item small">
                      <strong>Master's Degree Units</strong><br>
                      <span class="text-muted">Required for Teacher VI–VII</span>
                    </li>
                    <li class="list-group-item small">
                      <strong>Master's Degree</strong><br>
                      <span class="text-muted">Required for Master Teacher</span>
                    </li>
                    <li class="list-group-item small text-muted">
                      Points are based on excess units earned
                    </li>
                  </ul>
                </div>
              </div>

              <!-- SUMMARY CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-body p-2">
                  <strong class="d-block mb-1">Education Summary</strong>
                  <div class="small">
                    Degree: <span id="edit_edu_degree_display">—</span><br>
                    Level: <span id="edit_edu_level_display">—</span><br>
                    Status: <span id="edit_edu_status_display" class="text-muted">Waiting..</span><br>
                    Score: <span id="edit_edu_points_display" class="fw-bold text-muted">0 pts</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - EDIT FORM -->
          <div class="col-md-8">
            <input type="hidden" id="edit_education_id">
            
            <!-- EDUCATION INFO CARD -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold text-warning">
                  <i class="fas fa-user-graduate me-2"></i>Edit Education Information
                </h6>
              </div>
              <div class="card-body p-3">
                <div class="row g-3 mb-2">
                  <!-- DEGREE -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-graduation-cap me-1 text-primary"></i>Degree/Title
                    </label>
                    <input type="text"
                           id="edit_education_name"
                           class="form-control"
                           placeholder="e.g. Bachelor of Secondary Education"
                           required>
                  </div>
                  <!-- SCHOOL -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-school me-1 text-primary"></i>School Graduated
                    </label>
                    <input type="text"
                           id="edit_education_school"
                           class="form-control"
                           placeholder="e.g. University of Manila"
                           required>
                  </div>
                </div>

                <div class="row g-3 align-items-end">
                  <!-- DATE -->
                  <div class="col-md-3">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-calendar-alt me-1 text-primary"></i>Date Graduated
                    </label>
                    <input type="date"
                           id="edit_education_date"
                           class="form-control"
                           required>
                  </div>
                  <!-- EDUC LEVEL -->
                  <div class="col-md-5">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-layer-group me-1 text-primary"></i>Highest Educational Attainment
                    </label>
                    <select id="edit_education_units_select"
                            class="form-select">
                      <option value="">Select Education Level</option>
                    </select>
                  </div>
                  <!-- CTP (Hidden by default, only shows for non-education degrees) -->
                  <div class="col-md-4 d-none" id="edit_ctp_container">
                    <label class="form-label fw-semibold text-warning">
                      <i class="fas fa-exclamation-triangle me-1"></i>Professional Education (CTP)
                    </label>
                    <select id="edit_ctp_units_select"
                            class="form-select border-warning">
                      <option value="">Select CTP Units</option>
                    </select>
                    <small class="text-muted">Required: 18 units for Non-Education degrees</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- VIEW CERTIFICATE (READ ONLY) -->
<div class="card shadow-sm border-0 mt-3">
  <div class="card-header bg-light py-2">
    <h6 class="mb-0 fw-semibold text-primary">
      <i class="fas fa-eye me-2"></i>View Certificate
    </h6>
  </div>

  <div class="card-body p-3 text-center">

    <!-- PDF VIEW LINK -->
    <a id="view_certificate_link"
       href="#"
       target="_blank"
       class="btn btn-outline-primary btn-sm d-none">
       <i class="fas fa-file-pdf me-1"></i>Open Certificate
    </a>

    <!-- PREVIEW BOX -->
    <div id="view_certificate_preview"
         class="mt-2 border rounded p-2 bg-light d-none">

      <iframe id="certificate_iframe"
              src=""
              style="width:100%; height:300px; border:none;">
      </iframe>

    </div>

    <!-- NO FILE -->
    <div id="no_certificate_text" class="text-muted">
      No certificate uploaded
    </div>

  </div>
</div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" id="deleteEducationBtn">
          <i class="fas fa-trash me-1"></i>Delete
        </button> -->
        <button type="button" class="btn btn-warning" id="updateEducationBtn">
          <i class="fas fa-save me-1"></i>Update Education
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>