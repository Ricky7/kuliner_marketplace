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

	$id_penjual = $_REQUEST['slug'];

	date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');

	if(isset($_POST['submit'])) {

	      try {
	        $produk->insertOrder(array(
	          'id_pembeli' => $curUser['id_user'],
	          'id_penjual' => $id_penjual,
	          'no_hp' => $_POST['no_hp'],
	          'alamat' => $_POST['alamat'],
	          'jarak' => $_POST['jarak'],
	          'ongkir' => $_POST['ongkir'],
	          'tgl_order' => $tanggal,
	          'desk_order' => $_POST['desk_order'],
	          'status_order' => 'NOT PAID'
	        ), $curUser['id_user'], $id_penjual);
	        header("Location: order_diproses.php");
	      } catch (Exception $e) {
	      die($e->getMessage());
	    }
	}

?>

<?php

	include "header.php";

?>
	<script src="asset/dist/jquery.addressPickerByGiro.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7zVeusOAU0YBF9JtwV97OXVM9dowacso&sensor=false&language=en"></script>
    <link href="asset/dist/jquery.addressPickerByGiro.css" rel="stylesheet" media="screen">

<!-- breadcrumbs -->
<div class="breadcrumbs">
	<div class="container">
		<ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
			<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Beranda</a></li>
			<li class="active">Pemesanan</li>
		</ol>
	</div>
</div>
<!-- //breadcrumbs -->

