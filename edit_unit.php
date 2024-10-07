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
$unit="";

$res=mysqli_query($link,"select * from units where id='$id' ");
while($row=mysqli_fetch_array($res))
{
    $unit=$row["unit"];
}
?>
<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i>
            Edit Unit</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">

        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">

           <div class="span12">
              <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                  <h5>Edit Unit</h5>
                </div>
                <div class="widget-content nopadding">
                  <form name="form1" action="" method="post" class="form-horizontal">
                    <div class="control-group">
                      <label class="control-label">Unit Name :</label>
                      <div class="controls">
                        <input type="text" name="unit" value="<?php echo $unit; ?>" class="span11" placeholder="Unit name" />
                      </div>
                    </div>
                    
                    <div class="form-actions">
                      <button type="submit" name="submit1" style=" background: #6F4E37;border-radius:5px+++++" class="btn btn-success">Update</button>
                    </div>

                    <div class="alert alert-success text-center" id="success" style="display: none;">
                        <strong>Unit Updated Successfully !</strong>
                    </div>

                  </form>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<?php

if(isset($_POST['submit1']))
{
    $unit = mysqli_real_escape_string($link,$_POST['unit']);

    mysqli_query($link,"UPDATE `units` SET `unit`='$unit' WHERE id='$id' ") or die(mysqli_error($link));

    ?>
        <script type="text/javascript">
            document.getElementById("success").style.display="block";
            setTimeout(function() {
                window.location="add_new_unit.php";
            },1000);
        </script>
    <?php
}
?>

<?php include "footer.php" ; ?>