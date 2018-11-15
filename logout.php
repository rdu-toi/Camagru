<?php

session_start();

session_destroy();

setcookie("user_email", "", time()-60*5);

header("Location:index.php?success=" . urlencode("The user has logged out successfully!"));
exit();

?>