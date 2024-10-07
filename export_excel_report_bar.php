<?php
include "connection.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php");
    exit();
}

// Initialize variables for search
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Set headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=barista_report.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Write the column headings
fputcsv($output, [
    'ID',
    'Imported Product',
    'Imported Unit',
    'Imported Quantity',
    'Imported Price (frw)',
    'Imported Date',
    'Exported Product',
    'Exported Unit',
    'Exported Quantity',
    'Exported Price (frw)',
    'Exported Date',
    'Remaining in Stock',
]);

// Query to fetch data based on date range
if ($start_date && $end_date) {
    $query = "
      SELECT 
        import_bar.id, 
        import_bar.product_name AS imported_product, 
        import_bar.unit AS imported_unit, 
        import_bar.Quantity AS imported_quantity, 
        import_bar.Price AS imported_price, 
        import_bar.Date AS imported_Date, 
        served_bar.product_name AS exported_product, 
        served_bar.unit AS exported_unit, 
        served_bar.Quantity AS exported_quantity, 
        served_bar.Price AS exported_price, 
        served_bar.Date AS exported_Date 
      FROM import_bar
      LEFT JOIN served_bar
        ON import_bar.id = served_bar.id
      WHERE import_bar.Date BETWEEN '$start_date' AND '$end_date' 
      OR served_bar.Date BETWEEN '$start_date' AND '$end_date'";
    
    $res = mysqli_query($link, $query);

    while ($row = mysqli_fetch_assoc($res)) {
        $imported_quantity = $row['imported_quantity'];
        $exported_quantity = $row['exported_quantity'];

        // Calculate remaining stock
        $remaining_stock = $imported_quantity - $exported_quantity;

        // Write each row to the CSV
        fputcsv($output, [
            $row['id'],
            $row['imported_product'],
            $row['imported_unit'],
            $row['imported_quantity'],
            number_format($row['imported_price'], 2),
            $row['imported_Date'],
            $row['exported_product'],
            $row['exported_unit'],
            $row['exported_quantity'],
            number_format($row['exported_price'], 2),
            $row['exported_Date'],
            $remaining_stock,
        ]);
    }
}

// Close the output stream
fclose($output);
exit();
?>
