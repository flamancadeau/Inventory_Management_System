<?php 
include "connection.php"; 
session_start(); // Start session at the top of the file

// Check if the user is already logged in
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    // Redirect to add_new_user.php if the session is already set
    header("Location: add_new_user.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login ||Inventory Management System</title>
    <meta charset="UTF-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="font-awesome/css/font-awesome.css"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Open Sans', sans-serif;
        }
        #loginbox {
            width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .control-group {
            margin-bottom: 20px;
        }
        .control-group h3 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .main_input_box {
            position: relative;
            width: 100%;
        }
        .main_input_box input {
            width: 80%;
            padding: 10px 30px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        .main_input_box input:focus {
            border-color: #5cb85c;
            box-shadow: 0 0 5px rgba(92, 184, 92, 0.5);
        }
        .add-on {
            position: absolute;
            left: 10px;
            top: 10px;
            font-size: 18px;
            color: #aaa;
        }
        .form-actions {
            text-align: center;
        }
        .btn-success {
            background-color: #5cb85c;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-success:hover {
            background-color: #4cae4c;
        }
        .alert {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div id="loginbox">
    <form name="form1" class="form-vertical" action="" method="post">
        <div class="control-group normal_text"><h3 >Login Page</h3></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on"><i class="icon-user"></i></span>
                    <input type="text" placeholder="Username" name="username" required />
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on"><i class="icon-lock"></i></span>
                    <input type="password" placeholder="Password" name="password" required />
                </div>
            </div>
        </div>
        <div class="form-actions">
            <input type="submit" name="submit1" style=" background: #6F4E37;" value="Login" class="btn btn-success"/>
        </div>
    </form>
    <?php
    if (isset($_POST['submit1'])) {
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $password = mysqli_real_escape_string($link, $_POST['password']);
        
        // Check for username and password in the database
        $res = mysqli_query($link, "SELECT * FROM user_registration WHERE username='$username' and password='$password' ");
        
        if (mysqli_num_rows($res) > 0) {
            // Store both username and password in session
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;  // Not recommended to store plain password
            
            echo '<div class="alert alert-success"><strong>Login successful! Redirecting...</strong></div>';
            echo '<script type="text/javascript">setTimeout(function(){ window.location="add_new_user.php"; }, 2000);</script>';
        } else {
            echo '<div class="alert alert-danger"><strong>Invalid Username or Password!</strong></div>';
        }
    }
    ?>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/matrix.login.js"></script>
</body>
</html>

<style>
  body{
    background: #6F4E37;
  }   
  h3{
    color: yellow;
  }
   
</style>