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
      <li class="active">Pesanan Selesai</li>
    </ol>
  </div>
</div>
<!-- //breadcrumbs -->

<!-- content -->
<div class="checkout">
  <div class="container">
      <div class="checkout-right">
        <table class="timetable_sub">
          <thead>
            <tr>
              <th>Tanggal Order</th>
              <th>Penjual</th>
              <th>Jarak Antar</th>
              <th>Ongkir</th>
              <th>Status</th>
            </tr>
          </thead>
    <?php
      
          $query = "SELECT * FROM tbl_order INNER JOIN tbl_user ON (tbl_order.id_penjual=tbl_user.id_user) WHERE (tbl_order.id_pembeli, tbl_order.status_order) IN (({$curUser['id_user']}, 'SENT'), ({$curUser['id_user']}, 'FINISH')) ORDER BY tbl_order.status_order ASC";

          $stmt = $db->prepare($query);
          $stmt->execute();
      
          if($stmt->rowCount()>0)
          {
              while($row=$stmt->fetch(PDO::FETCH_ASSOC))
              {
                  ?>
            <tr class="rem1">
              <td class="invert-image"><?php echo $row['tgl_order'] ?></td>
              <td class="invert"><?php echo $row['nama'] ?></td>
              <td class="invert"><?php echo $row['jarak'].' KM' ?></td>              
              <td class="invert"><?php echo "Rp ".number_format($row['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
              <td class="invert"><a href="#"><?php echo $row['status_order'] ?></a></td>
            </tr>
                  
                <?php
              }
        }
      ?>
        </table>
    <div class="clearfix"> <br><br></div>
  </div>
</div>

<?php

  include "footer.php";

?>