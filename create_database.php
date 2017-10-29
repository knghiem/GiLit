<!DOCTYPE html>
<!--
Code from Professor Izmirli
Create a database using PHP. Run first to prepare the database
status tinyint(1) default 0 - status of the request, 0 = no commitment, 1 = committed, 2 = completed
*/-->

<html>
<head>
	<title>GiLit First Run</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {margin: 10% auto; text-align: center; color: white}
        video {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            transform: translateX(-50%) translateY(-50%);
            background-size: cover;
            transition: 1s opacity;
        }
        #message{background-color: rgba(0,0,0,0.3); padding: 20px}
    </style>
</head>

<body style="text-align:center; margin:75px;">
    <div id="message">
    <h3>Preparing the Database</h3>
    <br>
	<?php
		$db_conn = mysqli_connect("localhost", "root", "");
		if (!$db_conn)
			die("Unable to connect: " . mysqli_connect_error());
        //I. Creating the user table
				if (mysqli_query($db_conn, "CREATE DATABASE gilit_db;"))
							echo "Database ready<br><br>";
						else
							echo "Unable to create database: " . mysqli_error($db_conn) . "<br>";
						mysqli_select_db($db_conn, "gilit_db");

				$cmd ="CREATE TABLE IF NOT EXISTS coms (
					com_id int(11) PRIMARY KEY UNIQUE NOT NULL AUTO_INCREMENT,
					com_name varchar(60) UNIQUE NOT NULL);";

				if(mysqli_query($db_conn, $cmd))
					echo "Success<br>";
				else
					echo mysqli_error($db_conn);

				$cmd2 ="CREATE TABLE IF NOT EXISTS users (
				   user_id int(11) PRIMARY KEY UNIQUE NOT NULL AUTO_INCREMENT,
				   point int(11) NULL DEFAULT 0,
           point_accrue int(11) NULL DEFAULT 0,
				   name VARCHAR(45) NOT NULL,
           email VARCHAR(45) UNIQUE NOT NULL,
           password VARCHAR(45) NOT NULL,
				   tier INT(11) NULL DEFAULT 0,
				   badges INT(11) NULL DEFAULT 0,
				   phone INT(11) NOT NULL,
           role VARCHAR(45) NOT NULL,
				   rating tinyint(5) NULL DEFAULT 5,
				   com_id INT(11),
				  INDEX  fk_users_com_idx  ( com_id  ASC));";

						if(mysqli_query($db_conn, $cmd2))
							echo "Success<br>";
						else
							echo mysqli_error($db_conn);

				$cmd3 ="CREATE TABLE IF NOT EXISTS postcats (
				   cat_id   INT(11) UNIQUE AUTO_INCREMENT,
					 cat_name  VARCHAR(45) UNIQUE,
				  PRIMARY KEY (cat_id ));";

				if(mysqli_query($db_conn, $cmd3))
					echo "Success<br>";
				else
					echo mysqli_error($db_conn);

				$cmd4 ="CREATE TABLE IF NOT EXISTS posts (
				   post_id  INT(11) UNIQUE PRIMARY KEY NOT NULL AUTO_INCREMENT,
					 title VARCHAR(50),
				   des VARCHAR(140),
					 post_point INT(11),
				   wellness  TINYINT(1) NULL,
				   post_lat  VARCHAR(45) NULL,
				   post_long  VARCHAR(45) NULL,
				   giver_id INT(11) NULL,
					 getter_id INT(11) NULL,
				   com_id   INT(11),
				   cat_id   INT(11),
					 status   TINYINT(5) DEFAULT 0,
				  INDEX  post_id_UNIQUE  ( post_id  ASC),
				  INDEX  fk_posts_users1_idx  ( getter_id  ASC,  com_id  ASC),
				  INDEX  fk_posts_postcats1_idx  ( cat_id  ASC),
				  CONSTRAINT  fk_posts_postcats1
				    FOREIGN KEY (cat_id)
				    REFERENCES postcats (cat_id)
    				ON UPDATE CASCADE);";

				if(mysqli_query($db_conn, $cmd4))
					echo "Success<br>";
				else
					echo mysqli_error($db_conn);

				$cmd = "INSERT INTO postcats (cat_name) VALUES
                          ('Tutoring'),
                          ('Shopping'),
                          ('Cooking'),
                          ('Moving'),
                          ('Driving'),
                          ('Training'),
                          ('Other'),
                          ('Community service'),
                          ('Wellness');";

				if(mysqli_query($db_conn, $cmd))
					echo "Success<br>";
				else
					echo mysqli_error($db_conn);

        //III.1 Insert the first user into the database
				$cmd_user = "INSERT INTO users (name, email, password, phone, com_id, role, point, point_accrue) VALUES
                           ('Khanh Nghiem', 'knghiem@conncoll.edu', 'ChangeThis', 8607018860, 1, 'admin', 15000, 15000),
                           ('Atish Patel', 'atish.patel95@yahoo.com', 'password', 7703612418, 2, 'admin', 12000, 12000),
                           ('Arjun Athreya', 'arjun.r.athreya@gmail.com', 'password', 2032712335, 3, 'admin', 14000, 14000),
                           ('Jane Doe', 'janedoe@gmail.com', 'password', 1111111111, 4, 'user', 10000, 10000),
                           ('Arnold roll', 'ati00@yahoo.com', 'password', 7803600418, 5, 'admin', 2000, 2000),
                           ('Bam Adebayo', 'bam@yahoo.com', 'password', 2032712334, 1, 'admin', 6000, 6000),
                           ('John Collins', 'jc@yahoo.com', 'password', 9997775555, 2, 'admin', 9000, 9000),
                           ('Jason Tatum', 'jason.tatum@yahoo.com', 'password', 1987237892, 5, 'admin', 2000, 2000),
                           ('Markelle Fultz', 'mfultz@gmail.com', 'password', 1234567890, 3, 'user', 1000, 1000);";
