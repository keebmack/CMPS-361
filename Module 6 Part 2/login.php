<?php

?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h2>Login Authentication</h2>
    </body>
    <form action="authentication.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Password</label>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</html>