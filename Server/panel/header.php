<div id="header" class="navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand" href="anasayfa.php">
				<img src="assets/img/logo.png" alt="Mevese İnternet Çözümleri" width="250" />
				</a>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="arrow"></span>
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->				
				<div class="top-nav">
					
					<!-- BEGIN TOP NAVIGATION MENU -->					
					<ul class="nav pull-right" id="top_menu">
						
						<!-- BEGIN INBOX DROPDOWN -->
						<li class="dropdown" id="header_inbox_bar">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-envelope-alt"></i>
                            	<?php
								 $bilgii= mysql_query("SELECT * FROM iletisim where durum='1'");
								 $mevcut = mysql_num_rows($bilgii);
								 ?>
							<span class="label label-success"><?=$mevcut?></span>
							</a>
							<ul class="dropdown-menu extended inbox">
								<li>
                                
									<p><?=$mevcut?> yeni mesaj var !</p>
								</li>
                                <?php
								$mesaj_cek=mysql_query("select * from iletisim where durum='1' order by tarih desc");
								while($mesaj_dizi=mysql_fetch_array($mesaj_cek))
								{
							   ?>
								<li>
									<a href="#">
									<span class="photo"><img src="./assets/img/avatar-mini.png" alt="avatar" /></span>
									<span class="subject">
									<span class="from"><?=$mesaj_dizi['ad']?></span>
									<span class="time"><?=$mesaj_dizi['tarih']?></span>
									</span>
									<span class="message">
									<?=substr($mesaj_dizi['mesaj'],0,50)?>...
									</span>  
									</a>
								</li>
                                <?php
								}
								?>
								<li>
									<a href="mesajlar.php">Tüm Mesajları Göster</a>
								</li>
							</ul>
						</li>
						<!-- END INBOX DROPDOWN -->
						<li class="divider-vertical hidden-phone hidden-tablet"></li>
						<!-- BEGIN USER LOGIN DROPDOWN -->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-wrench"></i>
							<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="site_ayar.php"><i class="icon-cogs"></i> Site Ayarları</a></li>
							</ul>
						</li>
						<!-- END USER LOGIN DROPDOWN -->
						<li class="divider-vertical hidden-phone hidden-tablet"></li>
						<!-- BEGIN USER LOGIN DROPDOWN -->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-user"></i>
							<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
                            
								<li><a href="yonetici_ekle.php?islem=duzenle&id=<?=$_SESSION['yonetici_id']?>"><i class="icon-user"></i> <?=$_SESSION['yonetici_ad_soyad']?></a></li>
								<li class="divider"></li>
								<li><a href="include/cikis.php"><i class="icon-signout"></i> Oturumu Kapat</a></li>
							</ul>
						</li>
						<!-- END USER LOGIN DROPDOWN -->
					</ul>
					<!-- END TOP NAVIGATION MENU -->	
				</div>
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>