<?php
	session_start();           // Start new or resume existing session
	if(!isset($_SESSION['active'])){
		header("Location: login.php");  //  back to login page
	}
	$db_conn = mysqli_connect("localhost", "root", "");
	mysqli_select_db($db_conn, "gilit_db");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Community</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {margin: 20% auto; text-align: center}
    </style>
</head>

<body class="container">

<?php
    if( !empty($_GET["com_id"])){
        $com_id = $_GET["com_id"];
        $username = $_SESSION['active'];

        // OPEN AND SELECT DATABASE
        $db_conn = mysqli_connect("localhost", "root", "");
        if (!$db_conn)
          die("Unable to connect: " . mysqli_connect_error());
        mysqli_select_db($db_conn, "gilit_db");

        $cmd  = "UPDATE users SET com_id =".$com_id." WHERE username ='".$username."'";
        $cmd2  = "SELECT com_name from coms where com_id=".$com_id."";

        $result = mysqli_query($db_conn, $cmd);
        $com_name = mysqli_query($db_conn, $cmd2);

        if ($result && $com_name){
            echo "<h4>Congratulations, <b>".$username."</b>. You are now a member of the ";
            while($row = mysqli_fetch_array($com_name)){
                echo "<b>".$row['com_name']."</b> community!</h4>";
                echo "<h4>Click here to go to <b>".$row['com_name']."</b>'s bulletin board.</h4>";
                echo "<br><br><a href = 'community_board.php' class='btn btn-primary'>".$row['com_name']." Board</a>";
            }
        }
        else{
            echo "<p>Database error. Contact: knghiem@conncoll.edu</p>".PHP_EOL;
            echo "<p>Go back to <a href ='home.html'>home page</a></p>";
        }
    }

    mysqli_close($db_conn);

?>
</body>
</html>
