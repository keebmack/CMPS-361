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

// Fetch all players and their stats
$query = "
    SELECT p.id AS player_id, p.name, p.position, p.jersey_number,
           ps.season_year, ps.games_played, ps.touchdowns, ps.yards, ps.receptions,
           ps.tackles, ps.sacks, ps.interceptions
    FROM players p
    LEFT JOIN player_stats ps ON p.id = ps.player_id
    ORDER BY p.name ASC, ps.season_year DESC;
";

$result = pg_query($conn, $query);
if (!$result) {
    die("Error fetching players and stats: " . pg_last_error());
}

// Fetch all rows
$playersStats = pg_fetch_all($result);

pg_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Players Stats</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>All Players Stats</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Jersey Number</th>
                <th>Season Year</th>
                <th>Games Played</th>
                <th>Touchdowns</th>
                <th>Yards</th>
                <th>Receptions</th>
                <th>Tackles</th>
                <th>Sacks</th>
                <th>Interceptions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($playersStats): ?>
                <?php foreach ($playersStats as $playerStat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($playerStat['name']); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['position']); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['jersey_number']); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['season_year'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['games_played'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['touchdowns'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['yards'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['receptions'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['tackles'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['sacks'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($playerStat['interceptions'] ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11">No players or stats found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="welcome.php" class="button">Return to Welcome Page</a>
</body>
</html>
