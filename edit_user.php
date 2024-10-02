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
$firstname="";
$lastname="";
$username="";
$password="";
$role="";
$status="";
$res=mysqli_query($link,"select * from user_registration where id='$id' ");
while($row=mysqli_fetch_array($res))
{
    $firstname=$row["firstname"];
    $lastname=$row["lastname"];
    $username=$row["username"];
    $password=$row["password"];
    $role=$row["role"];
    $status=$row["status"];
}
?>
<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i>
            Edit User</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">

        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">

           <div class="span12">
              <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                  <h5>Edit User</h5>
                </div>
                <div class="widget-content nopadding">
                  <form name="form1" action="" method="post" class="form-horizontal">
                    <div class="control-group">
                      <label class="control-label">First Name :</label>
                      <div class="controls">
                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="span11" placeholder="First name" />
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label">Last Name :</label>
                      <div class="controls">
                        <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="span11" placeholder="Last name" />
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">User Name :</label>
                      <div class="controls">
                        <input type="text" name="username" value="<?php echo $username; ?>" class="span11" placeholder="User name" readonly />
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">Password :</label>
                      <div class="controls">
                        <input type="password" name="password" value="<?php echo $password; ?>" class="span11" placeholder="Enter Password"  />
                      </div>
                    </div>

                   

                    <div class="form-actions">
                      <button type="submit" name="submit1" class="btn btn-success">Update</button>
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
    $firstname = mysqli_real_escape_string($link,$_POST['firstname']);
    $lastname = mysqli_real_escape_string($link,$_POST['lastname']);
    $password = mysqli_real_escape_string($link,$_POST['password']);
    

    mysqli_query($link,"UPDATE `user_registration` SET `firstname`='$firstname',`lastname`='$lastname',`password`='$password' WHERE id='$id' ") or die(mysqli_error($link));

    ?>
        <script type="text/javascript">
            document.getElementById("success").style.display="block";
            setTimeout(function() {
                window.location="add_new_user.php";
            },1000);
        </script>
    <?php
}
?>

<?php include "footer.php" ; ?>