document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[type="date"], input[type="time"]').forEach((input) => {
        function updateDisplay() {
            if (input.value) {
                let formattedValue = input.value;

                // Format date as "Month Day, Year"
                if (input.type === "date") {
                    const selectedDate = new Date(input.value);
                    if (!isNaN(selectedDate)) {
                        const options = { month: "long", day: "2-digit", year: "numeric" };
                        formattedValue = selectedDate.toLocaleDateString("en-US", options);
                    }
                }

                // Format time as "HH:MM AM/PM"
                if (input.type === "time") {
                    const [hours, minutes] = input.value.split(":");
                    let hour = parseInt(hours, 10);
                    const ampm = hour >= 12 ? "PM" : "AM";
                    hour = hour % 12 || 12; // Convert 0 or 12 to 12
                    formattedValue = `${hour}:${minutes} ${ampm}`;
                }

                // Create span to replace the input field
                const span = document.createElement("span");
                span.className = "formatted-date";
                span.textContent = formattedValue;
                span.dataset.originalInput = input.name;
                span.style.cursor = "pointer";
                span.style.display = "inline-block";
                span.style.padding = "5px";
                span.style.borderBottom = "1px solid black"; // Add border bottom

                // Clicking the span brings back the input field
                span.addEventListener("click", function () {
                    input.style.display = "inline-block";
                    span.replaceWith(input);
                    input.focus();
                });

                input.replaceWith(span);
            }
        }

        input.addEventListener("change", updateDisplay);
        updateDisplay();
    });
});
