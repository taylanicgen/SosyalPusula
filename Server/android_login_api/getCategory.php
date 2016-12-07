<?php
include_once 'DbConnectt.php';
 
function getCategories(){
    $db = new DbConnectt();
    // array for json response
    $response = array();
    $response["categories"] = array();
     
    // Mysql select query
    $result = mysql_query("SELECT * FROM category");
     
    while($row = mysql_fetch_array($result)){
        // temporary array to create single category
        $tmp = array();
        $tmp["id"] = $row["id"];
        $tmp["name"] = $row["name"];
         
        // push category to final json array
        array_push($response["categories"], $tmp);
    }
     
    // keeping response header to json
    header('Content-Type: application/json');
     
    // echoing json result
    echo json_encode($response);
}
 
getCategories();
?>