<?php
include "header.php";
include "connection.php";
session_start();

// Check if the user is logged in with both username and password
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php"); 
    exit();
}

// Fetch the date from the URL
$Date = $_GET['Date'];
$product_name = "";
$unit = "";
$Quantity = "";
$Price = "";

// Make sure to validate the date format if needed
if ($Date) {
    $res = mysqli_query($link, "SELECT * FROM served_ketchen WHERE Date='$Date'");
    
    // Check if any record was found
    if ($row = mysqli_fetch_array($res)) {
        $product_name = $row["product_name"];
        $unit = $row["unit"];
        $Price = $row["Price"];
        $Quantity = $row["Quantity"];
    } else {
        echo "<script>alert('No records found for the selected date.');</script>";
    }
} else {
    echo "<script>alert('Invalid date provided.');</script>";
}
?>
<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i> Edit served kitchen Product</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"><span class="icon"><i class="icon-align-justify"></i></span>
                        <h5>Edit served kitchen Product</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">Product Name :</label>
                                <div class="controls">
                                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" class="span11" placeholder="Product name" required />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Select Unit :</label>
                                <div class="controls">
                                    <select name="unit" class="span11" required>
                                        <option>--- Select Unit ---</option>
                                        <?php
                                        $res = mysqli_query($link, "SELECT * FROM `units`");
                                        while ($unit_row = mysqli_fetch_array($res)) {
                                            ?>
                                            <option <?php if ($unit_row['unit'] == $unit) { echo "selected"; } ?>>
                                                <?php echo htmlspecialchars($unit_row['unit']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Quantity</label>
                                <div class="controls">
                                    <input type="number" name="Quantity" value="<?php echo htmlspecialchars($Quantity); ?>" class="span11" placeholder="Enter Quantity" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Price</label>
                                <div class="controls">
                                    <input type="number" step="0.01" name="Price" class="span11" value="<?php echo htmlspecialchars($Price); ?>" placeholder="Price" required />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date</label>
                                <div class="controls">
                                    <input type="date" name="Date" class="span11" value="<?php echo htmlspecialchars($Date); ?>" placeholder="Date" required />
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="submit1" style="background: #6F4E37; border-radius:5px" class="btn btn-success">Update</button>
                            </div>

                            <div class="alert alert-success text-center" id="success" style="display: none;">
                                <strong>Record Updated Successfully!</strong>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['submit1'])) {
    $new_product_name = mysqli_real_escape_string($link, $_POST['product_name']);
    $unit = mysqli_real_escape_string($link, $_POST['unit']);
    $new_Quantity = intval(mysqli_real_escape_string($link, $_POST['Quantity']));
    $Price = floatval(mysqli_real_escape_string($link, $_POST['Price']));
    $Date = mysqli_real_escape_string($link, $_POST['Date']);

    // Fetch the total imported quantity for the product
    $import_res = mysqli_query($link, "SELECT SUM(Quantity) as total_imported FROM `import_ketchen` WHERE product_name='$new_product_name'");
    $import_row = mysqli_fetch_array($import_res);
    $total_imported_quantity = intval($import_row['total_imported']);

    // Fetch the total served quantity for the product
    $served_res = mysqli_query($link, "SELECT SUM(Quantity) as total_served FROM `served_ketchen` WHERE product_name='$new_product_name' AND Date='$Date'");
    $served_row = mysqli_fetch_array($served_res);
    $total_served_quantity = intval($served_row['total_served']);

    // Check if the new quantity to be served exceeds the available stock
    if ($total_served_quantity >= $total_imported_quantity) {
        // Update the served_ketchen record considering both ID and Date
        mysqli_query($link, "UPDATE `served_ketchen` SET `product_name`='$new_product_name', `unit`='$unit', `Quantity`='$new_Quantity', `Price`='$Price', `Date`='$Date' WHERE Date='$Date'") or die(mysqli_error($link));

        // Show success message
        echo "<script>
            document.getElementById('success').style.display = 'block';
            setTimeout(function() {
                window.location = 'served_kitchen_products.php';
            }, 1000);
        </script>";
    } else {
        // Show error modal
        echo "<script>
            alert('Cannot update Quantity: The served quantity exceeds available stock.');
        </script>";
    }
}
?>

<?php include "footer.php"; ?>

<style>
        /* Styling the modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 9999; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
        }

        /* Modal content box */
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f4f4f9;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        /* Styling the message text */
        .modal-content h3 {
            color: #ff4444;
            font-family: 'Arial', sans-serif;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .modal-content p {
            color: #555;
            font-family: 'Roboto', sans-serif;
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Styling the close button */
        .modal-content button {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
        }

        .modal-content button:hover {
            background-color: #e33b3b;
        }
    </style>
