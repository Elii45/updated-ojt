
document.addEventListener("DOMContentLoaded", function () {
    // Function to load the section dynamically
    function loadSection(id, url) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById(id).innerHTML = data;
                // Initialize datepicker after the section is loaded
                if (id === "sectionC") {
                    // Initialize datepicker
                    $('#datepicker').datepicker({
                        startDate: new Date(),
                        multidate: true,  // Allow multiple dates
                        format: "MM dd, yyyy",
                        daysOfWeekHighlighted: [5, 6], // Highlight Friday and Saturday
                        datesDisabled: ['31/08/2024'],
                        todayHighlight: true,
                        autoclose: false,
                        toggleActive: true // Allow active toggling of dates
                    })
                        .on('changeDate', function (e) {
                            const selectedDates = e.dates
                                .map(date => new Date(date)) // Convert to Date objects
                                .sort((a, b) => a - b); // Sort the dates

                            // Group dates into ranges (consecutive dates)
                            const dateRanges = getDateRanges(selectedDates);

                            // Display the ranges as a comma-separated string
                            $('#Dates').val(dateRanges.join(', '));

                            // Update workingDays count
                            $('#workingDays').val(selectedDates.length);
                        });

                    // Enable the calendar icon to open the datepicker
                    $('.input-group-addon').click(function () {
                        $('#datepicker').datepicker('show');
                    });

                    // Allow manual editing, and use a regex to keep the format correct
                    $('#Dates').on('input', function () {
                        const inputValue = $(this).val();
                        // Regex for the date range format "Month dd-dd"
                        const dateRangeRegex = /^([A-Za-z]+) (\d{1,2})-(\d{1,2}),? ([A-Za-z]+) (\d{1,2})-(\d{1,2}),? (\d{1,2})$/;
                        if (!dateRangeRegex.test(inputValue)) {
                            // Optionally show a message or revert to previous valid value
                            $(this).val('');
                        }
                    });
                }
            })
            .catch(error => console.error(`Error loading ${url}:`, error));
    }

    // Load Section C
    loadSection("sectionC", "details/sections/SectionC_Dates.html");

    // Function to format date as "Month dd, yyyy"
    function formatDateForDisplay(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }

    // Function to convert a sorted list of dates into date ranges
    function getDateRanges(dates) {
        const ranges = [];
        let rangeStart = dates[0];

        for (let i = 1; i < dates.length; i++) {
            const currentDate = dates[i];
            const prevDate = dates[i - 1];

            // Check if the current date is the next day of the previous date
            if (currentDate.getDate() === prevDate.getDate() + 1 &&
                currentDate.getMonth() === prevDate.getMonth() &&
                currentDate.getFullYear() === prevDate.getFullYear()) {
                // Continue the range
                continue;
            } else {
                // End the current range and start a new range
                if (rangeStart.getDate() === prevDate.getDate()) {
                    ranges.push(formatDateForDisplay(rangeStart));
                } else {
                    ranges.push(`${formatDateForDisplay(rangeStart)}-${formatDateForDisplay(prevDate)}`);
                }
                rangeStart = currentDate; // Start new range
            }
        }

        // Push the last range
        if (rangeStart.getDate() === dates[dates.length - 1].getDate()) {
            ranges.push(formatDateForDisplay(rangeStart));
        } else {
            ranges.push(`${formatDateForDisplay(rangeStart)}-${formatDateForDisplay(dates[dates.length - 1])}`);
        }

        return ranges;
    }
});