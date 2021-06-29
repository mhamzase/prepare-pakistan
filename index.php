<?php session_start();

include "database/connection.php";

if (isset($_SESSION['loggedIn'])) {
    $sql_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$_SESSION[loggedIn]'");
    $fetch_data = mysqli_fetch_assoc($sql_user);

    if ($fetch_data['type'] == 0) {
        header("Location: admin-dashboard.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prepare Pakistan</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .container {
            position: relative;
        }

        #homeText {
            position: absolute;
            margin-top: 150px;
            font-size: 150px;
            text-shadow: 3px 3px 10px 10px black;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- include header file -->
    <?php include "includes/header.php" ?>

    <!-- Welcome Page / Home Page -->
    <div class="container">
        <h1 id="homeText">Prepare <br />  <span class="text-success">PAKISTAN</span></h1>
    </div> 
    <img src="images/home.jpg" alt="home wallpers" height="700px" width="100%">


    <?php include "includes/footer.php" ?>

</body>

</html>