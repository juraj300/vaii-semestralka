<?php

/** @var string|null $message */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="row justify-content-center fade-in">
    <div class="col-md-6 col-lg-4">
        <h2 class="text-center mb-4">Register</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?= $link->url('auth.register') ?>">
                    <?= \App\Auth\Csrf::input() ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="username" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="submit" value="submit">Register</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="<?= $link->url('auth.login') ?>">Login here</a></p>
        </div>
    </div>
</div>
