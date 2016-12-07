<?php 
	$id = $_GET['id'];
	
	require_once('dbConnect.php');
	
	$sql = "SELECT * FROM users WHERE username='$id'";
	$r = mysqli_query($con,$sql);
	
	$result = array();
	
	$row = mysqli_fetch_array($r);
	array_push($result,array(
			"username"=>$row['username'],
			"name"=>$row['name'],
			"email"=>$row['email'],
			"phone"=>$row['phone'],
			"sex"=>$row['sex']
		));

	echo json_encode(array('result'=>$result));
	
	mysqli_close($con);