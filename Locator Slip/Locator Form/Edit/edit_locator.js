document.addEventListener("DOMContentLoaded", () => {
  populateFormFromURL();
  initializeInclusiveDates();

  setupEventListeners();
});

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

  const inclDateStr = params.get("inclDate") || "";
  initializeInclusiveDates(inclDateStr);

  const timeDepRaw = params.get("timeDeparture") || "";
  const timeArrRaw = params.get("timeArrival") || "";

  const timeDep = convertTo24Hour(timeDepRaw);
  const timeArr = convertTo24Hour(timeArrRaw);

  document.getElementById("timeDeparture").value = timeDep;
  document.getElementById("timeArrival").value = timeArr;
}

function initializeInclusiveDates(inclDateStr = "") {
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
  // Currently no dynamic dropdowns, so no event listeners here.
}
