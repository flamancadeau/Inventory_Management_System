<?php
include "header.php";
include "connection.php";
session_start();

// Check if the user is logged in with both username and password
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php"); 
    exit();


 
}

?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i> Add Served Barista Product</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Add Served Barista Product</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">

                        <div class="control-group">
    <label class="control-label">Product Name</label>
    <div class="controls">
        <!-- Replacing the select with an input and datalist for product name -->
        <input list="product_names" name="product_name" class="span11" placeholder="--- Select product name ---">
        <datalist id="product_names">
            <?php
            $res = mysqli_query($link, "SELECT * FROM `import_barista`");
            while ($row = mysqli_fetch_array($res)) {
                echo "<option value='{$row['product_name']}' label='{$row['id']} - {$row['product_name']}'></option>";
            }
            ?>
        </datalist>
    </div>
</div>

<div class="control-group">
    <label class="control-label">Product ID</label>
    <div class="controls">
        <!-- Replacing the select with an input and datalist for product id -->
        <input list="product_ids" name="id" class="span11" placeholder="--- Select product id ---">
        <datalist id="product_ids">
            <?php
            $res = mysqli_query($link, "SELECT * FROM `import_barista`");
            while ($row = mysqli_fetch_array($res)) {
                echo "<option value='{$row['id']}' label='ID:{$row['id']} - {$row['product_name']}'></option>";
            }
            ?>
        </datalist>
    </div>
</div>

<div class="control-group">
    <label class="control-label">Select Unit</label>
    <div class="controls">
        <!-- Replacing the select with an input and datalist for units -->
        <input list="units" name="unit" class="span11" placeholder="--- Select Unit ---">
        <datalist id="units">
            <?php
            $res = mysqli_query($link, "SELECT * FROM `units`");
            while ($row = mysqli_fetch_array($res)) {
                echo "<option value='{$row['unit']}'></option>";
            }
            ?>
        </datalist>
    </div>
</div>

                            <div class="control-group">
                                <label class="control-label">Quantity</label>
                                <div class="controls">
                                    <input type="text" name="Quantity" class="span11" placeholder="Quantity" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Price</label>
                                <div class="controls">
                                    <input type="text" name="Price" class="span11" placeholder="Price" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date</label>
                                <div class="controls">
                                    <input type="Date" name="Date" class="span11" placeholder="Date" />
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="serve_product" style="border-radius:5px; background: #6F4E37;" class="btn btn-success">Serve Product</button>
                            </div>

                            <div class="alert alert-danger text-center" id="error" style="display: none;">
                                <strong>This Product is Unavailable in Stock!</strong>
                            </div>

                            <div class="alert alert-success text-center" id="success" style="display: none;">
                                <strong>Product Served Successfully!</strong>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
  <!-- Add Export Button -->
  <form method="post" action="export_excel_served_ketchen.php">
                <input type="hidden" name="table_name" value="served_barista"> <!-- Table name -->
                <input type="hidden" name="filename" value="export barista records"> <!-- Desired file name -->
                <button type="submit" style="color:white; float:right;margin-right:40px ;margin-bottom:20px; background: #6F4E37;" name="export">
                    Export to excel
    <a></a><i class="fas fa-file-excel"></i> </a>
</button>
            </form>



            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Product Name</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($link, "SELECT * FROM served_barista");
                        while ($row = mysqli_fetch_array($res)) {
                            echo "<tr>
                             <td>{$row['id']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['unit']}</td>
                                <td>{$row['Quantity']}</td>
                                <td>{$row['Price']}</td>
                                <td>{$row['Date']}</td>
                                <td>
                                    <center>
                                        <a title='Edit' class='tip-bottom' href='edit_served_barista_product.php?Date={$row['Date']}' style='color: green'><i class='icon icon-edit'></i></a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                    
                                    </center>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php
if (isset($_POST['serve_product'])) {
    $id = intval($_POST['id']);
    $product_name = mysqli_real_escape_string($link, $_POST['product_name']);
    $unit = mysqli_real_escape_string($link, $_POST['unit']);
    $Quantity = intval($_POST['Quantity']); // Cast to integer for quantity
    $Price = floatval($_POST['Price']); // Cast to float for price
    $Date = mysqli_real_escape_string($link, $_POST['Date']); // Sanitize date

    // Check the total stock available for the product in import_barista
    $stock_res = mysqli_query($link, "SELECT SUM(Quantity) as total_stock FROM import_barista WHERE id='$id'");
    $stock_row = mysqli_fetch_array($stock_res);
    $available_stock = intval($stock_row['total_stock']);

    // Check the total quantity already served from served_barista
    $served_res = mysqli_query($link, "SELECT SUM(Quantity) as total_served FROM served_barista WHERE id='$id'");
    $served_row = mysqli_fetch_array($served_res);
    $total_served = intval($served_row['total_served']);

    // Calculate the remaining stock
    $remaining_stock = $available_stock - $total_served;

    if ($remaining_stock >= $Quantity) {
        // Sufficient stock available, proceed with serving the product
        mysqli_query($link, "INSERT INTO served_barista (product_name, unit, Quantity, Price, Date,id) VALUES ('$product_name', '$unit', '$Quantity', '$Price', '$Date','$id')");
        
        echo "<script type='text/javascript'>
            document.getElementById('error').style.display='none';
            document.getElementById('success').style.display='block';
            setTimeout(function() {
                window.location.href=window.location.href;
            }, 1000);
        </script>";
    } else {
        // Insufficient stock, show error message
        echo "<script type='text/javascript'>
            document.getElementById('success').style.display='none';
            document.getElementById('error').style.display='block';
        </script>";
    }
}
?>

<?php include "footer.php"; ?>
