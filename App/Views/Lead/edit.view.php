<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \App\Models\Lead $lead */
/** @var array|null $errors */
use App\Models\Lead;
?>

<h1>Edit Lead</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= $link->url('lead.update') ?>" method="POST" class="mt-4">
    <input type="hidden" name="id" value="<?= $lead->id ?>">
    
    <div class="mb-3">
        <label for="company" class="form-label">Company Name *</label>
        <input type="text" class="form-control" id="company" name="company" value="<?= htmlspecialchars($lead->company) ?>" required>
    </div>
    <div class="mb-3">
        <label for="contact_name" class="form-label">Contact Person *</label>
        <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?= htmlspecialchars($lead->contact_name) ?>" required>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone Number *</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($lead->phone) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($lead->email ?? '') ?>">
        </div>
    </div>

    <div class="mb-3">
        <label for="website" class="form-label">Website URL</label>
        <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($lead->website ?? '') ?>" placeholder="https://example.com">
    </div>

    <div class="mb-3">
        <label for="background_info" class="form-label">Background Info / Context</label>
        <textarea class="form-control" id="background_info" name="background_info" rows="3" placeholder="Any specific context for the AI..."><?= htmlspecialchars($lead->background_info ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status">
            <?php foreach (Lead::getStatuses() as $status): ?>
                <option value="<?= $status ?>" <?= $lead->status === $status ? 'selected' : '' ?>>
                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update Lead</button>
        <a href="<?= $link->url('lead.index') ?>" class="btn btn-secondary">Cancel</a>
    </div>
    <?= \App\Auth\Csrf::input() ?>
</form>

<hr class="my-4">

<h4>Attachments</h4>
<div class="card mb-3">
    <div class="card-body">
        <form action="<?= $link->url('lead.upload') ?>" method="post" enctype="multipart/form-data" class="row g-3 align-items-center">
            <input type="hidden" name="id" value="<?= $lead->id ?>">
            <?= \App\Auth\Csrf::input() ?>
            <div class="col-auto">
                <input class="form-control" type="file" name="attachment" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Upload</button>
            </div>
        </form>

        <?php if (!empty($attachments)): ?>
            <ul class="list-group mt-3">
                <?php foreach ($attachments as $att): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= $link->asset('uploads/' . $att->path) ?>" target="_blank">
                            <i class="bi bi-file-earmark"></i> <?= htmlspecialchars($att->filename) ?>
                        </a>
                        <form action="<?= $link->url('lead.deleteAttachment') ?>" method="post" class="d-inline" onsubmit="return confirm('Delete file?');">
                            <input type="hidden" name="id" value="<?= $att->id ?>">
                            <?= \App\Auth\Csrf::input() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted mt-2 mb-0">No attachments yet.</p>
        <?php endif; ?>
    </div>
</div>
