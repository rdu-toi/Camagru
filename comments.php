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

if (isset($_POST['submitcomment'])){
    try {
        $comment = $_POST['comment'];

        $query = $conn->prepare("SELECT * FROM `user_info` WHERE `email` = '$useremail'");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $key => $row){
          $stmt = $conn->prepare("INSERT INTO `comments` (`photoid`, `username`, `comment`) VALUES ('$id', :username, '$comment')");
          $stmt->bindValue(':username', $row['username']);
          $stmt->execute();
          $stmt = null;
        }
        header("Location:gallery.php?success=" . urlencode("Comment submitted!"));
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
            if (loggedIn()){ 
                if(isset($_SESSION['user_email'])){
                    echo $_SESSION['user_email'];
                }
                else if(isset($_COOKIE['user_email'])){
                    echo $_COOKIE['user_email'];
                }
            }?>
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
                  echo '<img src="'.$value['photo'].'" width="400" height="300";/><form action="comments.php" method="post";></br><textarea rows="5" cols="48" name="comment" required placeholder="Leave a comment here!";></textarea><button type="submit" class="btn btn-default" name="submitcomment">Submit Comment</button></form></div>';
                }
            }
              catch(PDOException $e)
                {
                echo "Error: " . $e->getMessage();
                }
        ?>
    </div>
  </body>
</html>