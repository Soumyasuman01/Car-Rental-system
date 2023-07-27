<?php 
session_start();
include('includes/config.php');
error_reporting(0);

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>EZRent</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="assets/css/style.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!-- SWITCHER -->
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" data-default-color="true"/>
<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
<!-- Fav and touch icons -->
<!-- <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png"> -->
<!-- <img src="assets/images/3.jpg" style="width:60%"> -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/3.jpg">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/3.jpg">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/3.jpg">
<link rel="shortcut icon" href="assets/images/favicon-icon/3.jpg">
<link rel="stylesheet" href="assets/css/map.css">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet"> 
<link rel="stylesheet" href="assets/css/modal_confirmation.css">
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script src="onesignal.js"></script>
</head>
<body>

<!-- Start Switcher -->
<!-- /Switcher -->  



<!--Header--> 
<?php include('includes/header.php');?>
<!-- /Header --> 


<!--Mobile Checker-->
<?php
$mobile = false;
    $banner = 'banner-section';
    if ($detect->isMobile() || $detect->isTablet() ) {
        $mobile = true;
    $banner = 'mobile-banner-section';
    }
?>
<!--/Mobile Checker-->

<!-- GET TAGS -->
<?php

if(isset($_SESSION['login'])) {
  $sql = "SELECT id, UserType FROM tblusers WHERE EmailId=:user_email";
  $query = $dbh->prepare($sql);
  $query->bindParam(':user_email', $_SESSION['login'], PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!-- REGISTER TAGS TO A USER -->
  <script>
    OneSignal.push(function() {
      OneSignal.sendTags({
        user_name:"<?php echo $_SESSION['login'] ?>",
        user_id: "<?php echo $result[0]->id ?>",
        user_type: "<?php echo $result[0]->UserType ?>",
      });
    });
  </script>
<?php
}
?>

<!--Page Header-->
<section id="banner" class="<?php echo $banner ?>">
  <div class="container">
    <div class="div_zindex">
      <div class="row">
        <div class="col-md-5 col-md-push-7">
          <div class="banner_content">
              <?php if ($mobile) {  echo '<span style="text-align:center !important;">'; } ?>
                     <?php if ($mobile) {  echo '<h2 style="color:#fff !important; margin-right:10px;">EzRent <span style="background-color:#e00d4c; padding: 4px;">Go!</span></h2><hr style="margin-right:10px;"><br/>'; } ?>
            <h1 class="text-shadow" style="color:#fff !important;">Rent the right vehicle for you.</h1>
            <p class="text-shadow" style="margin-right:10px;"><small>EZRent offers thousand of vehicles for you to choose.</small></p>
            <!-- <a href="#" class="btn">Read More <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></a> </div> -->
                   <?php if ($mobile) {  echo '<div class="text-center m-auto" style="background-color:#e00d4c; padding:4px; color:#fff; margin-right:20px;"><small>Scroll down for deals</small></div>'; } ?>
              <?php if ($mobile) {  echo '</span>'; } ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /Page Header--> 

<div class="container" id="map-container">
  <button id="btn-view-map" class="btn btn-primary" onclick="dropDownMap();" type="button"> View Rentals' Location</button>
  <div id="map"></div>
  <input type="hidden" id="lat" value="12312312">
  <input type="hidden" id="lng" value="123123123">
  <button type="button" id="click-trigger"style="visibility:hidden;">Click me</button>
  <button type="button" id="trigger-confirmation-modal" style="visibility:hidden;" data-toggle="modal" data-target="#confirmationModal">Confirmation Button</button>
</div>
<!--Listing-->

<section class="listing-page">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-md-push-3" id="insert-rows">
        <div class="result-sorting-wrapper">
          <div class="sorting-count">
          
          <?php 
          //Query for Listing count
          $sql = "SELECT vehicle.*,user.*, user.id as u_id, brands.BrandName,(SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) from tblvehicles vehicle inner join tblbrands brands on brands.id=vehicle.VehiclesBrand inner join tblusers user on vehicle.user_id = user.id left join tblbooking booking on vehicle.id = booking.VehicleId WHERE (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) != 1 or (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) is null and user.verified_at is not null group by vehicle.VehiclesTitle;";
          $query = $dbh -> prepare($sql);
          $query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
          $query->execute();
          $results=$query->fetchAll(PDO::FETCH_OBJ);
          $cnt=$query->rowCount();
          ?>
          <p><span><?php echo htmlentities($cnt);?> Listings</span></p>
          </div>
        </div>

<?php $sql = "SELECT vehicle.*, vehicle.id as v_id, user.*, user.id as u_id, brands.BrandName,(SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) from tblvehicles vehicle inner join tblbrands brands on brands.id=vehicle.VehiclesBrand inner join tblusers user on vehicle.user_id = user.id left join tblbooking booking on vehicle.id = booking.VehicleId WHERE (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) != 1 or (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) is null and user.verified_at is not null group by vehicle.VehiclesTitle;";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  ?>
        <div class="product-listing-m gray-bg">
          <div class="product-listing-img"><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="Image" /> </a> 
          </div>
          <div class="product-listing-content">
            <h5><a href="vehical-details.php?vhid=<?php echo htmlentities($result->v_id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
            <p class="list-price">&#8369; <?php echo number_format(htmlentities($result->PricePerDay),2);?></p>
            <ul>
              <li><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity);?> seats</li>
              <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($result->ModelYear);?> model</li>
              <li><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($result->FuelType);?></li>
            </ul>
            <a href="vehical-details.php?vhid=<?php echo htmlentities($result->v_id);?>" class="btn">View Details <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></a>
          </div>
        </div>
      <?php }} ?>
         </div>
      
      <!--Side-Bar-->
      <aside class="col-md-3 col-md-pull-9">
        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-filter" aria-hidden="true"></i> Find Your  Car </h5>
          </div>
          <div class="sidebar_filter">
            <form action="search-carresult.php" method="post">
              <div class="form-group select">
                <select class="form-control" name="brand">
                  <option>Select Brand</option>

                  <?php $sql = "SELECT * from  tblbrands ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{       ?>  
