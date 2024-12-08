<?php
$host = 'localhost';
$dbname = 'steelers_roster';
$user = 'postgres';
$password = 'Ricoisapug';
$port = '5433';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname;user=$user;password=$password;port=$port");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}  catch (PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
}
?>