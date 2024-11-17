<?php
// Start session
session_start();

// Database connection parameters
$host = 'localhost';
$dbname = 'steelers_roster';
$user = 'postgres';
$password = 'Ricoisapug';
$port = '5433';

// Create connection to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Validate connection
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        die("Username and password are required.");
    }

    // Query the `users` table for the user
    $sql = "SELECT * FROM users WHERE username = $1";
    $result = pg_query_params($conn, $sql, array($username));

    if (pg_num_rows($result) > 0) {
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
        } else {
            // Invalid password
            echo "Invalid username or password.";
        }
    } else {
        // User not found
        echo "Invalid username or password.";
    }
}

// Close the database connection
pg_close($conn);
?>
