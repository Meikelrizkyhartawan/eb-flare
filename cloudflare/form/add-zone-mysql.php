<?php

    session_start();

    date_default_timezone_set("Asia/Jakarta");

    $date = date('d-m-Y H:i:s');

    //include env file
    include("../../env.php");

    $addZoneName = $_POST['addZoneName'];
    $addZoneID = $_POST['addZoneID'];
    $addZoneAPIToken = $_POST['addZoneAPIToken'];

    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    $insert = mysqli_query($connect, "INSERT INTO domain_list (name, zone_id, api_token) VALUES ('$addZoneName', '$addZoneID', '$addZoneAPIToken') ");

    //for insert logs
    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Add a Zone', '$addZoneName', '$date')");

    if($insert){

        header("Location: log/add-zone-success.php");
        
    }else{

        header("Location: log/add-zone-error.php");
        
    }

?>