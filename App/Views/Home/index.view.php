<?php

/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col mt-5">
            <div class="text-center">
                <h2>Welcome to <?= App\Configuration::APP_NAME ?></h2>
                <img src="<?= $link->asset('images/vaiicko_logo.png') ?>" alt="Logo" class="img-fluid mb-4" style="max-height: 200px;">
                <p class="lead">
                    Your professional tool for call management.
                </p>
                <div class="mt-4">
                    <a href="<?= $link->url('auth.login') ?>" class="btn btn-primary btn-lg me-3">Login</a>
                    <a href="<?= $link->url('auth.register') ?>" class="btn btn-outline-primary btn-lg">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>
