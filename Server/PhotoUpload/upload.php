<?php

	if($_SERVER['REQUEST_METHOD']=='POST'){
		
		$image = $_POST['image'];
		
		require_once('dbConnect.php');
		
		$sql ="SELECT id FROM photos ORDER BY id ASC";
		
		$res = mysqli_query($con,$sql);
		
		$id = 0;
		
		while($row = mysqli_fetch_array($res)){
				$id = $row['id'];
		}
		
		$path = "uploads/$id.png";
		
		$actualpath = "http://52.38.97.233/PhotoUpload/$path";
		
		$yol = "PhotoUpload/uploads/";
		
		$sql = "INSERT INTO photos (image) VALUES ('$actualpath')";
		
		if(mysqli_query($con,$sql)){
			 try {
		 
				$name = "aaa";
				$extension = pathinfo($name, PATHINFO_EXTENSION);
				$FFx = md5($this->generateRandomString(5).$yol.$name).".".$extension;
				@move_uploaded_file($image["tmp_name"], $yol.$FFx);
				return $FFx;
			 } catch (Exception $e) {
			   return $e->getMessage();
			 }
		
			echo "Successfully Uploaded";
		}
		
		mysqli_close($con);
	}else{
		echo "Error";
	}
	
