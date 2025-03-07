function submitAndPrint() {
    var form = document.querySelector("form");
    var inputs = form.querySelectorAll("input[required]");
    var isValid = true;

    // Validate required fields
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.border = "2px solid red";
        } else {
            input.style.border = "";
        }
    });

    if (!isValid) {
        showCustomAlert("Please fill out all required fields before printing.");
        return;
    }

    // Store formatted date values before submitting
    document.querySelectorAll("input").forEach(input => {
        if (input.type === "date") {
            let dateValue = input.value;
            if (dateValue) {
                input.setAttribute("data-value", formatDate(dateValue));
            }
        } else {
            input.setAttribute("data-value", input.value);
        }
    });

    // Prepare form data for submission to the database
    var formData = new FormData(form);

    fetch("submit_cto.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            setTimeout(() => {
                window.print();
            }, 500);
        } else {
            showCustomAlert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showCustomAlert("An error occurred while submitting the form.");
    });
}

// Format date to "Month DD, YYYY"
function formatDate(dateString) {
    const months = [
        "January", "February", "March", "April", "May", "June", 
        "July", "August", "September", "October", "November", "December"
    ];
    
    let date = new Date(dateString);
    let day = date.getDate().toString().padStart(2, "0");
    let month = months[date.getMonth()];
    let year = date.getFullYear();

    return `${month} ${day}, ${year}`;
}

// Show error alert
function showCustomAlert(message) {
    var alertBox = document.getElementById("customAlert");
    alertBox.querySelector("p").textContent = message;
    alertBox.style.display = "block";
}

// Close error alert
function closeCustomAlert() {
    document.getElementById("customAlert").style.display = "none";
}
