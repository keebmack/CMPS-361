<?php
header('Content-Type: application/json');

// Sample data array with Pittsburgh Steelers information
$data = [
    ['id' => 1, 'name' => 'Pittsburgh Steelers', 'founded' => 1933, 'stadium' => 'Heinz Field', 'colors' => ['Black', 'Gold'], 'super_bowls' => 6],
    ['id' => 2, 'name' => 'Franchise History', 'details' => 'The Pittsburgh Steelers are a professional football team based in Pittsburgh, Pennsylvania. They are a in the AFC North division of the National Football League (NFL). They are most definitely better than the Rat Birds, the Bungels and the Brownies. The current head coach is Mike Tomlin.'],
    ['id' => 3, 'name' => 'Notable Players', 'details' => 'Some notable players include Joe Greene, Terry Bradshaw, Franco Harris, Ben Roethlisberger and Troy Polamalu.'],
    ['id' => 4, 'name' => 'Team Achievements', 'details' => 'The Steelers have won 6 Super Bowl championships, more than any other NFL team.']
];

// Pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 2; // Change this for more or less items per page
$totalItems = count($data);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;

// Slice the data for the current page
$pagedData = array_slice($data, $offset, $itemsPerPage);

// Output the paginated data
echo json_encode([
    'current_page' => $page,
    'total_pages' => $totalPages,
    'data' => $pagedData
]);
?>

