document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#inclDate", {
        mode: "multiple",
        dateFormat: "Y-m-d", // Use full date format to process ranges
        allowInput: false,
        onChange: function (selectedDates, dateStr, instance) {
            const formattedRanges = formatDateRanges(selectedDates);
            instance.input.value = formattedRanges;
        }
    });
});

// Function to group consecutive dates into ranges
function formatDateRanges(dates) {
    if (dates.length === 0) return "";
    
    // Sort dates in ascending order
    dates.sort((a, b) => a - b);
    
    let ranges = [];
    let start = dates[0];
    let end = dates[0];

    for (let i = 1; i < dates.length; i++) {
        let currentDate = dates[i];
        let prevDate = new Date(dates[i - 1]);
        prevDate.setDate(prevDate.getDate() + 1); // Next day of previous date

        if (currentDate.getTime() === prevDate.getTime()) {
            // Dates are consecutive, extend the range
            end = currentDate;
        } else {
            // Push the previous range and start a new one
            ranges.push(formatRange(start, end));
            start = end = currentDate;
        }
    }
    // Push the last range
    ranges.push(formatRange(start, end));

    return ranges.join(", ");
}

// Function to format a single range
function formatRange(start, end) {
    const options = { month: "short", day: "numeric" }; // "Mar 1"
    if (start.getTime() === end.getTime()) {
        return start.toLocaleDateString("en-US", options);
    } else {
        return `${start.toLocaleDateString("en-US", options)}-${end.toLocaleDateString("en-US", options)}`;
    }
}
