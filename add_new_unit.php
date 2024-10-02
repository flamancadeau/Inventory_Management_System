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
            Add New Unit</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <!-- Add new User -->
            <div class="span12">
              <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                  <h5>Add New Unit</h5>
                </div>
                <div class="widget-content nopadding">
                  <form name="form1" action="" method="post" class="form-horizontal">
                    <div class="control-group">
                      <label class="control-label">Unit Name :</label>
                      <div class="controls">
                        <input type="text" name="unit" class="span11" placeholder="Unit name" />
                      </div>
                    </div>

                    <div class="alert alert-danger text-center" id="error" style="display: none;">
                        <strong>This Unit Already Exists ! Use Another</strong>
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
         <form method="post" action="export_excel_add_new_unit.php">
                <input type="hidden" name="table_name" value="units"> <!-- Table name -->
                <input type="hidden" name="filename" value="Qantity unit"> <!-- Desired file name -->
                <button type="submit" style="color:Green; float:right;margin-right:40px ;margin-bottom:20px;" name="export">
                    Export to excel
    <a></a><i class="fas fa-file-excel"></i> </a>
</button>
            </form>
            <!-- Display all user -->
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Unit Name</th>
                      
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $res=mysqli_query($link,"select * from units ");
                    while($row=mysqli_fetch_array($res))
                    {
                        ?>
                        <tr>
                          <td><?php echo $row['id']; ?></td>
                          <td><?php echo $row['unit']; ?></td>
                          
                          <td><center><a href="edit_unit.php?id=<?php echo $row['id']; ?>"style="color: green" >Edit</a></center></td>
                          <td><center><a href="delete_unit.php?id=<?php echo $row['id']; ?>"style="color: red">Delete</a></center></td>
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
    $unit = mysqli_real_escape_string($link,$_POST['unit']);
   

    $count=0;
    $res = mysqli_query($link,"select * from units where unit='$unit' ");
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
        mysqli_query($link,"INSERT INTO `units`(`unit`) VALUES ('$unit')  ");

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