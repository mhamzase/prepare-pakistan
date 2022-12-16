<?php

session_start();
include "database/connection.php";

if (isset($_SESSION['loggedIn'])) {

  $session_email = $_SESSION['loggedIn'];

  $user_query = "SELECT * FROM users WHERE email = '$session_email'";
  $user_result = mysqli_query($conn, $user_query);

  $user_data = mysqli_fetch_assoc($user_result);
  $db_type = $user_data['type'];

  if ($dp_type == 0 || $dp_type == 2) {
    header("Location: admin-dashboard.php");
  }
  if ($db_type == 1) {
    header("Location: user-dashboard.php");
  }
} else {

  if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = mysqli_query($conn, $sql);
    $isUser = mysqli_num_rows($result);

    if ($isUser) {
      $user_data = mysqli_fetch_assoc($result);

      $db_password = $user_data['password'];
      $password_check = password_verify($password, $db_password);

      if ($password_check) {

        $type = $user_data['type'];

        if ($type == 0) {
          $_SESSION["loggedIn"] = $email;
          header("Location: admin-dashboard.php");
        } else {
          $_SESSION["loggedIn"] = $email;
          header("Location:user-dashboard.php");
        }
      } else {
        header("Location:login-form.php?error= username or password incorrect !");
        
      }
    } else {
      header("Location:login-form.php?error= username or password incorrect !");
     
    }
  }



?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
          <p class="display-4">Login</p>
        </div>

        <!-- Error messages -->
        <span class="badge bg-danger p-3 m-3" id="message"></span>

        <?php
        if (isset($_GET['error'])) {
        ?>
          <span class='badge bg-danger p-3 m-3'><?php echo $_GET['error']; ?></span>
        <?php
        }

        if (isset($_GET['resetPassword'])) {
          ?>
            <span class='badge bg-info p-3 m-3'><?php echo $_GET['resetPassword']; ?></span>
          <?php
          }
        ?>


        <form action="" method="post" onsubmit="return validateLoginForm()">
          <div class="card-body">
            <div class="form-group">
              <input type="email" id="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email*" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"/>
              <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <input type="password" id="password" name="password" class="form-control mt-3" id="exampleInputPassword1" placeholder="Password*" />
            </div>
            <div class="form-group">
            <a style="float: right;" class="mt-3" href="forgot-password.php">Forgot Passowrd?</a>
            </div>
            <button type="submit" name="login" class="btn btn-success mt-5 w-100">Login</button>
          </div>
        </form>
      </div>
    </div>

    <?php include "includes/footer.php" ?>

    <script src="js/login.js"></script>
  </body>

  </html>
<?php
}
mysqli_close($conn);
?>