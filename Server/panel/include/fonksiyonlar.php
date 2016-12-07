<?php
include("include/baglan.php");

$tarih = date("d.m.Y");
$saat = date("h:i");

function oturumkontrolana(){
	 $kullanici = $_SESSION['yonetici_kullanici'];
	 $sifre = $_SESSION['yonetici_sifre'];
	 $oturumkontrol = mysql_query("select * from yonetici where yonetici_kullanici ='$kullanici' and yonetici_sifre ='$sifre'"); 
	 $durum = mysql_fetch_array($oturumkontrol);
	 if($durum){ }else{ echo '<script language="javascript">window.location="index.php";</script>'; die(); }
 }
 
 
 $ayarlar = mysql_query("select * from ayar where ayar_id ='1'"); 
	$ayar = mysql_fetch_array($ayarlar);
 
?>
