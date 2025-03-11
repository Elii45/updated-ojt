document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[type="date"]').forEach((dateInput) => {
        function updateDateDisplay() {
            if (dateInput.value) { // Ensure a date is selected
                const selectedDate = new Date(dateInput.value);
                const options = { month: "long", day: "2-digit", year: "numeric" };
                const formattedDate = selectedDate.toLocaleDateString("en-US", options);

                const span = document.createElement("span");
                span.className = "formatted-date";
                span.textContent = formattedDate;
                span.dataset.originalInput = dateInput.name;
                span.dataset.dateValue = dateInput.value; // Store the actual value
                span.style.cursor = "pointer";
                span.style.display = "inline-block";

                // When clicked, replace span with the original input field
                span.addEventListener("click", function () {
                    span.replaceWith(dateInput);
                });

                dateInput.replaceWith(span);
            }
        }

        dateInput.addEventListener("change", updateDateDisplay);
        updateDateDisplay();
    });

    // ✅ Restore date inputs before submission
    document.querySelector("form").addEventListener("submit", function () {
        document.querySelectorAll(".formatted-date").forEach(span => {
            let input = document.createElement("input");
            input.type = "date";
            input.name = span.dataset.originalInput;
            input.value = span.dataset.dateValue; // Retrieve the correct value
            input.required = true;
            span.replaceWith(input);
        });
    });
});
