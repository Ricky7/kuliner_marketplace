<!--
author: W3layouts
author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php  
    // Lampirkan db dan User
    //require_once "class/db.php";
    ob_start();

 ?>
<!DOCTYPE html>
<html>
<head>
<title>Super Market an Ecommerce Online Shopping Category Flat Bootstrap Responsive Website Template | Home :: w3layouts</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Super Market Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<link href="asset/index/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="asset/index/css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- font-awesome icons -->
<link href="asset/index/css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
<!-- js -->
<script src="asset/index/js/jquery-1.11.1.min.js"></script>
<!-- //js -->
<link href='//fonts.googleapis.com/css?family=Raleway:400,100,100italic,200,200italic,300,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="asset/index/js/move-top.js"></script>
<script type="text/javascript" src="asset/index/js/easing.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
</head>
	
<body>
<!-- header -->
	<div class="agileits_header">
		<div class="container">
			<div class="w3l_offers">
				<?php
                    if(!$user->isUserLoggedIn()){

                        ?>
                            <p>Selamat ber belanja Guest</p>
                        <?php
                    } else {

                        ?>
                            <p>Selamat ber belanja 
								<a href="#">
									<?php echo $curUser['nama'] ?>
								</a>
							</p>
                        <?php
                    }
                ?>
				
			</div>
			<div class="agile-login">
				<ul>
					<?php
	                    if(!$user->isUserLoggedIn()){

	                        ?>
	                            <li><a href="register.php"> Buat Akun </a></li>
								<li><a href="login.php">Masuk</a></li>
	                        <?php
	                    } else {

	                        ?>
	                            <li><a href="logout.php"> Keluar</a></li>
	                            <li><a href="seller/s_dashboard.php">Toko</a></li>
	                        <?php
	                    }
	                ?>
					<li></li>
					<li></li>
					<li></li>
					<li><a href="cart.php"><h4><i class="fa fa-cart-arrow-down"></i></h4></a></li>



				</ul>
			</div>
			<!-- <div class="product_list_header">  
					<form action="#" method="post" class="last"> 
						<input type="hidden" name="cmd" value="_cart">
						<input type="hidden" name="display" value="1">
						<button class="w3view-cart" type="submit" name="submit" value=""><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
					</form> 
			</div> -->

			<div class="product_list_header">  
				
			</div>

			<div class="clearfix"> </div>
		</div>
	</div>

	<div class="logo_products">
		<div class="container">
		<div class="w3ls_logo_products_left1">
				<ul class="phone_email">
					<li><i class="fa fa-phone" aria-hidden="true"></i>Hubungi Kami : (+0123) 234 567</li>
					
				</ul>
			</div>
			<div class="w3ls_logo_products_left">
				<h1><a href="#">Wisata Kuliner</a></h1>
			</div>
		<div class="w3l_search">
			<form action="search.php" method="get">
				<input type="text" name="search" placeholder="Cari Produk disini...." required>
				<button type="submit" class="btn btn-default search" aria-label="Left Align">
					<i class="fa fa-search" aria-hidden="true"> </i>
				</button>
				<div class="clearfix"></div>
			</form>
		</div>
			
			<div class="clearfix"> </div>
		</div>
	</div>
<!-- //header -->
<!-- navigation -->
	<div class="navigation-agileits">
		<div class="container">
			<nav class="navbar navbar-default">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header nav_2">
					<button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div> 
				<div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
					<ul class="nav navbar-nav">
						<li class="active"><a href="index.php" class="act">Beranda</a></li>	
						<!-- Mega Menu -->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Produk<b class="caret"></b></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="row">
									<div class="multi-gd-img">
										<ul class="multi-column-dropdown">
											<h6>Kategori</h6>
											<?php
										        $query = "SELECT nama_kategori FROM tbl_kategori";       
										        $produk->menuKategori($query);
										    ?>
										</ul>
									</div>	
								</div>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Wisata Kuliner<b class="caret"></b></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="row">
									<div class="multi-gd-img">
										<ul class="multi-column-dropdown">
											<h6>All Places</h6>
											<?php
												$q = "SELECT * FROM tbl_blog WHERE jenis_konten='Blog'";

										        $stmtq = $db->prepare($q);
										        $stmtq->execute();
										        if($stmtq->rowCount()>0)
									          	{
									              	while($rowq=$stmtq->fetch(PDO::FETCH_ASSOC))
									              	{
									                  ?>
									                  	<li><a href="konten_blog.php?slug=<?php echo $rowq['id_blog'] ?>"><?php echo $rowq['judul'] ?></a></li>
									                  <?php
									                }
									            }
									        ?>
										</ul>
									</div>	
								</div>
							</ul>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Event<b class="caret"></b></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="row">
									<div class="multi-gd-img">
										<ul class="multi-column-dropdown">
											<h6>All Places</h6>
											<?php
												$q = "SELECT * FROM tbl_blog WHERE jenis_konten='Event'";

										        $stmtq = $db->prepare($q);
										        $stmtq->execute();
										        if($stmtq->rowCount()>0)
									          	{
									              	while($rowq=$stmtq->fetch(PDO::FETCH_ASSOC))
									              	{
									                  ?>
									                  	<li><a href="konten_blog.php?slug=<?php echo $rowq['id_blog'] ?>"><?php echo $rowq['judul'] ?></a></li>
									                  <?php
									                }
									            }
									        ?>
										</ul>
									</div>	
								</div>
							</ul>
						</li>
						
						<?php
		                    if($user->isUserLoggedIn()){

		                        ?>
		                            <li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">Pemesanan<b class="caret"></b></a>
										<ul class="dropdown-menu multi-column columns-3">
											<div class="row">
												<div class="multi-gd-img">
													<ul class="multi-column-dropdown">
														<h6>Pemesanan</h6>
														<li><a href="cart.php">Keranjang</a></li>
														<li><a href="order_diproses.php">Proses</a></li>
														<li><a href="order_selesai.php">Selesai</a></li>
													</ul>
												</div>	
											</div>
										</ul>
									</li>
		                        <?php
		                    }
		                ?>
					</ul>
				</div>
				</nav>
			</div>
		</div>