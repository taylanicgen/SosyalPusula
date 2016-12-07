<?php 
	header('Content-type: text/html; charset=utf-8');
	if($_SERVER['REQUEST_METHOD']=='POST'){
		
		
		$title			= $_POST['title'];
		$detail			= $_POST['detail'];
		$start_date		= $_POST['start_date'];
		$end_date		= $_POST['end_date'];
		$location_lat		= $_POST['location_lat'];
		$location_long		= $_POST['location_long'];
		$max_member		= $_POST['max_member'];
		$user_name		= $_POST['user_name'];
		$category_name		= $_POST['category_name'];
		$address		= $_POST['address'];
		
		//echo $start_date;
		echo $end_date;
		//$start_date = date('Y-m-d H:i:s');
		//$end_date = date('Y-m-d H:i:s');

		
		
		//$start_date 	= strtotime($start_date2);
		//$end_date 	= strtotime($end_date2);

		//echo date_format($start_date, 'Y-m-d H:i:s');
		//echo date_format($end_date, 'Y-m-d H:i:s');
		
		//Creating an sql query
		
		$sql = "INSERT INTO activity (title,detail,start_date,end_date,location_lat,location_long,max_member,user_name,category_name,address,created_at) 
		
		VALUES ('$title','$detail','$start_date','$end_date','$location_lat','$location_long','$max_member','$user_name','$category_name','$address',NOW())";
		
		//Importing our db connection script
		require_once('dbConnect.php');

		    mysqli_query("SET character_set_client=utf8", $con);
    		mysqli_query("SET character_set_connection=utf8", $con);
		
		//Executing query to database
		if(mysqli_query($con,$sql)){
			echo $detail;
		}else{
			echo 'Etkinlik oluşturmada bir hata oluştu. Lütfen bilgilerinizi kontrol ediniz..';
		}
		
		//Closing the database 
		mysqli_close($con);
	}