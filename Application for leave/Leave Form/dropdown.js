document.addEventListener("DOMContentLoaded", () => {
    console.log("Dropdown script loaded")

    // Get references
    const officeDropdown = document.getElementById("officeDropdown")

    if (!officeDropdown) {
        console.error("Office dropdown not found!")
        return
    }

    // Fetch offices from the server
    fetch("fetch_offices.php")
        .then((response) => response.json())
        .then((data) => {
            console.log("Fetched offices:", data)

            // Populate the dropdown
            data.forEach((office) => {
                const option = document.createElement("option")
                option.value = office.office_ID // Ensure this matches the database field
                option.textContent = office.office_name
                officeDropdown.appendChild(option)
            })
        })
        .catch((error) => console.error("Error loading offices:", error))

    // Form submit validation
    const form = document.querySelector("form")
    if (form) {
        form.addEventListener("submit", (event) => {
            if (!officeDropdown.value) {
                event.preventDefault()
                alert("Please select an office before submitting the form.")
                console.error("Form submission prevented: No office selected")
            }
        })
    } else {
        console.error("Form not found!")
    }
})
