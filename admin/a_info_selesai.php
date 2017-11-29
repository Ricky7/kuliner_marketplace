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

?>

<?php
  include "a_header.php";
?>
<h2 class="page-header">
  Informasi Transaksi
</h2>

<?php
  $que = "SELECT * FROM tbl_order WHERE id_order={$id_order}";
  $stmtq = $db->prepare($que);
  $stmtq->execute();
  $rowName=$stmtq->fetch(PDO::FETCH_ASSOC);

  $buy = "SELECT * FROM tbl_user WHERE id_user={$rowName['id_pembeli']}";
  $stmtb = $db->prepare($buy);
  $stmtb->execute();
  $rowBuy=$stmtb->fetch(PDO::FETCH_ASSOC);

  $sell = "SELECT * FROM tbl_user WHERE id_user={$rowName['id_penjual']}";
  $stmts = $db->prepare($sell);
  $stmts->execute();
  $rowSell=$stmts->fetch(PDO::FETCH_ASSOC);

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
            <td>Nama Pembeli</td>
            <td><?php echo $rowBuy['nama'] ?></td>
          </tr>
          <tr>
            <td>No HP Pembeli</td>
            <td><?php echo $rowBuy['no_hp'] ?></td>
          </tr>
          <tr>
            <td>No Rekening</td>
            <td><?php echo $rowName['no_rek'] ?></td>
          </tr>
          <tr>
            <td>Nama Rekening</td>
            <td><?php echo $rowName['nama_rek'] ?></td>
          </tr>
          <tr>
            <td>Nama Bank</td>
            <td><?php echo $rowName['nama_bank'] ?></td>
          </tr>
          <tr>
            <td>Nominal Transfer</td>
            <td><?php echo "Rp ".number_format($rowName['nominal'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
          </tr>
          <tr>
            <td>Detail Transfer</td>
            <td><?php echo $rowName['detail_transfer'] ?></td>
          </tr>
           <tr>
            <td>Nama Penjual</td>
            <td><?php echo $rowSell['nama'] ?></td>
          </tr>
          <tr>
            <td>No HP Penjual</td>
            <td><?php echo $rowSell['no_hp'] ?></td>
          </tr>
          <tr>
            <td colspan="2">
              <img src="../asset/bukti_transfer/<?php echo $rowName['bukti_transfer'] ?>" class="img-responsive">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-2"></div>
</div>


<?php
  include "a_footer.php";
?>