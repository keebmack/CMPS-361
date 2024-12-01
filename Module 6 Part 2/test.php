<?php
$conn = pg_connect("host=localhost port=5433 dbname=steelers_roster user=postgres password=Ricoisapug");
if ($conn) {
    echo "PostgreSQL is working!";
} else {
    echo "Error: " . pg_last_error();
}
?>
