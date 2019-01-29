<?php

include('config/database.php');

if(isset($_POST['send_my_password'])){
  $email = $_POST['email'];
	$query = "SELECT * FROM `user_info` WHERE `email` = '$email'";
	$result = $conn->query($query);
	if ($row = $result->fetch(PDO::FETCH_ASSOC)){

    // $password = $row['password'];

    $name = $row['username'];
    $token = $row['token'];
    if(mail($email, 'Update Password!', "Hi, $name. To change your password click on this link: http://localhost:8080/Camagru_v2/change_password.php?token=$token", 'From: rdu-toi@student.wethinkcode.co.za')){
      header("Location:index.php?success=" . urlencode("To change your password, check the link in your email!"));
      exit();
    } else {
      header("Location:forgot_password.php?err=" . urlencode("Sorry, we could not send your password at this time!"));
      exit();
    }
  } else {
      header("Location:forgot_password.php?err=" . urlencode("Sorry, no user exists with the provided email!"));
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

    <title>Forgot Password</title>

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
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <form action="forgot_password.php" method="post" style="margin-top:35px;" >
        <h2>Retrieve Password</h2>

			<?php if(isset($_GET['success'])) { ?>

            <div class="alert alert-success"><?php echo $_GET['success']; ?></div>

      <?php } ?>

      <?php if(isset($_GET['err'])) { ?>

            <div class="alert alert-danger"><?php echo $_GET['err']; ?></div>

      <?php } ?>
        <hr>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <button type="submit" name="send_my_password" class="btn btn-default">Send My Password</button>
            <a href="index.php" class="btn btn-danger">Cancel</a>
        </form>

    </div>

    <script src="js/bootstrap.js"></script>
  </body>
</html>
