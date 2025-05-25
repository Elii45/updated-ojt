let employeesList = [];

async function fetchEmployees() {
  try {
    const response = await fetch("getEmployees.php");
    if (!response.ok) throw new Error("Network error");
    const data = await response.json();

    if (data.error) {
      alert("Error: " + data.error);
      return;
    }

    employeesList = data;
    initializeForms();
  } catch (error) {
    alert("Failed to fetch employees: " + error.message);
  }
}

const createContainer = document.getElementById("create-dropdown-container");
const createAddBtn = document.getElementById("createAddDropdown");
const createForm = document.getElementById("createForm");

const readContainer = document.getElementById("readContainer");
const editFromReadBtn = document.getElementById("editFromRead");

const editContainer = document.getElementById("edit-dropdown-container");
const editAddBtn = document.getElementById("editAddDropdown");
const editForm = document.getElementById("editForm");

function initializeForms() {
  addDropdown(createContainer);
  addDropdown(editContainer);
}

createAddBtn.addEventListener("click", () => addDropdown(createContainer));

createForm.addEventListener("submit", e => {
  e.preventDefault();
  const selectedEmployees = getSelectedEmployees(createContainer);
  if (!selectedEmployees.length) {
    alert("Select at least one employee.");
    return;
  }
  updateReadForm(selectedEmployees);
  resetEditForm();
});

editFromReadBtn.addEventListener("click", () => {
  const selected = getSelectedEmployeesFromRead();
  buildDropdowns(editContainer, selected);
});

editAddBtn.addEventListener("click", () => addDropdown(editContainer));

editForm.addEventListener("submit", e => {
  e.preventDefault();
  const selected = getSelectedEmployees(editContainer);
  if (!selected.length) {
    alert("Select at least one employee.");
    return;
  }
  updateReadForm(selected);
});

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

  if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
    select.value = selectedValue;
  } else {
    select.value = "";
  }
}

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

  handleSelection(container);
}

function getSelectedEmployees(container) {
  return Array.from(container.querySelectorAll(".request"))
    .map(select => select.value)
    .filter(val => val !== "");
}

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

function getSelectedEmployeesFromRead() {
  return Array.from(readContainer.querySelectorAll("li")).map(li => li.textContent.trim());
}

function buildDropdowns(container, employees) {
  container.innerHTML = "";
  if (!employees.length) {
    addDropdown(container);
    return;
  }
  employees.forEach(emp => addDropdown(container, emp));
  updateDropdowns(container);
}

function resetEditForm() {
  editContainer.innerHTML = "";
  addDropdown(editContainer);
}

// Start fetching
fetchEmployees();
