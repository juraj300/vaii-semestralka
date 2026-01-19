<?php
/** @var array $leads */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
if (isset($no_layout) && $no_layout) {
    $view->setLayout(null);
}
?>
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
        <td>Next Call</td>
        <td>
            <div class="btn-group btn-group-sm">
                <a href="<?= $link->url('call.room', ['id' => $lead->id]) ?>" class="btn btn-success" title="Call"><i class="bi bi-telephone"></i> Call</a>
                <a href="<?= $link->url('lead.edit', ['id' => $lead->id]) ?>" class="btn btn-secondary" title="Edit">Edit</a>
                <form action="<?= $link->url('lead.delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                    <input type="hidden" name="id" value="<?= $lead->id ?>">
                    <?= \App\Auth\Csrf::input() ?>
                    <button type="submit" class="btn btn-danger" title="Delete">Del</button>
                </form>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
<?php if (empty($leads)): ?>
    <tr>
        <td colspan="6" class="text-center text-muted">No leads found.</td>
    </tr>
<?php endif; ?>
