<?php

session_start();
include('config/database.php');
include('config/functions.php');

if(!loggedIn()){
    header("Location:index.php?err=" .urlencode("You need to login to leave a comment!"));
    exit();
}

if (isset($_GET['id'])){
  $_SESSION['id'] = $_GET['id'];
}

$idstr = $_SESSION['id'];
$id = (int)$idstr;

$useremail = $_SESSION['user_email'];

if (isset($_POST['confirm'])){
    try {
        $stmt = $conn->prepare("DELETE FROM `gallery` WHERE `id` = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $stmt = null;
        $stmt = $conn->prepare("DELETE FROM `comments` WHERE `photoid` = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $stmt = null;
        header("Location:gallery.php?success=" . urlencode("Image successfully deleted!"));
        exit();
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
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
            <li><a href="myaccount.php">My Account</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="user_preferences.php">User Preferences</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h2>Welcome <?php
                $query = "SELECT * FROM `user_info` WHERE `email` = '$useremail'";
                $result = $conn->query($query);
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $username = $row['username'];
                echo $username;
            ?>
            </h2>
        </div>
    </div>
    </br>
    <div class="booth">
        <?php
            try {
                $results = $conn->prepare("SELECT * FROM `gallery` WHERE `id` = '$id'");
                $results->execute();
                $rows = $results->fetchAll(PDO::FETCH_ASSOC);
                foreach($rows as $key => $value){
                  echo '<img src="'.$value['photo'].'" width="400" height="300";/>
                  <form action="delete.php" method="post";>
                  <div style="color:#f1f1f1;">
                  <h4>Are you sure you want to delete this image?</h4>
                  </div>
                  <button type="submit" class="btn btn-default" name="confirm">Confirm</button></form></div>';
                }
            }
              catch(PDOException $e)
                {
                echo "Error: " . $e->getMessage();
                }
        ?>
    </div>
  </body>
  <footer>
  <div class="text-center">© 2019 Copyright: rdu-toi Camagru</div>
  </footer>
</html>