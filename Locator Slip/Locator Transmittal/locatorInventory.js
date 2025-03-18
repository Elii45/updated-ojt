function printPage() {
    // Format dates for printing
    document.querySelectorAll('input[type="date"]').forEach((input) => {
      if (input.value) {
        const selectedDate = new Date(input.value)
        const options = { month: "long", day: "2-digit", year: "numeric" }
        input.setAttribute("data-print-value", selectedDate.toLocaleDateString("en-US", options))
      }
    })
  
    // Apply pagination before printing
    applyPrintPagination()
  
    window.print()
  
    // Remove pagination elements after printing
    removePrintPagination()
  }
  
  function fetchInventoryData() {
    return fetch("inventory.php")
      .then((response) => response.json())
      .then((data) => {
        const tableBody = document.getElementById("inventoryTableBody")
        tableBody.innerHTML = ""
  
        data.forEach((item, index) => {
          const row = document.createElement("tr")
  
          row.innerHTML = `
                      <td>${index + 1}</td> <!-- Sequential numbering (Blg.) -->
                      <td>${item.requested_by}</td>
                      <td>${item.date}</td>
                      <td>${item.inclusive_dates}</td>
                      <td>${item.hrmo_action}</td>
                      <td>${item.remarks}</td>
                  `
  
          tableBody.appendChild(row)
        })
  
        return data
      })
      .catch((error) => {
        console.error("Error fetching inventory data:", error)
        return []
      })
  }
  
  function applyPrintPagination() {
    // Store original footer reference
    const originalFooter = document.querySelector(".footer")
  
    // Remove any previously added elements
    document.querySelectorAll(".print-page-wrapper").forEach((el) => el.remove())
  
    const tableBody = document.getElementById("inventoryTableBody")
    const rows = tableBody.querySelectorAll("tr")
    const totalRows = rows.length
    const rowsPerPage = 10
  
    if (totalRows > 0) {
      const pagesNeeded = Math.ceil(totalRows / rowsPerPage)
      const originalTable = document.getElementById("inventoryTable")
      const container = document.querySelector(".container")
      const body = document.body
  
      // Clone all rows first
      const allRows = Array.from(rows).map((row) => row.cloneNode(true))
  
      // Clear original table
      tableBody.innerHTML = ""
  
      // First page - add exactly 10 rows or all rows if less than 10
      for (let i = 0; i < Math.min(rowsPerPage, totalRows); i++) {
        tableBody.appendChild(allRows[i].cloneNode(true))
      }
  
      // Create additional pages if needed
      for (let page = 1; page < pagesNeeded; page++) {
        const pageWrapper = document.createElement("div")
        pageWrapper.className = "print-page-wrapper"
  
        // Clone all necessary elements
        const headerClone = document.querySelector(".headerDetails").cloneNode(true)
        const containerClone = document.createElement("div")
        containerClone.className = "container print-container"
  
        const upperContentClone = document.querySelector(".upperContent").cloneNode(true)
        const tableClone = originalTable.cloneNode(true)
        const lowerContentClone = document.querySelector(".lowerContent").cloneNode(true)
        const footerClone = originalFooter.cloneNode(true)
  
        // Set up table content - exactly 10 rows per page
        const cloneTbody = tableClone.querySelector("tbody")
        cloneTbody.innerHTML = ""
  
        const startRow = page * rowsPerPage
        const endRow = Math.min(startRow + rowsPerPage, totalRows)
  
        for (let i = startRow; i < endRow; i++) {
          cloneTbody.appendChild(allRows[i].cloneNode(true))
        }
  
        // If this page has less than 10 rows, add empty rows to maintain spacing
        const emptyRowsNeeded = rowsPerPage - (endRow - startRow)
        for (let i = 0; i < emptyRowsNeeded; i++) {
          const emptyRow = document.createElement("tr")
          emptyRow.innerHTML = "<td>&nbsp;</td>".repeat(6) // 6 columns
          cloneTbody.appendChild(emptyRow)
        }
  
        // Append all elements to the container clone
        containerClone.appendChild(upperContentClone)
        containerClone.appendChild(tableClone)
        containerClone.appendChild(lowerContentClone)
  
        // Append header and container to the page wrapper
        pageWrapper.appendChild(headerClone)
        pageWrapper.appendChild(containerClone)
        pageWrapper.appendChild(footerClone)
  
        // Append the entire page wrapper to the body, outside the original container
        body.appendChild(pageWrapper)
      }
    }
  }
  
  function removePrintPagination() {
    // Remove all print-specific elements
    document.querySelectorAll(".print-page-wrapper").forEach((wrapper) => {
      wrapper.remove()
    })
  
    // Refresh the data to restore the original table
    fetchInventoryData()
  }
  
  // Ensure data is fetched when the page loads
  window.onload = () => {
    fetchInventoryData()
  }
  
  