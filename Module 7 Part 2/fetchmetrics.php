<?php
include 'conn.php';

try {
    // Fetch metric data
    $sql = "SELECT page_url, session_id, user_id, page_view_count, session_duration, timestamp 
            FROM mywebmetrics
            ORDER BY session_id DESC";
    $stmt = $pdo->query($sql);
    $metrics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set the response headers
    header('Content-Type: application/json');

    if ($metrics) {
        // Return the data in JSON
        echo json_encode($metrics);
    } else {
        // Return an empty JSON array if no data is found
        echo json_encode([]);
    }
} catch (PDOException $e) {
    // Return an error response
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}
?>
