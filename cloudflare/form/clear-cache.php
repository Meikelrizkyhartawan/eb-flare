<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../env.php");

    //variable input by POST
    $clearCacheDomainID = $_POST['clearCacheDomainID'];
    $clearCacheZoneID = $_POST['clearCacheZoneID'];
    $clearCacheDomainName = $_POST['clearCacheDomainName'];

    // link input record
    $linkClearCacheRecord = "https://api.cloudflare.com/client/v4/zones/$clearCacheZoneID/purge_cache";

    // print_r($linkClearCacheRecord);

    // header settings
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );


    //curl settings
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $linkClearCacheRecord);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $bodyArray = array("files"=>array("http://".$clearCacheDomainName, array("url"=>"http://".$clearCacheDomainName, "headers"=>array("Origin"=>"https://www.cloudflare.com", "CF-IPCountry"=>"US", "CF-Device-Type"=>"desktop"))));

    $body = json_encode($bodyArray);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    $resp = curl_exec($curl);

    // print_r($resp);

    // print_r($resp);

    if($resp){

        header("Location: log/clear-cache-success.php");

    }else{

        header("Location: log/clear-cache-error.php");
        
    }

?>