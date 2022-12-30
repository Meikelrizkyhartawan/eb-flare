<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../env.php");

    //variable input by POST
    // $clearCacheZoneID = $_POST['clearCacheZoneID'];

    // header settings
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );


    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    //GET Domain
    $get = mysqli_query($connect, "SELECT * FROM domain_list");

    //looping
    while($showZone = mysqli_fetch_assoc($get)){
        
        $myZone = $showZone['zone_id'];

        // link input record
        $linkClearCacheRecord = "https://api.cloudflare.com/client/v4/zones/$myZone/purge_cache";

        //curl settings
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $linkClearCacheRecord);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $bodyArray = array("purge_everything"=>true);

        $body = json_encode($bodyArray);


        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        $resp = curl_exec($curl);

        

    }








    // print_r($resp);


    if($resp){

        header("Location: log/clear-cache-success.php");

    }else{

        header("Location: log/clear-cache-error.php");
        
    }

?>