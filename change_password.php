<?php

session_start();

include('config/database.php');
include('config/functions.php');

if(loggedIn()){
  header("Location:myaccount.php");
  exit();
}

if (isset($_GET['token'])){
	try {
        $token = $_GET['token'];
        $stmt = $conn->prepare("SELECT * FROM `user_info` WHERE `token` = $token");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row){
            echo "My name is Jeff!";
            // header("Location:index.php?err=" .urlencode("Something went wrong!"));
            // exit();
        }
	}
	catch(PDOException $e){
		echo "Error: " . $e->getMessage();
	}
}
else {
	header("Location:index.php?err=" .urlencode("There is a technical problem, we are trying to sort it out now!"));
	exit();
}

if (isset($_POST['register'])){
	$_SESSION['password'] = $_POST['password'];
	$_SESSION['confirm_password'] = $_POST['confirm_password'];

	if ($_POST['password'] != $_POST['confirm_password']){
		header("Location:register.php?err=" . urlencode("The passwords do not match!"));
    exit();
  }
	
	else if ( strlen( $_POST['password'] ) < 8 ) {
		header("Location:register.php?err=" . urlencode("The password needs to be atleast 8 characters long!"));
		exit();
	}

	else if ( $_POST['password'] == $_POST['name'] ) {
		header("Location:register.php?err=" . urlencode("The password cannot match your username!"));
		exit();
	}

	else if ( strpos( $_POST['password'], $_POST['name'] ) !== false ) {
		header("Location:register.php?err=" . urlencode("The password cannot match your username!"));
		exit();
	}

	else if ( ! preg_match( '/[a-z]/', $_POST['password'] ) ) {
		header("Location:register.php?err=" . urlencode("The passwords needs atleast one lowercase letter!"));
		exit();
	}

	else if ( ! preg_match( '/[A-Z]/', $_POST['password'] ) ) {
		header("Location:register.php?err=" . urlencode("The passwords needs atleast one uppercase letter!"));
		exit();
	}

	else if ( ! preg_match( '/[0-9]/', $_POST['password'] ) ) {
		header("Location:register.php?err=" . urlencode("The passwords needs atleast one number!"));
		exit();
	}

	else if ( ! preg_match( '/[\W]/', $_POST['password'] ) ) {
		header("Location:register.php?err=" . urlencode("The passwords needs atleast one special character!"));
		exit();
	}

	else {
		try {
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE `user_info` SET `password` = '$hashed_password' where `token` = '$token'");
        $stmt->execute();
        $stmt = null;
        header("Location:index.php?success=" . urlencode("Your password has been successfully updated!"));
        exit();
		}
		catch(PDOException $e){
			echo "Error: " . $e->getMessage();
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Change Password</title>

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
            <li class="active"><a href="register.php">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <form action="change_password.php" method="post" style="margin-top:35px;" >
            <h2>Change Password</h2>

			<?php if(isset($_GET['err'])) { ?>

			<div class="alert alert-danger"><?php echo $_GET['err']; ?></div>

			<?php } ?>

            <hr>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" placeholder="New Password" value="<?php echo @$_SESSION['password']; ?>" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm New Password" value="<?php echo @$_SESSION['confirm_password']; ?>" required>
            </div>
            <button type="submit" name="confirm" class="btn btn-default">Confirm</button>
        </form>

    </div>
  </body>
</html>