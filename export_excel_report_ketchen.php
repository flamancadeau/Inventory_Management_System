<?php
if (isset($_POST['export'])) {
    // Database connection
    include 'connection.php'; // Add your DB connection details here

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=kitchen_report.csv');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output column headings if needed
    fputcsv($output, array('ID', 'Imported Product', 'Imported Unit', 'Imported Quantity', 'Imported Price', 'Imported Date', 'Exported Product', 'Exported Unit', 'Exported Quantity', 'Exported Price', 'Exported Date', 'Remaining in Stock'));

    // Fetch the data
    $start_date = '2024-01-01'; // Example, replace with your input
    $end_date = '2024-12-31';   // Example, replace with your input

    $query = "
        SELECT 
            import_ketchen.id, 
            import_ketchen.product_name AS imported_product, 
            import_ketchen.unit AS imported_unit, 
            import_ketchen.Quantity AS imported_quantity, 
            import_ketchen.Price AS imported_price, 
            import_ketchen.Date AS imported_Date, 
            served_ketchen.product_name AS exported_product, 
            served_ketchen.unit AS exported_unit, 
            served_ketchen.Quantity AS exported_quantity, 
            served_ketchen.Price AS exported_price, 
            served_ketchen.Date AS exported_Date 
        FROM import_ketchen 
        LEFT JOIN served_ketchen 
            ON import_ketchen.id = served_ketchen.id
        WHERE import_ketchen.Date BETWEEN '$start_date' AND '$end_date' 
        OR served_ketchen.Date BETWEEN '$start_date' AND '$end_date'";

    $res = mysqli_query($link, $query);

    // Loop over the rows and output them in CSV format
    while ($row = mysqli_fetch_assoc($res)) {
        $remaining_stock = $row['imported_quantity'] - $row['exported_quantity'];
        $row['remaining_stock'] = $remaining_stock;

        // Export the row data to CSV
        fputcsv($output, array(
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
            $row['remaining_stock']
        ));
    }

    // Close the output stream
    fclose($output);
    exit();
}
?>
