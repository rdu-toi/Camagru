<?php

session_start();

include('config/database.php');
include('config/functions.php');

if(loggedIn()){
  header("Location:myaccount.php");
  exit();
}

function isUnique($email){
	global $conn;
	$query = $conn->prepare( "SELECT * FROM `user_info` WHERE `email` = '$email'" );
	$query->bindValue( 1, $email );
	$query->execute();

	if( $query->rowCount() > 0 ) {
		$query = null;
		return false;
	}
	else {
		$query = null;
		return true;
	}
}

if (isset($_POST['register'])){
	$_SESSION['name'] = $_POST['name'];
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['password'] = $_POST['password'];
	$_SESSION['confirm_password'] = $_POST['confirm_password'];

	if (strlen($_POST['name']) < 3){
		header("Location:register.php?err=" . urlencode("The name must be between 3 and 15 characters long!"));
		exit();
	}
	else if (strlen($_POST['name']) > 15){
		header("Location:register.php?err=" . urlencode("The name must be between 3 and 15 characters long!"));
		exit();
	}
	else if ($_POST['password'] != $_POST['confirm_password']){
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

  else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    header("Location:register.php?err=" . urlencode("Please enter a valid email!"));
    exit();
  }
	else if (!isUnique($_POST['email'])){
		header("Location:register.php?err=" . urlencode("The email is already in use. Please use another or sign in using this email!"));
		exit();
	}
	else {
		try {
			$name = $_POST['name'];
      $password = $_POST['password'];
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$email = $_POST['email'];
			$token = bin2hex(openssl_random_pseudo_bytes(32));

			$stmt = $conn->prepare("INSERT INTO `user_info` (`username`, `password`, `email`, `token`) VALUES ('$name', '$hashed_password', '$email', '$token')");
			$stmt->execute();
      $stmt = null;
      $message = "Hi $name! Your account has been created, here is the activation link http://localhost:8080/Camagru/activate.php?token=$token";
      mail($email, 'Activate Account', $message, 'From: rdu-toi@student.wethinkcode.co.za');
      header("Location:index.php?success=" . urlencode("Activation email sent"));
      exit();
			}
		catch(PDOException $e)
			{
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

    <title>Register</title>

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
            <li><a href="index.php">Login</a></li>
            <li class="active"><a href="register.php">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <form action="register.php" method="post" style="margin-top:35px;" >
            <h2>Register Here</h2>

			<?php if(isset($_GET['err'])) { ?>

			<div class="alert alert-danger"><?php echo $_GET['err']; ?></div>

			<?php } ?>

            <hr>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo @$_SESSION['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo @$_SESSION['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo @$_SESSION['password']; ?>" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo @$_SESSION['confirm_password']; ?>" required>
            </div>
            <button type="submit" name="register" class="btn btn-default">Register</button>
        </form>

    </div>
  </body>
  <footer>
  <div class="text-center">© 2019 Copyright: rdu-toi Camagru</div>
  </footer>
</html>