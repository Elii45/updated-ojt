document.addEventListener("DOMContentLoaded", function () {
    fetchEmployees();

    let addButton = document.getElementById("addDropdown");
    addButton.addEventListener("click", function () {
        addDropdown();
    });

    let firstDropdown = document.getElementById("request");
    attachChangeListener(firstDropdown);
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
            populateDropdown(document.getElementById("request"), true);
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

    attachChangeListener(selectElement);
}

function addDropdown() {
    const container = document.getElementById("dropdown-container");

    let newGroup = document.createElement("div");
    newGroup.classList.add("request-group");

    let select = document.createElement("select");
    select.name = "request[]";  // ✅ Ensures the backend receives an array
    select.required = true;
    select.classList.add("request");

    populateDropdown(select, false);

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

    attachChangeListener(select);
}

function updateDropdowns() {
    document.querySelectorAll(".request").forEach((select, index) => {
        let previousValue = select.value;
        populateDropdown(select, index === 0); // Allow "PITO Office" only in the first dropdown
        select.value = previousValue;
    });
    handleSelection();
}

function getSelectedEmployees() {
    return Array.from(document.querySelectorAll(".request"))
        .map(select => select.value)
        .filter(value => value !== "");
}

function attachChangeListener(selectElement) {
    selectElement.addEventListener("change", handleSelection);
}

function handleSelection() {
    let firstDropdown = document.getElementById("request");
    let addButton = document.getElementById("addDropdown");

    if (firstDropdown.value === "PITO Office") {
        addButton.disabled = true;
        document.querySelectorAll(".request-group").forEach((group, index) => {
            if (index > 0) group.remove();
        });
    } else {
        addButton.disabled = false;
    }
}
