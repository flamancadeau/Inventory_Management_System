<?php
include "header.php" ;
include "connection.php" ;
?>

<!--main-container-part-->
<div id="content">
  <!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i>
    Generate report</a></div>
  </div>
  <!--End-breadcrumbs-->
  <!--Action boxes-->
  





  
  <div class="container-fluid">
    <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
      <!-- Display all Products -->
      <div class="widget-content nopadding">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>S.no</th>
              <th>Company Name</th>
              <th>Product Name</th>
              <th>Packing Size</th>
              <th>Quantity</th>
              <th>Selling Price</th>
              <th>Action</th>
              
            </tr>
          </thead>
          <tbody>
            <?php
            $count=0;
            $res=mysqli_query($link,"select * from stock_master ");
            while($row=mysqli_fetch_array($res))
            {
              $count++;
            ?>
            <tr>
              <td><?php echo $count; ?></td>
              <td><?php echo $row['product_company']; ?></td>
              <td><?php echo $row['product_name']; ?></td>
              <td><?php echo $row['Quantity']; ?> &nbsp;<?php echo $row['product_unit']; ?></td>
              <td><?php echo $row['product_qty']; ?></td>
              <td><?php echo $row['product_selling_price']; ?></td>
              <td>
                <center>
                  <a href="edit_stock_master.php?id=<?php echo $row['id']; ?>" style="color: #0903FD"><i class="icon icon-pencil"></i> edit</a>
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


<?php include "footer.php" ; ?>



<form class="form-horizontal" action="php_action/getOrderReport.php" method="post" id="getOrderReportForm">
  <div class="form-group row">
    <label for="startDate" class="col-sm-3 col-form-label">Start Date</label>
    <div class="col-sm-9">
      <input type="text" class="form-control text-center" id="startDate" name="startDate" placeholder="Start Date" />
    </div>
  </div>
  <div class="form-group row">
    <label for="endDate" class="col-sm-3 col-form-label">End Date</label>
    <div class="col-sm-9">
      <input type="text" class="form-control text-center" id="endDate" name="endDate" placeholder="End Date" />
    </div>
  </div>
  <div class="form-group row">
    <div class="col-sm-12 text-center">
      <button type="submit" class="btn btn-success" id="generateReportBtn">
        <i class="glyphicon glyphicon-ok-sign"></i> Generate Report
      </button>
    </div>
  </div>
</form>