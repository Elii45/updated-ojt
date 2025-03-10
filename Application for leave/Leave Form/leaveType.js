document.addEventListener("DOMContentLoaded", function () {
    const leaveTypeRadios = document.querySelectorAll('input[name="leaveType"]');
    const detailSections = {
        vacationLeave: document.getElementById("vacationDetails"),
        sickLeave: document.getElementById("sickLeaveDetails"),
        specialLeave: document.getElementById("womenLeaveDetails"),
        studyLeave: document.getElementById("studyLeaveDetails"),
        otherPurposes: document.getElementById("otherPurposes"),
    };

    const allDetailInputs = document.querySelectorAll('.container3B input[type="radio"], .container3B input[type="text"]');

    // Function to disable all sections in 6.B and their inputs
    function disableAllDetails() {
        Object.values(detailSections).forEach(section => {
            if (section) {
                section.style.display = "none";
                section.querySelectorAll("input").forEach(input => {
                    input.disabled = true;
                    input.checked = false;
                    input.value = ""; // Clear text inputs
                });
            }
        });
    }

    // Initially disable all 6.B sections and inputs
    disableAllDetails();

    // Event listener for 6.A radio buttons
    leaveTypeRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            disableAllDetails(); // Reset everything first
            
            if (detailSections[this.value]) {
                const section = detailSections[this.value];
                section.style.display = "block";
                section.querySelectorAll("input").forEach(input => {
                    input.disabled = false;
                });
            }
        });
    });
});