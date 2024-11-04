<?php error_reporting(E_ALL) ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laboratorio 3</title>
</head>
<body>
<header>

</header>
<main>
    <form action="src/Controllers/AuthController.php?action=login" method="POST">
        <label for="user">Username:</label>
        <input type="text" id="user" name="user">
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="pass">
        <?php
        session_start();
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }
        ?>
        <input type="submit" value="submit">
    </form>
</main>
<footer></footer>
</body>
</html>
