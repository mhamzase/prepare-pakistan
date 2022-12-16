<?php
include "database/connection.php";

$user_id = $_REQUEST['user_id'];
$quiz_id = $_REQUEST['quiz_id'];
$question_id = $_REQUEST['question_id'];
$selected_option = $_REQUEST['selected_option'];

try {
    $sql_data = mysqli_query($conn, "SELECT * FROM results WHERE user_id = '$user_id' and quiz_id = '$quiz_id' and status = 'current'");
    $fetch_data = mysqli_fetch_assoc($sql_data);
    $old_score = $fetch_data['obtained_marks'];

    $sql = mysqli_query($conn, "SELECT * FROM questions WHERE id = '$question_id'");
    $fetch_question_data = mysqli_fetch_assoc($sql);
    $fetch_correct_answer = $fetch_question_data['correct_answer'];

    
    $old_score = $old_score + 0;

    $sql_user_result = mysqli_query($conn, "UPDATE results SET `obtained_marks` = '$old_score' WHERE user_id = '$user_id' and quiz_id = '$quiz_id' and status = 'current'");

    if($sql_user_result)
    {
        echo "data success!!!";
    }
    else
    {
        throw new Exception(mysqli_error($conn));  
    }


} catch (Exception $e) {
    echo $e->getMessage();
}
