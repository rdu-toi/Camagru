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

if (isset($_GET['userid'])){
  $_SESSION['userid'] = $_GET['userid'];
}

$useridstr = $_SESSION['userid'];
$userid = (int)$useridstr;

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
          $user = $row['username'];
          $querytwo = "SELECT * FROM `user_info` WHERE `id` = '$userid'";
          $results = $conn->query($querytwo);
          $rows = $results->fetch(PDO::FETCH_ASSOC);
          if ($rows['comemail']){
            $user_email = $rows['email'];
            $message = "$user commented on one of your pics. Comment: $comment";
            mail($user_email, 'Someone commented on your pic!', $message, 'From: rdu-toi@student.wethinkcode.co.za');
          }
        }
        header("Location:comments.php?success=" . urlencode("Comment submitted!"));
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
    <?php if(isset($_GET['success'])) { ?>

        <div class="alert alert-success"><?php echo $_GET['success']; ?></div>

    <?php } ?>
    <br>
    <div class="booth">
        <?php
            try {
                $results = $conn->prepare("SELECT * FROM `gallery` WHERE `id` = '$id'");
                $results->execute();
                $rows = $results->fetchAll(PDO::FETCH_ASSOC);
                foreach($rows as $key => $value){
                  echo '
                  <img src="'.$value['photo'].'" width="400" height="300";/>
                  <form action="comments.php" method="post";>
                    <textarea style="resize:none;" rows="5" cols="54" name="comment" required placeholder="Leave a comment here!";></textarea>
                    <button type="submit" class="btn btn-default" name="submitcomment">Submit Comment</button>
                  </form>';
                }
                echo '<textarea style="resize:none;" rows="6" cols="54" disabled>';
                $results = $conn->prepare("SELECT * FROM `comments` WHERE `photoid` = '$id' ORDER by id DESC");
                $results->execute();
                $row = $results->fetchAll(PDO::FETCH_ASSOC);
                foreach($row as $key => $value){
                echo $value['username'];
                echo ": ";
                echo $value['comment'];
                echo "\n";
                }
                echo '</textarea></div>';
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