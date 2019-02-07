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

try {
    $query = $conn->prepare("UPDATE `gallery` SET `likes` = `likes` + 1 WHERE `id` = '$id'");
    $query->execute();
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
?>