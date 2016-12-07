<?php
 
if($_POST['olustur']=='Oluştur')
{ 
$dbhost     = $_POST['host']; 
$dbadi      = $_POST['db']; 
$dbuser     = $_POST['host_username']; 
$dbpass     = $_POST['host_sifre'];

$dosya_adi = "../include/baglan.php"; 

$dosya = fopen ($dosya_adi , 'w') or die ("Dosya açılamadı!"); 

$metin = '<?php $bag = mysql_connect("'.$dbhost.'","'.$dbuser.'","'.$dbpass.'"); '; 

fwrite ( $dosya , $metin ) ; 

fputs ( $dosya , 'mysql_select_db("'.$dbadi.'",$bag) or die	("Veritabanina Baglanamadi."); ?>' ) ; 

fclose ($dosya); 

echo "Dosya Oluşturuldu. Yönlendiriliyorsunuz.";
echo '<meta http-equiv="refresh" content="1; url=yonetici.php">';
 
}

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Panel Kurulum Ekranı | Database Oluşturma</title>
</head>

<body>

<form action="#" method="post">

	<table>
    
    
    	<tr>
        
        	<td>Host</td>
            <td><input type="text" name="host"></td>
        
        </tr>
        
        <tr>
        
        	<td>Host username</td>
            <td><input type="text" name="host_username"></td>
        
        </tr>
        
        <tr>
        
        	<td>Host şifre</td>
            <td><input type="text" name="host_sifre"></td>
        
        </tr>
        
        <tr>
        
        	<td>Veritabanı adı</td>
            <td><input type="text" name="db"></td>
        
        </tr>
        
        <tr>
        
        	<td></td>
            <td><input type="submit" value="Oluştur" name="olustur"></td>
        
        </tr>
    
    </table>

</form>


</body>
</html>