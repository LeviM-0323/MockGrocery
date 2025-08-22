<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$first_name = $_SESSION['first_name'] ?? '';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title>MockGrocery Frontend</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css"> 
        <style>
            .navbar-burger span {
                background-color: #48c774 !important; 
            }
            .navbar-burger.is-active span {
                background-color: #257942 !important;
            }
        </style>
    </head>

    <body class="has-navbar-fixed-top">

        <!-- Navbar -->
        <nav class="navbar is-fixed-top" role="navigation" aria-label="Main navigation">
            <div class="navbar-brand">
                <a class="navbar-item has-text-weight-bold is-size-4" href="index.php">Home</a>
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="mainNavbar">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="mainNavbar" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="shop.php">Shop</a>
                    <a class="navbar-item" href="deals.php">Deals</a>
                    <a class="navbar-item" href="recipes.php">Recipes</a>
                    <a class="navbar-item" href="about.php">About</a>
                </div>
                <div class="navbar-end">
                    <?php if ($is_logged_in): ?>
                        <div class="navbar-item">Welcome, <?= htmlspecialchars($first_name) ?></div>
                        <a class="navbar-item" href="login.php?logout=1">Log out</a>
                    <?php else: ?>
                        <a class="navbar-item" href="signup.php">Sign up</a>
                        <a class="navbar-item" href="login.php">Log in</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="hero is-medium is-bold mt-6">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title is-1">
                        Weekly deals
                    </h1>
                    <h2 class="subtitle is-3">
                        Always great prices and selection!
                    </h2>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer has-background has-text-white">
            <div class="content has-text-centered">
                <p><strong>MockGrocery</strong>&copy; <?php echo date('Y'); ?>. All rights reserved.</p>
                <p>
                    <a href="privacy.php" class="has-text-white">Privacy Policy</a> &middot;
                    <a href="contact.php" class="has-text-white">Contact</a> &middot;
                    <a href="careers.php" class="has-text-white">Careers</a>
                </p>
            </div>
        </footer>

        <script src="https://kit.fontawesome.com/2b8e2e6e13.js" crossorigin="anonymous"></script>
        <script src="js/index.js"></script>
    </body>
</html>