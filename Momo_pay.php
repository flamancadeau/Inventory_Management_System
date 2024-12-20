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
    <div id="breadcrumb"><a href="Momo_pay.php" class="tip-bottom"><i class="icon-home"></i>
        Momo records</a></div>
  </div>
  <!--End-breadcrumbs-->

  <!--Action boxes-->
  <div class="container-fluid">
    <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
      <!-- Add new Product -->
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5> Momo records</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" action="" method="post" class="form-horizontal">



              <div class="control-group">
                <label class="control-label">Money</label>
                <div class="controls">
                  <input type="text" name="Money" class="span11" placeholder=".........frw........" />
                </div>


              </div>
              <div class="control-group">
                <label class="control-label">Date</label>
                <div class="controls">
                  <input type="datetime-local" name="Date" class="span11" value="<?php echo date('Y-m-d\TH:i'); ?>"
                    placeholder="Date" />

                </div>
              </div>


              <div class="alert alert-danger text-center" id="error" style="display: none;">
                <strong>This Product is Already Exists ! Try Another</strong>
              </div>

              <div class="form-actions">
                <button type="submit" name="submit1" style=" background: #6F4E37;border-radius:5px"
                  class="btn btn-success">Save</button>
              </div>

              <div class="alert alert-success text-center" id="success" style="display: none;">
                <strong>Record Inserted Successfully !</strong>
              </div>

            </form>
          </div>
        </div>
      </div>

      <form method="post" action="export_to_excel_momo.php">
        <input type="hidden" name="Momo" value="Momo"> <!-- Table name -->
        <input type="hidden" name="filename" value="Momo_pay"> <!-- Desired file name -->
        <button type="submit"
          style="color: white; float: right; margin-right: 40px; margin-bottom: 20px; background: #6F4E37;"
          name="export">
          <i class="fas fa-file-excel"></i> Export to Excel
        </button>
      </form>

      <!-- Display all Products -->
      <div class="widget-content nopadding">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>

              <th>id</th>
              <th>Money</th>
              <th>Date</th>
              <th>Action</th>

            </tr>
          </thead>
          <tbody>
            <?php
            $res = mysqli_query($link, "select * from momo  ");
            while ($row = mysqli_fetch_array($res)) {
              ?>
              <tr>

                <td><?php echo $row['id'] ?></td>
                <td><?php echo $row['Money'] ?></td>
                <td><?php echo $row['Date']; ?></td>

                <td>
                  <center>
                    <a title="Edit" class="tip-bottom" href="edit_Momo_pay.php?id=<?php echo $row['id']; ?>"
                      style="color: green"><i class="icon icon-edit"></i></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a title="Delete" class="tip-bottom" href="delete_momo.php?id=<?php echo $row['id']; ?>"
                      style="color: red"><i class="icon icon-trash"></i></a>
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
if (isset($_POST['submit1'])) {


  $Money = mysqli_real_escape_string($link, $_POST['Money']);
  $Date = mysqli_real_escape_string($link, $_POST['Date']);

  $count = 0;
  $res = mysqli_query($link, "select * from momo where  Money='$Money' && Date='$Date' ");
  $count = mysqli_num_rows($res);

  if ($count > 0) {
    ?>
    <script type="text/javascript">
      document.getElementById("success").style.display = "none";
      document.getElementById("error").style.display = "block";
    </script>
    <?php
  } else {
    mysqli_query($link, "INSERT INTO `momo`(`Money`,`Date`) VALUES ('$Money','$Date')  ");

    ?>
    <script type="text/javascript">
      document.getElementById("error").style.display = "none";
      document.getElementById("success").style.display = "block";
      setTimeout(function () {
        window.location.href = window.location.href;
      }, 1000);
    </script>
    <?php

  }
}

?>

<?php include "footer.php"; ?>