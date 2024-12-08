<?php
include 'conn.php';

// Process form data if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve form data
        $pageURL = $_POST['page_url'] ?? '';
        $sessionID = $_POST['session_id'] ?? '';
        $userID = $_POST['user_id'] ?? null;
        $pageViewCount = $_POST['page_view_count'] ?? 1;
        $sessionDuration = $_POST['session_duration'] ?? 0;

        // SQL statement
        $sql = "INSERT INTO mywebmetrics (page_url, session_id, user_id, page_view_count, session_duration)
                VALUES (:page_url, :session_id, :user_id, :page_view_count, :session_duration)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':page_url', $pageURL, PDO::PARAM_STR);
        $stmt->bindParam(':session_id', $sessionID, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':page_view_count', $pageViewCount, PDO::PARAM_INT);
        $stmt->bindParam(':session_duration', $sessionDuration, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        echo "Metrics added successfully.";
    } catch (PDOException $e) {
        echo "Error adding metrics: " . $e->getMessage();
    }
} else {
    echo "No data submitted.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Metrics</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST" action="addmetrics.php">
        <label for="page_url">Page URL:</label>
        <input type="text" id="page_url" name="page_url" required><br>

        <label for="session_id">Session ID:</label>
        <input type="text" id="session_id" name="session_id" required><br>

        <label for="user_id">User ID (optional):</label>
        <input type="number" id="user_id" name="user_id"><br>

        <label for="page_view_count">Page View Count:</label>
        <input type="number" id="page_view_count" name="page_view_count" value="1"><br>

        <label for="session_duration">Session Duration (seconds):</label>
        <input type="number" id="session_duration" name="session_duration" value="0"><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
