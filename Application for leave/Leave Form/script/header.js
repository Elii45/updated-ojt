
        fetch('leave_form_header.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-container').innerHTML = data;
            })
            .catch(error => console.error('Error loading the header:', error));