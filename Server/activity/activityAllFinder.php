<?php 
//Veritaban�m�za ba�lan�yoruz..
mysql_connect("localhost","android_admin","123")or die("baglanamadim"); 
mysql_query('SET NAMES utf8');
mysql_query('SET CHARACTER_SET utf8');
mysql_select_db("android");

//�ki konum aras�ndaki mesafeyi hesaplayan fonksiyon
function calculate_distance($lat1, $lon1, $lat2, $lon2, $unit='K') 
{ 
  $theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}
function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $seamiles = $miles * 0.868976242;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return $kilometers;
} 
//RESTful Web servisinde varolan kullan�c�n�n GPS koordinat de�erlerini  �ekiyoruz..
$json = file_get_contents('php://input');
$obj = json_decode($json);
//Mysql veritaban�mda bulunan restoranlar�n  latitude ve longitude de�erlerini ve 
//Android uygulama taraf�ndan gelen GPS koordinat de�erlerini calculate_distance fonksiyon gonderdim
$sql=mysql_query("select * from activity");
while($row=mysql_fetch_assoc($sql)){
//Kullan�c�n�n konumu ile veritaban�ndaki restoranlar�n konumlar� aras�ndaki mesafeleri hesaplad�k ve milesArray array atad�m.
//$milesArray[$row[id]]=calculate_distance($obj->{'latitude'},$obj->{'longitude'}, $row['location_lat'], $row['location_long']);
$milesArray[$row[id]]=getDistanceBetweenPointsNew($obj->{'latitude'},$obj->{'longitude'}, $row['location_lat'], $row['location_long']);
//echo $obj->{'latitude'}.'---'.$obj->{'longitude'}.'---'.$row['location_lat'].'---'.$row['location_long']."<br/>";
//echo "haciiii".getDistanceBetweenPointsNew('40.82275431452078','29.927924238145355', '40.81780668073278', '29.91875275969505');
//echo $milesArray[$row[id]]."<br/>";
 }
//milesArray dizisindeki uzakl�k de�erlerini s�ralad�m
asort($milesArray,SORT_NUMERIC);
$i=0;
//Kullan�c�n�n konumuna en yak�n olan yani uzakl��� en az olan 3 restoran�n  latitude ve longitude de�erlerini  $encode dizisine atad�m
foreach ($milesArray as $key => $id) {
	
	$sql2=mysql_query("select * from activity where id='".$key."'");
	while($allRow=mysql_fetch_assoc($sql2)){

			$new = array(
						'latitude' => $allRow['location_lat'],
                        'longitude' => $allRow['location_long'],                     
						'activity_name' => $allRow['title'],
						'detail' => $allRow['detail'],
                        'start_date' => $allRow['start_date'],                     
						'end_date' => $allRow['end_date'],
						'max_member' => $allRow['max_member'],
                        'user_name' => $allRow['user_name'],
					'address' => $allRow['address'],
			'activity_id' => $allRow['id'],                       
						'category_name' => $allRow['category_name']
											
                    );
            $encode[] = $new;

		 
	 }
}
//$outputArr dizisini json ile �ifreleyip(encode), Web servise g�nderdim
$outputArr = array();
$outputArr['Android'] = $encode;
echo json_encode($outputArr);
mysql_close();

?>