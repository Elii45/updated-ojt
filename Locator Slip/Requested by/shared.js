import { employeesList } from './employees.js';

export function addDropdown(container, preselectValue = "") {
  const newGroup = document.createElement("div");
  newGroup.classList.add("request-group");

  const select = document.createElement("select");
  select.name = "request[]";
  select.required = true;
  select.classList.add("request");

  const isFirst = container.querySelectorAll(".request").length === 0;
  populateDropdown(select, container, isFirst, preselectValue);

  const removeBtn = document.createElement("button");
  removeBtn.textContent = "Remove";
  removeBtn.type = "button";
  removeBtn.classList.add("remove-btn");
  removeBtn.onclick = () => {
    newGroup.remove();
    updateDropdowns(container);
  };

  newGroup.appendChild(select);
  if (!(isFirst && preselectValue === "PITO Office")) {
    newGroup.appendChild(removeBtn);
  }

  container.appendChild(newGroup);

  select.addEventListener("change", () => {
    updateDropdowns(container);
  });

  updateDropdowns(container);
}

export function populateDropdown(selectElement, container, isFirst = false, selectedValue = "") {
  const selectedValues = getSelectedEmployees(container);
  selectElement.innerHTML = `<option value="" disabled>Select Employee</option>`;
  if (isFirst) selectElement.innerHTML += `<option value="PITO Office">PITO Office</option>`;

  employeesList.forEach(employee => {
    if (!selectedValues.includes(employee.name) || employee.name === selectedValue) {
      const option = document.createElement("option");
      option.value = employee.name;
      option.textContent = employee.name;
      selectElement.appendChild(option);
    }
  });

  if (selectedValue && selectElement.querySelector(`option[value="${selectedValue}"]`)) {
    selectElement.value = selectedValue;
  } else {
    selectElement.value = "";
  }
}

export function updateDropdowns(container) {
  const selects = container.querySelectorAll(".request");
  const selectedValues = getSelectedEmployees(container);

  selects.forEach((select, index) => {
    const prevValue = select.value;
    const availableOptions = employeesList.filter(emp =>
      !selectedValues.includes(emp.name) || emp.name === prevValue
    );

    select.innerHTML = `<option value="" disabled>Select Employee</option>`;
    if (index === 0) {
      select.innerHTML += `<option value="PITO Office">PITO Office</option>`;
    }

    availableOptions.forEach(employee => {
      const option = document.createElement("option");
      option.value = employee.name;
      option.textContent = employee.name;
      select.appendChild(option);
    });

    if (prevValue && select.querySelector(`option[value="${prevValue}"]`)) {
      select.value = prevValue;
    } else {
      select.value = "";
    }
  });

  handleSelection(container);
}

export function getSelectedEmployees(container) {
  return Array.from(container.querySelectorAll(".request"))
    .map(select => select.value)
    .filter(value => value !== "");
}

export function handleSelection(container) {
  const dropdowns = container.querySelectorAll(".request");
  const addButton = container.parentElement.querySelector("button.addButton");

  if (dropdowns.length > 0 && dropdowns[0].value === "PITO Office") {
    addButton.disabled = true;
    dropdowns.forEach((select, index) => {
      if (index > 0) select.closest(".request-group").remove();
    });
  } else {
    addButton.disabled = false;
  }
}
