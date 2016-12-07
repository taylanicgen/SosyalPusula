<?php 
	//Importing Database Script 
	require_once('dbConnect.php');
	
	//Creating sql query
	$sql = "SELECT * FROM activity";
	
	//getting result 
	$r = mysqli_query($con,$sql);
	
	//creating a blank array 
	$result = array();
	
	//looping through all the records fetched
	while($row = mysqli_fetch_array($r)){
		
		//Pushing name and id in the blank array created 
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
	}
	
	//Displaying the array in json format 
	echo json_encode(array('result'=>$result));
	
	mysqli_close($con);