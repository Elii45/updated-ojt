document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch_offices.php") // Fetch from PHP
        .then(response => response.json()) 
        .then(data => {
            console.log("Fetched Offices:", data); // Debugging

            const officeDropdown = document.getElementById("officeDropdown");

            if (!Array.isArray(data)) {
                console.error("Invalid JSON format", data);
                return;
            }

            data.forEach(office => {
                const option = document.createElement("option");
                option.value = office.office_id;
                option.textContent = office.office_name;
                officeDropdown.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching offices:", error));
});
