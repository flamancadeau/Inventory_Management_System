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
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i> Add Served Bar Product</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Add Served Bar Product</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">

                    
                                <div class="control-group">
                                <label class="control-label">Product Name</label>
                                <div class="controls">
                                    <select name="product_name" class="span11">
                                        <option>--- Select product name ---</option>
                                        <?php
                                        $res = mysqli_query($link, "SELECT * FROM `import_bar`");
                                        while ($row = mysqli_fetch_array($res)) {
                                            echo "<option value='{$row['product_name']}'> " . $row['id'] . " - " . $row['product_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Product ID</label>
                                <div class="controls">
                                    <select name="id" class="span11">
                                        <option>--- Select product id ---</option>
                                        <?php
                                        $res = mysqli_query($link, "SELECT * FROM `import_bar`");
                                        while ($row = mysqli_fetch_array($res)) {
                                            echo "<option value='{$row['id']}'>ID:" . $row['id'] ." - ".$row['product_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Select Unit</label>
                                <div class="controls">
                                    <select name="unit" class="span11">
                                        <option>--- Select Unit ---</option>
                                        <?php
                                        $res = mysqli_query($link, "SELECT * FROM `units`");
                                        while ($row = mysqli_fetch_array($res)) {
                                            echo "<option>" . $row['unit'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Quantity</label>
                                <div class="controls">
                                    <input type="text" name="Quantity" class="span11" placeholder="Quantity" required />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Price</label>
                                <div class="controls">
                                    <input type="text" name="Price" class="span11" placeholder="Price" required />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date</label>
                                <div class="controls">
                                    <input type="date" name="Date" class="span11" placeholder="Date" required />
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="serve_product" class="btn btn-success">Serve Product</button>
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
            <form method="post" action="export_excel_served_bar.php">
                <input type="hidden" name="table_name" value="served_bar"> <!-- Table name -->
                <input type="hidden" name="filename" value="served_bar_records"> <!-- Desired file name -->
                <button type="submit" style="color:Green; float:right;margin-right:40px ;margin-bottom:20px;" name="export">
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
                        $res = mysqli_query($link, "SELECT * FROM served_bar");
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
                                        <a title='Edit' class='tip-bottom' href='edit_served_bar_product.php?id={$row['id']}' style='color: green'><i class='icon icon-edit'></i></a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                       
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
    $Quantity = intval($_POST['Quantity']);
    $Price = floatval($_POST['Price']);
    $Date = mysqli_real_escape_string($link, $_POST['Date']);

    // Check the total stock available for the product in import_bar
    $stock_res = mysqli_query($link, "SELECT SUM(Quantity) as total_stock FROM import_bar WHERE id='$id'");
    $stock_row = mysqli_fetch_array($stock_res);
    $available_stock = intval($stock_row['total_stock']);

    // Check the total quantity already served from served_bar
    $served_res = mysqli_query($link, "SELECT SUM(Quantity) as total_served FROM served_bar WHERE id='$id'");
    $served_row = mysqli_fetch_array($served_res);
    $total_served = intval($served_row['total_served']);

    // Calculate remaining stock
    $remaining_stock = $available_stock - $total_served;

    if ($remaining_stock >= $Quantity) {
        // Insert product into served_bar if stock is sufficient
        $insert_query = "INSERT INTO served_bar (product_name, unit, Quantity, Price, Date, id) 
                         VALUES ('$product_name', '$unit', '$Quantity', '$Price', '$Date', '$id')";

        if (mysqli_query($link, $insert_query)) {
            echo "<script type='text/javascript'>
                document.getElementById('error').style.display='none';
                document.getElementById('success').style.display='block';
                setTimeout(function() {
                    window.location.href=window.location.href;
                }, 1000);
            </script>";
        } else {
            echo "<div class='alert alert-danger text-center'>
                    <strong>MySQL Error: " . mysqli_error($link) . "</strong>
                  </div>";
        }
    } else {
        echo "<script type='text/javascript'>
            document.getElementById('success').style.display='none';
            document.getElementById('error').style.display='block';
        </script>";
    }
}
?>

<?php include "footer.php"; ?>
