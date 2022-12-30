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
    $domain = $_POST['domain'];
    $recordID = $_POST['recordID'];
    $content = $_POST['content'];
    $zoneID = $_POST['zoneID'];

    //for update record
    $linkRecord = "https://api.cloudflare.com/client/v4/zones/$zoneID/dns_records/$recordID";
  
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $linkRecord);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);


    $bodyArray = array("type"=>"A","name"=>$domain,"content"=>$content,"proxied"=>true);

    $body = json_encode($bodyArray);


    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    $resp = curl_exec($curl);

    // print_r($resp);



    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    //for insert logs
    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Update a Site', '$domain', '$date')");



    if($resp){
        header("Location: log/update-record-success.php");
    }else{
        header("Location: log/update-record-error.php");
    }

?>