<?php
include('includes/config.php');

$rating = $_POST['rating'];
$rental = $_POST['rental']; //lender
$booking_id = $_POST['booking_id'];
$rate_type = $_POST['rate_type'];

	$sql = "INSERT INTO tblratings VALUES(null, :id, (SELECT user.id FROM tblusers user INNER JOIN tblbooking booking on user.EmailId = booking.userEmail WHERE booking.id = :rate_id),:rate_id, :rating, :rate_type, curdate());";	
    $query = $dbh->prepare($sql);
    $query->bindParam(':id',intval($rental), PDO::PARAM_INT);
    $query->bindParam(':rate_id',$booking_id, PDO::PARAM_STR);
    $query->bindParam(':rating', $rating, PDO::PARAM_STR);
    $query->bindParam(':rate_type',$rate_type, PDO::PARAM_STR);
    if($query->execute()) {
        echo "Rating: " . $rating . " Rental: " . $rental . " Booking ID: " . $booking_id . " Rate Type: " . $rate_type;
    }else{
        print_r($query->errorInfo());
    }
    
    
?>