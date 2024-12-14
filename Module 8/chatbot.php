<?php
// Database connection configuration
$host = 'localhost';
$dbname = 'steelers_roster';
$user = 'postgres';
$password = 'Ricoisapug';
$port = '5433';

try {
    // Establish a connection to database
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, send a JSON response with an error message and exit
    echo json_encode(['response' => 'Database connection failed.']);
    exit();
}

// Retrieve the user query from the POST request
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

if ($query) {
    // Prepare a SQL statement to search for a matching response
    // Using LOWER to perform a case-insensitive search
    $stmt = $pdo->prepare("SELECT response FROM chatbot_responses WHERE LOWER(:query) LIKE LOWER('%' || question || '%')");
    
    // Bind the user query to the prepared statement to prevent SQL injection
    $stmt->bindParam(':query', $query);
    
    // Execute the SQL query
    $stmt->execute();
    
    // Fetch the result as an associative array
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // If a matching response is found, send it as a JSON response
        echo json_encode(['response' => $result['response']]);
    } else {
        // If no match is found, send a fallback response
        echo json_encode(['response' => "I'm sorry, I don't know the answer to that."]);
    }
} else {
    // If no query is provided in the POST request, send an error response
    echo json_encode(['response' => 'No query provided.']);
}
?>

