// Get URL parameters
const urlParams = new URLSearchParams(window.location.search);

// Function to format dates
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Load and process personal details
fetch("../Leave Form/details/personalDetails.html")
    .then(response => response.text())
    .then(data => {
        let printData = data.replace(/<input[^>]*name="([^"]*)"[^>]*>/g, (match, name) => {
            const value = urlParams.get(name) || '';
            return `<span class="print-field" id="${name}">${value}</span>`;
        });

        // Replace select elements
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

// Load and process leave details
fetch("../Leave Form/details/leaveDetails.html")
    .then(response => response.text())
    .then(data => {
        let printData = data.replace(/<input[^>]*name="([^"]*)"[^>]*>/g, (match, name) => {
            const value = urlParams.get(name) || '';

            // Handle radio buttons properly
            if (match.includes('type="radio"')) {
                const matchValue = match.match(/value="([^"]*)"/);
                const inputValue = matchValue ? matchValue[1] : '';

                return urlParams.get(name) === inputValue 
                    ? `☑ ${match.split('>')[1]}`  // Checked
                    : `☐ ${match.split('>')[1]}`; // Unchecked
            }
            
            // Handle checkboxes
            if (match.includes('type="checkbox"')) {
                return urlParams.get(name) === 'on' || urlParams.get(name) === '1' ? '☑' : '☐';
            }

            return `<span class="print-field" id="${name}">${value}</span>`;
        });

        // Format dates properly
        const dateFields = ['inclDates'];
        dateFields.forEach(field => {
            const value = urlParams.get(field);
            if (value) {
                const formattedDate = formatDate(value);
                printData = printData.replace(new RegExp(`id="${field}">[^<]*<`, 'g'), `id="${field}">${formattedDate}<`);
            }
        });

        document.getElementById("leaveDetails").innerHTML = printData;
    });

// Load and process action details
fetch("../Leave Form/details/actionDetails.html")
    .then(response => response.text())
    .then(data => {
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
