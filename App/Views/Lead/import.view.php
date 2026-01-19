<?php
/** @var array|null $errors */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="fade-in">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Import Leads from CSV</h4>
        </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info py-2">
                    <h5>CSV Template Requirements:</h5>
                    <p class="small mb-2">Your CSV file should have a header row with the following exact names (lowercase):</p>
                    <code class="d-block p-2 border rounded mb-3" style="background: rgba(0,0,0,0.2); color: var(--primary-hover);">
                        company,contact_name,phone,email
                    </code>
                    <p class="small mb-0"><strong>Note:</strong> Company, Contact Name, and Phone are mandatory fields. Rows with missing mandatory data will be skipped.</p>
                </div>

                <form action="<?= $link->url('lead.import') ?>" method="post" enctype="multipart/form-data" class="mt-4">
                    <?= \App\Auth\Csrf::input() ?>
                    <div class="mb-4">
                        <label for="csv" class="form-label fw-bold">Select CSV File</label>
                        <input class="form-control form-control-lg" type="file" id="csv" name="csv" accept=".csv" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= $link->url('lead.index') ?>" class="btn btn-outline-secondary">Back to Leads</a>
                        <button type="submit" class="btn btn-primary btn-lg">Start Import</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4 border-info">
            <div class="card-body">
                <h5 class="text-info"><i class="bi bi-question-circle"></i> How to prepare your CSV?</h5>
                <p class="small text-muted mb-0">
                    You can use Excel or Google Sheets to prepare your list. Just make sure to save it as <strong>CSV (Comma Separated Values)</strong>. 
                    The first row must contain the headers as shown above.
                </p>
            </div>
        </div>
    </div>
</div>
