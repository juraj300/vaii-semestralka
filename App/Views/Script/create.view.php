<div class="fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h1 class="h3 mb-0">Create Script</h1>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $link->url('script.store') ?>" method="POST">
                        <?= \App\Auth\Csrf::input() ?>
                        <div class="mb-4">
                            <label for="name" class="form-label">Script Name</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="e.g., Sales Pitch v1" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="body" class="form-label">Script Body</label>
                            <div class="form-text mb-2 text-muted small">
                                <i class="bi bi-info-circle"></i> Available variables: 
                                <span class="badge bg-dark">#contact_name</span>
                                <span class="badge bg-dark">#company</span>
                                <span class="badge bg-dark">#agent_name</span>
                            </div>
                            <textarea class="form-control" id="body" name="body" rows="12" placeholder="Write your pitch here..." required></textarea>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default">
                            <label class="form-check-label fw-bold" for="is_default">Set as Default Script</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="<?= $link->url('script.index') ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5">Save Script</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
