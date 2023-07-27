<?php

    include('includes/config.php');
    $booking_id = $_POST['booking_id']; 
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
  
    $sql = "SELECT booking_id FROM tbllocation WHERE booking_id = :booking_id;";
    $query = $dbh->prepare($sql);
    $query->bindParam('booking_id', $booking_id, PDO::PARAM_STR);
    $query->execute();
    
    if($query->rowCount() == 0) {
        $sql_location = "INSERT INTO tbllocation VALUES(:booking_id, :lat, :lng);";
        $query_location = $dbh->prepare($sql_location);
        $query_location->bindParam(':booking_id', $booking_id, PDO::PARAM_STR);
        $query_location->bindParam(':lat', $lat, PDO::PARAM_STR);
        $query_location->bindParam(':lng', $lng, PDO::PARAM_STR);
        $query_location->execute();
    }else{
        $sql_location = "UPDATE tbllocation SET lat = :lat, lng = :lng WHERE booking _id = :booking_id;";
        $query_location = $dbh->prepare($sql_location);
        $query_location->bindParam(':booking_id', $booking_id, PDO::PARAM_STR);
        $query_location->bindParam(':lat', $lat, PDO::PARAM_STR);
        $query_location->bindParam(':lng', $lng, PDO::PARAM_STR);
        $query_location->execute();
    }
    
    // echo "Successfully fetched customer's location. Booking ID: " . $booking_id;
     echo "Successfully fetched customer's location.";
?>