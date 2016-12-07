<?php
define("DB_HOST", "localhost");
define("DB_USER", "android_admin");
define("DB_PASSWORD", "123");
define("DB_DATABASE", "android");
 
 $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die('Unable to Connect');