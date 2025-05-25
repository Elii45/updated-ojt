const editBtn = document.getElementById("editFromRead");

// Grab references to the spans/divs where we'll put data
const officialEl = document.getElementById("official");
const dateEl = document.getElementById("date");
const destinationEl = document.getElementById("destination");
const purposeEl = document.getElementById("purpose");
const inclDateEl = document.getElementById("inclDate");
const timeDepartureEl = document.getElementById("timeDeparture");
const timeArrivalEl = document.getElementById("timeArrival");
const employeesEl = document.getElementById("employees");

// Retrieve saved form data from localStorage
const formData = JSON.parse(localStorage.getItem("formData") || "null");

if (formData) {
  const {
    official,
    date,
    destination,
    purpose,
    inclDate,
    timeDeparture,
    timeArrival,
    selectedEmployees
  } = formData;

  officialEl.textContent = official ? "Yes" : "No";
  dateEl.textContent = date || "(not provided)";
  destinationEl.textContent = destination || "(not provided)";
  purposeEl.textContent = purpose || "(not provided)";
  inclDateEl.textContent = inclDate || "(not provided)";
  timeDepartureEl.textContent = timeDeparture || "(not provided)";
  timeArrivalEl.textContent = timeArrival || "(not provided)";

  // Clear previous employees (if any)
  employeesEl.innerHTML = "";

  if (selectedEmployees && selectedEmployees.length > 0) {
    selectedEmployees.forEach(emp => {
      const div = document.createElement("div");
      div.textContent = emp;
      div.classList.add("employee");
      if (emp === "PITO Office") {
        div.classList.add("important");
      }
      employeesEl.appendChild(div);
    });
  } else {
    const noEmpDiv = document.createElement("div");
    noEmpDiv.textContent = "(No employees selected)";
    noEmpDiv.classList.add("employee");
    employeesEl.appendChild(noEmpDiv);
  }

  editBtn.disabled = false;
} else {
  // If no data, clear all fields and disable button
  officialEl.textContent = "";
  dateEl.textContent = "";
  destinationEl.textContent = "";
  purposeEl.textContent = "";
  inclDateEl.textContent = "";
  timeDepartureEl.textContent = "";
  timeArrivalEl.textContent = "";
  employeesEl.innerHTML = "No data submitted yet.";
  editBtn.disabled = true;
}

editBtn.addEventListener("click", () => {
  window.location.href = "../edit/index.html";
});