<option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?></option>
<?php }} ?>
                 
                </select>
              </div>
              <div class="form-group select">
                <select class="form-control" name="fueltype">
                  <option>Select Fuel Type</option>
<option value="Petrol">Petrol</option>
<option value="Diesel">Diesel</option>
<option value="CNG">CNG</option>
                </select>
              </div>
             
              <div class="form-group">
                <button type="submit" class="btn btn-block"><i class="fa fa-search" aria-hidden="true"></i> Search Car</button>
              </div>
            </form>
          </div>
        </div>

        <!-- <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-car" aria-hidden="true"></i> Recently Listed Cars</h5>
          </div>
          <div class="recent_addedcars">
            <ul>
<?php $sql = "SELECT vehicle.*,user.*, user.id as u_id, brands.BrandName,(SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) from tblvehicles vehicle inner join tblbrands brands on brands.id=vehicle.VehiclesBrand inner join tblusers user on vehicle.user_id = user.id left join tblbooking booking on vehicle.id = booking.VehicleId WHERE (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) != 1 or (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) is null and user.verified_at is not null order by id desc limit 4;";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  ?>
              <li class="gray-bg">
                <div class="recent_post_img"> <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="image"></a> </div>
                <div class="recent_post_title"> <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a>
                  <p class="widget_price">Php <?php echo htmlentities($result->PricePerDay);?> / Day</p>
                </div>
              </li>
              <?php }} ?>
              
            </ul>
          </div>
        </div>
      </aside>
      <!--/Side-Bar
    </div>
  </div> -->
</section>
<!-- /Listing--> 

