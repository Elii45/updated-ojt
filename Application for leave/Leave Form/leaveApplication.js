function disableOtherCheckboxes(selectedCheckbox) {
    let checkboxes = document.querySelectorAll('input[name^="leaveType"]');
    checkboxes.forEach((checkbox) => {
        if (checkbox !== selectedCheckbox) {
            checkbox.checked = false;
        }
    });
    updateRequiredFields(selectedCheckbox.value);
}

document.addEventListener("DOMContentLoaded", function() {
let withinPhilippinesCheckbox = document.querySelector('input[value="withinPhilippines"]');
let abroadCheckbox = document.querySelector('input[value="abroad"]');
let withinPhilippinesDetail = document.getElementById("withinPhilippinesDetail");
let abroadDetails = document.getElementById("abroadDetails");

function toggleDetail(selectedCheckbox) {
if (selectedCheckbox === withinPhilippinesCheckbox) {
    abroadCheckbox.checked = false;
    withinPhilippinesDetail.disabled = false;
    abroadDetails.disabled = true;
    abroadDetails.value = "";
} else if (selectedCheckbox === abroadCheckbox) {
    withinPhilippinesCheckbox.checked = false;
    abroadDetails.disabled = false;
    withinPhilippinesDetail.disabled = true;
    withinPhilippinesDetail.value = "";
}

if (!withinPhilippinesCheckbox.checked && !abroadCheckbox.checked) {
    withinPhilippinesDetail.disabled = true;
    abroadDetails.disabled = true;
    withinPhilippinesDetail.value = "";
    abroadDetails.value = "";
}
}

withinPhilippinesCheckbox.addEventListener("change", function() {
toggleDetail(this);
});

abroadCheckbox.addEventListener("change", function() {
toggleDetail(this);
});

withinPhilippinesDetail.disabled = true;
abroadDetails.disabled = true;
});



document.addEventListener("DOMContentLoaded", function() {
let hospitalCheckbox = document.querySelector('input[name="hospital"]');
let outPatientCheckbox = document.querySelector('input[name="outPatient"]');
let hospitalDetail = document.querySelector('input[name="hospitalDetail"]'); 
let outPatientDetail = document.querySelector('input[name="outPatientDetail"]');

function toggleHospital(selectedCheckbox) {
if (selectedCheckbox === hospitalCheckbox) {
    outPatientCheckbox.checked = false;
    hospitalDetail.disabled = false;
    outPatientDetail.disabled = true;
    outPatientDetail.value = "";
} else if (selectedCheckbox === outPatientCheckbox) {
    hospitalCheckbox.checked = false;
    outPatientDetail.disabled = false;
    hospitalDetail.disabled = true; 
    hospitalDetail.value = "";
}

if (!hospitalCheckbox.checked && !outPatientCheckbox.checked) {
    hospitalDetail.disabled = true;
    hospitalDetail.value = ""; 
    outPatientDetail.disabled = true;
    outPatientDetail.value = "";
}
}

hospitalCheckbox.addEventListener("change", function() {
toggleHospital(this);
});

outPatientCheckbox.addEventListener("change", function() {
toggleHospital(this);
});

hospitalDetail.disabled = true;
outPatientDetail.disabled = true;
});

document.addEventListener("DOMContentLoaded", function() {
let completionCheckbox = document.querySelector('input[name="completion"]');
let examCheckbox = document.querySelector('input[name="exam"]');
let completionDetail = document.querySelector('input[name="completionDetail"]');

function toggleCompletion(selectedCheckbox) {
if (selectedCheckbox === completionCheckbox) {
    examCheckbox.checked = false;
    completionDetail.disabled = false;
} else if (selectedCheckbox === examCheckbox) {
    completionCheckbox.checked = false;
    completionDetail.disabled = true; 
    completionDetail.value = "";
}
}

completionCheckbox.addEventListener("change", function() {
toggleCompletion(this);
});

examCheckbox.addEventListener("change", function() {
toggleCompletion(this);
});

completionDetail.disabled = true;
});


