<?php
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in">
                <img src="<?= $link->asset('images/vaiicko_logo.png') ?>" alt="Logo" class="img-fluid mb-5" style="max-height: 120px; filter: drop-shadow(0 0 20px rgba(14, 165, 233, 0.3));">
                <h1 class="hero-title">Elevate Your Call Strategy</h1>
                <p class="lead text-muted mb-5 px-md-5">
                    Empower your agents with real-time lead management, automated scripts, and seamless file organization in one powerful dark-themed dashboard.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <?php if (!$user->isLoggedIn()) { ?>
                        <a href="<?= $link->url('auth.login') ?>" class="btn btn-primary btn-lg px-5">Get Started</a>
                        <a href="<?= $link->url('auth.register') ?>" class="btn btn-outline-light btn-lg px-5">Join Now</a>
                    <?php } else { ?>
                        <a href="<?= $link->url('lead.index') ?>" class="btn btn-primary btn-lg px-5">Go to Dashboard</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 mt-4">
        <div class="col-md-4 fade-in" style="animation-delay: 0.1s">
            <div class="card h-100 p-4">
                <div class="display-5 text-primary mb-3"><i class="bi bi-lightning-charge"></i></div>
                <h4>Lightning Fast</h4>
                <p class="text-muted">AJAX-powered lead navigation and live search keeps your workflow moving at light speed.</p>
            </div>
        </div>
        <div class="col-md-4 fade-in" style="animation-delay: 0.2s">
            <div class="card h-100 p-4">
                <div class="display-5 text-primary mb-3"><i class="bi bi-shield-lock"></i></div>
                <h4>Secure & Precise</h4>
                <p class="text-muted">Role-based access control and robust server-side validation protect your sensitive data.</p>
            </div>
        </div>
        <div class="col-md-4 fade-in" style="animation-delay: 0.3s">
            <div class="card h-100 p-4">
                <div class="display-5 text-primary mb-3"><i class="bi bi-file-earmark-spreadsheet"></i></div>
                <h4>CSV Integration</h4>
                <p class="text-muted">Import thousands of leads instantly and manage all your supporting files in one place.</p>
            </div>
        </div>
    </div>
</div>
