<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database configuration
require_once('db_config.php');

// Create database connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Error: Unable to connect to the database.");
}

// Get the player's ID from the URL
$playerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate the ID
if ($playerId <= 0) {
    echo "Invalid player ID.";
    exit;
}

// Query to fetch player details and stats
$query = "
    SELECT p.id AS player_id, p.name, p.position, p.jersey_number, 
           ps.season_year, ps.games_played, ps.touchdowns, ps.yards, ps.receptions,
           ps.tackles, ps.sacks, ps.interceptions
    FROM players p
    LEFT JOIN player_stats ps ON p.id = ps.player_id
    WHERE p.id = $1
";


$result = pg_query_params($conn, $query, array($playerId));

if (!$result || pg_num_rows($result) === 0) {
    echo "Player not found.";
    exit;
}

// Fetch combined player and stats data
$playerStats = pg_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($playerStats['name']); ?> - Stats</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($playerStats['name']); ?></h1>
    <p><strong>Position:</strong> <?php echo htmlspecialchars($playerStats['position']); ?></p>
    <p><strong>Jersey Number:</strong> <?php echo htmlspecialchars($playerStats['jersey_number']); ?></p>

    <!-- Additional stats -->
    <h2>Stats</h2>
    <ul>
        <li><strong>Season Year:</strong> <?php echo htmlspecialchars($playerStats['season_year'] ?? 'N/A'); ?></li>
        <li><strong>Games Played:</strong> <?php echo htmlspecialchars($playerStats['games_played'] ?? 'N/A'); ?></li>
        <li><strong>Touchdowns:</strong> <?php echo htmlspecialchars($playerStats['touchdowns'] ?? 'N/A'); ?></li>
        <li><strong>Yards:</strong> <?php echo htmlspecialchars($playerStats['yards'] ?? 'N/A'); ?></li>
        <li><strong>Receptions:</strong> <?php echo htmlspecialchars($playerStats['receptions'] ?? 'N/A'); ?></li>
        <li><strong>Tackles:</strong> <?php echo htmlspecialchars($playerStats['tackles'] ?? 'N/A'); ?></li>
        <li><strong>Sacks:</strong> <?php echo htmlspecialchars($playerStats['sacks'] ?? 'N/A'); ?></li>
        <li><strong>Interceptions:</strong> <?php echo htmlspecialchars($playerStats['interceptions'] ?? 'N/A'); ?></li>
    </ul>
    <div class="navigation">
        <a href="addstats.php?player_id=<?php echo htmlspecialchars($playerStats['player_id']); ?>" class="button">Edit Stats</a>
        <a href="steelers_roster.php" class="button">Back to Roster</a>
        <a href="welcome.php" class="button">Return to Welcome Page</a>
        <a href="authentication.php?logout=true" class="button">Logout</a>
    </div>
</body>
</html>
<?php
// Close the database connection
pg_close($conn);
?>
