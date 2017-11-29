<?php

	require_once "class/db.php";
  	require_once "class/User.php";
  	require_once "class/Produk.php";

  	$user = new User($db);

  	$produk = new Produk($db);

  	//cek jika belum login, maka kembalikan ke hal. login
  	if(!$user->isUserLoggedIn()) {
  		header("Location: login.php");
  	}

  	$curUser = $user->getUser();

  	// format mata uang
 	$jumlah_desimal = "0";
	$pemisah_desimal = ",";
	$pemisah_ribuan = ".";

?>

<?php

	include "header.php";

?>

<!-- breadcrumbs -->
<div class="breadcrumbs">
	<div class="container">
		<ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
			<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Beranda</a></li>
			<li class="active">Keranjang Belanja</li>
		</ol>
	</div>
</div>
<!-- //breadcrumbs -->

<!--content -->
<div class="checkout">
	<div class="container">
		<?php
	        $query = "SELECT tbl_user.id_user,tbl_user.nama FROM tbl_produk INNER JOIN tbl_cart INNER JOIN tbl_user ON (tbl_produk.id_produk = tbl_cart.id_produk) AND (tbl_user.id_user=tbl_cart.id_penjual) WHERE tbl_cart.id_pembeli = {$curUser['id_user']} GROUP BY tbl_user.nama";

	 		$stmt = $db->prepare($query);
			$stmt->execute();
	    
	        if($stmt->rowCount()>0)
	        {
	            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
	            {
	                ?>
					<h2>Produk dijual Oleh: <span><?php echo $row['nama']; ?> Shop</span></h2>
					<div class="checkout-right">
						<table class="timetable_sub">
							<thead>
								<tr>
									<th>Produk</th>
									<th>Jumlah</th>
									<th>Nama Produk</th>
									<th>Harga</th>
									<th>Aksi</th>
								</tr>
							</thead>
					<?php
					$query2 = "SELECT tbl_cart.id_cart,tbl_produk.id_produk,tbl_produk.gambar,tbl_produk.nama_produk,tbl_cart.harga,tbl_cart.kuantitas FROM tbl_cart INNER JOIN tbl_produk ON (tbl_cart.id_produk=tbl_produk.id_produk) WHERE tbl_cart.id_penjual={$row['id_user']} AND tbl_cart.id_pembeli={$curUser['id_user']}";
					$stmt2 = $db->prepare($query2);
					$stmt2->execute();
			    
			        if($stmt2->rowCount()>0)
			        {
			            while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
			            {
			            ?>

			            	<tr class="rem1">
								<td class="invert-image"><a href="single.php?id=<?php echo $row2['id_produk'] ?>"><img src="asset/img_produk/<?php echo $row2['gambar']; ?>" alt=" " class="img-responsive" /></a></td>
								<td class="invert"><?php echo $row2['kuantitas'] ?></td>
								<td class="invert"><?php echo $row2['nama_produk'] ?></td>
								
								<td class="invert"><?php echo "Rp ".number_format($row2['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
								<td class="invert">
									<a href="#" data-href="del_cart.php?id=<?php echo $row2['id_cart']; ?>" data-toggle="modal" data-target="#confirm-delete">
									<div class="rem">
										<div class="close1"> </div>
									</div></a>
								</td>
							</tr>

			            <?php
			            }
			    	}
			    	?>
			    		<tr>
			    			<td colspan="5">
			    				<div class="snipcart-details top_brand_home_details">
				    				<a href="order.php?slug=<?php echo $row['id_user'] ?>">
	                                    <input type="submit" value="Lanjut ke Pemesanan" class="button" style="height: 40px; width: 400px"/>
	                                </a>
	                            </div>
			    			</td>
			    		</tr>
		            	</table>
		            	<div class="clearfix"> <br><br></div>
		            <?php
	            }
	    	}
	    ?>
		
		</div>
		<div class="checkout-left">	
			<div class="checkout-left-basket">
				<a href="index.php"><h4>Lanjut Belanja</h4></a>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
</div>
<!-- content -->

<!-- Modal Delete -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Hapus Produk</h4>
            </div>
        
            <div class="modal-body">
                <p>Kamu terlihat akan menghapus produk ini dari keranjang belanja..</p>
                <p>Apakah kamu ingin melanjutkan?</p>
                <!-- <p class="debug-url"></p> -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <a class="btn btn-danger btn-ok">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- Script Modal Delete -->
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    
    $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');

});
</script>

<?php

	include "footer.php";

?>