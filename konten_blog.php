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

    $id_blog = $_REQUEST['slug'];
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
      <li class="active">Konten</li>
    </ol>
  </div>
</div>
<!-- //breadcrumbs -->

<!-- content -->
<div class="checkout">
  <div class="container">
    <div class="row" style="padding-bottom:20px;">
      <?php
        $query = "SELECT * FROM tbl_blog WHERE id_blog={$id_blog}";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
      ?>
      <p><?php echo $row['isi'] ?></p>
    </div>

    <div class="row-fluid">
    <div class="controls">
      <input type="text" value="<?php echo $row['lokasi'] ?>" class="inputAddress input-xxlarge form-control" autocomplete="off" disabled>
    </div>
  </div> 
  </div>
</div>
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
<?php

  include "footer.php";

?>