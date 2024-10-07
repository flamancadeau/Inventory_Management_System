<?php
include "header.php";
include "connection.php";
session_start();

// Check if the user is logged in with both username and password
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php"); 
    exit();
}

// Initialize variables for search
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

?>

<!--main-container-part-->
<div id="content">
  <!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i> Kitchen Report</a></div>
  </div>
  <!--End-breadcrumbs-->

  <!-- Date Filter Form -->
  <div class="container-fluid">
    <form action="" method="POST" class="form-inline">
      <div class="form-group">
        <label for="start_date" class="control-label">Start Date:</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
      </div>
      <div class="form-group">
        <label for="end_date" class="control-label">End Date:</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
      </div>
      <button type="submit" style=" background: #6F4E37;" class="btn btn-primary">Search</button>
    </form>
  </div>

  <!-- Add Export Button -->
  <form method="post" action="export_excel_report_ketchen.php" class="text-right">
      <button type="submit" style="color:white; background: #6F4E37; margin-bottom:20px;" name="export">
          Export to Excel (CSV) <i class="fas fa-file-excel"></i>
      </button>
  </form>

  <!-- Results Section -->
  <div class="container-fluid">
    <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
      <!-- Display all Products -->
      <div class="widget-content nopadding">
        <div class="table-responsive" style="overflow-x: auto;">
          <table class="table table-bordered table-striped medium-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Imported Product</th>
                <th>Imported Unit</th>
                <th>Imported Quantity</th>
                <th>Imported Price (frw)</th>
                <th>Imported Date</th>
                <th>Exported Product</th>
                <th>Exported Unit</th>
                <th>Exported Quantity</th>
                <th>Exported Price (frw)</th>
                <th>Exported Date</th>
                <th>Remaining in Stock</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total_imported_ketchen = 0;
              $total_exported_ketchen = 0;

              // If date range is provided, filter the records
              if ($start_date && $end_date) {
                $query = "
                  SELECT 
                    import_ketchen.id, 
                    import_ketchen.product_name AS imported_product, 
                    import_ketchen.unit AS imported_unit, 
                    import_ketchen.Quantity AS imported_quantity, 
                    import_ketchen.Price AS imported_price, 
                    import_ketchen.Date AS imported_Date, 
                    served_ketchen.product_name AS exported_product, 
                    served_ketchen.unit AS exported_unit, 
                    served_ketchen.Quantity AS exported_quantity, 
                    served_ketchen.Price AS exported_price, 
                    served_ketchen.Date AS exported_Date 
                  FROM import_ketchen 
                  LEFT JOIN served_ketchen 
                    ON import_ketchen.id = served_ketchen.id
                  WHERE import_ketchen.Date BETWEEN '$start_date' AND '$end_date' 
                  OR served_ketchen.Date BETWEEN '$start_date' AND '$end_date'";
                
                $res = mysqli_query($link, $query);
              
                while ($row = mysqli_fetch_array($res)) {
                  $imported_quantity = $row['imported_quantity'];
                  $exported_quantity = $row['exported_quantity'];
                  $imported_price = $row['imported_price'];
                  $exported_price = $row['exported_price'];

                  // Accumulate totals
                  $total_imported_ketchen += $imported_quantity;
                  $total_exported_ketchen += $exported_quantity;

                  // Calculate remaining stock
                  $remaining_stock = $imported_quantity - $exported_quantity;
              ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['imported_product']; ?></td>
                <td><?php echo $row['imported_unit']; ?></td>
                <td><?php echo $imported_quantity; ?></td>
                <td><?php echo number_format($imported_price, 2) . " frw"; ?></td>
                <td><?php echo $row['imported_Date']; ?></td>
                <td><?php echo $row['exported_product']; ?></td>
                <td><?php echo $row['exported_unit']; ?></td>
                <td><?php echo $exported_quantity; ?></td>
                <td><?php echo number_format($exported_price, 2) . " frw"; ?></td>
                <td><?php echo $row['exported_Date']; ?></td>
                <td><?php echo $remaining_stock; ?></td>
              </tr>
              <?php
                }
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" style="text-align: right;">Total Imported:</th>
                <th><?php echo $total_imported_ketchen; ?></th>
                <th><?php echo number_format($total_imported_ketchen, 2) . " frw"; ?></th>
                <th colspan="5" style="text-align: right;">Total Exported:</th>
                <th><?php echo $total_exported_ketchen; ?></th>
                <th><?php echo number_format($total_exported_ketchen, 2) . " frw"; ?></th>
              </tr>
              <tr>
                <th colspan="10" style="text-align: right;">Remaining Stock:</th>
                <th><?php echo $total_imported_ketchen - $total_exported_ketchen; ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>

<style>


.form-group {
  margin-bottom: 20px; /* add some space between form groups */
}

.control-label {
  font-weight: bold; /* make labels bold */
  margin-right: 10px; /* add some space between label and input */
}



.btn-primary {
  background-color: #337ab7; /* blue background */
  border-color: #2e6da4; /* darker blue border */
  color: #fff; /* white text color */
  padding: 6px 12px; /* add some padding to the button */
  font-size: 14px; /* adjust the font size to your liking */
  line-height: 1.42857143; /* adjust the line height to your liking */
  border-radius: 4px; /* add some rounded corners */
  cursor: pointer; /* change the cursor to a pointer on hover */
}

.btn-primary:hover {
  background-color: #23527c; /* darker blue background on hover */
  border-color: #1d4e7a; /* darker blue border on hover */
}
</style>