

<!-- Hidden inputs for QS -->
<input type="hidden" id="appliedPosition" value="Teacher V">
<input type="hidden" id="appliedLevel" value="kindergarten">


<div class="modal fade" id="ipcrfModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white border-0">
        <h6 class="modal-title">Upload IPCRF Files</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">

        <p id="ipcrfInstruction" class="text-muted mb-4"></p>

        <div class="row g-3" id="ipcrfContainer">
          <!-- IPCRF upload cards rendered dynamically -->
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveIpcrfBtn">Save</button>
      </div>

    </div>
  </div>
</div>