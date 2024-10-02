<?php
include "../connection.php" ;
$company_name=$_GET['company_name'];
$product_name=$_GET['product_name'];
$unit=$_GET['unit'];
$res=mysqli_query($link,"select * from products where company_name='$company_name' && product_name='$product_name' && unit='$unit' ");

?>
<select class="span11" name="Quantity" id="Quantity">
	<option>Select packing size</option>
<?php

while($row=mysqli_fetch_array($res))
{
    echo "<option>";
    echo $row['Quantity'];
    echo "</option>";
}
echo "</select>";
?>