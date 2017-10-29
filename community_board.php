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

		<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

    <style>
		span {margin: 0px 15px 0px 0px}
        .name {font-size: small; color: gray}
        .post {border: 1px solid blue; margin: 20px 0px 20px 0px; padding: 20px}
        body {margin: 30px}
        .right {display: inline-block; float: right; margin-right: 20px;}
				.ui-loader {
				  display:none !important;
				}
				.ui-icon-loading {
				    background:none !important;
				}
	</style>
</head>
<a href="logout.php" class="btn btn-danger right" role="button">Logout</a>
<a href="index.php" class="btn btn-primary right" role="button">Home</a>

<body class="container">
		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Submit a Post</h4>
					</div>
					<form id="postForm" style="margin:20px 20px">
							<div class="form-group">
									<input type="radio" name="wellness" id="wellness"><span style="margin-left: 20px">Well-ness Issue</span><br>
							</div>
							<div class="form-group">
									<input type="radio" name="location" id="location"><span style="margin-left: 20px">Send My Location</span><br>
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
			</div>
		</div>

    <?php
        $email = $_SESSION['active'];

        // OPEN AND SELECT DATABASE
        $db_conn = mysqli_connect("localhost", "root", "");
        if (!$db_conn)
          die("Unable to connect: " . mysqli_connect_error());
        mysqli_select_db($db_conn, "gilit_db");

        //look for the com_id of the user
        $cmd  = "SELECT * from users where email='".$email."'";
        $result = mysqli_query($db_conn, $cmd);

        //check that the id exists
        if (mysqli_num_rows($result)==1){
            while($row = mysqli_fetch_array($result)){
                //assign com_id to the com_id of the user
                $com_id = $row['com_id'];
                $user_point = $row['point'];
								$user_point_accrue = $row['point_accrue'];
								$user_id = $row['user_id'];
								$name = $row['name'];
								$phone = $row['phone'];
								$email = $row['email'];
								$role = $row['role'];

                if ($com_id!=NULL){
                    $cmd1  = "SELECT com_name from coms where com_id=".$com_id."";
                        $result1 = mysqli_query($db_conn, $cmd1);

                        if (mysqli_num_rows($result1)==1){
                            while($row1 = mysqli_fetch_array($result1)){
																$com_name = $row1['com_name'];
                                echo "<h4>Welcome to the <b>".$com_name."</b> community board, <b>".$name."</b>!</h4>";
                                echo "<p>Your currently have <b>".$user_point."</b> points</p>";
                            }
                        }

										echo '<button type="button" class="btn btn-default-lg" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus"></span>Add New Post</button>';
										echo '<button type="button" class="btn btn-default-lg" data-toggle="modal" data-target="#account" style ="margin-left: 20px">Your Account</button>';


										?>

										<div class="modal fade" id="account" role="dialog">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Your Account</h4>
													</div>
													<div style="margin: 30px">
										<?php

										echo "<p><b>Community</b>: ".$com_name."</p>";
										echo "<p><b>Display name</b>: ".$name."</p>";
										echo "<p><b>Current point</b>: ".$user_point."</p>";
										echo "<p><b>Accumulate point</b>: ".$user_point_accrue."</p>";
										echo "<p><b>Phone number</b>: ".$phone."</p>";
										echo "<p><b>Email</b>: ".$email."</p>";
										echo "<p><b>Role</b>: ".$role."</p>";
										echo "<p><b>Your status</b>: ";
										if ($user_point_accrue < 2000){
											$badge = "Supporter";
											$badge_img = "media/supporter.jpg";
										}
										else if ($user_point_accrue < 10000){
											$badge = "Hero";
											$badge_img = "media/hero.jpg";
										}
										else if ($user_point_accrue < 10000){
											$badge = "Superhero";
											$badge_img = "media/superhero.jpg";
										}
										echo $badge.'<img src="'.$badge_img.'" alt="'.$badge.'" style="margin-left:10px" width=30 height=30>';
										echo "<p><b>Contribution History</b></p>";

										$cmd  = "SELECT * from posts where giver_id='".$user_id."'";
						        $result = mysqli_query($db_conn, $cmd);

										if (mysqli_num_rows($result1)>0){
												while($row = mysqli_fetch_array($result)){
														echo "<p style='margin-left:10px'>You helped user with id".$row['getter_id']." with ".$row['title']."</p>";
												}
										}

										echo "</div></div></div></div>";
                    //look for all the posts in the community board
                    $cmd  = "SELECT * from posts where com_id=".$com_id."";


                    $result = mysqli_query($db_conn, $cmd);
                    $posts = [];

                    if (mysqli_num_rows($result)>0){
                        while($row = mysqli_fetch_array($result)){
                        //assign com_id to the com_id of the user
                            //echo the title
                            echo "<div class='post'><p><b>".$row['title']."</b></p>";
                            //echo the full description
                            echo '<div id="des'.$row['post_id'].'"><br>'.$row['des'].'</div><br>';

                            //get from the database and echo the getter's user name
                            $cmd2  = "SELECT * from users where user_id=".$row['getter_id']."";
                            $result2 = mysqli_query($db_conn, $cmd2);

                            if (mysqli_num_rows($result2)==1){
                                while($row2 = mysqli_fetch_array($result2)){
                                    $getter_name = $row2["name"];
																		$getter_email = $row2["email"];
                                }
                                echo '<p>Posted by <b>'.$getter_name.'</b>&nbsp;&nbsp;&nbsp;';
                            }
                            else
                                echo '<p>Posted by undefined';

                            echo '<span>Points: '.$row['post_point'].'</span></p>';

                            //if the current user is not the getter of this help post, then allow them to commit
                            if ($email!=$getter_email && $row['status']==0){
                                echo "<button id = '".$row['post_id']."' class='btn btn-success btn-xs commit'>Commit</button>";
                            }
														if ($email==$getter_email && $row['status']==0){
                                echo "<button id = '".$row['post_id']."' class='btn btn-danger btn-xs delete'>Delete</button>";
                            }
														else if ($email!=$getter_email && $row['status']==1){
                                echo "<button class='btn btn-xs' disabled>Pending</button>";
                            }
														else if ($email==$getter_email && $row['status']==1){
                                echo "<input type ='submit' value = 'Verify' id = '".$row['post_id']."' class='btn btn-info btn-xs verify'>";
                            }
                            else if ($row['status']==2){
                                echo "<button class='btn btn-xs' disabled>Completed</button>";

                                $cmd3  = "SELECT * from users where user_id='".$row['giver_id']."'";
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

    <script>
        $(document).ready(function(){
            //jQuery code taken from here: https://goo.gl/jPFgub

            $('.commit').click(function(){
                var post_id = parseInt($(this).attr('id'));
                var button = $(this);
                var url = "commit.php";
                var data = {'id': post_id};
                $.post(url, data, function(response){
                    console.log(response);
                    location.reload();
                });
            });

						$('.delete').click(function(){
								var post_id = parseInt($(this).attr('id'));
								var button = $(this);
								var url = "delete.php";
								var data = {'id': post_id};
								$.post(url, data, function(response){
										console.log(response);
										location.reload();
								});
						});

						$('.verify').click(function(){
								var post_id = parseInt($(this).attr('id'));
								var button = $(this);
								var url = "verify.php";
								var data = {'id': post_id};
								$.post(url, data, function(response){
										console.log(response);
										location.reload();
								});
						});

            $("#newPost").click(function(){
							var lat;
							var lng;
							if ($("#location").prop('checked')){
								console.log("location is checked")
								// Try HTML5 geolocation.
								 if (navigator.geolocation){
									 navigator.geolocation.getCurrentPosition(function(position) {
										 lat = position.coords.latitude;
										 lng = position.coords.longitude;

										 console.log(lat,lng)

										 var $form = $("#postForm");
										 var url = "add_post.php";
										 var data = {'title': $('input[name="title"').val(),
										 					 'des': $('input[name="des"]').val(),
										 					 'point': $('input[name="point"]').val(),
										 						'wellness':$("#wellness").prop('checked'),
										 					'email':<?php echo "'".$email."'" ?>,
										 				'lat':lat,'lng':lng};

										 if ($("#wellness").prop('checked')){
										 	var url2 = "messaging/messaging/sendnotifications.php";
										 	$.post(url2, data, function(response){
										 			alert(response);
										 		});
										 }

										 $.post(url, data, function(response){
										 		alert(response);
										 		location.reload();
										 });
									 }, function(){console.log("No geolocation")})
									 }
								 }
								 else{

									 var $form = $("#postForm");
									 var url = "add_post.php";
									 var data = {'title': $('input[name="title"').val(),
									 					'des': $('input[name="des"]').val(),
									 					'point': $('input[name="point"]').val(),
									 					 'wellness':$("#wellness").prop('checked'),
									 				 'email':<?php echo "'".$email."'" ?>,
									 			 'lat':NULL,'lng':NULL};

									 if ($("#wellness").prop('checked')){
									  var url2 = "messaging/messaging/sendnotifications.php";
									  $.post(url2, data, function(response){
									 		 alert(response);
									 	 });
									 }

									 $.post(url, data, function(response){
									 	 alert(response);
									 	 location.reload();
									 });

								 }



            });
        });
    </script>


</body>
</html>
