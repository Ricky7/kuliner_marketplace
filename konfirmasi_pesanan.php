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

    $id_order = $_REQUEST['slug'];

    if(isset($_POST['submit'])) {

        try {
          $produk->goToPaid($_POST['konfirmasi'], $id_order);
          header("Location: order_selesai.php");
        } catch (Exception $e) {
        die($e->getMessage());
      }
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
      <li class="active">Konfirmasi Pesanan</li>
    </ol>
  </div>
</div>
<!-- //breadcrumbs -->

<!-- content tabel 1 -->
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
      
          $query = "SELECT * FROM tbl_order INNER JOIN tbl_user ON (tbl_order.id_penjual=tbl_user.id_user) WHERE (tbl_order.id_order = {$id_order})";

          $stmt = $db->prepare($query);
          $stmt->execute();
          $row=$stmt->fetch(PDO::FETCH_ASSOC);
        ?>
          <tr class="rem1">
            <td class="invert-image"><?php echo $row['tgl_order'] ?></td>
            <td class="invert"><?php echo $row['nama'] ?></td>
            <td class="invert"><?php echo $row['jarak'].' KM' ?></td>              
            <td class="invert"><?php echo "Rp ".number_format($row['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
            <td class="invert"><?php echo $row['status_order'] ?></td>
          </tr>
        </table>
    <div class="clearfix"></div>
  </div>
</div>

<!-- content tabel 2 -->
<div class="checkout">
  <div class="container">
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
          $id_order = $_REQUEST['slug'];
          $ongkir = $row['ongkir'];
      
          $query2 = "SELECT tbl_produk.gambar,tbl_produk.nama_produk,tbl_order_detail.kuantitas,tbl_order_detail.harga FROM tbl_order_detail INNER JOIN tbl_produk ON (tbl_order_detail.id_produk=tbl_produk.id_produk) WHERE tbl_order_detail.id_order = {$id_order}";

          $stmt2 = $db->prepare($query2);
          $stmt2->execute();
      
          if($stmt2->rowCount()>0)
          {
              while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
              {
                  ?>
                    <tr class="rem1">
                      <td class="invert-image"><a href="#"><img src="asset/img_produk/<?php echo $row2['gambar']; ?>" alt=" " class="img-responsive" /></a></td>
                      <td class="invert"><?php echo $row2['kuantitas'] ?></td>
                      <td class="invert"><?php echo $row2['nama_produk'] ?></td>              
                      <td class="invert"><?php echo "Rp ".number_format($row2['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
                    </tr>
                <?php
              }
        }
      ?>
      <?php
          $getHarga = $db->prepare("SELECT SUM(harga) as xtotal FROM tbl_order_detail  WHERE id_order=:id");
          $getHarga->execute(array(":id"=>$id_order));
          $xharga = $getHarga->fetch(PDO::FETCH_ASSOC);
      ?>
        <tr>
          <td colspan="3"><b>TOTAL YANG HARUS DIBAYAR</b></td>
          <td><?php echo "Rp. ".number_format($xharga['xtotal']+$row['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
        </tr>
        </table>
      <div class="clearfix"> <br><br></div>
    </div>

    <?php if($row['status_order'] == 'SENDING') {

      ?>
    <form method="post">
      <div class="row" style="padding-bottom:10px">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <div class="control-group">
          <label class="control-label">Konfirmasi Pesanan</label>
            <select class="form-control border-input" name="konfirmasi" required>
              <option></option>
              <option value="SENT">Produk diterima</option>
            </select>
          </div>
        </div>
        <div class="col-md-4"></div>
      </div>

      <div class="row" style="padding-bottom:10px">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <div class="control-group">
            <div class="snipcart-details top_brand_home_details">
              <input type="submit" name="submit" value="Konfirmasi" class="button" style="height: 40px; width: 280px"/>
            </div> 
          </div>
        </div>
        <div class="col-md-4"></div>
      </div>
    </form>
      <?php

          }
    ?>
  </div>
</div>



<?php

  include "footer.php";

?>