<?php
session_start();

session_destroy();

header("Location:index.php?success:" . urlencode("The user has logged out successfully!"));

?>