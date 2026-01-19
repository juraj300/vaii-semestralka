<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */
?>

<div class="container py-4 fade-in">
    <div class="p-5 mb-4 card border-0">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4 fw-bold hero-title">Welcome, <?= htmlspecialchars($user->getName()) ?>!</h1>
            <p class="col-md-8 mx-auto fs-5 text-muted">Your Call Assistant is ready. Start by managing your leads or jump straight into the call room.</p>
            <div class="mt-4">
                <a href="<?= $link->url('lead.index') ?>" class="btn btn-primary btn-lg shadow-lg" type="button">
                    <i class="bi bi-telephone-forward me-2"></i> Start Calling
                </a>
            </div>
        </div>
    </div>

    <div class="row align-items-md-stretch g-4">
        <div class="col-lg-6">
            <div class="h-100 p-5 card">
                <h2 class="text-primary mb-3">Lead Management</h2>
                <p class="text-muted">Import contacts, update statuses, and track your sales pipeline with ease.</p>
                <a href="<?= $link->url('lead.index') ?>" class="btn btn-outline-primary mt-auto" type="button" style="width: fit-content;">View Leads</a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="h-100 p-5 card">
                <h2 class="mb-4">Performance Overview</h2>
                <div class="row g-4">
                    <div class="col-6">
                        <div class="p-3 surface-light rounded-3">
                            <div class="fs-4 fw-bold text-main"><?= $totalCalls ?></div>
                            <div class="text-muted small text-uppercase">Total Calls</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 surface-light rounded-3">
                            <div class="fs-4 fw-bold text-success"><?= $conversionRate ?>%</div>
                            <div class="text-muted small text-uppercase">Conversion</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 surface-light rounded-3">
                            <div class="fs-4 fw-bold text-primary"><?= $newLeads ?></div>
                            <div class="text-muted small text-uppercase">New Leads</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 surface-light rounded-3">
                            <div class="fs-4 fw-bold text-warning"><?= $interestedLeads ?></div>
                            <div class="text-muted small text-uppercase">Interested</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>