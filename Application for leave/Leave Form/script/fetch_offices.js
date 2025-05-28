document.addEventListener("DOMContentLoaded", function () {
    fetch("./backend/fetch_offices.php")
        .then(response => response.json())
        .then(data => {
            const officeDropdown = document.getElementById("officeDropdown");
            const selectedOfficeId = officeDropdown.getAttribute("data-selected-office");

            if (!Array.isArray(data)) {
                console.error("Invalid JSON format", data);
                return;
            }

            data.forEach(office => {
                const option = document.createElement("option");
                option.value = office.office_id;
                option.textContent = office.office_name;

                // Fix: Convert both to strings before comparing
                if (String(office.office_id) === String(selectedOfficeId)) {
                    option.selected = true;
                }

                officeDropdown.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching offices:", error));
});
