<?php
session_start();
include "database/connection.php";

// delete a test 
if (isset($_POST['updateTest'])) {

    $test_id = mysqli_real_escape_string($conn, $_POST['update_test_id']);
    $update_test_name = mysqli_real_escape_string($conn, $_POST['update_test_name']);


    
    $var1 = rand(111, 999);  // generate random number in $var1 variable
    $var2 = md5($var1);     // convert $var3 using md5 function and generate 32 characters hex number

    $target_dir = "test-files/";
    $target_file = $target_dir . $var2 . "." . basename($_FILES["update_test_file"]["name"]);
    
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));



    if ($_FILES['update_test_file']['name'] != "") {

        if ($imageFileType != "pdf") {

            header("Location: add-tests.php?error=Sorry, only PDF files are allowed.");
        } else {
            if (move_uploaded_file($_FILES["update_test_file"]["tmp_name"], $target_file)) {

                $file_query = mysqli_query($conn, "SELECT * FROM tests WHERE id = '$test_id'");
                $fetch_file = mysqli_fetch_assoc($file_query);
                $test_file = $fetch_file['test_file'];

                unlink($test_file);


                $sql_update = mysqli_query($conn, "UPDATE tests SET name = '$update_test_name' , test_file = '$target_file' WHERE id = '$test_id'");
            }
        }
    } else {
        $sql = mysqli_query($conn, "UPDATE `tests` SET name = '$update_test_name' WHERE id = '$test_id'");
    }

    header("Location: add-tests.php");
}


// delete a test 
if (isset($_POST['deleteTest'])) {

    $test_id = mysqli_real_escape_string($conn, $_POST['delete_test_id']);


    $file_query = mysqli_query($conn, "SELECT * FROM tests WHERE id = '$test_id'");
    $fetch_file = mysqli_fetch_assoc($file_query);
    $test_file = $fetch_file['test_file'];

    unlink($test_file);


    $sql = "DELETE FROM `tests` WHERE id = '$test_id'";
    mysqli_query($conn, $sql);
    header("Location: add-tests.php");
}


