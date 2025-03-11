// Handle status messages from URL parameters
const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get("status");

if (status) {
    const alertBox = document.createElement("div");
    alertBox.classList.add("alert");

    if (status === "success") {
        alertBox.textContent = "Leave Application Submitted Successfully!";
        alertBox.classList.add("success");
    } else if (status === "error") {
        alertBox.textContent = "Error submitting the form. Please try again.";
        alertBox.classList.add("error");
    }

    document.body.prepend(alertBox);
    alertBox.style.display = "block";

    setTimeout(() => {
        alertBox.style.display = "none";
    }, 5000);
}

// Function to print locator slip
function printLocator() {
    document.body.classList.add("print-mode"); // Apply print styles if needed
    window.print(); // Open print dialog
    document.body.classList.remove("print-mode"); // Remove after printing
}

document.addEventListener("DOMContentLoaded", function () {
    function formatInput(inputId, displayId) {
        const input = document.getElementById(inputId);
        const display = document.getElementById(displayId);

        if (input.value) {
            display.textContent = input.value;
            input.style.display = "none"; // Hide the input
            display.style.display = "inline"; // Show the formatted value
        }
    }

    // Ensure previously entered values are displayed on page load
    function initializeInputs() {
        formatInput("inclDate", "inclDateText");
        formatInput("timeDeparture", "timeDepartureText");
        formatInput("timeArrival", "timeArrivalText");
    }

    initializeInputs();
});
