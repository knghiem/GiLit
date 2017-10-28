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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/css/bootstrap-slider.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.0/bootstrap-slider.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>Gilit Pick Nearby Community</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaMAC8kPZ9TPt7gtps6gb18QHnfCpVvbk"></script>
	<style type="text/css" >
      #mapholder {width:700px; height:400px; margin: auto;}
        #zoom {padding: 20px}
        body{margin-bottom: 50px}
    </style>
</head>

<body style="text-align:center;">
	<h3>Become a member of a <b>Gilit</b> community</h3>
    <button class="btn btn-primary" onclick="getLocation()">Find local communities</button>
	<div id="zoom">
    <p> Zoom &nbsp;&nbsp;<input id="slider" data-slider-id='ex1Slider' type="text" data-slider-min="1" data-slider-max="18" data-slider-step="1" data-slider-value="7"/>  </p>
    <p>The slider library is buggy. To change the zoom level, slide back and forth before stopping at one value</p>
    </div>
	<div id="mapholder"></div>
    <div id="output"></div>
    
	<script>
        var zoom;
		var x = document.getElementById("demo");
        
        if(typeof(Storage)!=="undefined"){   // make sure local storage is supported
            //Get zoom from the local storage
            if(localStorage.getItem("zoom"))
                zoom = parseInt(localStorage.getItem("zoom"));
            else
                zoom = 7;
        }
        else
            zoom = 7;
            
        
        var slider = new Slider('#slider', {
            value: zoom,
        formatter: function(value) {
            return 'Current value: ' + value;
        }
        });
        
        slider.on("slide", function(sliderValue) {
            zoom = parseInt(sliderValue);
            if(typeof(Storage)!=="undefined"){ 
                localStorage.zoom = zoom;
            }
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
            
            //draw the Map
            initMap(lat, lon, zoom);
            //create buttons to add community
            addCommunity(lat, lon);
		}
        
        function initMap(lat,lon, zoom){
            var myLatlng = new google.maps.LatLng(lat, lon);
		    var mapOptions = {
		        zoom: zoom,
		        center: myLatlng,
		        mapTypeId: google.maps.MapTypeId.ROADMAP
		    }
		    var map = new google.maps.Map(document.getElementById('mapholder'), mapOptions);
            var infoWindow = new google.maps.InfoWindow;
		     // marker
		    var marker = new google.maps.Marker({ position: myLatlng, map: map, title: 'marker'});

		     // information window
		    var infowindow = new google.maps.InfoWindow({
		      content: "You are here"
		      });

		      // Eventlistener for the information window
            infowindow.open(map,marker);
		  
            
            downloadUrl("AJAX_MySql_latlonMAP.php?", lat, lon, function(data){
                    var xml = data.responseXML;
                    var markers = xml.documentElement.getElementsByTagName('marker');
                    /* test code
                    console.log(markers[0]);
                    console.log(markers[1]);
                    console.log(markers[2]);
                    */
                    Array.prototype.forEach.call(markers, function(markerElem) {
                        var id = markerElem.getAttribute('id');
                        var name = markerElem.getAttribute('com_name');
                        
                        var point = new google.maps.LatLng(
                            parseFloat(markerElem.getAttribute('lat')),
                            parseFloat(markerElem.getAttribute('lon')));
                        
                        marker = new google.maps.Marker({
                            position: point
                        });
                        
                        infowincontent = document.createElement('div');
                        var strong = document.createElement('strong');
                        
                        strong.textContent = name;
                        infowincontent.appendChild(strong);
                        infowincontent.appendChild(document.createElement('br'));
                        
                        var text = document.createElement('text');
                        text.textContent = "Community ID: "+id;
                        infowincontent.appendChild(text)
                         
                        
                        marker.infowindow = new google.maps.InfoWindow({content: infowincontent});
                        marker.infowindow.open(map, marker);
                        
                        marker.addListener('click', function() {
                            this.infowindow.open(map, this);
                        });
                        marker.setMap(map);
                    });
                });
        };
        
        function downloadUrl(url, lat, lon, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
              if (request.readyState == 4) {
                request.onreadystatechange = doNothing;
                callback(request, request.status);
              }
            };
                
            url += "lat=" + lat.toString();
            url += "&lon=" + lon.toString();
                
            request.open('GET', url, true);
            request.send(null);
        }

        function doNothing() {}
        
        function addCommunity(lat,lon){
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById("output").innerHTML = xmlhttp.responseText;
                    console.log(xmlhttp.responseXML);
                    $(document).ready(function(){
                        window.location= "#output";
                        //$("#mapholder").hide();
                    })
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
