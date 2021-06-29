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

                body {
                    overflow-x: hidden;
                }
            </style>
        </head>

        <body>

            <?php include "includes/header.php"; ?>

            <a href="test-fullpaper-details.php?test_id=<?php echo $_GET['test_id']; ?>"><button type="button" class="btn btn-light m-2" data-bs-toggle="tooltip" title="Back to Dashboard"><i class="bi bi-arrow-left-circle-fill"></i> Back</button></a>

            <div class="container-fluid">


                <?php
                if (isset($_GET['test_id'])) {
                    $test_id = $_GET['test_id'];


                    $tests = mysqli_query($conn, "SELECT * FROM tests WHERE id = '$test_id'");
                    $fetch_data = mysqli_fetch_assoc($tests);
                    $test_name = $fetch_data['name'];
                ?>
                    <h3 class="text-light text-center m-5">Test : <span class="text-success"><?php echo $test_name; ?></span></h3>

                    <iframe src="<?php echo $fetch_data['test_file'] ?>" width="100%" height="900px">View File</iframe>

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