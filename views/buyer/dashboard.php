<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../config/database.php';
require_once '../../models/ListingModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../models/WatchlistModel.php';

requireRole('buyer');
$user_id = $_SESSION['user_id'];

closeExpiredListings($conn);
$categories = getAllCategories($conn);
$activeListings = getActiveListings($conn, 10);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/buyer_nav.php'; ?>

<section class="hero">
    <p class="eyebrow">Buyer dashboard</p>
    <h1>Welcome back, <?php echo e($_SESSION['user_name']); ?></h1>
    <p class="lead">Discover live auctions, place smarter bids and track everything you are watching in one place.</p>

    <div class="search-bar">
        <div class="input-group has-icon">
            <span class="input-icon"><?php echo icon('search', 18); ?></span>
            <input type="text" id="searchInput" placeholder="Search auctions by title or description..." autocomplete="off">
        </div>
        <select id="categoryFilter" aria-label="Category">
            <option value="">All categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button id="searchBtn" class="btn btn-primary" type="button"><?php echo icon('search', 16); ?> Search</button>
    </div>
</section>

<div id="searchResults" style="margin-top:28px"></div>

<div id="defaultListings">
    <div class="section-head">
        <h2>Live auctions</h2>
        <a href="browse.php" class="btn btn-ghost btn-sm">View all <?php echo icon('arrow', 14); ?></a>
    </div>

    <?php if (empty($activeListings)): ?>
        <div class="empty-state">
            <span class="empty-icon"><?php echo icon('package', 26); ?></span>
            <h3>No live auctions right now</h3>
            <p>New items are added by sellers all the time. Please check back soon.</p>
        </div>
    <?php else: ?>
        <div class="listings-grid">
            <?php foreach ($activeListings as $listing): ?>
                <?php echo renderListingCard($listing); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="../../assets/js/buyer.js"></script>
<?php include '../partials/footer.php'; ?>
