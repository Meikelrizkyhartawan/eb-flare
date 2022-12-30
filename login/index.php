<?php

  date_default_timezone_set("Asia/Jakarta");

  $date = date('d-m-Y H:i:s');

    include("../env.php");

  ob_start();
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  session_start();

  $insidesession = $_SESSION['username'];

  if ($_SESSION['username']) {
    header("Location: ../cloudflare");
  }


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>

    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title></title>
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

    <style type="text/css">

    .gradients {
      width: 100px;
      height: 100px;
      background-image: linear-gradient(140deg, #EADEDB 0%, #BC70A4 50%, #BFD641 75%);
    }

      html, body {
        background: url(../assets/img/eb-flare-bg.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
      }

      .card {
        border-radius: 35px;
      }

      .btn {
        border-radius: 15px;
      }

      .form-control {
        border-radius: 25px;
        height: 60px;
      }

      .bringHeader {
        text-decoration: none;
        font-weight: 900;
      }

      .costumLink {
        text-decoration: none;
      }

      .costumTextBrown {
        color: brown;
      }

      h3 {
        font-family: sans-serif;
      }

      a {
        font-family: sans-serif;
      }

      .paddinglogin {
        padding-top: 100px;
      }

      /* .bgforlogin {
          background: rgba(	105, 108, 255, 0.8);
      } */

      .bgforlogin {
        background: orange;
      }

      .bgforlogin:hover {
        background: #fcba03;
      }


    </style>
  </head>
  <body onload="zoom()">

    <div class="container mt-auto windah pt-5">
      <a class="font-weight-bold bringHeader">
        <p style="font-family: sans-serif;" class="font-weight-bold text-light display-1 text-center pt-5">EB - Flare</p>
      </a>
    <div class="row justify-content-center ">
        <div class="col-md-5">
        <div class="card px-4">
            <div class="card-header bg-white py-4"><h3 class="costumTextBrown text-dark text-center">Sign in to your account</h3></div>
            <div class="card-body">
            <form id="login-form" method="POST">
            <div class="mb-3 pt-4">
                <!--<label for="Username" class="form-label">Username</label>-->
                <input type="username" name="username" placeholder="Username" class="form-control " id="username-field" aria-describedby="emailHelp" required />
            </div>
            <div class="mb-3 pt-4">
                <!--<label for="exampleInputPassword1" class="form-label">Password</label>-->
                <input type="password" name="password" placeholder="Password" class="form-control" id="password-field" required>
            </div>
            <div class="mb-3 form-check pt-1 pb-4">
                <input type="checkbox" class="form-check-input" id="checkbox" />
                <label class="form-check-label" for="exampleCheck1">Remember me</label>
            </div>
            <button type="submit" value="login" name="submit" class="btn text-white w-100 bgforlogin" id="login-form-submit">Submit</button>
            <a class="costumLink" href="https://t.me/meikelrizkyhartawan">
            <p class="text-center pt-4 text-dark costumLink">Forget your password?</p>
          </a>
            </form>
            </div>
        </div>
        </div>
    </div>
    </div>

  </body>
</html>

<script type="text/javascript">
function zoom() {
      document.body.style.zoom = "75%";
  }
</script>

<?php


if (isset($_POST['submit'])) {
  // code...

    $usernameLogin = $_POST['username'];
    $passwordLogin = $_POST['password'];

    $connect = mysqli_connect($host, $user, $password, $database);

    // $getRole = mysqli_query($connect, "SELECT * FROM users WHERE ");

    $getData = mysqli_query($connect, "SELECT * FROM users WHERE username = '$usernameLogin' AND password = '$passwordLogin' ");

    while($assignRole = mysqli_fetch_assoc($getData)){

      $userRole = $assignRole['role'];
      $_SESSION['role'] = $assignRole['role'];

    }

    $checkData = mysqli_num_rows($getData);

    // print_r($checkData);

    if($checkData > 0){
        $_SESSION['username'] = $_POST['username'];
        

        $insertLogs = mysqli_query($connect, "INSERT INTO logs_activity(username, action, action_value, timestamp) VALUES ('$usernameLogin', 'Login', '-', '$date')");

        header("location: ../cloudflare");

    }else{
        header("location: index.php");	
    }

}

 ?>
