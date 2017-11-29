<?php
  error_reporting(0);
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
  Laporan Penjualan
</h1>

<div class="container">
  <div class="row">
    <form method="get">
    <div class="col-md-4">
      <input type="date" name="tgl_awal" class="form-control">
    </div>
    <div class="col-md-4">
      <input type="date" name="tgl_akhir" class="form-control">
    </div>
    <div class="col-md-4">
      <div class="wrapper">
          <span class="group-btn">     
              <input type="submit" name="submit" class="btn btn-primary btn-md" value="Cari">
          </span>
      </div>
    </div>
    </form>
  </div>
  <?php
    $tgl = $_REQUEST['tgl_awal'].' - '.$_REQUEST['tgl_akhir'];
  ?>
  <div class="row" style="padding-top:30px;">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nama Produk</th>
            <th>Jumlah Terjual</th>
            <th>Harga</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if(!$_REQUEST['tgl_awal'] == NULL && !$_REQUEST['tgl_akhir'] == NULL) {

                $tgl_awal = $_REQUEST['tgl_awal'];
                $tgl_akhir = $_REQUEST['tgl_akhir'];
                $tgl = $tgl_awal.' - '.$tgl_akhir;

                $query = "SELECT tbl_produk.nama_produk, SUM(tbl_order_detail.kuantitas) as xkuantitas, SUM(tbl_order_detail.harga) as xharga FROM tbl_order INNER JOIN tbl_order_detail INNER JOIN tbl_produk ON (tbl_order.id_order=tbl_order_detail.id_order) AND (tbl_order_detail.id_produk=tbl_produk.id_produk) WHERE date(tbl_order.tgl_order) BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}' AND tbl_order.id_penjual={$getID} AND tbl_order.status_order='FINISH' GROUP BY tbl_produk.id_produk";       
                $records_per_page=5;
                $newquery = $produk->paging($query,$records_per_page);
                $produk->laporanPenjualan($newquery);
              ?>
              <tr>
                  <td colspan="7" align="center">
                <div class="pagination-wrap">
                      <?php $produk->paginglink($query,$records_per_page); ?>
                    </div>
                  </td>
              </tr>
              <?php
            } else {
          ?>
            <div class="col-md-12 card">
              <div class="header">
                  <h4 class="title">Gunakan form pencarian...</h4>
                  <br>
              </div>
            </div>
          <?php
        }
                          
         ?>
        </tbody>
      </table>
    </div>
  </div>

</div>




<?php
  include "s_footer.php";
?>
          
      