/* =======================================
GLOBAL FUNCTIONS
======================================= */
function toggleSubmitButtonByResult() {
    const finalText = document.getElementById("finalRating")?.textContent || "";
    const submitBtn = document.getElementById("submitBtn");

    if (!submitBtn) return;

    const status = finalText.trim();

    // ❌ DISABLE kung walang result or in progress or waiting
    if (
        status === "" ||
        status.includes("WAITING") ||
        status.includes("IN PROGRESS") ||
        status.includes("Loading")
    ) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = "⏳ Complete requirements first";
        return;
    }

    // ✅ ENABLE kahit MET or NOT MET
    if (status.includes("MET") || status.includes("NOT MET")) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = "💾 Submit Application";
        return;
    }

    // fallback
    submitBtn.disabled = true;
    submitBtn.innerHTML = "⏳ Complete requirements first";
}
function syncRow(id){

    const o = document.querySelector(`input[name="ppst[${id}][O]"]`);
    const vs = document.querySelector(`input[name="ppst[${id}][VS]"]`);
    const s = document.querySelector(`input[name="ppst[${id}][S]"]`);

    if(!o || !vs || !s) return;

    o.disabled = false;
    vs.disabled = false;
    s.disabled = false;

    if(o.checked){
        vs.checked = false;
        s.checked = false;
        vs.disabled = true;
        s.disabled = true;
    }

    if(vs.checked){
        o.checked = false;
        s.checked = false;
        o.disabled = true;
        s.disabled = true;
    }

    if(s.checked){
        o.checked = false;
        vs.checked = false;
        o.disabled = true;
        vs.disabled = true;
    }
}


/* =======================================
TOTALS
======================================= */

function updateTotals(){

    let coiO = 0, coiVS = 0, ncoiO = 0, ncoiVS = 0;
    let totalS = 0, totalVS = 0;

    document.querySelectorAll('.ppst-checkbox, .ppst-checkbox-s').forEach(box=>{

        if(box.checked){

            const type = box.dataset.type;
            const col = box.dataset.column;

            if(col === "S") totalS++;
            if(col === "VS") totalVS++;

            if(type === "COI"){
                if(col === "O") coiO++;
                if(col === "VS") coiVS++;
            }

            if(type === "NCOI"){
                if(col === "O") ncoiO++;
                if(col === "VS") ncoiVS++;
            }
        }
    });

    // ✅ DISPLAY TOTALS
    document.getElementById("totalCOI_O").value = coiO;
    document.getElementById("totalCOI_VS").value = coiVS;
    document.getElementById("totalNCOI_O").value = ncoiO;
    document.getElementById("totalNCOI_VS").value = ncoiVS;

    // ✅ SAVE TO HIDDEN INPUTS (FOR DATABASE)
    document.getElementById("coi_outstanding").value = coiO;
    document.getElementById("coi_vs").value = coiVS;
    document.getElementById("ncoi_outstanding").value = ncoiO;
    document.getElementById("ncoi_vs").value = ncoiVS;

    calculateFinalRating(totalS, totalVS);
}


/* =======================================
FINAL RATING
======================================= */

