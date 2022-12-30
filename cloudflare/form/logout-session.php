<?php

    include("../../env.php");

    date_default_timezone_set("Asia/Jakarta");

    $date = date('d-m-Y H:i:s');

    session_start();

    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Logout', '-', '$date')");

    session_destroy();

    header("Location: ../../login");

?>