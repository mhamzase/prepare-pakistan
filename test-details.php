<?php
session_start();
include "database/connection.php";

if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $db_type = $user_data['type'];

    if ($db_type == 1) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Test Details</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/userDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

            <style>
                .container-fluid a button {
                    width: 30%;
                    height: 150px;
                    margin: 20px;
                    font-size: 30px;
                    font-weight: bold;
                }

                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>

            <a href="user-dashboard.php"><button type="button" class="btn btn-light  m-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>

            <div class="container-fluid">


                <?php
                if (isset($_GET['test_id'])) {
                    $test_id = $_GET['test_id'];

                    $tests = mysqli_query($conn, "SELECT * FROM tests WHERE ID = '$test_id'");
                    $fetch_name = mysqli_fetch_assoc($tests);
                    $test_name = $fetch_name['name'];
                ?>
                    <h3 class="text-light text-center m-5">Test : <span class="text-success"><?php echo $test_name; ?></span></h3>

                    <?php


                    $subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE test_id = '$test_id'");

                    ?>
                    <div class="card border-primary mb-3" style="width:100%">
                        <div class="card-header text-center">
                            <h4>Subjects</h4>
                        </div>
                        <div class="card-body">
                            <?php

                            while ($subject_list = mysqli_fetch_assoc($subjects)) {
                            ?>
                                <div class="card text-white bg-primary mb-3" style="width: 100%;">
                                    <div class="card-header"><?php echo $subject_list['name']; ?></div>
                                    <div class="card-body">
                                        <?php
                                        $subject_id = $subject_list['id'];

                                        $chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE subject_id = '$subject_id'");

                                        while ($chapter_list = mysqli_fetch_assoc($chapters)) {
                                        ?><a href="chapter-details.php?chapter_id=<?php echo $chapter_list['id']; ?>" class="btn btn-outline-light m-2"><?php echo $chapter_list['name']; ?></a><?php
                                                                                                                                                                                            } ?>

                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                <?php
                }
                ?>




            </div>

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