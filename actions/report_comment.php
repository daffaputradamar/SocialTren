<?php

include '../helper/connection.php'; 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

$user_id = $_SESSION['user'];

if(isset($_POST['report-comment'])) {
    $kd_comment = $_POST['kd_comment'];
    $kd_user = $_POST['kd_user'];
    $kd_post = $_POST['kd_post'];

    $query = "UPDATE comments SET is_reported = 1 WHERE kd_comment = $kd_comment";

    if (mysqli_query($con, $query)) {
        header("Location:../post.php?kd_post=$kd_post");
    } else {
        $error = urldecode("Comment is failed to be reported");
        header("Location: ../home.php??kd_post=$kd_post&error=$error&");
    }

    mysqli_close($con);
}