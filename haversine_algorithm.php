<?php
    include('includes/config.php');
    $sql = "SELECT vehicle.*, vehicle.id as v_id, user.*, user.id as u_id, brands.BrandName,(SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) from tblvehicles vehicle inner join tblbrands brands on brands.id=vehicle.VehiclesBrand inner join tblusers user on vehicle.user_id = user.id left join tblbooking booking on vehicle.id = booking.VehicleId WHERE (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) != 1 or (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) is null and user.verified_at is not null group by vehicle.VehiclesTitle order by v_id;";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $count=$query->rowCount();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    $current_latitude = $_GET['lat'];
    $current_longitude = $_GET['lng'];

    $distance_array = array();

    
    // Haversine Algorithm Function
    function getDistance($current_latitude, $current_longitude, $vehicle_latitude, $vehicle_longitude) {
        // Distance between latitude and longitude
        $diff_latitude = ($vehicle_latitude - $current_latitude) * M_PI / 180.0;
        $diff_longitude = ($vehicle_longitude - $current_longitude) * M_PI / 180.0;

        // Convert to radians
        $current_latitude = ($current_latitude) * M_PI / 180.0;
        $vehicle_latitude = ($vehicle_latitude) * M_PI / 180.0;

        //Apply haversine formula
        $apply = pow(sin($diff_latitude/2),2) + pow(sin($diff_longitude/2),2) * cos($current_latitude) * cos($vehicle_latitude);
        $radius = 6371; //Radius of the earth in KM
        $c = 2 * asin(sqrt($apply));
        $distance = $radius * $c;
        
        return $distance;
    }

    foreach($result as $vehicle) {
        
        $vehicle_latitude = $vehicle->lat;
        $vehicle_longitude = $vehicle->lng;
        $vehicle_distance = getDistance($current_latitude, $current_longitude, $vehicle_latitude, $vehicle_longitude);
        array_push($distance_array, number_format($vehicle_distance, 3, '.',''));
    }
    $string_distance = '[';
    $counter = 0;

    foreach($result as $vehicle) {

        $string_distance = $string_distance . '{"id":'.'"'.$vehicle->v_id.'"' .',"vehicle": "'.$vehicle->VehiclesTitle.'", "brand": "'.$vehicle->BrandName.'", "price": "'.$vehicle->PricePerDay.'","fuel": "'.$vehicle->FuelType.'","model": "'.$vehicle->ModelYear.'","Seats": "'.$vehicle->SeatingCapacity.'","image":"'.$vehicle->Vimage1.'","distance":'. $distance_array[$counter] .'},';
        $counter ++;
    }
    $string_distance= substr($string_distance, 0, -1);
    $string_distance = $string_distance . ']';
    $json_distance = $string_distance;

    echo $json_distance;
    
    // 7.7260795525433KM, 8.5822075061321KM, 41.627253420613KM, 2.8536273908496KM, 6.657244172769KM, 1.2101242506515KM, 6.8375769560576KM, 5.8698166565111KM, 
?>