<?php
// Database connection parameters
$host = "localhost";
$port = "5433";
$dbname = "steelers_roster";
$user = "postgres";
$password = "Ricoisapug";

// Create a connection to the database
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Check connection
if (!$conn) {
    die("Error: Unable to connect to the database.");
}

// Set default sorting values
$sortColumn = isset($_GET['column']) ? $_GET['column'] : 'jersey_number';
$sortOrder = isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'DESC' : 'ASC';

// Whitelist columns to prevent SQL injection
$validColumns = ['name', 'position', 'jersey_number'];
if (!in_array($sortColumn, $validColumns)) {
    $sortColumn = 'jersey_number';
}

// Search functionality
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';
if ($searchQuery !== '') {
    $searchCondition = "WHERE name ILIKE '%$searchQuery%' 
                        OR position ILIKE '%$searchQuery%' 
                        OR CAST(jersey_number AS TEXT) ILIKE '%$searchQuery%'";
}

// Pagination settings
$playersPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $playersPerPage;

// Build the SQL query
if ($sortColumn === 'position') {
    $query = "
        SELECT id, name, position, jersey_number
        FROM players
        $searchCondition
        ORDER BY 
            CASE 
                WHEN position = 'QB' THEN 1
                WHEN position = 'RB' THEN 2
                WHEN position = 'WR' THEN 3
                WHEN position = 'TE' THEN 4
                WHEN position = 'OT' THEN 5
                WHEN position = 'G' THEN 6
                WHEN position = 'C' THEN 7
                WHEN position = 'DT' THEN 8
                WHEN position = 'DL' THEN 9
                WHEN position = 'OLB' THEN 10
                WHEN position = 'ILB' THEN 11
                WHEN position = 'CB' THEN 12
                WHEN position = 'S' THEN 13
                WHEN position = 'K' THEN 14
                WHEN position = 'P' THEN 15
                ELSE 16
            END $sortOrder,
            name ASC
        LIMIT $playersPerPage OFFSET $offset";
} else {
    $query = "
        SELECT id, name, position, jersey_number
        FROM players
        $searchCondition
        ORDER BY $sortColumn $sortOrder
        LIMIT $playersPerPage OFFSET $offset";
}

// Execute the query
$result = pg_query($conn, $query);

if (!$result) {
    die("Error: Query failed.");
}

// Count total players for pagination
$totalPlayersQuery = "SELECT COUNT(*) AS total FROM players $searchCondition";
$totalPlayersResult = pg_query($conn, $totalPlayersQuery);
$totalPlayersRow = pg_fetch_assoc($totalPlayersResult);
$totalPlayers = $totalPlayersRow['total'];
$totalPages = ceil($totalPlayers / $playersPerPage);

$newSortOrder = $sortOrder == 'ASC' ? 'DESC' : 'ASC'; // Toggle sort order
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steelers Roster</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1>Pittsburgh Steelers Active Roster</h1>

    <!-- Search Form -->
    <form method="GET" style="text-align: center; margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>" style="padding: 8px; width: 250px; border: 1px solid #ddd; border-radius: 4px;">
        <button type="submit" style="padding: 8px 12px; background-color: #f4b41a; color: #000; border: none; border-radius: 4px; cursor: pointer;">Search</button>
        <a href="?column=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>&page=1" style="margin-left: 10px; padding: 8px 12px; background-color: #f4b41a; color: #000; text-decoration: none; border-radius: 4px;">Clear Search</a>
    </form>

    <!-- Roster Table -->
    <table>
        <thead>
            <tr>
                <th><a href="?column=name&order=<?php echo $newSortOrder; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($searchQuery); ?>">Player Name</a></th>
                <th><a href="?column=position&order=<?php echo $newSortOrder; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($searchQuery); ?>">Position</a></th>
                <th><a href="?column=jersey_number&order=<?php echo $newSortOrder; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($searchQuery); ?>">Jersey Number</a></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = pg_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <a href="player_stats.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['jersey_number']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&column=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>&search=<?php echo urlencode($searchQuery); ?>">Previous</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&column=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>&search=<?php echo urlencode($searchQuery); ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>&column=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>&search=<?php echo urlencode($searchQuery); ?>">Next</a>
        <?php endif; ?>
    </div>
    <div class="navigation">
        <a href="welcome.php" class="button">Return to Welcome Page</a>
        <a href="authentication.php?logout=true" class="button">Logout</a>
    </div>


    <?php pg_close($conn); ?>
</body>
</html>