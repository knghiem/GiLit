<?php
    /* Send an SMS using Twilio
     *
     * 1. Save it as sendnotifications.php and at the command line, run
     *         php sendnotifications.php
     *
     * 2. Upload it to a web host and load mywebhost.com/sendnotifications.php
     *    in a web browser.
     *
     * 3. Download a local server like WAMP, MAMP or XAMPP. Point the web root
     *    directory to the folder containing this file, and load
     *    localhost:8888/sendnotifications.php in a web browser.
     */

    // Step 1: Get the Twilio-PHP library from twilio.com/docs/libraries/php,
    // following the instructions to install it with Composer.
    require __DIR__ . '/vendor/autoload.php';

    use Twilio\Rest\Client;

    // Step 2: set our AccountSid and AuthToken from https://twilio.com/console

        if( !empty($_POST["title"])){
            $title = $_POST["title"];
            $des = $_POST["des"];
            $email = $_POST["email"];
            $lat = $_POST["lat"];
            $lng = $_POST["lng"];

            $location_msg = "Contact the sender for info for the location.";

            if ($lat && $lng) {
              $url = "https://www.google.com/maps/search/?api=1&query=".$lat.",".$lng."";
              $location_msg = "Go to this link for direct to the sent location: ".$url;
            }



            // OPEN AND SELECT DATABASE
            $db_conn = mysqli_connect("localhost", "root", "");
            if (!$db_conn)
              die("Unable to connect: " . mysqli_connect_error());
            mysqli_select_db($db_conn, "gilit_db");

            $cmd  = "SELECT * from users where email='".$email."'";
            $result=mysqli_query($db_conn, $cmd);

            if (mysqli_num_rows($result)==1){
                while($row = mysqli_fetch_array($result)){
                    $getter_name = $row["name"];
                    $getter_phone = $row["phone"];
                    $com_id = $row["com_id"];
                }
              }
            else
                echo mysqli_error($db_conn);

            //get from the database all other members in the community
            $cmd2  = "SELECT * from users where com_id=".$com_id."";
            $result2 = mysqli_query($db_conn, $cmd2);
            $people = [];
            if (mysqli_num_rows($result2)>0){
                while($row2 = mysqli_fetch_array($result2)){
                    if ($row2["email"] != $email){
                      $name = $row2["name"];
                      $number = $row2["phone"];
                      $people[$number] = $name;
                    }
                }
            }
            else
                echo '<p>No one to message</p>';

            mysqli_close($db_conn);
          }
          else
            echo mysqli_error($db_conn);



    $AccountSid = "AC1316b1c2d5b02bee7fdccb555b43aa30";
    $AuthToken = "03fd6c129648b3b2bbaa25a6937aeff8";

    // Step 3: instantiate a new Twilio Rest Client
    $client = new Client($AccountSid, $AuthToken);

    // Step 4: Array of people to send message to
     //user phone number who posted
     $people = array(
         "+12037277793" => "Arjun",
     );
    // Step 5: loop over the people who will receive the message
    foreach ($people as $number => $name) {
        $sms = $client->account->messages->create(

            // the number the messsage is being sent to
            $number,
            array(
                'from' => "+16315460584", //twilio_num - user who commited
                // the sms body
                'body' => "GiLit well-ness message from ".$getter_name.": ".$des." Sender: ".$getter_phone." as soon as possible.".$location_msg  //user_message
            )
        );
        // Display a confirmation message on the screen
        echo "Sent message to ".$name."at number: ".$number.".<br>";
    }
?>
