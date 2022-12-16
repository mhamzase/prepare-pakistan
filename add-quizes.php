<?php
session_start();
include "database/connection.php";




// add a subject
if (isset($_POST['addQuizes'])) {

    $chapter_id = mysqli_real_escape_string($conn, $_POST['chapter_id']);

    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $option1 = mysqli_real_escape_string($conn, $_POST['option1']);
    $option2 = mysqli_real_escape_string($conn, $_POST['option2']);
    $option3 = mysqli_real_escape_string($conn, $_POST['option3']);
    $option4 = mysqli_real_escape_string($conn, $_POST['option4']);
    $correct_answer = mysqli_real_escape_string($conn, $_POST['correct_answer']);

    $select_chatper = mysqli_query($conn, "SELECT chapter_id FROM quizes WHERE chapter_id = '$chapter_id'");
    $fetch_chatper = mysqli_num_rows($select_chatper);

    if (!$fetch_chatper) {
        $sql = "INSERT INTO `quizes` (`chapter_id`) VALUES ('$chapter_id')";
        mysqli_query($conn, $sql);
    }


    $select_quiz = mysqli_query($conn, "SELECT id FROM quizes WHERE chapter_id = '$chapter_id'");
    $fetch_quiz_id = mysqli_fetch_assoc($select_quiz);
    $quiz_id = $fetch_quiz_id['id'];

    $sql1 = "INSERT INTO `questions` (`question`,`option1`,`option2`, `option3`, `option4` , `correct_answer`, `quiz_id`) VALUES ('$question','$option1' , '$option2' , '$option3' , '$option4',  '$correct_answer' , '$quiz_id')";

    if (mysqli_query($conn, $sql1)) {
        header("Location: add-quizes.php?success=Quiz added successfully!");
    }
}


