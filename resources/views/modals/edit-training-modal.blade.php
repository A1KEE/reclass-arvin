<!-- EDIT TRAINING MODAL -->
<div class="modal fade" id="editTrainingModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
      <!-- Modal Header -->
      <div class="modal-header bg-warning text-dark">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-edit me-2"></i>Edit Training
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">

          <!-- LEFT SIDE - RUBRICS + SUMMARY -->
          <div class="col-md-4 d-flex border-end pe-3">
            <div class="training-rubrics-sticky w-100 d-flex flex-column gap-2">
              
              <!-- RUBRICS CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-header">
                  <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-list-check me-2"></i>Training Rubrics
                  </h6>
                </div>
                <div class="card-body p-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item small">
                      <strong>Face-to-Face Training</strong><br>
                      <span class="text-muted">8 hours per day</span>
                    </li>
                    <li class="list-group-item small">
                      <strong>Online / Virtual Training</strong><br>
                      <span class="text-muted">3 hours per day</span>
                    </li>
                    <li class="list-group-item small text-muted">
                      Points are based on total accumulated hours
                    </li>
                  </ul>
                </div>
              </div>

              <!-- SUMMARY CARD -->
              <div class="card border-0 shadow-sm border-light-green">
                <div class="card-body p-2">
                  <strong class="d-block mb-1">Training Summary</strong>
                  <div class="small">
                    Title: <span id="edit_training_title_display">—</span><br>
                    Hours: <span id="edit_training_hours_display">0</span> hrs<br>
                    Status: <span id="edit_training_status_display" class="text-muted">Waiting..</span><br>
                    Score: <span id="edit_training_points_display" class="fw-bold text-muted">0 pts</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - EDIT FORM -->
          <div class="col-md-8">
            <input type="hidden" id="edit_training_id">
            <input type="hidden" id="edit_training_current_remarks">
            
            <!-- TRAINING INFO CARD -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold text-warning">
                  <i class="fas fa-chalkboard-teacher me-2"></i>Edit Training Information
                </h6>
              </div>
              <div class="card-body p-3">
                <div class="row g-3 mb-2">
                  <!-- TITLE -->
                  <div class="col-md-12">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-graduation-cap me-1 text-primary"></i>Training Title
                    </label>
                    <input type="text"
                           id="edit_training_title"
                           class="form-control"
                           placeholder="Enter training title"
                           required>
                  </div>
                </div>

                <div class="row g-3">
                  <!-- TYPE -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-chalkboard-user me-1 text-primary"></i>Training Type
                    </label>
                    <select id="edit_training_type" class="form-select" required>
                      <option value="">Select training type</option>
                      <option value="Face-to-Face">Face-to-Face</option>
                      <option value="Online">Online</option>
                    </select>
                  </div>
                  
                  <!-- HOURS (auto-compute) -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-clock me-1 text-primary"></i>No. of Hours
                    </label>
                    <input type="number"
                           id="edit_training_hours"
                           class="form-control bg-light"
                           readonly>
                    <small class="text-muted">Automatically computed from dates</small>
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <!-- START DATE -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-calendar-alt me-1 text-primary"></i>Start Date
                    </label>
                    <input type="date"
                           id="edit_training_start_date"
                           class="form-control"
                           required>
                  </div>
                  
                  <!-- END DATE -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      <i class="fas fa-calendar-check me-1 text-primary"></i>End Date
                    </label>
                    <input type="date"
                           id="edit_training_end_date"
                           class="form-control"
                           required>
                  </div>
                </div>
              </div>
            </div>

            <!-- VIEW CERTIFICATE (SA LOOB NG MODAL, GAYA NG EDUCATION) -->
            <div class="card shadow-sm border-0 mt-3">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-semibold text-primary">
                  <i class="fas fa-eye me-2"></i>View Certificate
                </h6>
              </div>

              <div class="card-body p-3 text-center">
                <!-- PDF VIEW LINK -->
                <a id="view_training_certificate_link"
                   href="#"
                   target="_blank"
                   class="btn btn-outline-primary btn-sm d-none">
                   <i class="fas fa-file-pdf me-1"></i>Open Certificate
                </a>

                <!-- PREVIEW BOX -->
                <div id="view_training_certificate_preview"
                     class="mt-2 border rounded p-2 bg-light d-none">
                  <iframe id="training_certificate_iframe"
                          src=""
                          style="width:100%; height:300px; border:none;">
                  </iframe>
                </div>

                <!-- NO FILE -->
                <div id="no_training_certificate_text" class="text-muted">
                  No certificate uploaded
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="updateTrainingBtn">
          <i class="fas fa-save me-1"></i>Update Training
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>