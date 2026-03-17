function initPPST() {

const rows = document.querySelectorAll("tr");

function syncRow(id){

const o = document.querySelector(`input[name="ppst[${id}][O]"]`);
const vs = document.querySelector(`input[name="ppst[${id}][VS]"]`);
const s = document.querySelector(`input[name="ppst[${id}][S]"]`);

if(!o || !vs || !s) return;

/* if O checked */
if(o.checked){
vs.checked = false;
s.checked = false;
}

/* if VS checked */
if(vs.checked){
o.checked = false;
s.checked = false;
}

/* if S checked */
if(s.checked){
o.checked = false;
vs.checked = false;
}

}

function updateTotals(){

let coiO = 0;
let coiVS = 0;
let ncoiO = 0;
let ncoiVS = 0;

document.querySelectorAll('.ppst-checkbox, .ppst-checkbox-s').forEach(box=>{

if(box.checked){

const type = box.dataset.type;
const col = box.dataset.column;

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

document.getElementById("totalCOI_O").value = coiO;
document.getElementById("totalCOI_VS").value = coiVS;
document.getElementById("totalNCOI_O").value = ncoiO;
document.getElementById("totalNCOI_VS").value = ncoiVS;

calculateFinalRating();

}

/* EVENTS */

document.querySelectorAll('.ppst-checkbox, .ppst-checkbox-s').forEach(box=>{

box.addEventListener("change", function(){

const id = this.dataset.id;

syncRow(id);
updateTotals();

});

});

updateTotals();

}


/* =======================================
FINAL RATING BASED ON POSITION APPLIED
======================================= */

function calculateFinalRating(){

const position = document.getElementById("position_applied")?.value;

const coiO = parseInt(document.getElementById("totalCOI_O").value) || 0;
const coiVS = parseInt(document.getElementById("totalCOI_VS").value) || 0;
const ncoiO = parseInt(document.getElementById("totalNCOI_O").value) || 0;
const ncoiVS = parseInt(document.getElementById("totalNCOI_VS").value) || 0;


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

document.getElementById("finalRating").textContent = "-";
return;

}

const qualified = (

(!req.coiO || coiO >= req.coiO) &&
(!req.coiVS || coiVS >= req.coiVS) &&
(!req.ncoiO || ncoiO >= req.ncoiO) &&
(!req.ncoiVS || ncoiVS >= req.ncoiVS)

);

document.getElementById("finalRating").textContent =
qualified ? "QUALIFIED ✅" : "DISQUALIFIED ❌";

}


/* INIT */

document.addEventListener("DOMContentLoaded", function(){

initPPST();

});