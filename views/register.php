<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
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
    <title>Register - Online Auction System</title>
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
            <h1>Create your account</h1>
            <p class="sub">Join as a buyer to bid, or as a seller to list items.</p>

            <?php
                $savedName = htmlspecialchars($_GET['name'] ?? '');
                $savedEmail = htmlspecialchars($_GET['email'] ?? '');
                $savedPhone = htmlspecialchars($_GET['phone'] ?? '');
                $savedRole = htmlspecialchars($_GET['role'] ?? 'buyer');
            ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error" data-persist><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="../controllers/auth_controller.php?action=register" novalidate>
                <div class="form-group">
                    <label>I want to</label>
                    <div class="role-options">
                        <label class="role-option">
                            <input type="radio" name="role" value="buyer" <?php echo $savedRole !== 'seller' ? 'checked' : ''; ?>>
                            <span><strong>Buy items</strong><small>Bid on live auctions</small></span>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="seller" <?php echo $savedRole === 'seller' ? 'checked' : ''; ?>>
                            <span><strong>Sell items</strong><small>Create and manage auctions</small></span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Full name</label>
                    <input type="text" name="name" id="name" placeholder="Your full name" value="<?php echo $savedName; ?>">
                    <span class="error" id="nameError"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="you@example.com" value="<?php echo $savedEmail; ?>">
                    <span class="error" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" placeholder="At least 10 digits" value="<?php echo $savedPhone; ?>">
                    <span class="error" id="phoneError"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Min 6 characters">
                        <span class="error" id="passwordError"></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat password">
                        <span class="error" id="confirmError"></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Create account</button>
            </form>

            <p class="switch">Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </main>
</div>
<script src="/auction-system/assets/js/main.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let name = document.getElementById('name').value;
            let email = document.getElementById('email').value;
            let phone = document.getElementById('phone').value;
            let password = document.getElementById('password').value;
            let confirm = document.getElementById('confirm_password').value;
            let valid = true;
            
            if (!name) {
                document.getElementById('nameError').textContent = 'Name is required';
                valid = false;
            } else {
                document.getElementById('nameError').textContent = '';
            }
            
            if (!email) {
                document.getElementById('emailError').textContent = 'Email is required';
                valid = false;
            } else if (!email.includes('@')) {
                document.getElementById('emailError').textContent = 'Invalid email format';
                valid = false;
            } else {
                document.getElementById('emailError').textContent = '';
            }
            
            if (!phone || phone.length < 10) {
                document.getElementById('phoneError').textContent = 'Valid phone number required (min 10 digits)';
                valid = false;
            } else {
                document.getElementById('phoneError').textContent = '';
            }
            
            if (!password || password.length < 6) {
                document.getElementById('passwordError').textContent = 'Password must be at least 6 characters';
                valid = false;
            } else {
                document.getElementById('passwordError').textContent = '';
            }
            
            if (password !== confirm) {
                document.getElementById('confirmError').textContent = 'Passwords do not match';
                valid = false;
            } else {
                document.getElementById('confirmError').textContent = '';
            }
            
            if (!valid) e.preventDefault();
        });
    </script>
</body>
</html>
