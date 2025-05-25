let employeesList = [];

document.addEventListener("DOMContentLoaded", async () => {
  await fetchEmployees();

  initializeRequestedByDropdowns();

  populateFormFromURL();

  setupEventListeners();

  updateDropdowns();
});

/** -------------------------
 * Employee Dropdown Management
 * ------------------------- */

async function fetchEmployees() {
  try {
    const response = await fetch("../Backend/getEmployees.php");
    const data = await response.json();
    if (data.error) {
      console.error("Error:", data.error);
      employeesList = [];
      return;
    }
    employeesList = data; // array of {name: "Employee Name"}
  } catch (error) {
    console.error("Error fetching employee names:", error);
    employeesList = [];
  }
}

function getPreselectedEmployees() {
  const urlParams = new URLSearchParams(window.location.search);
  const requested = urlParams.get("requested");
  if (!requested) return [];
  return requested.split(",").map(name => decodeURIComponent(name.trim())).filter(Boolean);
}

function addDropdown(defaultValue = "", isFirstDropdown = false) {
  const container = document.getElementById("dropdown-container");

  const newGroup = document.createElement("div");
  newGroup.classList.add("request-group");

  const select = document.createElement("select");
  select.name = "request[]";
  select.required = true;
  select.classList.add("request");

  populateDropdown(select, isFirstDropdown, defaultValue);

  const removeBtn = document.createElement("button");
  removeBtn.textContent = "Remove";
  removeBtn.type = "button";
  removeBtn.classList.add("remove-btn");
  removeBtn.onclick = () => {
    newGroup.remove();
    updateDropdowns();
  };

  newGroup.appendChild(select);
  newGroup.appendChild(removeBtn);
  container.appendChild(newGroup);

  select.addEventListener("change", () => {
    updateDropdowns();
  });

  updateDropdowns();
}

function populateDropdown(selectElement, isFirst = false, defaultValue = "") {
  // Get selected values from all dropdowns except this one (to allow defaultValue)
  const selectedValues = getSelectedEmployees().filter(val => val !== defaultValue);

  selectElement.innerHTML = `<option value="" disabled>Select Employee</option>`;

  if (isFirst) {
    selectElement.innerHTML += `<option value="PITO Office">PITO Office</option>`;
  }

  employeesList.forEach(employee => {
    if (!selectedValues.includes(employee.name) || employee.name === defaultValue) {
      const option = document.createElement("option");
      option.value = employee.name;
      option.textContent = employee.name;
      selectElement.appendChild(option);
    }
  });

  if (defaultValue) {
    selectElement.value = defaultValue;
  } else {
    selectElement.value = "";
  }
}

function getSelectedEmployees() {
  return Array.from(document.querySelectorAll(".request"))
    .map(select => select.value)
    .filter(val => val !== "");
}

function updateDropdowns() {
  const selectedValues = getSelectedEmployees();

  const dropdowns = document.querySelectorAll(".request");
  dropdowns.forEach((select, index) => {
    const previousValue = select.value;

    // Available options exclude other selected values except this one
    const availableOptions = employeesList.filter(emp =>
      !selectedValues.includes(emp.name) || emp.name === previousValue
    );

    select.innerHTML = `<option value="" disabled ${previousValue ? "" : "selected"}>Select Employee</option>`;

    if (index === 0) {
      select.innerHTML += `<option value="PITO Office">PITO Office</option>`;
    }

    availableOptions.forEach(employee => {
      const option = document.createElement("option");
      option.value = employee.name;
      option.textContent = employee.name;
      select.appendChild(option);
    });

    if (previousValue && select.querySelector(`option[value="${previousValue}"]`)) {
      select.value = previousValue;
    } else {
      select.value = "";
    }
  });

  handleSelection();
  toggleRemoveButtons();
}

function handleSelection() {
  const dropdowns = document.querySelectorAll(".request");
  const addButton = document.getElementById("addDropdown");

  if (dropdowns.length > 0 && dropdowns[0].value === "PITO Office") {
    addButton.disabled = true;
    // Remove all except first dropdown
    dropdowns.forEach((select, i) => {
      if (i > 0) select.closest(".request-group").remove();
    });
  } else {
    addButton.disabled = false;
  }
}

function toggleRemoveButtons() {
  const container = document.getElementById("dropdown-container");
  const groups = container.querySelectorAll(".request-group");
  groups.forEach(group => {
    const removeBtn = group.querySelector(".remove-btn");
    removeBtn.disabled = groups.length === 1;
  });
}

function initializeRequestedByDropdowns() {
  const preselected = getPreselectedEmployees();

  if (preselected.length > 0) {
    preselected.forEach((name, index) => {
      addDropdown(name, index === 0);
    });
  } else {
    addDropdown("", true);
  }
}

/** -------------------------
 * Date and Time Utilities
 * ------------------------- */

function convertTo24Hour(timeStr) {
  if (!timeStr) return "";
  if (/^\d{2}:\d{2}$/.test(timeStr)) {
    return timeStr;
  }
  const m = timeStr.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
  if (!m) return "";
  let hour = parseInt(m[1], 10);
  const minutes = m[2];
  const ampm = m[3].toUpperCase();
  if (ampm === "PM" && hour !== 12) hour += 12;
  if (ampm === "AM" && hour === 12) hour = 0;
  return (hour < 10 ? "0" : "") + hour + ":" + minutes;
}

/** -------------------------
 * Form Data Population
 * ------------------------- */

function populateFormFromURL() {
  const params = new URLSearchParams(window.location.search);

  document.getElementById("official").checked = params.get("official") === "yes";
  document.getElementById("date").value = params.get("date") || "";
  document.getElementById("destination").value = params.get("destination") || "";
  document.getElementById("purpose").value = params.get("purpose") || "";

  initializeInclusiveDates(params.get("inclDate") || "");

  const timeDepRaw = params.get("timeDeparture") || "";
  const timeArrRaw = params.get("timeArrival") || "";

  const timeDep = convertTo24Hour(timeDepRaw);
  const timeArr = convertTo24Hour(timeArrRaw);

  document.getElementById("timeDeparture").value = timeDep;
  document.getElementById("timeArrival").value = timeArr;
}

function initializeInclusiveDates(inclDateStr) {
  const inclDatePicker = flatpickr("#inclDate", {
    mode: "multiple",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "F j, Y",
  });

  if (inclDateStr) {
    const datesArray = inclDateStr.split(",").map(d => d.trim()).filter(Boolean);
    inclDatePicker.setDate(datesArray, true);
  }
}

/** -------------------------
 * Event Listeners Setup
 * ------------------------- */

function setupEventListeners() {
  document.getElementById("addDropdown").addEventListener("click", () => {
    addDropdown();
  });
}
