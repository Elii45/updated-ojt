// ===== Global Data =====
let employeesList = [];

// ===== Fetch Employees =====
async function fetchEmployees() {
  try {
    const response = await fetch("./getEmployees.php");
    if (!response.ok) throw new Error("Network error");
    const data = await response.json();

    if (data.error) {
      alert("Error: " + data.error);
      return;
    }

    employeesList = data; // Save fetched employees
    initializeForms(); // Initialize forms that are present on the page
  } catch (error) {
    alert("Failed to fetch employees: " + error.message);
  }
}

// ===== DOM Elements =====
// Use optional chaining to avoid errors if elements don't exist
const createContainer = document.getElementById("create-dropdown-container");
const createAddBtn = document.getElementById("createAddDropdown");
const createForm = document.getElementById("createForm");

const readContainer = document.getElementById("readContainer");
const editFromReadBtn = document.getElementById("editFromRead");

const editContainer = document.getElementById("edit-dropdown-container");
const editAddBtn = document.getElementById("editAddDropdown");
const editForm = document.getElementById("editForm");

// ===== Initialize Forms =====
function initializeForms() {
  if (createContainer && createAddBtn && createForm) {
    addDropdown(createContainer);
    createAddBtn.addEventListener("click", () => addDropdown(createContainer));
    createForm.addEventListener("submit", handleCreateSubmit);
  }

  if (readContainer && editFromReadBtn) {
    // On page load, load selected employees from localStorage if any
    const saved = localStorage.getItem("selectedEmployees");
    if (saved) {
      updateReadForm(JSON.parse(saved));
    }
    editFromReadBtn.addEventListener("click", () => {
      const selected = getSelectedEmployeesFromRead();
      localStorage.setItem("selectedEmployees", JSON.stringify(selected));
      // Navigate to edit page with data saved in localStorage
      window.location.href = "edit.html";
    });
  }

  if (editContainer && editAddBtn && editForm) {
    // On page load, populate edit form from localStorage if exists
    const saved = localStorage.getItem("selectedEmployees");
    if (saved) {
      buildDropdowns(editContainer, JSON.parse(saved));
    } else {
      addDropdown(editContainer);
    }

    editAddBtn.addEventListener("click", () => addDropdown(editContainer));
    editForm.addEventListener("submit", handleEditSubmit);
  }
}

// ===== Handlers =====

function handleCreateSubmit(e) {
  e.preventDefault();
  const selectedEmployees = getSelectedEmployees(createContainer);
  if (!selectedEmployees.length) {
    alert("Select at least one employee.");
    return;
  }
  // Save selection in localStorage
  localStorage.setItem("selectedEmployees", JSON.stringify(selectedEmployees));
  // Redirect to read view
  window.location.href = "read.html";
}

function handleEditSubmit(e) {
  e.preventDefault();
  const selected = getSelectedEmployees(editContainer);
  if (!selected.length) {
    alert("Select at least one employee.");
    return;
  }
  // Update localStorage with new selection
  localStorage.setItem("selectedEmployees", JSON.stringify(selected));
  // Redirect to read view
  window.location.href = "read.html";
}

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

  if (!(isFirst && preselect === "PITO Office")) {
    group.appendChild(removeBtn);
  }

  container.appendChild(group);
  select.addEventListener("change", () => updateDropdowns(container));
  updateDropdowns(container);
}

// Populates a <select> element with available employee options
function populateDropdown(select, container, isFirst, selectedValue) {
  const selected = getSelectedEmployees(container);
  select.innerHTML = `<option value="" disabled>Select Employee</option>`;

  if (isFirst) {
    select.innerHTML += `<option value="PITO Office">PITO Office</option>`;
  }

  employeesList.forEach((emp) => {
    if (!selected.includes(emp.name) || emp.name === selectedValue) {
      const option = document.createElement("option");
      option.value = emp.name;
      option.textContent = emp.name;
      select.appendChild(option);
    }
  });

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

    employeesList.forEach((emp) => {
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

  handleSelection(container);
}

// Returns array of selected employee names from a container
function getSelectedEmployees(container) {
  return Array.from(container.querySelectorAll(".request"))
    .map((select) => select.value)
    .filter((val) => val !== "");
}

// Handles logic when "PITO Office" is selected
function handleSelection(container) {
  const selects = container.querySelectorAll(".request");
  const addBtn = container.parentElement.querySelector(".addButton");

  if (selects.length > 0 && selects[0].value === "PITO Office") {
    addBtn.disabled = true;
    selects.forEach((select, i) => {
      if (i > 0) select.closest(".request-group").remove();
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
  selected.forEach((name) => {
    html += `<li>${name}</li>`;
  });
  html += "</ul>";

  readContainer.innerHTML = html;
  editFromReadBtn.disabled = false;
}

// Retrieves selected employees from Read view (localStorage copy)
function getSelectedEmployeesFromRead() {
  const saved = localStorage.getItem("selectedEmployees");
  return saved ? JSON.parse(saved) : [];
}

// Builds dropdowns in Edit form from saved data
function buildDropdowns(container, selected) {
  container.innerHTML = "";
  selected.forEach((name, idx) => {
    addDropdown(container, name);
  });
}

// ===== Start =====
fetchEmployees();
