// ===== Global Data =====
let employeesList = [];

// ===== Fetch Employees =====
async function fetchEmployees() {
  try {
    const response = await fetch("getEmployees.php");
    if (!response.ok) throw new Error("Network error");
    const data = await response.json();

    if (data.error) {
      alert("Error: " + data.error);
      return;
    }

    employeesList = data; // Save fetched employees
    initializeForms(); // Initialize both Create and Edit forms with dropdowns
  } catch (error) {
    alert("Failed to fetch employees: " + error.message);
  }
}

// ===== DOM Elements =====
// --- Create Elements ---
const createContainer = document.getElementById("create-dropdown-container");
const createAddBtn = document.getElementById("createAddDropdown");
const createForm = document.getElementById("createForm");

// --- Read Elements ---
const readContainer = document.getElementById("readContainer");
const editFromReadBtn = document.getElementById("editFromRead");

// --- Edit Elements ---
const editContainer = document.getElementById("edit-dropdown-container");
const editAddBtn = document.getElementById("editAddDropdown");
const editForm = document.getElementById("editForm");

// ===== Initialize Forms =====
function initializeForms() {
  // Add initial dropdowns to Create and Edit forms
  addDropdown(createContainer);
  addDropdown(editContainer);
}

// ===== CREATE =====
// Add new dropdown to Create form
createAddBtn.addEventListener("click", () => addDropdown(createContainer));

// Handle Create form submission
createForm.addEventListener("submit", e => {
  e.preventDefault();
  const selectedEmployees = getSelectedEmployees(createContainer);
  if (!selectedEmployees.length) {
    alert("Select at least one employee.");
    return;
  }
  updateReadForm(selectedEmployees); // Populate Read view
  resetEditForm(); // Clear and reset Edit form
});

// ===== READ =====
// Transfer data from Read view to Edit view
editFromReadBtn.addEventListener("click", () => {
  const selected = getSelectedEmployeesFromRead();
  buildDropdowns(editContainer, selected); // Populate Edit with selected employees
});

// ===== EDIT =====
// Add new dropdown to Edit form
editAddBtn.addEventListener("click", () => addDropdown(editContainer));

// Handle Edit form submission
editForm.addEventListener("submit", e => {
  e.preventDefault();
  const selected = getSelectedEmployees(editContainer);
  if (!selected.length) {
    alert("Select at least one employee.");
    return;
  }
  updateReadForm(selected); // Update Read view with changes
});

// ===== Helper Functions =====

// Adds a new dropdown to a container (Create or Edit)
// Optionally preselect a value
function addDropdown(container, preselect = "") {
  const group = document.createElement("div");
  group.classList.add("request-group");

  const select = document.createElement("select");
  select.name = "request[]";
  select.required = true;
  select.classList.add("request");

  const isFirst = container.querySelectorAll(".request").length === 0;
  populateDropdown(select, container, isFirst, preselect);

  const removeBtn = document.createElement("button");
  removeBtn.type = "button";
  removeBtn.className = "remove-btn";
  removeBtn.textContent = "Remove";
  removeBtn.onclick = () => {
    group.remove();
    updateDropdowns(container);
  };

  group.appendChild(select);

  // Don't show remove button for first PITO Office selection
  if (!(isFirst && preselect === "PITO Office")) {
    group.appendChild(removeBtn);
  }

  container.appendChild(group);
  select.addEventListener("change", () => updateDropdowns(container));
  updateDropdowns(container); // Refresh all dropdowns after adding
}

// Populates a <select> element with available employee options
function populateDropdown(select, container, isFirst, selectedValue) {
  const selected = getSelectedEmployees(container);
  select.innerHTML = `<option value="" disabled>Select Employee</option>`;

  if (isFirst) {
    select.innerHTML += `<option value="PITO Office">PITO Office</option>`;
  }

  employeesList.forEach(emp => {
    if (!selected.includes(emp.name) || emp.name === selectedValue) {
      const option = document.createElement("option");
      option.value = emp.name;
      option.textContent = emp.name;
      select.appendChild(option);
    }
  });

  // Preselect previously chosen value if available
  if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
    select.value = selectedValue;
  } else {
    select.value = "";
  }
}

// Updates all dropdowns in the container to prevent duplicates
function updateDropdowns(container) {
  const selects = container.querySelectorAll(".request");
  const selected = getSelectedEmployees(container);

  selects.forEach((select, i) => {
    const prev = select.value;
    select.innerHTML = `<option value="" disabled>Select Employee</option>`;
    if (i === 0) {
      select.innerHTML += `<option value="PITO Office">PITO Office</option>`;
    }

    employeesList.forEach(emp => {
      if (!selected.includes(emp.name) || emp.name === prev) {
        const option = document.createElement("option");
        option.value = emp.name;
        option.textContent = emp.name;
        select.appendChild(option);
      }
    });

    if (prev && select.querySelector(`option[value="${prev}"]`)) {
      select.value = prev;
    } else {
      select.value = "";
    }
  });

  handleSelection(container); // Special handling for "PITO Office"
}

// Returns array of selected employee names from a container
function getSelectedEmployees(container) {
  return Array.from(container.querySelectorAll(".request"))
    .map(select => select.value)
    .filter(val => val !== "");
}

// Handles logic when "PITO Office" is selected
function handleSelection(container) {
  const selects = container.querySelectorAll(".request");
  const addBtn = container.parentElement.querySelector(".addButton");

  if (selects.length > 0 && selects[0].value === "PITO Office") {
    addBtn.disabled = true;
    selects.forEach((select, i) => {
      if (i > 0) select.closest(".request-group").remove(); // Remove extra dropdowns
    });
  } else {
    addBtn.disabled = false;
  }
}

// Displays selected employees in Read view
function updateReadForm(selected) {
  if (!selected.length) {
    readContainer.textContent = "No employees selected.";
    editFromReadBtn.disabled = true;
    return;
  }

  let html = "<ul>";
  selected.forEach(emp => {
    html += `<li>${emp === "PITO Office" ? "<strong>PITO Office</strong>" : emp}</li>`;
  });
  html += "</ul>";

  readContainer.innerHTML = html;
  editFromReadBtn.disabled = false;
}

// Returns list of employees shown in the Read view
function getSelectedEmployeesFromRead() {
  return Array.from(readContainer.querySelectorAll("li")).map(li => li.textContent.trim());
}

// Builds dropdowns in Edit form using provided employee list
function buildDropdowns(container, employees) {
  container.innerHTML = ""; // Clear existing
  if (!employees.length) {
    addDropdown(container);
    return;
  }
  employees.forEach(emp => addDropdown(container, emp));
  updateDropdowns(container);
}

// Resets Edit form to initial empty state
function resetEditForm() {
  editContainer.innerHTML = "";
  addDropdown(editContainer);
}

// ===== Start =====
fetchEmployees(); // Load employees and initialize on page load
