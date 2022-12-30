<?php

    //session started
    session_start();

    include("../../env.php");

    date_default_timezone_set("Asia/Jakarta");

    $date = date('d-m-Y H:i:s');

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../env.php");

    $username_add = $_POST['username'];
    $password_add = $_POST['password'];
    $role_add = $_POST['role'];

    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    $addUser = mysqli_query($connect, "INSERT INTO users (username, password, role) VALUES ('$username_add', '$password_add', '$role_add')");

    //insert logs session
    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Add a User', '$username_add', '$date')");

    if($addUser){

        header("Location: log/create-record-success.php");

    }else{

        header("Location: log/create-record-error.php");

    }

?>