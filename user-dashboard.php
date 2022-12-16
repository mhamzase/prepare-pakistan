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
            <title>User - Dashboard</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/userDashboard.css">
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

        <body>

            <?php include "includes/header.php"; ?>

            <div class="container-fluid">
                <h1 class="text-light display-4 text-center m-5">All Available Tests</h1>


                <h4 class="text-light text-center text-success m-5">Tests by Chapters</h4>
                <hr>
                <?php
                $tests = mysqli_query($conn, "SELECT * FROM tests");
                while ($row = mysqli_fetch_assoc($tests)) {
                ?>
                    <a href="test-details.php?test_id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-outline-primary text-white btn-lg "><i class="bi bi-journal-bookmark-fill m-3"></i><?php echo $row['name']; ?></button></a>

                <?php
                }
                ?>

                <h4 class="text-light text-center text-success m-5">Tests by Full Test Paper</h4>
                <hr>

                <?php
                $tests = mysqli_query($conn, "SELECT * FROM tests");
                while ($row = mysqli_fetch_assoc($tests)) {
                ?>
                    <a href="test-fullpaper-details.php?test_id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-outline-success btn-lg "><i class="bi bi-journal-bookmark-fill m-3"></i><?php echo $row['name']; ?></button></a>

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