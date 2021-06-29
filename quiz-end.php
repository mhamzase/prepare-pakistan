<?php
session_start();
include "database/connection.php";


if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $user_id = $user_data['id'];
    $db_type = $user_data['type'];

    if ($db_type == 1) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Quiz Area</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/userDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"> -->

            <style>
                .notify {
                    margin-top: 300px;
                    margin-left: 22%;
                }
            </style>
        </head>

        <body>
                <?php include "chapter-quiz-reset.php" ?>
                <?php include "fullpaper-test-reset.php" ?>
            <div class="container-fluid">

                <div class="list-group w-50 notify">
                    <span class="list-group-item list-group-item-action bg-primary text-center display-4">Quiz Ended !</span>
                    <span class="list-group-item list-group-item-action text-success text-center">You are redirecting... <br />to Results Page</span>


                    <?php

                    header("refresh:2;url=user-results.php");

                    ?>
                </div>

            </div>

            <script>
                if (localStorage.getItem("question_timer")) {
                    localStorage.removeItem("question_timer");
                }
            </script>
        </body>

        </html>

<?php
    } else {
        header("Location: admin-dashboard.php");
    }
} else {
    header("Location: login-form.php");
}
mysqli_close($conn);
?>