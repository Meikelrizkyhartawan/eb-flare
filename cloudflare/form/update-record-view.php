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

    // print_r($body);

    // print_r($linkRecord);



    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    //for insert logs
    $username = $_SESSION['username'];

    $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$username', 'Update a Site', '$domain', '$date')");

    //Telegram Send Message
    $text = "✅Switch Successfull✅\r\n\r\nUser : $username \r\nAction : Switch\r\nAction Value : $domain\r\nRecent Content : $content\r\nTime : $date";

    $data = [
        'text' => $text,
        'chat_id' => $chatID
    ];
    
    $responseTelegram = file_get_contents("https://api.telegram.org/bot$tokenTelegram/sendMessage?" . http_build_query($data) );



    if($resp){

        header("Location: log/update-record-view-success.php");
        
    }else{

        header("Location: log/update-record-view-error.php");
        
    }

?>