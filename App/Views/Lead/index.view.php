<?php
/** @var array $leads */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="row mb-3">
    <div class="col-6">
        <h1>My Leads</h1>
    </div>
    <div class="col-6 text-end">
        <a href="<?= $link->url('lead.create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Lead
        </a>
    </div>
</div>

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
        <tbody>
        <?php foreach ($leads as $lead): ?>
            <tr>
                <td><?= htmlspecialchars($lead->company) ?></td>
                <td><?= htmlspecialchars($lead->contact_name) ?></td>
                <td>
                    <a href="tel:<?= htmlspecialchars($lead->phone) ?>"><?= htmlspecialchars($lead->phone) ?></a>
                </td>
                <td>
                    <?php
                    $badgeClass = match ($lead->status) {
                        'new' => 'bg-secondary',
                        'contacted' => 'bg-info',
                        'interested' => 'bg-warning',
                        'closed_won' => 'bg-success',
                        'closed_lost' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($lead->status) ?></span>
                </td>
                <td>Wait for Call Impl</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="<?= $link->url('call.room', ['id' => $lead->id]) ?>" class="btn btn-success" title="Call"><i class="bi bi-telephone"></i> Call</a>
                        <a href="<?= $link->url('lead.edit', ['id' => $lead->id]) ?>" class="btn btn-secondary" title="Edit">Edit</a>
                        <form action="<?= $link->url('lead.delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="id" value="<?= $lead->id ?>">
                            <button type="submit" class="btn btn-danger" title="Delete">Del</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($leads)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No leads found. Start by adding one!</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
