document.addEventListener("DOMContentLoaded", function(){

const current = document.getElementById("current_position");
const applied = document.getElementById("position_applied");

const promotionMap = {

"Teacher I": ["Teacher II","Teacher III","Teacher IV"],
"Teacher II": ["Teacher III","Teacher IV","Teacher V"],
"Teacher III": ["Teacher IV","Teacher V","Teacher VI"],
"Teacher IV": ["Teacher V","Teacher VI","Teacher VII"],
"Teacher V": ["Teacher VI","Teacher VII","Master Teacher I"],
"Teacher VI": ["Teacher VII","Master Teacher I"],
"Teacher VII": ["Master Teacher I"],

"Master Teacher I": ["Master Teacher II","Master Teacher III","Master Teacher IV"],
"Master Teacher II": ["Master Teacher III","Master Teacher IV","Master Teacher V"],
"Master Teacher III": ["Master Teacher IV","Master Teacher V"],
"Master Teacher IV": ["Master Teacher V"],
"Master Teacher V": []

};

function populateApplied(selected){

    applied.innerHTML = '<option value="">-- Select Position Applied --</option>';

    if(promotionMap[selected]){
        promotionMap[selected].forEach(function(position){

            let option = document.createElement("option");
            option.value = position;
            option.textContent = position;

            applied.appendChild(option);

        });
    }
}

// 🔥 ON CHANGE
current.addEventListener("change", function(){
    populateApplied(this.value);
});

// 🔥 AUTO LOAD (FIX FOR YOUR BUG)
if(current.value){
    populateApplied(current.value);
}

});