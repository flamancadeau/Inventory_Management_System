<?php
include "connection.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php"); 
    exit();
}

// Get start and end dates from POST request
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Set headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="General_stock_report.csv"');

// Output the column headings
$output = fopen('php://output', 'w');
fputcsv($output, ['Source', 'Total Quantity', 'Total Price']);

// Prepare the query
$query = "
   SELECT 
    'import_ketchen' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM import_ketchen

UNION ALL

SELECT 
    'bank' AS source, 
    NULL AS total_quantity, 
    IFNULL(SUM(Money), 0) AS total_price 
FROM bank

UNION ALL

SELECT 
    'served_ketchen' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM served_ketchen

UNION ALL

SELECT 
    'import_barista' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM import_barista

UNION ALL

SELECT 
    'served_barista' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM served_barista

UNION ALL

SELECT 
    'import_bar' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM import_bar

UNION ALL

SELECT 
    'served_bar' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM served_bar

UNION ALL

SELECT 
    'momo' AS source, 
    NULL AS total_quantity, 
    IFNULL(SUM(Money), 0) AS total_price 
FROM momo;
;
";

// Execute the query
$res = mysqli_query($link, $query);

// Fetch and output each row
while ($row = mysqli_fetch_assoc($res)) {
    fputcsv($output, [$row['source'], $row['total_quantity'], $row['total_price']]);
}

// Close the output
fclose($output);
exit();
?>
