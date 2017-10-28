<?php
if( !empty($_GET["lat"]) && !empty($_GET["lon"])){
    $lat = $_GET["lat"];
    $lon = $_GET["lon"];

    // OPEN AND SELECT DATABASE
    $db_conn = mysqli_connect("localhost", "root", "");
    if (!$db_conn)
      die("Unable to connect: " . mysqli_connect_error());
    mysqli_select_db($db_conn, "gilit_db");

    //Select the communities by nearest to user's location
    $cmd = "SELECT *,
            SQRT(POW((com_long-$lon),2)+POW((com_lat-$lat),2)) as distance
            FROM coms ORDER BY distance ASC;";
    
    $records = mysqli_query($db_conn, $cmd);
    
    if (!$records)
        die('Invalid query: ' . mysql_error());
        // Start XML file, create parent node

    $dom = new DOMDocument("1.0","UTF-8");
    $node = $dom->createElement("markers");
    $node = $dom->appendChild($node);

    header("Content-type: text/xml");

    while($row = mysqli_fetch_array($records)){
        $newnode = $dom->createElement("marker");
        $newnode = $node->appendChild($newnode);
        $newnode->setAttribute("id", $row['com_id']);
        $newnode->setAttribute("com_name", $row['com_name']);
        $newnode->setAttribute("lat", $row['com_lat']);
        $newnode->setAttribute("lon", $row['com_long']);
    }

    echo $dom->saveXML();
    
    mysqli_close($db_conn);
  }
?>
