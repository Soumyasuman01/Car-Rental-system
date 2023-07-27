<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  {
header('location:index.php');
}
else{
?><!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>EZRent | My Booking</title>
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
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
<!-- Google-Font-->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">


<link rel="stylesheet" href="assets/css/rate-owner.css">
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet"> 
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script src="onesignal.js"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>

<!-- Start Switcher -->
<?php //include('includes/colorswitcher.php');?>
<!-- /Switcher -->

<!--Header-->
<?php include('includes/header.php');?>
<!--Page Header-->
<!-- /Header -->

<!--Page Header-->
<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>My Booking</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li>My Booking</li>
      </ul>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header-->

<?php
$useremail=$_SESSION['login'];
$sql = "SELECT * from tblusers where EmailId=:useremail";
$query = $dbh -> prepare($sql);
$query -> bindParam(':useremail',$useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{ }}?>
<section class="user_profile inner_pages">
  
    <div class="row">
      <div class="col-md-3 col-sm-3">
       <?php include('includes/sidebar.php');?>

      <div class="col-md-6 col-sm-8">
        <div class="profile_wrap">
          <h5 class="uppercase">My Bookings </h5>
          <div class="my_vehicles_list">
            <ul class="vehicle_listing">
<?php
$useremail=$_SESSION['login'];
 $sql = "SELECT tblvehicles.Vimage1 as Vimage1,tblvehicles.VehiclesTitle,tblvehicles.id as vid,tblbrands.BrandName, tblbooking.id as booking_id,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.Status  from tblbooking join tblvehicles on tblbooking.VehicleId=tblvehicles.id join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblbooking.userEmail=:useremail order by tblbooking.id desc";
$query = $dbh -> prepare($sql);
$query-> bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  ?>

<li>
                <div class="vehicle_img"> <a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid);?>""><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="image"></a> </div>
                <div class="vehicle_title">
                  <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid);?>""> <?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h6>
                  <p><b>From Date:</b> <?php echo htmlentities($result->FromDate);?><br /> <b>To Date:</b> <?php echo htmlentities($result->ToDate);?></p>
                  <?php

                  // Get interval of from date and current date (2 days prior)
                    $from_date = new DateTime($result->FromDate);
                    $now = new DateTime();
                    $interval = $from_date->diff($now)->format("%a");

                    if($interval <= 2 && $result->Status==1) {
                  ?>
                  <a href="my-booking.php?cancel_booking_id=<?php echo htmlentities($result->booking_id); ?>" id="btn_cancel_booking" class="btn btn-sm">Cancel Booking</a>
                  <?php
                    }
                  ?>
                  
                </div>
          <?php if($result->Status==1){ 
                  $sql_usage = "SELECT * FROM tblusage WHERE booking_id = :booking_id and start = 1";
                  $query_usage = $dbh->prepare($sql_usage);
                  $query_usage->bindParam(':booking_id', $result->booking_id, PDO::PARAM_STR);
                  $query_usage->execute();
                  $result_usage = $query_usage->fetchAll(PDO::FETCH_OBJ);
                  if($query_usage->rowCount() > 0) {
                    if($result_usage[0]->confirmation == 0){
          ?>
                      <div class="vehicle_status">
                        <a href="my-booking.php?usage_id=<?php echo $result->booking_id; ?>" class="btn primary btn-xs" style="color: white;">Confirm Usage</a>
                        <div class="clearfix">
                      </div>
          <?php
                    }else{
          ?>
                    <div class="vehicle_status">
                      <a href="#" class="btn outline btn-xs disabled">Usage on-going</a>
                      <div class="clearfix">
                    </div>
          <?php
                    }
                  }else{
          ?>
                  <div class="vehicle_status">
                    <a href="#" class="btn outline btn-xs disabled">Confirmed</a>
                    <div class="clearfix">
                  </div>
          <?php
                  }
         ?>
                
        </div>

              <?php } else if($result->Status==2) { ?>
 <div class="vehicle_status"> <a href="#" class="btn outline btn-xs disabled">Cancelled</a>
            <div class="clearfix"></div>
        </div>



                <?php } else if($result->Status == 3) { 
                  $sql_rating = "SELECT booking_id, rating from tblratings where booking_id=:booking_id and type = 1;";
                  $query_rating = $dbh -> prepare($sql_rating);
                  $query_rating -> bindParam(':booking_id',$result->booking_id, PDO::PARAM_STR);
                  $query_rating->execute();	
                  $result_rating = $query_rating->fetchAll(PDO::FETCH_OBJ);

                  if($query_rating->rowCount() === 0) {
                ?>
                    <div class="vehicle_status"> <a href="my-booking.php?booking_id=<?php echo htmlentities($result->booking_id); ?>" class="btn btn-primary btn-xs" style="color:#fbfcfc;">Rate Owner</a>
                      <button type="button" id="rate_owner" style="visibility:hidden;" data-toggle="modal" data-target="#rateOwnerModal">Click me</button>
                      <div class="clearfix"></div>
                    </div>
                <?php
                  }else{
                ?>
                <p> Rating:&nbsp;&nbsp;
                    <?php
                      for($rating_count = 0; $rating_count < $result_rating[0]->rating; $rating_count++) {
                    ?>
                        <span class="glyphicon glyphicon-star"></span>
                    <?php
                      }
                    ?>
                    <strong></p>
                    
                <?php
                  }
                ?>
                  
                       
                <?php }else{
                ?>
                <div class="vehicle_status"> <a href="#" class="btn outline btn-xs disabled">Pending</a>
                    <div class="clearfix"></div>
                </div>
                <?php
                } ?>
       <div style="float: left"><p><b>Message:</b> <?php echo htmlentities($result->message);?> </p></div>
              </li>
              <?php }} ?>


            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- RATE OWNER MODAL -->
