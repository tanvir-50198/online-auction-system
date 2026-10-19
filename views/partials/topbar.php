<?php
/*
 * Shared top bar. Expects (set by the role nav file):
 *   $navHome  - dashboard link
 *   $navItems - array of [file => label]
 *   $navRole  - 'buyer' | 'seller' | 'admin'
 */
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$navUserName = $_SESSION['user_name'] ?? 'User';
$navInitial  = strtoupper(function_exists('mb_substr') ? mb_substr($navUserName, 0, 1) : substr($navUserName, 0, 1));
?>
<header class="topbar" id="topbar">
    <div class="container topbar-inner">
        <a href="<?php echo $navHome; ?>" class="brand">
            <span class="brand-mark"><?php echo icon('gavel', 20); ?></span>
            <span class="brand-name">Auction <span>System</span></span>
        </a>

        <button class="nav-toggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="primaryNav" id="navToggle">
            <?php echo icon('menu', 22); ?>
        </button>

        <nav class="nav" id="primaryNav" aria-label="Main navigation">
            <?php foreach ($navItems as $file => $label): ?>
                <a href="<?php echo $file; ?>" class="<?php echo $currentPage === $file ? 'active' : ''; ?>"<?php echo $currentPage === $file ? ' aria-current="page"' : ''; ?>><?php echo e($label); ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="topbar-user">
            <div class="user-chip">
                <span class="avatar"><?php echo e($navInitial); ?></span>
                <span class="user-chip-text">
                    <strong><?php echo e($navUserName); ?></strong>
                    <small><?php echo e($navRole); ?></small>
                </span>
            </div>
            <a href="../../logout.php" class="btn btn-ghost btn-sm">Logout</a>
        </div>
    </div>
</header>
<main class="site-main container" id="main">
