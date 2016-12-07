<?php
include_once("dbConnect.php");

function getCategories(){

    // array for json response
    $response = array();
    $response["categories"] = array();
    
	$sql = "SELECT * FROM category;";
	$r = mysqli_query($con,$sql);

	$result = array();
	
	$row = mysqli_fetch_array($r);
	array_push($result ,array(
			"id"=>$row['id'],
			"name"=>$row['name']
		));

	echo json_encode(array('result'=>$result));
    }
    
    // keeping response header to json
    header('Content-Type: application/json');
    
    // echoing json result
    echo json_encode($response);
}

getCategories();
?>


