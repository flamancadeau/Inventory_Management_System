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

$Money="";
$Date="";

$res=mysqli_query($link,"select * from momo where id='$id' ");
while($row=mysqli_fetch_array($res))
{
   
  
    $Money=$row["Money"];
    $Date=$row["Date"];
  
   
}
?>
<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i>
            Edit Momo pay</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">

        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">

           <div class="span12">
              <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                  <h5>Edit Momo pay</h5>
                </div>
                <div class="widget-content nopadding">
                  <form name="form1" action="" method="post" class="form-horizontal">

    
                    <div class="control-group">
                      <label class="control-label">Money</label>
                      <div class="controls">
                        <input type="text" name="Money" class="span11" value="<?php echo $Money?>" placeholder="Price"  />
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

if(isset($_POST['submit1']))
{

    $Money = mysqli_real_escape_string($link,$_POST['Money']);
    $Date = mysqli_real_escape_string($link,$_POST['Date']);

    mysqli_query($link,"UPDATE `momo` SET `Money`='$Money',`Date`='$Date'WHERE id='$id' ") or die(mysqli_error($link));

    ?>
        <script type="text/javascript">
            document.getElementById("success").style.display="block";
            setTimeout(function() {
                window.location="Momo_pay.php";
            },1000);
        </script>
    <?php
}
?>

<?php include "footer.php" ; ?>