<?php 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		
		$id				= $_POST['id'];
		$title			= $_POST['title'];
		$detail			= $_POST['detail'];
		$start_date		= $_POST['start_date'];
		$end_date		= $_POST['end_date'];
		$location_lat	= $_POST['location_lat'];
		$location_long	= $_POST['location_long'];
		$max_member		= $_POST['max_member'];
		$user_id		= $_POST['user_id'];
		$category_id	= $_POST['category_id'];
		
		require_once('dbConnect.php');
		
		$sql = "UPDATE activity SET	id = '$id',  
									title = '$title', 
									detail = '$detail', 
									start_date = '$start_date', 
									end_date = '$end_date', 
									location_lat = '$location_lat', 
									location_long = '$location_long',
									max_member = '$max_member',
									user_id = '$user_id',									
									category_id = '$category_id' WHERE id = $id;";
		
		if(mysqli_query($con,$sql)){
			echo 'Etkinlik güncelleme işlemini başarılı..';
		}else{
			echo 'Etkinlik güncelleme işleminde hata oluştu.';
		}
		
		mysqli_close($con);
	}