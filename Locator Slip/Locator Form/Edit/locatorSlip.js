document.addEventListener("DOMContentLoaded", function () {
    const addDropdownBtn = document.getElementById("addDropdown");
    const dropdownContainer = document.getElementById("dropdown-container");

    // Generate options HTML from window.employeesList
    function getOptionsHtml() {
        let options = '<option value="" disabled>Select Employee</option>';
        window.employeesList.forEach(emp => {
            options += `<option value="${emp.name}">${emp.name}</option>`;
        });
        return options;
    }

    function updateDropdowns() {
        const requestGroups = dropdownContainer.querySelectorAll(".request-group");

        requestGroups.forEach((group, index) => {
            const label = group.querySelector("label");
            const select = group.querySelector("select");

            if (label) {
                label.setAttribute("for", index === 0 ? "request" : "request" + index);
                label.textContent = index === 0 ? "Requested by:" : "";
            }

            if (select) {
                select.id = index === 0 ? "request" : "request" + index;
                select.name = "request[]";
            }

            // Handle remove button
            let removeBtn = group.querySelector(".remove-btn");
            if (index === 0) {
                if (removeBtn) removeBtn.remove();
            } else {
                if (!removeBtn) {
                    removeBtn = document.createElement("button");
                    removeBtn.type = "button";
                    removeBtn.className = "remove-btn";
                    removeBtn.textContent = "Remove";
                    removeBtn.onclick = () => {
                        group.remove();
                        updateDropdowns();
                    };
                    group.appendChild(removeBtn);
                }
            }
        });

        // Disable Add button if only PITO Office is selected in all dropdowns
        const selectedValues = Array.from(dropdownContainer.querySelectorAll("select")).map(sel => sel.value);
        addDropdownBtn.disabled = selectedValues.length === 1 && selectedValues[0] === "PITO Office";
    }

    addDropdownBtn.addEventListener("click", () => {
        const requestGroups = dropdownContainer.querySelectorAll(".request-group");
        const newIndex = requestGroups.length;

        const div = document.createElement("div");
        div.className = "request-group";

        const label = document.createElement("label");
        label.setAttribute("for", "request" + newIndex);
        label.textContent = "";

        const select = document.createElement("select");
        select.name = "request[]";
        select.id = "request" + newIndex;
        select.className = "request";
        select.innerHTML = getOptionsHtml();
        select.selectedIndex = 0;

        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.className = "remove-btn";
        removeBtn.textContent = "Remove";
        removeBtn.onclick = () => {
            div.remove();
            updateDropdowns();
        };

        div.appendChild(label);
        div.appendChild(select);
        div.appendChild(removeBtn);

        dropdownContainer.appendChild(div);
        updateDropdowns();
    });

    updateDropdowns();
});
