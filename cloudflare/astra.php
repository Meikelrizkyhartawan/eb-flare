<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    session_start();

    // if (!$_SESSION['username']) {
    //     header("Location: ../login");
    //   }

    if (!isset($_SESSION['username'])) {
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






  //data isa.blackeye.id

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



  //Astra get domain list (blackeye.id)
  $responseDomainAll = file_get_contents("https://api.cloudflare.com/client/v4/zones/218c158e6bb0d78f22b80e51d5731417/dns_records?type=A", false, $context);

  $dataDomainAll = json_decode($responseDomainAll);

  $totalDomainAll = count($dataDomainAll->result);

  //print_r($totalDomainAll);

  //Blank array for astra
  $astraDomainList = [];

  //Astra looping get astra domain list
  for($astraNumber = 0; $astraNumber < $totalDomainAll; $astraNumber++){

    $astraStartList = ["admin-cpro.blackeye.id", "admin-eco.blackeye.id", "admineco.blackeye.id", "admin-pna.blackeye.id", "cpro.blackeye.id", "eco.blackeye.id", "fmn.blackeye.id", "fms.blackeye.id", "inqurio.blackeye.id", "pna.blackeye.id"];
    $astraStartListCount = count($astraStartList);

    for($astraStartNumber = 0; $astraStartNumber < $astraStartListCount; $astraStartNumber++){

        if($dataDomainAll->result[$astraNumber]->name == $astraStartList[$astraStartNumber]){

            array_push($astraDomainList, $dataDomainAll->result[$astraNumber]);
    
        }

    }

  }


?>

<html>
    <head>

        <!-- React JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.production.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/18.2.0/umd/react-dom.production.min.js"></script>
        <script src="https://unpkg.com/@babel/standalone@7.19.2/babel.min.js"></script>

        <!-- bootstrap -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script> -->

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

            <!-- sidebar -->
          <script src="../assets/js/sidebars.js"></script>
          <link href="../assets/css/sidebars.css" rel="stylesheet">
          <script src="../assets/js/bootstrap.bundle.min.js"></script>
          <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

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
                    <li class="nav-item" style="padding-right: 12px;">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>


                    <li class="nav-item" 
                    
                    <?php
                    
                        if($_SESSION['role'] == "Viewer"){

                            echo 'style="display: none;"';
                  
                        }

                    ?>
                    
                    >
                        <select class="form-select" name="" id="" onchange="location = this.value;">
                            <option>Select Domain</option>

                            <?php
                            
                                $mysqliGetDomainList = mysqli_query($connect, "SELECT * FROM domain_list");

                                while($domainList = mysqli_fetch_assoc($mysqliGetDomainList)){
                            
                                    ?>

                                        <option value="index.php?zoneID=<?=$domainList['zone_id']?>"><?=$domainList['name']?></option>  

                                    <?php
                            
                                }
                            
                            ?>

                        </select>
                    </li>
                    <li class="nav-item"

                    <?php
                    
                        if($_SESSION['role'] == "Viewer"){

                            echo 'style="display: none;"';
                
                        }

                        if($_SESSION['role'] == "Editor"){

                            echo 'style="display: none;"';
                
                        }

                    ?>

                    >
                        <button class="btn btn-primary" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#exampleModalAddDomain"> <i class="bi bi-window-plus"></i> Add a Site</button>
                    </li>
                    <li class="nav-item"
                    <?php
                    
                        if($_SESSION['role'] == "Viewer"){

                            echo 'style="display: none;"';
                
                        }

                        if($_SESSION['role'] == "Editor"){

                            echo 'style="display: none;"';
                
                        }

                    ?>
                    >
                        <button class="btn btn-primary" style="margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#exampleModalAddZone"> <i class="bi bi-diagram-3-fill"></i> Add a Zone</button>
                    </li>
                </ul>

                <form class="d-flex pt-2 pr-5 mr-5" role="search">
                    <img class="dropdown-toggle" style="height: 40px; width: 40px;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS785biEGWYfQ3kCbvts_QRuNPn7IJpvovN4A&usqp=CAU" alt="">

                    <div class="dropdown">

                        <a class="text-dark text-decoration-none dropdown-toggle pt-2 ml-2 text-capitalize" type="button" data-bs-toggle="dropdown">&nbsp;&nbsp;<?=$_SESSION['username'];?></a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalAbout">About</a></li>
                            <li><a class="dropdown-item" href="main-view.php">Main View</a></li>
                            <li><a class="dropdown-item" href="form/logout-session.php">Log Out</a></li>
                        </ul>

                    </div>
                    
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;

                </form>
                
                </div>
            </div>
        </nav>


        <hr class="pb-0 mb-0">

        <div class="row p-0 m-0">
        
            <div class="col-12">

                <div class="row flex-nowrap">
                <div class="flex-shrink-0 p-3 bg-white border" style="width: 280px;">
                    <!-- <a href="index.php" class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
                    <svg class="bi pe-none me-2" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
                    <span class="fs-5 fw-semibold"><?=$firstSessionDomain?></span>
                    <span class="fs-5 fw-semibold">Home</span> -->

                    <br>
                    
                    </a>
                    <ul class="list-unstyled ps-0">
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                        <i class="bi bi-menu-button-wide"></i>&nbsp;Dashboard
                        </button>
                        <div class="collapse show" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="main-view.php" class="link-dark d-inline-flex text-decoration-none rounded">IMA & ISA</a></li>
                            <li><a href="astra.php" class="link-dark d-inline-flex text-decoration-none rounded">Astra</a></li>
                            <li><a href="ias.php" class="link-dark d-inline-flex text-decoration-none rounded">IAS</a></li>
                        </ul>
                        </div>

                    </li>
                    <li class="mb-1"
                    <?php
                    
                    if($_SESSION['role'] == "Viewer"){

                        echo 'style="display: none;"';
            
                    }

                    ?>
                    >
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                        <i class="bi bi-stoplights"></i>&nbsp;Traffic
                        </button>
                        <div class="collapse" id="dashboard-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li
                            <?php
                    
                                if($_SESSION['role'] == "Viewer"){

                                    echo 'style="display: none;"';
                        
                                }

                            ?>
                            ><a href="load-balance.php" class="link-dark d-inline-flex text-decoration-none rounded">Load Balancer</a></li>                            
                        </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#caching-collapse" aria-expanded="false">
                        <i class="bi bi-file-earmark-minus"></i>&nbsp;Caching
                        </button>
                        <div class="collapse" id="caching-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">                    
                            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded" data-bs-toggle="modal" data-bs-target="#exampleModalClearCacheAllZone">Clear Cache All Zone</a></li>
                        </ul>
                        </div>
                    </li>
                    <li class="mb-1" 
                    <?php
                    
                        if($_SESSION['role'] == "Viewer"){

                            echo 'style="display: none;"';
                  
                        }

                    ?>
                    >
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                        <i class="bi bi-pc-display-horizontal"></i>&nbsp;Zone Domain
                        </button>
                        <div class="collapse" id="orders-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <?php
    
                                $mysqliGetDomainList = mysqli_query($connect, "SELECT * FROM domain_list");

                                while($domainList = mysqli_fetch_assoc($mysqliGetDomainList)){

                                    ?>

                                        <li><a href="index.php?zoneID=<?=$domainList['zone_id']?>" class="link-dark d-inline-flex text-decoration-none rounded"><?=$domainList['name']?></a></li>

                                    <?php

                                }
                        
                        ?>
                        </ul>
                        </div>
                    </li>
                    <li class="mb-1"
                    <?php
                    
                        if($_SESSION['role'] == "Viewer"){

                            echo 'style="display: none;"';
                
                        }

                        if($_SESSION['role'] == "Editor"){

                            echo 'style="display: none;"';
                
                        }

                    ?>
                    >
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#features-collapse" aria-expanded="false">
                        <i class="bi bi-safe2"></i>&nbsp;Features
                        </button>
                        <div class="collapse" id="features-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="activity.php" class="link-dark d-inline-flex text-decoration-none rounded">Logs Activity</a></li>
                        </ul>
                        </div>
                    </li>
                    <li class="mb-1"
                    <?php
                    
                        if($_SESSION['role'] == "Viewer"){

                            echo 'style="display: none;"';
                
                        }

                        if($_SESSION['role'] == "Editor"){

                            echo 'style="display: none;"';
                
                        }

                    ?>
                    >
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#users-collapse" aria-expanded="false">
                        <i class="bi bi-people"></i>&nbsp;Users
                        </button>
                        <div class="collapse" id="users-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded" data-bs-toggle="modal" data-bs-target="#exampleModalUsersManagement">Users List</a></li>
                        </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>&nbsp;Account
                        </button>
                        <div class="collapse" id="account-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a class="link-dark d-inline-flex text-decoration-none rounded" href="form/logout-session.php">Sign out</a></li>

                        </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    <li class="mb-1">
                        &nbsp;
                    </li>
                    </ul>
                </div>

                    <div class="col py-3">

                    <!-- <h1 class="text-center">Viewer Only</h1> -->
                    <div class="text-center" id="title-page"></div>

                    <div class="row pt-3">

                        <?php

                            for($astraCardNumber = 0; $astraCardNumber < $astraStartListCount; $astraCardNumber++){

                                ?>

                                    <div class="col-sm-6 
                                    <?php

                                    // if($astraCardNumber != 0 || $astraCardNumber != 1){
                                    //     echo "pt-3";
                                    // }

                                    echo "pt-3";

                                    ?>
                                    ">
                                        <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?=$astraDomainList[$astraCardNumber]->name?></h5>
                                            <p class="card-text">Status : 
                                            <?php
                                                                                                
                                                // if($astraDomainList[$astraCardNumber]->content == "103.75.25.116"){
                                                //     echo "Moratel NDC - 103.75.25.116";
                                                // }

                                                if($astraDomainList[$astraCardNumber]->content == "103.154.125.187"){
                                                    echo "Moratel Bintaro - 103.154.125.187";
                                                }

                                                if($astraDomainList[$astraCardNumber]->content == "103.121.213.204"){
                                                    echo "HSP Bintaro - 103.121.213.204";
                                                }

                                                
                                                // if($dataDomainIMAEBDESKCOM->result[0]->content == "103.75.25.116"){
                                                //     echo "Moratel NDC - 103.75.25.116";
                                                // }

                                                // if($dataDomainIMAEBDESKCOM->result[0]->content == "103.75.25.118"){
                                                //     echo "Moratel NDC - 103.75.25.118";
                                                // }

                                                // if($dataDomainIMAEBDESKCOM->result[0]->content == "103.121.213.206"){
                                                //     echo "HSP Bintaro - 103.121.213.206";
                                                // }

                                                // if($dataDomainIMAEBDESKCOM->result[0]->content == "103.154.125.186"){
                                                //     echo "Moratel Bintaro - 103.154.125.186";
                                                // }

                                                // if($dataDomainIMAEBDESKCOM->result[0]->content == "103.154.125.187"){
                                                //     echo "Moratel Bintaro - 103.154.125.187";
                                                // }

                                            ?>
                                            </p>
                                            <a>Content : <?=$astraDomainList[$astraCardNumber]->content?></a>
                                            <br>
                                            <br>
                                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalDNSAstra<?=$astraCardNumber?>">Settings</a>
                                        </div>
                                        </div>
                                    </div>

                                <?php

                            }
                        
                        ?>

                    </div>






                    <!-- Modal Section Astra -->
                    <?php

                        for($astraCardNumber = 0; $astraCardNumber < $astraStartListCount; $astraCardNumber++){

                            ?>








                                <!-- IMA EBDESK -->
                                <div class="modal fade" id="exampleModalDNSAstra<?=$astraCardNumber?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"><?=$astraDomainList[$astraCardNumber]->name?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        
                                        <form action="form/update-record-view-astra.php" method="POST">

                                            <label for="">Record Name</label>
                                            <input class="form-control" type="text" name="domain" value="<?=$astraDomainList[$astraCardNumber]->name?>" readonly="readonly">

                                            <!-- <br> -->

                                            <!-- <label for="">Record ID</label> -->
                                            <input class="form-control" type="hidden" name="recordID" value="<?=$astraDomainList[$astraCardNumber]->id?>" readonly="readonly">

                                            <br>

                                            <label for="">Content</label>
                                            <input type="hidden" name="zoneID" value="<?=$astraDomainList[$astraCardNumber]->zone_id?>">

                                            <select class="form-select" name="content" id="">
                                                <option value="103.121.213.204"
                                                
                                                <?php
                                                
                                                    if($astraDomainList[$astraCardNumber]->content == "103.121.213.204")
                                                    {
                                                        echo "selected";
                                                    }
                                                
                                                ?>

                                                >HSP Bintaro - 103.121.213.204</option>
                                                <option value="103.154.125.187"
                                                
                                                <?php
                                                
                                                    if($astraDomainList[$astraCardNumber]->content == "103.154.125.187")
                                                    {
                                                        echo "selected";
                                                    }
                                                
                                                ?>

                                                >Moratel Bintaro - 103.154.125.187</option>
                                            </select>

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

                        <!-- About IP Public -->
                        <div class="modal fade" id="exampleModalAbout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">About</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Name</th>
                                        <th>Content</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Moratel NDC</td>
                                            <td>103.75.25.116</td>
                                        </tr>
                                        <tr>
                                            <td>HSP Bintaro</td>
                                            <td>103.121.213.206</td>
                                        </tr>
                                        <tr>
                                            <td>Moratel Bintaro</td>
                                            <td>103.154.125.186</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

                        <!-- React JS Script -->

                        <script type="text/babel">

                            const root = ReactDOM.createRoot(document.getElementById('title-page'));

                            root.render(
                                <h1>Astra</h1>
                            );

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