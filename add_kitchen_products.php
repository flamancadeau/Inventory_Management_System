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
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i>
            Add ketchen Product</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <!-- Add new Product -->
            <div class="span12">
              <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                  <h5>Add ketch Product</h5>
                </div>
                <div class="widget-content nopadding">
                  <form name="form1" action="" method="post" class="form-horizontal">
                    
                  

                    <div class="control-group">
                      <label class="control-label">Product Name</label>
                      <div class="controls">
                        <input type="text" name="product_name" class="span11" placeholder="Product name" />
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">Select Unit</label>
                      <div class="controls">
                        <select name="unit" class="span11">
                          <option>--- Select Unit ---</option>
                            <?php
                            $res=mysqli_query($link,"SELECT * FROM `units` ");
                            while($row=mysqli_fetch_array($res))
                            {
                              echo "<option>";
                              echo $row['unit'];
                              echo "</option>";
                            }
                            ?>
                        </select>
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">Qauntity</label>
                      <div class="controls">
                        <input type="text" name="Quantity" class="span11" placeholder="Qauntity"  />
                      </div>


                      <div class="control-group">
                      <label class="control-label">Price</label>
                      <div class="controls">
                        <input type="text" name="Price" class="span11" placeholder="Price"  />
                      </div>

                       
                    </div>
                    <div class="control-group">
                      <label class="control-label">Date</label>
                      <div class="controls">
                        <input type="Date" name="Date" class="span11" placeholder="Date"  />
                      </div>
                    </div>


                    <div class="alert alert-danger text-center" id="error" style="display: none;">
                        <strong>This Product is Already Exists ! Try Another</strong>
                    </div>

                    <div class="form-actions">
                      <button type="submit" name="submit1" class="btn btn-success">Save</button>
                    </div>

                    <div class="alert alert-success text-center" id="success" style="display: none;">
                        <strong>Record Inserted Successfully !</strong>
                    </div>

                  </form>
                </div>
              </div>
            </div>
            <!-- Add Export Button -->
            <form method="post" action="export_to_excel_bank.php">
                <input type="hidden" name="table_name" value="import_ketchen"> <!-- Table name -->
                <input type="hidden" name="filename" value="Import ketchen records"> <!-- Desired file name -->
                <button type="submit" style="color:Green; float:right;margin-right:40px ;margin-bottom:20px;" name="export">
                    Export to excel
    <a></a><i class="fas fa-file-excel"></i> </a>
</button>
            </form>

            <!-- Display all Products -->
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                    <th>id</th>
                      <th>product_name</th>
                      <th>unit</th>
                      <th>Qauntity</th>
                      <th>Price</th>
                      <th>Date</th>
                      <th>Action</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $res=mysqli_query($link,"select * from import_ketchen  ");
                    while($row=mysqli_fetch_array($res))
                    {
                        ?>
                        <tr>
                        <td><?php echo $row['id']; ?></td>
                          <td><?php echo $row['product_name']; ?></td>
                          <td><?php echo $row['unit']; ?></td>
                          <td><?php echo $row['Quantity']; ?></td>
                          <td><?php echo $row['Price']?></td>
                          <td><?php echo $row['Date']; ?></td>
                          
                          <td>
                          <center>
                            <a title="Edit" class="tip-bottom" href="edit_ketch_product.php?id=<?php echo $row['id']; ?>" style="color: green"><i class="icon icon-edit"></i></a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a title="Delete" class="tip-bottom" href="delete_kitchen_products.php?id=<?php echo $row['id']; ?>"style="color: red"><i class="icon icon-trash"></i></a>
                          </center>
                        </td>
                        </tr>
                        <?php
                    }

                    ?>
                    
                  </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php 
if(isset($_POST['submit1']))
{

    $product_name = mysqli_real_escape_string($link,$_POST['product_name']);
    $unit = mysqli_real_escape_string($link,$_POST['unit']);
    $Quantity = mysqli_real_escape_string($link,$_POST['Quantity']);
    $Price= mysqli_real_escape_string($link,$_POST['Price']);
    $Date = mysqli_real_escape_string($link,$_POST['Date']);

    $count=0;
    $res = mysqli_query($link,"select * from import_ketchen where product_name='$product_name' && unit='$unit' && Price='$Price' && Quantity='$Quantity' && Date='$Date' ");
    $count=mysqli_num_rows($res);

    if($count>0)
    {
        ?>
        <script type="text/javascript">
            document.getElementById("success").style.display="none";
            document.getElementById("error").style.display="block";
        </script>
        <?php
    }
    else{
        mysqli_query($link,"INSERT INTO `import_ketchen`(`product_name`, `unit`, `Quantity`,`Date`,`Price`) VALUES ('$product_name','$unit','$Quantity','$Date','$Price')  ");

        ?>
        <script type="text/javascript">
            document.getElementById("error").style.display="none";
            document.getElementById("success").style.display="block";
            setTimeout(function() {
                window.location.href=window.location.href;
            },1000);
        </script>
        <?php

    }
}

?>

<?php include "footer.php" ; ?>