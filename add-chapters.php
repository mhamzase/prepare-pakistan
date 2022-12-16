<?php
session_start();
include "database/connection.php";


// update a chapter
if (isset($_POST['updateChapter'])) {

    $update_chapter_id = mysqli_real_escape_string($conn, $_POST['update_chapter_id']);
    $update_chapter_name = mysqli_real_escape_string($conn, $_POST['update_chapter_name']);


    $var1 = rand(111, 999);  // generate random number in $var1 variable
    $var2 = md5($var1);     // convert $var3 using md5 function and generate 32 characters hex number

    $target_dir = "chapters/";
    $target_file = $target_dir . $var2 . "." . basename($_FILES["update_chapter_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allow pdf file format
    if ($_FILES['update_chapter_file']['name'] != "") {

        if ($imageFileType != "pdf") {

            header("Location: add-chapters.php?error=Sorry, only PDF files are allowed.");
        } else {
            if (move_uploaded_file($_FILES["update_chapter_file"]["tmp_name"], $target_file)) {

                $file_query = mysqli_query($conn, "SELECT * FROM chapters WHERE id = '$update_chapter_id'");
                $fetch_file = mysqli_fetch_assoc($file_query);
                $chapter_file = $fetch_file['chapter_file'];

                unlink($chapter_file);


                $sql_update = mysqli_query($conn, "UPDATE chapters SET name = '$update_chapter_name' , chapter_file = '$target_file' WHERE id = '$update_chapter_id'");
            }
        }
    } else {
        $sql = mysqli_query($conn, "UPDATE chapters SET name = '$update_chapter_name' WHERE id = '$update_chapter_id'");
    }

    header("Location: add-chapters.php");
}



// delete a subject
if (isset($_POST['deleteChapter'])) {

    $delete_chapter_id = mysqli_real_escape_string($conn, $_POST['delete_chapter_id']);

    $file_query = mysqli_query($conn, "SELECT * FROM chapters WHERE id = '$delete_chapter_id'");
    $fetch_file = mysqli_fetch_assoc($file_query);
    $chapter_file = $fetch_file['chapter_file'];

    unlink($chapter_file);

    $sql = mysqli_query($conn, "DELETE FROM chapters WHERE id = '$delete_chapter_id'");

    header("Location: add-chapters.php");
}



// add a subject
if (isset($_POST['addChapters'])) {


    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $user_id = $user_data['id'];

    $chapter_name = mysqli_real_escape_string($conn, $_POST['chapter_name']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);


    $var1 = rand(111, 999);  // generate random number in $var1 variable
    $var2 = md5($var1);     // convert $var3 using md5 function and generate 32 characters hex number

    $target_dir = "chapter-files/";
    $target_file = $target_dir . $var2 . "." . basename($_FILES["chapter_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    // Allow pdf file format
    if ($imageFileType != "pdf") {

        header("Location: add-chapters.php?error=Sorry, only PDF files are allowed.");
    } else {
        if (move_uploaded_file($_FILES["chapter_file"]["tmp_name"], $target_file)) {

            $sql = "INSERT INTO `chapters` (`name`,`chapter_file`, `subject_id`,`user_id`) VALUES ('$chapter_name','$target_file' , '$subject_id','$user_id')";

            if (mysqli_query($conn, $sql)) {
                header("Location: add-chapters.php?success=Chapter name and file has been added successfully!");
            }
        }
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
            <title>Add Chapters</title>
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
                <form action="" method="post" onsubmit="return validateChapters()" enctype="multipart/form-data">
                    <div class="card border-light m-auto w-100">
                        <div class="row justify-content-center">
                            <h3 class="text-center mb-3 mt-3">Add Chapter</h3>
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
                        </div>


                        <div class="row justify-content-center">
                            <div class="col-10">
                                <select class="form-select mb-3" aria-label="Default select example" id="subject_id" name="subject_id" required>
                                    <option selected disabled value="">Select Subject</option>
                                    <?php

                                    $test = "SELECT * FROM tests WHERE user_id = '$user_id'";
                                    $result_test = mysqli_query($conn, $test);

                                    $subject = "SELECT * FROM subjects WHERE user_id = '$user_id'";
                                    $result_subject = mysqli_query($conn, $subject);

                                    while ($row = mysqli_fetch_assoc($result_test)) {

                                        echo "<option disabled value='$row[id]' class='bg-primary text-light font-weight-bold'>$row[name]</option>";

                                        $fetch_subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE test_id = '$row[id]'");
                                        while ($row2 = mysqli_fetch_assoc($fetch_subjects)) {
                                            echo "<option value='$row2[id]'>$row2[name]</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="row justify-content-center">

                            <div class="col-5">
                                <div class="form-group mb-4">
                                    <input class="form-control" type="file" id="chapter_file" name="chapter_file" required>
                                    <small for="formFile" class="form-label ">Upload a document belong to chapter</small><br />
                                    <small class="text-info">Note!: Only pdf file's are allowed</small>
                                </div>
                            </div>

                            <div class="col-5">
                                <input type="text" placeholder="Chapter name" id="chapter_name" name="chapter_name" class="form-control" id="inputValid">
                                <small class="text-danger" id="error"></small>
                                <small class="text-success" id="success"></small>
                            </div>
                        </div>

                        <div class="row justify-content-center mb-3">
                            <div class="col-6">
                                <button type="submit" name="addChapters" class="btn btn-success w-100 m-auto">Submit</button>

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

                        <div class="card border-primary m-4" style="width: 46%;">
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

                                    $chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE  subject_id = '$row2[id]'");
                                    while ($row3 = mysqli_fetch_assoc($chapters)) {
                                        echo "
                                        <span style='float:left'>
                                            <small class='text-warning' style='cursor:pointer'>$row3[name]</small>
                                        </span>
                                        
                                        <span style='float:right'>
                                            <button onclick='fetchChapterId.call(this)' type='button' id='$row3[id]' value='$row3[name]'  class='btn btn-outline-info actions' data-toggle='modal' data-target='#updateChapter' ><i class='bi bi-pencil-square' ></i> Edit</button>
                            
                                            <button onclick='fetchChapterId.call(this)' type='button' id='$row3[id]'  class='btn btn-outline-danger actions' data-toggle='modal' data-target='#deleteChapter' ><i class='bi bi-trash-fill'></i> Delete</button>
                                        </span><br><br>";
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






            <!-- Confirmation model to delete chapter -->
            <div class="modal fade" id="deleteChapter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="exampleModalLabel">Confirmation</h5>
                        </div>
                        <div class="modal-body">
                            Do you want to Delete Chapter?
                        </div>
                        <div class="modal-footer">
                            <form method='post' action=''>
                                <input type='hidden' id="delete_chapter_id" name='delete_chapter_id' />
                                <input type="submit" name="deleteChapter" class="btn btn-danger" value="Confirm" />
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>





            <!-- Confirmation model to update chapter -->
            <div class="modal fade" id="updateChapter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-info" id="exampleModalLabel">Update chapter</h5>

                        </div>
                        <form method='post' action='' onsubmit="return checkUpdateChapterName()" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input class="form-control" type="text" id="update_chapter_name" name="update_chapter_name" placeholder="Subject name" required>
                                <span class="text-danger" id="error_chapter_name"></span>
                            </div>
                            <div class="form-group m-auto" style="width: 95%;">
                                <label for="formFile" class="form-label mt-4">Update a document belong to chapter (optional)</label>
                                <input class="form-control mb-5" type="file" id="update_chapter_file" name="update_chapter_file">
                                <span class="text-info">Note!: Only pdf file's are allowed</span>
                            </div>

                            <div class="modal-footer">
                                <input type='hidden' id="update_chapter_id" name='update_chapter_id' />
                                <input type="submit" name="updateChapter" class="btn btn-success" value="Update" />
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <script>
                function validateChapters() {
                    let chapter_name = document.getElementById("chapter_name");
                    let error = document.getElementById("error");
                    let success = document.getElementById("success");

                    if (chapter_name.value.trim() == "") {
                        error.innerText = "Chapter name is required!";
                        return false;
                    } else {
                        return true;
                    }

                }


                function fetchChapterId() {
                    let update_chapter_id = document.getElementById("update_chapter_id");
                    let delete_chapter_id = document.getElementById("delete_chapter_id");
                    let update_chapter_name = document.getElementById("update_chapter_name");

                    update_chapter_id.value = this.id;

                    update_chapter_name.value = this.value;
                    delete_chapter_id.value = this.id;

                }


                function checkUpdateChapterName() {
                    let update_chapter_name = document.getElementById("update_chapter_name");
                    let error_chapter_name = document.getElementById("error_chapter_name");

                    if (update_chapter_name.value.trim() == "") {
                        error_chapter_name.innerText = "Test name is required!";
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