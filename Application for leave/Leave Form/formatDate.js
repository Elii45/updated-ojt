document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[type="date"]').forEach((dateInput) => {
        function updateDateDisplay() {
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
                span.style.fontSize = "12px"; // Set font size
                span.style.display = "inline-block";

                // Apply different padding based on input name
                if (dateInput.name === "filingDate") {
                    span.style.padding = "0 170px 0 20px";
                } else {
                    span.style.padding = "20px 10px 10px 10px";
                }
                
                // When the span is clicked, replace it with the input again
                span.addEventListener("click", function () {
                    dateInput.style.display = "inline-block";
                    span.replaceWith(dateInput);
                });

                dateInput.replaceWith(span);
            }
        }

        dateInput.addEventListener("change", updateDateDisplay);
        updateDateDisplay();
    });
});
