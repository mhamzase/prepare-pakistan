<?php
session_start();
include "database/connection.php";


// update a question  (along mcqs)
if (isset($_POST['updateQuestion'])) {

    $update_question_id = mysqli_real_escape_string($conn, $_POST['update_question_id']);
    $update_question = mysqli_real_escape_string($conn, $_POST['update_question']);
    $update_option1 = mysqli_real_escape_string($conn, $_POST['update_option1']);
    $update_option2 = mysqli_real_escape_string($conn, $_POST['update_option2']);
    $update_option3 = mysqli_real_escape_string($conn, $_POST['update_option3']);
    $update_option4 = mysqli_real_escape_string($conn, $_POST['update_option4']);
    $update_correct_answer = mysqli_real_escape_string($conn, $_POST['update_correct_answer']);



    $sql = mysqli_query($conn, "UPDATE questions SET question = '$update_question' , option1 = '$update_option1' , option2 = '$update_option2' , option3 = '$update_option3' , option4 = '$update_option4' , correct_answer = '$update_correct_answer' WHERE id = '$update_question_id'");


    if ($sql) {
        header("Location: chapter-quizes.php?chapter_id=$_GET[chapter_id]");
    }
}




// delete a question  (along mcqs)
if (isset($_POST['deleteQuestion'])) {

    $delete_question_id = $_POST['delete_question_id'];

    $sql = mysqli_query($conn, "DELETE FROM questions WHERE id = '$delete_question_id'");

    if ($sql) {
        header("Location: chapter-quizes.php?chapter_id=$_GET[chapter_id]");
    }
}




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
            <title>Chapter Quiz's</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/adminDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <style>
                .actions {
                    width: 130px;
                    height: 28px;
                    font-size: 10px;
                    padding-top: -10px;
                }

                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body style="overflow-x: hidden;">

            <?php include "includes/header.php"; ?>

            <a href="add-quizes.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Dashboard"><i class="bi bi-arrow-left-circle-fill"></i> Back</button></a>


            <div class="container">
            </div>


            <div class="container">
                <div class="row justify-content-center">

                    <?php

                    if (isset($_GET['chapter_id'])) {
                        $chapter_id = $_GET['chapter_id'];

                        $query1 = mysqli_query($conn, "SELECT chapters.name , quizes.id FROM chapters LEFT JOIN quizes ON quizes.chapter_id = '$chapter_id'");
                        $fetch_data = mysqli_fetch_assoc($query1);

                        $chapter_name = $fetch_data['name'];
                        $quiz_id = $fetch_data['id'];

                        $query2 = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id = '$quiz_id'");

                    ?>

                        <h3 class="text-center m-5">Chapter : <span class="text-success"><?php echo $chapter_name; ?> </span>Quiz's</h3>

                        <div class="list-group">
                            <?php

                            $count = 0;
                            while ($row = mysqli_fetch_assoc($query2)) {
                                $count++;
                                echo "<span  class='list-group-item list-group-item-action bg-primary'>
                                <span style='float:left'>
                                    <span class='text-light'>Q.$count - </span>
                                    <span class='text-light'>$row[question]</span>
                                </span>
                                <span style='float:right'>
                                    <button onclick='fetchQuestionId.call(this)' type='button' id='$row[id]' value='$row[question]0@#$%&*$row[option1]0@#$%&*$row[option2]0@#$%&*$row[option3]0@#$%&*$row[option4]0@#$%&*$row[correct_answer]'  class='btn btn-info actions' data-toggle='modal' data-target='#updateQuestion' ><i class='bi bi-pencil-square' ></i> Edit</button>

                                    <button onclick='fetchQuestionId.call(this)' type='button' id='$row[id]'  class='btn btn-danger actions float-right' data-toggle='modal' data-target='#deleteQuestion' ><i class='bi bi-trash-fill'></i> Delete</button>
                                </span>
                            </span>";

                                echo "<span  class='list-group-item list-group-item-action'>
                            <span class='text-light'>$row[option1]</span>
                            </span>";

                                echo "<span  class='list-group-item list-group-item-action'>
                            <span class='text-light'>$row[option2]</span> 
                            </span>";

                                echo "<span  class='list-group-item list-group-item-action '>
                            <span class='text-light'>$row[option3]</span> 
                            </span>";

                                echo "<span  class='list-group-item list-group-item-action '>
                            <span class='text-light'>$row[option4]</span> 
                            </span>";
                            }

                            ?>
                        </div>
                    <?php

                    }

                    ?>


                </div>
            </div>




            <!-- Confirmation model to delete question -->
            <div class="modal fade" id="deleteQuestion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="exampleModalLabel">Confirmation</h5>

                        </div>
                        <div class="modal-body">
                            Do you want to delete question?
                        </div>
                        <div class="modal-footer">
                            <form action="" method="post">
                                <input type="hidden" id="delete_question_id" name="delete_question_id">
                                <input type="submit" name="deleteQuestion" class="btn btn-danger" value="Delete" />
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Confirmation model to update question -->
            <div class="modal fade" id="updateQuestion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-info" id="exampleModalLabel">Update Question</h5>
                        </div>
                        <small class="text-danger text-center mb-2" id="error"></small>
                        <form action="" method="post" onsubmit="return validateUpdateQuestion()">
                            <div class="modal-body">
                                <textarea style="resize: none;" id="update_question" name="update_question" class="form-control" placeholder="Update Question..." id="question" rows="4" spellcheck="false" style="margin-top: 0px; margin-bottom: 0px; height: 104px;"></textarea>
                                <hr width="100%">

                                <input type="text" id="update_option1" name="update_option1" class="form-control mb-1" placeholder="Option 1" style="float:left;width:90%">
                                <button type="button" style="float:right" class="btn btn-primary mb-1" onclick="copyOption1()"> <i class="bi bi-clipboard"></i></button>

                                <input type="text" id="update_option2" name="update_option2" class="form-control mb-1" placeholder="Option 2" style="float:left;width:90%">
                                <button type="button" style="float:right" class="btn btn-primary mb-1" onclick="copyOption2()"> <i class="bi bi-clipboard"></i></button>

                                <input type="text" id="update_option3" name="update_option3" class="form-control mb-1" placeholder="Option 3" style="float:left;width:90%">
                                <button type="button" style="float:right" class="btn btn-primary mb-1" onclick="copyOption3()"> <i class="bi bi-clipboard"></i></button>

                                <input type="text" id="update_option4" name="update_option4" class="form-control mb-1" placeholder="Option 4" style="float:left;width:90%">
                                <button type="button" style="float:right" class="btn btn-primary mb-1" onclick="copyOption4()"> <i class="bi bi-clipboard"></i></button>


                                <hr width="100%">
                                <small class="text-success">Correct Answer</small>
                                <input type="text" id="update_correct_answer" name="update_correct_answer" class="form-control" placeholder="Correct answer">

                            </div>
                            <div class="modal-footer">

                                <input type="hidden" id="update_question_id" name="update_question_id">
                                <input type="submit" name="updateQuestion" class="btn btn-success" value="Update" />
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>








            <script>
                // fetching quiz id
                function fetchQuestionId() {
                    let delete_question_id = document.getElementById("delete_question_id");
                    let update_question_id = document.getElementById("update_question_id");

                    let update_question = document.getElementById("update_question");
                    let update_option1 = document.getElementById("update_option1");
                    let update_option2 = document.getElementById("update_option2");
                    let update_option3 = document.getElementById("update_option3");
                    let update_option4 = document.getElementById("update_option4");
                    let update_correct_answer = document.getElementById("update_correct_answer");

                    let data = this.value.split("0@#$%&*");

                    update_question.value = data[0];
                    update_option1.value = data[1];
                    update_option2.value = data[2];
                    update_option3.value = data[3];
                    update_option4.value = data[4];
                    update_correct_answer.value = data[5];

                    delete_question_id.value = this.id;
                    update_question_id.value = this.id;
                }


                // copy the options of question

                function copyOption1() {
                    let update_option1 = document.getElementById("update_option1");
                    update_option1.select();
                    document.execCommand("copy");
                }

                function copyOption2() {
                    let update_option2 = document.getElementById("update_option2");
                    update_option2.select();
                    document.execCommand("copy");
                }

                function copyOption3() {
                    let update_option3 = document.getElementById("update_option3");
                    update_option3.select();
                    document.execCommand("copy");
                }

                function copyOption4() {
                    let option4 = document.getElementById("update_option4");
                    option4.select();
                    document.execCommand("copy");
                }




                // validate question update form
                function validateUpdateQuestion() {
                    let update_question = document.getElementById("update_question");
                    let update_option1 = document.getElementById("update_option1");
                    let update_option2 = document.getElementById("update_option2");
                    let update_option3 = document.getElementById("update_option3");
                    let update_option4 = document.getElementById("update_option4");
                    let update_correct_answer = document.getElementById("update_correct_answer");
                    let error = document.getElementById("error");

                    if (update_question.value.trim() == "") {
                        error.innerText = "Question is required!";
                        return false;
                    } else {
                        if (update_option1.value.trim() == "" || update_option2.value.trim() == "" || update_option3.value.trim() == "" || update_option4.value.trim() == "") {
                            error.innerText = "All options are required!";
                            return false;
                        } else {
                            if (update_correct_answer.value.trim() == "") {
                                error.innerText = "Correct answer is required!";
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }

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