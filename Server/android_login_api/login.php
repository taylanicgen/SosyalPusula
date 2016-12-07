<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
error_reporting(-1);
ini_set('display_errors', 'On');
// json response array
$response = array("error" => FALSE);

if (isset($_POST['username']) && isset($_POST['password'])) {

    // receiving the post params
    $username = $_POST['username'];
    $password = $_POST['password'];

	

    // get the user by email and password
    $user = $db->getUserByEmailAndPassword($username, $password);

    if ($user != false) {
        // use is found
        $response["error"] = FALSE;
        $response["uid"] = $user["unique_id"];
        $response["user"]["name"] = $user["name"];
        $response["user"]["email"] = $user["email"];
		$response["user"]["username"] = $user["username"];
        $response["user"]["sex"] = $user["sex"];
		$response["user"]["phone"] = $user["phone"];
        $response["user"]["created_at"] = $user["created_at"];
        $response["user"]["updated_at"] = $user["updated_at"];
        print json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        print json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters username or password is missing!";
    print json_encode($response);
}


