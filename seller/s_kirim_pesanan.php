<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";
  require_once "../class/Produk.php";

  $user = new User($db);

  $datas = $user->getUser();
  $getID = $datas['id_user'];

  $user->cekUserLogin();

  $produk = new Produk($db);

  $id_order = $_REQUEST['slug'];

  // format mata uang
  $jumlah_desimal = "0";
  $pemisah_desimal = ",";
  $pemisah_ribuan = ".";

  date_default_timezone_set('Asia/Jakarta');
  $tanggal_kirim = date('Y-m-d H:i:s');

  if(isset($_POST['submit'])) {

      try {
        $produk->kirimProduk(array(
          'id_order' => $id_order,
          'alamat_kirim' => $_POST['alamat_kirim'],
          'tgl_kirim' => $tanggal_kirim,
          'nama_driver' => $_POST['nama_driver'],
          'no_plat' => $_POST['no_plat']
        ), $id_order);
        header("Location: s_order_pending.php");
      } catch (Exception $e) {
      die($e->getMessage());
    }
  }

?>

<?php
  include "s_header.php";
?>
    
<h1 class="page-header">
  Proses Pengiriman
</h1>

<?php
  $query = "SELECT * FROM tbl_order INNER JOIN tbl_user ON (tbl_order.id_pembeli=tbl_user.id_user) 
  WHERE tbl_order.id_order={$id_order}";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);

  $query2 = "SELECT SUM(harga) as total FROM tbl_order_detail WHERE id_order={$id_order}";
  $stmt2 = $db->prepare($query2);
  $stmt2->execute();
  $row2=$stmt2->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
  <div class="col-md-2"></div>
  <div class="col-md-8">
    <div class="table-responsive">
      <table class="table">
        <tbody>
          <tr>
            <td>ID Pesanan</td>
            <td><?php echo $row['id_order'] ?></td>
          </tr>
          <tr>
            <td>Total Belanja</td>
            <td><?php echo "Rp ".number_format($row2['total'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
          </tr>
          <tr>
            <td>Nama Pembeli</td>
            <td><?php echo $row['nama'] ?></td>
          </tr>
          <tr>
            <td>No HP Pembeli</td>
            <td><?php echo $row['no_hp'] ?></td>
          </tr>
          <tr>
            <td>Alamat Tujuan</td>
            <td><?php echo $row['alamat'] ?></td>
          </tr>
          <tr>
            <td>Deskripsi Pesanan</td>
            <td><?php echo $row['desk_order']; ?></td>
          </tr>
          <tr>
            <td>Jarak Pengiriman</td>
            <td><?php echo $row['jarak'].' KM' ?></td>
          </tr>
          <tr>
            <td>Ongkos Pengiriman</td>
            <td><?php echo "Rp ".number_format($row['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
          </tr>
          <form method="post">
          <tr>
            <td>Nama Driver</td>
            <td>
              <input type="text" name="nama_driver" class="form-control input-sm chat-input" placeholder="Nama Driver Gojek" required/>
            </td>
          </tr>
          <tr>
            <td>No Plat Gojek</td>
            <td>
              <input type="text" name="no_plat" class="form-control input-sm chat-input" placeholder="Nomor Plat Gojek" required/>
              <input type="hidden" name="alamat_kirim" value="<?php echo $row['alamat'] ?>">
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" name="submit" value="Proses" class="form-control btn btn-primary btn-md">
            </td>
          </tr>
        </form>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-2"></div>
</div>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Gambar Produk</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $query = "SELECT tbl_produk.gambar,tbl_produk.nama_produk,tbl_order_detail.harga,tbl_order_detail.kuantitas FROM tbl_order_detail INNER JOIN tbl_produk 
        ON (tbl_order_detail.id_produk = tbl_produk.id_produk) WHERE tbl_order_detail.id_order={$id_order}";       
        $records_per_page=10;
        $newquery = $produk->paging($query,$records_per_page);
        $produk->infoProduk($newquery);
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

<hr>

<?php
  include "s_footer.php";
?>
          
      