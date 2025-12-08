document.addEventListener("DOMContentLoaded", function () {
    console.log("Call Assistant JS Loaded");

    // Bootstrap validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Script Selector Logic
    const scriptSelector = document.getElementById('script-selector');
    if (scriptSelector && typeof ALL_SCRIPTS !== 'undefined') {
        scriptSelector.addEventListener('change', function () {
            const scriptId = this.value;
            const selectedScript = ALL_SCRIPTS.find(s => s.id == scriptId);
            if (selectedScript) {
                // We need to replace placeholders client-side
                let body = selectedScript.body;

                // Get current lead data from DOM
                const contact = document.getElementById('lead-contact').innerText;
                const company = document.getElementById('lead-company').innerText;
                const agent = document.getElementById('navbar-user-name') ? document.getElementById('navbar-user-name').innerText : ''; // Need to ensure this element exists or fallback

                body = body.replace(/{{contact_name}}/g, contact)
                    .replace(/{{company}}/g, company)
                    .replace(/{{agent_name}}/g, agent);

                document.getElementById('script-body').innerHTML = body.replace(/\n/g, "<br>");
            }
        });
    }
});

/**
 * Handles the "Save & Next" action in Call Room.
 */
window.saveCall = async function () {
    const container = document.getElementById('call-room-container');
    if (!container) return;

    const currentLeadId = container.dataset.currentLeadId;
    const form = document.getElementById('call-outcome-form');
    // Safety check for radio button
    const radio = form.querySelector('input[name="outcome"]:checked');
    if (!radio) {
        alert("Please select an outcome.");
        return;
    }
    const outcome = radio.value;
    const notes = document.getElementById('notes').value;
    const saveBtn = document.getElementById('save-call-btn');

    // Disable button to prevent double clicks
    saveBtn.disabled = true;
    saveBtn.innerText = "Saving...";

    try {
        // 1. Log Call
        const formData = new FormData();
        formData.append('lead_id', currentLeadId);
        formData.append('outcome', outcome);
        formData.append('notes', notes);

        const responseLog = await fetch(LOG_CALL_URL, {
            method: 'POST',
            body: formData
        });
        const resultLog = await responseLog.json();

        if (resultLog.status !== 'success') {
            alert("Error saving call: " + (resultLog.message || 'Unknown error'));
            saveBtn.disabled = false;
            saveBtn.innerText = "Save & Next";
            return;
        }

        // 2. Load Next Lead
        const formDataNext = new FormData();
        formDataNext.append('current_id', currentLeadId);

        const responseNext = await fetch(NEXT_LEAD_URL, {
            method: 'POST',
            body: formDataNext
        });
        const resultNext = await responseNext.json();

        if (resultNext.status === 'found') {
            updateRoom(resultNext.lead, resultNext.script_body);
            // Reset form
            form.reset();
            document.getElementById('notes').value = '';
            // Reset button
            saveBtn.disabled = false;
            saveBtn.innerText = "Save & Next";
        } else {
            alert("Great job! No more new leads.");
            window.location.href = LEAD_INDEX_URL;
        }

    } catch (e) {
        console.error(e);
        alert("Network error occurred.");
        saveBtn.disabled = false;
        saveBtn.innerText = "Save & Next";
    }
};

function updateRoom(lead, scriptBody) {
    const container = document.getElementById('call-room-container');
    container.dataset.currentLeadId = lead.id;

    document.getElementById('lead-company').innerText = lead.company;
    document.getElementById('lead-contact').innerText = lead.contact_name;

    const phoneLink = document.getElementById('lead-phone-link');
    phoneLink.href = "tel:" + lead.phone;
    document.getElementById('lead-phone').innerText = lead.phone;

    document.getElementById('lead-email').innerText = lead.email || '';

    // Update script - server sends the default script for the new lead
    // But if we want to keep the selected script type, we might need logic.
    // For now, accept server's default script logic to keep it simple as per spec.
    document.getElementById('script-body').innerHTML = scriptBody.replace(/\n/g, "<br>");

    // Reset selector to default or find the matching ID?
    // Let's assume server returns default.
    // Ideally we might want to trigger the selector change with new data?

    // Animate transition
    container.classList.add('fade-in');
    setTimeout(() => container.classList.remove('fade-in'), 500);
}
