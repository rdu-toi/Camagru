<?php

include 'db.php';

//Create database "Camagru"

try {
    $conn = new PDO($DB_DSN_SHORT, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE $DB_NAME";
    $conn->exec($sql);
    echo "Database created successfully<br>";
}
catch(PDOException $e){
    echo "An error occured creating the database 'Camagru' " . $e->getMessage() . "\n";
}

$conn = null;

//Create table "User_info"

try {
    $conn = new PDO("$DB_DSN_SHORT;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE `User_info` (
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(30) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `email` VARCHAR(50),
        `token` VARCHAR(255),
        `status` INT NOT NULL DEFAULT '0'
    )";

    $conn->exec($sql);
    echo "Table User_info created successfully\n";
    }
catch(PDOException $e)
    {
    echo "An error occured creating the table 'User_info' " . $e->getMessage() . "\n";
    }

$conn = null;

//Create table "Gallery"

try {
    $conn = new PDO("$DB_DSN_SHORT;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE `Gallery` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `userid` INT(100) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        FOREIGN KEY (userid) REFERENCES User_info(id)
    )";

    $conn->exec($sql);
    echo "Table Gallery created successfully\n";
    }
catch(PDOException $e)
    {
    echo "An error occured creating the table 'Gallery' " . $e->getMessage() . "\n";
    }

$conn = null;

// //Create table "Comments"

// try {
//     $conn = new PDO("$DB_DSN_SHORT;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     $sql = "CREATE TABLE Comments (
//     )";

//     $conn->exec($sql);
//     echo "Table Comments created successfully";
//     }
// catch(PDOException $e)
//     {
//     echo $sql . "<br>" . $e->getMessage();
//     }

// $conn = null;

// //Create table "Likes"

// try {
//     $conn = new PDO("$DB_DSN_SHORT;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     $sql = "CREATE TABLE Likes (
//     )";

//     $conn->exec($sql);
//     echo "Table Likes created successfully";
//     }
// catch(PDOException $e)
//     {
//     echo $sql . "<br>" . $e->getMessage();
//     }

// $conn = null;
?>