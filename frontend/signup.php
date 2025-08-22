<?php
$success = false;
$error = '';

$db_host = 'db'; // Docker Compose service name
$db_user = 'root';
$db_pass = getenv('MYSQL_ROOT_PASSWORD') ?: '123';
$db_name = getenv('MYSQL_DATABASE') ?: 'grocery_db';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$first || !$last || !$email || !$password || !$confirm) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($mysqli->connect_error) {
            $error = "Database connection failed: " . $mysqli->connect_error;
        } else {
            $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "An account with this email already exists.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, phone_num, password_hash) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $first, $last, $email, $phone, $password_hash);
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
            $stmt->close();
            $mysqli->close();
        }
    }
}
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
                    <a class="navbar-item" href="signup.php">Sign up</a>
                    <a class="navbar-item" href="login.php">Log in</a>
                </div>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="hero is-medium is-bold mt-6">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title is-1">
                        Sign up
                    </h1>
                </div>
            </div>
        </section>

        <!-- Signup form -->
        <section class="section">
            <div class="container" style="max-width: 500px;">
                <?php if ($success): ?>
                    <div class="notification is-success">Registration successful! You can now <a href="login.php">log in</a>.</div>
                <?php elseif ($error): ?>
                    <div class="notification is-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="post" action="signup.php" autocomplete="off">
                    <div class="field">
                        <label class="label">First Name</label>
                        <div class="control">
                            <input class="input" type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Last Name</label>
                        <div class="control">
                            <input class="input" type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Phone Number <span class="has-text-grey-light">(optional)</span></label>
                        <div class="control">
                            <input class="input" type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input class="input" type="password" name="password" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Confirm Password</label>
                        <div class="control">
                            <input class="input" type="password" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="field mt-4">
                        <div class="control">
                            <button class="button is-success is-fullwidth" type="submit">Register</button>
                        </div>
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