<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'buyer') header("Location: buyer/dashboard.php");
    elseif ($_SESSION['role'] == 'seller') header("Location: seller/dashboard.php");
    else header("Location: admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0a0b10">
    <title>Login - Online Auction System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="/auction-system/assets/css/style.css">
</head>
<body class="auth-page">
<div class="auth-shell">
    <aside class="auth-aside">
        <a href="/auction-system/index.php" class="brand">
            <span class="brand-mark"><?php echo icon('gavel', 20); ?></span>
            <span class="brand-name">Auction <span>System</span></span>
        </a>
        <div>
            <h2>Bid smarter. <span>Win better.</span></h2>
            <p>A fast, secure marketplace where sellers reach real buyers and every bid is tracked live.</p>
            <ul class="auth-features">
                <li><span class="stat-icon"><?php echo icon('bolt', 18); ?></span> Live countdowns and instant bid updates</li>
                <li><span class="stat-icon blue"><?php echo icon('shield', 18); ?></span> Secure accounts for buyers, sellers and admins</li>
                <li><span class="stat-icon green"><?php echo icon('heart', 18); ?></span> Watchlist your favourite auctions</li>
            </ul>
        </div>
        <small>&copy; <?php echo date('Y'); ?> Online Auction System</small>
    </aside>
    <main class="auth-main">
        <div class="auth-card">
            <a href="/auction-system/index.php" class="brand auth-mobile-brand">
                <span class="brand-mark"><?php echo icon('gavel', 20); ?></span>
                <span class="brand-name">Auction <span>System</span></span>
            </a>
            <h1>Welcome back</h1>
            <p class="sub">Sign in to continue to your account.</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error" data-persist>Invalid email or password.</div>
            <?php endif; ?>
            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success">Registration successful! Please sign in.</div>
            <?php endif; ?>
            <?php
                // If redirected from logout, ensure fields are empty; otherwise preserve email on failed login
                $savedEmail = '';
                if (!isset($_GET['logout']) && isset($_GET['email'])) {
                    $savedEmail = htmlspecialchars($_GET['email']);
                }
            ?>

            <form id="loginForm" method="POST" action="../controllers/auth_controller.php?action=login" autocomplete="email" novalidate>
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group has-icon">
                        <span class="input-icon"><?php echo icon('mail', 18); ?></span>
                        <input type="email" name="email" id="email" placeholder="you@example.com" value="<?php echo $savedEmail; ?>" autocomplete="off">
                    </div>
                    <span class="error" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group has-icon">
                        <span class="input-icon"><?php echo icon('lock', 18); ?></span>
                        <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="off">
                        <button type="button" class="pw-toggle" data-toggle-password="password">Show</button>
                    </div>
                    <span class="error" id="passwordError"></span>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
            </form>

            <p class="switch">Don't have an account? <a href="register.php">Create one</a></p>
        </div>
    </main>
</div>
<script src="/auction-system/assets/js/main.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let valid = true;
            
            if (!email) {
                document.getElementById('emailError').textContent = 'Email is required';
                valid = false;
            } else if (!email.includes('@')) {
                document.getElementById('emailError').textContent = 'Invalid email format';
                valid = false;
            } else {
                document.getElementById('emailError').textContent = '';
            }
            
            if (!password) {
                document.getElementById('passwordError').textContent = 'Password is required';
                valid = false;
            } else {
                document.getElementById('passwordError').textContent = '';
            }
            
            if (!valid) e.preventDefault();
        });
    </script>
    <script>
        // If arrived via logout flag, clear any autofilled values (works around browser autofill)
        (function(){
            var fromLogout = <?php echo isset($_GET['logout']) ? 'true' : 'false'; ?>;
            if (fromLogout) {
                setTimeout(function(){
                    var e = document.getElementById('email');
                    var p = document.getElementById('password');
                    if (e) e.value = '';
                    if (p) p.value = '';
                }, 50);
            }
        })();
    </script>
</body>
</html>
