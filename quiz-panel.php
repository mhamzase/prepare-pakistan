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
                .container-fluid a button {
                    width: 30%;
                    height: 100px;
                    font-size: 20px;
                    font-weight: bold;
                    margin-left: 13%;
                }

                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>


            <div class="container-fluid">


                <?php
                $question_count = $_REQUEST['question_no'];

                if (isset($_GET['quiz_id'])) {
                    $quiz_id = $_GET['quiz_id'];

                    $chapter_id = mysqli_query($conn, "SELECT * FROM quizes WHERE id = '$quiz_id'");
                    $fetch_chapter_id = mysqli_fetch_assoc($chapter_id)['chapter_id'];

                    $chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE id = '$fetch_chapter_id'");
                    $chapter_data = mysqli_fetch_assoc($chapters);

                    $chapter_name = $chapter_data['name'];
                ?>
                    <h3 class="text-light text-center m-5">Chapter : <span class="text-success"><?php echo $chapter_name; ?></span></h3>

                    <div class="card border-primary m-auto" style="width:50%">
                        <div class="card-header text-center">
                            <h4>Quiz Panel</h4>
                        </div>
                        <div class="card-body">

                            <?php
                            $questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id = '$quiz_id' ORDER BY RAND() LIMIT 3");
                            $total_no_questions = mysqli_num_rows($questions);
                            $ques = [0];

                            while ($q = mysqli_fetch_assoc($questions)) {
                                $ques[] = $q;
                            }


                            if ($question_count > $total_no_questions) {
                                $sql_user_result = mysqli_query($conn, "UPDATE results SET `status` = 'finished' WHERE user_id = '$user_id' and quiz_id = '$quiz_id' and status = 'current'");
                                setcookie("loadCount", "", time() - 3600);
                                header("Location: quiz-end.php");
                            }






                            if (!isset($_COOKIE["loadCount"])) {

                                date_default_timezone_set("Asia/Karachi");
                                $taken_date =  date("j F Y, \a\\t g:i a", time());

                                $status = "current";
                                $score = 0;

                                $sql_user_result = mysqli_query($conn, "INSERT INTO `results`(`quiz_id`, `total_marks`, `obtained_marks`, `user_id`, `status`, `taken_date`) VALUES ('$quiz_id','$total_no_questions','$score','$user_id','$status','$taken_date')");
                            }

                            setcookie("loadCount", "1");


                            if (isset($_POST['next'])) {
                                $selected_option = $_POST['options'];
                                $question_id = $_POST['question_db_id'];

                                $sql = mysqli_query($conn, "SELECT * FROM questions WHERE id = '$question_id'");
                                $fetch_question_data = mysqli_fetch_assoc($sql);
                                $fetch_correct_answer = $fetch_question_data['correct_answer'];


                                $sql_data = mysqli_query($conn, "SELECT * FROM results WHERE user_id = '$user_id' and quiz_id = '$quiz_id' and status = 'current'");
                                $fetch_data = mysqli_fetch_assoc($sql_data);
                                $old_score = $fetch_data['obtained_marks'];



                                // check the answer is correct or not
                                if ($selected_option == $fetch_correct_answer) {
                                    $old_score = $old_score + 1;
                                }

                                $sql_user_result = mysqli_query($conn, "UPDATE results SET `obtained_marks` = '$old_score' WHERE user_id = '$user_id' and quiz_id = '$quiz_id' and status = 'current'");




                                $question_count++;
                                if ($question_count <= $total_no_questions) {

                                    header("Location: quiz-panel.php?quiz_id=$quiz_id&&question_no=$question_count");
                                } else {
                                    $sql_user_result = mysqli_query($conn, "UPDATE results SET `status` = 'finished' WHERE user_id = '$user_id' and quiz_id = '$quiz_id' and status = 'current'");
                                    setcookie("loadCount", "", time() - 3600);
                                    header("Location: quiz-end.php");
                                }
                            }



                            ?>
                            <form action="" method="post">

                                <div class="list-group">
                                    <br>

                                    <input type="hidden" id="quiz_id" value="<?php echo $quiz_id; ?>">
                                    <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
                                    <input type="hidden" id="question_count_id" name="question_count_id" value="<?php echo $question_count; ?>">
                                    <input type="hidden" id="question_db_id" name="question_db_id" value="<?php echo $ques[$question_count]['id']; ?>">

                                    <div class="container d-flex justify-content-between">
                                        <div>
                                            <span class="text-warning" style="position: relative;top:-2rem;"><br>Question <?php echo $question_count . " Of " . $total_no_questions ?></span>
                                        </div>
                                        <div>

                                            <span style="position: relative;top:-0.5rem">
                                                Question Time : <span class="text-warning" id="question_timer"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <span class="list-group-item list-group-item-action active">Q: <?php echo $ques[$question_count]['question'] ?></span>

                                    <span class="list-group-item list-group-item-action"><input type="radio" name="options" class="options" value="<?php echo $ques[$question_count]['option1'] ?>" /> <?php echo $ques[$question_count]['option1'] ?></span>
                                    <span class="list-group-item list-group-item-action"><input type="radio" name="options" class="options" value="<?php echo $ques[$question_count]['option2'] ?>" /> <?php echo $ques[$question_count]['option2'] ?></span>
                                    <span class="list-group-item list-group-item-action"><input type="radio" name="options" class="options" value="<?php echo $ques[$question_count]['option3'] ?>" /> <?php echo $ques[$question_count]['option3'] ?></span>
                                    <span class="list-group-item list-group-item-action"><input type="radio" name="options" class="options" value="<?php echo $ques[$question_count]['option4'] ?>" /> <?php echo $ques[$question_count]['option4'] ?></span>

                                </div>
                                <?php


                                if ($question_count == $total_no_questions) {
                                ?>

                                    <button type="submit" id="nextBtn" name="next" class="btn btn-danger d-none" style="height: 50px;margin-top:10px">Save & Finish</button>
                                <?php
                                } else {
                                ?>
                                    <button onclick="nextTimerReset()" type="submit" id="nextBtn" name="next" class="btn btn-info d-none" style="height: 50px;margin-top:10px">Save</button>
                                <?php
                                }


                                ?>



                            </form>



                        </div>
                    </div>


                <?php
                }
                ?>



            </div>

            <script>
                function nextTimerReset() {
                    localStorage.removeItem("question_timer");
                }



                let nextButton = document.getElementById("nextBtn");

                const option = Array.from(document.querySelectorAll(".options"));
                var selectedOption = null;
                option.forEach(option => {
                    option.addEventListener("click", (e) => {
                        nextButton.classList.remove('d-none');
                    });

                });


                setTimeout(() => {
                    let quiz_id = document.getElementById("quiz_id").value;
                    let question_id = document.getElementById("question_count_id").value;
                    let user_id = document.getElementById("user_id").value;

                    question_id = parseInt(question_id) + 1;

                    localStorage.setItem("question_timer", 20);

                    window.location.replace(`quiz-panel.php?quiz_id=${quiz_id}&&question_no=${question_id}`);

                }, 20000);






                if (localStorage.getItem("question_timer") == null) {
                    localStorage.setItem("question_timer", 20);
                } else {
                    let time_counter = localStorage.getItem("question_timer");
                    localStorage.setItem("question_timer", time_counter);
                }


                setInterval(function() {
                    let question_timer = document.getElementById("question_timer");

                    let time_counter = parseInt(localStorage.getItem("question_timer")) - 1;

                    question_timer.innerText = time_counter + "'s";
                    localStorage.setItem("question_timer", time_counter);
                }, 1000);
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