document.addEventListener("DOMContentLoaded", function () {
    function loadSection(id, url) {
        fetch(url)
            .then(response => response.text())
            .then(data => document.getElementById(id).innerHTML = data)
            .catch(error => console.error(`Error loading ${url}:`, error));
    }

    loadSection("personalDetails", "details/personalDetails.html");
    loadSection("leaveDetails", "details/leaveDetails.html");
    loadSection("actionDetails", "details/actionDetails.html");
});
