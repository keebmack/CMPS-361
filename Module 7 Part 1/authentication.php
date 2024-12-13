<?php
session_start();

include './functions/track_activity.php';

/** @var string $host */
/** @var string $port */
/** @var string $dbname */
/** @var string $user */
/** @var string $password */
require_once('db_config.php');

// Create connection to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Validate connection
if ($conn === false) {
    error_log("Database connection failed: " . pg_last_error());
    echo "A technical error occurred. Please try again later.";
    exit;
}

// Check if the user is logging out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (trim($username) === '' || trim($password) === '') {
        echo "Username and password are required.";
        exit;
    }

    $sql = "SELECT * FROM users WHERE username = $1 LIMIT 1";
    $result = pg_query_params($conn, $sql, array($username));

    if ($result !== false && pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);

        if ($user !== false && $password === $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            logActivity($username, 'login', 'logged in successfully');
            header("Location: welcome.php");
            exit;
        }
    }

    echo "Invalid username or password.";
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo "<p>Welcome, " . htmlspecialchars($_SESSION['username']) . "! <a href='?logout=true'>Logout</a></p>";
}

pg_close($conn);
?>
