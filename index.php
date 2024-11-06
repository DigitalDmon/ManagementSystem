<?php error_reporting(E_ALL) ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Laboratorio 3</title>
</head>
<body>
<header>

</header>
<main class="content-container">
    <div class="login-container">
        <h1 class="login-title">Login</h1>
        <form action="src/Controllers/AuthController.php?action=login" method="POST" id="login-form" class="login-form">
            <div class="form-group">
                <label for="user">Username:</label>
                <input type="text" id="user" name="user" required>
            </div>
            <div class="form-group">
                <label for="pass">Password:</label>
                <input type="password" id="pass" name="pass" required>
            </div>
            <?php
            session_start();
            if (isset($_SESSION['error_message'])) {
                echo "<p class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</p>";
                unset($_SESSION['error_message']);
            }
            ?>
            <input type="submit" value="Login" class="submit-button">
        </form>
    </div>
</main>
<footer>
    <p>&copy; <?php echo date('Y'); ?> My application. All rights reserved.</p>
</footer>
</body>
</html>
