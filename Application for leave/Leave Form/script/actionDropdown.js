  document.addEventListener('DOMContentLoaded', () => {
    loadActionDetails();
  });

  function loadActionDetails() {
    fetch('./details/actionDetails.html')
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
      })
      .then(html => {
        const container = document.getElementById('actionDetails');
        if (!container) {
          console.error('No element with id "actionDetails" found.');
          return;
        }
        container.innerHTML = html;
        setupRecommendationToggle();
      })
      .catch(err => {
        console.error('Failed to load actionDetails.html:', err);
      });
  }

  function setupRecommendationToggle() {
    const recommendationSelect = document.getElementById('recommendation');
    const disapprovalInput = document.getElementById('disapprovalDetail');

    if (!recommendationSelect || !disapprovalInput) {
      console.error('Recommendation dropdown or disapproval input not found.');
      return;
    }

    // Show or hide disapproval input based on current selection on load
    toggleDisapprovalInput(recommendationSelect.value, disapprovalInput);

    // Add event listener for future changes
    recommendationSelect.addEventListener('change', function () {
      toggleDisapprovalInput(this.value, disapprovalInput);
    });
  }

  function toggleDisapprovalInput(value, inputElement) {
    if (value === 'disapproval') {
      inputElement.style.display = 'inline-block';
    } else {
      inputElement.style.display = 'none';
      inputElement.value = ''; // clear input when hidden
    }
  }
