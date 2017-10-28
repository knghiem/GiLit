<!DOCTYPE html>
<!-- 
Code from Professor Izmirli
Create a database using PHP. Run first to prepare the database 

I. A table of users: 
    1. user_id int(11) primary key not null auto_increment - user id to reference in the system
    2. name var char not null - Display Name
    3. email var char not null - email address
    4. username var char 10 unique not null - user name to log in
    5. password var char 20 not null - log in password
    6. com_id int(11) DEFAULT NULL - the id of the user's community
    7. role set('admin','user') DEFAULT user - the role of the user, the admin of the database has to go in and change this
    8. points int(11) not null DEFAULT 0 - the available points of the user

II. A table of communities:
    1. com_id int(11) primary key not null auto_increment - user id to reference in the system
    2. Name var char not null - name of the community
    3. admin_id var char not null - user_id of the admin
    4. com_lat decimal(15,10) - the latitude of the community
    5. com_long decimal(15,10) - the longitude of the community

III. Load the first user and community into the database

IV. A table of posts 
    1. help_id int(11) primary key not null auto_increment 
    2. com_id int(11) the id of the community
    3. title var char 100 - title of the request, max 100 character
    4. des medium text - full description, max 16777215 characters
    5. get_id int (11) not null - id of the getter
    6. give_id int (11) default null - id of the giver 
    7. status tinyint(1) default 0 - status of the request, 0 = no commitment, 1 = committed, 2 = completed
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
    <div id="video"><video id="bgvid" playsinline autoplay muted loop>
    <source src="media/water.webm" type="video/webm">
    </video></div>
    <div id="message">
    <h3>Preparing the Database</h3>
    <br>
	<?php
		$db_conn = mysqli_connect("localhost", "root", "");
		if (!$db_conn)
			die("Unable to connect: " . mysqli_connect_error());

		if (mysqli_query($db_conn, "CREATE DATABASE gilit_db;"))
			echo "Database ready<br><br>";
		else
			echo "Unable to create database: " . mysqli_error($db_conn) . "<br>";

		mysqli_select_db($db_conn, "gilit_db");
        
        //I. Creating the user table
		$cmd = "CREATE TABLE IF NOT EXISTS users (
                                                    user_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                                    name varchar(60) NOT NULL,
                                                    email varchar(100) UNIQUE NOT NULL,
                                                    username varchar(60) UNIQUE NOT NULL,
                                                    password varchar(60) NOT NULL,
                                                    com_id int(11) DEFAULT NULL,
                                                    role set('user','admin') DEFAULT 'user',
                                                    point int(11) NOT NULL DEFAULT 0
                                                  );";
		if( mysqli_query($db_conn, $cmd) )
			echo "Table 'users' created<br><br>";
		else
			echo "Table 'users' not created: ". mysqli_error($db_conn) . "<br>";
    
    
    
        //II. Creating the community table
		$cmd = "CREATE TABLE IF NOT EXISTS coms (
                                                    com_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                                    com_name varchar(60) UNIQUE NOT NULL,
                                                    admin_id int(11) NOT NULL,
                                                    com_lat decimal(7,4) NOT NULL,
                                                    com_long decimal(7,4) NOT NULL
                                                  );";
        if( mysqli_query($db_conn, $cmd) )
			echo "Table 'coms' created<br><br>";
		else
			echo "Table 'coms' not created: ". mysqli_error($db_conn) . "<br>";
    
    
    
        //III.1 Insert the first user into the database
        $cmd = "INSERT INTO users (user_id, name, email, username, password, com_id, role, point) VALUES
                           (1, 'Khanh Nghiem', 'knghiem@conncoll.edu', 'knghiem', 'ChangeThis', 1, 'admin', 1000)
                           ;";
    
        if( mysqli_query($db_conn, $cmd) )
			echo "Khanh Nghiem is the first user in the database<br><br>";
		else
			echo "Attempted to add Khanh Nghiem to the user databased but failed". mysqli_error($db_conn) . "<br>";
    
    
    
        //III.2 Insert the first community into the database
        $cmd = "INSERT INTO coms (com_name, admin_id, com_lat, com_long) VALUES
                           ('Connecticut College', 1, 41.3787, -72.1046),
                           ('Yale University', 1, 41.3163, -72.9223),
                           ('University of Connecticut', 1, 41.8077, -72.254),
                           ('Trinity College', 1, 41.7479, -72.6903)
                           ;";
    
        if( mysqli_query($db_conn, $cmd) )
			echo "Sample communities added to the database<br><br>";
		else
			echo "Attempted to add Connecticut College to the community databased but failed". mysqli_error($db_conn) . "<br>";
    
        //IV. Create the post table
        $cmd = "CREATE TABLE IF NOT EXISTS posts (
                                                    help_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                                    com_id int(11) NOT NULL,
                                                    title varchar(100) NOT NULL,
                                                    des mediumtext DEFAULT NULL,
                                                    get_id int(11) NOT NULL,
                                                    give_id int(11) DEFAULT NULL,
                                                    point int(11) NOT NULL,
                                                    status tinyint(1) NOT NULL DEFAULT 0
                                                  );";
        if( mysqli_query($db_conn, $cmd) )
			echo "Table 'posts' created<br><br>";
		else
			echo "Table 'posts' not created: ". mysqli_error($db_conn) . "<br>";
        
        //IV.2 Insert the first 2 posts
        $des1 = "Shain Library is looking for book donations, preferrably books in good conditions. Please come to Blue Camel Coffee and drop off your old books. Your kindness is greatly appreciated. Thank you.";
        
        $des2 = "The Office of Community Engagement is looking for volunteers to teach languages, music, and sports for the New London Public High School. Sign up for the emailing list and get points. Based on your commitment with the office, points will be adminstered by the admin of the college community.";
    
        $cmd = "INSERT INTO posts (com_id, title, des, get_id, point, status) VALUES
                (1, 'Donate a book to Shain Library', '".$des1."', 1, 1000, 0),
                (2, 'Donate a book to Shain Library', '".$des1."', 1, 1000, 0),
                (3, 'Donate a book to Shain Library', '".$des1."', 1, 1000, 0),
                (4, 'Donate a book to Shain Library', '".$des1."', 1, 1000, 0),
                (1, 'Volunteer at New London High School', '".$des2."', 1, 1000, 0),
                (2, 'Volunteer at New London High School', '".$des2."', 1, 1000, 0),
                (3, 'Volunteer at New London High School', '".$des2."', 1, 1000, 0),
                (4, 'Volunteer at New London High School', '".$des2."', 1, 1000, 0);";
    
        if( mysqli_query($db_conn, $cmd) )
			echo "Sample posts have been added to the database<br><br>";
		else
			echo "Attempted to add sample posts to the databased but failed". mysqli_error($db_conn) . "<br>";
        
        echo "<br><a href='index.html' class='btn btn-default'>Home</a>";

		mysqli_close($db_conn);
    
    /*
    LOAD DATA INFILE 'filename.csv' INTO TABLE tablename (col1,col2,...) FIELDS TERMINATED BY ','
    */
	?>
    </div>

</body>
</html>
