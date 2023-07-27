<?php
    include('includes/config.php');

    $booking_id = $_POST['booking_id'];

    $sql = "UPDATE tblusage SET confirmation = 1 WHERE booking_id = :booking_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':booking_id', $booking_id, PDO::PARAM_STR);
    $query->execute();
    
    echo "Booking ID #" . $booking_id . " usage has successfully started.";
    
?>