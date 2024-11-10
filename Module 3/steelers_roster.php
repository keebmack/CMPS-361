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

// Whitelist the columns to prevent SQL injection
$validColumns = ['name', 'position', 'jersey_number'];
if (!in_array($sortColumn, $validColumns)) {
    $sortColumn = 'jersey_number';
}

// Pagination settings
$playersPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $playersPerPage;

// Fetch Steelers roster data with dynamic sorting and pagination
if ($sortColumn === 'position') {
    // Custom sort order for positions
    $query = "
        SELECT name, position, jersey_number
        FROM players
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
                ELSE 16  -- For any positions not specified
            END $sortOrder,
            name ASC
        LIMIT $playersPerPage OFFSET $offset";
} else {
    // Default sorting for other columns
    $query = "SELECT name, position, jersey_number FROM players ORDER BY $sortColumn $sortOrder LIMIT $playersPerPage OFFSET $offset";
}
$result = pg_query($conn, $query);


if (!$result) {
    die("Error: Query failed.");
}

// Count the total number of players for pagination
$totalPlayersQuery = "SELECT COUNT(*) AS total FROM players";
$totalPlayersResult = pg_query($conn, $totalPlayersQuery);
$totalPlayersRow = pg_fetch_assoc($totalPlayersResult);
$totalPlayers = $totalPlayersRow['total'];
$totalPages = ceil($totalPlayers / $playersPerPage);

// Determine new sort order for the next click
$newSortOrder = $sortOrder == 'ASC' ? 'DESC' : 'ASC';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steelers Roster</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4b41a;
            color: black;
        }
        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Pittsburgh Steelers Active Roster</h1>
    <table>
        <tr>
            <th><a href="?column=name&order=<?php echo $newSortOrder; ?>&page=<?php echo $page; ?>">Player Name</a></th>
            <th><a href="?column=position&order=<?php echo ($sortColumn == 'position' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&page=<?php echo $page; ?>">Position</a></th>
            <th><a href="?column=jersey_number&order=<?php echo ($sortColumn == 'jersey_number' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>&page=<?php echo $page; ?>">Jersey Number</a></th>
        </tr>
        <?php
        // Loop through the roster data and output rows
        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['position']) . "</td>";
            echo "<td>" . htmlspecialchars($row['jersey_number']) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Pagination links -->
    <div style="text-align: center;">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&column=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>">Previous</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&column=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>&column=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>">Next</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
pg_close($conn);
?>
