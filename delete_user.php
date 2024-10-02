<?php
    include "connection.php";
    $id = $_GET['id'];

    // Prepare and bind
    $stmt = $link->prepare("DELETE FROM `user_registration` WHERE id = ?");
    $stmt->bind_param("i", $id); // Assuming id is an integer

    // Execute the statement
    $stmt->execute();
    $stmt->close();
    $link->close();
    ?>
    <script type="text/javascript">
        window.location = "add_new_user.php";
    </script>