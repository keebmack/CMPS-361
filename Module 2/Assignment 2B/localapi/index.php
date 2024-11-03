<?php
// Fetch data from the API
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$apiUrl = "http://localhost/localapi/api.php?page=" . $page;

$response = @file_get_contents($apiUrl);
if ($response === FALSE) {
    die("Error fetching data from the API.");
}
$data = json_decode($response, true);

if ($data === null) {
    die("Error decoding JSON response.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pittsburgh Steelers Information</title>
</head>
<body>
    <h1>Pittsburgh Steelers Information Page (Page <?php echo $data['current_page']; ?> of <?php echo $data['total_pages']; ?>)</h1>
    <ul>
        <?php foreach ($data['data'] as $item): ?>
            <li>
                <strong><?php echo $item['name']; ?></strong>
                <?php if (isset($item['founded'])): ?>
                    <br>Founded: <?php echo $item['founded']; ?>
                    <br>Stadium: <?php echo $item['stadium']; ?>
                    <br>Colors: <?php echo implode(', ', $item['colors']); ?>
                    <br>Super Bowls Won: <?php echo $item['super_bowls']; ?>
                <?php else: ?>
                    <br><?php echo $item['details']; ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div>
        <?php if ($data['current_page'] > 1): ?>
            <a href="index.php?page=<?php echo $data['current_page'] - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php if ($data['current_page'] < $data['total_pages']): ?>
            <a href="index.php?page=<?php echo $data['current_page'] + 1; ?>">Next</a>
        <?php endif; ?>
    </div>

    <div>
        <h2>All Pages</h2>
        <ul>
            <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                <li><a href="index.php?page=<?php echo $i; ?>">Page <?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </div>
</body>
</html>
