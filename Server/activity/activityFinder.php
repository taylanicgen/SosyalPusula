<?php 
//Veritabanımıza bağlanıyoruz..
mysql_connect("localhost","android_admin","123")or die("baglanamadim"); 
mysql_query('SET NAMES utf8');
mysql_query('SET CHARACTER_SET utf8');
mysql_select_db("android");

//İki konum arasındaki mesafeyi hesaplayan fonksiyon
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
//RESTful Web servisinde varolan kullanıcının GPS koordinat değerlerini  çekiyoruz..
$json = file_get_contents('php://input');
$obj = json_decode($json);
//Mysql veritabanımda bulunan restoranların  latitude ve longitude değerlerini ve 
//Android uygulama tarafından gelen GPS koordinat değerlerini calculate_distance fonksiyon gonderdim
$sql=mysql_query("select * from activity");
while($row=mysql_fetch_assoc($sql)){
//Kullanıcının konumu ile veritabanındaki restoranların konumları arasındaki mesafeleri hesapladık ve milesArray array atadım.
//$milesArray[$row[id]]=calculate_distance($obj->{'latitude'},$obj->{'longitude'}, $row['location_lat'], $row['location_long']);
$milesArray[$row[id]]=getDistanceBetweenPointsNew($obj->{'latitude'},$obj->{'longitude'}, $row['location_lat'], $row['location_long']);
//echo $obj->{'latitude'}.'---'.$obj->{'longitude'}.'---'.$row['location_lat'].'---'.$row['location_long']."<br/>";
//echo "haciiii".getDistanceBetweenPointsNew('40.82275431452078','29.927924238145355', '40.81780668073278', '29.91875275969505');
//echo $milesArray[$row[id]]."<br/>";
 }
//milesArray dizisindeki uzaklık değerlerini sıraladım
asort($milesArray,SORT_NUMERIC);
$i=0;
//Kullanıcının konumuna en yakın olan yani uzaklığı en az olan 3 restoranın  latitude ve longitude değerlerini  $encode dizisine atadım
foreach ($milesArray as $key => $id) {
	
	$sql2=mysql_query("select * from activity where id='".$key."'");
	while($allRow=mysql_fetch_assoc($sql2)){
		if($id<50.0){
			$new = array(
						'latitude' => $allRow['location_lat'],
                        'longitude' => $allRow['location_long'],                     
						'activity_name' => $allRow['title'],                      
                    );
            $encode[] = $new;
		}
		 
	 }
}
//$outputArr dizisini json ile şifreleyip(encode), Web servise gönderdim
$outputArr = array();
$outputArr['Android'] = $encode;
echo json_encode($outputArr);
mysql_close();

?>