<!-- MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="rateOwnerModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="font-family: 'Montserrat', sans-serif;">
    <div class="modal-body text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="font-size: 35px;">&times;</span>
        </button>

    <span class="glyphicon glyphicon-user" aria-hidden="true" style="font-size: 50px; padding-top: 40px; padding-left: 20px;"></span>
    
      <h4 class="display-5 text-center" style="padding-top: 10px;">Rate Owner</h4>
      <p style="font-size: 15px;">Thank you for trusting EZrent! Kindly rate the owner of the vehicle.</p>
      <?php
        $sql = "SELECT user.FullName, booking.userEmail FROM tblusers user INNER JOIN tblbooking booking on user.EmailId = booking.userEmail WHERE booking.id = :rate_id;";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rate_id',$_REQUEST['booking_id'], PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
      ?>
      
      <div class="rating" style="padding-top: 20px; padding-bottom: 20px;">
      <button type="button" class="btn btn-warning btn-sm rateButton" aria-label="Left Align" name="first_star" value="first_star" id="first_star">
        <span class="glyphicon glyphicon-star" aria-hidden="true" style="font-size: 20px; padding-top: 2px;"></span>
      </button>
      <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align" name="second_star" value="second_star" id="second_star">
        <span class="glyphicon glyphicon-star" aria-hidden="true" style="font-size: 20px; padding-top: 2px;"></span>
      </button>
      <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align" name="third_star" value="third_star" id="third_star">
        <span class="glyphicon glyphicon-star" aria-hidden="true" style="font-size: 20px; padding-top: 2px;"></span>
      </button>
      <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align" name="fourth_star" value="fourth_star" id="fourth_star">
        <span class="glyphicon glyphicon-star" aria-hidden="true" style="font-size: 20px; padding-top: 2px;"></span>
      </button>
      <button type="button" class="btn btn-default btn-grey btn-sm rateButton" aria-label="Left Align" name="fifth_star" value="fifth_star" id="fifth_star">
        <span class="glyphicon glyphicon-star" aria-hidden="true" style="font-size: 20px; padding-top: 2px;"></span>
      </button>
      </div>
    </div>
    <br>
    <button type="button" class="btn btn-primary" name="submit_rating" value="<?php echo $_REQUEST['booking_id']; ?>" id="submit_rating" onclick="submitOwnerRating();" style="display:block;width:100%;margin:auto;font-size:14px;">Submit Rating</button>
    
    
      
    
    </div>
  </div>
