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
                $user_id = $row['user_id'];
                $user_point = $row['point'];
            }
        }

        else
            echo mysqli_error($db_conn);

        $cmd  = "UPDATE posts SET giver_id = ".$user_id." WHERE post_id ='".$post_id."'";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Giver updated</p>";
        }
        else
            echo mysqli_error($db_conn);

        $cmd  = "UPDATE posts SET status = 1 WHERE post_id ='".$post_id."'";
        $result = mysqli_query($db_conn, $cmd);
        if ($result){
            echo "<p>Task committed</p>";
        }
        else
            echo mysqli_error($db_conn);

    }

?>
