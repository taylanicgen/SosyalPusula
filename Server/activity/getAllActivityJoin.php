<?php 

	$username = $_GET['username'];
	//Importing Database Script 
	require_once('dbConnect.php');
	
	
	//Creating sql query
	$sql = "SELECT * FROM user_activity WHERE user_name='$username'";
	
	//getting result 
	$r = mysqli_query($con,$sql);
	
	//creating a blank array 
	$result = array();
	
	//looping through all the records fetched
	while($row = mysqli_fetch_array($r)){
		$num=(int)$row['activity_id'];
		$sqlsorgu = "SELECT * FROM activity WHERE id=$num";
		$sorgur = mysqli_query($con,$sqlsorgu);
		$sorgurow = mysqli_fetch_array($sorgur);
		
		
		//Pushing name and id in the blank array created 
		array_push($result,array(
			"user_name"			=>$sorgurow['user_name'],
			"activity_name"		=>$sorgurow['title'],
			"activity_id"	=>$sorgurow['id']

		));
	}
	
	//Displaying the array in json format 
	echo json_encode(array('result'=>$result));
	
	mysqli_close($con);