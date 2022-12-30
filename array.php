<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $list = array("de73e0d5e8a567bb82f158c88a549b14", "94ce4f4928738ef54af52acd716704ee", "8628401848468301a6b0b19001cb08df");

    $list2 = array(2, 1, 3);

    $new = array();

    for ($i=0; $i < count($list); $i++) { 
        $new[$list2[$i] - 1] = $list[$i];
    }

    // $encode = json_encode($new);

    $reindex_array = array_values($new);

    $encode = json_encode($reindex_array);

    print_r($encode);

?>