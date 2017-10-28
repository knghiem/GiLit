<?php
	session_start();           // Start new or resume existing session
	if(!isset($_SESSION['active'])){
		header("Location: login.php");  //  back to login page
	}
	$db_conn = mysqli_connect("localhost", "root", "");
	mysqli_select_db($db_conn, "gilit_db");
?>

<!-- This page takes the geolocation of the user an put allow the user to choose a nearby community   

There is a button to get the user's geolocation
When the user click the button, a JavaScript google map appears, with the center as the user's location
A list of nearby communities (that registered for Gilit) is pulled from the database
Draw markers on the map, each one represents a community, when clicked, an info window pops up with the name of the community
The user choose one community, update the user's community in the database
-->

<!-- Check if the user is logged in -->

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale = 1, user-scalable = no">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/css/bootstrap-slider.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/bootstrap-slider.js"></script>
	<title>Gilit Pick Nearby Community</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaMAC8kPZ9TPt7gtps6gb18QHnfCpVvbk"></script>
	<style type="text/css" >
      #mapholder {width:700px; height:400px; margin: auto;}
        #zoom {padding: 20px}
    </style>
</head>

<body style="text-align:center;">
	<h1>Become a member of a <b>Gilit</b> community</h1>
	<p id="demo">Click the button to get find nearby communities!</p>
	
    <button onclick="getLocation()">Find local communities</button>
	<div id="zoom">
    <p> Zoom &nbsp;&nbsp;<input id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="1" data-slider-max="18" data-slider-step="1" data-slider-value="14"/>  </p>
    </div>
    
	<div id="mapholder"></div>
    <article id="output"></article>

	<script>
		var zoom = 12;
		var x = document.getElementById("demo");
        
        //Bootstrap Slider code from http://seiyria.com/bootstrap-slider/
        var slider = new Slider('#ex1', {
        formatter: function(value) {
            return 'Current value: ' + value;
        }
        });
        
        slider.on("slide", function(sliderValue) {
            zoom = parseInt(sliderValue);
        });

		//Called by button click; retrieves coordinates
		function getLocation(){
			if (navigator.geolocation)
				navigator.geolocation.getCurrentPosition(showPosition, showError);
			else
				x.innerHTML = "Geolocation is not supported by this browser.";
		}

		function showError(error){
			switch(error.code) {
				case error.PERMISSION_DENIED:
					x.innerHTML="User denied the request for Geolocation."
					break;
				case error.POSITION_UNAVAILABLE:
					x.innerHTML="Location information is unavailable."
					break;
				case error.TIMEOUT:
					x.innerHTML="The request to get user location timed out."
					break;
				case error.UNKNOWN_ERROR:
					x.innerHTML="An unknown error occurred."
					break;
			}
		}
		
		function showPosition(position){
			var lat = position.coords.latitude;
			var lon = position.coords.longitude;
		    var myLatlng = new google.maps.LatLng(lat, lon);
		    var mapOptions = {
		        zoom: zoom,
		        center: myLatlng,
		        mapTypeId: google.maps.MapTypeId.ROADMAP
		    }
		    var map = new google.maps.Map(document.getElementById('mapholder'), mapOptions);
		     // marker
		    var marker = new google.maps.Marker({ position: myLatlng, map: map, title: 'marker'});

		     // information window
		    var infowindow = new google.maps.InfoWindow({
		      content: "You are here"
		  });

		   // Eventlistener for the information window
		  google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });
            
            getData(lat,lon);
		}
        
        function getData(lat,lon){
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById("output").innerHTML = xmlhttp.responseText;
                    console.log(xmlhttp.responseXML);
                }
                    
            };
            var str = "AJAX_MySql_latlon.php?";
            str += "lat=" + lat.toString();
            str += "&lon=" + lon.toString();
            xmlhttp.open("GET",str,true);
            xmlhttp.send();
        }

	</script>
    
</body>
</html>
