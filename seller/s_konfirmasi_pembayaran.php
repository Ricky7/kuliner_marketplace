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

  if(isset($_POST['submit'])) {

      try {
        $produk->goToPaid($_POST['konfirmasi'], $id_order);
        header("Location: s_order_paying.php");
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
            <td>Total Pembayaran</td>
            <td><?php echo "Rp ".number_format($row2['total']+$row['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
          </tr>
          <tr>
            <td>Nama Rekening</td>
            <td>Erta Sianturi</td>
          </tr>
          <tr>
            <td>No Rek pengirim</td>
            <td>456-5067056</td>
          </tr>
          <tr>
            <td>Nama Bank</td>
            <td>BNI</td>
          </tr>
          <form method="post">
          <tr>
            <td>Konfirmasi</td>
            <td>
              <select name="konfirmasi" class="form-control" required>
                <option></option>
                <option value="FINISH">Dibayar</option>
              </select>
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

<hr>

<?php
  include "s_footer.php";
?>
          
      