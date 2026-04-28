<!-- EDIT EXPERIENCE MODAL -->
<div class="modal fade" id="editExperienceModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
      <!-- Modal Header -->
      <div class="modal-header bg-warning text-dark">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-edit me-2"></i>Edit Experience
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">

          <!-- LEFT SIDE - RUBRICS + SUMMARY -->
          <div class="col-md-4 d-flex border-end pe-3">
            <div class="experience-rubrics-sticky w-100 d-flex flex-column gap-2">
              
              <!-- RUBRICS CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-header">
                  <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-list-check me-2"></i>Experience Rubrics
                  </h6>
                </div>
                <div class="card-body p-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item small">
                      <strong>Teaching Experience</strong><br>
                      <span class="text-muted">Counts towards total years</span>
                    </li>
                    <li class="list-group-item small">
                      <strong>Administrative Experience</strong><br>
                      <span class="text-muted">May or may not count based on position</span>
                    </li>
                    <li class="list-group-item small text-muted">
                      Points are based on total accumulated years (2 points per year increment)
                    </li>
                  </ul>
                </div>
              </div>

              <!-- SUMMARY CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-body p-2">
                  <strong class="d-block mb-1">Experience Summary</strong>
                  <div class="small">
                    Position: <span id="edit_experience_position_display">—</span><br>
                    Years: <span id="edit_experience_years_display">0</span> yrs<br>
                    Status: <span id="edit_experience_status_display" class="text-muted">Waiting..</span><br>
                    Score: <span id="edit_experience_points_display" class="fw-bold text-muted">0 pts</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - EDIT FORM -->
          <div class="col-md-8">
            <input type="hidden" id="edit_experience_id">
            <input type="hidden" id="edit_experience_current_remarks">
            
            <!-- EXPERIENCE INFO CARD -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold text-warning">
                  <i class="fas fa-briefcase me-2"></i>Edit Experience Information
                </h6>
              </div>
              <div class="card-body p-3">
                <div class="row g-3 mb-2">
                  <!-- POSITION -->
                  <div class="col-md-12">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-user-tie me-1 text-primary"></i>Position
                    </label>
                    <input type="text"
                           id="edit_experience_position"
                           class="form-control"
                           placeholder="Enter position title"
                           required>
                  </div>
                </div>

                <div class="row g-3">
                  <!-- SCHOOL NAME -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-school me-1 text-primary"></i>School Name
                    </label>
                    <input type="text"
                           id="edit_experience_school"
                           class="form-control"
                           placeholder="Enter school name"
                           required>
                  </div>
                  
                  <!-- SCHOOL TYPE -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-building me-1 text-primary"></i>School Type
                    </label>
                    <select id="edit_experience_school_type" class="form-select" required>
                      <option value="">Select school type</option>
                      <option value="Public">Public School</option>
                      <option value="Private">Private School</option>
                    </select>
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <!-- START DATE -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-calendar-alt me-1 text-primary"></i>Start Date
                    </label>
                    <input type="date"
                           id="edit_experience_start_date"
                           class="form-control"
                           required>
                  </div>
                  
                  <!-- END DATE -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-calendar-check me-1 text-primary"></i>End Date
                    </label>
                    <input type="date"
                           id="edit_experience_end_date"
                           class="form-control">
                    <small class="text-muted">Leave blank if Present</small>
                  </div>
                </div>

                <!-- YEARS (auto-compute display) -->
                <div class="row g-3 mt-2">
                  <div class="col-md-12">
                    <div class="alert alert-info bg-primary bg-opacity-10 border-primary border-opacity-25">
                      <div class="row align-items-center">
                        <div class="col-md-8">
                          <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-clock me-2 text-primary"></i>Total Years
                          </label>
                          <div class="form-text">Automatically computed from start and end dates</div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group">
                            <span class="input-group-text bg-white border-primary">
                              <i class="fas fa-hourglass-half text-primary"></i>
                            </span>
                            <input type="text" 
                                   id="edit_experience_years_computed" 
                                   class="form-control border-primary fw-bold text-primary bg-light" 
                                   readonly 
                                   placeholder="0 years">
                          </div>
                        </div>
                      </div>
                    </div>
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
                <!-- PDF VIEW LINK -->
                <a id="view_experience_certificate_link"
                   href="#"
                   target="_blank"
                   class="btn btn-outline-primary btn-sm d-none">
                   <i class="fas fa-file-pdf me-1"></i>Open Certificate
                </a>

                <!-- PREVIEW BOX -->
                <div id="view_experience_certificate_preview"
                     class="mt-2 border rounded p-2 bg-light d-none">
                  <iframe id="experience_certificate_iframe"
                          src=""
                          style="width:100%; height:300px; border:none;">
                  </iframe>
                </div>

                <!-- NO FILE -->
                <div id="no_experience_certificate_text" class="text-muted">
                  No certificate uploaded
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="updateExperienceBtn">
          <i class="fas fa-save me-1"></i>Update Experience
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>