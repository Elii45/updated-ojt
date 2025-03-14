// Get URL parameters
const urlParams = new URLSearchParams(window.location.search);

// Format date function
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Load the same section templates but with print modifications
fetch("../Leave Form/details/personalDetails.html")
    .then(response => response.text())
    .then(data => {
        let urlParams = new URLSearchParams(window.location.search);

        // Replace input elements with span elements for printing
        let printData = data.replace(/<input[^>]*name="([^"]*)"[^>]*>/g, (match, name) => {
            const value = urlParams.get(name) || '';
            return `<span class="print-field" id="${name}">${value}</span>`;
        });

        // Replace select elements with span elements
        printData = printData.replace(/<select[^>]*name="([^"]*)"[^>]*>.*?<\/select>/gs, (match, name) => {
            const value = urlParams.get(name) || '';
            return `<span class="print-field" id="${name}">${value}</span>`;
        });

        // Ensure office name is displayed instead of ID
        if (urlParams.has("officeName")) {
            printData = printData.replace(/<span class="print-field" id="office">.*?<\/span>/,
                `<span class="print-field" id="office">${urlParams.get("officeName")}</span>`);
        }

        document.getElementById("personalDetails").innerHTML = printData;
    });


fetch("../Leave Form/details/leaveDetails.html")
    .then(response => response.text())
    .then(data => {
        let printData = data.replace(/<input[^>]*name="([^"]*)"[^>]*>/g, (match, name) => {
            const value = urlParams.get(name) || '';

            // Check for radio buttons and checkboxes
            if (match.includes('type="radio"') || match.includes('type="checkbox"')) {
                const matchValue = match.match(/value="([^"]*)"/);
                const inputValue = matchValue ? matchValue[1] : '';

                // Special handling for section 6.B
                if (name === "detailType") {
                    return urlParams.get(name) === inputValue ? `☑ ${match.split('>')[1]}` : `☐ ${match.split('>')[1]}`;
                }

                // General fix for other checkboxes/radio buttons
                return urlParams.get(name) === inputValue ? '☑' : '☐';
            } else {
                return `<span class="print-field" id="${name}">${value}</span>`;
            }
        });

        // Format dates properly
        const dateFields = ['inclDates']; // Add other date fields if needed
        dateFields.forEach(field => {
            const value = urlParams.get(field);
            if (value) {
                const formattedDate = formatDate(value);
                printData = printData.replace(new RegExp(`id="${field}">[^<]*<`, 'g'), `id="${field}">${formattedDate}<`);
            }
        });

        document.getElementById("leaveDetails").innerHTML = printData;
    });


fetch("../Leave Form/details/actionDetails.html")
    .then(response => response.text())
    .then(data => {
        // Replace input elements with span elements for printing
        let printData = data.replace(/<input[^>]*name="([^"]*)"[^>]*>/g, (match, name) => {
            if (match.includes('type="radio"') || match.includes('type="checkbox"')) {
                const checked = urlParams.get(name) === 'on' || urlParams.get(name) === '1';
                return checked ? '☑' : '☐';
            } else {
                const value = urlParams.get(name) || '';
                return `<span class="print-field" id="${name}">${value}</span>`;
            }
        });

        document.getElementById("actionDetails").innerHTML = printData;
    });
