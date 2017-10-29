<!-- This page will load all the posts from the community users and populate it in a table      -->
<?php
	session_start();           // Start new or resume existing session
	if(!isset($_SESSION['active'])){
		header("Location: login.php");  //  back to login page
	}
	$db_conn = mysqli_connect("localhost", "root", "");
	mysqli_select_db($db_conn, "gilit_db");
?>

<!-- HTML Code goes here -->
<!DOCTYPE html>
<html>
<head>
    <title>Community Board</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
		span {margin: 0px 15px 0px 0px}
        .name {font-size: small; color: gray}
        .post {border: 1px solid RoyalBlue; margin: 20px 0px 20px 0px; padding: 20px}
        body {margin: 30px}
        .right {display: inline-block; float: right; margin-right: 20px;}
        #addPost {border: 1px solid green; padding: 20px}
	</style>
</head>

<body class="container">
    <a href="logout.php" class="btn btn-danger right" role="button">Logout</a>
    <a href="pick_community.php" class="btn btn-success right" role="button">Change Community</a>
    <a href="index.html" class="btn btn-primary right" role="button">Home</a>
    <?php
        $username = $_SESSION['active'];

        // OPEN AND SELECT DATABASE
        $db_conn = mysqli_connect("localhost", "root", "");
        if (!$db_conn)
          die("Unable to connect: " . mysqli_connect_error());
        mysqli_select_db($db_conn, "gilit_db");

        //look for the com_id of the user
        $cmd  = "SELECT * from users where username='".$username."'";
        $result = mysqli_query($db_conn, $cmd);

        //check that the id exists
        if (mysqli_num_rows($result)==1){
            while($row = mysqli_fetch_array($result)){
                //assign com_id to the com_id of the user
                $com_id = $row['com_id'];
                $user_point = $row['point'];

                if ($com_id!=NULL){
                    $cmd1  = "SELECT com_name from coms where com_id=".$com_id."";
                        $result1 = mysqli_query($db_conn, $cmd1);

                        if (mysqli_num_rows($result1)==1){
                            while($row1 = mysqli_fetch_array($result1)){
                                echo "<h4>Welcome to the <b>".$row1['com_name']."</b> community board, <b>".$row['name']."</b>!</h4>";
                                echo "<p>Your currently have <b>".$user_point."</b> points</p>";
                            }
                        }

                    if ($user_point >0)
                        echo '<button id="add" type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Add new posts</button>';

                    //look for all the posts in the community board
                    $cmd  = "SELECT * from posts where com_id=".$com_id."";

                    $result = mysqli_query($db_conn, $cmd);
                    $posts = [];

                    if (mysqli_num_rows($result)>0){
                        while($row = mysqli_fetch_array($result)){
                        //assign com_id to the com_id of the user
                            //echo the title
                            echo "<div class='post'><p><b>".$row['title']."</b></p>";
                            //echo the collapse button to show full description
                            echo '<button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#des'.$row['help_id'].'">
                                <span class="glyphicon glyphicon-option-horizontal"></span> See full description</button><br>';
                            //echo the full description
                            echo '<div id="des'.$row['help_id'].'" class="collapse"><br>'.$row['des'].'</div><br>';

                            //get from the database and echo the getter's user name
                            $cmd2  = "SELECT * from users where user_id=".$row['get_id']."";
                            $result2 = mysqli_query($db_conn, $cmd2);

                            if (mysqli_num_rows($result2)==1){
                                while($row2 = mysqli_fetch_array($result2)){
                                    $getter_username = $row2["username"];
                                }
                                echo '<p>Posted by <b>'.$getter_username.'</b>&nbsp;&nbsp;&nbsp;';
                            }
                            else
                                echo '<p>Posted by undefined';

                            echo '<span>Points: '.$row['point'].'</span></p>';

                            //if the current user is not the getter of this help post, then allow them to commit
                            if ($username!=$getter_username && $row['status']!=2){
                                echo "<input type ='submit' value = 'Commit' id = '".$row['help_id']."' class='btn btn-success btn-xs commit'>";
                            }
                            if ($username!=$getter_username && $row['status']==2){
                                echo "<button class='btn btn-xs' disabled>Completed</button>";

                                $cmd3  = "SELECT * from users where user_id='".$row['give_id']."'";
                                $result3 = mysqli_query($db_conn, $cmd3);

                                //check that the id exists
                                if (mysqli_num_rows($result3)==1){
                                    while($row = mysqli_fetch_array($result3)){
                                        //assign com_id to the com_id of the user
                                        echo "<span class='name'> by ".$row['name']."</span>";
                                    }
                                }
                            }
                            echo "</div>";
                        }
                    }
                }
                //if the user has not enrolled in a community, go to the pick community page
                else
                    header("Location: pick_community.php");
            }
        }
        else
            echo 'Database error.';

        mysqli_close($db_conn);
    ?>

    <div id="addPost">
        <h4>Add a new post to the community board</h4>
        <form>
						<div class="form-group">
								<input type="radio" name="wellness" id="wellness"><span style="padding-left: 10px">Well-ness Issue</span><br>
						</div>
            <div class="form-group">
                <br>
                *Required Title
                <input class="form-control" type="text" name="title" placeholder="A short title" required>
            </div>
            <div class="form-group"> Description
                <input class="form-control" type="text" name="des" placeholder="Description" required>
            </div>
            *Required Points
            <div class="form-group">
                <input class="form-control" type="number" name="point" placeholder="Points" required>
            </div>
            <button id='newPost' class='btn btn-default'>Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function(){
            var show = false;
            $('#addPost').hide();

            //jQuery code taken from here: https://goo.gl/jPFgub

            $('.commit').click(function(){
                var help_id = parseInt($(this).attr('id'));
                var button = $(this);
                var url = "commit.php";
                var data = {'id': help_id};
                $.post(url, data, function(response){
                    console.log(response);
                    button.hide();
                });
            });

            $('#add').click(function(){
                if (show){
                    $('#addPost').hide();
                    show = false;
                }
                else{
                    $('#addPost').show();
                    show = true;
                    window.location = "#addPost"
                }
            });

            $("#newPost").click(function(){
                var $form = $("#postForm");
                var url = "add_post.php";
                var data = { 'title': $('input[name="title"').val(),
                           'des': $('input[name="des"]').val(),
                           'point': $('input[name="point"]').val()};

                $.post(url, data, function(response){
                    alert(response);
                    location.reload();
                });
            });
        });
    </script>


</body>
</html>
