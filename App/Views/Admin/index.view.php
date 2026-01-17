<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */
?>

<div class="container py-4">
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Welcome, <?= htmlspecialchars($user->getName()) ?>!</h1>
            <p class="col-md-8 fs-4">Your Call Assistant is ready. Start by managing your leads or jump straight into the call room.</p>
            <a href="<?= $link->url('lead.index') ?>" class="btn btn-primary btn-lg" type="button">
                <i class="bi bi-telephone-forward"></i> Start Calling
            </a>
        </div>
    </div>

    <div class="row align-items-md-stretch">
        <div class="col-md-6 mb-4">
            <div class="h-100 p-5 text-white bg-dark rounded-3 shadow-sm">
                <h2 class="text-white">Lead Management</h2>
                <p>Import contacts, update statuses, and track your sales pipeline.</p>
                <a href="<?= $link->url('lead.index') ?>" class="btn btn-outline-light" type="button">View Leads</a>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="h-100 p-5 bg-white border rounded-3 shadow-sm">
                <h2>Performance Overview</h2>
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <div class="fs-4 fw-bold"><?= $totalCalls ?></div>
                        <div class="text-muted small">Total Calls Made</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="fs-4 fw-bold text-success"><?= $conversionRate ?>%</div>
                        <div class="text-muted small">Conversion Rate</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-4 fw-bold text-primary"><?= $newLeads ?></div>
                        <div class="text-muted small">New Leads</div>
                    </div>
                    <div class="col-6">
                        <div class="fs-4 fw-bold text-warning"><?= $interestedLeads ?></div>
                        <div class="text-muted small">Interested</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>