<!-- EDIT EXPERIENCE MODAL (WITH CERTIFICATE PREVIEW) -->
<div class="modal fade" id="editExperienceModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
      <!-- Modal Header -->
      <div class="modal-header bg-warning text-dark">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-edit me-2"></i>Edit Experiences
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">
          
          <!-- LEFT SIDE - RUBRICS + SUMMARY -->
          <div class="col-md-4 d-flex">
            <div class="experience-rubrics-sticky w-100 d-flex flex-column gap-2">
              
              <!-- RUBRICS CARD -->
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
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
              <div class="card border-0 shadow-sm">
                <div class="card-body p-2">
                  <strong class="d-block mb-1">Experience Summary</strong>
                  <div class="small">
                    Total Years: <span id="edit_total_years_display">0</span><br>
                    Required Years: <span id="edit_required_years_display">0</span><br>
                    Status: <span id="edit_status_display" class="text-muted">Waiting..</span><br>
                    Score: <span id="edit_points_display" class="fw-bold text-muted">0 pts</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE - EDIT FORMS CONTAINER -->
          <div class="col-md-8">
            <div id="editExperiencesContainer"></div>
          </div>
          
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="updateExperienceBtn">
          <i class="fas fa-save me-1"></i>Save All Changes
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>