import { addDropdown, getSelectedEmployees } from '../shared.js';

const container = document.getElementById("create-dropdown-container");
const addBtn = document.getElementById("createAddDropdown");
const form = document.getElementById("createForm");

addDropdown(container);

addBtn.addEventListener("click", () => addDropdown(container));

form.addEventListener("submit", e => {
  e.preventDefault();

  const selected = getSelectedEmployees(container);
  if (selected.length === 0) {
    alert("Please select at least one employee.");
    return;
  }

  // Gather all other form data
  const formData = {
    official: form.official.checked,
    date: form.date.value,
    destination: form.destination.value,
    purpose: form.purpose.value,
    inclDate: form.inclDate.value,
    timeDeparture: form.timeDeparture.value,
    timeArrival: form.timeArrival.value,
    selectedEmployees: selected
  };

  localStorage.setItem("formData", JSON.stringify(formData));
  window.location.href = "../read/index.html";
});
