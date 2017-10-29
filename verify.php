<?php
	session_start();           // Start new or resume existing session
	if(!isset($_SESSION['active'])){
		header("Location: login.php");  //  back to login page
	}
	$db_conn = mysqli_connect("localhost", "root", "");
	mysqli_select_db($db_conn, "gilit_db");
?>

<?php
    //if we get the post_id
    if (isset($_POST['id'])){
        $post_id = $_POST['id'];
        //find the id of the current user and make that user the giver of the post
        $email = $_SESSION['active'];

        // OPEN AND SELECT DATABASE
        $db_conn = mysqli_connect("localhost", "root", "");
        if (!$db_conn)
          die("Unable to connect: " . mysqli_connect_error());
        mysqli_select_db($db_conn, "gilit_db");

        $cmd  = "SELECT * from users where email='".$email."'";
        $result = mysqli_query($db_conn, $cmd);

        if (mysqli_num_rows($result)==1){
            while($row = mysqli_fetch_array($result)){
                //assign com_id to the com_id of the user
                $getter_id = $row['user_id'];
                $getter_point = $row['point'];
            }
        }

        else
            echo mysqli_error($db_conn);

        //get the number of points for the help and the giver_id
        $cmd  = "SELECT * from posts where post_id=".$post_id."";
        $result = mysqli_query($db_conn, $cmd);

        if (mysqli_num_rows($result)==1){
            while($row = mysqli_fetch_array($result)){
                $post_point = $row['post_point'];
								$giver_id = $row['giver_id'];
            }
        }
        else
            echo mysqli_error($db_conn);

				$cmd  = "UPDATE posts SET status = 2 WHERE post_id ='".$post_id."'";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Task completed</p>";
        }
        else
            echo mysqli_error($db_conn);

				//the number of points for the giver
				$cmd  = "SELECT * from users where user_id='".$giver_id."'";
				$result = mysqli_query($db_conn, $cmd);

				if (mysqli_num_rows($result)==1){
						while($row = mysqli_fetch_array($result)){
								$giver_point = $row['point'];
								$giver_point_accrue = $row['point_accrue'];
						}
				}

				else
						echo mysqli_error($db_conn);

        //transfer the points from the getter to the giver
        $getter_point_new = $getter_point - $post_point;
				$giver_point_new = $giver_point + $post_point;
				$giver_point_accrue_new = $giver_point_accrue +  $post_point;

        $cmd  = "UPDATE users SET point = ".$getter_point_new." WHERE user_id =".$getter_id."";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Getter gives points succesfully</p>";
        }
        else
            echo mysqli_error($db_conn);

				$cmd  = "UPDATE users SET point = ".$giver_point_new." WHERE user_id =".$giver_id."";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Giver gets points succesfully</p>";
        }
        else
            echo mysqli_error($db_conn);

				$cmd  = "UPDATE users SET point_accrue = ".$giver_point_accrue_new." WHERE user_id =".$giver_id."";
				$result = mysqli_query($db_conn, $cmd);
				if ($result){
						echo "<p>Giver gets points succesfully 2</p>";
				}
				else
						echo mysqli_error($db_conn);

    }

?>
