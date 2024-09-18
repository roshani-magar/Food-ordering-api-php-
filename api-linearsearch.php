<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    exit(0);
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');
include "config.php";

// Get search query and price filters from the request
$search_query = isset($_GET['query']) ? $_GET['query'] : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 1000;

// Fetch food items from the database, apply price filters
$sql = "SELECT * FROM tbl_food WHERE price BETWEEN $min_price AND $max_price";
$result = $conn->query($sql);

$food_items = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $food_items[] = $row;
    }
}

// Perform linear search on the food items based on the search query
$filtered_items = array();
foreach ($food_items as $item) {
    if (stripos($item['title'], $search_query) !== false) { // Check if the query exists in the food title
        $filtered_items[] = $item;
    }
}

// Return the filtered items as JSON
echo json_encode($filtered_items);

$conn->close();
?>
