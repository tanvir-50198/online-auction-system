<?php
require_once '../../includes/session.php';
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../models/ListingModel.php';
require_once '../../models/BidModel.php';
requireRole('admin');

$totalUsers = getTotalUsers($conn);
$totalSellers = count(getAllUsers($conn, 'seller'));
$totalBuyers = count(getAllUsers($conn, 'buyer'));
$totalListings = getTotalListings($conn);
$totalBids = getTotalBids($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/admin_nav.php'; ?>

<section class="hero">
    <p class="eyebrow">Administrator</p>
    <h1>Welcome, <?php echo e($_SESSION['user_name']); ?></h1>
    <p class="lead">See how the platform is performing and manage users, listings, categories and admins from one place.</p>
</section>

<div class="section-head"><h2>Platform overview</h2></div>
<div class="stats">
    <a href="users.php" class="stat-card">
        <span class="stat-icon"><?php echo icon('users', 22); ?></span>
        <div><div class="stat-value"><?php echo (int)$totalUsers; ?></div><div class="stat-label">Total users</div></div>
    </a>
    <a href="users.php" class="stat-card">
        <span class="stat-icon blue"><?php echo icon('package', 22); ?></span>
        <div><div class="stat-value"><?php echo (int)$totalSellers; ?></div><div class="stat-label">Sellers</div></div>
    </a>
    <a href="users.php" class="stat-card">
        <span class="stat-icon green"><?php echo icon('heart', 22); ?></span>
        <div><div class="stat-value"><?php echo (int)$totalBuyers; ?></div><div class="stat-label">Buyers</div></div>
    </a>
    <a href="listings.php" class="stat-card">
        <span class="stat-icon"><?php echo icon('layers', 22); ?></span>
        <div><div class="stat-value"><?php echo (int)$totalListings; ?></div><div class="stat-label">Listings</div></div>
    </a>
    <div class="stat-card">
        <span class="stat-icon red"><?php echo icon('gavel', 22); ?></span>
        <div><div class="stat-value"><?php echo (int)$totalBids; ?></div><div class="stat-label">Total bids</div></div>
    </div>
</div>

<div class="section-head"><h2>Quick actions</h2></div>
<div class="page-head-actions">
    <a href="users.php" class="btn">Manage users</a>
    <a href="listings.php" class="btn">Review listings</a>
    <a href="categories.php" class="btn">Edit categories</a>
    <a href="create_admin.php" class="btn btn-primary"><?php echo icon('shield', 16); ?> Create admin</a>
</div>

<?php include '../partials/footer.php'; ?>
