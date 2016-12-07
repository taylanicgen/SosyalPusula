<?php
 $objConnect = mysql_connect("localhost","android_admin","123");
 $objDB = mysql_select_db("android");
 $strSQL = "SELECT * FROM category WHERE 1 ";
 $objQuery = mysql_query($strSQL);
 $intNumField = mysql_num_fields($objQuery);
 $resultArray = array();
$resultArray["categories"] = array();
 while($obResult = mysql_fetch_array($objQuery))
 {
 $arrCol = array();
 for($i=0;$i<$intNumField;$i++)
 {
 $arrCol[mysql_field_name($objQuery,$i)] = $obResult[$i];
 }
 array_push($resultArray["categories"],$arrCol);
 }
 
mysql_close($objConnect);
 
echo json_encode($resultArray);
?>