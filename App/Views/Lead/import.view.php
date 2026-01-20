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

                <div class="csv-instructions p-4 rounded mb-4 shadow" style="background: rgba(13, 110, 253, 0.05); border: 1px solid rgba(13, 110, 253, 0.2);">
                    <h5 class="text-primary mb-3"><i class="bi bi-info-circle"></i> CSV Template Requirements</h5>
                    <p class="small text-muted mb-3">Your CSV file must have a header row with these exact names. Order doesn't matter, but the names must match:</p>
                    
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-dark table-hover mb-0" style="background: rgba(0,0,0,0.3);">
                            <thead class="bg-dark">
                                <tr>
                                    <th class="text-primary">Column (Header)</th>
                                    <th>Required?</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody class="small text-white-50">
                                <tr><td class="text-white"><code>company</code></td><td><span class="text-warning fw-bold">Yes</span></td><td>Name of the business</td></tr>
                                <tr><td class="text-white"><code>contact_name</code></td><td><span class="text-warning fw-bold">Yes</span></td><td>Full name of the person</td></tr>
                                <tr><td class="text-white"><code>phone</code></td><td><span class="text-warning fw-bold">Yes</span></td><td>Phone number</td></tr>
                                <tr><td class="text-white"><code>email</code></td><td>No</td><td>Contact email address</td></tr>
                                <tr><td class="text-white"><code>website</code></td><td>No</td><td>Company website (starts with http)</td></tr>
                                <tr><td class="text-white"><code>background_info</code></td><td>No</td><td>Context for AI research</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="small mb-2 text-muted"><strong>Example Row:</strong></p>
                    <code class="d-block p-3 border rounded mb-0" style="background: rgba(0,0,0,0.4); color: #7dd3fc; font-family: 'Courier New', monospace;">
                        company,contact_name,phone,email,website,background_info<br>
                        "Google","Larry Page","+1 234 567","larry@google.com","https://google.com","Tech giant in Mountain View"
                    </code>
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
