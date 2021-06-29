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
            <title>Subjects Details</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/userDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


            <style>
                .container-fluid a button {
                    width: 35%;
                    height: 80px;
                    font-size: 20px;
                    font-weight: bold;
                    margin-left: 10%;
                }

                body{
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>
            <?php include "fullpaper-test-reset.php" ?>
           


            <a href="user-dashboard.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>

            <div class="container-fluid">


                <?php
                if (isset($_GET['test_id'])) {
                    $test_id = $_GET['test_id'];

                    $tests = mysqli_query($conn, "SELECT * FROM tests WHERE id = '$test_id'");
                    $fetch_name = mysqli_fetch_assoc($tests);
                    $test_name = $fetch_name['name'];
                ?>
                    <h3 class="text-light text-center m-5">Test : <span class="text-success"><?php echo $test_name; ?></span></h3>

                    <div class="card border-primary m-auto" style="width:30%">
                        <div class="card-header text-center">
                            <h4>Content</h4>
                        </div>
                        <div class="card-body">
                            <!-- <a href="fullpaper-test-preparation.php?test_id=<?php echo $test_id ?>">
                                <button type="button" class="btn btn-outline-info "><i class="bi bi-journal-bookmark-fill m-3"></i>View Past Paper</button>
                            </a> -->
                            <a>
                                <button type="button" class="btn btn-outline-success w-75" data-toggle="modal" data-target="#startQuiz"><i class="bi bi-journal-bookmark-fill m-3"></i>Take Test Full Paper</button>
                            </a>

                        </div>
                    </div>


                    <!-- Confirmation model to start quiz -->
                    <div class="modal fade" id="startQuiz" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="exampleModalLabel">Confirmation</h5>

                                </div>
                                <div class="modal-body">
                                    Do you want to start Full Test Paper?
                                </div>
                                <div class="modal-footer">
                                    <a href="fullpaper-test-panel.php?test_id=<?php echo $_GET['test_id']; ?>&&question_no=1">
                                        <input type="button" class="btn btn-danger" value="Start" />
                                    </a>

                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
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