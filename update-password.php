<?php

session_start();
include "database/connection.php";


if (isset($_GET['token'])) {
    if (isset($_POST['updatePassword'])) {

        $token = $_GET['token'];

        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if($newPassword == $confirmPassword)
		{
            $password = password_hash($newPassword,PASSWORD_BCRYPT);

            $sql = mysqli_query($conn,"UPDATE users SET password='$password' WHERE token='$token'");

            header("Location: login-form.php?resetPassword=Your password has been reset <br> Now you can login...");
        }
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    </head>

    <body>
        <!-- include header file -->
        <?php include "includes/header.php" ?>

        <div class="container" style="min-height: 518px;">
            <div class="card border-primary login-form mb-5">
                <div class="card-header text-center">
                    <h3>Update Password</h3>
                </div>

                <!-- Error messages -->
                <span class="badge bg-danger p-3 m-3" id="message"></span>

                <?php
                if (isset($_GET['error'])) {
                ?>
                    <span class='badge bg-danger p-3 m-3'><?php echo $_GET['error']; ?></span>
                <?php
                }
                if (isset($_GET['success'])) {
                ?>
                    <span class='badge bg-success p-3 m-3'><?php echo $_GET['success']; ?></span>
                <?php
                }
                ?>


                <form action="" method="post" onsubmit="return validateUpdatePassword()">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="New Password*" required />
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control mt-2" placeholder="Confirm Password*" required />
                        </div>

                        <button type="submit" name="updatePassword" class="btn btn-primary mt-3 w-100">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        <?php include "includes/footer.php" ?>

        <script>
            function validateUpdatePassword() {
                let newPassword = document.getElementById("newPassword");
                let confirmPassword = document.getElementById("confirmPassword");
                let message = document.getElementById("message");

                if (newPassword.value != confirmPassword.value) {
                    message.innerText = "Both password are not same !";
                    return false;
                } else {
                    return true;
                }
            }
        </script>
    </body>

    </html>

<?php
} else {
    echo "Sorry! We can't proceed your request without token...";
    die;
}
mysqli_close($conn);

?>