const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get("status");
        
            if (status) {
                const alertBox = document.createElement("div");
                alertBox.classList.add("alert");
        
                if (status === "success") {
                    alertBox.textContent = "Leave Application Submitted Successfully!";
                    alertBox.classList.add("success");
                } else if (status === "error") {
                    alertBox.textContent = "Error submitting the form. Please try again.";
                    alertBox.classList.add("error");
                }
        
                document.body.prepend(alertBox);
                alertBox.style.display = "block";
        
                setTimeout(() => {
                    alertBox.style.display = "none";
                }, 5000);
            }