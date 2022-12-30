<?php

    session_start();

    if (!$_SESSION['username']) {
        header("Location: ../login");
      }

    include("../env.php");

    //connect mysql database
    $connect = mysqli_connect($host, $user, $password, $database);

    if(isset($_SESSION['firstDomain'])){

        $firstSessionDomain = $_SESSION['firstDomain'];
        $firstSessionDomainZoneID = $_SESSION['firstDomainZoneID'];

    }else{

        $getShowDomain = mysqli_query($connect, "SELECT * FROM domain_list LIMIT 1");

        while($showDomain = mysqli_fetch_assoc($getShowDomain)){
    
            $firstSessionDomain = $showDomain['name'];
            $firstSessionDomainZoneID = $showDomain['zone_id'];
    
        }
    
        //first setting domain
        $_SESSION['firstDomain'] = $firstSessionDomain;
        $_SESSION['firstDomainZoneID'] = $firstSessionDomainZoneID;

    }

    if(isset($_GET['zoneID'])){

        $firstSessionDomainZoneID = $_GET['zoneID'];

        $getShowDomainZone = mysqli_query($connect, "SELECT * FROM domain_list WHERE zone_id = '$firstSessionDomainZoneID' ");

        while($showZoneForMe = mysqli_fetch_assoc($getShowDomainZone)){

            $firstSessionDomain = $showZoneForMe['name'];

        }

    }


    //for get load balance blackeye.id
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );


    $body = '';
    $data = json_decode($body);

    $options = array(
        'http' => array(
            'method' => 'GET',
            'content' => $body,
            'header' => implode("\r\n", $header)
        )
        
    );

    $context  = stream_context_create($options);

    $responseLoadBalanceList = file_get_contents("https://api.cloudflare.com/client/v4/zones/218c158e6bb0d78f22b80e51d5731417/load_balancers", false, $context);

    $dataLoadBalanceList = json_decode($responseLoadBalanceList);

    $totalLoadBalanceList = count($dataLoadBalanceList->result);






    //for get load balance ebdesk.com
    $header = array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken"
    );


    $body = '';
    $data = json_decode($body);

    $options = array(
        'http' => array(
            'method' => 'GET',
            'content' => $body,
            'header' => implode("\r\n", $header)
        )
        
    );

    $context  = stream_context_create($options);

    $responseLoadBalanceListEbdeskCom = file_get_contents("https://api.cloudflare.com/client/v4/zones/7a7238508b9b007696ce538ad0b55a39/load_balancers", false, $context);

    $dataLoadBalanceListEbdeskCom = json_decode($responseLoadBalanceListEbdeskCom);

    $totalLoadBalanceListEbdeskCom = count($dataLoadBalanceListEbdeskCom->result);

?>

