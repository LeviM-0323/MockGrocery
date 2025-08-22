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
                        Weekly Deals
                    </h1>
                    <h2 class="subtitle is-3">
                        Always great prices and selection!
                    </h2>
                </div>
            </div>
        </section>

        <!-- Deals Content -->
        <section class="section">
            <div class="container">
                <div class="columns is-multiline">
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=128&q=80" alt="Fresh Strawberries">
                            </figure>
                            <h3 class="title is-5 mb-1">Fresh Strawberries</h3>
                            <p class="mb-1"><del>$3.99</del> <strong class="has-text-success">$2.49</strong> / pint</p>
                            <p class="has-text-grey">Save $1.50 this week only!</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=128&q=80" alt="Organic Avocados">
                            </figure>
                            <h3 class="title is-5 mb-1">Organic Avocados (3 pack)</h3>
                            <p class="mb-1"><del>$4.99</del> <strong class="has-text-success">$3.49</strong></p>
                            <p class="has-text-grey">Perfect for guacamole!</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1464306076886-debca5e8a6b0?auto=format&fit=crop&w=128&q=80" alt="Bakery Bread">
                            </figure>
                            <h3 class="title is-5 mb-1">Bakery Sourdough Bread</h3>
                            <p class="mb-1"><del>$5.49</del> <strong class="has-text-success">$3.99</strong></p>
                            <p class="has-text-grey">Freshly baked every morning.</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=128&q=80" alt="Milk">
                            </figure>
                            <h3 class="title is-5 mb-1">2% Milk (1 Gallon)</h3>
                            <p class="mb-1"><del>$3.29</del> <strong class="has-text-success">$2.49</strong></p>
                            <p class="has-text-grey">Dairy aisle special!</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=128&q=80" alt="Eggs">
                            </figure>
                            <h3 class="title is-5 mb-1">Large Brown Eggs (Dozen)</h3>
                            <p class="mb-1"><del>$2.99</del> <strong class="has-text-success">$1.99</strong></p>
                            <p class="has-text-grey">Farm fresh savings!</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=128&q=80" alt="Apples">
                            </figure>
                            <h3 class="title is-5 mb-1">Honeycrisp Apples (lb)</h3>
                            <p class="mb-1"><del>$2.49</del> <strong class="has-text-success">$1.49</strong></p>
                            <p class="has-text-grey">Crisp, sweet, and juicy!</p>
                        </div>
                    </div>
                </div>
                <div class="has-text-centered mt-5">
                    <p class="is-size-5">Check back every week for new deals and savings!</p>
                    <a href="shop.php" class="button is-success is-medium mt-3">Shop All Deals</a>
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