<div class="checkout">
	<div class="container">
		<?php

			$que = "SELECT * FROM tbl_user WHERE id_user={$id_penjual}";
			$stmtq = $db->prepare($que);
			$stmtq->execute();
			$rowName=$stmtq->fetch(PDO::FETCH_ASSOC);

		?>
		<h2>Produk dijual Oleh: <span><?php echo $rowName['nama']; ?> Shop</span></h2>
			<div class="checkout-right">
				<table class="timetable_sub">
					<thead>
						<tr>
							<th>Produk</th>
							<th>Jumlah</th>
							<th>Nama Produk</th>
							<th>Harga</th>
						</tr>
					</thead>
		<?php
			
	        $query = "SELECT tbl_produk.gambar,tbl_produk.nama_produk,tbl_cart.harga,tbl_cart.kuantitas 
					FROM tbl_cart INNER JOIN tbl_produk ON (tbl_cart.id_produk=tbl_produk.id_produk) 
					WHERE tbl_cart.id_penjual={$id_penjual} 
					AND tbl_cart.id_pembeli={$curUser['id_user']}";

	 		$stmt = $db->prepare($query);
			$stmt->execute();
	    
	        if($stmt->rowCount()>0)
	        {
	            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
	            {
	                ?>
						<tr class="rem1">
							<td class="invert-image"><a href="single.php?id=<?php echo $row['id_produk'] ?>"><img src="asset/img_produk/<?php echo $row['gambar']; ?>" alt=" " class="img-responsive" /></a></td>
							<td class="invert"><?php echo $row['kuantitas'] ?></td>
							<td class="invert"><?php echo $row['nama_produk'] ?></td>
							
							<td class="invert"><?php echo "Rp ".number_format($row['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
						</tr>
		            	
		            <?php
	            }
	    	}
	    ?>
	    	</table>
		<div class="clearfix"> <br><br></div>
		
		</div>
	</div>

	<div class="container">
      <div class="row-fluid">
        <div class="row-fluid">

			<form autocomplete="off" class="form-horizontal" method="post">
			<div class="row-fluid">
			  <div class="span9">
				<div class="row-fluid">
					<label class="control-label" for="inputAddress">Input Alamat</label>
					<div class="controls">
					  <input type="text" class="inputAddress input-xxlarge form-control" autocomplete="off" placeholder="Type in your address">
					</div>
				</div>			  

			  <div class="control-group">
    			<label class="control-label">Dikirim dari Alamat</label>
    			<div class="controls">
    			  <input type="text" class="form-control" value="<?php echo $rowName['alamat']; ?>" disabled="disabled">
    			</div>
    		  </div>	
				
			  <div class="control-group">
    			<label class="control-label">Dikirim ke Alamat</label>
    			<div class="controls">
    			  <input type="text" class="formatted_address input-xxlarge form-control" disabled="disabled">
    			</div>
    		  </div>
			
			  
			  <div class="control-group">
				<div class="controls">
				  <input type="hidden" class="latitude" id="latitude">
				  <input type="hidden" class="longitude" id="longitude">
				</div>
			  </div>

			  <!-- // koordinat penjual -->
			  <div class="control-group">
				<div class="controls">
				  <input type="hidden" value="<?php echo $rowName['x_lat']; ?>" id="x_lat">
				  <input type="hidden" value="<?php echo $rowName['x_long']; ?>" id="x_long">
				</div>
			  </div>
			  
			  <div class="row">
			  	<div class="col-md-4">
			  		<div class="control-group">
						<label class="control-label">Jarak Kirim /KM</label>
						<div class="controls">
							<input type="text" val="" name="jarak" id="distance" class="form-control" required>
						</div>
					</div>
			  	</div>
			  	<div class="col-md-4">
			  		<div class="control-group">
						<label class="control-label">Ongkos Kirim</label>
						<div class="controls">
							<input type="text" val="" name="ongkir" id="shipping_c" class="form-control" required>
						</div>
					</div>
			  	</div>
			  	<div class="col-md-4">
			  		<div class="control-group">
						<label class="control-label">Cek Ongkos Kirim</label>
						<div class="controls">
						  <a id="calculate" class="btn btn-success" name="calculate" >Calculate Shipping</a>
						</div>
					  </div>
			  	</div>
			  </div>

			  <div class="row">
			  	<div class="col-md-4">
			  		<div class="control-group">
						<label class="control-label">Alamat</label>
						<div class="controls">
							<textarea class="form-control" name="alamat" rows="4" required><?php echo $curUser['alamat'] ?></textarea>
						</div>
					</div>
			  	</div>
			  	<div class="col-md-4">
			  		<div class="control-group">
						<label class="control-label">No Kontak</label>
						<div class="controls">
							<input type="text" name="no_hp" value="<?php echo $curUser['no_hp'] ?>" class="form-control" required>
						</div>
					</div>
			  	</div>
			  	<div class="col-md-4">
			  		<div class="control-group">
						<label class="control-label">Deskripsi</label>
						<div class="controls">
						  <textarea class="form-control" name="desk_order" rows="4" Placeholder="Silahkan berikan catatan disini, bila ada permintaan khusus.."></textarea>
						</div>
					</div>
			  	</div>
			  </div>

			  <div class="row" style="padding-top:20px">
			  	<div class="col-md-5"></div>
			  	<div class="col-md-2">
			  		<div class="wrapper">
			            <span class="group-btn">     
			                <input type="submit" name="submit" class="btn btn-primary btn-md form-control" value="Submit">
			            </span>
			        </div>
			  	</div>
			  	<div class="col-md-5"></div>
			  </div>

			  </div>
			</div>
			</form>
          </div><!--/span-->
        <script>
            $('.inputAddress').addressPickerByGiro({
				distanceWidget: true,
                boundElements: {
                    'region': '.region',
                    'county': '.county',
                    'street': '.street',
                    'street_number': '.street_number',
                    'latitude': '.latitude',
                    'longitude': '.longitude',
                    'formatted_address': '.formatted_address'
                }
            });
        </script>
        </div><!--/span-->
      </div><!--/row-->
    </div><!--/.fluid-container-->
</div>
<!-- content -->

<script>

      	$("#calculate").click(function(){
        var lat = $("#latitude").val();
        var longs = $("#longitude").val();
        var x_lat = $("#x_lat").val();
        var x_long = $("#x_long").val();
        $.ajax({
            url: "http://localhost:9000/si_erta/ongkir_gojek.php",
            data: 'lat=' + lat + '&longs=' + longs + '&x_lat=' + x_lat + '&x_long=' + x_long,
            cache: false,
            dataType: 'json', 
            success: function(data){
              var d = data.distance;
              var s = data.shipping;
              $("#shipping_c").html(s);
              $("#distance").html(d);
              $('#shipping_c').val(s);
              $('#distance').val(d);
            }
        });
      	});
      	
</script>

<?php

	include "footer.php";

?>