<div class="modal fade" id="performanceModal">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-success text-white">
        <h6 class="modal-title">Compute Performance Rating</h6>
      </div>

      <div class="modal-body">

        <label>Enter IPCRF Score (1–5)</label>
        <input type="number" id="perfInputModal" step="0.1" class="form-control text-center">

        <small class="text-muted d-block text-center mt-2">
          (Score ÷ 5 × 30)
        </small>

        <hr>

        <input type="text" id="perfResultModal" class="form-control text-center" readonly>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="applyPerfBtn">Apply</button>
      </div>

    </div>
  </div>
</div>