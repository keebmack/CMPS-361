<?php
session_start();

// Include database configuration
require_once('db_config.php');

// Create connection to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Validate connection
if (!$conn) {
    error_log("Database connection failed: " . pg_last_error());
    echo "A technical error occurred. Please try again later.";
    exit;
}

// Check if the user is logging out
if (isset($_GET['logout'])) {
    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: login.php");
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }

    // Query the users table for the user
    $sql = "SELECT * FROM users WHERE username = $1 LIMIT 1";
    $result = pg_query_params($conn, $sql, array($username));

    if ($result && pg_num_rows($result) > 0) {
        // Fetch the user data
        $user = pg_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];

            // Redirect to the dashboard or protected page
            header("Location: dashboard.php");
            exit;
        }
    }

    // Generic error message
    echo "Invalid username or password.";
}

// Welcome message if logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo "<p>Welcome, " . htmlspecialchars($_SESSION['username']) . "! <a href='?logout=true'>Logout</a></p>";
}

// Close the database connection
pg_close($conn);
?>

