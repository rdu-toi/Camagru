<?php

session_start();
include('includes/db.php');
include('includes/functions.php');

if(!loggedIn()){
  header("Location:index.php?err=" . urlencode("You need to login to view account!"));
  exit();
}

var $getname;

if(isset($_SESSION['user_email']){
  $getname = $_SESSION['user_email'];
}
else if (isset($_COOKIE['user_email']){
  $getname = $_COOKIE['user_email'];
}
else{
  header("Location:index.php?err=" . urlencode("You need to login!"));
  exit();
}

function getUserName($email){
	$query = $conn->prepare( "SELECT * FROM `user_info` WHERE `email` = '$email'" );
  $query->execute();
  return $query;
	}
}

if (isset($_POST['submit'])){
  try {
    $name = $_POST['imgsrc'];
    $results = getUserName($getname);
    $stmt = $conn->prepare("INSERT INTO `gallery` (`userid`, `name`, `photo`) VALUES ('$results['id']', '$results['name']', '$name')");
    $stmt->execute();
    $stmt = null;
    header("Location:myaccount.php?success=" . urlencode("Photo successfully submitted!"));
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
            <li><a href="index.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h2>Welcome <?php if(isset($_SESSION['user_email'])){ echo $_SESSION['user_email'];} else echo $_COOKIE['user_email']; ?></h2>
        </div>
    </div>
    
    <?php if(isset($_GET['success'])) { ?>

        <div class="alert alert-success"><?php echo $_GET['success']; ?></div>

    <?php } ?>

    <div class="booth">
        <video id="video" width="400" height="300" autoplay></video>
        <button id="snap">Snap Photo</button>
        <canvas id="canvas" width="400" height="300"></canvas>
        <form method="post" action="myaccount.php">
          <input name="imgsrc" id="imgsrc" type="hidden" value="">
          <button type="submit" id="submitphoto">Submit Photo</button>
        </form>
    </div>
    </br>
        <img id="test">
    </br>
    <div class="flex-container">
        <img class="items" src="http://localhost:8080/Camagru_v2/img/1.png">
        <img class="items" src="http://localhost:8080/Camagru_v2/img/2.png">
        <img class="items" src="http://localhost:8080/Camagru_v2/img/1.png">
        <img class="items" src="http://localhost:8080/Camagru_v2/img/2.png">
        <img class="items" src="http://localhost:8080/Camagru_v2/img/1.png">
    </div>

    <script src="js/photo.js"></script>
  </body>
</html>
