function printPage() {
    const dateInput = document.querySelector('input[type="date"]').value;
    const dateLabel = document.querySelector('.date-group label');
    if (dateInput) {
        dateLabel.innerHTML = `Date: ${dateInput}`;
    }
    window.print();
}

function fetchInventoryData() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'leaveInventory.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            const inventoryData = JSON.parse(xhr.responseText);
            const tableBody = document.getElementById('inventoryTableBody');
            tableBody.innerHTML = '';

            if (inventoryData.length > 0) {
                inventoryData.forEach((row, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${row.first_name} ${row.middle_name} ${row.last_name}</td>
                        <td>${row.filing_date}</td>
                        <td>${row.inclusive_dates}</td>
                        <td></td>
                        <td></td>
                    `;
                    tableBody.appendChild(tr);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6">No data found</td></tr>';
            }
        } else {
            console.error('Error fetching data: ' + xhr.status);
        }
    };
    xhr.send();
}