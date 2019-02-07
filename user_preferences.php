<?php

session_start();

include('config/database.php');
include('config/functions.php');

if(!loggedIn()){
  header("Location:index.php?err=" .urlencode("You are not logged in!"));
  exit();
}

$user_email = $_SESSION['user_email'];

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

// function comemail(){
// 	try {
// 			$query = "SELECT * FROM `user_info` WHERE `email` = '$user_email'";
// 			$result = $conn->query($query);
// 			$row = $result->fetch(PDO::FETCH_ASSOC);
// 			if ($row['comemail'] === 0){
// 				$query = $conn->prepare( "UPDATE `user_info` SET `comemail`='1' WHERE `email`='$email'" );
// 				$query->execute();
// 				echo '<button type="submit" name="enable" class="btn btn-default">Enable email notification for comments</button>';
// 				header("Location:index.php?success=" . urlencode("You will not recieve emails for comments!"));
// 				exit();
// 			}
// 			else{
// 				$query = $conn->prepare( "UPDATE `user_info` SET `comemail`='0' WHERE `email`='$email'" );
// 				$query->execute();
// 				echo '<button type="submit" name="disable" class="btn btn-default">Disable email notification for comments</button>';
// 				header("Location:index.php?success=" . urlencode("You will now recieve emails for comments!"));
// 				exit();
// 			}
// 	}
// 	catch(PDOException $e){
// 			echo "Error: " . $e->getMessage();
// 			}
// }

if (isset($_POST['submit'])){
	if (strlen($_POST['name']) < 3){
		header("Location:user_preferences.php?err=" . urlencode("The name must be at least 3 characters long!"));
		exit();
	}
	else if ($_POST['password'] != $_POST['confirm_password']){
		header("Location:user_preferences.php?err=" . urlencode("The passwords do not match!"));
    exit();
  }
	
	else if ( strlen( $_POST['password'] ) < 8 ) {
		header("Location:user_preferences.php?err=" . urlencode("The password needs to be atleast 8 characters long!"));
		exit();
	}

	else if ( $_POST['password'] == $_POST['name'] ) {
		header("Location:user_preferences.php?err=" . urlencode("The password cannot match your username!"));
		exit();
	}

	else if ( strpos( $_POST['password'], $_POST['name'] ) !== false ) {
		header("Location:user_preferences.php?err=" . urlencode("The password cannot match your username!"));
		exit();
	}

	else if ( ! preg_match( '/[a-z]/', $_POST['password'] ) ) {
		header("Location:user_preferences.php?err=" . urlencode("The passwords needs atleast one lowercase letter!"));
		exit();
	}

	else if ( ! preg_match( '/[A-Z]/', $_POST['password'] ) ) {
		header("Location:user_preferences.php?err=" . urlencode("The passwords needs atleast one uppercase letter!"));
		exit();
	}

	else if ( ! preg_match( '/[0-9]/', $_POST['password'] ) ) {
		header("Location:user_preferences.php?err=" . urlencode("The passwords needs atleast one number!"));
		exit();
	}

	else if ( ! preg_match( '/[\W]/', $_POST['password'] ) ) {
		header("Location:user_preferences.php?err=" . urlencode("The passwords needs atleast one special character!"));
		exit();
	}

  else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    header("Location:user_preferences.php?err=" . urlencode("Please enter a valid email!"));
    exit();
	}

	else {
		try {
			$name = $_POST['name'];
      $password = $_POST['password'];
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$email = $_POST['email'];

			$query = "SELECT * FROM `user_info` WHERE `email` = '$user_email'";
			$result = $conn->query($query);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$token = $row['token'];
			$username = $row['username'];

			$stmt = $conn->prepare( "UPDATE `user_info` SET STATUS='0' WHERE `token`='$token'" );
			$stmt->execute();
			$stmt = null;

			$stmt = $conn->prepare( "UPDATE `gallery` SET `username` = '$name' WHERE `username`='$username'" );
			$stmt->execute();
			$stmt = null;

			$stmt = $conn->prepare( "UPDATE `comments` SET `username` = '$name' WHERE `username`='$username'" );
			$stmt->execute();
			$stmt = null;

			$stmt = $conn->prepare("UPDATE `user_info` SET `username` = '$name', `password` = '$hashed_password', `email` = '$email' WHERE `email` = '$user_email'");
			$stmt->execute();
      $stmt = null;
      $message = "Hi $name! You have successfully changed your details, here is the activation link http://localhost:8080/Camagru_v2/activate.php?token=$token";
			mail($email, 'Activate Account', $message, 'From: rdu-toi@student.wethinkcode.co.za');

			session_destroy();
			
			setcookie("user_email", "", time()-60*5);

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

    <title>Change Details</title>

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
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="myaccount.php">My Account</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <form action="user_preferences.php" method="post" style="margin-top:35px;" >
            <h2>Change Details</h2>

			<?php if(isset($_GET['success'])) { ?>

			<div class="alert alert-success"><?php echo $_GET['success']; ?></div>

			<?php } ?>

			<?php if(isset($_GET['err'])) { ?>

			<div class="alert alert-danger"><?php echo $_GET['err']; ?></div>

			<?php } ?>

            <hr>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" placeholder="Name" value="" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="Email" value="" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" value="" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" value="" required>
            </div>
            <button type="submit" name="submit" class="btn btn-default">Save</button>
						<br>
						<?php
						try{
							$query = "SELECT * FROM `user_info` WHERE `email` = '$user_email'";
							$result = $conn->query($query);
							$row = $result->fetch(PDO::FETCH_ASSOC);
							if ($row['comemail'] == 0){
								echo
								'<a style="margin-top:15px;" class="btn btn-primary a-btn-slide-text" href="comemail.php?confirm=yes">
									<span><strong>Enable email for comments on your pics</strong></span>
								</a>';
							}
							else{
								echo
								'<a style="margin-top:15px;" class="btn btn-primary a-btn-slide-text" href="comemail.php?confirm=no">
									<span><strong>Disable email for comments on your pics</strong></span>
								</a>';
							}
						}
						catch(PDOException $e)
							{
							echo "Error: " . $e->getMessage();
							}
						?>
        </form>

    </div>
  </body>
</html>