const sgMap = {

"Teacher I": 11,
"Teacher II": 12,
"Teacher III": 13,
"Teacher IV": 14,
"Teacher V": 15,
"Teacher VI": 16,
"Teacher VII": 17,
"Master Teacher I": 18,
"Master Teacher II": 19,
"Master Teacher III": 20,
"Master Teacher IV": 21,
"Master Teacher V": 22

};

const salaryTable = {

11:[31705,31820,32109,32401,32697,32998,33302,33611],
12:[33947,34069,34357,34648,34943,35242,35544,35850],
13:[36125,36283,36599,36919,37244,37572,37904,38241],
14:[38764,39141,39523,39910,40300,40696,41097,41503],
15:[42178,42594,43015,43442,43874,44310,44753,45202],
16:[45694,46152,46615,47084,47559,48040,48528,49020],
17:[49562,50066,50576,51092,51614,52144,52678,53221],
18:[53818,54371,54933,55499,56075,56657,57246,57842],
19:[59153,59966,60793,61632,62486,63353,64236,65132],
20:[66052,66970,67904,68853,69818,70772,71727,72671],
21:[73303,74337,75388,76456,77542,78645,79692,80831],
22:[81796,82963,84151,85356,86582,87746,89011,90295]

};

const position = document.getElementById("current_position");
const step = document.getElementById("step");
const applied = document.getElementById("position_applied");

const salaryField = document.getElementById("salary");

const fromPosition = document.getElementById("from_position");
const fromGrade = document.getElementById("from_grade");
const toPosition = document.getElementById("to_position");
const toGrade = document.getElementById("to_grade");


function updateForm(){

let pos = position.value;
let stp = step.value;
let appliedPos = applied ? applied.value : null;


/* ===== SALARY COMPUTATION ===== */

if(pos && stp){

let sg = sgMap[pos];
let monthly = salaryTable[sg][stp-1];
let annual = monthly * 12;

salaryField.value = sg + " / " + annual.toLocaleString();

/* FROM POSITION */

if(fromPosition) fromPosition.value = pos;
if(fromGrade) fromGrade.value = sg;

}


/* ===== TO POSITION ===== */

if(appliedPos){

let sgTo = sgMap[appliedPos];

if(toPosition) toPosition.value = appliedPos;
if(toGrade) toGrade.value = sgTo;

}

}


position.addEventListener("change", updateForm);
step.addEventListener("change", updateForm);

if(applied){
applied.addEventListener("change", updateForm);
}
