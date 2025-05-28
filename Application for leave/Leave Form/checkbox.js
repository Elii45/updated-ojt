document.addEventListener('DOMContentLoaded', function() {
    // PHP variables embedded into JS
    const leaveTypes = <?php echo json_encode($leaveTypes ?? []); ?>;

    // Check leave type checkboxes
    leaveTypes.forEach(type => {
        const checkbox = document.querySelector(`input[name="leaveType[]"][value="${type}"]`);
        if (checkbox) {
            checkbox.checked = true;
            // If "others" is checked, also enable the text field for specifying
            if (type === "others") {
                const othersInput = document.querySelector('input[name="leaveTypeOthers"]');
                if (othersInput) othersInput.disabled = false;
            }
        }
    });

    // Show/hide detail sections based on leave type
    if (leaveTypes.includes('vacationLeave') || leaveTypes.includes('specialPrivilege')) {
        document.getElementById('vacationDetails').style.display = 'block';
    } else {
        document.getElementById('vacationDetails').style.display = 'none';
    }

    if (leaveTypes.includes('sickLeave')) {
        document.getElementById('sickLeaveDetails').style.display = 'block';
    } else {
        document.getElementById('sickLeaveDetails').style.display = 'none';
    }

    if (leaveTypes.includes('specialLeave')) {
        document.getElementById('womenLeaveDetails').style.display = 'block';
    } else {
        document.getElementById('womenLeaveDetails').style.display = 'none';
    }

    if (leaveTypes.includes('studyLeave')) {
        document.getElementById('studyLeaveDetails').style.display = 'block';
    } else {
        document.getElementById('studyLeaveDetails').style.display = 'none';
    }

    // Fill detail inputs with PHP data if present
    <?php if (!empty($leave['withinPhilippinesDetail'])): ?>
        document.getElementById('withinPhilippinesDetail').value = <?php echo json_encode($leave['withinPhilippinesDetail']); ?>;
        document.getElementById('withinPhilippines').checked = true;
    <?php endif; ?>

    <?php if (!empty($leave['abroadDetails'])): ?>
        document.getElementById('abroadDetails').value = <?php echo json_encode($leave['abroadDetails']); ?>;
        document.getElementById('abroad').checked = true;
    <?php endif; ?>

    <?php if (!empty($leave['hospitalDetail'])): ?>
        document.getElementById('hospitalDetail').value = <?php echo json_encode($leave['hospitalDetail']); ?>;
        document.getElementById('hospital').checked = true;
    <?php endif; ?>

    <?php if (!empty($leave['outPatientDetail'])): ?>
        document.getElementById('outPatientDetail').value = <?php echo json_encode($leave['outPatientDetail']); ?>;
        document.getElementById('outPatient').checked = true;
    <?php endif; ?>

    <?php if (!empty($leave['leaveWomenDetail'])): ?>
        document.getElementById('leaveWomen').value = <?php echo json_encode($leave['leaveWomenDetail']); ?>;
    <?php endif; ?>

    // For checkboxes in Study Leave and Other Purposes
    <?php if (!empty($leave['detailTypes'])): 
        $detailTypes = json_decode($leave['detailTypes'], true);
        foreach ($detailTypes as $dt): ?>
            const dtCheckbox = document.querySelector(`input[name="detailType[]"][value="<?php echo $dt; ?>"]`);
            if (dtCheckbox) dtCheckbox.checked = true;
    <?php endforeach; endif; ?>

    // For "others" text field
    <?php if (!empty($leave['othersText'])): ?>
        const othersText = <?php echo json_encode($leave['othersText']); ?>;
        const othersInput = document.querySelector('input[name="leaveTypeOthers"]');
        if (othersInput) {
            othersInput.value = othersText;
            othersInput.disabled = false;
        }
    <?php endif; ?>

    // Optional: call your function to disable other checkboxes if "others" is checked
    if (leaveTypes.includes('others')) {
        if (typeof disableOtherCheckboxes === 'function') {
            disableOtherCheckboxes();
        }
    }
});
