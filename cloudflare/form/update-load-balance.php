<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    
    include("../../env.php");

    //session started
    session_start();

    //Variable Input Sesssion
    $loadBalanceID = $_POST['loadBalanceID'];
    $loadBalanceName = $_POST['loadBalanceName'];
    $loadBalanceFallbackPool = $_POST['loadBalanceFallbackPool'];
    $loadBalancePriorityContent = $_POST['loadBalancePriorityContent'];
    $loadBalancePriority = $_POST['loadBalancePriority'];
    $loadBalancePriorityContentLength = count($loadBalancePriorityContent);
    //proxied variable
    if(isset($_POST['proxied'])){
        $loadBalanceProxied = true;
    }else{
        $loadBalanceProxied = false;
    }
    //enabled variable
    if(isset($_POST['enabled'])){
        $loadBalanceEnabled = true;
    }else{
        $loadBalanceEnabled = false;
    }

    // $json = json_encode($_POST);

    // print_r($_POST);

    $arrayLoadBalance = array();

    for($i = 0; $i < $loadBalancePriorityContentLength; $i++){


        $arrayLoadBalance[$loadBalancePriority[$i] - 1] = $loadBalancePriorityContent[$i];

        
    }

    $newArrayLoadBalance = array();

    for ($crawling=0; $crawling < count($arrayLoadBalance); $crawling++) { 
        array_push($newArrayLoadBalance, $arrayLoadBalance[$crawling]);
    }


    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );

    $linkLoadBalance = "https://api.cloudflare.com/client/v4/zones/218c158e6bb0d78f22b80e51d5731417/load_balancers/$loadBalanceID";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $linkLoadBalance);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $bodyArray = array("proxied"=>$loadBalanceProxied,"enabled"=>$loadBalanceEnabled,"name"=>$loadBalanceName,"session_affinity"=>"none","session_affinity_attributes"=>array("samesite"=>"Auto","secure"=>"Auto","drain_duration"=>0),"steering_policy"=>"off","fallback_pool"=>$loadBalanceFallbackPool,"default_pools"=>$newArrayLoadBalance,"pop_pools"=>new stdClass,"region_pools"=>new stdClass,"random_steering"=>array("default_weigth"=>1));

    $body = json_encode($bodyArray);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

    // print_r($body);

    $resp = curl_exec($curl);


    if($resp){

        header("Location: log/update-load-balance-success.php");

    }else{

        header("Location: log/update-load-balance-error.php");
        
    }

?>