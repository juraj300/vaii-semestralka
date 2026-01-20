<?php
/** @var array $leads */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="fade-in">
    <div class="row align-items-center mb-4">
        <div class="col-sm-6 mb-3 mb-sm-0">
            <h1 class="mb-0">My Leads</h1>
        </div>
        <div class="col-sm-6 text-sm-end d-flex gap-2 justify-content-sm-end">
            <a href="<?= $link->url('lead.import') ?>" class="btn btn-sm btn-outline-primary flex-fill flex-sm-none">
                <i class="bi bi-file-earmark-spreadsheet"></i> Import
            </a>
            <a href="<?= $link->url('lead.create') ?>" class="btn btn-sm btn-primary flex-fill flex-sm-none">
                <i class="bi bi-plus-lg"></i> Add New
            </a>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="lead-search" class="form-control" placeholder="Search company or contact..." onkeyup="window.liveSearch(this.value)">
        </div>
    </div>
</div>

<div class="card shadow-sm mt-3">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th>Company</th>
                <th>Contact Name</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Next Follow-up</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="leads-table-body">
            <?php include "index_rows.view.php"; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const LEAD_SEARCH_URL = "<?= $link->url('lead.search') ?>";
</script>
