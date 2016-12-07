<?php 
	
require_once('dbConnect.php');
	if($_SERVER['REQUEST_METHOD']=='POST'){

		$username = $_POST['username'];
		$location_lat = $_POST['location_lat'];
		$location_long = $_POST['location_long'];
		
		$sql = "UPDATE users SET 
		location_lat = '$location_lat',
		location_long = '$location_long',
		updated_at = NOW() 
		WHERE username = '$username';";
		
		if(mysqli_query($con,$sql)){
			echo 'Lokasyon Updated Successfully';
		}else{
			echo 'Could Not Update Lokasyon Try Again';
		}
		
		mysqli_close($con);
	}
	
	?>