<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <img src="images/Pittsburgh_Steelers_logo.svg" alt="Steelers Logo" class="logo">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </header>
        <p class="success-message">You have successfully logged into the app.</p>
    <div class="navigation">
        <a href="allplayerstats.php" class="button">Steelers Roster and Stats</a>
        <a href="addplayer.php" class="button">Add Player</a>
        <a href="steelers_roster.php" class="button">Steelers Roster</a><br><br>
        <a href="authentication.php?logout=true" class="button">Logout</a>
    </div>
</body>
</html>
