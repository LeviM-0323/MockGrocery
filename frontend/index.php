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
                        Welcome to MockGrocery!
                    </h1>
                    <h2 class="subtitle is-3">
                        Groceries delivered to your door in 1 hour or less.
                    </h2>
                    <a href="shop.php" class="button is-primary is-large mt-4">Start Shopping</a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="section">
            <div class="container">
                <div class="columns is-multiline is-variable is-8">
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <span class="icon is-large has-text-success">
                                <i class="fas fa-leaf fa-3x"></i>
                            </span>
                            <h3 class="title is-4 mt-2">Fresh Produce</h3>
                            <p>We source the freshest fruits and vegetables daily from local farms and trusted suppliers.</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <span class="icon is-large has-text-success">
                                <i class="fas fa-truck fa-3x"></i>
                            </span>
                            <h3 class="title is-4 mt-2">Fast Delivery</h3>
                            <p>Get your groceries delivered to your doorstep in as little as one hour, every day of the week.</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box has-text-centered">
                            <span class="icon is-large has-text-success">
                                <i class="fas fa-tags fa-3x"></i>
                            </span>
                            <h3 class="title is-4 mt-2">Great Deals</h3>
                            <p>Enjoy weekly specials, exclusive online offers, and loyalty rewards for every purchase.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="section has-background-light">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-half">
                        <figure class="image is-4by3">
                            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80" alt="Groceries">
                        </figure>
                    </div>
                    <div class="column is-half has-text-dark">
                        <h2 class="title is-3 has-text-dark">Why Choose MockGrocery?</h2>
                        <ul class="content has-text-dark">
                            <li><strong class="has-text-dark">Wide Selection:</strong> From pantry staples to specialty items, we have it all.</li>
                            <li><strong class="has-text-dark">Easy Ordering:</strong> Simple, intuitive website and mobile experience.</li>
                            <li><strong class="has-text-dark">Safe & Secure:</strong> Contactless delivery and secure payment options.</li>
                            <li><strong class="has-text-dark">Customer Support:</strong> Friendly help available 7 days a week.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="section">
            <div class="container">
                <h2 class="title is-3 has-text-centered mb-6">What Our Customers Say</h2>
                <div class="columns is-centered">
                    <div class="column is-one-third">
                        <div class="box">
                            <p class="is-italic">"MockGrocery made my life so much easier! The produce is always fresh and delivery is super fast."</p>
                            <p class="has-text-weight-bold mt-2">- Sarah J.</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box">
                            <p class="is-italic">"I love the weekly deals and the rewards program. I save money every time I shop!"</p>
                            <p class="has-text-weight-bold mt-2">- Mike D.</p>
                        </div>
                    </div>
                    <div class="column is-one-third">
                        <div class="box">
                            <p class="is-italic">"Great selection and excellent customer service. Highly recommend to anyone!"</p>
                            <p class="has-text-weight-bold mt-2">- Priya K.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Signup -->
        <section class="section has-background-light">
            <div class="container has-text-centered has-text-dark">
                <h2 class="title is-4 has-text-dark">Subscribe to Our Newsletter</h2>
                <p class="mb-4 has-text-dark">Get the latest deals, recipes, and updates delivered to your inbox.</p>
                <form class="field has-addons has-addons-centered" style="max-width: 400px; margin: 0 auto;">
                    <div class="control is-expanded">
                        <input class="input" type="email" placeholder="Enter your email" required>
                    </div>
                    <div class="control">
                        <button class="button is-success" type="submit">Subscribe</button>
                    </div>
                </form>
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