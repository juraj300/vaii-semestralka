<?php
/** @var array $files */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="fade-in">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>My Private Files</h1>
            <p class="text-muted">Manage your personal documents, templates, and CSV files for import.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Upload New File</h5>
            </div>
            <div class="card-body">
                <form action="<?= $link->url('file.upload') ?>" method="post" enctype="multipart/form-data">
                    <?= \App\Auth\Csrf::input() ?>
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input class="form-control" type="file" id="file" name="file" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            <strong>Tip:</strong> Upload CSV files here before importing them as leads in the Leads section.
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Filename</th>
                            <th>Uploaded At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($files as $file): ?>
                            <tr>
                                <td>
                                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                    <?= htmlspecialchars($file->filename) ?>
                                </td>
                                <td><?= date('d.m.Y H:i', strtotime($file->created_at)) ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= $link->asset('uploads/' . $file->path) ?>" target="_blank" class="btn btn-outline-secondary" title="View/Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="<?= $link->url('file.delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this file?')">
                                            <?= \App\Auth\Csrf::input() ?>
                                            <input type="hidden" name="id" value="<?= $file->id ?>">
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($files)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No files uploaded yet.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
