<?php

session_start();
include('includes/db.php');
include('includes/functions.php');

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
                else echo 'Guest';
            }?>
            </h2>
        </div>
    </div>
    </br>
        <?php
            if ($_GET['id']){
                $id = $_GET['id'];    
            try {
                $results = $conn->prepare("SELECT * FROM `gallery` WHERE `id` = '$id'");
                $results->execute();
                $rows = $results->fetchAll(PDO::FETCH_ASSOC);
                foreach($rows as $key => $value){
                  echo '<div style="position:relative;float:left;"><img src="'.$value['photo'].'"/></div><div width="400" height="300"><form action="comments.php"><textarea rows="4" cols="50"></textarea></form></div></div>';
                //   echo '<div width="400" height="300"><form action="comments.php"><textarea rows="4" cols="50"></textarea></form></div>';
                }
            }
              catch(PDOException $e)
                {
                echo "Error: " . $e->getMessage();
                }
            }
            else header("Location:index.php?err=" . urlencode("Error!"));

        ?>

  </body>
</html>