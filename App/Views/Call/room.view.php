<?php
/** @var \App\Models\Lead $lead */
/** @var \App\Models\Script $script */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="row g-4" id="call-room-container" data-current-lead-id="<?= $lead->id ?>">
    <div class="col-lg-5 col-xl-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Lead Details</h5>
            </div>
            <div class="card-body text-white" id="lead-info">
                <h3><span id="lead-company"><?= htmlspecialchars($lead->company) ?></span></h3>
                <p><strong>Contact:</strong> <span id="lead-contact"><?= htmlspecialchars($lead->contact_name) ?></span></p>
                <p><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($lead->phone) ?>" id="lead-phone-link" class="text-white"><span id="lead-phone"><?= htmlspecialchars($lead->phone) ?></span></a></p>
                <p><strong>Email:</strong> <span id="lead-email"><?= htmlspecialchars($lead->email) ?></span></p>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Talking Points</h6>
                    <button type="button" id="ai-research-btn" class="btn btn-sm btn-outline-primary" onclick="window.generateAIPoints()">
                        <i class="bi bi-stars"></i> Research AI
                    </button>
                </div>
                
                <div id="ai-loading" class="text-center d-none my-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 small">Gemini is researching...</span>
                </div>
 
                <div id="ai-talking-points" class="small text-main">
                    <ul id="talking-points" class="text-muted">
                        <li>Mention recent industry trends.</li>
                        <li>Ask about current solution for XYZ.</li>
                        <li>Offer free audit.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
 
    <div class="col-lg-7 col-xl-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Call Script</h5>
                <select id="script-selector" class="form-select form-select-sm" style="width: auto;">
                    <?php foreach ($scripts as $s): ?>
                        <option value="<?= $s->id ?>" <?= ($script && $script->id == $s->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="card-body">
                <div id="script-body" class="p-4 rounded border" style="background: rgba(0,0,0,0.2); color: var(--text-main); min-height: 400px; font-size: 1.1rem; border-color: var(--border-color) !important;">
                    <?= nl2br(htmlspecialchars($script->body ?? 'No script found.')) ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="call-outcome-form">
                     <!-- Lead ID injected by JS or kept in data attr -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Call Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="d-flex flex-column flex-xl-row justify-content-between gap-3">
                        <div class="btn-group flex-wrap" role="group">
                             <input type="radio" class="btn-check" name="outcome" id="outcome1" value="contacted" autocomplete="off" checked>
                             <label class="btn btn-outline-info" for="outcome1">Contacted</label>

                             <input type="radio" class="btn-check" name="outcome" id="outcome2" value="interested" autocomplete="off">
                             <label class="btn btn-outline-warning" for="outcome2">Interested</label>

                             <input type="radio" class="btn-check" name="outcome" id="outcome3" value="closed_won" autocomplete="off">
                             <label class="btn btn-outline-success" for="outcome3">Closed Won</label>
                             
                             <input type="radio" class="btn-check" name="outcome" id="outcome4" value="closed_lost" autocomplete="off">
                             <label class="btn btn-outline-danger" for="outcome4">Closed Lost</label>
                        </div>

                        <div class="d-flex flex-wrap flex-sm-nowrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#followupModal">
                                <i class="bi bi-calendar-event"></i> Follow-up
                            </button>
                            <button type="button" id="save-call-btn" class="btn btn-sm btn-primary" onclick="window.saveCall()">Save & Next</button>
                            <a href="<?= $link->url('lead.index') ?>" class="btn btn-sm btn-secondary">Exit</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Follow-up Modal -->
<div class="modal fade" id="followupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Schedule Follow-up</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="followup-form">
                    <div class="mb-3">
                        <label class="form-label text-light">Date & Time</label>
                        <input type="datetime-local" class="form-control" name="start_at" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light">Short Note</label>
                        <input type="text" class="form-control" name="title" placeholder="e.g. Discuss Q4 budget" value="Follow-up: <?= htmlspecialchars($lead->company) ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="window.saveFollowup()">Save Appointment</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.saveFollowup = async function() {
        const form = document.getElementById('followup-form');
        const formData = new FormData(form);
        formData.append('lead_id', '<?= $lead->id ?>');
        formData.append('csrf_token', CSRF_TOKEN);

        try {
            const response = await fetch("<?= $link->url('calendar.save') ?>", {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                alert('Appointment scheduled!');
                bootstrap.Modal.getInstance(document.getElementById('followupModal')).hide();
            } else {
                alert('Error: ' + (result.error || 'Unknown error'));
            }
        } catch (e) {
            alert('Failed to save appointment');
        }
    };
</script>

<!-- URLs for JS -->
<script>
    const LOG_CALL_URL = "<?= $link->url('call.logCall') ?>";
    const NEXT_LEAD_URL = "<?= $link->url('call.nextLead') ?>";
    const GEN_POINTS_URL = "<?= $link->url('call.generateTalkingPoints') ?>";
    const LEAD_INDEX_URL = "<?= $link->url('lead.index') ?>";
    const ALL_SCRIPTS = <?= json_encode($scripts ?? []) ?>;
    const CSRF_TOKEN = "<?= \App\Auth\Csrf::getToken() ?>";

    window.generateAIPoints = async function() {
        const btn = document.getElementById('ai-research-btn');
        const loading = document.getElementById('ai-loading');
        const results = document.getElementById('ai-talking-points');
        const scriptBody = document.getElementById('script-body').innerText;
        const leadId = document.getElementById('call-room-container').dataset.currentLeadId;

        btn.disabled = true;
        loading.classList.remove('d-none');
        results.classList.add('opacity-50');

        try {
            const formData = new FormData();
            formData.append('lead_id', leadId);
            formData.append('script_body', scriptBody);
            formData.append('csrf_token', CSRF_TOKEN);

            const response = await fetch(GEN_POINTS_URL, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                // Simple markdown to HTML (bullets)
                let html = result.talking_points
                    .replace(/\n\n/g, '<br>')
                    .replace(/\* (.*)/g, '<li>$1</li>')
                    .replace(/- (.*)/g, '<li>$1</li>');
                
                if (html.includes('<li>')) {
                    html = '<ul class="ps-3 mb-0">' + html + '</ul>';
                }
                
                results.innerHTML = html;
            } else {
                results.innerHTML = `<div class="text-danger small">${result.error}</div>`;
            }
        } catch (e) {
            results.innerHTML = `<div class="text-danger small">Network error occurred.</div>`;
        } finally {
            btn.disabled = false;
            loading.classList.add('d-none');
            results.classList.remove('opacity-50');
        }
    };
</script>
