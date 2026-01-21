<?php
/** @var array $scripts */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="fade-in">
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h1 class="mb-md-0">Manage Scripts</h1>
    </div>
    <div class="col-md-6 text-md-end">
        <?php if ($user->getIdentity()->isAdmin()): ?>
            <a href="<?= $link->url('script.create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create Script
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Preview</th>
                <th>Default</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($scripts as $script): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($script->name) ?></td>
                    <td><?= htmlspecialchars(substr($script->body, 0, 50)) ?>...</td>
                    <td>
                        <?php if ($script->is_default): ?>
                            <span class="badge bg-success">Default</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user->getIdentity()->isAdmin()): ?>
                            <div class="btn-group btn-group-sm">
                                <a href="<?= $link->url('script.edit', ['id' => $script->id]) ?>" class="btn btn-secondary">Edit</a>
                                <form action="<?= $link->url('script.delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete script?')">
                                    <?= \App\Auth\Csrf::input() ?>
                                    <input type="hidden" name="id" value="<?= $script->id ?>">
                                    <button type="submit" class="btn btn-danger">Del</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <span class="text-muted small">Viewing only</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
