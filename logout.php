<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {margin: 10% auto; text-align: center}
    </style>
</head>

<body class="container">
    
<?php
    session_start();
    if(isset($_SESSION['active'])){
        $user = $_SESSION['active'];
    }
    
    if(session_destroy() && isset($_SESSION['active'])){
        echo "<h3>We're sad to see you go, <b>".$_SESSION['active']."</b>!</h3><br>";
        echo "<p>You have successfully logged out from <b>Gilit</b>. Click here to login.</p><br>";
    }
    echo "<a href='login.php' class='btn btn-default'>Login</a><br><br><a href='index.html' class='btn btn-default'>Home</a>";
        
?>
</body>