// add a new test
if (isset($_POST['addTest'])) {


    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $user_id = $user_data['id'];

    $test_name = mysqli_real_escape_string($conn, $_POST['test_name']);


    // $var1 = rand(111, 999);  // generate random number in $var1 variable
    // $var2 = md5($var1);     // convert $var3 using md5 function and generate 32 characters hex number

    // $target_dir = "test-files/";
    // $target_file = $target_dir . $var2 . "." . basename($_FILES["test_file"]["name"]);

    // $uploadOk = 1;
    // $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    // // Allow pdf file format
    // if ($imageFileType != "pdf") {

    //     header("Location: add-tests.php?error=Sorry, only PDF files are allowed.");
    // } else {
    //     if (move_uploaded_file($_FILES["test_file"]["tmp_name"], $target_file)) {

            $sql = "INSERT INTO `tests` (`name`,`user_id`) VALUES ('$test_name','$user_id')";

            if (mysqli_query($conn, $sql)) {
                header("Location: add-tests.php?success=Test name  has been added successfully!");
            }
    //     }
    // }
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
            <title>Add Tests</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/adminDashboard.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
            <style>
                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>

            <a href="admin-dashboard.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>


            <div class="container">

                <div class="card border-light m-auto w-100">
                    <div class="row">
                        <h3 class="text-center mt-3 mb-3">Add Test</h3>
                        <?php
                        if (isset($_GET['success'])) {
                        ?>
                            <span class='text-success text-center'><?php echo $_GET['success']; ?></span>
                        <?php
                        }
                        ?>
                        <?php
                        if (isset($_GET['error'])) {
                        ?>
                            <span class='text-danger text-center'><?php echo $_GET['error']; ?></span>
                        <?php
                        }
                        ?>
                    </div>

                    <form action="" method="post" onsubmit="return validateTests()" enctype="multipart/form-data">
                        <div class="row justify-content-center">

                            <div class="col-6">
                                <input type="text" placeholder="Test name" id="test_name" name="test_name" class="form-control" id="inputValid">
                                <small class="text-danger" id="error"></small>
                                <small class="text-success" id="success"></small><br />

                            </div>
                        </div>
                        <!-- <div class="row justify-content-center">
                            <div class="col-6">
                                <div class="form-group mb-4">
                                    <input class="form-control" type="file" id="test_file" name="test_file" required>
                                    <small for="formFile" class="form-label ">Upload a document belong to Test</small><br />
                                    <small class="text-info">Note!: Only pdf file's are allowed</small>
                                </div>
                            </div>

                        </div> -->
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <button type="submit" name="addTest" class="btn btn-success w-100 mb-4">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr width="80%" class="m-auto mt-3 mb-2">

            <table class="table table-hover text-center mt-4">
                <thead>
                    <tr class="table-primary">
                        <th scope="row">SR #</th>
                        <td>Test Name</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $tests = mysqli_query($conn, "SELECT * FROM tests WHERE user_id = '$user_id'");
                    $sr = 0;
                    while ($row = mysqli_fetch_assoc($tests)) {
                        $sr++;
                        echo "<tr class='table-dark'>
                            
                            <th>$sr</th>
                            <td>$row[name]</td>
                            <td>
                            
                            
                            <button onclick='fetchTestId.call(this)' type='button' id='$row[id]' value='$row[name]'  class='btn btn-outline-info' data-toggle='modal' data-target='#updateTest'><i class='bi bi-pencil-square' ></i> Edit</button>
                           
                            <button onclick='fetchTestId.call(this)' type='button' id='$row[id]'  class='btn btn-outline-danger' data-toggle='modal' data-target='#deleteTest'><i class='bi bi-trash-fill'></i> Delete</button>
                            
                            </t>
                        </tr>";

                    ?>
                    <?php
                    }
                    ?>
                </tbody>
            </table>




            <!-- Confirmation model to delete test -->
            <div class="modal fade" id="deleteTest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="exampleModalLabel">Confirmation</h5>

                        </div>
                        <div class="modal-body">
                            Do you want to Delete Test?
                        </div>
                        <div class="modal-footer">
                            <form method='post' action=''>
                                <input type='hidden' id="delete_test_id" name='delete_test_id' />
                                <input type="submit" name="deleteTest" class="btn btn-danger" value="Confirm" />
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>



            <!-- Confirmation model to update test -->
            <div class="modal fade" id="updateTest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-info" id="exampleModalLabel">Update test name</h5>

                        </div>
                        <form method='post' action='' onsubmit="return checkUpdateTestName()" enctype="multipart/form-data">
                             <div class="modal-body">
                                <input class="form-control" type="text" id="update_test_name" name="update_test_name" placeholder="Test name" value="" required>
                                <span class="text-danger" id="error_test_name"></span>

                                <!-- <div class="form-group m-auto" >
                                    <label for="formFile" class="form-label mt-4">Update a document belong to test (optional)</label>
                                    <input class="form-control mb-2" type="file" id="update_test_file" name="update_test_file">
                                    <span class="text-info">Note!: Only pdf file's are allowed</span>
                                </div> -->
                            </div> 
                            <div class="modal-footer">

                                <input type='hidden' id="update_test_id" name='update_test_id' />
                                <input type="submit" name="updateTest" class="btn btn-success" value="Update" />
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>













            <script>
                function validateTests() {
                    let test_name = document.getElementById("test_name");
                    let error = document.getElementById("error");
                    let success = document.getElementById("success");

                    if (test_name.value.trim() == "") {
                        error.innerText = "Test name is required!";
                        return false;
                    } else {
                        return true;
                    }

                }

                function fetchTestId() {
                    let update_test_id = document.getElementById("update_test_id");
                    let delete_test_id = document.getElementById("delete_test_id");
                    let update_test_name = document.getElementById("update_test_name");

                    update_test_name.value = this.value;
                    update_test_id.value = this.id;
                    delete_test_id.value = this.id;
                }


                function checkUpdateTestName() {
                    let update_test_name = document.getElementById("update_test_name");
                    let error_test_name = document.getElementById("error_test_name");

                    if (update_test_name.value.trim() == "") {
                        error_test_name.innerText = "Test name is required!";
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