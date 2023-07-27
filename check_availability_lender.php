<?php 
require_once("includes/config.php");
// code user email availablity
if(!empty($_POST["fullname"])) {
	$fullname= $_POST["fullname"];
	
	$sql ="SELECT FullName FROM tblusers WHERE FullName=:fullname and UserType='0'";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $query-> execute();
    $results = $query -> fetchAll(PDO::FETCH_OBJ);
    $cnt=1;
    if($query -> rowCount() > 0){
        echo "<span style='color:red'> Lender already exists .</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    }else{
        echo "<span style='color:green'> Lender available for Registration .</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
}



?>
