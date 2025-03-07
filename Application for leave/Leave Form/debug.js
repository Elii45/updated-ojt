// Add this script to your HTML to help debug the form submission
document.addEventListener("DOMContentLoaded", () => {
  // Log all form elements before submission
  const form = document.querySelector("form")
  if (form) {
    form.addEventListener("submit", (event) => {
      console.log("Form is being submitted")

      // Log all form elements
      const formData = new FormData(form)
      console.log("Form data:")
      for (const [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`)
      }

      // Check specifically for office data
      const officeDropdown = document.getElementById("officeDropdown")
      const officeInput = document.getElementById("officeInput")

      console.log("Office dropdown value:", officeDropdown ? officeDropdown.value : "not found")
      console.log("Office input value:", officeInput ? officeInput.value : "not found")
    })
  }
})

