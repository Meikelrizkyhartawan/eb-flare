<?php

    //session started
    session_start();

    $id = $_GET['id'];

    include("../../env.php");

    date_default_timezone_set("Asia/Jakarta");

    $date = date('d-m-Y H:i:s');
    $username_delete = $_GET['username'];

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    $addUser = mysqli_query($connect, "DELETE FROM users WHERE id = '$id' ");


    //insert logs session
    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Delete a User', '$username_delete', '$date')");

    if($addUser){

        header("Location: log/add-zone-success.php");
        
    }else{

        header("Location: log/add-zone-error.php");
        
    }

       
?>