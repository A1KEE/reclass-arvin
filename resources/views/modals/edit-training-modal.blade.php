<!-- EDIT TRAINING MODAL (MULTI-ENTRY VERSION) -->
<div class="modal fade" id="editTrainingModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header bg-warning text-dark">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-edit me-2"></i>Edit Trainings
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-4">
        <div class="row g-4">
          
          <!-- LEFT SIDE - RUBRICS + SUMMARY -->
          <div class="col-md-4 d-flex">
            <div class="training-rubrics-sticky w-100 d-flex flex-column gap-2">
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
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

              <div class="card border-0 shadow-sm">
                <div class="card-body p-2">
                  <strong class="d-block mb-1">Training Summary</strong>
                  <div class="small">
                    Total Hours: <span id="edit_total_hours_display">0</span> hrs<br>
                    Required Hours: <span id="edit_required_hours_display">0</span><br>
                    Status: <span id="edit_training_status_display" class="text-muted">Waiting..</span><br>
                    Score: <span id="edit_training_points_display" class="fw-bold text-muted">0 pts</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - EDIT FORMS CONTAINER -->
          <div class="col-md-8">
            <div id="editTrainingsContainer"></div>
          </div>
          
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="updateAllTrainingsBtn">
          <i class="fas fa-save me-1"></i>Save All Changes
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>