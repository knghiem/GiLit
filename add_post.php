<?php
	session_start();           // Start new or resume existing session
	if(!isset($_SESSION['active'])){
		header("Location: login.php");  //  back to login page
	}
	$db_conn = mysqli_connect("localhost", "root", "");
	mysqli_select_db($db_conn, "gilit_db");
?>

<?php
    if(!empty($_POST["title"])) {
        $title = $_POST["title"];
        $des = $_POST["des"];
        $point = $_POST["point"];
				$wellness = $_POST["wellness"];
				if ($wellness)
					$wellness = 1;
				else
				 	$wellness = 0;
				$lat = $_POST["lat"];
				$lng = $_POST["lng"];

        $email = $_SESSION['active'];

        // OPEN AND SELECT DATABASE
        $db_conn = mysqli_connect("localhost", "root", "");
        if (!$db_conn)
          die("Unable to connect: " . mysqli_connect_error());
        mysqli_select_db($db_conn, "gilit_db");

        $cmd  = "SELECT * from users where email='".$email."'";
        $result=mysqli_query($db_conn, $cmd);

        if (mysqli_num_rows($result)==1){
            while($row = mysqli_fetch_array($result)){
                $user_id = $row["user_id"];
                $com_id = $row["com_id"];
								$user_point = $row['point'];
            }
						if ($user_point < $point && $wellness!=true)
							echo "Not enough points";
						else{
		            $cmd2 = "INSERT INTO posts (com_id, title, des, getter_id, post_point, post_lat, post_long, wellness) VALUES
		                (".$com_id.", '".$title."', '".$des."', ".$user_id.", ".$point.", ".$lat.", ".$lng.", ".$wellness.")
		                ;";

		            if( mysqli_query($db_conn, $cmd2) )
		                echo "Your post have been added to the board";
		            else
		                echo mysqli_error($db_conn);
									}
				}
        else
            echo mysqli_error($db_conn);

    }
    else
        echo "Title and Point are required fields";

    mysqli_close($db_conn);

?>
