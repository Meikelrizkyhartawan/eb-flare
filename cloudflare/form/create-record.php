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
    $inputType = $_POST['inputType'];
    $inputDomainName = $_POST['inputDomainName'];
    $inputContent = $_POST['inputContent'];
    $inputZoneID = $_POST['inputZoneID'];

    // link input record
    $linkInputRecord = "https://api.cloudflare.com/client/v4/zones/$inputZoneID/dns_records";

    // header settings
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );

    //curl settings
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $linkInputRecord);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $bodyArray = array("type"=>$inputType,"name"=>$inputDomainName,"content"=>$inputContent,"proxied"=>true);

    $body = json_encode($bodyArray);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    $resp = curl_exec($curl);

    // print_r($resp);

    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    //for insert logs
    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Add a Site', '$inputDomainName', '$date')");


    if($resp){
        header("Location: log/create-record-success.php");
    }else{
        header("Location: log/create-record-error.php");
    }

?>