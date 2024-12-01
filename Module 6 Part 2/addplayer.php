<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

/** @var string $host */
/** @var string $port */
/** @var string $dbname */
/** @var string $user */
/** @var string $password */
/** @phpstan-ignore-next-line */
require_once('db_config.php');

// Create database connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if ($conn === false) {
    die("Error: Unable to connect to the database.");
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerName = isset($_POST['player_name']) ? trim($_POST['player_name']) : '';
    $position = isset($_POST['position']) ? trim($_POST['position']) : '';
    $jerseyNumber = isset($_POST['jersey_number']) ? (int)$_POST['jersey_number'] : 0;

    if (isset($playerName[0]) && isset($position[0]) && $jerseyNumber > 0) {
        // Insert new player into the database
        $query = "
            INSERT INTO players (name, position, jersey_number)
            VALUES ($1, $2, $3)
        ";

        $result = pg_query_params($conn, $query, [$playerName, $position, $jerseyNumber]);

        if ($result !== false) {
            $message = "Player successfully added: " . htmlspecialchars($playerName);
        } else {
            $message = "Error adding player: " . htmlspecialchars(pg_last_error($conn));
        }
    } else {
        $message = "Please fill out all fields correctly.";
    }
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Player</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <img src="images/Pittsburgh_Steelers_logo.svg" alt="Steelers Logo" class="logo">
        <h1>Add New Player</h1>
    </header>
    <?php if ($message !== ''): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="addplayer.php" method="post">
        <label for="player_name">Player Name:</label>
        <input type="text" name="player_name" id="player_name" required><br><br>

        <label for="position">Position:</label>
        <input type="text" name="position" id="position" required><br><br>

        <label for="jersey_number">Jersey Number:</label>
        <input type="number" name="jersey_number" id="jersey_number" required><br><br>

        <button type="submit" class="button">Add Player</button>
    </form>
    <div class="navigation">
        <a href="welcome.php" class="button">Return to Welcome Page</a>
        <a href="steelers_roster.php" class="button">Steelers Roster</a>
        <a href="allplayerstats.php" class="button">Steelers Roster and Stats</a>
    </div>
</body>
</html>

