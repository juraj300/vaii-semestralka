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
</form>
