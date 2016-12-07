<?php 
	$id = $_GET['id'];
	
	require_once('dbConnect.php');
	
	$sql = "SELECT * FROM activity WHERE id=$id";
	$r = mysqli_query($con,$sql);
	
	$result = array();
	
	$row = mysqli_fetch_array($r);
	array_push($result,array(
			"title"			=>$row['title'],
			"detail"		=>$row['detail'],
			"start_date"	=>$row['start_date'],
			"end_date"		=>$row['end_date'],
			"location_lat"	=>$row['location_lat'],
			"location_long"	=>$row['location_long'],
			"max_member"	=>$row['max_member'],
			"user_id"		=>$row['user_id'],
			"category_id"	=>$row['category_id']
		));

	echo json_encode(array('result'=>$result));
	
	mysqli_close($con);
	
