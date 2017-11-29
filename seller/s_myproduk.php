<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";
  require_once "../class/Produk.php";

  $user = new User($db);

  $datas = $user->getUser();
  $getID = $datas['id_user'];

  $user->cekUserLogin();

  $produk = new Produk($db);

  if(isset($_POST['ubah'])) {

      try {
        //$produk->ubahStatus($_POST['status'], $_POST['id_produk']);
        $produk->editProduk(array(
            'status' => $_POST['status']
        ), $_POST['id_produk']);
        header("location: s_myproduk.php");
      } catch (Exception $e) {
      die($e->getMessage());
    }
  }

?>

<?php
  include "s_header.php";
?>
    <script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <script data-require="bootstrap@*" data-semver="3.1.1" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <link data-require="bootstrap-css@3.1.1" data-semver="3.1.1" rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<h1 class="page-header">
      Daftar Produk <a href="s_addproduk.php"><span class="glyphicon glyphicon-pencil"></span></a>
    </h1>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Produk</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Status</th>
        <th colspan="2">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $query = "SELECT * FROM tbl_produk WHERE id_seller={$getID}";       
        $records_per_page=15;
        $newquery = $produk->paging($query,$records_per_page);
        $produk->listMYproduk($newquery);
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

<!-- Modal Delete -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
            </div>
        
            <div class="modal-body">
                <p>You are about to delete one track, this procedure is irreversible.</p>
                <p>Do you want to proceed?</p>
                <p class="debug-url"></p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Status -->
<div class="modal fade" id="confirm-status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Ubah Status Produk</h4>
            </div>

            <form method="post">
              <div class="modal-body">
                  <select name="status" class="form-control">
                    <option value="1">Tersedia</option>
                    <option value="0">Tidak Tersedia</option>
                  </select>
                  <input type="hidden" name="id_produk" class="debug-url">
              </div>
              
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger btn-ok" name="ubah">Ubah</a>
              </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    
    $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');

});

$('#confirm-status').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    
    $('.debug-url').val($(this).find('.btn-ok').attr('href'));

});
</script>
<?php
  include "s_footer.php";
?>
          
      