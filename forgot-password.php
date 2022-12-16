<?php

session_start();
include "database/connection.php";

if (isset($_SESSION['loggedIn'])) {

  $session_email = $_SESSION['loggedIn'];

  $user_query = "SELECT * FROM users WHERE email = '$session_email'";
  $user_result = mysqli_query($conn, $user_query);

  $user_data = mysqli_fetch_assoc($user_result);
  $db_type = $user_data['type'];

  if ($dp_type == 0) {
    header("Location: admin-dashboard.php");
  }
  if ($db_type == 1) {
    header("Location: user-dashboard.php");
  }
} else {

  if (isset($_POST['sendEmail'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = mysqli_query($conn, $sql);
    $isUser = mysqli_num_rows($result);

    if ($isUser) {
      $data = mysqli_fetch_assoc($result);
      $fullname = $data['fullname'];
      $token = $data['token'];

      $receiver = $email;
      $subject = "Reset Password! Prepare Pakistan";
      $body = "Hi | $fullname | Click the link to reset your password http://localhost/prepare-pakistan/update-password.php?token=$token";
      $sender = "From: userhack994@gmail.com";

      if (mail($receiver, $subject, $body, $sender)) {
        header("Location:forgot-password.php?success= Reset password link send successfully to $receiver");
      } else {
        header("Location:forgot-password.php?error= Sorry! failed to sending email");
      }
    } else {
      header("Location:forgot-password.php?error= Email doesn't exists in our database!");
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
      <div class="card border-success login-form mb-5">
        <div class="card-header text-center">
          <h3>Forgot Password</h3>
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


        <form action="" method="post">
          <div class="card-body">
            <div class="form-group">
              <input type="email" id="email" name="email" class="form-control" placeholder="Email*" required />
            </div>

            <button type="submit" name="sendEmail" class="btn btn-success mt-3 w-100">Send Email</button>
          </div>
        </form>
      </div>
    </div>

    <?php include "includes/footer.php" ?>

    
  </body>

  </html>
<?php
}
mysqli_close($conn);
?>