<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$first_name = $_SESSION['first_name'] ?? '';

$db_host = 'db';
$db_user = 'root';
$db_pass = getenv('MYSQL_ROOT_PASSWORD') ?: '123';
$db_name = getenv('MYSQL_DATABASE') ?: 'grocery_db';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$categories = [];
$res = $mysqli->query("SELECT id, name FROM categories ORDER BY name");
while ($row = $res->fetch_assoc()) {
    $categories[$row['id']] = $row['name'];
}

$products_by_cat = [];
$all_products = [];
$res = $mysqli->query("SELECT * FROM products ORDER BY category_id, name");
while ($row = $res->fetch_assoc()) {
    $products_by_cat[$row['category_id']][] = $row;
    $all_products[$row['id']] = $row;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$toast_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = $_POST['product_id'];
    $qty = isset($_POST['qty']) ? max(1, intval($_POST['qty'])) : 1;
    if (isset($all_products[$pid])) {
        // Add or increment quantity/weight
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$pid] = [
                'id' => $pid,
                'name' => $all_products[$pid]['name'],
                'price_cents' => $all_products[$pid]['price_cents'],
                'uom' => $all_products[$pid]['uom'],
                'qty' => $qty
            ];
        }
        $toast_message = "{$all_products[$pid]['name']} added to cart!";
    }
}

$subtotal_cents = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal_cents += $item['price_cents'] * $item['qty'];
}
$total_cents = $subtotal_cents * 1.13; 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title>MockGrocery Frontend</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
            .cart-drawer {
                position: fixed;
                top: 70px;
                right: 0;
                width: 340px;
                max-width: 90vw;
                height: calc(100vh - 80px);
                background: #fff;
                box-shadow: -2px 0 12px rgba(0,0,0,0.15);
                border-left: 1px solid #eee;
                z-index: 1200;
                transform: translateX(100%);
                transition: transform 0.3s cubic-bezier(.4,0,.2,1);
                overflow-y: auto;
                padding: 2rem 1.5rem 1.5rem 1.5rem;
            }
            .cart-drawer.open {
                transform: translateX(0);
            }
            .cart-drawer .close-btn {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
            }
            .cart-toggle-btn {
                position: fixed;
                top: 90px;
                right: 20px;
                z-index: 1300;
                background: #48c774;
                color: #fff;
                border: none;
                border-radius: 50%;
                width: 56px;
                height: 56px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                font-size: 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }
            @media (max-width: 1023px) {
                .cart-drawer, .cart-toggle-btn {
                    top: 60px;
                }
            }
        </style>
        <script>
            window.__TOAST_MESSAGE__ = <?= json_encode($toast_message) ?>;
        </script>
    </head>
    <body class="has-navbar-fixed-top">
        <!-- Toast Notification -->
        <div id="toast" class="notification is-success"></div>

        <!-- Cart Toggle Button -->
        <button id="cartToggleBtn" class="cart-toggle-btn" aria-label="Open cart">
            <span class="icon"><i class="fa-solid fa-shopping-cart"></i></span>
        </button>

        <!-- Cart Drawer -->
        <div id="cartDrawer" class="cart-drawer box has-background-dark">
            <button class="delete is-large close-btn" id="closeCartDrawer" aria-label="Close cart"></button>
            <h2 class="title is-4 mb-4 has-text-white" id="review">
                <span class="icon-text">
                    <span class="icon has-text-white"><i class="fas fa-shopping-cart"></i></span>
                    <span>Cart Review</span>
                </span>
            </h2>
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="notification is-warning is-dark">Your cart is empty.</div>
            <?php else: ?>
                <ul class="mb-4">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <li class="mb-2">
                            <span class="has-text-weight-semibold has-text-white"><?= htmlspecialchars($item['name']) ?></span>
                            <span class="tag is-success is-light ml-2"><?= $item['qty'] ?> <?= $item['uom'] ? htmlspecialchars($item['uom']) : '' ?></span>
                            <span class="has-text-grey-light ml-2">× $<?= number_format($item['price_cents']/100, 2) ?><?= $item['uom'] ? ' / ' . htmlspecialchars($item['uom']) : '' ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="content">
                    <p class="mb-2"><strong class="has-text-white">Subtotal:</strong> <span class="has-text-info has-text-weight-bold">$<?= number_format($subtotal_cents/100, 2) ?></span></p>
                    <p class="mb-2"><strong class="has-text-white">Tax (13%):</strong> <span class="has-text-info has-text-weight-bold">$<?= number_format(($subtotal_cents * 0.13)/100, 2) ?></span></p>
                    <p class="mb-4"><strong class="has-text-white">Total:</strong> <span class="has-text-primary has-text-weight-bold is-size-5">$<?= number_format($total_cents/100, 2) ?></span></p>
                </div>
            <?php endif; ?>
            <h2 class="title is-5 mt-5 mb-3 has-text-grey-lighter" id="checkout">Checkout</h2>
            <label class="label">Order type</label>
            <input class="input" type="radio" name="orderType" required>
            <div class="field">
                <label class="label">Pickup time</label>
                <div class="control">
                    <input class="input" type="datetime-local" name="pickupTime" required>
                </div>
            </div>
            <button class="button is-success" type="submit">Place order</button>
        </div>

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
        
        <!-- Main content -->
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
                                                <img src="<?= htmlspecialchars($product['image_url'] ?? 'https://placehold.co/128x128?text=No+Image') ?>" alt="<?= htmlspecialchars($product['name'] ?? 'Product') ?>" loading="lazy" width="128" height="128">
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
                                            <form method="post" action="#cat<?= $cat_id ?>" style="display:inline-block; width:100%;">
                                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                <?php if (strtoupper($product['uom']) === 'EA'): ?>
                                                    <input class="input is-small mb-2" type="number" name="qty" min="1" value="1" style="width:70px; display:inline-block;" placeholder="Qty">
                                                <?php elseif (strtoupper($product['uom']) === 'KG'): ?>
                                                    <input class="input is-small mb-2" type="number" name="qty" min="0.01" step="0.01" value="0.25" style="width:90px; display:inline-block;" placeholder="Kg">
                                                <?php endif; ?>
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