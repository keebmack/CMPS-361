<?php
  $host = "localhost";
  $port = "5433";
  $dbname = "CMPS361";
  $user = "ryankebe";
  $password = "Ricod@pug1897";

  // Connection string
  $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

  try {
    // Create a new PDO instance
    $instance = new PDO($dsn, $user, $password);

    // Set error alert
    $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Echo messages
    echo "Successfully connected to the database";

  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
?>
