document.addEventListener("DOMContentLoaded", function () {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);

    // Debug - Log all parameters
    console.log("Received Parameters:", Object.fromEntries(urlParams.entries()));

    // Format a single date in "Month Day, Year" format
    function formatDate(dateString) {
        if (!dateString) return '';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
        } catch (e) {
            console.error("Date formatting error:", e);
            return dateString;
        }
    }

    // Format and group inclusive dates correctly
    function formatDateRanges(dateList) {
        if (!dateList.length) return '';

        // Convert to Date objects & sort
        let dates = dateList
            .map(d => new Date(d.trim())) // Trim spaces & convert to Date
            .filter(d => !isNaN(d)) // Remove invalid dates
            .sort((a, b) => a - b); // Sort in ascending order

        let ranges = [];
        let start = dates[0];
        let end = dates[0];

        for (let i = 1; i < dates.length; i++) {
            let currentDate = dates[i];
            let prevDate = new Date(end);
            prevDate.setDate(prevDate.getDate() + 1); // Check if it's consecutive

            if (currentDate.getTime() === prevDate.getTime()) {
                // Consecutive date, extend range
                end = currentDate;
            } else {
                // Save previous range & start a new one
                ranges.push(formatRange(start, end));
                start = end = currentDate;
            }
        }
        // Add last range
        ranges.push(formatRange(start, end));

        return ranges.join(", ");
    }

    // Format a date range (e.g., "Mar 3-4" or "Mar 6-7")
    function formatRange(start, end) {
        const options = { month: "short", day: "numeric" };
        if (start.getTime() === end.getTime()) {
            return start.toLocaleDateString("en-US", options);
        } else {
            return `${start.toLocaleDateString("en-US", options)}-${end.toLocaleDateString("en-US", { day: "numeric" })}`;
        }
    }

    // Set checkbox value
    const officialValue = urlParams.get('official');
    const officialElement = document.getElementById('official');
    if (officialElement) {
        officialElement.checked = (officialValue === "1" || officialValue?.toLowerCase() === "yes");
    }

    // Set text field values
    const fields = ['date', 'destination', 'purpose', 'inclDate', 'timeDeparture', 'timeArrival', 'request'];

    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            let value = urlParams.get(field) || '';

            // ✅ Fix Inclusive Dates (Use Database Format Directly)
            if (field === 'inclDate') {
                // If value is already formatted as "Mar 3-4, Mar 6-7", don't process further
                if (!value.includes("-")) {
                    let dateArray = value.split(",").filter(Boolean);
                    value = formatDateRanges(dateArray);
                }
            }

            // Format regular dates
            if (field === 'date') {
                value = formatDate(value);
            }

            element.value = value;
        } else {
            console.warn(`Element with ID '${field}' not found`);
        }
    });
});
