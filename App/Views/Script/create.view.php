<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var array|null $errors */
?>

<h1>Create Script</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= $link->url('script.store') ?>" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Script Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="body" class="form-label">Script Body</label>
        <div class="form-text mb-1">Available variables: {{contact_name}}, {{company}}, {{agent_name}}</div>
        <textarea class="form-control" id="body" name="body" rows="10" required></textarea>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_default" name="is_default">
        <label class="form-check-label" for="is_default">Set as Default Script</label>
    </div>

    <button type="submit" class="btn btn-primary">Save Script</button>
    <a href="<?= $link->url('script.index') ?>" class="btn btn-secondary">Cancel</a>
</form>
