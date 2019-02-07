<?php

session_start();
include('config/database.php');
include('config/functions.php');

function paginate($num){
  $counter = 1;
  $pages = intdiv($num, 5);
  if ($num % 5 > 0){
    $pages = $pages + 1;
  }
  while($counter <= $pages){
    echo '<li class="page-item"><a class="page-link" href="gallery.php?page='.$counter.'">'.$counter.'</a></li>';
    $counter = $counter + 1;
  }
}

function delete($value, $row){
  if ($value['userid'] === $row['id']){
    echo '
    <a style="float:right; margin-right: 10px" href="delete.php?id='.$value['id'].'" class="btn btn-primary a-btn-slide-text">
    <span><strong>Delete</strong></span>
    </a>';
  }
  else{
    echo '
    <a style="float:right; margin-right: 10px" target="iframe_a" class="btn btn-primary a-btn-slide-text" href="like.php?id='.$value['id'].'">
      <span><strong>Like</strong></span>
    </a>';
    echo '
    <a style="float:right; margin-right: 10px" href="comments.php?id='.$value['id'].'&userid='.$value['userid'].'" class="btn btn-primary a-btn-slide-text">
    <span><strong>Comment</strong></span>
    </a>';
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
          <?php
            if(loggedIn()){
                echo '<li><a href="logout.php">Logout</a></li>';
                echo '<li><a href="myaccount.php">My Account</a></li>';
                echo '<li><a href="user_preferences.php">User Preferences</a></li>';
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
              $useremail = $_SESSION['user_email'];
              $query = "SELECT * FROM `user_info` WHERE `email` = '$useremail'";
              $result = $conn->query($query);
              $row = $result->fetch(PDO::FETCH_ASSOC);
              $username = $row['username'];
              echo $username;
            }
            else echo "Human";
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
    <br>
    <div>
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
                if (isset($_GET['page'])){
                  $page = $_GET['page'];
                }
                else $page = 1;
                if ($key <= ($page * 5) - 1 && $key >= ($page * 5 - 5)){
                  echo '<div style="position:relative;float:left;margin:10px;border:3px solid black;">
                          <img src="'.$value['photo'].'"/>
                          <div style="position: absolute;width:400px;height:40px;bottom:0px;;color:#f1f1f1;">
                          <h4 style="display:inline-block; margin-left:6px"><strong>'.$value['username'].'</strong></h4>';
                  if (loggedIn()){
                    delete($value, $row);
                  }
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
    <div class="page">
        <nav aria-label="Page navigation example">
          <ul class="pagination">
          <?php
            paginate($count);
          ?>
          </ul>
        </nav>
    </div>
    <iframe style="z-index:-1;position: absolute;border:none;" name="iframe_a"></iframe>

  </body>
  <footer>
  <div class="text-center">© 2019 Copyright: rdu-toi Camagru</div>
  </footer>
</html>