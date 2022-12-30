<?php

    //api token of cloudflare
    $authToken = "AB5Z2_QFZcxlSGxrC2nPGAIDGkET5Tc0f7V5rfgP";

    //mysql databases parameters
    $host = "192.168.20.47";
    $user = "root";
    $password = "rahasia2021";
    $database = "eb_flare";

    $connect = mysqli_connect($host, $user, $password, $database);

    if($connect){
        echo "success";
    }else{
        echo "failed";
    }

?>