</div>
<!--/my-vehicles-->
<?php include('includes/footer.php');?>

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
<script>
// SCRIPT FOR RATING OWNER
<?php
  if(isset($_REQUEST['booking_id'])){
?>
    $.ajax({
      type: "GET",
      url: "my-booking.php?booking_id=<?php echo $_REQUEST['booking_id'];?>",
      success: function(data) {
        $("#rate_owner").click();
      }
    });
<?php


    $sql = "SELECT user.id as user_id FROM tblbooking booking INNER JOIN tblvehicles vehicle ON booking.VehicleId = vehicle.id INNER JOIN tblusers user ON vehicle.user_id = user.id WHERE booking.id = :booking_id;";
    $query = $dbh->prepare($sql);
    $query->bindParam(':booking_id', $_REQUEST['usage_id'], PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    // SEND NOTIF TO LENDER
    $_SESSION['lenderid'] = $results[0]->user_id;
    $_SESSION['renter_notification_message'] = 'Renter has confirmed the usage';
    include('includes/one-signal.php');
  }

  if(isset($_REQUEST['usage_id'])){
?>
    $.ajax({
      type: "POST",
      url: "confirm-usage.php",
      data: {
        booking_id: "<?php echo $_REQUEST['usage_id']; ?>"
      },
      success: function(data) {
        alert(data);
        window.location = window.location.pathname;
      }
    });
    
<?php
  }
?>
</script>
<!-- SCRIPT IN MOVING RATINGS -->
<script>
  $('#first_star').click(function (event) {

    // Don't follow the link
    event.preventDefault();

    // Log the clicked element in the console
    document.getElementById('first_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('second_star').className = "btn btn-default btn-sm rateButton";
    document.getElementById('third_star').className = 'btn btn-default btn-sm rateButton';
    document.getElementById('fourth_star').className = 'btn btn-default btn-sm rateButton';
    document.getElementById('fifth_star').className = 'btn btn-default btn-sm rateButton';
    
  });
  $('#second_star').click(function (event) {

    // Don't follow the link
    event.preventDefault();

    // Log the clicked element in the console
    document.getElementById('first_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('second_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('third_star').className = 'btn btn-default btn-sm rateButton';
    document.getElementById('fourth_star').className = 'btn btn-default btn-sm rateButton';
    document.getElementById('fifth_star').className = 'btn btn-default btn-sm rateButton';
    
  });
  $('#third_star').click(function (event) {

    // Don't follow the link
    event.preventDefault();

    // Log the clicked element in the console
    document.getElementById('first_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('second_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('third_star').className = 'btn btn-warning btn-sm rateButtonChanged';
    document.getElementById('fourth_star').className = 'btn btn-default btn-sm rateButton';
    document.getElementById('fifth_star').className = 'btn btn-default btn-sm rateButton';
    
  });
  $('#fourth_star').click(function (event) {

    // Don't follow the link
    event.preventDefault();

    // Log the clicked element in the console
    document.getElementById('first_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('second_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('third_star').className = 'btn btn-warning btn-sm rateButtonChanged';
    document.getElementById('fourth_star').className = 'btn btn-warning btn-sm rateButtonChanged';
    document.getElementById('fifth_star').className = 'btn btn-default btn-sm rateButton';
    
  });
  $('#fifth_star').click(function (event) {

    // Don't follow the link
    event.preventDefault();

    // Log the clicked element in the console
    document.getElementById('first_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('second_star').className = "btn btn-warning btn-sm rateButtonChanged";
    document.getElementById('third_star').className = 'btn btn-warning btn-sm rateButtonChanged';
    document.getElementById('fourth_star').className = 'btn btn-warning btn-sm rateButtonChanged';
    document.getElementById('fifth_star').className = 'btn btn-warning btn-sm rateButtonChanged';
    
  });
</script>
<script>
  var rating = 0;
  function submitOwnerRating() {
    if (document.getElementById('first_star').className === "btn btn-warning btn-sm rateButtonChanged") {
      rating = 1;
    }
    if (document.getElementById('second_star').className === "btn btn-warning btn-sm rateButtonChanged") {
      rating = 2;
    }
    if (document.getElementById('third_star').className === "btn btn-warning btn-sm rateButtonChanged") {
      rating = 3;
    }
    if (document.getElementById('fourth_star').className === "btn btn-warning btn-sm rateButtonChanged") {
      rating = 4;
    }
    if (document.getElementById('fifth_star').className === "btn btn-warning btn-sm rateButtonChanged") {
      rating = 5;
    }

    <?php
       $sql = "SELECT vehicle.user_id as vehicle_user_id FROM tblvehicles vehicle INNER JOIN tblbooking booking ON vehicle.id = booking.VehicleId WHERE booking.id = :booking_id;";
       $query = $dbh->prepare($sql);
       $query->bindParam(':booking_id', $_REQUEST['booking_id'], PDO::PARAM_STR);
       $query->execute();
       $results=$query->fetchAll(PDO::FETCH_OBJ);
    ?>
    //alert("Rating: " + rating + "; Lender: " + <?php echo $results[0]->vehicle_user_id;?> + "; Booking ID: " + <?php echo $_REQUEST['booking_id']; ?>);
    $.ajax({
      type: "POST",
      url: "insert_rating.php",
      data: {
        rating: rating,
        rental: "<?php echo $results[0]->vehicle_user_id;?>",
        booking_id: "<?php echo $_REQUEST['booking_id']; ?>",
        rate_type: 1
      },
      success: function(data) {
        console.log(data);
        window.location = window.location.pathname;
      }
    });
  }
</script>
<script>
  <?php
    if(isset($_REQUEST['cancel_booking_id'])) {
    // Cancel booking

      $sql = "UPDATE tblbooking SET status = 2 WHERE id = :booking_id";
      $query = $dbh->prepare($sql);
      $query->bindParam(':booking_id', $_REQUEST['cancel_booking_id'], PDO::PARAM_STR);
      $status = $query->execute();

      if($status) {
  ?>
      alert('Your have successfully cancelled your booking.');
      window.location = window.location.href.split("?")[0];
  <?php
      }else{
  ?>
      alert('An error had occured.');
  <?php
      }
    }
  ?>
</script>
</body>
</html>
<?php } ?>
