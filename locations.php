<?php
session_start();
error_reporting(0);
include('includes/config.php');
	if(strlen($_SESSION['login'])==0 || ($_SESSION['utype'] != 0)) 
	{	
header('location:index.php');
}else{


 ?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	
	<title>EZRent | View Locations   </title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="admin/css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="admin/css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="admin/css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="admin/css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="admin/css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="admin/css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="admin/css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="admin/css/style.css">
	<link rel="stylesheet" href="admin/css/viewlocations.css">
	<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
	<script src="onesignal.js"></script>
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>
		

</head>

<body>

	<?php include('alt_includes/header.php');?>
	
	<div class="ts-main-content" style="padding:50px;">
		<div class="container-text">
		</div>
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">View Customers Location</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default" style=" width:100%; overflow-x:scroll;">
							<div class="panel-heading">Location Details</div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Vehicle</th>
											<th>Customer Name</th>
											<th>Booking Date</th>
											<th>Return Date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>#</th>
											<th>Vehicle</th>
											<th>Customer Name</th>
											<th>Booking Date</th>
											<th>Return Date</th>
											<th>Action</th>
											
										</tr>
									</tfoot>
									<tbody>

									<?php $sql = "SELECT location.booking_id, user.FullName, booking.userEmail, vehicle.VehiclesTitle, booking.FromDate, booking.ToDate, booking.Status as booking_status FROM tbllocation location INNER JOIN tblbooking booking ON location.booking_id = booking.id INNER JOIN tblvehicles vehicle ON booking.VehicleId = vehicle.id INNER JOIN tblusers user ON user.EmailId = booking.userEmail WHERE vehicle.user_id = :user_id order by booking_id desc";
										$query = $dbh -> prepare($sql);
										$query->bindParam(':user_id',$_SESSION['uid'], PDO::PARAM_STR);
										$query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $count = 1;
                                        if($query->rowCount() > 0)
                                        {
                                        foreach($results as $result)
                                        {				
    ?>	
										<tr>
											<td><?php echo $count;?></td>
											<td><?php echo htmlentities($result->VehiclesTitle);?></td>
											<td><?php echo htmlentities($result->FullName);?></td>
											<td><?php echo htmlentities($result->FromDate);?></td>
											<td><?php echo htmlentities($result->ToDate);?></td>
                                            <td>
												<?php
													if($result->booking_status == 3) {
												?>
													<p>Vehicle already retuned.</p>
												<?php
													}else{
														
												?>
														<a href="locations.php?booking_id=<?php echo htmlentities		($result->booking_id); ?>" class="btn btn-info btn-sm" 	id="btnLocation">
														<span class="fa fa-map-pin"></span>&nbsp; View Location</a>
														<button type="button" id="trigger-me-location" style="visibility:hidden;" data-toggle="modal" data-target="#locationModal">Click me</button>
												<?php
													}
												?>
                                                
                                            </td>
										</tr>
										
										<?php
												$count++; 
											}
                                        } 
                                        ?>
										
									</tbody>
								</table>

						

							</div>
						</div>

					

					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- MODAL FOR VIEWING LOCATIONS -->
	<div class="modal fade w-100" tabindex="-1" role="dialog" id="locationModal" aria-hidden="true">
		<div class="modal-dialog" role="document" id="locationDialog">
			<div class="modal-content">
				<div class="modal-body text-center" id="locationBody">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" style="font-size: 25px;">&times;</span></button>
					<span class="fa fa-map-pin" style="font-size: 35px; padding-top: 25px; padding-bottom:0"></span>
					<h3 class="display-4" style="padding-bottom: 15px;">Customer Location</h3>
					<div class="row">
						<div class="col-md-3" style="padding-top: 15px;">
							<p><strong>Current Location</strong></p>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control" id="currentLocation" disabled>
						</div>
					</div>
					<div id="myMap"></div>
				</div>
			</div>
		</div>
	</div>							
	<!-- END OF MODAL -->
	
	<!-- Loading Scripts -->
	<script src="admin/js/jquery.min.js"></script>
	<script src="admin/js/bootstrap-select.min.js"></script>
	<script src="admin/js/bootstrap.min.js"></script>
	<script src="admin/js/jquery.dataTables.min.js"></script>
	<script src="admin/js/dataTables.bootstrap.min.js"></script>
	<script src="admin/js/Chart.min.js"></script>
	<script src="admin/js/fileinput.js"></script>
	<script src="admin/js/chartData.js"></script>
	<script src="admin/js/main.js"></script>
</body>
<!-- GOOGLE MAPS API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5PPR2NTq1Q8W3oQQB5EBBP5dc0DaDO2I&callback=myMap" async differ></script>
	<script type="text/javascript">
		function myMap() {
			// GET LATITUDE AND LONGITUDE OF USER
			<?php
				$latitude = 48.1391;
				$longitude = 11.5802;
				if(isset($_REQUEST['booking_id'])){
					$sql = "SELECT lat, lng FROM tbllocation WHERE booking_id = :booking_id;";
					$query = $dbh->prepare($sql);
					$query->bindParam(':booking_id',$_REQUEST['booking_id'], PDO::PARAM_STR);
					$query->execute();
					$result = $query->fetchAll(PDO::FETCH_OBJ);

					$latitude = $result[0]->lat;
					$longitude = $result[0]->lng;
				}
			?>

			var element = document.getElementById("myMap");

			var map = new google.maps.Map(element, {
				center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
				zoom: 11,
				mapTypeId: "OSM",
				mapTypeControlOptions: {
					mapTypeIds: ["OSM"]
				},
				streetViewControl: false
			});
			
			map.mapTypes.set("OSM", new google.maps.ImageMapType({
				getTileUrl: function(coord, zoom) {
					// See above example if you need smooth wrapping at 180th meridian
					return "https://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
				},
				tileSize: new google.maps.Size(256, 256),
				name: "OpenStreetMap",
				maxZoom: 18
			}));
			var infoWindow = new google.maps.InfoWindow;
			var marker;
			var position = {
				lat: <?php echo $latitude; ?>, 
				lng: <?php echo $longitude; ?>
			};
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(position['lat'], position['lng']),
				map: map
			});
			map.setCenter(new google.maps.LatLng(position['lat'], position['lng']));
			map.setZoom(15);	
				
			var defaultBounds = new google.maps.LatLngBounds(
				new google.maps.LatLng(position['lat'], position['lng']),
			);

			var options = {
				bounds: defaultBounds
			};

			var geocoder = new google.maps.Geocoder;
			getAddress(geocoder, marker);

		}

		function getAddress(geocoder, marker) {
			var latLng = {lat: marker.getPosition().lat(), lng: marker.getPosition().lng()};
			geocoder.geocode({'location': latLng}, function(results,status) {
				if (status == 'OK') {
					if (results[0]) {
						// String address
						var address = results[0].formatted_address;
						document.getElementById('currentLocation').value = address
					}else{
						alert('No Results found');
					}
				}else{
					alert('Geocoder not supported. ' + status);
				}
			});
		}
	</script>
	<script>
		<?php
			if(isset($_REQUEST['booking_id'])){
		?>
				$.ajax({
					type: "GET",
					url: "locations.php?booking_id=<?php $_REQUEST['booking_id'] ?>",
					success: function(data) {
						
						$("#trigger-me-location").click();
					}
				});
				
		<?php
			}
		?>
	</script>
</html>
<?php } ?>
	