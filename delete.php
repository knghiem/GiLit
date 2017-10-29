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

        //get the number of points for the help and the giver_id
        $cmd  = "DELETE FROM posts where post_id=".$post_id."";
        $result = mysqli_query($db_conn, $cmd);

        if (mysqli_num_rows($result)==1){
            echo "Deleted";
        }
        else
            echo mysqli_error($db_conn);
    }

?>
