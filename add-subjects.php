<?php
session_start();
include "database/connection.php";


// update a subject
if (isset($_POST['updateSubject'])) {

    $update_subject_id = mysqli_real_escape_string($conn, $_POST['update_subject_id']);
    $update_subject_name = mysqli_real_escape_string($conn, $_POST['update_subject_name']);

    $sql = mysqli_query($conn, "UPDATE subjects SET name = '$update_subject_name' WHERE id = '$update_subject_id'");
    header("Location: add-subjects.php");
}


// delete a subject
if (isset($_POST['deleteSubject'])) {

    $delete_subject_id = mysqli_real_escape_string($conn, $_POST['delete_subject_id']);

    $sql = mysqli_query($conn, "DELETE FROM subjects WHERE id = '$delete_subject_id'");
    header("Location: add-subjects.php");
}



// add a subject
if (isset($_POST['addSubject'])) {

    $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);
    $test_id = $_POST['test_id'];

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $user_id = $user_data['id'];


    $sql = "INSERT INTO `subjects` (`name`,`test_id`,`user_id`) VALUES ('$subject_name','$test_id','$user_id')";

    if (mysqli_query($conn, $sql)) {
        header("Location: add-subjects.php?success=Subject name with corresponding test has been added successfully!");
    }
}


if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $db_type = $user_data['type'];
    $user_id = $user_data['id'];

    if ($db_type == 0 || $db_type == 2) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Add Subjects</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/adminDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <style>
                .actions {
                    width: 80px;
                    height: 27px;
                    font-size: 10px;
                    padding-top: -10px
                }body{
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body style="overflow-x: hidden;">

            <?php include "includes/header.php"; ?>

            <a href="admin-dashboard.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>




            <div class="container">


                <div class="card border-light m-auto w-100">

                    <form action="" method="post" onsubmit="return validateSubjects()">
                        <div class="row">
                            <h3 class="text-center m-3">Add Subject</h3>
                            <?php
                            if (isset($_GET['success'])) {
                            ?>
                                <span class='text-success text-center'><?php echo $_GET['success']; ?></span>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <select class="form-select mb-3" aria-label="Default select example" id="test_id" name="test_id" required>
                                    <option selected disabled value="">Select Test</option>
                                    <?php

                                    $test = "SELECT * FROM tests WHERE user_id = '$user_id'";
                                    $result_test = mysqli_query($conn, $test);

                                    while ($row = mysqli_fetch_assoc($result_test)) {

                                        echo "<option value='$row[id]'>$row[name]</option>";
                                    }
                                    ?>


                                </select>
                            </div>
                            <div class="col-4">
                                <input type="text" placeholder="Subject name" id="subject_name" name="subject_name" class="form-control" id="inputValid">
                                <small class="text-danger" id="error"></small>
                                <small class="text-success" id="success"></small>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-10">
                                <button type="submit" name="addSubject" class="btn btn-success w-100 mb-3 flaot-right">Submit</button>
                            </div>
                        </div>

                    </form>

                </div>


            </div>
            <hr width="80%" class="m-auto mt-3 mb-2">

            <div class="container-fluid mt-5">
                <div class="row justify-content-center">

                    <?php
                    $tests = mysqli_query($conn, "SELECT * FROM tests WHERE user_id = '$user_id'");


                    while ($row = mysqli_fetch_assoc($tests)) {
                    ?>

                        <div class="card border-primary m-2 col-4" style="max-width: 30rem;">
                            <div class="card-header bg-primary text-center"><?php echo $row['name'] ?></div>
                            <div class="card-body ">
                                <?php
                                $subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE test_id = '$row[id]' and user_id = '$user_id'");


                                while ($row2 = mysqli_fetch_assoc($subjects)) {
                                    echo "
                                    <span style='float:left'>
                                        <i class='bi bi-journals m-3 text-success' style='cursor:pointer'>$row2[name]</i>
                                    </span>

                                    <span style='float:right'>
                                        <button onclick='fetchSubjectId.call(this)' type='button' id='$row2[id]' value='$row2[name]'  class='btn btn-outline-info actions' data-toggle='modal' data-target='#updateSubject' ><i class='bi bi-pencil-square' ></i> Edit</button>
                            
                                        <button onclick='fetchSubjectId.call(this)' type='button' id='$row2[id]'  class='btn btn-outline-danger actions' data-toggle='modal' data-target='#deleteSubject' ><i class='bi bi-trash-fill'></i> Delete</button>
                                    </span>
                                    <br><br>";
                                }

                                ?>
                            </div>
                        </div>



                    <?php
                    }
                    ?>
                </div>
            </div>





            <!-- Confirmation model to delete subject -->
            <div class="modal fade" id="deleteSubject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="exampleModalLabel">Confirmation</h5>
                        </div>
                        <div class="modal-body">
                            Do you want to Delete Subject?
                        </div>
                        <div class="modal-footer">
                            <form method='post' action=''>
                                <input type='hidden' id="delete_subject_id" name='delete_subject_id' />
                                <input type="submit" name="deleteSubject" class="btn btn-danger" value="Confirm" />
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>



            <!-- Confirmation model to update subject -->
            <div class="modal fade" id="updateSubject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-info" id="exampleModalLabel">Update subject name</h5>

                        </div>
                        <form method='post' action='' onsubmit="return checkUpdateSubjectName()">
                            <div class="modal-body">
                                <input class="form-control" type="text" id="update_subject_name" name="update_subject_name" placeholder="Subject name" required>
                                <span class="text-danger" id="error_subject_name"></span>
                            </div>
                            <div class="modal-footer">

                                <input type='hidden' id="update_subject_id" name='update_subject_id' />
                                <input type="submit" name="updateSubject" class="btn btn-success" value="Update" />
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>





            <script>
                function validateSubjects() {
                    let subject_name = document.getElementById("subject_name");
                    let test_name = document.getElementById("test_name");
                    let error = document.getElementById("error");
                    let success = document.getElementById("success");

                    if (subject_name.value.trim() == "") {
                        error.innerText = "Subject name is required!";
                        return false;
                    } else {
                        return true;
                    }

                }


                function fetchSubjectId() {
                    let update_subject_id = document.getElementById("update_subject_id");
                    let delete_subject_id = document.getElementById("delete_subject_id");
                    let update_subject_name = document.getElementById("update_subject_name");

                    update_subject_name.value = this.value;
                    update_subject_id.value = this.id;
                    delete_subject_id.value = this.id;
                    // console.log("Asdf");
                }


                function checkUpdateSubjectName() {
                    let update_subject_name = document.getElementById("update_subject_name");
                    let error_subject_name = document.getElementById("error_subject_name");

                    if (update_subject_name.value.trim() == "") {
                        error_subject_name.innerText = "Test name is required!";
                        return false;
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