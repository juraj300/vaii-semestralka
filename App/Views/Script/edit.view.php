<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \App\Models\Script $script */
?>

<h1>Edit Script</h1>

<form action="<?= $link->url('script.update') ?>" method="POST">
    <input type="hidden" name="id" value="<?= $script->id ?>">
    
    <div class="mb-3">
        <label for="name" class="form-label">Script Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($script->name) ?>" required>
    </div>
    <div class="mb-3">
        <label for="body" class="form-label">Script Body</label>
        <div class="form-text mb-1">Available variables: {{contact_name}}, {{company}}, {{agent_name}}</div>
        <textarea class="form-control" id="body" name="body" rows="10" required><?= htmlspecialchars($script->body) ?></textarea>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" <?= $script->is_default ? 'checked' : '' ?>>
        <label class="form-check-label" for="is_default">Set as Default Script</label>
    </div>

    <button type="submit" class="btn btn-primary">Update Script</button>
    <a href="<?= $link->url('script.index') ?>" class="btn btn-secondary">Cancel</a>
</form>