function calculateFinalRating(totalS = 0, totalVS = 0){

    const position = document.getElementById("position_applied")?.value;
    const finalEl = document.getElementById("finalRating");
    const warningEl = document.getElementById("ncoiWarning");
    const progressEl = document.getElementById("ppstProgress");
    const resultInput = document.getElementById("ppst_result");

    const coiO = +document.getElementById("totalCOI_O").value || 0;
    const coiVS = +document.getElementById("totalCOI_VS").value || 0;
    const ncoiO = +document.getElementById("totalNCOI_O").value || 0;
    const ncoiVS = +document.getElementById("totalNCOI_VS").value || 0;

    if(!position){
        finalEl.textContent = "WAITING ⏳";
        resultInput.value = "draft";
        return;
    }

    const totalChecked = coiO + coiVS + ncoiO + ncoiVS + totalS;

    if(totalChecked === 0){
        finalEl.textContent = "WAITING ⏳";
        resultInput.value = "draft";
        return;
    }

    // =============================
    // DISQUALIFICATION RULES
    // =============================
    if(["Teacher IV","Teacher V","Teacher VI","Teacher VII"].includes(position)){
        if(totalS >= 3){
            finalEl.textContent = "NOT MET ❌ - 3 Satisfactor reached";
            resultInput.value = "not_met";
            return;
        }
    }

    if(["Master Teacher I","Master Teacher II","Master Teacher III"].includes(position)){
        if(totalVS >= 3){
            finalEl.textContent = "NOT MET ❌ - 3 Very Satisfactor reached";
            resultInput.value = "not_met";
            return;
        }
    }

    // =============================
    // REQUIREMENTS
    // =============================
    const prRequirements = {
        "Teacher II": { coiVS:6 , ncoiVS:4 },
        "Teacher III": { coiVS:12 , ncoiVS:8 },
        "Teacher IV": { coiVS:21 , ncoiVS:16 },
        "Teacher V": { coiO:6 , ncoiO:4 },
        "Teacher VI": { coiO:12 , ncoiVS:4 , ncoiO:4 },
        "Teacher VII": { coiO:18 , ncoiVS:6 , ncoiO:6 },
        "Master Teacher I": { coiO:21 , ncoiVS:8 , ncoiO:8 },
        "Master Teacher II": { coiO:10 , ncoiVS:5 , ncoiO:5 },
        "Master Teacher III": { coiO:21 , ncoiVS:8 , ncoiO:8 }
    };

    const req = prRequirements[position];
    if(!req){
        finalEl.textContent = "WAITING ⏳";
        resultInput.value = "draft";
        return;
    }

    // =============================
    // TEACHER V SPECIAL LOGIC
    // =============================
    if(position === "Teacher V"){

        const totalCOI = coiO + coiVS;
        const totalNCOI = ncoiO + ncoiVS;

        if(coiO < 6){
            finalEl.textContent = "NOT MET ❌ - Need 6 COI Outstanding";
            resultInput.value = "not_met";
            toggleSubmitButtonByResult();
            return;
        }

        if(ncoiO < 4){
            finalEl.textContent = "NOT MET ❌ - Need 4 NCOI Outstanding";
            resultInput.value = "not_met";

            toggleSubmitButtonByResult();
            return;
        }

        if(totalCOI < 21 || totalNCOI < 16){
            finalEl.textContent = "IN PROGRESS ⏳";
            resultInput.value = "draft";

            toggleSubmitButtonByResult();
            return;
        }

        finalEl.textContent = "MET ✅";
        resultInput.value = "met";

        toggleSubmitButtonByResult();
        return;
    }

    // =============================
    // REQUIRED VALUES
    // =============================
    const requiredCOI = (req.coiO || 0) + (req.coiVS || 0);
    const actualCOI = coiO + coiVS;

    const requiredNCOI = (req.ncoiO || 0) + (req.ncoiVS || 0);
    const actualNCOI = ncoiO + ncoiVS;

    const isHighPosition = [
        "Teacher VI",
        "Teacher VII",
        "Master Teacher I",
        "Master Teacher II",
        "Master Teacher III"
    ].includes(position);

    let maxAllowedVS = requiredNCOI - ncoiO;

    // =============================
    // 1. IN PROGRESS CHECK (FIRST)
    // =============================
    if (actualCOI < requiredCOI || actualNCOI < requiredNCOI) {
        finalEl.textContent = "IN PROGRESS ⏳";
        resultInput.value = "draft";

        toggleSubmitButtonByResult();
        return;
    }

    // =============================
    // 2. DISQUALIFIED RULES
    // =============================
    if (isHighPosition && ncoiVS > maxAllowedVS) {
        finalEl.textContent = "NOT MET ❌ - VS > O";
        resultInput.value = "not_met";

        toggleSubmitButtonByResult();
        return;
    }

    // =============================
    // 3. QUALIFIED
    // =============================
    finalEl.textContent = "MET ✅";
    resultInput.value = "met";

    toggleSubmitButtonByResult();
}


/* =======================================
EVENTS
======================================= */

document.addEventListener("change", function(e){
    if(e.target.classList.contains("ppst-checkbox") || 
       e.target.classList.contains("ppst-checkbox-s")){
        const id = e.target.dataset.id;
        syncRow(id);
        updateTotals();
    }
});

function initPPST(){
    updateTotals();
}


/* =======================================
POSITION CHANGE
======================================= */

document.getElementById("position_applied")?.addEventListener("change", function(){

    const position = this.value;
    document.getElementById("finalRating").textContent = "Loading...";

    fetch(`/load-ppst?position=${encodeURIComponent(position)}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("ppst-container").innerHTML = html;
            initPPST();
        });

});


/* =======================================
ON LOAD
======================================= */

document.addEventListener("DOMContentLoaded", function(){
    initPPST();
    toggleSubmitButtonByResult();
});