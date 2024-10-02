<?php
session_start();
$max=0;
if(isset($_SESSION['cart']))
{
    $max = sizeof($_SESSION['cart']);
}
for ($i = 0;$i < $max; $i++)
{
    if (isset($_SESSION['cart'][$i])) {
        $company_name = "";
        $product_name = "";
        $unit = "";
        $Quantity = "";
        $qty="";
        while (list ($key, $val) = each($_SESSION['cart'][$i]))
        {
            if ($key == "company_name") {
                $company_name = $val;
            } else if ($key == "product_name") {
                $product_name = $val;
            } else if ($key == "unit") {
                $unit = $val;
            } else if ($key == "Quantity") {
                $Quantity = $val;
            }
            else if($key=="qty")
            {
                $qty=$val;
            }
        }
        echo $company_name." ".$product_name." ".$unit." ".$Quantity." ".$qty;
        echo "<br>";
    }
}
?>
