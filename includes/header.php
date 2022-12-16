<?php
include "database/connection.php";
?>

<link rel="stylesheet" href="../css/header.css">
<style>
  .logoText {
    width: 13% !important;
    height: 3em !important;
    font-size: 18px
  }

  .nav-link{
    font-size: 20px;
  }
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-weight:bold">
  <div class="container-fluid">
    <img src="images/logo.png" alt="Logo is here" width="4%">

    <?php
    if (isset($_SESSION['loggedIn'])) {
      $sql_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$_SESSION[loggedIn]'");
      $fetch_data = mysqli_fetch_assoc($sql_user);

      if ($fetch_data['type'] == 1) {
    ?>
        <div class="collapse navbar-collapse" id="navbarColor01">
          <ul class="navbar-nav me-auto float-right">
            <li class="nav-item">
              <a class="nav-link" href="/prepare-pakistan">Home
                <span class="visually-hidden">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about-us.php">About us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact-us.php">Contact us</a>
            </li>
          </ul>
        </div>
      <?php
      } else {
        echo "";
      }
    } else {
      ?>
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto float-right">
          <li class="nav-item">
            <a class="nav-link" href="/prepare-pakistan">Home
              <span class="visually-hidden">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about-us.php">About us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact-us.php">Contact us</a>
          </li>
        </ul>
      </div>
    <?php
    }
    ?>

    <div class="auth">
      <?php
      if (isset($_SESSION['loggedIn'])) {
      ?>
        <div class="dropdown mr-5">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php


            echo $fetch_username = $fetch_data['username'];
            ?>
          </button>
          <a href="user-dashboard.php" class="btn btn-primary bg-dark text-ligh" type="button">Dashboard</a>

          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php
            if ($fetch_data['type'] == 1) {
            ?>
              <a class="dropdown-item" href="user-profile.php">Profile</a>
              <a class="dropdown-item" href="user-results.php">Results</a>
            <?php
            }
            ?>
            <form action="" method="post">
              <button type="submit" name="logout" class="dropdown-item">Logout</button>
            </form>
          </div>
        </div>

      <?php
      } else {
      ?>
        <div class="collapse navbar-collapse" id="navbarColor01">
          <ul class="navbar-nav me-auto">

            <li class="nav-item">
              <a class="nav-link" href="login-form.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="registration-form.php">Register</a>
            </li>
          </ul>
        </div>
      <?php
      }

      ?>

    </div>
  </div>
</nav>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
<?php
if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: login-form.php");
}
?>