<?php

include('config/database.php');

if (isset($_GET['token'])){
	try {
		$token = $_GET['token'];
		$query = $conn->prepare( "UPDATE `user_info` SET STATUS='1' WHERE `token`='$token'" );
		$query->execute();
		header("Location:index.php?success=Account Activated");
		}
	catch(PDOException $e)
		{
		echo "Error: " . $e->getMessage();
		}
	}
else {
	header("Location:index.php?err=There is a technical problem, we are trying to sort it out now!");
	exit();
}
?>