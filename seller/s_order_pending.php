<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";
  require_once "../class/Produk.php";

  $user = new User($db);

  $datas = $user->getUser();
  $getID = $datas['id_user'];

  $user->cekUserLogin();

  $produk = new Produk($db);

?>

<?php
  include "s_header.php";
?>
    
<h1 class="page-header">
  Pesanan Tersedia
</h1>
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
        $query = "SELECT * FROM tbl_order WHERE id_penjual={$getID} AND status_order='PAID'";       
        $records_per_page=5;
        $newquery = $produk->paging($query,$records_per_page);
        $produk->orderPerSeller($newquery);
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
          
      