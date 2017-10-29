<!DOCTYPE html>
<html>
<head>
    <title>Give A Little, Get A Little</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="index.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body style="background-color: grey">
    <ul id="nav">
        <li><h1><b>GiLit</b></h1></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="adduser.php">Sign up</a></li>
        <li><a href="community_board.php">Community Board</a></li>
        <li><a href="create_database.php" class=>Prepare database</a></li>
    </ul>
    <div class="container" style="background-color:white">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
            <li><a data-toggle="tab" href="#docp">Documentation</a></li>
        </ul>

        <div class="tab-content" style="padding: 50px 50px 50px 50px">

            <div id="home" class="tab-pane fade in active">
                <h3>Welcome to our community!</h3><br>
                <h4><b>GiLit</b> is a web-based platform for people to give and get help within their local community.</h4>
                <br>
                <b>How it works</b>
                <ul>
                    <li>Prepare database in the first run</li>
                    <li>Register for a free account</li>
                    <li>Find your local community</li>
                    <li>Help out and gain points</li>
                    <li>Use your points to ask for help, or give them to others in need!</li>
                    <li>Maintain the safety and wellness of the student body</li>
                    <li>When a dangerous situation takes place, a text message is sent out to the community via Twilio</li>
                </ul>

                <img src="https://www.bu.edu/cs/files/2015/11/rhett_logo.jpg"/>
                <div id = "block1"><p><b>GiLit</b> is created by Khanh Nghiem, Arjun Athreya and Atish Patel. The name is short for <b>"Give a Little, Get a Little"</b>, a brilliant phrase that Jason Karos (Connecticut College '18) came up with. We want to use software engineering to foster community engagement and spread kindness on campus' all over the world. This is the prototype version and will be graded as the project for the Boston Hacks 2017 event. More features to come!</p>
                <br>
                <a href="adduser.php" id="signup" class="btn btn-primary">Get Started!</a>
                </div>
                <br>
            </div>
            <div id="docp" class="tab-pane fade">
                <h3>The Project Documentation Page</h3>

                <h4><b>Team Members</b></h4>
                <p>Khanh Nghiem, Arjun Athreya and Atish Patel. BostonHacks Fall 2017</p>

            </div>
        </div>
    </div>
</body>
</html>
