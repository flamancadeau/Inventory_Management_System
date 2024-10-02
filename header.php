<?php

include "connection.php";
session_start();

// Check if the user is logged in with both username and password
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php"); 
    exit();


 
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vella| Admin</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="css/fullcalendar.css"/>
    <link rel="stylesheet" href="css/matrix-style.css"/>
    <link rel="stylesheet" href="css/matrix-media.css"/>
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/jquery.gritter.css"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">


</head>
<body>


<div id="header">

    <h2 style="position: absolute">
        <p style="font-size:46px;margin-left:30px;font-family:Brush Script MT;color:color:#ffd700;">vella coffee</p>
    </h2>
</div>



<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
    <ul class="nav">
        <li class="dropdown" id="profile-messages">
            <a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i
                    class="icon icon-user"></i>  <span class="text">Welcome <?php echo $_SESSION['username']; ?></span><b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="my_profile.php"><i class="icon-user"></i> My Profile</a></li>
               
                <li class="divider"></li>
                <li><a href="logout.php"><i class="icon-key"></i> Log Out</a></li>
            </ul>
        </li>
    </ul>
</div>
<!--sidebar-menu-->
<div id="sidebar">
    <ul>
        <li><a href=""><i class="icon icon-home"></i><span>Dashboard</span></a></li>
        <li><a href="add_new_user.php"><i class="bi bi-person"></i><span>Activate New User</span></a></li>
        <li><a href="add_new_unit.php"><i class="bi bi-plus"></i><span>Register New Unit</span></a></li>
        
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="bi bi-box"></i>
                <span>Stock</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="add_kitchen_products.php">Kitchen</a></li>
                <li><a href="add_barista_products.php">Barista</a></li>
                <li><a href="add_bar_products.php">Bar</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="bi bi-box-arrow-right"></i>
                <span>Export Inventory</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="served_kitchen_products.php">Kitchen</a></li>
                <li><a href="served_barista_product.php">Barista</a></li>
                <li><a href="served_bar_products.php">Bar</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="bi bi-wallet"></i>
                <span>Transaction</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li ><a href="cash_bank.php">Cash at Bank</a></lic>
                <li><a href="Momo_pay.php">Momo Pay</a></li>
            </ul>
        </li>

    


        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-file-alt"></i>
                <span>Generate Report</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="">Kitchen</a></li>
                <li><a href="">Barista</a></li>
                <li><a href="p">Bar</a></li>
            </ul>
        </li>

        
    </ul>
</div>




<div id="search">
    <form action="logout.php" method="post">
        <button type="submit" style="background:none; border:none; color:white; cursor:pointer;">
            <i class="fas fa-sign-out-alt"></i><span>LogOut</span>
        </button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').click(function(e) {
            e.preventDefault(); // Prevent default anchor click behavior
            $(this).next('.dropdown-menu').toggle(); // Toggle the dropdown menu
        });
    });
</script>