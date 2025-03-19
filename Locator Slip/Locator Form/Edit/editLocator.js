document.addEventListener("DOMContentLoaded", () => {
    // Ensure we fetch employees first
    if (typeof window.fetchEmployees === "function") {
      window.fetchEmployees()
    } else {
      console.error("fetchEmployees function not available")
    }
  
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search)
  
    // Store original values for identification in the update query
    const originalRequest = urlParams.get("request")
    const originalDate = urlParams.get("date")
  
    document.getElementById("originalRequest").value = originalRequest
    document.getElementById("originalDate").value = originalDate
  
    // Set checkbox value
    const officialValue = urlParams.get("official")
    const officialElement = document.getElementById("official")
    if (officialElement) {
      officialElement.checked = officialValue === "1" || officialValue?.toLowerCase() === "yes"
    }
  
    // Format date for the date input (YYYY-MM-DD)
    function formatDateForInput(dateString) {
      if (!dateString) return ""
      try {
        const parts = dateString.split(" ")
        const month = new Date(Date.parse(parts[0] + " 1, 2000")).getMonth() + 1
        const day = Number.parseInt(parts[1].replace(",", ""))
        const year = Number.parseInt(parts[2])
  
        return `${year}-${month.toString().padStart(2, "0")}-${day.toString().padStart(2, "0")}`
      } catch (e) {
        console.error("Date formatting error:", e)
        return ""
      }
    }
  
    // Format time from "12:00 PM" to "12:00" for time input
    function formatTimeForInput(timeString) {
      if (!timeString) return ""
      try {
        const [time, period] = timeString.split(" ")
        const [hours, minutes] = time.split(":")
  
        let hour = Number.parseInt(hours)
        if (period === "PM" && hour < 12) hour += 12
        if (period === "AM" && hour === 12) hour = 0
  
        return `${hour.toString().padStart(2, "0")}:${minutes}`
      } catch (e) {
        console.error("Time formatting error:", e)
        return timeString
      }
    }
  
    // Set text field values
    const dateValue = urlParams.get("date")
    if (dateValue) {
      document.getElementById("date").value = formatDateForInput(dateValue)
    }
  
    document.getElementById("destination").value = urlParams.get("destination") || ""
    document.getElementById("purpose").value = urlParams.get("purpose") || ""
    document.getElementById("inclDate").value = urlParams.get("inclDate") || ""
  
    const timeDeparture = urlParams.get("timeDeparture")
    if (timeDeparture) {
      document.getElementById("timeDeparture").value = formatTimeForInput(timeDeparture)
    }
  
    const timeArrival = urlParams.get("timeArrival")
    if (timeArrival) {
      document.getElementById("timeArrival").value = formatTimeForInput(timeArrival)
    }
  
    // Handle requesters (may be multiple)
    const requesters = urlParams.get("request")
    if (requesters) {
      const requesterList = requesters.split(", ")
  
      // Function to set up requesters with available data
      function setupRequesters() {
        // Remove the first default dropdown
        const container = document.getElementById("dropdown-container")
        container.innerHTML = ""
  
        // Add a dropdown for each requester
        requesterList.forEach((requester, index) => {
          if (index === 0) {
            // First dropdown
            const newGroup = document.createElement("div")
            newGroup.classList.add("request-group")
  
            const select = document.createElement("select")
            select.name = "request[]"
            select.required = true
            select.id = "request" // Keep the first one with the original ID
            select.classList.add("request")
  
            // Add options
            const defaultOption = document.createElement("option")
            defaultOption.value = ""
            defaultOption.textContent = "Select Employee"
            defaultOption.disabled = true
            select.appendChild(defaultOption)
  
            // Add PITO Office option to first dropdown
            const pitoOption = document.createElement("option")
            pitoOption.value = "PITO Office"
            pitoOption.textContent = "PITO Office"
            select.appendChild(pitoOption)
  
            // Add all employees if available
            if (window.employeesList && window.employeesList.length > 0) {
              window.employeesList.forEach((employee) => {
                const option = document.createElement("option")
                option.value = employee.name
                option.textContent = employee.name
                select.appendChild(option)
              })
            } else {
              // Add the current requester as an option if no employee list
              const option = document.createElement("option")
              option.value = requester
              option.textContent = requester
              select.appendChild(option)
            }
  
            // Set the selected value
            select.value = requester
  
            newGroup.appendChild(select)
            container.appendChild(newGroup)
  
            // Attach event listener if available
            if (typeof window.handleSelection === "function") {
              select.addEventListener("change", window.handleSelection)
            }
          } else {
            // Additional dropdowns
            const newGroup = document.createElement("div")
            newGroup.classList.add("request-group")
  
            const select = document.createElement("select")
            select.name = "request[]"
            select.required = true
            select.classList.add("request")
  
            // Add options
            const defaultOption = document.createElement("option")
            defaultOption.value = ""
            defaultOption.textContent = "Select Employee"
            defaultOption.disabled = true
            select.appendChild(defaultOption)
  
            // Add all employees if available
            if (window.employeesList && window.employeesList.length > 0) {
              window.employeesList.forEach((employee) => {
                const option = document.createElement("option")
                option.value = employee.name
                option.textContent = employee.name
                select.appendChild(option)
              })
            } else {
              // Add the current requester as an option if no employee list
              const option = document.createElement("option")
              option.value = requester
              option.textContent = requester
              select.appendChild(option)
            }
  
            // Set the selected value
            select.value = requester
  
            const removeBtn = document.createElement("button")
            removeBtn.textContent = "Remove"
            removeBtn.type = "button"
            removeBtn.classList.add("remove-btn")
            removeBtn.onclick = () => {
              newGroup.remove()
              if (typeof window.updateDropdowns === "function") {
                window.updateDropdowns()
              }
            }
  
            newGroup.appendChild(select)
            newGroup.appendChild(removeBtn)
            container.appendChild(newGroup)
  
            // Attach event listener if available
            if (typeof window.handleSelection === "function") {
              select.addEventListener("change", window.handleSelection)
            }
          }
        })
  
        // Disable add button if PITO Office is selected
        if (requesterList.length === 1 && requesterList[0] === "PITO Office") {
          const addButton = document.getElementById("addDropdown")
          if (addButton) {
            addButton.disabled = true
          }
        }
      }
  
      // Check if employee data is already loaded
      if (window.employeesList && window.employeesList.length > 0) {
        setupRequesters()
      } else {
        // Wait for employees to load with a timeout
        let attempts = 0
        const maxAttempts = 30 // Try for about 3 seconds
        const checkEmployeesLoaded = setInterval(() => {
          attempts++
          if (window.employeesList && window.employeesList.length > 0) {
            clearInterval(checkEmployeesLoaded)
            setupRequesters()
          }
          // Stop trying after max attempts
          if (attempts >= maxAttempts) {
            clearInterval(checkEmployeesLoaded)
            console.warn("Failed to load employee data after multiple attempts")
            // Set up with whatever data we have
            setupRequesters()
          }
        }, 100)
      }
    }
  })
  
  