if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $db_type = $user_data['type'];
    $user_id = $user_data['id'];

    if ($db_type == 0 || $db_type == 2 ) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Add Quiz's</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/adminDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <style>
                .actions {
                    width: 130px;
                    height: 28px;
                    font-size: 10px;
                    padding-top: -10px;
                }body{
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body style="overflow-x: hidden;">

            <?php include "includes/header.php"; ?>

            <a href="admin-dashboard.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>


            <div class="container">
                <form action="" method="post" onsubmit="return validateQuizes()" enctype="multipart/form-data">
                    <div class="card border-light m-auto w-100">
                        <div class="row justify-content-center">
                            <h3 class="text-center mt-3">Add Quiz</h3>

                            <div class="form-group has-success col-10 text-center">
                                <?php
                                if (isset($_GET['success'])) {
                                ?>
                                    <span class='text-success text-center'><?php echo $_GET['success']; ?></span>
                                <?php
                                }
                                if (isset($_GET['error'])) {
                                ?>
                                    <span class='text-danger text-center'><?php echo $_GET['error']; ?></span>
                                <?php
                                }
                                ?>

                                <small class="text-danger" id="error"></small>
                                <small class="text-success" id="success"></small>
                            </div>
                        </div>


                        <div class="row justify-content-center mt-3">
                            <div class="col-10">
                                <select class="form-select" aria-label="Default select example" id="chapter_id" name="chapter_id" required>
                                    <option selected disabled value="">Select Chapter</option>
                                    <?php

                                    $test = "SELECT * FROM tests WHERE user_id = '$user_id'";
                                    $result_test = mysqli_query($conn, $test);

                                    $subject = "SELECT * FROM subjects WHERE user_id = '$user_id'";
                                    $result_subject = mysqli_query($conn, $subject);

                                    while ($row = mysqli_fetch_assoc($result_test)) {

                                        echo "<option disabled value='$row[id]' class='bg-primary text-light font-weight-bold'>$row[name]</option>";

                                        $fetch_subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE test_id = '$row[id]' AND user_id = '$user_id'");
                                        while ($row2 = mysqli_fetch_assoc($fetch_subjects)) {
                                            echo "<option disabled value='$row[id].$row2[id]' class='bg-light text-dark font-weight-bold'> == $row2[name] == </option>";

                                            $fetch_chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE subject_id = '$row2[id]' AND user_id = '$user_id'");
                                            while ($row3 = mysqli_fetch_assoc($fetch_chapters)) {
                                                echo "<option value='$row3[id]'>$row3[name]</option>";
                                            }
                                        }
                                    }
                                    ?>


                                </select>
                            </div>


                        </div>


                        <div class="row justify-content-center mt-2">

                            <div class="col-10">
                                <div class="form-group">
                                    <label for="exampleTextarea" class="form-label">Question</label>
                                    <textarea style="resize: none;" name="question" class="form-control" placeholder="Write a Question..." id="question" rows="4" spellcheck="false" style="margin-top: 0px; margin-bottom: 0px; height: 104px;"></textarea>
                                </div>
                            </div>
                        </div>



                        <div class="row justify-content-center mt-3">
                            <div class="col-5">
                                <div class="alert alert-dismissible alert-secondary " style="height: 14em;">

                                    <div class="form-check">
                                        <input type="text" placeholder="Option 1" id="option1" name="option1" class="form-control m-1" style="float:left;width:80%">
                                        <button type="button" style="float:right" class="btn btn-primary mt-1" onclick="copyOption1()"> <i class="bi bi-clipboard"></i></button>
                                    </div>

                                    <div class="form-check">
                                        <input type="text" placeholder="Option 2" id="option2" name="option2" class="form-control m-1" style="float:left;width:80%">
                                        <button type="button" style="float:right" class="btn btn-primary mt-1" onclick="copyOption2()"> <i class="bi bi-clipboard"></i></button>
                                    </div>

                                    <div class="form-check">
                                        <input type="text" placeholder="Option 3" id="option3" name="option3" class="form-control m-1" style="float:left;width:80%">
                                        <button type="button" style="float:right" class="btn btn-primary mt-1" onclick="copyOption3()"> <i class="bi bi-clipboard"></i></button>
                                    </div>

                                    <div class="form-check">
                                        <input type="text" placeholder="Option 4" id="option4" name="option4" class="form-control m-1" style="float:left;width:80%">
                                        <button type="button" style="float:right" class="btn btn-primary mt-1" onclick="copyOption4()"> <i class="bi bi-clipboard"></i></button>
                                    </div>
                                </div>
                            </div>



                            <div class="col-5">
                                <div class="alert alert-dismissible alert-success" style="height: 14em;">
                                    <br /><br />
                                    <span class="badge bg-primary">
                                        Please copy correct answer & paste in given below field.
                                    </span>
                                    <div class="form-check">
                                        <input type="text" placeholder="Correct Answer" id="correct_answer" name="correct_answer" class="form-control m-1">
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="row justify-content-center mb-3">
                            <div class="col-10">
                                <button type="submit" name="addQuizes" class="btn btn-success w-100 m-auto">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <hr width="80%" class="m-auto mt-3 mb-2">

            <div class="container">
                <div class="row justify-content-center">

                    <?php
                    $tests = mysqli_query($conn, "SELECT * FROM tests WHERE user_id = '$user_id'");


                    while ($row = mysqli_fetch_assoc($tests)) {
                    ?>

                        <div class="card border-primary m-3" style="width: 46%;">
                            <div class="card-header bg-primary text-center"><?php echo $row['name'] ?></div>
                            <div class="card-body">
                                <?php
                                $subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE test_id = '$row[id]' AND user_id = '$user_id'");


                                while ($row2 = mysqli_fetch_assoc($subjects)) {
                                    echo "
                                    <ul style='list-style-type:none'>
                                        <li>
                                            <span style='float:left'>
                                                <i class='bi bi-journals m-3 text-info' style='cursor:pointer'>$row2[name]</i>
                                                
                                            </span>
                                            <br>
                                            <ul style='list-style-type:none;margin-left:20px'>";

                                    $chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE subject_id = '$row2[id]'");
                                    while ($row3 = mysqli_fetch_assoc($chapters)) {
                                        echo "
                                        <span style='float:left'>
                                            <span class='text-warning' style='cursor:pointer' >$row3[name]</span>
                                        </span>
                                        <span style='float:right'>
                                            <a href='chapter-quizes.php?chapter_id=$row3[id]' >
                                                <button type='button'class='btn btn-outline-success actions' data-toggle='modal' data-target='#quizes' >
                                                <i class='bi bi-eye-fill'></i> View Quize's
                                                </button>
                                            </a>
                                        </span>
                                        
                                        <br/><br/>
                                        
                                        ";
                                    }
                                    echo "</ul>
                                        </li>
                                        
                                    </ul>";
                                }

                                ?>
                            </div>
                        </div>



                    <?php
                    }
                    ?>


                </div>
            </div>













            <script>
                function validateQuizes() {
                    let question = document.getElementById("question");
                    let option1 = document.getElementById("option1");
                    let option2 = document.getElementById("option2");
                    let option3 = document.getElementById("option3");
                    let option4 = document.getElementById("option4");

                    let correct_answer = document.getElementById("correct_answer");

                    let error = document.getElementById("error");
                    let success = document.getElementById("success");


                    if (question.value.trim() == "") {
                        error.innerText = "Question is required!";
                        return false;
                    } else {
                        if (option1.value.trim() == "" || option2.value.trim() == "" || option3.value.trim() == "" || option4.value.trim() == "") {
                            error.innerText = "Please fill out all options!";
                            return false;
                        } else {
                            if (correct_answer.value.trim() == "") {
                                error.innerText = "Please fill out the correct answer!";
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }


                }

                function copyOption1() {
                    let option1 = document.getElementById("option1");
                    option1.select();
                    document.execCommand("copy");
                }

                function copyOption2() {
                    let option2 = document.getElementById("option2");
                    option2.select();
                    document.execCommand("copy");
                }

                function copyOption3() {
                    let option3 = document.getElementById("option3");
                    option3.select();
                    document.execCommand("copy");
                }

                function copyOption4() {
                    let option4 = document.getElementById("option4");
                    option4.select();
                    document.execCommand("copy");
                }


                function fetchQuizes() {
                    let chapter_id = document.getElementById("chapter_id");

                    chapter_id.value = this.id;
                }
            </script>


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