<?php
include "header.php";
include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Display</title>

    <style>
        /* Center the image container */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        /* Optional: Style the dashboard link */
        a.dashboard-link {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            text-decoration: none;
            color: #007bff;
        }

        a.dashboard-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Dashboard link -->
  

    <!-- Image centered in the middle of the page -->
    <img src="./villa_1.jpeg" alt="Description of the image" width="500" height="300">
</body>
</html>

<?php include "footer.php"; ?>
