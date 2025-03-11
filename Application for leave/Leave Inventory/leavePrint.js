function printPage() {
    document.querySelectorAll('input[type="date"]').forEach((input) => {
        if (input.value) {
            const selectedDate = new Date(input.value);
            const options = { month: "long", day: "2-digit", year: "numeric" };
            input.setAttribute("data-print-value", selectedDate.toLocaleDateString("en-US", options));
        }
    });

    window.print();
}
