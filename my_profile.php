<?php
include "header.php";
include "connection.php";
session_start();

// Check if the user is logged in with both username and password
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: logout.php"); 
    exit();
}

// Fetch user data from the database
$username = $_SESSION['username'];
$result = mysqli_query($link, "SELECT * FROM user_registration WHERE username='$username'");
$user_data = mysqli_fetch_assoc($result);

// Handle profile update
if (isset($_POST['update_profile'])) {
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $telephone = mysqli_real_escape_string($link, $_POST['telephone']);
    $image = $_FILES['profile_image']['name'];

    // Update image if uploaded
    if ($image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);
        mysqli_query($link, "UPDATE user_registration SET email='$email', telephone='$telephone', image='$image' WHERE username='$username'");
    } else {
        mysqli_query($link, "UPDATE user_registration SET email='$email', telephone='$telephone' WHERE username='$username'");
    }

    header("Location: profile.php");
}

// Handle password reset
if (isset($_POST['reset_password'])) {
    $previous_password = mysqli_real_escape_string($link, $_POST['previous_password']);
    $new_password = mysqli_real_escape_string($link, $_POST['new_password']);

    if ($previous_password === $_SESSION['password']) {
        mysqli_query($link, "UPDATE user_registration SET password='$new_password' WHERE username='$username'");
        $_SESSION['password'] = $new_password;
        echo "<script>alert('Password updated successfully!');</script>";
    } else {
        echo "<script>alert('Previous password is incorrect!');</script>";
    }
}
?>

<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="cash_bank.php" class="tip-bottom"><i class="icon-home"></i>
           MY profiles</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">


        <div class="profile-box">
    <h3> <?php echo $_SESSION['username']; ?></h3>

   
        <!-- Profile Image -->
        <div class="form-group">
            <?php if ($user_data['image']) { ?>
                <img src="uploads/<?php echo $user_data['image']; ?>" alt="Profile Image">
            <?php } else { ?>
                <img src="default-profile.png" alt="Profile Image">
            <?php } ?>
            <input type="file" name="profile_image" class="span11">
        </div>

        <!-- Username (readonly) -->
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $user_data['username']; ?>"class="span11" p readonly>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user_data['email']; ?>"class="span11" prequired>
        </div>

        <!-- Telephone -->
        <div class="form-group">
            <label>Telephone:</label>
            <input type="text" name="telephone" value="<?php echo $user_data['telephone']; ?>" class="span11"  required>
        </div>

        <!-- Update Profile Button -->
        <div class="form-group">
            <input type="submit" name="update_profile" value="Update Profile" class="span11" >
        </div>
    </form>

        <hr>
    <h4>Reset Password</h4>
    <form method="post">
        <!-- Previous Password -->
        <div name="form1" action="" method="post" class="form-horizontal">
            <label>Previous Password:</label>
            <input type="password" name="previous_password" class="span11" p required>
        </div>
        <!-- New Password -->
        <div class="form-group">
            <label>New Password:</label>
            <input type="password" name="new_password" class="span11" p required>
        </div>
        <!-- Reset Password Button -->
        <div class="form-group">
            <input type="submit" name="reset_password" value="Reset Password" class="span11" p>
        </div>
    </form>
</div>

<?php include "footer.php"; ?>