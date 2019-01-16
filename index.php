<?php

session_start();
include('includes/db.php');
include('includes/functions.php');

if(loggedIn()){
  header("Location:myaccount.php");
  exit();
}

if (isset($_POST['login'])){
	$email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM `user_info` WHERE `email` = '$email'";
  $result = $conn->query($query);
  $row = $result->fetch(PDO::FETCH_ASSOC);
	if ($row && password_verify($password, $row['password'])){
		if ($row['status'] == 1){
      $_SESSION['user_email'] = $email;
      if(isset($_POST['remember_me'])){
        setcookie("user_email", $email, time()+60*5);
      }
			header("Location:myaccount.php");
			exit();
		}
		else {
			header("Location:index.php?err=" . urlencode("The user account is not activated!"));
			exit();
		}
	}
	else {
		header("Location:index.php?err=" . urlencode("Incorrect Email or Password!"));
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

    <title>Login</title>

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
            <li class="active"><a href="index.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <form action="index.php" method="post" style="margin-top:35px;" >
        <h2>Login Here</h2>

			<?php if(isset($_GET['success'])) { ?>

			<div class="alert alert-success"><?php echo $_GET['success']; ?></div>

			<?php } ?>

			<?php if(isset($_GET['err'])) { ?>

			<div class="alert alert-danger"><?php echo $_GET['err']; ?></div>

			<?php } ?>

        <hr>
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" name="email" class="form-control" placeholder="Email">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
    </div>
    <div class="checkbox">
        <label>
        <input type="checkbox" name="remember_me" > Remember Me
        </label>
    </div>
    <button type="submit" name="login" class="btn btn-default">Login</button>
    <a href="forgot_password.php">Forgot Password?</a>
    </form>

    </div>
  </body>
</html>
