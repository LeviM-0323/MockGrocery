<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$first_name = $_SESSION['first_name'] ?? '';

// Connect to the database
$db_host = 'db';
$db_user = 'root';
$db_pass = getenv('MYSQL_ROOT_PASSWORD') ?: '123';
$db_name = getenv('MYSQL_DATABASE') ?: 'grocery_db';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Fetch categories
$categories = [];
$res = $mysqli->query("SELECT id, name FROM categories ORDER BY name");
while ($row = $res->fetch_assoc()) {
    $categories[$row['id']] = $row['name'];
}

// Fetch products grouped by category
$products_by_cat = [];
$all_products = [];
$res = $mysqli->query("SELECT * FROM products ORDER BY category_id, name");
while ($row = $res->fetch_assoc()) {
    $products_by_cat[$row['category_id']][] = $row;
    $all_products[$row['id']] = $row;
}

// --- CART LOGIC ---
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$toast_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = $_POST['product_id'];
    if (isset($all_products[$pid])) {
        // Add or increment quantity
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['qty'] += 1;
        } else {
            $_SESSION['cart'][$pid] = [
                'id' => $pid,
                'name' => $all_products[$pid]['name'],
                'price_cents' => $all_products[$pid]['price_cents'],
                'uom' => $all_products[$pid]['uom'],
                'qty' => 1
            ];
        }
        $toast_message = "{$all_products[$pid]['name']} added to cart!";
    }
}

// Calculate subtotal/total
$subtotal_cents = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal_cents += $item['price_cents'] * $item['qty'];
}
$total_cents = $subtotal_cents; // Add tax/shipping if needed
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title>MockGrocery Frontend</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css"> 
        <style>
            .navbar-burger span { background-color: #48c774 !important; }
            .navbar-burger.is-active span { background-color: #257942 !important; }
            .side-menu {
                position: sticky;
                top: 90px;
                align-self: flex-start;
                z-index: 10;
            }
            @media (max-width: 1023px) {
                .side-menu {
                    display: none;
                }
            }
            #toast {
                display: none;
                position: fixed;
                top: 80px;
                right: 30px;
                z-index: 9999;
                min-width: 200px;
            }
        </style>
        <script>
            window.__TOAST_MESSAGE__ = <?= json_encode($toast_message) ?>;
        </script>
    </head>
    <body class="has-navbar-fixed-top">
        <!-- Toast Notification -->
        <div id="toast" class="notification is-success"></div>

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
                    <h1 class="title is-1">Shop for groceries</h1>
                    <h2 class="subtitle is-3">Select some items to start a cart</h2>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="columns">
                <!-- Side Navbar -->
                <div class="column is-2">
                    <aside class="menu side-menu">
                        <p class="menu-label">Departments</p>
                        <ul class="menu-list">
                            <?php foreach ($categories as $cat_id => $cat_name): ?>
                                <li><a href="#cat<?= $cat_id ?>"><?= htmlspecialchars($cat_name) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="menu-label">Cart</p>
                        <ul class="menu-list">
                            <li><a href="#review">Review items</a></li>
                            <li><a href="#checkout">Checkout</a></li>
                        </ul>
                    </aside>
                </div>
                <!-- Products by Category -->
                <div class="column is-10">
                    <?php foreach ($categories as $cat_id => $cat_name): ?>
                        <?php if (!empty($products_by_cat[$cat_id])): ?>
                            <h2 class="title is-3 mt-6 mb-4" id="cat<?= $cat_id ?>"><?= htmlspecialchars($cat_name) ?></h2>
                            <div class="columns is-multiline">
                                <?php foreach ($products_by_cat[$cat_id] as $product): ?>
                                    <div class="column is-one-quarter">
                                        <div class="box has-text-centered">
                                            <figure class="image is-128x128 mx-auto mb-2">
                                                <img src="<?= htmlspecialchars($product['image_url'] ?? 'https://placehold.co/128x128?text=No+Image') ?>" alt="<?= htmlspecialchars($product['name'] ?? 'Product') ?>">
                                            </figure>
                                            <h3 class="title is-5 mb-1"><?= htmlspecialchars($product['name'] ?? '') ?></h3>
                                            <?php if (!empty($product['brand'])): ?>
                                                <p class="has-text-grey mb-1"><?= htmlspecialchars($product['brand']) ?></p>
                                            <?php endif; ?>
                                            <p class="mb-1">
                                                <strong>
                                                    $<?= isset($product['price_cents']) ? number_format($product['price_cents']/100, 2) : '0.00' ?>
                                                </strong>
                                                <span class="has-text-grey-light"><?= htmlspecialchars($product['uom'] ?? '') ?></span>
                                            </p>
                                            <p class="mb-2"><?= htmlspecialchars($product['package_size'] ?? '') ?></p>
                                            <form method="post" action="#cat<?= $cat_id ?>">
                                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                <button class="button is-success is-small" type="submit">
                                                    Add to Cart
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Cart Review Section -->
                    <hr>
                    <h2 class="title is-4 mt-6 mb-4" id="review">Cart Review</h2>
                    <?php if (empty($_SESSION['cart'])): ?>
                        <p>Your cart is empty.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <li>
                                    <?= htmlspecialchars($item['name']) ?> 
                                    (<?= $item['qty'] ?> × $<?= number_format($item['price_cents']/100, 2) ?><?= $item['uom'] ? ' / ' . htmlspecialchars($item['uom']) : '' ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="mt-3"><strong>Subtotal:</strong> $<?= number_format($subtotal_cents/100, 2) ?></p>
                        <p><strong>Total:</strong> $<?= number_format($total_cents/100, 2) ?></p>
                    <?php endif; ?>
                    <h2 class="title is-4 mt-6 mb-4" id="checkout">Checkout</h2>
                    <p>Checkout functionality coming soon!</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer has-background has-text-white">
            <div class="content has-text-centered">
                <p><strong>MockGrocery</strong>&copy; <?= date('Y'); ?>. All rights reserved.</p>
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