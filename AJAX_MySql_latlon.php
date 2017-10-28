<!-- Part of group of three files:
     AJAX_MySql_prepare_database.php, AJAX_MySql_demo.php, AJAX_MySql_demo.html
     Called by AJAX_MySql_demo.html

     Returns a table with random recomendations from the requested season     -->
<?php

function latLonToMiles($lat1, $lon1, $lat2, $lon2){  //haversine formula
      $R = 3961;  // radius of the Earth in miles
      $dlon = ($lon2 - $lon1)*M_PI/180;
      $dlat = ($lat2 - $lat1)*M_PI/180;
      $lat1 *= M_PI/180;
      $lat2 *= M_PI/180;
      $a = pow(sin($dlat/2),2) + cos($lat1) * cos($lat2) * pow(sin($dlon/2),2) ;
      $c = 2 * atan2( sqrt($a), sqrt(1-$a) ) ;
      $d = number_format($R * $c, 2);
      return $d;
}

if( !empty($_GET["lat"]) && !empty($_GET["lon"])){
    $lat = $_GET["lat"];
    $lon = $_GET["lon"];

    // OPEN AND SELECT DATABASE
    $db_conn = mysqli_connect("localhost", "root", "");
    if (!$db_conn)
      die("Unable to connect: " . mysqli_connect_error());
    mysqli_select_db($db_conn, "gilit_db");

    //CONSTRUCT TABLE
    echo( "<h3><b>Gilit</b> communities near you</h3>" . PHP_EOL);
    
    $cmd = "SELECT *,
            SQRT(POW((com_long-$lon),2)+POW((com_lat-$lat),2)) as distance
            FROM coms ORDER BY distance ASC;";
    
    $records = mysqli_query($db_conn, $cmd);
    
    if (!$records)
        echo "Database error.".PHP_EOL;
    else{
        while($row = mysqli_fetch_array($records)){
            echo "<span><b>".$row['com_name']."</b> (".(latLonToMiles($row['com_lat'], $row['com_long'], $lat, $lon))." miles)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href ='add_community.php?com_id=".$row['com_id']."' class = 'btn btn-success btn-xs'>
            Add to my community</a><br><br>";
        }
    }
    mysqli_close($db_conn);
  }
?>
