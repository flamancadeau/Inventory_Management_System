<?php
include "connection.php";
$id = $_GET['id'];

// Prepare and bind the statement properly
$stmt = $link->prepare("DELETE FROM `products` WHERE id = ?");
$stmt->bind_param("i", $id); // Assuming id is an integer

// Execute the statement
$stmt->execute();

// Optionally check for errors
if ($stmt->affected_rows > 0) {
    // Successfully deleted
} else {
    // Handle the case where no rows were deleted (e.g., id not found)
}

$stmt->close();
$link->close();
?>
<script type="text/javascript">
    window.location = "add_products.php";
</script>