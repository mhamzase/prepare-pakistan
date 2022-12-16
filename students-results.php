<?php
session_start();
include "database/connection.php";

if (isset($_SESSION['loggedIn'])) {

    $session_email = $_SESSION['loggedIn'];

    $user_query = "SELECT * FROM users WHERE email = '$session_email'";
    $user_result = mysqli_query($conn, $user_query);

    $user_data = mysqli_fetch_assoc($user_result);
    $db_type = $user_data['type'];

    if ($db_type == 0) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Students - Resutls</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/userDashboard.css">
            <link rel="stylesheet" href="css/userProfile.css">
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

        <body style="overflow-x:hidden">

            <?php include "includes/header.php"; ?>


            <a href="user-dashboard.php"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" title="Back to Dashboard"><i class="bi bi-speedometer"></i> Dashboard</button></a>




            <div class="container">
            <h1 class="text-center m-5">Students Results</h1>
                <div class="row">
                    <h3 class="text-warning text-center"> Chapter Quiz Results</h3>
                </div>
                <table class="table table-hover mt-5">
                    <thead class="bg-primary">
                        <tr class="text-center">
                            <th scope="col">Test </th>
                            <th scope="col">Subject </th>
                            <th scope="col">Chapter </th>
                            <th scope="col">Obtained marks</th>
                            <th scope="col">Total marks</th>
                            <th scope="col">Taken date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $sql_user = mysqli_query($conn, "SELECT * FROM users WHERE email = $_SESSION[loggedIn]");
                        // $sql_user_result = mysqli_fetch_assoc($sql_user);

                        $sql_results = mysqli_query($conn, "SELECT * FROM results  ORDER BY id DESC");



                        while ($row = mysqli_fetch_assoc($sql_results)) {
                            $sql_chapter_id = mysqli_query($conn, "SELECT * FROM quizes WHERE id = '$row[quiz_id]'");
                            $sql_chapter_result = mysqli_fetch_assoc($sql_chapter_id);

                            $sql_chapter_name = mysqli_query($conn, "SELECT * FROM chapters WHERE id = '$sql_chapter_result[chapter_id]'");
                            $sql_chapter = mysqli_fetch_assoc($sql_chapter_name);

                            $sql_subject_name = mysqli_query($conn, "SELECT * FROM subjects WHERE id = '$sql_chapter[subject_id]'");
                            $sql_subject = mysqli_fetch_assoc($sql_subject_name);

                            $sql_test_name = mysqli_query($conn, "SELECT * FROM tests WHERE id = '$sql_subject[test_id]'");
                            $sql_test = mysqli_fetch_assoc($sql_test_name);

                        ?>
                            <tr class="bg-dark text-center
                            <?php
                            $get = $row['obtained_marks'];
                            $total = $row['total_marks'];

                            $result = $get * 100 / $total;

                            if ($result >= 80) {
                                echo "text-success";
                            } else if ($result <= 40) {
                                echo "text-danger";
                            }

                            ?>">
                                <td><?php echo $sql_test['name'] ?></td>
                                <td><?php echo $sql_subject['name'] ?></td>
                                <td><?php echo $sql_chapter['name'] ?></td>
                                <td><?php echo $row['obtained_marks']; ?></td>
                                <td><?php echo $row['total_marks']; ?></td>
                                <td><?php echo $row['taken_date']; ?></td>

                            </tr>
                        <?php
                        }
                        ?>


                    </tbody>
                </table>

            

                        <br/><br/><br/><br/>

                <div class="row mt-5">
                    <h3 class="text-warning text-center"> Full Paper Test Results</h3>
                </div>
                <table class="table table-hover mt-5">
                    <thead class="bg-primary">
                        <tr class="text-center">
                            <th scope="col">Test </th>
                            <th scope="col">Obtained marks</th>
                            <th scope="col">Total marks</th>
                            <th scope="col">Taken date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $sql_user = mysqli_query($conn, "SELECT * FROM users WHERE email = $_SESSION[loggedIn]");
                        // $sql_user_result = mysqli_fetch_assoc($sql_user);

                        $sql_results = mysqli_query($conn, "SELECT * FROM test_results  ORDER BY id DESC");



                        while ($row = mysqli_fetch_assoc($sql_results)) {
                            $sql_test_id = mysqli_query($conn, "SELECT * FROM tests WHERE id = '$row[test_id]'");
                            $sql_test_result = mysqli_fetch_assoc($sql_test_id);

                            

                        ?>
                            <tr class="bg-dark text-center
                            <?php
                            $get = $row['obtained_marks'];
                            $total = $row['total_marks'];

                            $result = $get * 100 / $total;

                            if ($result >= 80) {
                                echo "text-success";
                            } else if ($result <= 40) {
                                echo "text-danger";
                            }

                            ?>">
                                <td><?php echo $sql_test_result['name'] ?></td>
                                <td><?php echo $row['obtained_marks']; ?></td>
                                <td><?php echo $row['total_marks']; ?></td>
                                <td><?php echo $row['taken_date']; ?></td>

                            </tr>
                        <?php
                        }
                        ?>


                    </tbody>
                </table>


            </div>



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