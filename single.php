<?php

	require_once "class/db.php";
  	require_once "class/User.php";
  	require_once "class/Produk.php";

  	$user = new User($db);

  	$produk = new Produk($db);

  	//cek jika belum login, maka kembalikan ke hal. login
  	// if(!$user->isUserLoggedIn()) {
  	// 	header("Location: login.php");
  	// }

  	// $curUser = $user->getUser();
  	if($user->isUserLoggedIn()) {
  		$curUser = $user->getUser();
  	}

?>

<?php

	include "header.php";

?>
<!-- breadcrumbs -->
<div class="breadcrumbs">
	<div class="container">
		<ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
			<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Beranda</a></li>
			<li class="active">Informasi Produk</li>
		</ol>
	</div>
</div>

<?php

 	// format mata uang
 	$jumlah_desimal = "0";
	$pemisah_desimal = ",";
	$pemisah_ribuan = ".";

 	if(isset($_GET['id'])) {

 		$id = $_GET['id'];


 		extract($produk->getID($id));

 		$query = "SELECT tbl_kategori.nama_kategori FROM tbl_produk RIGHT JOIN tbl_kategori ON (tbl_produk.id_kategori=tbl_kategori.id_kategori)";

 		$stmt = $db->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

 	}

 	if(isset($_POST['addtocart'])) {

 		$total_harga = $_POST['harga'] * $_POST['kuantitas'];
 		try {
	    	$produk->addtoCart(array(
	    		'id_produk' => $_POST['id_produk'],
	    		'id_penjual' => $_POST['id_penjual'],
	    		'id_pembeli' => $_POST['id_pembeli'],
	    		'harga' => $total_harga,
	    		'kuantitas' => $_POST['kuantitas']
	    	));
	    	//header("refresh:0");
	    	header("location: cart.php");
	    } catch (Exception $e) {
			die($e->getMessage());
		}
 	}

?>
<!-- //breadcrumbs -->
<div class="products">
	<div class="container">
		<div class="agileinfo_single">
			
			<div class="col-md-3 agileinfo_single_left">
				<img id="example" src="asset/img_produk/<?php echo $gambar; ?>" alt=" " class="img-responsive">
			</div>
			<div class="col-md-8 agileinfo_single_right">
			<h2><?php echo $nama_produk; ?></h2>
				
				<div class="w3agile_description">
					<h4>Kategori :</h4>
					<p><?php print($row['nama_kategori']); ?></p>
					<h4>Penjual :</h4>
					<p><a href="informasi.php?id=<?php echo $id_seller; ?>"><?php echo $nama; ?></a></p>
					<h4>Alamat :</h4>
					<p><?php print($alamat); ?></p>
					<h4>Deskripsi :</h4>
					<p><?php echo $deskripsi; ?></p>
					<form method="post">
					<h4>Jumlah :</h4>
					<p class="qty"></p><input type="number" name="kuantitas" value="1">
				</div>
				<div class="snipcart-item block">
					<div class="snipcart-thumb agileinfo_single_right_snipcart">
						<h4 class="m-sing"><?php echo "Rp ".number_format($harga,$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></h4>
					</div>
					<div class="snipcart-details agileinfo_single_right_details">
						
							<fieldset>
								<input type="hidden" value="<?php echo $id_produk ?>" name="id_produk">
								<input type="hidden" value="<?php echo $id_seller ?>" name="id_penjual">
								<input type="hidden" value="<?php echo $curUser['id_user'] ?>" name="id_pembeli">
								<input type="hidden" value="<?php echo $harga; ?>" name="harga">
								<?php
				                    if($user->isUserLoggedIn()){

				                        ?>
				                            <input type="submit" name="addtocart" value="Add to cart" class="button">
				                        <?php
				                    } else {
				                    	?>
				                           Silahkan <a href="login.php"> Login</a> terlebih dahulu...
				                        <?php
				                    }
				                ?>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
</div>

<?php

	include "footer.php";

?>