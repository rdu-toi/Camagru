<?php

session_start();
include('config/database.php');
include('config/functions.php');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Account</title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Camagru</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          <?php
            if(loggedIn()){
                echo '<li><a href="logout.php">Logout</a></li>';
                echo '<li><a href="myaccount.php">My Account</a></li>';
            }
            else {
                echo '<li><a href="index.php">Login</a></li>';
                echo '<li><a href="register.php">Register</a></li>';
            }
            ?>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h2>Welcome <?php
            if (loggedIn()){ 
                if(isset($_SESSION['user_email'])){
                    echo $_SESSION['user_email'];
                }
                else if(isset($_COOKIE['user_email'])){
                    echo $_COOKIE['user_email'];
                }
                else echo "Guest";
            }
            ?>
            </h2>
        </div>
    </div>
    <?php if(isset($_GET['success'])) { ?>

        <div class="alert alert-success"><?php echo $_GET['success']; ?></div>

    <?php } ?>

    <?php if(isset($_GET['err'])) { ?>

        <div class="alert alert-danger"><?php echo $_GET['err']; ?></div>

    <?php } ?>
    </br>
        <?php
            try {
              $results = $conn->prepare("SELECT * FROM `gallery`");
              $results->execute();
              $rows = $results->fetchAll();
              foreach($rows as $key => $value){
                echo '<div style="position:relative;float:left;"><a href="comments.php?id='.$value['id'].'"><img src="'.$value['photo'].'"/></a><h4><div style="position: absolute;width:400px;height:40px;bottom:0px;background-color:black;opacity:0.5;color:#f1f1f1;">'.$value['username'].'</h4></div></div>';
              }
            }
            catch(PDOException $e)
            {
              echo "Error: " . $e->getMessage();
            }
        ?>

  </body>
</html>