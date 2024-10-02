<?php
include "connection.php";

if (isset($_POST['export'])) {
    $filename = $_POST['filename'] . ".csv"; // Desired file name

    // Set headers to force download the file
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    // Open file pointer for output
    $output = fopen('php://output', 'w');

    // Query to fetch all records from the `momo` table
    $query = "SELECT * FROM momo";
    $result = mysqli_query($link, $query);

    // If the query returns rows
    if ($result) {
        // Get the column names for the CSV header
        $columns_res = mysqli_query($link, "SHOW COLUMNS FROM momo");
        $columns = [];
        while ($column = mysqli_fetch_assoc($columns_res)) {
            $columns[] = $column['Field'];
        }

        // Output the column headings in CSV format
        fputcsv($output, $columns);

        // Fetch rows and output each one in CSV format
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
    }

    // Close the file pointer
    fclose($output);
    exit();
}
?>
