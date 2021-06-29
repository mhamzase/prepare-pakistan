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

                if (isset($_GET['test_id'])) {
                    $test_id = $_GET['test_id'];

                    $sql_test = mysqli_query($conn, "SELECT * FROM tests WHERE id = '$test_id'");
                    $fetch_test_name = mysqli_fetch_assoc($sql_test)['name'];
                ?>
                    <h3 class="text-light text-center m-5">Test : <span class="text-success"><?php echo $fetch_test_name; ?></span></h3>

                    <div class="card border-primary m-auto" style="width:50%">
                        <div class="card-header text-center">
                            <h4>Quiz Panel</h4>
                        </div>
                        <div class="card-body">

                            <?php
                            $sql_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$_SESSION[loggedIn]'");
                            $user_id = mysqli_fetch_assoc($sql_user)['id'];


                            $ques = [0];
                            $total_no_questions = 0;


                            $sql_subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE test_id = '$test_id'");
                            // $sql_subjects_result = mysqli_fetch_assoc($sql_subjects);

                            while ($sql_subjects_result = mysqli_fetch_assoc($sql_subjects)) {
                                $sql_chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE subject_id = '$sql_subjects_result[id]'");
                                // $sql_chapters_result = mysqli_fetch_assoc($sql_chapters);
                                while ($sql_chapters_result = mysqli_fetch_assoc($sql_chapters)) {
                                    $sql_quizes = mysqli_query($conn, "SELECT * FROM quizes WHERE chapter_id = '$sql_chapters_result[id]'");
                                    // $sql_quizes_result = mysqli_fetch_assoc($sql_quizes);
                                    while ($sql_quizes_result = mysqli_fetch_assoc($sql_quizes)) {
                                        $questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id = '$sql_quizes_result[id]' ORDER BY RAND() LIMIT 5");
                                        $total_no_questions = $total_no_questions +  mysqli_num_rows($questions);

                                        while ($q = mysqli_fetch_assoc($questions)) {
                                            $ques[] = $q;
                                        }
                                    }
                                }
                            }


                            if ($question_count > $total_no_questions) {
                                $sql_user_result = mysqli_query($conn, "UPDATE test_results SET `status` = 'finished' WHERE user_id = '$user_id' and test_id = '$test_id' and status = 'current'");
                                setcookie("loadCount", "", time() - 3600);
                                header("Location: quiz-end.php");
                            }



                            if (!isset($_COOKIE["loadCount"])) {

                                date_default_timezone_set("Asia/Karachi");
                                $taken_date =  date("j F Y, \a\\t g:i a", time());

                                $status = "current";
                                $score = 0;

                                $sql_user_result = mysqli_query($conn, "INSERT INTO `test_results`(`test_id`, `total_marks`, `obtained_marks`, `user_id`, `status`, `taken_date`) VALUES ('$test_id','$total_no_questions','$score','$user_id','$status','$taken_date')");
                            }

                            setcookie("loadCount", "1");


                            if (isset($_POST['next'])) {
                                $selected_option = $_POST['options'];
                                $question_id = $_POST['question_db_id'];

                                $sql = mysqli_query($conn, "SELECT * FROM questions WHERE id = '$question_id'");
                                $fetch_question_data = mysqli_fetch_assoc($sql);
                                $fetch_correct_answer = $fetch_question_data['correct_answer'];


                                $sql_data = mysqli_query($conn, "SELECT * FROM test_results WHERE user_id = '$user_id' and test_id = '$test_id' and status = 'current'");
                                $fetch_data = mysqli_fetch_assoc($sql_data);
                                $old_score = $fetch_data['obtained_marks'];



                                // check the answer is correct or not
                                if ($selected_option == $fetch_correct_answer) {
                                    $old_score = $old_score + 1;
                                }

                                $sql_user_result = mysqli_query($conn, "UPDATE test_results SET `obtained_marks` = '$old_score' WHERE user_id = '$user_id' and test_id = '$test_id' and status = 'current'");




                                $question_count++;
                                if ($question_count <= $total_no_questions) {

                                    header("Location: fullpaper-test-panel.php?test_id=$test_id&&question_no=$question_count");
                                } else {
                                    $sql_user_result = mysqli_query($conn, "UPDATE test_results SET `status` = 'finished' WHERE user_id = '$user_id' and test_id = '$test_id' and status = 'current'");
                                    setcookie("loadCount", "", time() - 3600);
                                    header("Location: quiz-end.php");
                                }
                            }

                            
                            ?>
                            <form action="" method="post">

                                <div class="list-group">
                                    <br>

                                    <input type="hidden" id="test_id" value="<?php echo $test_id; ?>">
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


                // this will run after question time when user don't choose any option 
                setTimeout(() => {
                    let test_id = document.getElementById("test_id").value;
                    let question_id = document.getElementById("question_count_id").value;
                    let user_id = document.getElementById("user_id").value;

                    question_id = parseInt(question_id) + 1;

                    localStorage.setItem("question_timer", 20);

                    window.location.replace(`fullpaper-test-panel.php?test_id=${test_id}&&question_no=${question_id}`);

                }, 20000);






                if (localStorage.getItem("question_timer") == null) {
                    localStorage.setItem("question_timer", 20);
                } else {
                    let time_counter = localStorage.getItem("question_timer");
                    localStorage.setItem("question_timer", time_counter);
                }


                // this will run on every second     1000 ms = 1 s
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