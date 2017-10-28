<?php
	session_start();           // Start new or resume existing session
	if(!isset($_SESSION['active'])){
		header("Location: login.php");  //  back to login page
	}
	$db_conn = mysqli_connect("localhost", "root", "");
	mysqli_select_db($db_conn, "gilit_db");
?>

<?php
    //if we get the help_id
    if (isset($_POST['id'])){
        $help_id = $_POST['id'];
        //find the id of the current user and make that user the giver of the post
        $username = $_SESSION['active'];

        // OPEN AND SELECT DATABASE
        $db_conn = mysqli_connect("localhost", "root", "");
        if (!$db_conn)
          die("Unable to connect: " . mysqli_connect_error());
        mysqli_select_db($db_conn, "gilit_db");

        $cmd  = "SELECT * from users where username='".$username."'";
        $result = mysqli_query($db_conn, $cmd);
        
        if (mysqli_num_rows($result)==1){
            while($row = mysqli_fetch_array($result)){
                //assign com_id to the com_id of the user
                $user_id = $row['user_id'];
                $user_point = $row['point'];
            }
        }
        
        else
            echo "database error 1";
        
        //the number of points for the help
        $cmd  = "SELECT * from posts where help_id=".$help_id."";
        $result = mysqli_query($db_conn, $cmd);
        
        if (mysqli_num_rows($result)==1){
            while($row = mysqli_fetch_array($result)){
                //assign com_id to the com_id of the user
                $point = $row['point'];
            }
        }
        
        else 
            echo "Database error 2";
        
        //transfer the points from the getter to the giver
        $new_point = $user_point + $point;
        
        $cmd  = "UPDATE users SET point = ".$new_point." WHERE user_id =".$user_id."";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Transfer points succesfully</p>";
        }
        else 
            echo "database error 3";
        
        $cmd  = "UPDATE posts SET give_id = ".$user_id." WHERE help_id ='".$help_id."'";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Giver updated</p>";
        }
        else
            echo "database error 4";
        
        $cmd  = "UPDATE posts SET status = 2 WHERE help_id ='".$help_id."'";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Task completed</p>";
        }
        else
            echo "database error 5";

    }

?>