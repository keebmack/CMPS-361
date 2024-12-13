<?php

function logActivity($userId, $activityType, $activityDescription){


//create db connection
$host = 'localhost';
$dbname = 'steelers_roster';
$user = 'postgres';
$password = 'Ricoisapug';
$port = '5433';

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password port=$port");
//validate the connection works
if(!$conn){
    die("connection failed: " . pg_last_error());
}
//capture ip addresses
$ipAddress = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

//add tracking info to database
$sql = "INSERT INTO user_activity_logging(user_id,activity_type,activity_description,ip_address,user_agent) VALUES ($1, $2, $3, $4, $5)";

//execute the SQL for the INSERT into the table
$result = pg_query_params($conn, $sql, array($userId, $activityType, $activityDescription, $ipAddress, $userAgent));

if(!$result){
    echo "error in query execution " . pg_last_error();
} else {
    echo "activity logged successfully";
}

//close connection to the database
pg_close($conn);

}

?>