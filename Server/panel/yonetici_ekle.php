<?php
@ob_start();
@session_start();
include("include/baglan.php");
include("include/fonksiyonlar.php");
oturumkontrolana();

if($_POST['name'] && $_GET['islem']=="")
{
	$ad_soyad = $_POST['name'];
	$email = $_POST['email'];
	$kullanici = $_POST['occupation'];
	$sifre = $_POST['sifre'];
	
	$yonetici_ekle_sorgu=mysql_query("insert into yonetici (yonetici_ad_soyad, 
														   yonetici_kullanici, 
														   yonetici_sifre, 
														   yonetici_email,
														   yonetici_son_giris) 
														   values ('$ad_soyad',
																   '$kullanici',
																   '$sifre',
																   '$email',
																   '$tarih')");
																   
	$bilgi = '	 <div class="alert alert-success">
										Yönetici Başarı ile Eklenmiştir !
				  </div>' ;
}



if($_POST['name'] && $_GET['islem']=="duzenle")
{
	$ad_soyad = $_POST['name'];
	$email = $_POST['email'];
	$kullanici = $_POST['occupation'];
	$sifre = $_POST['sifre'];
	$duzenlenecek_id = $_GET['id'];
	
	$yonetici_duzenle_sorgu=mysql_query("update yonetici set yonetici_ad_soyad='$ad_soyad', yonetici_kullanici='$kullanici', yonetici_sifre='$sifre', yonetici_email='$email' where yonetici_id='$duzenlenecek_id'");
	
	$bilgi = '	 <div class="alert alert-success">
										Yönetici Başarı ile Güncellenmiştir !
				  </div>' ;
}


if($_GET['islem']=="duzenle")
{
	$yonetici_idd = $_GET['id'];
	
	$durum = "duzenle" ;
	
	$yonetici_dizi=mysql_fetch_array(mysql_query("select * from yonetici where yonetici_id = '$yonetici_idd'"));
	
}

?>


<!DOCTYPE html>
<!--  
Template Name: Conquer Responsive Admin Dashboard Template build with Twitter Bootstrap 2.3.1
Version: 1.4
Author: KeenThemes
Website: http://www.keenthemes.com
Purchase: http://themeforest.net/item/conquer-responsive-admin-dashboard-template/3716838
-->
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Madmin V1 | Mevese İnternet Çözümleri</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.css" rel="stylesheet" />
	<link href="assets/css/themes/default.css" rel="stylesheet" id="style_color" />
	<link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
	<link href="#" rel="stylesheet" id="style_metro" />
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->	
	<link href="assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" type="text/css"  />
	<link href="assets/plugins/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL STYLES -->
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body class="fixed-top">
	<!-- BEGIN HEADER -->
	<?php include("header.php") ;?>
	<!-- END HEADER -->
    
    
    
	<!-- BEGIN CONTAINER -->
	<div id="container" class="row-fluid">
		
        
        <!-- BEGIN SIDEBAR -->
		<?php include("menu.php") ;?>
		<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->
        
        
        
		<!-- BEGIN PAGE -->
		<div id="body">
			
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						  	
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
						<h3 class="page-title">
							<?=ucwords($_SESSION['yonetici_ad_soyad'])?> <small>Hoşgeldin | Site Yönetim Paneli</small>
						</h3>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
                
                
				<!-- BURALAR BENIM ALANIM BASI-->
				<div id="page" class="dashboard">
					
                	<div class="widget box light-grey">
                     
                     <div class="widget-title">

                        <h4><i class="icon-user"></i> Yönetici Ekleme / Güncelleme</h4>
                        
                     </div> <!--widget-title end-->
                     
                     
                    <div class="widget-body form">
                    
                    <?=$bilgi?>
                    
                        <!-- BEGIN FORM-->
                        <form action="#" id="form_sample_1" class="form-horizontal" method="post">
                            <div class="control-group">
                              <label class="control-label">Ad Soyad<span class="required">*</span></label>
                              <div class="controls">
                                 <input type="text" name="name" data-required="1" class="span6 " value="<?=$yonetici_dizi['yonetici_ad_soyad']?>"/>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">E-mail adres<span class="required">*</span></label>
                              <div class="controls">
                                 <input name="email" type="text" class="span6 " value="<?=$yonetici_dizi['yonetici_email']?>"/>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Kullanıcı Adı</label>
                              <div class="controls">
                                 <input name="occupation" type="text" class="span6 " value="<?=$yonetici_dizi['yonetici_kullanici']?>"/>
                              </div>
                           </div>
                           
                          <div class="control-group">
                              <label class="control-label">Şifre</label>
                              <div class="controls">
                                 <input name="sifre" type="password" class="span6 " value="<?=$yonetici_dizi['yonetici_sifre']?>"/>
                             </div>
                           </div>
                           
                           <div class="form-actions">
                           
                           <?php
						   if($_GET['islem']=="duzenle")
						   {
						   ?>
                           <button type="submit" onclick="submit();" class="btn btn-primary"><i class="icon-ok"></i> Güncelle</button>
                           <?php
						   }
						   else
						   {
						   ?>
                           <button type="submit" onclick="submit();" class="btn btn-primary"><i class="icon-ok"></i> Kaydet</button>	
                           <?php
						   }
						   ?>
                           </div>
                        </form>
                        <!-- END FORM-->
                     
                     </div> <!--widget box light-grey end-->
                 
				</div>
				<!-- BURALAR BENIM ALANIM-->
                
                
			</div>
			<!-- END PAGE CONTAINER-->	
            
            	
		</div>
		<!-- END PAGE -->
        
        
	</div>
	<!-- END CONTAINER -->
    
    
    
	<!-- BEGIN FOOTER -->
	<?php include("footer.php") ;?>
	<!-- END FOOTER -->
    
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
   <!-- BEGIN CORE PLUGINS -->
   <script src="assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>   
   <!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->  
   <script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      
   <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
   <!--[if lt IE 9]>
   <script src="assets/plugins/excanvas.js"></script>
   <script src="assets/plugins/respond.js"></script>  
   <![endif]-->   
   <script src="assets/plugins/breakpoints/breakpoints.js" type="text/javascript"></script>  
   <!-- IMPORTANT! jquery.slimscroll.min.js depends on jquery-ui-1.10.1.custom.min.js --> 
   <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
   <script src="assets/plugins/jquery.blockui.js" type="text/javascript"></script>  
   <script src="assets/plugins/jquery.cookie.js" type="text/javascript"></script>
   <script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script> 
   <!-- END CORE PLUGINS -->
   <!-- BEGIN PAGE LEVEL PLUGINS -->
   <script type="text/javascript" src="assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
   <script type="text/javascript" src="assets/plugins/jquery-validation/dist/additional-methods.min.js"></script>
   <script type="text/javascript" src="assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <!-- END PAGE LEVEL PLUGINS -->
   <!-- BEGIN PAGE LEVEL STYLES -->
   <script src="assets/scripts/app.js"></script>
   <script src="assets/scripts/form-validation.js"></script> 
   <!-- END PAGE LEVEL STYLES -->    
   <script>
      jQuery(document).ready(function() {   
         // initiate layout and plugins
         App.init();
         FormValidation.init();
      });
   </script>
   <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