/*comid: Connecticut College 1
*/
/*user_id int(11) PRIMARY KEY UNIQUE NOT NULL AUTO_INCREMENT,
				   points int(11) NULL DEFAULT 0,
				   name VARCHAR(45) NOT NULL,
           email VARCHAR(45) NOT NULL,
           password VARCHAR(45) NOT NULL,
				   tier INT(11) NULL DEFAULT 0,
				   badges INT(11) NULL DEFAULT 0,
				   phone INT(11)  NOT NULL,
           role VARCHAR(45) NOT NULL,
				   rating VARCHAR(45) NULL DEFAULT '5',
				   com_id INT(11),*/

        if( mysqli_query($db_conn, $cmd_user) )
						echo "User mock database success<br><br>";
				else
						echo mysqli_error($db_conn);
/*
					com_id int(11) PRIMARY KEY UNIQUE NOT NULL AUTO_INCREMENT,
					com_name varchar(60) NULL);";*/


        //III.2 Insert the first community into the database
        $cmd = "INSERT INTO coms (com_name) VALUES
                           ('Connecticut College'),
                           ('Bentley University'),
                           ('University of Pennsylvania'),
                           ('Yale University'),
                           ('University of Connecticut'),
                           ('Trinity College');";

        if( mysqli_query($db_conn, $cmd) )
						echo "Community mock database success<br><br>";
				else
						echo mysqli_error($db_conn);

/*
				   post_id  INT(11) UNIQUE PRIMARY KEY NOT NULL AUTO_INCREMENT,
				   title VARCHAR(50),
           des VARCHAR(140),
           wellness  TINYINT(1) NULL,
					 post_point INT(11),
				   getter_id  INT(11),
				   com_id   INT(11),
				   cat_id   INT(11),
           giver_id INT(11) NULL,
           post_lat  VARCHAR(45) NULL,
				   post_long  VARCHAR(45) NULL,
*/

        //IV.2 Insert the first posts

        $cmd = "INSERT INTO posts (title, des, wellness, post_point, getter_id, com_id, cat_id) VALUES
                ('Donate a book to Shain Library', 'Donate any book to the library', 1, 1000, 2, 2, 8),
                ('Buy me food', 'go to chipotle and buy me food', 1, 1000, 1, 1, 2),
                ('Train CS noobs', 'Train some CS undergrads', 1, 1000, 3, 3, 8),
                ('Help me get in shape', 'Be my physical trainer', 1, 1000, 2, 2, 6),
                ('Drunk student needs assistance', 'There is a student who is currently wasted on campus and needs help', 0, 0, 1, 1, 9),
                ('Volunteer at New London High School', 'Help the kids learn', 1, 1000, 2, 2, 8),
                ('Take out my trash', 'Throw out my trash', 1, 1000, 3, 3, 6),
                ('Drive me to the airport', 'Need to get to the airport ASAP to catch my flight', 1, 1000, 1, 1, 7),
                ('Volunteer at New Russia High School', 'Teach Russian at the Russian High School', 1, 1000, 3, 3, 8);";

        if( mysqli_query($db_conn, $cmd) )
					echo "Success<br>";
				else
					echo mysqli_error($db_conn)."<br>";

        echo "<br><a href='index.html' class='btn btn-default'>Home</a>";

		mysqli_close($db_conn);

    //LOAD DATA INFILE 'filename.csv' INTO TABLE tablename (col1,col2,...) FIELDS TERMINATED BY ','

	?>
    </div>

</body>
</html>