<html>
    <head>

        <!-- bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

        <!-- Datatables -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>

        <!-- For New Link -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
          <!-- <script src="assets/js/jquery-3.5.1.js"></script> -->
          <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

          <!-- Bootstrap Icon -->
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

          <!-- icon settings -->
          <link rel="icon" href="https://dash.cloudflare.com/favicon.ico">

    </head>
    <body>

        <nav class="navbar navbar-expand-lg bg-white text-white pb-0 mb-0">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="#">
                    <img style="height: 40px; width: 120px;" src="https://upload.wikimedia.org/wikipedia/id/thumb/a/a2/Cloudflare_logo.svg/1200px-Cloudflare_logo.svg.png" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 pt-3">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown Menu
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#load-balance.php">Load Balancer</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalClearCacheAllZone">Clear Cache All Zone</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                    </li>

                    <li class="nav-item">
                        <select class="form-select" name="" id="">

                            <?php
                            
                                $mysqliGetDomainList = mysqli_query($connect, "SELECT * FROM domain_list");

                                while($domainList = mysqli_fetch_assoc($mysqliGetDomainList)){
                            
                                    ?>

                                        <option href="index.php?zoneID=<?=$domainList['zone_id']?>" <?php if($domainList['name'] == $firstSessionDomain){ echo "selected"; }?>><?=$domainList['name']?></option>  

                                    <?php
                            
                                }
                            
                            ?>

                        </select>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-primary" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#exampleModalAddDomain"> <i class="bi bi-window-plus"></i> Add a Site</button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-primary" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#exampleModalAddZone"> <i class="bi bi-diagram-3-fill"></i> Add a Zone</button>
                    </li>
                </ul>

                <form class="d-flex pt-2 pr-5 mr-5" role="search">
                    <img class="dropdown-toggle" style="height: 40px; width: 40px;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS785biEGWYfQ3kCbvts_QRuNPn7IJpvovN4A&usqp=CAU" alt="">

                    <div class="dropdown">

                        <a class="text-dark text-decoration-none dropdown-toggle pt-2 ml-2" type="button" data-bs-toggle="dropdown"><?=$_SESSION['username'];?></a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">About</a></li>
                            <li><a class="dropdown-item" href="form/logout-session.php">Log Out</a></li>
                        </ul>

                    </div>

                </form>
                
                </div>
            </div>
        </nav>

        <hr class="pb-0 mb-0">

        <div class="row p-0 m-0">
        
            <div class="col-12">

                <div class="row flex-nowrap">
                    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark border border-dark">
                        <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-5 text-white min-vh-100">
                            <a class="text-center text-decoration-none text-white fs-5"><?=$firstSessionDomain?></a>

                            <hr>

                            <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                                <li class="nav-item">
                                    <a href="index.php" class="nav-link align-middle px-0">
                                        <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                        <i class="fs-4 bi-menu-up"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span> </a>
                                    <ul class="collapse show nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline" data-bs-toggle="modal" data-bs-target="#exampleModalLoadBalancer">Load Balancer</span></a>
                                        </li>
                                        <li>
                                            <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline" data-bs-toggle="modal" data-bs-target="#exampleModalClearCacheAllZone">Clear Cache All Zone</span></a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                        <i class="fs-4 bi-cloud-check"></i> <span class="ms-1 d-none d-sm-inline">Zone Domain</span></a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">

                                    <?php
                            
                                        $mysqliGetDomainList = mysqli_query($connect, "SELECT * FROM domain_list");

                                        while($domainList = mysqli_fetch_assoc($mysqliGetDomainList)){
                                    
                                            ?>
                                                
                                                <li class="w-100">
                                                    <a href="index.php?zoneID=<?=$domainList['zone_id']?>" class="nav-link px-0"> <span class="d-none d-sm-inline"><?=$domainList['name']?></span></a>
                                                </li>

                                            <?php
                                    
                                        }
                                    
                                    ?>

                                    </ul>
                                </li>

                                <!-- Features setting -->
                                <li>
                                    <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                        <i class="fs-4 bi-card-checklist"></i> <span class="ms-1 d-none d-sm-inline">Features</span> </a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a href="activity.php" class="nav-link px-0"> <span class="d-none d-sm-inline" >Logs Activity</span></a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Users settings -->
                                <li>
                                    <a href="#submenu4" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                        <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Users</span> </a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu4" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline" data-bs-toggle="modal" data-bs-target="#exampleModalUsersManagement">Users List</span></a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>

                            <hr>
                            <div class="dropdown pb-4">
                                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="https://github.com/mdo.png" alt="hugenerd" width="30" height="30" class="rounded-circle">
                                    <span class="d-none d-sm-inline mx-1"><?=$_SESSION['username'];?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                                    <li><a class="dropdown-item" href="#">New project...</a></li>
                                    <li><a class="dropdown-item" href="#">Settings</a></li>
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">Sign out</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col py-3">
                        
                    <h1 class="text-center">Load Balance List</h1>

                    <div class="row">

                    <?php

                        $numberOfRecord = 0;

                        for($i = 0; $i < $totalLoadBalanceList; $i++){

                        $numberOfRecord++;

                        ?>

                        <div class="col-sm-6 pt-3">
                            <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?=$dataLoadBalanceList->result[$i]->name?></h5>
                                <p class="card-text">Enabled
                                    <?php
                                    
                                        if($dataLoadBalanceList->result[$i]->enabled == true){

                                            echo '<i class="bi bi-check-lg text-success"></i>';

                                        }else {

                                            echo '<i class="bi bi-x-lg text-danger"></i>';

                                        }
                                    
                                    ?>
                                </p>
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLoadBalance<?=$i?>">Settings</a>
                            </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModalLoadBalance<?=$i?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?=$dataLoadBalanceList->result[$i]->name?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <form action="form/update-load-balance.php" method="POST" enctype='multipart/form-data'>

                                <!-- enable session -->
                                <div class="form-check form-switch">
                                    <input name="proxied" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php if($dataLoadBalanceList->result[$i]->proxied == true){ echo "checked"; }?>>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Proxied</label>
                                </div>
                                
                                <!-- enable session -->
                                <div class="form-check form-switch">
                                    <input name="enabled" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php if($dataLoadBalanceList->result[$i]->enabled == true){ echo "checked"; }?>>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Enabled</label>
                                </div>

                                <h4 class="text-center">Fallback Pool</h4>

                                <table class="table">
                                    <thead>
                                        <th>Load Balance Name</th>
                                        <th>Load Balance Content</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>

                                                <?php
                                                
                                                    if($dataLoadBalanceList->result[$i]->fallback_pool == "de73e0d5e8a567bb82f158c88a549b14"){

                                                        echo "HSP";

                                                    }

                                                    if($dataLoadBalanceList->result[$i]->fallback_pool == "94ce4f4928738ef54af52acd716704ee"){

                                                        echo "Moratel";

                                                    }

                                                    if($dataLoadBalanceList->result[$i]->fallback_pool == "8628401848468301a6b0b19001cb08df"){

                                                        echo "NDC";

                                                    }

                                                ?>
                                                
                                            </td>
                                            <td><input class="form-control" name="loadBalanceFallbackPool" type="text" value="<?=$dataLoadBalanceList->result[$i]->fallback_pool?>"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h4 class="text-center">Default Pools</h4>

                                <table class="table">
                                    <thead>
                                        <th>Load Balance Name</th>
                                        <th>Load Balance Content</th>
                                        <th>Priority</th>
                                    </thead>
                                    <tbody>

                                        <?php
                                        
                                            $loadBalanceContentLength = count($dataLoadBalanceList->result[$i]->default_pools);

                                            for($contentLength = 0; $contentLength < $loadBalanceContentLength; $contentLength++){

                                                
                                                $loadBalancePriority = $contentLength + 1;

                                                ?>

                                                    <tr>
                                                        <td>
                                                            <?php
                                                            
                                                                if($dataLoadBalanceList->result[$i]->default_pools[$contentLength] == "de73e0d5e8a567bb82f158c88a549b14"){

                                                                    echo "HSP";

                                                                }

                                                                if($dataLoadBalanceList->result[$i]->default_pools[$contentLength] == "94ce4f4928738ef54af52acd716704ee"){

                                                                    echo "Moratel";

                                                                }

                                                                if($dataLoadBalanceList->result[$i]->default_pools[$contentLength] == "8628401848468301a6b0b19001cb08df"){

                                                                    echo "NDC";

                                                                }

                                                            ?>
                                                        </td>
                                                        <td><?=$dataLoadBalanceList->result[$i]->default_pools[$contentLength]?></td>
                                                        <td><input class="form-control" name="loadBalancePriority[]" type="number" value="<?=$loadBalancePriority?>"></td>
                                                        <td><input name="loadBalancePriorityContent[]" type="hidden" value="<?=$dataLoadBalanceList->result[$i]->default_pools[$contentLength]?>"></td>
                                                    </tr>

                                                <?php

                                            }
                                        
                                        ?>

                                    </tbody>
                                </table>

                                <!-- input hidden session -->
                                <input name="loadBalanceID" type="hidden" value="<?=$dataLoadBalanceList->result[$i]->id?>">
                                <input name="loadBalanceName" type="hidden" value="<?=$dataLoadBalanceList->result[$i]->name?>">
                        
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                            </div>
                        </div>
                        </div>


                        <?php

                        }

                        ?>












                        <!-- second row -->
                        <?php

                            $numberOfRecord = 0;

                            for($i = 0; $i < $totalLoadBalanceListEbdeskCom; $i++){

                            $numberOfRecord++;

                            ?>

                            <div class="col-sm-6 pt-3">
                                <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?=$dataLoadBalanceListEbdeskCom->result[$i]->name?></h5>
                                    <p class="card-text">Enabled
                                        <?php
                                        
                                            if($dataLoadBalanceListEbdeskCom->result[$i]->enabled == true){

                                                echo '<i class="bi bi-check-lg text-success"></i>';

                                            }else {

                                                echo '<i class="bi bi-x-lg text-danger"></i>';

                                            }
                                        
                                        ?>
                                    </p>
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLoadBalance<?=$i?>">Settings</a>
                                </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalLoadBalance<?=$i?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><?=$dataLoadBalanceListEbdeskCom->result[$i]->name?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form action="form/update-load-balance.php" method="POST" enctype='multipart/form-data'>

                                    <!-- enable session -->
                                    <div class="form-check form-switch">
                                        <input name="proxied" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php if($dataLoadBalanceListEbdeskCom->result[$i]->proxied == true){ echo "checked"; }?>>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Proxied</label>
                                    </div>
                                    
                                    <!-- enable session -->
                                    <div class="form-check form-switch">
                                        <input name="enabled" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php if($dataLoadBalanceListEbdeskCom->result[$i]->enabled == true){ echo "checked"; }?>>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Enabled</label>
                                    </div>

                                    <h4 class="text-center">Fallback Pool</h4>

                                    <table class="table">
                                        <thead>
                                            <th>Load Balance Name</th>
                                            <th>Load Balance Content</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>

                                                    <?php
                                                    
                                                        if($dataLoadBalanceListEbdeskCom->result[$i]->fallback_pool == "de73e0d5e8a567bb82f158c88a549b14"){

                                                            echo "HSP";

                                                        }

                                                        if($dataLoadBalanceListEbdeskCom->result[$i]->fallback_pool == "94ce4f4928738ef54af52acd716704ee"){

                                                            echo "Moratel";

                                                        }

                                                        if($dataLoadBalanceListEbdeskCom->result[$i]->fallback_pool == "8628401848468301a6b0b19001cb08df"){

                                                            echo "NDC";

                                                        }

                                                    ?>
                                                    
                                                </td>
                                                <td><input class="form-control" name="loadBalanceFallbackPool" type="text" value="<?=$dataLoadBalanceListEbdeskCom->result[$i]->fallback_pool?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h4 class="text-center">Default Pools</h4>

                                    <table class="table">
                                        <thead>
                                            <th>Load Balance Name</th>
                                            <th>Load Balance Content</th>
                                            <th>Priority</th>
                                        </thead>
                                        <tbody>

                                            <?php
                                            
                                                $loadBalanceContentLength = count($dataLoadBalanceListEbdeskCom->result[$i]->default_pools);

                                                for($contentLength = 0; $contentLength < $loadBalanceContentLength; $contentLength++){

                                                    
                                                    $loadBalancePriority = $contentLength + 1;

                                                    ?>

                                                        <tr>
                                                            <td>
                                                                <?php
                                                                
                                                                    if($dataLoadBalanceListEbdeskCom->result[$i]->default_pools[$contentLength] == "de73e0d5e8a567bb82f158c88a549b14"){

                                                                        echo "HSP";

                                                                    }

                                                                    if($dataLoadBalanceListEbdeskCom->result[$i]->default_pools[$contentLength] == "94ce4f4928738ef54af52acd716704ee"){

                                                                        echo "Moratel";

                                                                    }

                                                                    if($dataLoadBalanceListEbdeskCom->result[$i]->default_pools[$contentLength] == "8628401848468301a6b0b19001cb08df"){

                                                                        echo "NDC";

                                                                    }

                                                                ?>
                                                            </td>
                                                            <td><?=$dataLoadBalanceListEbdeskCom->result[$i]->default_pools[$contentLength]?></td>
                                                            <td><input class="form-control" name="loadBalancePriority[]" type="number" value="<?=$loadBalancePriority?>"></td>
                                                            <td><input name="loadBalancePriorityContent[]" type="hidden" value="<?=$dataLoadBalanceListEbdeskCom->result[$i]->default_pools[$contentLength]?>"></td>
                                                        </tr>

                                                    <?php

                                                }
                                            
                                            ?>

                                        </tbody>
                                    </table>

                                    <!-- input hidden session -->
                                    <input name="loadBalanceID" type="hidden" value="<?=$dataLoadBalanceListEbdeskCom->result[$i]->id?>">
                                    <input name="loadBalanceName" type="hidden" value="<?=$dataLoadBalanceListEbdeskCom->result[$i]->name?>">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                            </div>


                            <?php

                            }

                            ?>













                    </div>


                    


                        <!-- add modal -->
                        <div class="modal fade" id="exampleModalAddDomain" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add sub domain</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                        
                                <form action="form/create-record.php" method="POST">

                                <label for="">Type</label>
                                <input class="form-control" type="text" value="A" name="inputType" readonly="readonly">

                                <br>
                            
                                <label for="">Sub Domain Name</label>
                                <input class="form-control" type="text" name="inputDomainName">

                                <br>

                                <label for="">Content</label>
                                <input class="form-control" type="text" name="inputContent">

                                <br>

                                <label for="">Zone ID</label>
                                <select class="form-control" name="inputZoneID" id="">

                                <?php
                                            
                                    $mysqliGetDomainList = mysqli_query($connect, "SELECT * FROM domain_list");

                                    while($domainList = mysqli_fetch_assoc($mysqliGetDomainList)){
                                        
                                        ?>

                                            <option value="<?=$domainList['zone_id']?>"><?=$domainList['name']?></option>  

                                        <?php
                                        
                                    }
                                        
                                ?>

                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- add zone modal -->
                        <div class="modal fade" id="exampleModalAddZone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add Zone</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                        
                                <form action="form/add-zone-mysql.php" method="POST">
                            
                                <label for="">Zone Name</label>
                                <input class="form-control" type="text" name="addZoneName">

                                <br>

                                <label for="">Zone ID</label>
                                <input class="form-control" type="type" name="addZoneID" value="">

                                <br>

                                <label for="">Zone API Token</label>
                                <input class="form-control" type="type" name="addZoneAPIToken" value="">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- clear all zone cache -->
                        <div class="modal fade" id="exampleModalClearCacheAllZone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                        
                                <form action="form/clear-cache-all.php" method="POST">

                                    <br>
                            
                                    <label for="">Are you sure to purge / clear cache on all zones?</label>

                                    <br>

                                    <input type="hidden" name="clearCacheZoneID" value="<?=$firstSessionDomainZoneID?>">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- load balancer menu -->
                        <div class="modal fade" id="exampleModalLoadBalancer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Load Balancer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                In Progress
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                            </div>
                            </div>
                        </div>
                        </div>


                        <!-- Users Management -->
                        <div class="modal fade" id="exampleModalUsersManagement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Users Management</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            
                            <table class="table" id="exampleUsers">
                                <thead>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                    <th>Menu</th>
                                </thead>
                                <tbody>
                                    <?php
                                        
                                        $queryUsers = mysqli_query($connect, "SELECT * FROM users");

                                        while($queryUsersShow = mysqli_fetch_assoc($queryUsers)){
                                            
                                            ?>

                                                <tr>
                                                    <td><?=$queryUsersShow['username']?></td>
                                                    <td><?=$queryUsersShow['password']?></td>
                                                    <td><?=$queryUsersShow['role']?></td>
                                                    <td>
                                                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#exampleModalUsersManagementUpdate<?=$queryUsersShow['id']?>" ><i class="bi bi-pencil"></i> Edit</button>
                                                        <a href="form/delete-user.php?id=<?=$queryUsersShow['id']?>&username=<?=$queryUsersShow['username']?>"><button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Delete</button></a>
                                                    </td>
                                                </tr>

                                            <?php

                                        }

                                    ?>
                                </tbody>
                            </table>

                            <?php
                                        
                                        $queryUsersMore = mysqli_query($connect, "SELECT * FROM users");
        
                                        while($queryUsersShowMore = mysqli_fetch_assoc($queryUsersMore)){
                                                
                                    ?>

                                                <!-- Update user -->
                                                <div class="modal fade" id="exampleModalUsersManagementUpdate<?=$queryUsersShowMore['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add a user</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    
                                                        <form action="form/add-user.php" method="POST">

                                                            <label for="">Username</label>
                                                            <input class="form-control" type="text" name="username" id="">

                                                            <br>

                                                            <label for="">Password</label>
                                                            <input class="form-control" type="text" name="password" id="">

                                                            <br>

                                                            <label for="">Role</label>
                                                            <select class="form-select" name="role" id="">
                                                                <option value="Superuser">Superuser</option>
                                                                <option value="Admin">Admin</option>
                                                                <option value="Editor">Editor</option>
                                                                <option value="Viewer">Viewer</option>
                                                            </select>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>

                                                        </form>

                                                    </div>
                                                    </div>
                                                </div>
                                                </div>

        
                                    <?php
        
                                        }
        
                                    ?>

                            
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalUsersManagementAdd">Add a user</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- Add user -->
                        <div class="modal fade" id="exampleModalUsersManagementAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add a user</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            
                                <form action="form/add-user.php" method="POST">

                                    <label for="">Username</label>
                                    <input class="form-control" type="text" name="username" id="">

                                    <br>

                                    <label for="">Password</label>
                                    <input class="form-control" type="text" name="password" id="">

                                    <br>

                                    <label for="">Role</label>
                                    <select class="form-select" name="role" id="">
                                        <option value="Superuser">Superuser</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Editor">Editor</option>
                                        <option value="Viewer">Viewer</option>
                                    </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>

                                </form>

                            </div>
                            </div>
                        </div>
                        </div>
                        
                        <script type="text/javascript">

                            //initialize main table
                            $('#example').dataTable( {
                            "pageLength": 6
                            } );

                            //initialize second table
                            $('#exampleUsers').dataTable( {
                            "pageLength": 6,
                            "paging" : false,
                            "searching" : false
                            } );

                        </script>

                        <br>


                    </div>
                </div>


            </div>

            <div class="col-10">

            </div>

        </div>

    </body>
</html>