<div class="fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h1 class="h3 mb-0">Edit Script</h1>
                </div>
                <div class="card-body p-4">
                    <form action="<?= $link->url('script.update') ?>" method="POST">
                        <?= \App\Auth\Csrf::input() ?>
                        <input type="hidden" name="id" value="<?= $script->id ?>">
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Script Name</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" value="<?= htmlspecialchars($script->name) ?>" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="body" class="form-label">Script Body</label>
                            <div class="form-text mb-2 text-muted small">
                                <i class="bi bi-info-circle"></i> Available variables: 
                                <span class="badge bg-dark">#contact_name</span>
                                <span class="badge bg-dark">#company</span>
                                <span class="badge bg-dark">#agent_name</span>
                            </div>
                            <textarea class="form-control" id="body" name="body" rows="12" required><?= htmlspecialchars($script->body) ?></textarea>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" <?= $script->is_default ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_default">Set as Default Script</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="<?= $link->url('script.index') ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5">Update Script</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
