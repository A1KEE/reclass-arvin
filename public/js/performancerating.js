$(function(){

    // =====================
    // COMPUTE PERFORMANCE
    // =====================
    function computePerf(){
        let val = parseFloat($("#perfInputModal").val());
        let output = $("#perfResultModal");

        if(isNaN(val)){
            output.val('');
            return;
        }

        let result = Math.round((val / 5) * 30);
        output.val(result);
    }

    // =====================
    // LIVE COMPUTE
    // =====================
    $(document).on("input", "#perfInputModal", function(){
        computePerf();
    });

    // =====================
    // APPLY BUTTON
    // =====================
    $(document).on("click", "#applyPerfBtn", function(e){
        e.preventDefault();

        let result = $("#perfResultModal").val();

        if(!result){
            alert("Please enter a score first");
            return;
        }

        // ilagay sa table (CAR)
        $("#performanceFinal").val(result);

        // close modal
        let modal = bootstrap.Modal.getInstance(document.getElementById('performanceModal'));
        if(modal){
            modal.hide();
        }
    });

});