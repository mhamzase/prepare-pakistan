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
            <title>Chapter Details</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/userDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


            <style>
                .container-fluid a button {
                    width: 30%;
                    height: 100px;
                    font-size: 20px;
                    font-weight: bold;
                    margin-left: 13%;
                }

                body{
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>
            <?php include "chapter-quiz-reset.php"; ?>

            <?php
            $chapter_id = $_GET['chapter_id'];

            $sql_result = mysqli_query($conn, "SELECT * FROM chapters WHERE id = '$chapter_id'");
            $sql_fetch = mysqli_fetch_assoc($sql_result);
            $subject_id = $sql_fetch['subject_id'];

            $sql_result1 = mysqli_query($conn, "SELECT * FROM subjects WHERE id = '$subject_id'");
            $sql_fetch1 = mysqli_fetch_assoc($sql_result1);
            $test_id = $sql_fetch1['test_id'];
            ?>

            <a href="test-details.php?test_id=<?php echo $test_id; ?>"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" title="Back to Dashboard"><i class="bi bi-arrow-left-circle-fill"></i> Back</button></a>

            <div class="container-fluid">


                <?php
                if (isset($_GET['chapter_id'])) {
                    $chapter_id = $_GET['chapter_id'];

                    $chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE id = '$chapter_id'");
                    $fetch_name = mysqli_fetch_assoc($chapters);
                    $chapter_name = $fetch_name['name'];
                ?>
                    <h3 class="text-light text-center m-5">Chapter : <span class="text-success"><?php echo $chapter_name; ?></span></h3>

                    <div class="card border-primary m-auto" style="width:50%">
                        <div class="card-header text-center">
                            <h4>Content</h4>
                        </div>
                        <div class="card-body">
                            <a href="chapter-test-preparation.php?chapter_id=<?php echo $chapter_id ?>">
                                <button type="button" class="btn btn-outline-info "><i class="bi bi-journal-bookmark-fill m-3"></i>Prepare Test</button>
                            </a>

                            <?php

                            $quizes = mysqli_query($conn, "SELECT * FROM quizes WHERE chapter_id = '$chapter_id'");
                            $fetch_quiz = mysqli_fetch_assoc($quizes);

                            ?>
                            <a>
                                <button type="button" class="btn btn-outline-success " data-toggle="modal" data-target="#startQuiz"><i class="bi bi-journal-bookmark-fill m-3"></i>Take Test</button>
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
                                    Do you want to start Quiz Test?
                                </div>
                                <div class="modal-footer">
                                    <a href="quiz-panel.php?quiz_id=<?php echo $fetch_quiz['id'] ?>&&question_no=1">
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