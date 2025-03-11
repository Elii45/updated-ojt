document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[type="date"]').forEach((dateInput) => {
        function updateDateDisplay() {
            if (dateInput.value) {
                const selectedDate = new Date(dateInput.value);
                if (!isNaN(selectedDate)) {
                    const options = { month: "long", day: "2-digit", year: "numeric" };
                    const formattedDate = selectedDate.toLocaleDateString("en-US", options);

                    // Create a span to replace the input field
                    const span = document.createElement("span");
                    span.className = "formatted-date";
                    span.textContent = formattedDate;
                    span.dataset.originalInput = dateInput.name;
                    span.style.cursor = "pointer";
                    span.style.display = "inline-block";
                    span.style.borderBottom = "1px solid black";
                    span.style.padding = "3px 0";
                    span.style.minWidth = "120px"; // Ensures spacing is correct

                    // Clicking the span brings back the input field
                    span.addEventListener("click", function () {
                        dateInput.style.display = "inline-block";
                        span.replaceWith(dateInput);
                        dateInput.focus();
                    });

                    dateInput.replaceWith(span);
                }
            }
        }

        dateInput.addEventListener("change", updateDateDisplay);
        updateDateDisplay(); // Run on page load to format pre-filled values
    });
});
