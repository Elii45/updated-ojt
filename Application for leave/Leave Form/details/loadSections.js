document.addEventListener("DOMContentLoaded", function () {
  function loadSection(id, url) {
    fetch(url)
      .then(response => response.text())
      .then(data => {
        document.getElementById(id).innerHTML = data;
      })
      .catch(error => console.error(`Error loading ${url}:`, error));
  }

  // Load main form sections
  loadSection("personalDetails", "details/personalDetails.html");
  
  // Load inner leave details sections
  loadSection("sectionA", "details/sections/SectionA_Type.html");
  loadSection("sectionB", "details/sections/SectionB_TypeDetails.html");
  loadSection("sectionD", "details/sections/SectionD_Commutation.html");
  
  loadSection("actionDetails", "details/actionDetails.html");
});

// If you're using fetch:
fetch("details/sections/SectionC_Dates.html")
  .then(res => res.text())
  .then(html => {
    document.getElementById("sectionC").innerHTML = html;

    // Initialize calendar after injecting HTML
    initializeDatepicker();
  });

