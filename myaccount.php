<?php

session_start();
include('config/database.php');
include('config/functions.php');

if(!loggedIn()){
  header("Location:index.php?err=" . urlencode("You need to login to view account!"));
  exit();
}

$getname = $_SESSION['user_email'];

if (isset($_POST['submit'])){
  if ($_POST['imgsrc']){
    try {
      $query = $conn->prepare("SELECT * FROM `user_info` WHERE `email` = '$getname'");
      $query->execute();
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      $name = $_POST['imgsrc'];
      foreach($result as $key => $row){
        $stmt = $conn->prepare("INSERT INTO `gallery` (`userid`, `username`, `photo`) VALUES (:id, :username, '$name')");
        $stmt->bindValue(':id', $row['id']);
        $stmt->bindValue(':username', $row['username']);
        $stmt->execute();
        $stmt = null;
      }
      header("Location:myaccount.php?success=" . urlencode("Photo successfully submitted!"));
      exit();
    }
    catch(PDOException $e)
      {
      echo "Error: " . $e->getMessage();
      }
  }
  else{
    header("Location:myaccount.php?err=" . urlencode("You need to choose a sticker before uploading!"));
    exit();
  }
}

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
          <a class="navbar-brand" href="#">Camagru</a>
        </div>
        <div id="navbar" class="navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="logout.php">Logout</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="user_preferences.php">User Preferences</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h2>Welcome <?php 
                $query = "SELECT * FROM `user_info` WHERE `email` = '$getname'";
                $result = $conn->query($query);
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $username = $row['username'];
                echo $username;
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

    <div class="booth">
        <video id="video" width="400" height="300" autoplay></video>
        <button id="snap">Snap Photo</button>
        <canvas id="canvas" width="400" height="300"></canvas>
        <form method="post" action="myaccount.php">
          <input name="imgsrc" id="imgsrc" type="hidden" value="">
          <button type="submit" name="submit" id="submitphoto">Submit Photo</button>
        </form>
    </div>
    </br>
    <div class="flex-container">
        <img class="items" src="http://localhost:8080/Camagru/img/1.png">
        <img class="items" src="http://localhost:8080/Camagru/img/2.png">
        <img class="items" src="http://localhost:8080/Camagru/img/3.png">
        <img class="items" src="http://localhost:8080/Camagru/img/4.png">
        <img class="items" src="http://localhost:8080/Camagru/img/5.png">
    </div>
    <div class="flex-container">
        <?php
            try {
              $email = $_SESSION['user_email'];
              $query = "SELECT * FROM `user_info` WHERE `email` = '$email'";
              $result = $conn->query($query);
              $row = $result->fetch(PDO::FETCH_ASSOC);

              $results = $conn->prepare("SELECT * FROM `gallery` ORDER by id DESC");
              $results->execute();
              $rows = $results->fetchAll();
              $count = $results->rowCount();
              foreach($rows as $key => $value){
                if ($key <= 4){
                  echo '<div style="position:relative;float:left;margin:10px;border:3px solid black;">
                          <img style="width:200px;height:150px;" src="'.$value['photo'].'"/>
                          <div style="position: absolute;width:200px;height:40px;bottom:0px;;color:#f1f1f1;">';
                  echo '
                  <a style="float:right; margin-right: 10px" href="delete.php?id='.$value['id'].'" class="btn btn-primary a-btn-slide-text">
                  <span><strong>Delete</strong></span>
                  </a>';
                  echo '</div>
                        </div>';
                }
              }
            }
            catch(PDOException $e)
            {
              echo "Error: " . $e->getMessage();
            }
        ?>
        <br>
    </div>

    <script src="js/photo.js"></script>
  </body>

  <footer>
  <div class="text-center">© 2019 Copyright: rdu-toi Camagru</div>
  </footer>
</html>