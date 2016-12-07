<?php
@ob_start();
@session_start();
include("include/baglan.php");
include("include/fonksiyonlar.php");
oturumkontrolana();

if($_POST['haber_sira'] && $_GET['islem']=="")
{
	$haber_sira = $_POST['haber_sira'];
	$haber_adi = $_POST['haber_adi'];
	$durum = $_POST['durum'];
	$aciklama = $_POST['aciklama'];
		
	$kaynak = $_FILES["resim"]["tmp_name"];  
	$dosya = $_FILES["resim"]["name"];
	$uzanti = explode(".", $_FILES[resim][name]); 
	$random = rand(0,9999);
	$yeni_isim = $random."_".$dosya;
	$hedef  = "../lib/images/haberler/".$yeni_isim;
	
	if($dosya=="")
	$yeni_isim="no-image";
	
	$gitti=move_uploaded_file($kaynak,$hedef);
	
	$haber_ekle_sorgu=mysql_query("insert into haberler (sira, 
														   adi, 
														   resim,
														   durum,
														   aciklama,
														   tarih) 
														   values ('$haber_sira',
																   '$haber_adi',
																   '$yeni_isim',
																   '$durum',
																   '$aciklama',
																   '$tarih')");
																   
	$bilgi = '	 <div class="alert alert-success">
										Başarı ile Eklenmiştir !
				  </div>' ; 
}



if($_POST['haber_sira'] && $_GET['islem']=="duzenle")
{
	$haber_sira = $_POST['haber_sira'];
	$haber_adi = $_POST['haber_adi'];
	$duzenlenecek_id = $_GET['id'];
	$durum = $_POST['durum'];
	$aciklama = $_POST['aciklama'];
	
	$kaynak = $_FILES["resim"]["tmp_name"];  
	$dosya = $_FILES["resim"]["name"];
	$uzanti = explode(".", $_FILES[resim][name]); 
	$random = rand(0,9999);
	$yeni_isim = $random."_".$dosya;
	$hedef  = "../lib/images/haberler/".$yeni_isim;
	
	if($dosya!="")
	{
		$resim_bul=mysql_fetch_array(mysql_query("select * from haberler where id='$duzenlenecek_id'"));
		$resim_sil=unlink("../lib/images/haberler/".$resim_bul['resim']);
	
		$haber_duzenle_sorgu=mysql_query("update haberler set durum='$durum', sira='$haber_sira', adi='$haber_adi', resim='$yeni_isim', aciklama='$aciklama' where id='$duzenlenecek_id'");
		
		$gitti=move_uploaded_file($kaynak,$hedef);		
	}
	else
	{	
	$haber_duzenle_sorgu=mysql_query("update haberler set durum='$durum', aciklama='$aciklama', sira='$haber_sira', adi='$haber_adi' where id='$duzenlenecek_id'");
	}
	
	$bilgi = '	 <div class="alert alert-success">
										Başarı ile Güncellenmiştir !
				  </div>' ;
}



if($_GET['islem']=="duzenle")
{
	$id = $_GET['id'];
	
	$durum = "duzenle" ;
	
	$haber_dizi=mysql_fetch_array(mysql_query("select * from haberler where id = '$id'"));
	
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
    <!-- BEGIN PAGE LEVEL STYLES -->
   <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/gritter/css/jquery.gritter.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/chosen-bootstrap/chosen/chosen.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/jquery-tags-input/jquery.tagsinput.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/clockface/css/clockface.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker/css/datepicker.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-timepicker/compiled/timepicker.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-colorpicker/css/colorpicker.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
   <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" />
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

                        <h4><i class="icon-cloud"></i> Haber Ekleme / Güncelleme</h4>
                        
                     </div> <!--widget-title end-->
                     
                     
                    <div class="widget-body form">
                    
                    <?=$bilgi?>
                    
                        <!-- BEGIN FORM-->
                        <form action="#" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="control-group">
                              <label class="control-label">Haber Sırası</label>
                              <div class="controls">
                                 <input type="text" name="haber_sira" data-required="1" class="span6 " value="<?=$haber_dizi['sira']?>"/>
                              </div>
                           </div>
                                                      
                           <div class="control-group">
                              <label class="control-label">Haber Adı</label>
                              <div class="controls">
                                 <input name="haber_adi" type="text" class="span6 " value="<?=$haber_dizi['adi']?>"/>
                              </div>
                           </div>
                           
                           
                           
                           <div class="control-group">
                              <label class="control-label">Haber Resmi</label>
                              <div class="controls">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                       <div class="input-append">
                                          <div class="uneditable-input">
                                             <i class="icon-file fileupload-exists"></i> 
                                             <span class="fileupload-preview"></span>
                                          </div>
                                          <span class="btn btn-file">
                                          <span class="fileupload-new">Resim Seç</span>
                                          <span class="fileupload-exists">Değiştir</span>
                                          <input type="file" name="resim" class="default" />
                                          </span>
                                          <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Vazgeç</a>
                                       </div>
                                    </div>
                                 </div>
                           </div>
                           
                           <div class="control-group">
                                 <label class="control-label">Durumu</label>
                                 <div class="controls">
                                    <div class="basic-toggle-button">
                                      
                                      <?php
									  if($_GET['islem']=='duzenle')
									  {
									 ?>
                                     <input type="checkbox" class="toggle" name="durum" <?php if($haber_dizi['durum']=='1') {?>checked="checked" <?php } ?> value="1" />
                                     <?php
									  }
									  else
									  {
									  ?>
                                      <input type="checkbox" class="toggle" checked="checked" name="durum"  value="1" />
                                      <?php
									  }
									  ?>
                                    </div>
                                 </div>
                              </div>
                           
                          
                           <div class="control-group">
                                 <label class="control-label">Açıklama</label>
                                 <div class="controls">
                                    <textarea class="span12 ckeditor m-wrap" name="aciklama" rows="6"><?=$haber_dizi['aciklama']?></textarea>
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
   <!-- BEGIN PAGE LEVEL PLUGINS -->
   <script type="text/javascript" src="assets/plugins/ckeditor/ckeditor.js"></script>  
   <script type="text/javascript" src="assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>
   <script type="text/javascript" src="assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
   <script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script> 
   <script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
   <script type="text/javascript" src="assets/plugins/jquery-tags-input/jquery.tagsinput.min.js"></script>
   <script type="text/javascript" src="assets/plugins/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
   <script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   <script type="text/javascript" src="assets/plugins/clockface/js/clockface.js"></script>
   <script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/date.js"></script>
   <script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script> 
   <script type="text/javascript" src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>  
   <script type="text/javascript" src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
   <script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>   
   <script type="text/javascript" src="assets/plugins/jquery.input-ip-address-control-1.0.min.js"></script>
   <!-- END PAGE LEVEL PLUGINS -->
   <!-- BEGIN PAGE LEVEL STYLES -->
   <script src="assets/scripts/app.js"></script>
   <script src="assets/scripts/form-validation.js"></script>
   <script src="assets/scripts/form-components.js"></script> 
   <!-- END PAGE LEVEL STYLES -->    
   <script>
      jQuery(document).ready(function() {   
         // initiate layout and plugins
         App.init();
         FormValidation.init();
		 FormComponents.init();
      });
   </script>
   <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
