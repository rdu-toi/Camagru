<!DOCTYPE html>
<html>
<head>
    <title>Mail Test</title>
</head>
<body>
    <?php
    
    $to = "rdu-toi@student.wethinkcode.co.za";
    $subject = "Test mail";
    $message = "Hello! This is a test email.";
    $from = "someonelse@example.com";
    $headers = "From:" . $from;

    if(mail($to, $subject, $message, $headers)){
        echo "Mail sent successfully!";
    }
    else {
        echo "Mail not sent!";
    }
    
    ?>
</body>
</html>