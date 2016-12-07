<?php
 
include("../include/baglan.php");
 
if($_POST['olustur']=='Kaydet')
{ 
$site_title            = $_POST['site_title']; 
$site_meta    		   = $_POST['site_meta']; 
$site_description      = $_POST['site_description']; 
$site_mail		       = $_POST['site_mail'];
$site_mail_sifre       = $_POST['site_mail_sifre'];
$site_mail_host        = $_POST['site_mail_host'];
$site_mail_port        = $_POST['site_mail_port'];
$google_analytics      = $_POST['google_analytics'];


$ayar_table_olustur = "CREATE TABLE ayar 
(
ayar_id INT NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(ayar_id),
site_title CHAR(70),
site_meta CHAR(160),
site_description CHAR(160),
site_mail CHAR(55),
site_mail_sifre CHAR(55),
site_mail_host CHAR(55),
site_mail_port CHAR(55),
google_analytics TEXT
)CHARACTER SET utf8 COLLATE utf8_turkish_ci";

mysql_query($ayar_table_olustur);

$ayar_ekle=mysql_query("insert into ayar (site_title, 
										  site_meta, 
										  site_description, 
										  site_mail,
										  site_mail_sifre,
										  site_mail_host,
										  site_mail_port,
										  google_analytics) 
										  values 
										  ('$site_title', 
										  '$site_meta', 
										  '$site_description', 
										  '$site_mail', 
										  '$site_mail_sifre',
										  '$site_mail_host',
										  '$site_mail_port',
										  '$google_analytics')");

echo "Yönetici eklendi. Yönlendiriliyorsunuz.";
echo '<meta http-equiv="refresh" content="1; url=ayar.php">';
 
}

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Panel Kurulum Ekranı | Site Ayarları</title>
</head>

<body>

<form action="#" method="post">

	<table>
    
    
    	<tr>
        
        	<td>Site Başlık</td>
            <td><input type="text" name="site_title"> max. 70 karakter</td>
        
        </tr>
        
        <tr>
        
        	<td>Site Anahtar Kelimeler ( Meta-Keywords )</td>
            <td><input type="text" name="site_meta"> max. 160 karakter</td>
        
        </tr>
        
        <tr>
        
        	<td>Site Kısa Açıklaması ( Meta-Description )</td>
            <td><input type="text" name="site_description"> max. 160 karakter</td>
        
        </tr>
        
        <tr>
        
        	<td colspan="2">
            
            <hr>
            
            </td>
        
        <tr>
                
        <tr>
        
        	<td>Site Mail Adresi</td>
            <td><input type="text" name="site_mail"></td>
        
        </tr>
        
        <tr>
        
        	<td>Site Mail Adresi Şifresi</td>
            <td><input type="text" name="site_mail_sifre"></td>
        
        </tr>
        
        <tr>
        
        	<td>Site Mail Hosto ( Örn : mail.siteadi.com )</td>
            <td><input type="text" name="site_mail_host"></td>
        
        </tr>
        
        <tr>
        
        	<td>Site Mail Portu ( Örn : 587 )</td>
            <td><input type="text" name="site_mail_port"></td>
        
        </tr>
        
        <tr>
        
        	<td colspan="2">
            
            <hr>
            
            </td>
        
        <tr>
        
        <tr>
        
        	<td>Google Analytics kodu ( Google ziyaretçi takip sistemi )</td>
            <td><textarea name="google_analytics"> </textarea></td>
        
        </tr>
        
        <tr>
        
        	<td></td>
            <td><input type="submit" value="Kaydet" name="olustur"></td>
        
        </tr>
    
    </table>

</form>


</body>
</html>