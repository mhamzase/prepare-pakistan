<?php
include "database/connection.php";

$sql_result = mysqli_query($conn, "SELECT * FROM users");

while ($row = mysqli_fetch_assoc($sql_result)) {
    $receiver = $row['email'];
    $subject = "Reminder! From Prepare Pakistan";
    $body = "Dear Student! Please be active on prepare pakistan platform to test your skills to take tests with various type of Tests & Subjects. ";
    $sender = "From: userhack994@gmail.com";

    mail($receiver, $subject, $body, $sender);
}
