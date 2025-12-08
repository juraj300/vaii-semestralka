<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var array|null $errors */
?>

<h1>Add New Lead</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= $link->url('lead.store') ?>" method="POST" class="mt-4" novalidate> <!-- Client validation added later via JS -->
    <div class="mb-3">
        <label for="company" class="form-label">Company Name *</label>
        <input type="text" class="form-control" id="company" name="company" required>
    </div>
    <div class="mb-3">
        <label for="contact_name" class="form-label">Contact Person *</label>
        <input type="text" class="form-control" id="contact_name" name="contact_name" required>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone Number *</label>
            <input type="tel" class="form-control" id="phone" name="phone" required placeholder="+421...">
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Save Lead</button>
        <a href="<?= $link->url('lead.index') ?>" class="btn btn-secondary">Cancel</a>
    </div>
</form>
