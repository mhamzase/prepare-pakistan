<?php


if (isset($_COOKIE["loadCount"])) {
    setcookie("loadCount", "", time() - 3600);
}

include "database/connection.php";

// fethcing user data as logged in
$sql_user = mysqli_query($conn, "SELECT * FROM users where email = '$_SESSION[loggedIn]'");
$sql_user_result = mysqli_fetch_assoc($sql_user);

// fetching notcompleted quiz from resutls
$sql_result = mysqli_query($conn, "SELECT * FROM test_results WHERE user_id = '$sql_user_result[id]' and status = 'current' ");
$found = mysqli_num_rows($sql_result);
if ($found) {
    $sql_fetch_result = mysqli_fetch_assoc($sql_result);

    // fethcing that quiz id not finished or completed
    $sql_update = mysqli_query($conn, "UPDATE test_results SET status = 'finished' WHERE id = '$sql_fetch_result[id]'");
}


?>

<script>
    if (localStorage.getItem("question_timer")) {
        localStorage.removeItem("question_timer");
    }
</script>