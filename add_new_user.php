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

<script>
// JavaScript to prompt for username and password before allowing Edit/Delete actions
function secondAuthentication() {
    let storedUsername = "<?php echo $_SESSION['username']; ?>";
    let storedPassword = "<?php echo $_SESSION['password']; ?>";

    // Prompt user for username and password
    let username = prompt("Please enter your username to access edit/delete functionality:");
    let password = prompt("Please enter your password:");

    // Check if the entered username and password match the session values
    if (username === storedUsername && password === storedPassword) {
        // Show the Edit/Delete buttons if the user is authenticated
        let editButtons = document.querySelectorAll('.editButton');
        let deleteButtons = document.querySelectorAll('.deleteButton');
        editButtons.forEach(button => button.style.display = "inline");
        deleteButtons.forEach(button => button.style.display = "inline");
        
        // Show the Save button
        document.getElementById("saveButton").style.display = "block";
    } else {
        // Hide the Edit/Delete buttons and Save button if authentication fails
        alert("Invalid credentials! You cannot edit or delete users.");
        let editButtons = document.querySelectorAll('.editButton');
        let deleteButtons = document.querySelectorAll('.deleteButton');
        editButtons.forEach(button => button.style.display = "none");
        deleteButtons.forEach(button => button.style.display = "none");
        
        // Ensure the Save button is hidden
        document.getElementById("saveButton").style.display = "none";
    }
}

// Call the second authentication function when the page loads
window.onload = secondAuthentication;
</script>

<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="" class="tip-bottom"><i class="icon-home"></i> Add New User</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <!-- Add new User -->
            <div class="span12">
              <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                  <h5>Add New User</h5>
                </div>
                <div class="widget-content nopadding" id="addNewUserForm" style="display: block;">
                  <form name="form1" action="" method="post" class="form-horizontal">
                    <div class="control-group">
                      <label class="control-label">First Name :</label>
                      <div class="controls">
                        <input type="text" name="firstname" class="span11" placeholder="First name" />
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label">Last Name :</label>
                      <div class="controls">
                        <input type="text" name="lastname" class="span11" placeholder="Last name" />
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">User Name :</label>
                      <div class="controls">
                        <input type="text" name="username" class="span11" placeholder="User name" />
                      </div>
                    </div>

                    <div class="control-group">
                      <label class="control-label">Password</label>
                      <div class="controls">
                        <input type="password" name="password" class="span11" placeholder="Enter Password" />
                      </div>
                    </div>

                    <div class="alert alert-danger text-center" id="error" style="display: none;">
                        <strong>This Username Already Exists! Try Another</strong>
                    </div>

                    <div class="form-actions">
                      <button type="submit" id="saveButton" style=" background: #6F4E37;border-radius:5px" name="submit1" class="btn btn-success" style="display: none;border-radius:5px;">Save</button>
                    </div>

                    <div class="alert alert-success text-center" id="success" style="display: none;">
                        <strong>Record Inserted Successfully!</strong>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Display all users -->
            <div class="widget-content nopadding">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Username</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $res = mysqli_query($link, "select * from user_registration");
                    while ($row = mysqli_fetch_array($res)) {
                    ?>
                        <tr>
                          <td><?php echo $row['firstname']; ?></td>
                          <td><?php echo $row['lastname']; ?></td>
                          <td><?php echo $row['username']; ?></td>
                          <td><a href="edit_user.php?id=<?php echo $row['id']; ?>" class="editButton" style="display:none;color:Green;"><i class="icon icon-edit"></i></a></td>
                          <td><a href="delete_user.php?id=<?php echo $row['id']; ?>" class="deleteButton" style="display:none;color:red;"> <i class="icon icon-trash"></i</a></td>
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
    $firstname = mysqli_real_escape_string($link, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($link, $_POST['lastname']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);

    $count = 0;
    $res = mysqli_query($link, "select * from user_registration where username='$username'");
    $count = mysqli_num_rows($res);

    if ($count > 0) {
?>
        <script type="text/javascript">
            document.getElementById("success").style.display = "none";
            document.getElementById("error").style.display = "block";
        </script>
    <?php
    } else {
        mysqli_query($link, "INSERT INTO user_registration (firstname, lastname, username, password) VALUES ('$firstname', '$lastname', '$username', '$password')");
    ?>
        <script type="text/javascript">
            document.getElementById("error").style.display = "none";
            document.getElementById("success").style.display = "block";
            setTimeout(function() {
                window.location.href = window.location.href;
            }, 1000);
        </script>
<?php
    }
}
?>

<?php include "footer.php"; ?>
