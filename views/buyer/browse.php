<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
requireRole('buyer');

closeExpiredListings($conn);
$categories = getAllCategories($conn);
$activeListings = getActiveListings($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<div class="page-head">
    <div>
        <h1>Browse auctions</h1>
        <p><?php echo count($activeListings); ?> live auction<?php echo count($activeListings) === 1 ? '' : 's'; ?> available</p>
    </div>
</div>

<div class="search-bar" style="margin:0 0 28px">
    <div class="input-group has-icon">
        <span class="input-icon"><?php echo icon('search', 18); ?></span>
        <input type="text" id="searchInput" placeholder="Search by title or description..." autocomplete="off">
    </div>
    <select id="categoryFilter" aria-label="Category">
        <option value="">All categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <button id="searchBtn" class="btn btn-primary" type="button"><?php echo icon('search', 16); ?> Search</button>
</div>

<div id="defaultListings">
    <?php if (empty($activeListings)): ?>
        <div class="empty-state">
            <span class="empty-icon"><?php echo icon('package', 26); ?></span>
            <h3>No live auctions right now</h3>
            <p>Please check back soon.</p>
        </div>
    <?php else: ?>
        <div class="listings-grid">
            <?php foreach ($activeListings as $listing): ?>
                <?php echo renderListingCard($listing); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<div id="searchResults"></div>

<script src="../../assets/js/buyer.js"></script>
<?php include '../partials/footer.php'; ?>
