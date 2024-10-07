<?php
include "header.php";
include "connection.php";
session_start();

// Check if the user is logged in with both username and password
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php"); 
    exit();


 
}


$id=$_GET['id'];
$company_name="";
$product_name="";
$unit="";
$Quantity="";

$res=mysqli_query($link,"select * from import_barista where id='$id' ");
while($row=mysqli_fetch_array($res))
{
   
    $product_name=$row["product_name"];
    $unit=$row["unit"];
    $Price=$row["Price"];
    $Date=$row["Date"];
    $Quantity=$row["Quantity"];
   
}
?>
<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i>
            Edit barista Product</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">

        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">

           <div class="span12">
              <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                  <h5>Edit barista Product</h5>
                </div>
                <div class="widget-content nopadding">
                  <form name="form1" action="" method="post" class="form-horizontal">

                

                    <div class="control-group">
                      <label class="control-label">Product Name :</label>
                      <div class="controls">
                        <input type="text" name="product_name"  value="<?php echo $product_name; ?>" class="span11" placeholder="Product name" />
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">Select Unit :</label>
                      <div class="controls">
                        <select name="unit" class="span11">
                          <option>--- Select Unit ---</option>
                            <?php
                            $res=mysqli_query($link,"SELECT * FROM `units` ");
                            while($row=mysqli_fetch_array($res))
                            {
                              ?>
                              <option <?php if($row['unit']==$unit) {echo "selected";} ?> >
                              <?php
                              echo $row['unit'];
                              echo "</option>";
                            }
                            ?>
                        </select>
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">Quanity</label>
                      <div class="controls">
                        <input type="text" name="Quantity" value="<?php echo $Quantity; ?>" class="span11" placeholder="Enter Quantity"  />
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label">Price</label>
                      <div class="controls">
                        <input type="text" name="Price" class="span11" value="<?php echo $Price?>" placeholder="Price"  />
                      </div>
                    </div>


                    <div class="control-group">
                      <label class="control-label">Date</label>
                      <div class="controls">
                        <input type="Date" name="Date" class="span11" value="<?php echo $Date?>" placeholder="Date"  />
                      </div>
                    </div>


                    <div class="form-actions">
                      <button type="submit" name="submit1" style=" background: #6F4E37;border-radius:5px" class="btn btn-success">Update</button>
                    </div>

                    <div class="alert alert-success text-center" id="success" style="display: none;">
                        <strong>Record Updated Successfully !</strong>
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
    $Price = floatval(mysqli_real_escape_string($link, $_POST['Price']));
    $Date = mysqli_real_escape_string($link, $_POST['Date']);
    
    // Update product_name, unit, Price, and Date without conditions
    mysqli_query($link, "UPDATE `import_barista` SET `product_name`='$new_product_name', `unit`='$unit', `Price`='$Price', `Date`='$Date' WHERE id='$id'") or die(mysqli_error($link));

    // Fetch the current stock quantities for comparison
    $import_res = mysqli_query($link, "SELECT Quantity FROM `import_barista` WHERE id='$id'");
    $import_row = mysqli_fetch_array($import_res);
    $current_import_quantity = intval($import_row['Quantity']); // Stock in

    // Fetch total served quantity for the product
    $served_res = mysqli_query($link, "SELECT SUM(Quantity) as total_served FROM `served_barista` WHERE product_name='$new_product_name'");
    $served_row = mysqli_fetch_array($served_res);
    $total_served_quantity = intval($served_row['total_served']); // Stock out

    // Only update Quantity if served quantity is less than imported quantity
    if ($total_served_quantity > $current_import_quantity) {
        $new_Quantity = intval(mysqli_real_escape_string($link, $_POST['Quantity']));
        mysqli_query($link, "UPDATE `import_barista` SET `Quantity`='$new_Quantity' WHERE id='$id'") or die(mysqli_error($link));
    } else {
        ?>
       
<!-- Modal structure -->
<div id="customAlertModal" class="modal">
    <div class="modal-content">
        <h3>Update Error</h3>
        <p>Cannot update Quantity: The served quantity is equal to or greater than the available stock. The product is out of stock.</p>
        <button onclick="closeModal()">Close</button>
    </div>
</div>

<!-- Your form or page content -->

<script type="text/javascript">
    // Function to display the modal
    function showModal() {
        document.getElementById('customAlertModal').style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('customAlertModal').style.display = 'none';
    }

    // Call this function instead of the alert
    showModal();
</script>
        <?php
    }

    ?>
    <script type="text/javascript">
        document.getElementById("success").style.display = "block";
        setTimeout(function () {
            window.location = "add_barista_products.php";
        }, 1000);
    </script>
    <?php
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