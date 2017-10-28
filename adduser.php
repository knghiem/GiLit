<!DOCTYPE html>
<!-- Demonstrates how to add a user to an existing database 
Take input from users: 
    2. Display Name
    3. Email address
    4. username unique 
    5. password

AngularJS code from https://goo.gl/JPxqkc
-->
<html ng-app="validation">
<head>
	<title>Register</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style>
        span {margin: 0px 0px 0px 15px; font-size: smaller; color: #008CBA}
        input {margin: 0px 0px 0px 30px}
        .messages { font-size: smaller; color: red;}
	</style>
    <script src="https://code.angularjs.org/1.3.0-rc.2/angular.js"></script>
    <script src="https://code.angularjs.org/1.3.0-rc.2/angular-messages.js"></script>
    <script src="password.js"></script>
</head>

<body style="text-align:center; margin:75px;" ng-controller="RegistrationController as registration">
    <h2>Start spreading kindness today!</h2>
    <h3>Sign up for a <b>GiLit</b> account</h3>
	<div>
		<?php
			$message = "";
            $error = array();
        
			if( !empty($_POST["username"]) ){
				$db_conn = mysqli_connect("localhost", "root", "");
				if (!$db_conn)
					die("Unable to connect: " . mysqli_connect_error());  // die is similar to exit

				if( !mysqli_select_db($db_conn, "gilit_db") )
					die("Database doesn't exist: " . mysqli_error($db_conn));

				mysqli_select_db($db_conn, "gilit_db");
                
                //check if the email address is valid, taken from https://goo.gl/CuQV4B
                if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) {
                        $email = $_POST['email'];
                } else {
                        $error[] = 'Invalid email';
                        echo 'Invalid email address';
                }
                
                //checking if the username already exists
                $cmd = 'SELECT * FROM users WHERE username = "'.$_POST["username"].'"';                
                
                $result = mysqli_query($db_conn,$cmd);
                $num_row = mysqli_num_rows($result);
                
                if ($num_row > 0){
                    $error[] = 'Username already exists';
                    echo 'The username <b>'.$_POST["username"].'</b> already exists';
                }
                
                $cmd = 'SELECT * FROM users WHERE email = "'.$_POST["email"].'"';                
                
                $result = mysqli_query($db_conn,$cmd);
                $num_row = mysqli_num_rows($result);
                
                if ($num_row > 0){
                    $error[] = 'This email is already in use';
                    echo 'The email <b>'.$_POST["email"].'</b> is already in use';
                }
                
                
                if (!$error){
                    $name = mysqli_real_escape_string($db_conn, $_POST['name']);
                    $user = mysqli_real_escape_string($db_conn, $_POST['username']);
                    $pass = mysqli_real_escape_string($db_conn, $_POST['password']);

				    $cmd = "INSERT INTO users (name, email, username, password) VALUES ('"
				                . $name . "','" . $email . "','" . $user . "','" . $pass . "');";

				    if( mysqli_query($db_conn, $cmd) ){
                        $message = "<br>Congratulations, <b>" .$name. "</b>. Your GiLit account is ready!<br>Click <a href='login.php'>here</a> and log in with your new username <b>".$user."</b><br>";
                    } 
				    else
					   $message = "Problem creating your account: ". mysqli_error($db_conn) . "<br>";
                }
				mysqli_close($db_conn);
			}
		?>
	</div>
    <?php echo "". $message . "<br>" ?>
    
	<form action="adduser.php" method="post" name="registrationForm" novalidate 
          ng-submit="registration.submit(registrationForm.$valid)">
        <span class="glyphicon glyphicon-star"></span>
        <input type="text" id="name" name="name" placeholder="Display Name" required /><span>*Required</span><br><br>

		<span class="glyphicon glyphicon-user"></span><input type="text" id="username" name="username" placeholder="Username" required /><span>*Required</span><br><br>
        
        <span class="glyphicon glyphicon-envelope"></span><input type="text" id="email" name="email" placeholder="Email Address" required /><span>*Required</span><br><br>

		<span class="glyphicon glyphicon-lock"></span><input type="password" id="password" name="password" placeholder="Password" ng-model="registration.user.password" required /><span>   *Required</span><br><br>

        <span class="glyphicon glyphicon-lock"></span><input type="password" name="confirmPassword" placeholder="Confirm Password"
                   ng-model="registration.user.confirmPassword" 
                   required compare-to="registration.user.password" /><span>	&#09; *Required</span>
        <div ng-messages="registrationForm.confirmPassword.$error" ng-messages-include="messages.html"></div>
        <br>
		<button type="submit" class="button">Register</button>
	</form>
    <br>
    <p>Already have an account? <a href='login.php'>Login</a></p>
</body>
</html>
