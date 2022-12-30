<?php

    //session started
    session_start();

    $id = $_GET['id'];

    include("../../env.php");

    date_default_timezone_set("Asia/Jakarta");

    $date = date('d-m-Y H:i:s');
    $username_update = $_POST['username'];
    $password_update = $_POST['password'];
    $role_update = $_POST['role'];

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    $updateUser = mysqli_query($connect, "UPDATE users SET username = ");
    // $addUser = mysqli_query($connect, "DELETE FROM users WHERE id = '$id' ");


    //insert logs session
    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Update a User', '$username_update', '$date')");

    if($addUser){

        header("Location: log/add-zone-success.php");
        
    }else{

        header("Location: log/add-zone-error.php");
        
    }

       
?>