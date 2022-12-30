<?php

    session_start();

    date_default_timezone_set("Asia/Jakarta");

    $date = date('d-m-Y H:i:s');

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../env.php");

    //variable input by POST
    $deleteDomainID = $_POST['deleteDomainID'];
    $deleteDomainName = $_POST['deleteDomainName'];
    $deleteZoneID = $_POST['deleteZoneID'];
    $confirmationToken = $_POST['confirmationToken'];

    // link input record
    $linkDeleteRecord = "https://api.cloudflare.com/client/v4/zones/$deleteZoneID/dns_records/$deleteDomainID";

    // header settings
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );

    if($confirmationToken == "AB5Z2_QFZcxlSGxrC2nPGAIDGkET5Tc0f7V5rfgP"){

        //curl settings
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $linkDeleteRecord);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $resp = curl_exec($curl);



        //connect mysql database
        $connect = mysqli_connect($host, $user, $password, $database);

        //for insert logs
        $username = $_SESSION['username'];

        $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Delete a Site', '$deleteDomainName', '$date')");



        if($resp){
            header("Location: ../index.php");
        }else{
            header("Location: ../index.php");
        }

    }else{

        if($resp){
            header("Location: ../index.php");
        }else{
            header("Location: ../index.php");
        }

    }

?>