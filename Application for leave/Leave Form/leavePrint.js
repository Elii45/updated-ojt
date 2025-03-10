/* If kasama instruction
function printLeaveForm() {
    fetch("../instruction/instruction.html") // Load Instruction.html dynamically
        .then(response => response.text())
        .then(data => {
            document.getElementById("instructionPage").innerHTML = data;

            // Apply print styles
            let leavePrintStyle = document.createElement("link");
            leavePrintStyle.rel = "stylesheet";
            leavePrintStyle.href = "leavePrint.css";

            let instructionPrintStyle = document.createElement("link");
            instructionPrintStyle.rel = "stylesheet";
            instructionPrintStyle.href = "../instruction/instructionPrint.css"; // Adjust path

            document.head.appendChild(leavePrintStyle);
            document.head.appendChild(instructionPrintStyle);

            // Delay printing to ensure content is loaded
            setTimeout(() => {
                window.print();
                
                // Remove styles after printing
                document.head.removeChild(leavePrintStyle);
                document.head.removeChild(instructionPrintStyle);
            }, 1000);
        })
        .catch(error => console.error("Error loading Instruction.html:", error));
}
*/
function printLeaveForm() {
    // Create a <link> element for leavePrint.css
    let printStyle = document.createElement("link");
    printStyle.rel = "stylesheet";
    printStyle.href = "leavePrint.css";
    printStyle.media = "all"; // Apply styles immediately
    document.head.appendChild(printStyle);

    // Print the page
    window.print();

    // Remove the print style after printing
    setTimeout(() => {
        document.head.removeChild(printStyle);
    }, 1000);
}
