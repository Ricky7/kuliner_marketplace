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

?>

<?php
  include "a_header.php";
?>
<h2 class="page-header">
  Pesanan Dibayar
</h2>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID Pesanan</th>
        <th>Tanggal Pesanan</th>
        <th>Status Pesanan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $query = "SELECT * FROM tbl_order WHERE status_order='PAID' ORDER BY tgl_order DESC";       
        $records_per_page=5;
        $newquery = $produk->paging($query,$records_per_page);
        $produk->daftarOrder($newquery);
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

<?php
  include "a_footer.php";
?>