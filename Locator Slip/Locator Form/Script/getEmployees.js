document.addEventListener("DOMContentLoaded", function () {
    fetchEmployees();

    let addButton = document.getElementById("addDropdown");
    addButton.addEventListener("click", function () {
        addDropdown();
    });
});

let employeesList = [];

function fetchEmployees() {
    fetch("Backend/getEmployees.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }
            employeesList = data;
        })
        .catch(error => console.error("Error fetching employee names:", error));
}

function populateDropdown(selectElement, isFirst = false) {
    let selectedValues = getSelectedEmployees();

    selectElement.innerHTML = `<option value="" disabled selected>Select Employee</option>`;

    if (isFirst) {
        selectElement.innerHTML += `<option value="PITO Office">PITO Office</option>`;
    }

    employeesList.forEach(employee => {
        if (!selectedValues.includes(employee.name)) {
            let option = document.createElement("option");
            option.value = employee.name;
            option.textContent = employee.name;
            selectElement.appendChild(option);
        }
    });
}

function addDropdown() {
    const container = document.getElementById("dropdown-container");

    let newGroup = document.createElement("div");
    newGroup.classList.add("request-group");

    let select = document.createElement("select");
    select.name = "request[]";  
    select.required = true;
    select.classList.add("request");

    let isFirstDropdown = container.querySelectorAll(".request").length === 0;
    populateDropdown(select, isFirstDropdown);

    // Ensure it always starts with "Select Employee"
    select.value = "";

    let removeBtn = document.createElement("button");
    removeBtn.textContent = "Remove";
    removeBtn.type = "button";
    removeBtn.classList.add("remove-btn");
    removeBtn.onclick = function () {
        newGroup.remove();
        updateDropdowns();
    };

    newGroup.appendChild(select);
    newGroup.appendChild(removeBtn);
    container.appendChild(newGroup);

    select.addEventListener("change", function () {
        updateDropdowns();
    });

    updateDropdowns();
}

function updateDropdowns() {
    let selectedValues = getSelectedEmployees();

    document.querySelectorAll(".request").forEach((select, index) => {
        let previousValue = select.value;

        let availableOptions = employeesList.filter(emp => 
            !selectedValues.includes(emp.name) || emp.name === previousValue
        );

        select.innerHTML = `<option value="" disabled ${previousValue ? "" : "selected"}>Select Employee</option>`;

        if (index === 0) {
            select.innerHTML += `<option value="PITO Office">PITO Office</option>`;
        }

        availableOptions.forEach(employee => {
            let option = document.createElement("option");
            option.value = employee.name;
            option.textContent = employee.name;
            select.appendChild(option);
        });

        if (previousValue && select.querySelector(`option[value="${previousValue}"]`)) {
            select.value = previousValue;
        } else {
            select.value = ""; // Ensure "Select Employee" is shown when adding new
        }
    });

    handleSelection();
}

function getSelectedEmployees() {
    return Array.from(document.querySelectorAll(".request"))
        .map(select => select.value)
        .filter(value => value !== "");
}

function handleSelection() {
    let dropdowns = document.querySelectorAll(".request");
    let addButton = document.getElementById("addDropdown");

    if (dropdowns.length > 0 && dropdowns[0].value === "PITO Office") {
        addButton.disabled = true;
        dropdowns.forEach((select, index) => {
            if (index > 0) select.closest(".request-group").remove();
        });
    } else {
        addButton.disabled = false;
    }
}
