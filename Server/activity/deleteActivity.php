<?php 
	$id = $_GET['id'];
	
	require_once('dbConnect.php');
	
	$sql = "DELETE FROM activity WHERE id=$id;";
	
	if(mysqli_query($con,$sql)){
		echo 'Etkinlik başarıyla silindi.';
	}else{
		echo 'Etkinlik silinemiyor, lütfen tekrar deneyiniz.';
	}
	
	mysqli_close($con);
