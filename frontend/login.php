<?php
session_start();
$success = false;
$error = '';

$db_host = 'db'; // Docker Compose service name
$db_user = 'root';
$db_pass = getenv('MYSQL_ROOT_PASSWORD') ?: '123';
$db_name = getenv('MYSQL_DATABASE') ?: 'grocery_db';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($mysqli->connect_error) {
            $error = "Database connection failed: " . $mysqli->connect_error;
        } else {
            $stmt = $mysqli->prepare("SELECT id, first_name, password_hash FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 1) {
                $stmt->bind_result($user_id, $first_name, $password_hash);
                $stmt->fetch();
                if (password_verify($password, $password_hash)) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['first_name'] = $first_name;
                    $success = true;
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "No account found with that email.";
            }
            $stmt->close();
            $mysqli->close();
        }
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

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
                        Login page
                    </h1>
                </div>
            </div>
        </section>
        
        <!-- Log in form -->
        <section class="section">
            <div class="container" style="max-width: 500px;">
                <?php if ($success): ?>
                    <div class="notification is-success">Log in successful! Welcome, <?= htmlspecialchars($first_name) ?>. <a href="shop.php">Start shopping</a>.</div>
                <?php elseif ($error): ?>
                    <div class="notification is-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (!$is_logged_in): ?>
                <form method="post" action="login.php" autocomplete="off">
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input class="input" type="password" name="password" required>
                        </div>
                    </div>
                    <div class="field mt-4">
                        <div class="control">
                            <button class="button is-success is-fullwidth" type="submit">Log in</button>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
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