<?php

/** @var string $contentHTML */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title><?= App\Configuration::APP_NAME ?></title>
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $link->asset('favicons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $link->asset('favicons/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $link->asset('favicons/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= $link->asset('favicons/site.webmanifest') ?>">
    <link rel="shortcut icon" href="<?= $link->asset('favicons/favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $link->asset('css/styl.css') ?>?v=2">
    <script src="<?= $link->asset('js/script.js') ?>?v=2"></script>
</head>
<body class="dark-mode">
<nav class="navbar navbar-expand-lg sticky-top navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $link->url('home.index') ?>">
            <img src="<?= $link->asset('images/vaiicko_logo.png') ?>" title="<?= App\Configuration::APP_NAME ?>" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                     <a class="nav-link" href="<?= $link->url('script.index') ?>">Scripts</a>
                </li>
                <li class="nav-item">
                     <a class="nav-link" href="<?= $link->url('lead.index') ?>">Leads</a>
                </li>
                <?php if ($user->isLoggedIn()) { ?>
                    <li class="nav-item">
                         <a class="nav-link" href="<?= $link->url('calendar.index') ?>">Calendar</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" href="<?= $link->url('file.index') ?>">Files</a>
                    </li>
                    <?php if ($user->getIdentity()->isAdmin()) { ?>
                        <li class="nav-item">
                             <a class="nav-link" href="<?= $link->url('admin.index') ?>">Users</a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
            
            <div class="navbar-nav ms-auto align-items-center flex-row gap-2 mt-3 mt-lg-0">
                <?php if ($user->isLoggedIn()) { ?>
                    <span class="navbar-text me-2 d-none d-md-inline">
                         <?= htmlspecialchars($user->getName()) ?>
                    </span>
                    <a href="<?= $link->url('auth.logout') ?>" class="btn btn-outline-danger btn-sm">Log out</a>
                <?php } else { ?>
                    <a class="nav-link px-2" href="<?= App\Configuration::LOGIN_URL ?>">Log in</a>
                    <a href="<?= $link->url('auth.register') ?>" class="btn btn-primary btn-sm">Join</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>
<div class="container-fluid mt-3">
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>
</body>
</html>
