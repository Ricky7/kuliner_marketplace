<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";
  require_once "../class/Kategori.php";
  require_once "../class/Produk.php";

  $user = new User($db);

  $kategori = new Kategori($db);

  $produk = new Produk($db);

  $user->cekLogin();

?>

<?php
  include "a_header.php";
?>
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script data-require="bootstrap@*" data-semver="3.1.1" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<link data-require="bootstrap-css@3.1.1" data-semver="3.1.1" rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<h2 class="page-header">
  Daftar Konten <a href="a_buat_blog.php"><span class="glyphicon glyphicon-pencil"></span></a>
</h2>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Judul Blog</th>
        <th>Lokasi</th>
        <th>Jenis Konten</th>
        <th colspan="2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $query = "SELECT * FROM tbl_blog";       
        $records_per_page=5;
        $newquery = $produk->paging($query,$records_per_page);
        $user->daftarBlog($newquery);
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

<script type="text/javascript">
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    
    $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');

});
</script>

<?php
  include "a_footer.php";
?>