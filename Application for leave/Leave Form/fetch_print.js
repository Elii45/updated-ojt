// Get URL parameters
const urlParams = new URLSearchParams(window.location.search);

// Function to format dates as "Month Day, Year" (e.g., "March 18, 2025")
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
        let printData = data;

        // Replace Name fields (Last, First, Middle)
        const nameFields = ["lastName", "firstName", "middleName"];
        nameFields.forEach(field => {
            const value = urlParams.get(field) || '';
            printData = printData.replace(
                new RegExp(`<input[^>]*name="${field}"[^>]*>`, "g"),
                `<span class="print-field-name" id="${field}">${value}</span>`
            );
        });

        // Replace Date of Filing, Position, and Salary fields
        const detailFields = ["filingDate", "position", "salary"];
        detailFields.forEach(field => {
            let value = urlParams.get(field) || '';
            
            // Format the filingDate field
            if (field === "filingDate") {
                value = formatDate(value);
            }

            printData = printData.replace(
                new RegExp(`<input[^>]*name="${field}"[^>]*>`, "g"),
                `<span class="print-field-date" id="${field}">${value}</span>`
            );
        });

        // Replace select elements (Office/Department)
        printData = printData.replace(
            /<select[^>]*name="office"[^>]*>.*?<\/select>/gs,
            `<span class="print-field-office" id="office">${urlParams.get("office") || ''}</span>`
        );

        // Ensure office name is displayed instead of ID if available
        if (urlParams.has("officeName")) {
            printData = printData.replace(
                /<span class="print-field-office" id="office">.*?<\/span>/,
                `<span class="print-field-office" id="office">${urlParams.get("officeName")}</span>`
            );
        }

        document.getElementById("personalDetails").innerHTML = printData;
    });

// Load and process leave details
fetch("../Leave Form/details/leaveDetails.html")
    .then(response => response.text())
    .then(data => {
        let printData = data.replace(/<input[^>]*name="([^"]*)"[^>]*>/g, (match, name) => {
            let value = urlParams.get(name) || '';

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

            function numberToWords(num) {
                const belowTwenty = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen"];
                const tens = ["", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];
            
                if (num < 20) return belowTwenty[num];  
                else if (num < 100) return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? "-" + belowTwenty[num % 10] : "");
                else return num; // Keeps numbers 100+ in digit form
            }
            
            // Replace input fields for specific leave details
            if (name === "leaveTypeOthers" || name === "workingDays") { 
                if (name === "workingDays") {
                    let wordForm = numberToWords(parseInt(value)); 
            
                    return `<span class="print-field-workingDays" id="${name}">(${value}) ${wordForm} working day${value > 1 ? 's' : ''}</span>`;
                }
                
                return `<span class="print-field-workingDays" id="${name}">${value}</span>`;
            }
            

            // Replace text input fields
            return `<span class="print-field-details" id="${name}">${value}</span>`;
        });

        // Format date fields properly
        const dateFields = ['inclDates'];
        dateFields.forEach(field => {
            let value = urlParams.get(field);
            if (value) {
                const formattedDate = formatDate(value);
                printData = printData.replace(
                    new RegExp(`id="${field}">[^<]*<`, 'g'),
                    `id="${field}">${formattedDate}<`
                );
            }
        });

        document.getElementById("leaveDetails").innerHTML = printData;
    });

// Load and process action details
fetch("../Leave Form/details/actionDetails.html")
    .then(response => response.text())
    .then(data => {
        let printData = data;

        // Replace As of Date and format it
        const asOfField = "asOf";
        let asOfValue = urlParams.get(asOfField) || '';
        if (asOfValue) {
            asOfValue = formatDate(asOfValue);
        }
        printData = printData.replace(
            new RegExp(`<input[^>]*name="${asOfField}"[^>]*>`, "g"),
            `<span class="print-field-actionDate" id="${asOfField}">${asOfValue}</span>`
        );

        // Replace fields for Total Earned, Less this Application, and Balance (Vacation and Sick Leave)
        const earnedFields = ["vacationTotalEarned", "sickTotalEarned", "vacationLessApplication", "sickLessApplication", "vacationBalance", "sickBalance"];
        earnedFields.forEach(field => {
            const value = urlParams.get(field) || '';
            printData = printData.replace(
                new RegExp(`<input[^>]*name="${field}"[^>]*>`, "g"),
                `<span class="print-field-actionTable" id="${field}">${value}</span>`
            );
        });

        // Replace radio buttons for Approval and Disapproval
        const approvalField = "approval";
        const disapprovalField = "disapproval";
        const approvalValue = urlParams.get(approvalField) === '1' ? '☑' : '☐';
        const disapprovalValue = urlParams.get(disapprovalField) === '1' ? '☑' : '☐';
        printData = printData.replace(
            new RegExp(`<input[^>]*name="${approvalField}"[^>]*>`, "g"),
            `<span class="print-field-actionRecommendation" id="${approvalField}">${approvalValue}</span>`
        );
        printData = printData.replace(
            new RegExp(`<input[^>]*name="${disapprovalField}"[^>]*>`, "g"),
            `<span class="print-field-actionDisapproval" id="${disapprovalField}">${disapprovalValue}</span>`
        );

        // Replace the disapproval detail input
        const disapprovalDetailField = "disapprovalDetail";
        const disapprovalDetailValue = urlParams.get(disapprovalDetailField) || '';
        printData = printData.replace(
            new RegExp(`<input[^>]*name="${disapprovalDetailField}"[^>]*>`, "g"),
            `<span class="print-field-actionDisapprovalReason" id="${disapprovalDetailField}">${disapprovalDetailValue}</span>`
        );

        // Replace Approved For and Disapproved Due To fields
        const approvedForFields = ["pay", "withoutPay", "othersApproved"];
        approvedForFields.forEach(field => {
            const value = urlParams.get(field) || '';
            printData = printData.replace(
                new RegExp(`<input[^>]*name="${field}"[^>]*>`, "g"),
                `<span class="print-field-actionApproved" id="${field}">${value}</span>`
            );
        });

        // Replace Disapproved Due To input field
        const disapprovedToField = "disapprovedTo";
        const disapprovedToValue = urlParams.get(disapprovedToField) || '';
        printData = printData.replace(
            new RegExp(`<input[^>]*name="${disapprovedToField}"[^>]*>`, "g"),
            `<span class="print-field-actionDisapproved" id="${disapprovedToField}">${disapprovedToValue}</span>`
        );

        document.getElementById("actionDetails").innerHTML = printData;
    });