document.addEventListener("DOMContentLoaded", function() {
let monetizationCheckbox = document.querySelector('input[name="monetization"]');
let terminalCheckbox = document.querySelector('input[name="terminal"]');
let monetizationDetail = document.querySelector('input[name="monetizationDetail"]');

function toggleMonetization(selectedCheckbox) {
if (selectedCheckbox === monetizationCheckbox) {
    terminalCheckbox.checked = false;
    monetizationDetail.disabled = false; 
} else if (selectedCheckbox === terminalCheckbox) {
    monetizationCheckbox.checked = false;
    monetizationDetail.disabled = true; 
    monetizationDetail.value = ""; 
}
}

monetizationCheckbox.addEventListener("change", function() {
toggleMonetization(this);
});

terminalCheckbox.addEventListener("change", function() {
toggleMonetization(this);
});

monetizationDetail.disabled = true;
});


document.addEventListener("DOMContentLoaded", function() {
let notRequestedCheckbox = document.querySelector('input[name="notRequested"]');
let requestedCheckbox = document.querySelector('input[name="requested"]');
let requestDetail = document.querySelector('input[name="requestDetail"]'); 

function toggleRequest(selectedCheckbox) {
if (selectedCheckbox === notRequestedCheckbox) {
    requestedCheckbox.checked = false;
    requestDetail.disabled = true; 
    requestDetail.value = ""; 
} else if (selectedCheckbox === requestedCheckbox) {
    notRequestedCheckbox.checked = false;
    requestDetail.disabled = false; 
}
}

notRequestedCheckbox.addEventListener("change", function() {
toggleRequest(this);
});

requestedCheckbox.addEventListener("change", function() {
toggleRequest(this);
});

requestDetail.disabled = true;
});



document.addEventListener("DOMContentLoaded", function() {
let approvalCheckbox = document.querySelector('input[name="approval"]');
let disapprovalCheckbox = document.querySelector('input[name="disapproval"]');
let disapprovalDetail = document.querySelector('input[name="disapprovalDetail"]');

function toggleRecommendation(selectedCheckbox) {
if (selectedCheckbox === approvalCheckbox) {
    disapprovalCheckbox.checked = false;
    disapprovalDetail.disabled = true; 
    disapprovalDetail.value = ""; 
} else if (selectedCheckbox === disapprovalCheckbox) {
    approvalCheckbox.checked = false;
    disapprovalDetail.disabled = false; 
}
}

approvalCheckbox.addEventListener("change", function() {
toggleRecommendation(this);
});

disapprovalCheckbox.addEventListener("change", function() {
toggleRecommendation(this);
});

disapprovalDetail.disabled = true;
});

document.addEventListener("DOMContentLoaded", function() {
let approvalFields = document.querySelectorAll('input[name="pay"], input[name="withoutPay"], input[name="othersApproved"]');
let disapprovedToField = document.querySelector('input[name="disapprovedTo"]');

function toggleFields() {
let isApprovalFilled = Array.from(approvalFields).some(input => input.value.trim() !== "");
let isDisapprovedFilled = disapprovedToField.value.trim() !== "";

if (isApprovalFilled) {
    disapprovedToField.disabled = true;
    disapprovedToField.value = ""; 
} else {
    disapprovedToField.disabled = false;
}

if (isDisapprovedFilled) {
    approvalFields.forEach(input => {
        input.disabled = true;
        input.value = "";
    });
} else {
    approvalFields.forEach(input => input.disabled = false);
}
}

approvalFields.forEach(input => {
input.addEventListener("input", toggleFields);
});

disapprovedToField.addEventListener("input", toggleFields);

disapprovedToField.disabled = false;
approvalFields.forEach(input => input.disabled = false);
});


function updateRequiredFields(selectedLeave) {
    let sections = ["vacationDetails", "sickLeaveDetails", "womenLeaveDetails", "studyLeaveDetails", "otherPurposes"];
    
    sections.forEach(section => {
        let inputs = document.querySelectorAll(`#${section} input`);
        inputs.forEach(input => input.disabled = true);
    });

    switch (selectedLeave) {
        case "vacationLeave":
        case "specialPrivilege":
            enableFields("vacationDetails");
            break;
        case "sickLeave":
            enableFields("sickLeaveDetails");
            break;
        case "specialLeave":
            enableFields("womenLeaveDetails");
            break;
        case "studyLeave":
            enableFields("studyLeaveDetails");
            break;
        
        case "others":
            enableFields("otherPurposes");
            break;
    }
}

function enableFields(sectionId) {
    let inputs = document.querySelectorAll(`#${sectionId} input`);
    inputs.forEach(input => input.disabled = false);
}