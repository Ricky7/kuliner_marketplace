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

  	if(isset($_GET['id'])) {

 		$id = $_GET['id'];

 		$query = "SELECT * FROM tbl_user WHERE id_user={$id}";

 		$stmt = $db->prepare($query);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);

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
			<li class="active">Informasi Penjual</li>
		</ol>
	</div>
</div>


<script src="asset/dist/jquery.addressPickerByGiro.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7zVeusOAU0YBF9JtwV97OXVM9dowacso&sensor=false&language=en"></script>
<link href="asset/dist/jquery.addressPickerByGiro.css" rel="stylesheet" media="screen">
<!-- //breadcrumbs -->
<div class="products">
	<div class="container">
		<div class="agileinfo_single">
			<div class="col-md-12 agileinfo_single_left">
				<h2 align="center"><?php echo $row['nama']; ?> Shop</h2>
				<div class="span9">
					<div class="row-fluid">
						<div class="controls">
						  <input type="text" class="inputAddress input-xxlarge form-control" autocomplete="off" value="<?php echo $row['alamat']; ?>" placeholder="Type in your address" disabled>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>

		<div class="agileinfo_single">
			<div class="col-md-12 agileinfo_single_left">
				<table class="table table-striped">
	                <thead></thead>
	                <tbody>
	                    <?php

		                    if(isset($_GET['id'])) {

	 							$id = $_GET['id'];
						        $query = "SELECT * FROM tbl_produk WHERE id_seller={$id}";       
						        $records_per_page=10;
						        $newquery = $produk->paging($query,$records_per_page);
						        $produk->indexProduk($newquery);
						    }
					      ?>
	                     <tr>
	                        <td colspan="7" align="center">
	                            <div class="pagination-wrap">
	                            <?php $produk->paginglink($query,$records_per_page); ?>
	                            </div>
	                        </td>
	                    </tr>
	                </tbody>
	            </table>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
</div>

<script>
  $('.inputAddress').addressPickerByGiro({
      distanceWidget: true,
      boundElements: {
          'latitude': '.latitude',
          'longitude': '.longitude'
      }
  });
</script>
<?php

	include "footer.php";

?>