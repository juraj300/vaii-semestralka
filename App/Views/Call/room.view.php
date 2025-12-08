<?php
/** @var \App\Models\Lead $lead */
/** @var \App\Models\Script $script */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="row" id="call-room-container" data-current-lead-id="<?= $lead->id ?>">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Lead Details</h5>
            </div>
            <div class="card-body" id="lead-info">
                <h3><span id="lead-company"><?= htmlspecialchars($lead->company) ?></span></h3>
                <p><strong>Contact:</strong> <span id="lead-contact"><?= htmlspecialchars($lead->contact_name) ?></span></p>
                <p><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($lead->phone) ?>" id="lead-phone-link"><span id="lead-phone"><?= htmlspecialchars($lead->phone) ?></span></a></p>
                <p><strong>Email:</strong> <span id="lead-email"><?= htmlspecialchars($lead->email) ?></span></p>
                <hr>
                <h6>Talking Points (Heuristics)</h6>
                <ul id="talking-points" class="small text-muted">
                    <li>Mention recent industry trends.</li>
                    <li>Ask about current solution for XYZ.</li>
                    <li>Offer free audit.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
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
            <div class="card-body bg-light">
                <div id="script-body" class="p-3 border bg-white rounded">
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
                    
                    <div class="d-flex justify-content-between">
                        <div class="btn-group" role="group">
                             <input type="radio" class="btn-check" name="outcome" id="outcome1" value="contacted" autocomplete="off" checked>
                             <label class="btn btn-outline-info" for="outcome1">Contacted</label>

                             <input type="radio" class="btn-check" name="outcome" id="outcome2" value="interested" autocomplete="off">
                             <label class="btn btn-outline-warning" for="outcome2">Interested</label>

                             <input type="radio" class="btn-check" name="outcome" id="outcome3" value="closed_won" autocomplete="off">
                             <label class="btn btn-outline-success" for="outcome3">Closed Won</label>
                             
                             <input type="radio" class="btn-check" name="outcome" id="outcome4" value="closed_lost" autocomplete="off">
                             <label class="btn btn-outline-danger" for="outcome4">Closed Lost</label>
                        </div>

                        <div>
                            <button type="button" id="save-call-btn" class="btn btn-primary" onclick="window.saveCall()">Save & Next</button>
                            <a href="<?= $link->url('lead.index') ?>" class="btn btn-secondary">Exit Room</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- URLs for JS -->
<script>
    const LOG_CALL_URL = "<?= $link->url('call.logCall') ?>";
    const NEXT_LEAD_URL = "<?= $link->url('call.nextLead') ?>";
    const LEAD_INDEX_URL = "<?= $link->url('lead.index') ?>";
    const ALL_SCRIPTS = <?= json_encode($scripts ?? []) ?>;
</script>
