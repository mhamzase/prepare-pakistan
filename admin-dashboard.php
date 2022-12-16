<?php
session_start();
include "database/connection.php";

if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $db_type = $user_data['type'];

    if ($db_type == 0 || $db_type == 2) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Dashboard</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/adminDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <style>
                .container-fluid a button {
                    width: 300px;
                    height: 150px;
                    margin: 20px;
                    font-size: 20px;
                    font-weight: bold;
                }

                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>

            <div class="container-fluid m-5">
                <a href="add-tests.php"><button type="button" class="btn btn-outline-info btn-lg "><i class="bi bi-journal-text m-3"></i>Add Tests</button></a>
                <a href="add-subjects.php"><button type="button" class="btn btn-outline-success btn-lg "><i class="bi bi-book-half m-3"></i>Add Subjects</button></a>
                <a href="add-chapters.php"><button type="button" class="btn btn-outline-primary btn-lg "><i class="bi bi-journal-bookmark-fill m-3"></i>Add Chapters</button></a>
                <a href="add-quizes.php"><button type="button" class="btn btn-outline-warning btn-lg "><i class="bi bi-card-list m-3"></i>Add Quiz's</button></a>
                <a href="students-results.php"><button type="button" class="btn btn-outline-light btn-lg "><i class="bi bi-card-list m-3"></i>Students Results</button></a>
                <?php
                if ($db_type == 0) {
                ?>
                    <a href="users-list.php"><button type="button" class="btn btn-outline-light btn-lg "><i class="bi bi-person-lines-fill m-3"></i> Users</button></a>

                <?php
                }
                ?>
            </div>
            <div class="w-100 row d-flex justify-content-center px-5">
                <img src="images/edu2.png" alt="" class="col-md-4">
                <img src="images/edu1.png" alt="" class="col-md-4">
                <img src="images/edu3.png" alt="" class="col-md-4">
            </div>

        </body>

        </html>

<?php
    } else {
        header("Location: user-dashboard.php");
    }
} else {
    header("Location: login-form.php");
}
mysqli_close($conn);
?>