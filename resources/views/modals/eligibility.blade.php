<!-- ELIGIBILITY MODAL -->
<div class="modal fade" id="eligibilityModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title">Add Eligibility</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

     <!-- Modal Body -->
<div class="modal-body p-0">

  <div class="row g-0">

    <!-- LEFT SIDE -->
    <div class="col-12 col-md-4 p-4 border-end eligibility-left">
      <div class="eligibility-summary-sticky">

        <!-- RUBRICS -->
        <div class="card shadow-sm mb-3 border-light-green bg-light-green">
          <div class="card-header text-green fw-bold">
            <i class="fas fa-list-check me-2"></i>Eligibility Rubrics
          </div>
          <div class="card-body small text-dark">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">RA 1080 (Teacher)</li>
              <li class="list-group-item">Expired ID → Upload renewal receipt or renewed ID</li>
              <li class="list-group-item">Accepted formats: PDF, Word, Images</li>
            </ul>
          </div>
        </div>

        <!-- SUMMARY -->
        <div id="eligibility_summary_modal" class="card shadow-sm border-light-green bg-light-green">
          <div class="card-body p-3 text-green">
            <strong>Eligibility Summary</strong><br>
            Status: <span class="badge bg-secondary">Waiting...</span><br>
            Selected Eligibility: <span class="fw-bold">—</span>
          </div>
        </div>

      </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="col-12 col-md-8 p-4 eligibility-right">

      <div class="row g-3">

        <!-- Eligibility -->
        <div class="col-12">
          <div class="card p-3 shadow-sm border-0">
            <label class="form-label fw-bold text-primary">Eligibility Name</label>
            <select id="eligibilityInput" class="form-select">
              <option value="">Select Eligibility</option>
              <option value="LET">LET</option>
              <option value="PBET">PBET</option>
              <option value="MAGNA CARTA">MAGNA CARTA</option>
            </select>
          </div>
        </div>

        <!-- Expiry -->
        <div class="col-12">
          <div class="card p-3 shadow-sm border-0">
            <label class="form-label fw-bold text-primary">ID Expiry Date</label>
            <input type="date" id="eligibilityExpiry" class="form-control">
          </div>
        </div>

        <!-- Attachment -->
        <div class="col-12">
          <div class="card p-3 shadow-sm border-0">
            <label class="form-label fw-bold text-primary">Attachment</label>
            <input type="file" id="eligibilityAttachment" class="form-control">
          </div>
        </div>

      </div>

    </div>

  </div>

</div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="saveEligibilityBtn" onclick="saveEligibility()">
          <i class="bi bi-check-circle me-1"></i> Save Eligibility
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
