<?php
 
include("../include/baglan.php");
 
if($_POST['olustur']=='Oluştur')
{ 
$yonetici_ad_soyad   = $_POST['yonetici_ad_soyad']; 
$yonetici_kullanici  = $_POST['yonetici_kullanici']; 
$yonetici_sifre      = $_POST['yonetici_sifre']; 
$yonetici_email      = $_POST['yonetici_email'];
$tarih               = date("d-m-Y");

$yonetici_table_olustur = "CREATE TABLE yonetici 
(
yonetici_id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(yonetici_id),
yonetici_ad_soyad CHAR(55),
yonetici_kullanici CHAR(25),
yonetici_sifre CHAR(25),
yonetici_email CHAR(55),
yonetici_son_giris CHAR(55)
)";

mysql_query($yonetici_table_olustur);

$yonetici_ekle=mysql_query("insert into yonetici (yonetici_ad_soyad, yonetici_kullanici, yonetici_sifre, yonetici_email, yonetici_son_giris) values ('$yonetici_ad_soyad', '$yonetici_kullanici', '$yonetici_sifre', '$yonetici_email', '$tarih')");

echo "Yönetici eklendi. Yönlendiriliyorsunuz.";
echo '<meta http-equiv="refresh" content="1; url=ayar.php">';
 
}

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Panel Kurulum Ekranı | Yönetici Oluşturma</title>
</head>

<body>

<form action="#" method="post">

	<table>
    
    
    	<tr>
        
        	<td>Yönetici Ad Soyad</td>
            <td><input type="text" name="yonetici_ad_soyad"></td>
        
        </tr>
        
        <tr>
        
        	<td>Yönetici Kullanıcı Adı</td>
            <td><input type="text" name="yonetici_kullanici"></td>
        
        </tr>
        
        <tr>
        
        	<td>Yönetici Şifre</td>
            <td><input type="text" name="yonetici_sifre"></td>
        
        </tr>
        
        <tr>
        
        	<td>Yönetici E-mail</td>
            <td><input type="text" name="yonetici_email"></td>
        
        </tr>
        
        <tr>
        
        	<td></td>
            <td><input type="submit" value="Oluştur" name="olustur"></td>
        
        </tr>
    
    </table>

</form>


</body>
</html>