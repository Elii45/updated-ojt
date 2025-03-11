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
