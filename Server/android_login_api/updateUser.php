<?php 
	
require_once('dbConnect.php');
	if($_SERVER['REQUEST_METHOD']=='POST'){
		
		$username = $_POST['username'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$phone = $_POST['phone'];
		$sex = $_POST['sex'];

		
		
		$sql = "UPDATE users SET 
		name = '$name', 
		email = '$email', 
		phone = '$phone', 
		sex = '$sex'
		WHERE username = '$username';";
		
		if(mysqli_query($con,$sql)){
			echo 'Employee Updated Successfully';
		}else{
			echo 'Could Not Update Employee Try Again';
		}
		
		mysqli_close($con);
	}
	
	?>