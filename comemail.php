<?php

session_start();
include('config/database.php');
include('config/functions.php');

if(!loggedIn()){
    header("Location:index.php?err=" .urlencode("You need to login to leave a comment!"));
    exit();
}

if (isset($_GET['confirm'])){
  $_SESSION['confirm'] = $_GET['confirm'];
}

$email = $_SESSION['user_email'];

$confirm = $_SESSION['confirm'];

if ($confirm == "yes"){
    try {
        $query = $conn->prepare("UPDATE `user_info` SET `comemail` = '1' WHERE `email` = '$email'");
        $query->execute();
        header("Location:user_preferences.php?success=" . urlencode("You will now recieve emails when others comment on your pics!"));
        exit();
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
}

else{
    try {
        $query = $conn->prepare("UPDATE `user_info` SET `comemail` = '0' WHERE `email` = '$email'");
        $query->execute();
        header("Location:user_preferences.php?success=" . urlencode("You will no longer recieve emails when others comment on your pics!"));
        exit();
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
}
?>