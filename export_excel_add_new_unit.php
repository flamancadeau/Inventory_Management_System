


<?php
include "connection.php"; // Include your database connection

if (isset($_POST['export'])) {
    $table = $_POST['table_name']; // Get the table name from the form
    $filename = $_POST['filename'] . ".csv"; // Get the desired filename

    // Set headers for downloading the file as an Excel sheet
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    // Open a file pointer
    $output = fopen('php://output', 'w');

    // Fetch the table's columns to create the header
    $columns_res = mysqli_query($link, "SHOW COLUMNS FROM $table");
    $columns = [];
    while ($column = mysqli_fetch_assoc($columns_res)) {
        $columns[] = $column['Field'];
    }

    // Output the column headings
    fputcsv($output, $columns);

    // Fetch data from the table and output each row
    $res = mysqli_query($link, "SELECT * FROM $table");
    while ($row = mysqli_fetch_assoc($res)) {
        fputcsv($output, $row);
    }

    // Close file pointer
    fclose($output);
    exit();
}
?>



