function handleSubmit(event) {
    event.preventDefault()
  
    // Get form data
    const form = document.getElementById("locatorForm")
    const formData = new FormData(form)
  
    // Create URL parameters
    const params = new URLSearchParams()
  
    // Add form fields to URL parameters
    params.append("official", document.getElementById("official").checked ? "yes" : "no")
    params.append("date", document.getElementById("date").value)
    params.append("destination", document.getElementById("destination").value)
    params.append("purpose", document.getElementById("purpose").value)
    params.append("inclDate", document.getElementById("inclDate").value)
    params.append("timeDeparture", document.getElementById("timeDeparture").value)
    params.append("timeArrival", document.getElementById("timeArrival").value)
    params.append("request", document.getElementById("request").value)
  
    // Show alert
    alert("Locator Slip Submitted Successfully!")
  
    // Redirect to print page with form data
    window.location.href = `locatorSlipPrint.html?${params.toString()}`
  
    return false
  }
  
  