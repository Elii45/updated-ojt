document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch_offices.php")
        .then(response => response.json())
        .then(data => {
            const officeDropdown = document.getElementById("officeDropdown");
            data.forEach(office => {
                const option = document.createElement("option");
                option.value = office.office_id;
                option.textContent = office.office_name;
                officeDropdown.appendChild(option);
            });
        })
        .catch(error => console.error("Error loading offices:", error));
});
