<?php
    include('includes/config.php');
    $booking_id = $_POST['booking_id'];
    $cancel_reservation = $_POST['cancel_reservation'];


    $sql = "INSERT INTO tblconfirmation VALUES(null, :booking_id, :confirmation, curdate());";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':booking_id',$booking_id, PDO::PARAM_STR);
    $query->bindParam(':confirmation', $cancel_reservation, PDO::PARAM_STR);
    $query->execute();
    
    if (isset($_POST['cancel_reservation'])){
        if($cancel_reservation == 0) {
            echo "Booking ID: " . $booking_id . "Reservation: " . $cancel_reservation;
        }else{
            $sql = "UPDATE tblbooking SET Status = 2 WHERE id=:booking_id;";
            $query = $dbh -> prepare($sql);
            $query->bindParam(':booking_id',$booking_id, PDO::PARAM_STR);
            $query->execute();
            echo "Booking ID: " . $booking_id . "Reservation: " . $cancel_reservation;
        }
    }
?>