document.addEventListener("DOMContentLoaded", () => {
    fetchEmployees()
  
    const addButton = document.getElementById("addDropdown")
    if (addButton) {
      addButton.addEventListener("click", () => {
        addDropdown()
      })
    }
  
    const firstDropdown = document.getElementById("request")
    if (firstDropdown) {
      attachChangeListener(firstDropdown)
    }
  })
  
  // Make these variables and functions available globally
  window.employeesList = []
  
  function fetchEmployees() {
    // Log the fetch attempt
    console.log("Attempting to fetch employees from ../Backend/getEmployees.php")
  
    // Try with different paths if needed
    fetch("../Backend/getEmployees.php")
      .then((response) => {
        console.log("Response status:", response.status)
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        console.log("Data received:", data)
        if (data.error) {
          console.error("Error in response:", data.error)
          alert("Error loading employee data: " + data.error)
          return
        }
        window.employeesList = data
        populateDropdown(document.getElementById("request"), true)
      })
      .catch((error) => {
        console.error("Error fetching employee names:", error)
  
        // Try alternative path as fallback
        console.log("Trying alternative path: ../../Backend/getEmployees.php")
        fetch("../../Backend/getEmployees.php")
          .then((response) => {
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`)
            }
            return response.json()
          })
          .then((data) => {
            console.log("Data received from alternative path:", data)
            if (data.error) {
              throw new Error(data.error)
            }
            window.employeesList = data
            populateDropdown(document.getElementById("request"), true)
          })
          .catch((fallbackError) => {
            console.error("Error with fallback path:", fallbackError)
            alert("Could not load employee data. Please check the console for details and ensure the server is running.")
          })
      })
  }
  
  function populateDropdown(selectElement, isFirst = false) {
    if (!selectElement) return
  
    const selectedValues = getSelectedEmployees()
  
    selectElement.innerHTML = `<option value="" disabled selected>Select Employee</option>`
  
    if (isFirst) {
      selectElement.innerHTML += `<option value="PITO Office">PITO Office</option>`
    }
  
    if (!window.employeesList || window.employeesList.length === 0) {
      console.warn("No employees data available to populate dropdown")
      return
    }
  
    window.employeesList.forEach((employee) => {
      if (!selectedValues.includes(employee.name)) {
        const option = document.createElement("option")
        option.value = employee.name
        option.textContent = employee.name
        selectElement.appendChild(option)
      }
    })
  
    attachChangeListener(selectElement)
  }
  
  function addDropdown() {
    const container = document.getElementById("dropdown-container")
    if (!container) return
  
    const newGroup = document.createElement("div")
    newGroup.classList.add("request-group")
  
    const select = document.createElement("select")
    select.name = "request[]" // Ensures the backend receives an array
    select.required = true
    select.classList.add("request")
  
    populateDropdown(select, false)
  
    const removeBtn = document.createElement("button")
    removeBtn.textContent = "Remove"
    removeBtn.type = "button"
    removeBtn.classList.add("remove-btn")
    removeBtn.onclick = () => {
      newGroup.remove()
      updateDropdowns()
    }
  
    newGroup.appendChild(select)
    newGroup.appendChild(removeBtn)
    container.appendChild(newGroup)
  
    attachChangeListener(select)
  }
  
  function updateDropdowns() {
    document.querySelectorAll(".request").forEach((select, index) => {
      const previousValue = select.value
      populateDropdown(select, index === 0) // Allow "PITO Office" only in the first dropdown
      select.value = previousValue
    })
    handleSelection()
  }
  
  function getSelectedEmployees() {
    return Array.from(document.querySelectorAll(".request"))
      .map((select) => select.value)
      .filter((value) => value !== "")
  }
  
  function attachChangeListener(selectElement) {
    selectElement.addEventListener("change", handleSelection)
  }
  
  function handleSelection() {
    const firstDropdown = document.getElementById("request")
    const addButton = document.getElementById("addDropdown")
    if (!firstDropdown || !addButton) return
  
    if (firstDropdown.value === "PITO Office") {
      addButton.disabled = true
      document.querySelectorAll(".request-group").forEach((group, index) => {
        if (index > 0) group.remove()
      })
    } else {
      addButton.disabled = false
    }
  }
  
  // Make functions available globally
  window.fetchEmployees = fetchEmployees
  window.populateDropdown = populateDropdown
  window.addDropdown = addDropdown
  window.updateDropdowns = updateDropdowns
  window.getSelectedEmployees = getSelectedEmployees
  window.attachChangeListener = attachChangeListener
  window.handleSelection = handleSelection
  
  