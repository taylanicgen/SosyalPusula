<?php
	define("HOST", "localhost");
	define("USER", "android_admin");
	define("PASS", "123");
	define("DB", "android");
	
	$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');