<!-- MODAL FOR CONFIRMATION (1 WEEK BEFORE RENTING VEHICLE) -->
<div class="modal fade" tabindex="-1" role="dialog" id="confirmationModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" id="modal_confirm_dialog">
    <div class="modal-content">
    <div class="modal-body text-center" id="modal_confirmation_body">
      
      <span class="glyphicon glyphicon-bell" style="font-size: 40px; padding-top: 15px; padding-left: 22px; padding-top: 30px;"></span>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="font-size: 35px;">&times;</span></button>
      <h4 class="display-4" style="padding-top: 10px;">Reminders</h4>
      <br>
      <p style="font-size: 15px;">Dear Customer, this is a reminder of your requested vehicle and that you must appear on the requested date of your rental. Kindly finalize your reservation.</p>

      <p style="font-size: 13px">*By clicking the green button, you have confirmed your reservation. Otherwise, you have cancelled the vehicle's booking.</p>
      <?php
        $sql = "SELECT booking.id as booking_id, booking.userEmail as user_email, booking.VehicleId as VehicleId, booking.FromDate as FromDate, booking.ToDate as ToDate, booking.message as message, booking.Status as Status, user.id as u_id, user.Fullname as Fullname, user.EmailId as EmailId, vehicle.id as vehicle_id, vehicle.VehiclesTitle as VehiclesTitle, brand.BrandName as BrandName, vehicle.PricePerDay as PricePerDay, vehicle.FuelType as FuelType, vehicle.ModelYear as ModelYear, vehicle.SeatingCapacity as SeatingCapacity FROM tblbooking booking INNER JOIN tblusers user ON booking.userEmail = user.EmailId INNER JOIN tblvehicles vehicle ON booking.VehicleId = vehicle.id INNER JOIN tblbrands brand on brand.id = vehicle.VehiclesBrand WHERE booking.userEmail = :email_id AND booking.Status = 1 AND DATEDIFF(booking.FromDate, curdate()) <= 7;";
        $query = $dbh -> prepare($sql);
        $query->bindParam(':email_id',$_SESSION['login'], PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
      ?>
<div class="table-responsive">
      <table class="table">
        <thead>
          <th style="text-align:center;">Booking Date</th>
          <th style="text-align:center;">Vehicle Name</th>
          <th style="text-align:center;">Purpose</th>
          <th style="text-align:center;">Return Date</th>
          <th style="text-align:center;">Action</th>
        </thead>
        <tbody>
          
            <?php
              for($index = 0; $index < $query->rowCount(); $index++) {
                $sql_confirmation = "SELECT booking_id FROM tblconfirmation WHERE booking_id = :book_id";
                $query_confirmation = $dbh -> prepare($sql_confirmation);
                $query_confirmation->bindParam(':book_id',$result[$index]->booking_id, PDO::PARAM_STR);
                $query_confirmation->execute();
                
                if($query_confirmation->rowCount() == 0) {
            ?>
                  <tr>
                    <td><?php echo $result[$index]->FromDate; ?></td>
                    <td><?php echo $result[$index]->VehiclesTitle; ?></td>
                    <td><?php echo $result[$index]->message; ?></td>
                    <td><?php echo $result[$index]->ToDate; ?></td>
                    
                    <td>
                        <input type="hidden" name="confirm_rent_confirm" value="<?php echo $result[$index]->booking_id ?>">
                        <input type="hidden" name="confirm_rent_cancel" value="<?php echo $result[$index]->booking_id ?>">
                        <button type="button" class="btn btn-primary confirm_rent_confirm" id="confirm_rent_confirm_<?php echo $result[$index]->booking_id; ?>" value="<?php echo $result[$index]->booking_id ?>"><span class="glyphicon glyphicon-check"></span></button>
                        <button type="button" class="btn btn-primary confirm_rent_cancel" id="confirm_rent_cancel_<?php echo $result[$index]->booking_id; ?>" value="<?php echo $result[$index]->booking_id ?>"><span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr>
            <?php
                }
              }
            ?>
        </tbody>
      </table>
      </div>
      <p style="font-size: 13px;"><strong style="color: red;">* NOTICE:</strong> Without your presence on the said booking date, a penalty will be applied.</p>
   </div>
   
    </div>
  </div>
</div>

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
<!--/Back to top--> 

<!--Login-Form -->
<?php include('includes/login.php');?>
<!--/Login-Form --> 

<!--Register-Form -->
<?php include('includes/registration.php');?>

<!--/Register-Form --> 

<!--Forgot-password-Form -->
<?php include('includes/forgotpassword.php');?>

<!-- Scripts --> 

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
<!--Switcher-->
<script src="assets/switcher/js/switcher.js"></script>
<!--bootstrap-slider-JS-->
<script src="assets/js/bootstrap-slider.min.js"></script>
<!--Slider-JS-->
<script src="assets/js/slick.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
</body>
<!--Google Maps API--> 

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5PPR2NTq1Q8W3oQQB5EBBP5dc0DaDO2I&libraries=places&callback=myMap" async differ></script>
<script type="text/javascript">
  var distances;
  
  function myMap() {
    var element = document.getElementById("map");
    var map = new google.maps.Map(element, {
        center: new google.maps.LatLng(48.1391, 11.5802),
        zoom: 13,
        mapTypeId: "OSM",
        mapTypeControlOptions: {
            mapTypeIds: ["OSM"]
        },
        streetViewControl: false
    });
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(48.1391, 11.5802),
      map: map,
      icon: {
        url: 'https://img.icons8.com/plasticine/100/000000/marker.png', 
        scaledSize: new google.maps.Size(50,50),
        origin: new google.maps.Point(0,0),
        anchor: new google.maps.Point(0,0)
      },
      title: 'Current Location'
    });
    map.mapTypes.set("OSM", new google.maps.ImageMapType({
      getTileUrl: function(coord, zoom) {
          // See above example if you need smooth wrapping at 180th meridian
          return "https://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
      },
      tileSize: new google.maps.Size(256, 256),
      name: "OpenStreetMap",
      maxZoom: 30
    }));
    var infoWindow = new google.maps.InfoWindow;
    // LENDER MAP
    var element_lender = document.getElementById("lenderMap");
    var map_lender = new google.maps.Map(element_lender, {
        center: new google.maps.LatLng(48.1391, 11.5802),
        zoom: 13,
        mapTypeId: "OSM",
        mapTypeControlOptions: {
            mapTypeIds: ["OSM"]
        },
        streetViewControl: true
    });
    var marker_lender = new google.maps.Marker({
      position: new google.maps.LatLng(48.1391, 11.5802),
      map: map_lender,
      icon: {
        url: 'https://img.icons8.com/plasticine/100/000000/marker.png', 
        scaledSize: new google.maps.Size(50,50),
        origin: new google.maps.Point(0,0),
        anchor: new google.maps.Point(0,0)
      },
      title: 'Current Location',
      draggable: true
    });
    map_lender.mapTypes.set("OSM", new google.maps.ImageMapType({
      getTileUrl: function(coord, zoom) {
          // See above example if you need smooth wrapping at 180th meridian
          return "https://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
      },
      tileSize: new google.maps.Size(256, 256),
      name: "OpenStreetMap",
      maxZoom: 30
    }));
    
    var geocoder_lender = new google.maps.Geocoder;
    var infoWindow_lender = new google.maps.InfoWindow;
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        marker.setMap(null);
        marker = new google.maps.Marker({
          position: pos,
          map: map,
          icon: {
            url: 'https://img.icons8.com/plasticine/100/000000/marker.png', 
            scaledSize: new google.maps.Size(50,50)
          },
          title: 'Current Location'
        });
        
        infoWindow.setPosition(pos);
        infoWindow.setContent('Current Location');
        infoWindow.open(map);
        map.setCenter(pos);
        map.setZoom(13);
        $.ajax({
            type: "GET",
            url: "haversine_algorithm.php",
            data: {
              lat: marker.getPosition().lat(),
              lng: marker.getPosition().lng()
            },
            success: function (data) {
              document.getElementById('lat').value = marker.getPosition().lat();
              document.getElementById('lng').value = marker.getPosition().lng();
              distances = JSON.parse(data);
              $("#click-trigger").click();
            }
        });
        // GET LOCATION OF CUSTOMER AND SEND TO LENDER
        <?php
          if(isset($_SESSION['login'])) {
        
            $sql = "SELECT booking.id as booking_id, tblusage.start as usage_start FROM tblbooking booking INNER JOIN tblusage ON tblusage.booking_id = booking.id WHERE booking.userEmail=:useremail AND booking.Status = 1 and tblusage.start = 1 AND tblusage.confirmation  = 1;";
            $query = $dbh->prepare($sql);
            $query->bindParam(':useremail',$_SESSION['login'], PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);
          ?>
            console.log('Row Count', <?php echo $query->rowCount(); ?>);
          <?php
            for($i = 0; $i < $query->rowCount(); $i++) {
              $sql_check_location_inserted = "SELECT booking_id FROM tbllocation WHERE booking_id = :booking_id";
              $query_check_location_inserted = $dbh->prepare($sql_check_location_inserted);
              $query_check_location_inserted->bindParam(':booking_id',$result[$i]->booking_id, PDO::PARAM_STR);
              $query_check_location_inserted->execute();
        ?>
              $.ajax({
                type: "POST",
                url: "get_location.php",
                data: {
                  lat: marker.getPosition().lat(),
                  lng: marker.getPosition().lng(),
                  booking_id: <?php echo $result[$i]->booking_id; ?>
                },
                success: function(data) {
                  console.log('Location successfully registered', data);
                }
              });
        <?php
            }
          }
        ?>
        var pos_lender = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        marker_lender.setMap(null);
        marker_lender = new google.maps.Marker({
          position: pos_lender,
          map: map_lender,
          icon: {
            url: 'https://img.icons8.com/plasticine/100/000000/marker.png', 
            scaledSize: new google.maps.Size(50,50)
          },
          title: 'Current Location',
          draggable: true
        });
        
        infoWindow_lender.setPosition(pos_lender);
        infoWindow_lender.setContent('Current Location');
        infoWindow_lender.open(map_lender);
        map_lender.setCenter(pos_lender);
        map_lender.setZoom(13);
        //   GEOCODER
				getAddress(geocoder_lender, marker_lender);
        google.maps.event.addListener(marker_lender, 'dragend', function() {
					getAddress(geocoder_lender, marker_lender);
				});
      }, function() {
        handleLocationError(true, infoWindow, map.getCenter());
      });
      
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }
    // MULTIPLE MARKERS
    var marker_vehicles;
    <?php
      $sql = "SELECT vehicle.*,user.*, user.id as u_id, brands.BrandName,(SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) from tblvehicles vehicle inner join tblbrands brands on brands.id=vehicle.VehiclesBrand inner join tblusers user on vehicle.user_id = user.id left join tblbooking booking on vehicle.id = booking.VehicleId WHERE (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) != 1 or (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) is null and user.verified_at is not null group by u_id";
      $query = $dbh -> prepare($sql);
      $query->execute();
      $count=$query->rowCount();
      $result = $query->fetchAll(PDO::FETCH_OBJ);
    ?>
    var i = 0;
    
    <?php
      $vehicle_count = 0;
      foreach($result as $vehicle){
        
    ?>
        var markers_position = new google.maps.LatLng(<?php echo $vehicle->lat; ?>, <?php echo $vehicle->lng; ?>);
        marker_vehicles = new google.maps.Marker({
          position: markers_position,
          map:map,
          icon: {
            url: 'https://img.icons8.com/ultraviolet/64/000000/car-rental.png',
            scaledSize: new google.maps.Size(50,50)
          },
          draggable: false
        });
       
        google.maps.event.addListener(marker_vehicles, 'click', (function(marker_vehicles, i){
          return function() {
            var contentString = '<div class="container text-center" id="infoWindowContainer" style="width:300px;">'
            +'<p style="font-size: 14px;"><span class="fa fa-address-card"></span> <strong>Lender: <?php echo $vehicle->FullName; ?></strong> <span class="fa fa-check-circle" style="color: green;"></span></p>';
            <?php
            $sql_inner = "SELECT user.*, vehicle.*,brands.BrandName,(SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) from tblusers user inner join tblvehicles vehicle on user.id = vehicle.user_id inner join tblbrands brands on brands.id=vehicle.VehiclesBrand left join tblbooking booking on vehicle.id = booking.VehicleId WHERE user.id = :id and ((SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) != 1 or (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) is null) and user.verified_at is not null group by vehicle.id";
            $query_inner = $dbh->prepare($sql_inner);
            $query_inner->bindParam(':id',$vehicle->user_id, PDO::PARAM_STR);
            $query_inner->execute();
            $result_inner = $query_inner->fetchAll(PDO::FETCH_OBJ);
            foreach($result_inner as $vehicle_inner) {
            ?>
              contentString+='<img style="width:100%;" src="admin/img/vehicleimages/<?php echo htmlentities($vehicle_inner->Vimage1) ?>" id="car_image">'
              
              +'<hr>'
              +'<p><strong><?php echo $vehicle_inner->VehiclesTitle ?></strong></p>'
              +'<p id="vehicle_details">&#187;<?php echo htmlentities($vehicle_inner->BrandName) ?>&nbsp;Brand</p>'
              +'<p id="vehicle_details">&#187;<?php echo htmlentities($vehicle_inner->FuelType) ?>&nbsp;Type</p>'
              +'<p id="vehicle_details">&#187;<?php echo htmlentities($vehicle_inner->SeatingCapacity) ?> seater vehicle</p>'
              +'<p id="vehicle_details">&#187;&nbsp;&#8369; <?php echo number_format(htmlentities($vehicle_inner->PricePerDay),2) ?>/day</p>'
              +'<hr><p id="vehicle_details">'+ distances[<?php echo $vehicle_count; ?>]["distance"]+'KM away</p>'
              +'<a href="vehical-details.php?vhid=<?php echo htmlentities($vehicle_inner->id);?>" class="btn btn-primary btn-xs" id="btn_view_details">View Details >></a><br>';
             
            <?php
             $vehicle_count++;
            }
            ?>
            contentString+='</div>'
            infoWindow.setContent(contentString);
            infoWindow.open(map, marker_vehicles);
            map.setCenter(marker_vehicles.getPosition());
            map.setZoom(17);
          }
        })(marker_vehicles, i));
        
    <?php
         
      }
    ?>
		var defaultBounds_lender = new google.maps.LatLngBounds(
			new google.maps.LatLng(7.3042, 126.0893),
		);
		var options_lender = {
			bounds: defaultBounds_lender
		};
		
		// AUTOCOMPLETE OF SEARCHBOX AND PLOTTING OF MARKER
		var searchBox = new google.maps.places.SearchBox(document.getElementById('lender-autocomplete-address'),options_lender);
		google.maps.event.addListener(searchBox, 'places_changed', function() {
			var places = searchBox.getPlaces();
			var bounds = new google.maps.LatLngBounds();
			var i, place;
			for (i=0; place=places[i];i++) {
				console.log(place.geometry.location);
				bounds.extend(place.geometry.location);
				marker_lender.setPosition(place.geometry.location);
        getAddress(geocoder_lender, marker_lender);
				map_lender.fitBounds(bounds);
				map_lender.setZoom(17);
			}
		});
  }
  function getAddress(geocoder_lender, marker) {
    var latLng = {lat: marker.getPosition().lat(), lng: marker.getPosition().lng()};
		geocoder_lender.geocode({'location': latLng}, function(results,status) {
			if (status == 'OK') {
				if (results[0]) {
					// String address
					var address = results[0].formatted_address;
					document.getElementById('lender-autocomplete-address').value = address;
          document.getElementById('lender_lat').value = marker.getPosition().lat();
          document.getElementById('lender_lng').value = marker.getPosition().lng();
          // console.log("Latitude: " + latLng['lat'] + "; Longitude: " + latLng['lng']);
				}else{
					alert('No Results found');
				}
			}else{
				alert('Geocoder not supported. ' + status);
			}
		});
  }
  function changeResults() {
    var sort_distance;
    // SORT SEARCH RESULT
    $.ajax({
        type: "GET",
        url: "haversine_algorithm.php",
        data: {
          lat: document.getElementById('lat').value,
          lng: document.getElementById('lng').value
        },
        success: function (data) {
          sort_distance = JSON.parse(data);
          // SORT DISTANCE
          sort_distance.sort(sortByProperty("distance"));
          console.log(sort_distance);
          <?php 
            //Query for Listing count
            $sql = "SELECT vehicle.*, vehicle.id as v_id, user.*, user.id as u_id, brands.BrandName,(SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) from tblvehicles vehicle inner join tblbrands brands on brands.id=vehicle.VehiclesBrand inner join tblusers user on vehicle.user_id = user.id left join tblbooking booking on vehicle.id = booking.VehicleId WHERE (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) != 1 or (SELECT status from tblbooking WHERE VehicleId=vehicle.id order by id desc limit 1) is null and user.verified_at is not null group by vehicle.VehiclesTitle;";
            $query = $dbh -> prepare($sql);
            $query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=$query->rowCount();
          ?>
          var changeResults = ''
          +'<div class="result-sorting-wrapper">'
          +'<div class="sorting-count">'
          +'<p><span><?php echo htmlentities($cnt);?> Listings</span></p>'
          +'</div>'
          +'</div>';
          
          // INSERT LISTINGS ON PARENT CONTAINER
          var index_count = 0;
          for (index_count = 0; index_count < sort_distance.length; index_count++) {
            changeResults = changeResults
            +'<div class="product-listing-m gray-bg">'
            +'<div class="product-listing-img"><img src="admin/img/vehicleimages/'+sort_distance[index_count]["image"]+'" class="img-responsive" alt="Image" /> </a> '
            +'</div>'
            +'<div class="product-listing-content">'
            +'<h5><a href="vehical-details.php?vhid='+sort_distance[index_count]["id"]+'">'+sort_distance[index_count]["brand"]+', '+sort_distance[index_count]["vehicle"]+'</a></h5>'
            +'<p class="list-price">&#8369; '+sort_distance[index_count]["price"]+'</p>'
            +'<ul>'
            +'<li><i class="fa fa-user" aria-hidden="true"></i>'+sort_distance[index_count]["Seats"]+' seats</li>'
            +'<li><i class="fa fa-calendar" aria-hidden="true"></i>'+sort_distance[index_count]["model"]+' model</li>'
            +'<li><i class="fa fa-car" aria-hidden="true"></i>'+sort_distance[index_count]["fuel"]+'</li>'
            +'</ul>'
            +'<a href="vehical-details.php?vhid='+sort_distance[index_count]["id"]+'" class="btn">View Details <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></a>'
            +'</div>'
            +'</div>';
            
          }
          
          changeResults = changeResults + '</div>';
          $('#insert-rows').html(changeResults);
        }
    });  
  }
  // SORT FUNCTION FOR SORTING DISTANCES
  function sortByProperty(property){  
    return function(a,b){  
        if(a[property] > b[property])  
          return 1;  
        else if(a[property] < b[property])  
          return -1;  
    
        return 0;  
    }  
  }
  $("#click-trigger").on('click', function() {
    changeResults();
  });
  <?php
    if(isset($_SESSION['login'])){
      $sql = "SELECT booking.id as booking_id, booking.userEmail as user_email, booking.VehicleId as VehicleId, booking.FromDate as FromDate, booking.ToDate as ToDate, booking.message as message, booking.Status as Status, user.id as u_id, user.Fullname as Fullname, user.EmailId as EmailId, vehicle.id as vehicle_id, vehicle.VehiclesTitle as VehiclesTitle, brand.BrandName as BrandName, vehicle.PricePerDay as PricePerDay, vehicle.FuelType as FuelType, vehicle.ModelYear as ModelYear, vehicle.SeatingCapacity as SeatingCapacity FROM tblbooking booking INNER JOIN tblusers user ON booking.userEmail = user.EmailId INNER JOIN tblvehicles vehicle ON booking.VehicleId = vehicle.id INNER JOIN tblbrands brand on brand.id = vehicle.VehiclesBrand WHERE booking.userEmail = :email_id AND booking.Status = 1 AND DATEDIFF(booking.FromDate, curdate()) <= 7;";
      $query = $dbh -> prepare($sql);
      $query->bindParam(':email_id',$_SESSION['login'], PDO::PARAM_STR);
      $query->execute();
      $result = $query->fetchAll(PDO::FETCH_OBJ);
      if($query->rowCount() != 0) {
        $open_modal = false;
        for($index = 0; $index < $query->rowCount(); $index++) {
          $sql_confirm_modal = "SELECT booking_id FROM tblconfirmation WHERE booking_id = :booking_id";
          $query_confirm_modal = $dbh -> prepare($sql_confirm_modal);
          $query_confirm_modal->bindParam(':booking_id',$result[$index]->booking_id, PDO::PARAM_STR);
          $query_confirm_modal->execute();
          if($query_confirm_modal->rowCount() == 0) {
            $open_modal = true;
          }
          
        }
        if($open_modal) {
  ?>
          $("#trigger-confirmation-modal").click();
  <?php
        }
  ?>      
          
    <?php
        }
    ?>    
        
    <?php
      
    }
    ?>
    // ON CLICK ACTION WHEN CLICKING CONFIRM/CANCEL BUTTONS
    <?php
      $sql = "SELECT booking.id as booking_id, booking.userEmail as user_email, booking.VehicleId as VehicleId, booking.FromDate as FromDate, booking.ToDate as ToDate, booking.message as message, booking.Status as Status, user.id as u_id, user.Fullname as Fullname, user.EmailId as EmailId, vehicle.id as vehicle_id, vehicle.VehiclesTitle as VehiclesTitle, brand.BrandName as BrandName, vehicle.PricePerDay as PricePerDay, vehicle.FuelType as FuelType, vehicle.ModelYear as ModelYear, vehicle.SeatingCapacity as SeatingCapacity FROM tblbooking booking INNER JOIN tblusers user ON booking.userEmail = user.EmailId INNER JOIN tblvehicles vehicle ON booking.VehicleId = vehicle.id INNER JOIN tblbrands brand on brand.id = vehicle.VehiclesBrand WHERE booking.userEmail = :email_id AND booking.Status = 1 AND DATEDIFF(booking.FromDate, curdate()) <= 7;";
      $query = $dbh -> prepare($sql);
      $query->bindParam(':email_id',$_SESSION['login'], PDO::PARAM_STR);
      $query->execute();
      $result = $query->fetchAll(PDO::FETCH_OBJ);
      
      for($index = 0; $index < $query->rowCount(); $index++) {
    ?>
        $("#confirm_rent_confirm_<?php echo $result[$index]->booking_id; ?>").on('click', function() {
          $.ajax({
              type: "POST",
              url: "confirm_rental.php",
              data: {
                booking_id: $("#confirm_rent_confirm_<?php echo $result[$index]->booking_id;?>").attr("value"),
                cancel_reservation: 0
              },
              success: function (data) {
                console.log(data);
                alert("You have confirmed your vehicle reservation." );
                window.location = window.location.pathname;
              }
          });
        });
        $("#confirm_rent_cancel_<?php echo $result[$index]->booking_id; ?>").on('click', function() {
         $.ajax({
              type: "POST",
              url: "confirm_rental.php",
              data: {
                booking_id: $("#confirm_rent_cancel_<?php echo $result[$index]->booking_id; ?>").attr("value"),
                cancel_reservation: 1
              },
              success: function (data) {
                console.log(data);
                alert("You have cancelled your vehicle reservation.");
                window.location = window.location.pathname;
              }
          });
        });
    <?php
      }
    ?>
  
</script>

<!--Dropdown transition of View Rentals' Location button--> 
<script>
  function dropDownMap() {
    var map = document.getElementById('map-container');
    // get back to 535px
    if (map.style.height != '535px') {
      map.style.transition = 'all 1s ease-out';
      map.style.height = '535px';
    }else{
      map.style.transition = 'all 1s ease-out';
      map.style.height = '80px';
    }
  }

</script>
</html>
