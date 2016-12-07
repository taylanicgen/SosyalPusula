<?php 
//Veritabanýmýza baðlanýyoruz..
mysql_connect("localhost","android_admin","123")or die("baglanamadim"); 
mysql_query('SET NAMES utf8');
mysql_query('SET CHARACTER_SET utf8');
mysql_select_db("android");

//Ýki konum arasýndaki mesafeyi hesaplayan fonksiyon
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
//RESTful Web servisinde varolan kullanýcýnýn GPS koordinat deðerlerini  çekiyoruz..
$json = file_get_contents('php://input');
$obj = json_decode($json);
//Mysql veritabanýmda bulunan restoranlarýn  latitude ve longitude deðerlerini ve 
//Android uygulama tarafýndan gelen GPS koordinat deðerlerini calculate_distance fonksiyon gonderdim
$sql=mysql_query("select * from activity");
while($row=mysql_fetch_assoc($sql)){
//Kullanýcýnýn konumu ile veritabanýndaki restoranlarýn konumlarý arasýndaki mesafeleri hesapladýk ve milesArray array atadým.
//$milesArray[$row[id]]=calculate_distance($obj->{'latitude'},$obj->{'longitude'}, $row['location_lat'], $row['location_long']);
$milesArray[$row[id]]=getDistanceBetweenPointsNew($obj->{'latitude'},$obj->{'longitude'}, $row['location_lat'], $row['location_long']);
//echo $obj->{'latitude'}.'---'.$obj->{'longitude'}.'---'.$row['location_lat'].'---'.$row['location_long']."<br/>";
//echo "haciiii".getDistanceBetweenPointsNew('40.82275431452078','29.927924238145355', '40.81780668073278', '29.91875275969505');
//echo $milesArray[$row[id]]."<br/>";
 }
//milesArray dizisindeki uzaklýk deðerlerini sýraladým
asort($milesArray,SORT_NUMERIC);
$i=0;
//Kullanýcýnýn konumuna en yakýn olan yani uzaklýðý en az olan 3 restoranýn  latitude ve longitude deðerlerini  $encode dizisine atadým
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
//$outputArr dizisini json ile þifreleyip(encode), Web servise gönderdim
$outputArr = array();
$outputArr['Android'] = $encode;
echo json_encode($outputArr);
mysql_close();

?>