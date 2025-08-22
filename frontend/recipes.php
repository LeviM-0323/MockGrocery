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
                        Our Favourite Recipes
                    </h1>
                    <h2 class="subtitle is-3">
                        Don't know what to make? We got you.
                    </h2>
                </div>
            </div>
        </section>

        <!-- Recipes Content -->
        <section class="section">
            <div class="container">
                <div class="columns is-multiline">
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=128&q=80" alt="Avocado Toast">
                            </figure>
                            <h3 class="title is-5 mb-1">Avocado Toast Deluxe</h3>
                            <p class="mb-1">Sourdough bread, ripe avocados, cherry tomatoes, and a sprinkle of chili flakes.</p>
                            <p class="has-text-grey">Sponsored by: Fresh Avocados</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=128&q=80" alt="Berry Smoothie">
                            </figure>
                            <h3 class="title is-5 mb-1">Berry Power Smoothie</h3>
                            <p class="mb-1">Mixed berries, banana, Greek yogurt, and a splash of almond milk.</p>
                            <p class="has-text-grey">Sponsored by: Berry Best Farms</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1464306076886-debca5e8a6b0?auto=format&fit=crop&w=128&q=80" alt="Veggie Stir Fry">
                            </figure>
                            <h3 class="title is-5 mb-1">Quick Veggie Stir Fry</h3>
                            <p class="mb-1">Broccoli, bell peppers, carrots, and snap peas tossed in a savory sauce.</p>
                            <p class="has-text-grey">Sponsored by: Green Valley Produce</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=128&q=80" alt="Classic Omelette">
                            </figure>
                            <h3 class="title is-5 mb-1">Classic Omelette</h3>
                            <p class="mb-1">Farm eggs, cheddar cheese, spinach, and mushrooms for a hearty breakfast.</p>
                            <p class="has-text-grey">Sponsored by: Sunny Eggs</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=128&q=80" alt="Fruit Salad">
                            </figure>
                            <h3 class="title is-5 mb-1">Fresh Fruit Salad</h3>
                            <p class="mb-1">A medley of seasonal fruits, honey, and a squeeze of lime.</p>
                            <p class="has-text-grey">Sponsored by: Orchard Select</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <figure class="image is-128x128 mx-auto mb-2">
                                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=128&q=80" alt="Hearty Lentil Soup">
                            </figure>
                            <h3 class="title is-5 mb-1">Hearty Lentil Soup</h3>
                            <p class="mb-1">Lentils, carrots, celery, and tomatoes simmered with herbs and spices.</p>
                            <p class="has-text-grey">Sponsored by: Pantry Essentials</p>
                        </div>
                    </div>
                </div>
                <div class="has-text-centered mt-5">
                    <p class="is-size-5">Find all the ingredients for these recipes in our <a href="shop.php">shop</a>!</p>
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