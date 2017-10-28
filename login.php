<?php
    session_start();
    $message = "";
    if( !empty($_POST["username"]) ){
        $db_conn = mysqli_connect("localhost", "root", "");
        mysqli_select_db($db_conn, "gilit_db");
      
        $user = mysqli_real_escape_string($db_conn, $_POST['username']);
        $pass = mysqli_real_escape_string($db_conn, $_POST['password']);

        $cmd = "SELECT user_id FROM users WHERE username='$user' AND password='$pass'";
        $result = mysqli_query($db_conn, $cmd);
        $row = mysqli_fetch_array($result);
        if($row != null && mysqli_num_rows($result)==1){
            $_SESSION['active'] = $user;
            header("location: community_board.php");
        }
        else
            $message = "Username or Password is invalid";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
		span {margin: 0px 15px 0px 0px}
        h2 {margin: 30px}
	</style>
</head>

<body style="text-align:center; margin:75px;">
    <div>
        <h2>We've been missing you!</h2>
        <form action="login.php" method="post">
            <span class="glyphicon glyphicon-user"></span><input type="text" id="username" name="username" placeholder="Username" /><br><br>
            <span class="glyphicon glyphicon-lock"></span><input type="password" id="password" name="password" placeholder="Password" />
            <br><br><button type="submit" class="btn btn-primary">Login</button>
        </form>
        <?php echo "<br>" . $message; ?>
        <br><br>
        <a href="adduser.php">Register if you do not already have an account</a>
    </div>
</body>
</html>
