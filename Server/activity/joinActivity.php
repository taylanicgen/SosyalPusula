<?php 		
	if($_SERVER['REQUEST_METHOD']=='POST'){

		//Importing our db connection script
			require_once('dbConnect.php');		
		
		$user_name			= $_POST['user_name'];
		$activity_id			= $_POST['activity_id'];
		

		$sqlselect = "SELECT c.score FROM category c INNER JOIN activity a ON c.name=a.category_name WHERE a.id='$activity_id'";
		//$sql = "SELECT * FROM activity WHERE id=54";
		$r = mysqli_query($con,$sqlselect );
		$row = mysqli_fetch_array($r);

		$sqlkontrol = "SELECT * FROM user_activity WHERE activity_id='$activity_id' AND user_name='$user_name'";
		$kr = mysqli_query($con,$sqlkontrol );
		$krow = mysqli_fetch_array($kr);
		
		$point = $row["score"];
		$kontrol = $krow["id"];

		if($kontrol==null){
			
		//Creating an sql query
		$sql = "INSERT INTO user_activity (user_name,activity_id,point) VALUES ('$user_name','$activity_id','$point')";
		

		
		//Executing query to database
		if(mysqli_query($con,$sql)){
			echo "Baþarýlý";
		}else{
			echo "hata";
		}
		
		}
		else{
			echo"Daha önce bu etkinliðe katýldýnýz..";
		}
		
		
		
		//Closing the database 
		mysqli_close($con);
	}