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
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i>General stock Report</a></div>
    </div>
    <!--End-breadcrumbs-->

    
    <form method="post" action="export_excel_report_all_stock.php" class="text-right">
        <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
        <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
        <button type="submit" style="color:white; margin-bottom:15px; background: #6F4E37; border-radius: 5px;" name="export">
            Export to Excel (CSV) <i class="fas fa-file-excel"></i>
        </button>
    </form>

    <!-- Results Section -->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="widget-content nopadding">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Total Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "
                                SELECT 
    'import_ketchen' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM import_ketchen

UNION ALL

SELECT 
    'bank' AS source, 
    NULL AS total_quantity, 
    IFNULL(SUM(Money), 0) AS total_price 
FROM bank

UNION ALL

SELECT 
    'served_ketchen' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM served_ketchen

UNION ALL

SELECT 
    'import_barista' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM import_barista

UNION ALL

SELECT 
    'served_barista' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM served_barista

UNION ALL

SELECT 
    'import_bar' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM import_bar

UNION ALL

SELECT 
    'served_bar' AS source, 
    IFNULL(SUM(Quantity), 0) AS total_quantity, 
    IFNULL(SUM(Price), 0) AS total_price 
FROM served_bar

UNION ALL

SELECT 
    'momo' AS source, 
    NULL AS total_quantity, 
    IFNULL(SUM(Money), 0) AS total_price 
FROM momo;

                            ";

                            $res = mysqli_query($link, $query);

                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<tr>";
                                echo "<td>{$row['source']}</td>";
                                echo "<td>{$row['total_quantity']}</td>";
                                echo "<td>{$row['total_price']} frw</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
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
