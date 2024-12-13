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

// Get the player ID from the URL
$playerId = isset($_GET['player_id']) ? (int)$_GET['player_id'] : 0;

if ($playerId <= 0) {
    echo "Invalid player ID.";
    exit;
}

// Fetch the player's name
$nameQuery = "SELECT name FROM players WHERE id = $1";
$nameResult = pg_query_params($conn, $nameQuery, [$playerId]);
if ($nameResult === false || pg_num_rows($nameResult) === 0) {
    echo "Player not found.";
    exit;
}

$playerName = is_string(pg_fetch_result($nameResult, 0, 'name')) ? 
              htmlspecialchars(pg_fetch_result($nameResult, 0, 'name')) : '';

// Fetch existing stats for the player
$query = "SELECT * FROM player_stats WHERE player_id = $1";
$result = pg_query_params($conn, $query, [$playerId]);
if ($result === false) {
    die("Error fetching player stats: " . pg_last_error());
}

/** @phpstan-ignore-next-line */
$playerStats = pg_fetch_assoc($result) ?: [
    'season_year' => date('Y'),
    'games_played' => 0,
    'touchdowns' => 0,
    'yards' => 0.0,
    'receptions' => 0,
    'tackles' => 0,
    'sacks' => 0.0,
    'interceptions' => 0
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seasonYear = (int)$_POST['season_year'];
    $gamesPlayed = (int)$_POST['games_played'];
    $touchdowns = (int)$_POST['touchdowns'];
    $yards = (float)$_POST['yards'];
    $receptions = (int)$_POST['receptions'];
    $tackles = (int)$_POST['tackles'];
    $sacks = (float)$_POST['sacks'];
    $interceptions = (int)$_POST['interceptions'];

    $query = "
        INSERT INTO player_stats (player_id, season_year, games_played, touchdowns, yards, receptions, tackles, sacks, interceptions)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
        ON CONFLICT (player_id, season_year)
        DO UPDATE SET
            games_played = EXCLUDED.games_played,
            touchdowns = EXCLUDED.touchdowns,
            yards = EXCLUDED.yards,
            receptions = EXCLUDED.receptions,
            tackles = EXCLUDED.tackles,
            sacks = EXCLUDED.sacks,
            interceptions = EXCLUDED.interceptions;
    ";

    $result = pg_query_params($conn, $query, [
        $playerId, $seasonYear, $gamesPlayed, $touchdowns, $yards, $receptions, $tackles, $sacks, $interceptions
    ]);

    if ($result !== false) {
        header("Location: player_stats.php?id=$playerId");
        exit;
    } else {
        echo "Error updating stats: " . pg_last_error();
    }
}

pg_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stats</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <img src="images/Pittsburgh_Steelers_logo.svg" alt="Steelers Logo" class="logo">
        <h1>Edit Stats for Player: <?php echo $playerName; ?></h1>
    </header>
    <form action="addstats.php?player_id=<?php echo htmlspecialchars((string) $playerId); ?>" method="post">
        <label for="season_year">Season Year:</label>
        <input type="number" name="season_year" id="season_year" value="<?php echo htmlspecialchars((string) $playerStats['season_year']); ?>" required><br><br>
        <label for="games_played">Games Played:</label>
        <input type="number" name="games_played" id="games_played" value="<?php echo htmlspecialchars((string) $playerStats['games_played']); ?>" required><br><br>
        <label for="touchdowns">Touchdowns:</label>
        <input type="number" name="touchdowns" id="touchdowns" value="<?php echo htmlspecialchars((string) $playerStats['touchdowns']); ?>" required><br><br>
        <label for="yards">Yards:</label>
        <input type="text" name="yards" id="yards" value="<?php echo htmlspecialchars((string) $playerStats['yards']); ?>" required><br><br>
        <label for="receptions">Receptions:</label>
        <input type="number" name="receptions" id="receptions" value="<?php echo htmlspecialchars((string) $playerStats['receptions']); ?>" required><br><br>
        <label for="tackles">Tackles:</label>
        <input type="number" name="tackles" id="tackles" value="<?php echo htmlspecialchars((string) $playerStats['tackles']); ?>" required><br><br>
        <label for="sacks">Sacks:</label>
        <input type="text" name="sacks" id="sacks" value="<?php echo htmlspecialchars((string) $playerStats['sacks']); ?>" required><br><br>
        <label for="interceptions">Interceptions:</label>
        <input type="number" name="interceptions" id="interceptions" value="<?php echo htmlspecialchars((string) $playerStats['interceptions']); ?>" required><br><br>
        <button type="submit" class="button">Save Stats</button>
    </form>
    <a href="player_stats.php?id=<?php echo htmlspecialchars((string) $playerId); ?>" class="button">Cancel</a>
</body>
</html>