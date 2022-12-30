<?php

$url = 'http://www.example.com';
// must set $url first....
$http = curl_init($url);
// do your curl thing here
$result = curl_exec($http);
$http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
curl_close($http);
echo $http_status;

if($http_status == 200){
    echo "access success";
}

?>