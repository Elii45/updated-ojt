function printPage() {
    document.querySelectorAll('input[type="date"]').forEach((input) => {
        if (input.value) {
            const selectedDate = new Date(input.value);
            const options = { month: "long", day: "2-digit", year: "numeric" };
            input.setAttribute("data-print-value", selectedDate.toLocaleDateString("en-US", options));
        }
    });

    window.print();
}

function fetchInventoryData() {
    fetch('inventory.php') // Ensure this fetches data correctly
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById("inventoryTableBody");
            tableBody.innerHTML = ""; // Clear existing rows

            data.forEach((item, index) => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${index + 1}</td> <!-- Sequential numbering (Blg.) -->
                    <td>${item.requested_by}</td>
                    <td>${item.date}</td>
                    <td>${item.inclusive_dates}</td>
                    <td>${item.hrmo_action}</td>
                    <td>${item.remarks}</td>
                `;

                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error("Error fetching inventory data:", error));
}

// Ensure data is fetched when the page loads
window.onload = fetchInventoryData;

function applyPagination() {
    const rows = document.querySelectorAll("#inventoryTableBody tr");
    rows.forEach((row, index) => {
        if ((index + 1) % 10 === 0) {
            const pageBreak = document.createElement("div");
            pageBreak.classList.add("page-break");
            row.parentNode.insertBefore(pageBreak, row.nextSibling);
        }
    });

    // Ensure footer appears only on the last page
    const footer = document.querySelector(".footer");
    if (rows.length > 10) {
        footer.style.display = "block";
    } else {
        footer.style.display = "none";
    }
}

// Run pagination after fetching data
window.onload = function () {
    fetchInventoryData().then(() => applyPagination());
};
