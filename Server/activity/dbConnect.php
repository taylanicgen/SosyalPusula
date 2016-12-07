<?php

header('Content-Type: text/html; charset=utf-8');
	
	define('HOST','localhost');
	define('USER','android_admin');
	define('PASS','123');
	define('DB','android');
	
	$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

	mysql_query("SET NAMES utf8");
	mysql_query("SET CHARACTER SET utf8");
	mysql_query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
	