<?php

$DB_DSN = "mysql:host=localhost;dbname=". $DB_NAME . ";charset=utf8";
$DB_DSN_SHORT = "mysql:host=localhost;charset=utf8";
$DB_NAME = "Camagru";
$DB_USER = "root";
$DB_PASSWORD = "administrator";

try {
    $conn = new PDO("$DB_DSN_SHORT;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "An error occured connecting to the database!" . $e->getMessage() . "\n";
    }

?>