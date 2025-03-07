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
