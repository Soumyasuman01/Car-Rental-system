<?php
	session_start();
	error_reporting(E_ALL);
	echo $_SESSION['uid'];
	include('includes/config.php');
	if(strlen($_SESSION['login'])==0 || ($_SESSION['utype'] != 0))
		{	
	header('location:index.php');
	}
	else{
	if(isset($_REQUEST['eid']))
		{
	$eid=intval($_GET['eid']);
	$status="2";
	$sql = "UPDATE tblbooking SET Status=:status WHERE  id=:eid";
	$query = $dbh->prepare($sql);
	$query -> bindParam(':status',$status, PDO::PARAM_STR);
	$query-> bindParam(':eid',$eid, PDO::PARAM_STR);
	$query -> execute();

	$msg="Booking Successfully Cancelled";
	}


	if(isset($_REQUEST['aeid']))
		{
	$aeid=intval($_GET['aeid']);
	$status=1;

	$sql = "UPDATE tblbooking SET Status=:status WHERE  id=:aeid";
	$query = $dbh->prepare($sql);
	$query -> bindParam(':status',$status, PDO::PARAM_STR);
	$query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
	$query -> execute();

	$msg="Booking Successfully Confirmed";
	}

	if(isset($_REQUEST['rid'])){
		$rid = intval($_GET['rid']);
		$status = 3;

		$sql = "UPDATE tblbooking SET Status=:status WHERE  id=:rid";
		$query = $dbh->prepare($sql);
		$query -> bindParam(':status',$status, PDO::PARAM_STR);
		$query-> bindParam(':rid',$rid, PDO::PARAM_STR);
		$query -> execute();
		$msg = "Vehicle has been returned.";
	}
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
	<title>EZRent</title>

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
		<div class="container-text" >
		   <div >

		   </div>
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Confirmed Bookings</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
						    
							<div class="panel-heading">Bookings Info</div>
								       <div class="row" style="background-color:#e9e8ed; width:100%; padding:10px; margin: 0 auto;">
														           <div class="col-md-3 text-center">
														               Filter By Status
														           </div>
    <div class="col-md-3 text-center  m-auto">
        <a class="btn-sm btn-primary m-auto text-center" style="margin: 0 auto;" href="manage-bookings.php">
            All
        </a>
    </div>
     <div class="col-md-3  text-center m-auto">
         <a class="btn-sm btn-primary m-auto text-center" style="margin: 0 auto;" href="cancelled-bookings.php">
            Cancelled
        </a>
    </div>
     <div class="col-md-3 text-center  m-auto">
         <a class="btn-sm btn-primary m-auto text-center"  style="margin: 0 auto;" href="pending-bookings.php">
            Pending
        </a>
    </div>
</div>
							<div class="panel-body">
							<!-- <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?> -->
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
											<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
										<th>#</th>
										<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>
									<?php $sql = "SELECT tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id where user_id = :uid  AND status = 1 order by tblbooking.id desc";
										$query = $dbh -> prepare($sql);
										$query->bindParam(':uid', intval($_SESSION['uid']), PDO::PARAM_INT);
										$query->execute();
										$results=$query->fetchAll(PDO::FETCH_OBJ);
										$cnt=1;
										if($query->rowCount() > 0)
										{
										foreach($results as $result)
										{				
									?>	
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><?php echo htmlentities($result->FullName);?></td>
											<td><a href="edit-vehicle.php?id=<?php echo htmlentities($result->vid);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></td>
											<td><?php echo htmlentities($result->FromDate);?></td>
											<td><?php echo htmlentities($result->ToDate);?></td>
											<td><?php echo htmlentities($result->message);?></td>
											<td>
											<?php 
												if($result->Status==0)
												{
												echo htmlentities('Pending');
												} else if ($result->Status==1) {
												echo htmlentities('Confirmed');
												}
												else if($result->Status==2){
													echo htmlentities('Cancelled');
												}else{
													echo htmlentities('Returned');
												}
											?>
											</td>
											<td><?php echo htmlentities($result->PostingDate);?></td>
										<?php
											if($result->Status == 0) {
										?>
											<td>
												<a href="manage-bookings.php?aeid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Confirm this booking')"> Confirm</a>/
												<a href="manage-bookings.php?eid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Cancel this Booking')"> Cancel</a>
											</td>

										<?php
											}else if($result->Status == 1 && $result->FromDate < date('Y-m-d')){
										?>
											<td>
												<a href="manage-bookings.php?rid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Confirming the vehicle was returned')"> Returned</a>
											</td>
										<?php
											}else if($result->Status == 2){
										?>
											<td>
												<a href="manage-bookings.php?aeid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Confirm this booking')"> Confirm</a>
											</td>
										<?php
											}else if($result->Status == 3) {
										?>
											<td>
											<?php
												$sql_rating = "SELECT booking_id, rating from tblratings where booking_id=:booking_id and type = 0;";
												$query_rating = $dbh -> prepare($sql_rating);
												$query_rating -> bindParam(':booking_id',$result->id, PDO::PARAM_STR);
												$query_rating->execute();	
												$result_rating = $query_rating->fetchAll(PDO::FETCH_OBJ);

												if($query_rating->rowCount() == 0) {
											?>
											<a href="manage-bookings.php?rate_id=<?php echo htmlentities($result->id);?>" id="ratingButton" class="btn btn-sm btn-warning"> Rate Customer</a>
											<button type="button" id="trigger-me" style="visibility:hidden;" data-toggle="modal" data-target="#ratingModal">Click me</button>
											<?php

												}else{
											?>
											<p>
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
											</td>
										<?php	
											}else{
										?>
											<td>
												<a href="manage-bookings.php?rid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Confirming the vehicle was returned')"> Returned</a>/
												<a href="manage-bookings.php?eid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Cancel this Booking')"> Cancel</a>
											</td>
										<?php
											}
										?> 
										
										</tr>
										<?php $cnt=$cnt+1; }} ?>
										
									</tbody>
								</table>

						

							</div>
						</div>

					

					</div>
				</div>

			</div>
		</div>
	</div>

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
	<script>
		<?php
			if(isset($_REQUEST['rid'])){
		?>
			console.log(<?php echo $_REQUEST['rid']; ?>)
			window.location = window.location.pathname;
		<?php
			}

			if(isset($_REQUEST['rate_id'])){
		?>
			$.ajax({
				type: "GET",
				url: "manage-bookings.php?rate_id=<?php $_REQUEST['rate_id'] ?>",
				success: function(data) {
					$("#trigger-me").click();
				}
			});
			console.log(<?php echo $_REQUEST['rate_id']; ?>);
		<?php
			}
		?>
	</script>
	
	<!-- MODAL -->
	<div class="modal fade" tabindex="-1" role="dialog" id="ratingModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="font-family: 'Montserrat', sans-serif;">
			<div class="modal-body text-center">
				<h3 class="display-5 text-center">Rate Customer</h3>
				
				<?php
					$sql = "SELECT user.FullName, booking.userEmail FROM tblusers user INNER JOIN tblbooking booking on user.EmailId = booking.userEmail WHERE booking.id = :rate_id;";
					$query = $dbh->prepare($sql);
					$query->bindParam(':rate_id',$_REQUEST['rate_id'], PDO::PARAM_STR);
					$query->execute();
					$results=$query->fetchAll(PDO::FETCH_OBJ);
				?>
				<span class="glyphicon glyphicon-user" aria-hidden="true" style="font-size: 50px; padding-top: 2px;"></span>
				
				<h5 class="display-5 text-center"><strong><?php echo $results[0]->FullName;?></strong></h5>
				<h5 class="display-6 text-center"><strong><?php echo $results[0]->userEmail;?></strong></h5>
				<hr class="dashed">
				<div class="rating">
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
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" name="submit_rating" value="<?php echo $_REQUEST['rate_id']; ?>" id="submit_rating" onclick="submitRating();">Submit Rating</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>

	<!-- END MODAL -->

	<!-- SCRIPT IN MOVING RATINGS -->
	<script>
		$('#first_star').click(function (event) {

			// Don't follow the link
			event.preventDefault();

			// Log the clicked element in the console
			document.getElementById('first_star').className = "btn btn-warning btn-sm";
			document.getElementById('second_star').className = "btn btn-default btn-sm";
			document.getElementById('third_star').className = 'btn btn-default btn-sm';
			document.getElementById('fourth_star').className = 'btn btn-default btn-sm';
			document.getElementById('fifth_star').className = 'btn btn-default btn-sm';
			
		});
		$('#second_star').click(function (event) {

			// Don't follow the link
			event.preventDefault();

			// Log the clicked element in the console
			document.getElementById('first_star').className = "btn btn-warning btn-sm";
			document.getElementById('second_star').className = "btn btn-warning btn-sm";
			document.getElementById('third_star').className = 'btn btn-default btn-sm';
			document.getElementById('fourth_star').className = 'btn btn-default btn-sm';
			document.getElementById('fifth_star').className = 'btn btn-default btn-sm';
			
		});
		$('#third_star').click(function (event) {

			// Don't follow the link
			event.preventDefault();

			// Log the clicked element in the console
			document.getElementById('first_star').className = "btn btn-warning btn-sm";
			document.getElementById('second_star').className = "btn btn-warning btn-sm";
			document.getElementById('third_star').className = 'btn btn-warning btn-sm';
			document.getElementById('fourth_star').className = 'btn btn-default btn-sm';
			document.getElementById('fifth_star').className = 'btn btn-default btn-sm';
			
		});
		$('#fourth_star').click(function (event) {

			// Don't follow the link
			event.preventDefault();

			// Log the clicked element in the console
			document.getElementById('first_star').className = "btn btn-warning btn-sm";
			document.getElementById('second_star').className = "btn btn-warning btn-sm";
			document.getElementById('third_star').className = 'btn btn-warning btn-sm';
			document.getElementById('fourth_star').className = 'btn btn-warning btn-sm';
			document.getElementById('fifth_star').className = 'btn btn-default btn-sm';
			
		});
		$('#fifth_star').click(function (event) {

			// Don't follow the link
			event.preventDefault();

			// Log the clicked element in the console
			document.getElementById('first_star').className = "btn btn-warning btn-sm";
			document.getElementById('second_star').className = "btn btn-warning btn-sm";
			document.getElementById('third_star').className = 'btn btn-warning btn-sm';
			document.getElementById('fourth_star').className = 'btn btn-warning btn-sm';
			document.getElementById('fifth_star').className = 'btn btn-warning btn-sm';
			
		});
	</script>
	<script>
		var rating = 1;
		function submitRating() {
			if (document.getElementById('first_star').className === "btn btn-warning btn-sm") {
				rating = 1;
			}
			if (document.getElementById('second_star').className === "btn btn-warning btn-sm") {
				rating = 2;
			}
			if (document.getElementById('third_star').className === "btn btn-warning btn-sm") {
				rating = 3;
			}
			if (document.getElementById('fourth_star').className === "btn btn-warning btn-sm") {
				rating = 4;
			}
			if (document.getElementById('fifth_star').className === "btn btn-warning btn-sm") {
				rating = 5;
			}
			$.ajax({
				type: "POST",
				url: "insert_rating.php",
				data: {
					rating: rating,
					rental: "<?php echo $_SESSION['uid']; ?>",
					booking_id: "<?php echo $_REQUEST['rate_id']; ?>",
					rate_type: 0
				},
				success: function(data) {
					console.log(data);
					window.location = window.location.pathname;
				},
				onError: function(data) {
				    alert('An error has occurred. Please try again or contact the site admin.');
				}
			});
		}
	</script>
	
  <?php  include('one-signal-check.php'); ?>
</body>
</html>
<?php } ?>
