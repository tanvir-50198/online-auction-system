<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/BidModel.php';
requireRole('buyer');

$myBids = getUserBids($conn, $_SESSION['user_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<div class="page-head">
    <div>
        <h1>My bids</h1>
        <p>Every auction you have bid on, and where you stand.</p>
    </div>
    <div class="page-head-actions"><a href="browse.php" class="btn btn-primary"><?php echo icon('search', 16); ?> Find more auctions</a></div>
</div>

<?php if (empty($myBids)): ?>
    <div class="empty-state">
        <span class="empty-icon"><?php echo icon('gavel', 26); ?></span>
        <h3>You have not placed any bids yet</h3>
        <p>Browse live auctions and place your first bid.</p>
        <a href="browse.php" class="btn btn-primary">Browse auctions</a>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Item</th><th>Your bid</th><th>Highest bid</th><th>Status</th><th class="right">Action</th></tr></thead>
            <tbody>
            <?php foreach ($myBids as $bid): ?>
                <?php
                $isWinner  = ($bid['winner_id'] == $_SESSION['user_id'] && $bid['status'] == 'closed');
                $isLeading = ($bid['highest_bid'] == $bid['bid_amount'] && $bid['status'] == 'active');
                if ($bid['status'] == 'closed') {
                    $badge = $isWinner ? statusBadge('won', 'Won') : statusBadge('lost', 'Lost');
                } else {
                    $badge = $isLeading ? statusBadge('leading', 'Leading') : statusBadge('outbid', 'Outbid');
                }
                ?>
                <tr>
                    <td class="cell-title"><?php echo e($bid['title']); ?></td>
                    <td class="num">$<?php echo number_format($bid['bid_amount'], 2); ?></td>
                    <td class="num">$<?php echo number_format($bid['highest_bid'], 2); ?></td>
                    <td><?php echo $badge; ?></td>
                    <td class="right"><a href="details.php?id=<?php echo (int)$bid['listing_id']; ?>" class="btn btn-sm">View</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>
