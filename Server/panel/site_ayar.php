<?php
@ob_start();
@session_start();
include("include/baglan.php");
include("include/fonksiyonlar.php");
oturumkontrolana();

if(isset($_POST['site_title']))
{
	$site_title = $_POST['site_title'];
	$site_meta = $_POST['site_meta'];
	$site_description = $_POST['site_description'];
	$site_mail = $_POST['site_mail'];
	$site_mail_sifre = $_POST['site_mail_sifre'];
	$site_mail_host = $_POST['site_mail_host'];
	$site_mail_port = $_POST['site_mail_port'];
	
	$firma_adi = $_POST['firma_adi'];
	$firma_telefon = $_POST['firma_telefon'];
	$firma_fax = $_POST['firma_fax'];
	$firma_email = $_POST['firma_email'];
	$firma_adres = $_POST['firma_adres'];
	
	$google_analytics = $_POST['google_analytics'];
	$google_maps = $_POST['google_maps'];
	
	$facebook = $_POST['facebook'];
	$twitter = $_POST['twitter'];
	$gplus = $_POST['gplus'];
	$linkedin = $_POST['linkedin'];
	$pinterest = $_POST['pinterest'];
	
	$ayar_duzenle_sorgu=mysql_query("update ayar set site_title='$site_title',
									site_meta='$site_meta', 
									site_description='$site_description', 
									site_mail='$site_mail',
									site_mail_sifre='$site_mail_sifre',
									site_mail_host='$site_mail_host',
									site_mail_port='$site_mail_port',
									firma_adi='$firma_adi',
									firma_telefon='$firma_telefon',
									firma_fax='$firma_fax',
									firma_email='$firma_email',
									firma_adres='$firma_adres',
									google_analytics='$google_analytics',
									google_maps='$google_maps',
									facebook='$facebook',
									twitter='$twitter',
									gplus='$gplus',
									linkedin='$linkedin',
									pinterest='$pinterest' 
									where ayar_id='1'");
	
				  $kaynak = $_FILES["firma_logo"]["tmp_name"];  
				  $yeni_isim = "logo.png";
				  $hedef  = "../lib/images/".$yeni_isim; 
				  
				  $gitti=move_uploaded_file($kaynak,$hedef);
									
									
	$bilgi = '	 <div class="alert alert-success">
										Bilgiler Başarı ile Güncellenmiştir. !
				  </div>' ;
	
	
}

	$ayar_dizi=mysql_fetch_array(mysql_query("select * from ayar where ayar_id = '1'"));

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

                        <h4><i class="icon-cogs"></i> Site Genel Ayarları </h4>
                        
                     </div> <!--widget-title end-->
                     
                     
                    <div class="widget-body form">
                    
                    <?=$bilgi?>
                    
                        <!-- BEGIN FORM-->
                        <form action="#" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="control-group">
                              <label class="control-label">Site Title</label>
                              <div class="controls">
                                 <input type="text" name="site_title" data-required="1" class="span7 " value="<?=$ayar_dizi['site_title']?>"/> <span class="dipnot">max. 70 karakter</span>
                              </div>
                           </div>
                           
                           
                           <div class="control-group">
                              <label class="control-label">Site Meta</label>
                              <div class="controls">
                                 <input name="site_meta" type="text" class="span7 " value="<?=$ayar_dizi['site_meta']?>"/> <span class="dipnot">max. 160 karakter</span>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Site Açıklama (description)</label>
                              <div class="controls">
                                 <input name="site_description" type="text" class="span7 " value="<?=$ayar_dizi['site_description']?>"/> <span class="dipnot">max. 160 karakter</span>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Site Mail Adresi</label>
                              <div class="controls">
                                 <input name="site_mail" type="text" class="span7 " value="<?=$ayar_dizi['site_mail']?>"/> <span class="dipnot">Örn : info@siteadi.com</span>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Site Mail Şifre</label>
                              <div class="controls">
                                 <input name="site_mail_sifre" type="text" class="span7 " value="<?=$ayar_dizi['site_mail_sifre']?>"/> <span class="dipnot">SMTP ayarları için gereklidir.</span>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Site Mail Host</label>
                              <div class="controls">
                                 <input name="site_mail_host" type="text" class="span7 " value="<?=$ayar_dizi['site_mail_host']?>"/> <span class="dipnot">SMTP ayarları için gereklidir. Örn : mail.siteadi.com</span>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Site Mail Port</label>
                              <div class="controls">
                                 <input name="site_mail_port" type="text" class="span7 " value="<?=$ayar_dizi['site_mail_port']?>"/> <span class="dipnot">SMTP ayarları için gereklidir. Örn : 587</span>
                              </div>
                           </div>
                           
                           <hr>
                           
                           <div class="control-group">
                              <label class="control-label">Firma Logo</label>
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
                                          <input type="file" name="firma_logo" class="default" />
                                          </span>
                                          <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Vazgeç</a>
                                       </div>
                                    </div>
                                 </div>
                           </div>
                           
                           
                           <div class="control-group">
                              <label class="control-label">Firma Adı</label>
                              <div class="controls">
                                 <input name="firma_adi" type="text" class="span7 " value="<?=$ayar_dizi['firma_adi']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Firma Telefon</label>
                              <div class="controls">
                                 <input name="firma_telefon" type="text" class="span7 " value="<?=$ayar_dizi['firma_telefon']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Firma Fax</label>
                              <div class="controls">
                                 <input name="firma_fax" type="text" class="span7 " value="<?=$ayar_dizi['firma_fax']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Firma Email</label>
                              <div class="controls">
                                 <input name="firma_email" type="text" class="span7 " value="<?=$ayar_dizi['firma_email']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Firma Adres</label>
                              <div class="controls">
                                 <textarea name="firma_adres" class="span7" rows="3" ><?=$ayar_dizi['firma_adres']?></textarea>
                              </div>
                           </div>
                           
                           
                           <hr>
                           
                           <div class="control-group">
                              <label class="control-label">Google Analytics</label>
                              <div class="controls">
                                 <textarea name="google_analytics" class="span7" rows="6" ><?=$ayar_dizi['google_analytics']?></textarea>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Google Maps</label>
                              <div class="controls">
                                 <textarea name="google_maps" class="span7" rows="6" ><?=$ayar_dizi['google_maps']?></textarea>
                              </div>
                           </div>
                           
                           
                           <hr>
                           
                           <div class="control-group">
                              <label class="control-label">Facebook Sayfa URL</label>
                              <div class="controls">
                                 <input name="facebook" type="text" class="span7 " value="<?=$ayar_dizi['facebook']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Twitter Sayfa URL</label>
                              <div class="controls">
                                 <input name="twitter" type="text" class="span7 " value="<?=$ayar_dizi['twitter']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Gplus Sayfa URL</label>
                              <div class="controls">
                                 <input name="gplus" type="text" class="span7 " value="<?=$ayar_dizi['gplus']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Linkedin Sayfa URL</label>
                              <div class="controls">
                                 <input name="linkedin" type="text" class="span7 " value="<?=$ayar_dizi['linkedin']?>"/> 
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">Pinterest Sayfa URL</label>
                              <div class="controls">
                                 <input name="pinterest" type="text" class="span7 " value="<?=$ayar_dizi['pinterest']?>"/> 
                              </div>
                           </div>
                           
                           <div class="form-actions">
                           
                          
                           <button type="submit" name="guncelle" onclick="submit();" class="btn btn-primary"><i class="icon-ok"></i> Güncelle</button>
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
