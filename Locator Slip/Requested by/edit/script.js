import { addDropdown, getSelectedEmployees } from '../shared.js';

const container = document.getElementById("edit-dropdown-container");
const addBtn = document.getElementById("editAddDropdown");
const form = document.getElementById("editForm");

// Initialize flatpickr on inclDate input
const inclDateInput = form.inclDate;
flatpickr(inclDateInput, {
  mode: "multiple",
  dateFormat: "Y-m-d",
  altInput: true,
  altFormat: "F j, Y",
});

// Load saved form data from localStorage
const savedData = JSON.parse(localStorage.getItem("formData") || "null");

if (savedData) {
  // Pre-fill inputs
  form.official.checked = savedData.official || false;
  form.date.value = savedData.date || "";
  form.destination.value = savedData.destination || "";
  form.purpose.value = savedData.purpose || "";
  form.timeDeparture.value = savedData.timeDeparture || "";
  form.timeArrival.value = savedData.timeArrival || "";

  // Pre-fill flatpickr with inclusive dates
  if (savedData.inclDate) {
    const dates = savedData.inclDate.split(",").map(d => d.trim());
    inclDateInput._flatpickr.setDate(dates, true); // true for triggerChange
  }

  // Pre-fill employee dropdowns
  if (savedData.selectedEmployees && savedData.selectedEmployees.length > 0) {
    savedData.selectedEmployees.forEach(emp => addDropdown(container, emp));
  } else {
    addDropdown(container); // add one empty dropdown if none saved
  }
} else {
  // No saved data, add one empty dropdown
  addDropdown(container);
}

// Add employee dropdown on button click
addBtn.addEventListener("click", () => addDropdown(container));

// On form submit
form.addEventListener("submit", (e) => {
  e.preventDefault();

  const selectedEmployees = getSelectedEmployees(container);
  if (selectedEmployees.length === 0) {
    alert("Please select at least one employee.");
    return;
  }

  // Gather form data
  const formData = {
    official: form.official.checked,
    date: form.date.value,
    destination: form.destination.value.trim(),
    purpose: form.purpose.value.trim(),
    inclDate: form.inclDate.value,
    timeDeparture: form.timeDeparture.value,
    timeArrival: form.timeArrival.value,
    selectedEmployees,
  };

  localStorage.setItem("formData", JSON.stringify(formData));

  // Redirect to read page
  window.location.href = "../read/index.html";
});
