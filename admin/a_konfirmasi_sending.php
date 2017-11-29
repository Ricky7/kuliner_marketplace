<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";
  require_once "../class/Kategori.php";
  require_once "../class/Produk.php";

  $user = new User($db);

  $kategori = new Kategori($db);

  $produk = new Produk($db);

  $datas = $user->getAdmin();
  $id_pengurus = $datas['id_admin'];

  $user->cekLogin();

  $id_order = $_REQUEST['slug'];

  // format mata uang
  $jumlah_desimal = "0";
  $pemisah_desimal = ",";
  $pemisah_ribuan = ".";

  if(isset($_POST['submit'])) {

      try {
        $produk->goToPaid($_POST['status'], $id_order);
        header("Location: a_order_sending.php");
      } catch (Exception $e) {
      die($e->getMessage());
    }
  }

?>

<?php
  include "a_header.php";
?>
<h2 class="page-header">
  Konfirmasi Pesanan Tekirim
</h2>

<?php
  $que = "SELECT * FROM tbl_order INNER JOIN tbl_kirim ON (tbl_order.id_kirim=tbl_kirim.id_kirim) WHERE tbl_order.id_order={$id_order}";
  $stmtq = $db->prepare($que);
  $stmtq->execute();
  $rowName=$stmtq->fetch(PDO::FETCH_ASSOC);

  $query = "SELECT SUM(harga) as total FROM tbl_order_detail WHERE id_order={$id_order}";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="row">
  <div class="col-md-2"></div>
  <div class="col-md-8">
    <div class="table-responsive">
      <table class="table">
        <tbody>
          <tr>
            <td>ID Pesanan</td>
            <td><?php echo $rowName['id_order'] ?></td>
          </tr>
          <tr>
            <td>Total Belanja + Ongkir</td>
            <td><?php echo "Rp ".number_format($row['total']+$rowName['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
          </tr>
          <tr>
            <td>Tanggal Kirim</td>
            <td><?php echo $rowName['tgl_kirim'] ?></td>
          </tr>
          <tr>
            <td>Nama Driver</td>
            <td><?php echo $rowName['nama_driver'] ?></td>
          </tr>
          <tr>
            <td>No Plat Driver</td>
            <td><?php echo $rowName['no_plat'] ?></td>
          </tr>
          <form method="post">
          <tr>
            <td>Opsi</td>
            <td>
              <select name="status" class="form-control" required>
                <option></option>
                <option value="SENT">Terkirim</option>
                <option value="SENDING">Belum Terkirim</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" name="submit" value="Konfirmasi" class="form-control btn btn-primary btn-md">
            </td>
          </tr>
        </form>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-2"></div>
</div>


<?php
  include "a_footer.php";
?>