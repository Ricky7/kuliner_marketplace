<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";
  require_once "../class/Kategori.php";

  $user = new User($db);

  $kategori = new Kategori($db);

  $datas = $user->getAdmin();
  $id_pengurus = $datas['id_admin'];

  $user->cekLogin();

  if(isset($_POST['submit'])) {

      try {
        $kategori->insertKategori(array(
          'nama_kategori' => $_POST['nama_kategori'],
          'desk_kategori' => $_POST['desk_kategori']
        ));
        header("Refresh:0");
      } catch (Exception $e) {
      die($e->getMessage());
    }
  }

?>

<?php
  include "a_header.php";
?>
<script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script data-require="bootstrap@*" data-semver="3.1.1" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<link data-require="bootstrap-css@3.1.1" data-semver="3.1.1" rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<h2 class="page-header">
      Daftar Kategori
    </h2>
<div class="row placeholders">
  <div class="col-md-4">
      <form method="post">
          <input type="text" name="nama_kategori" class="form-control input-sm chat-input" placeholder="Nama Kategori" required/>
          </br>
          <textarea name="desk_kategori" class="form-control" placeholder="Deskripsi Kategori" required></textarea>
          </br>
          <div class="wrapper">
              <span class="group-btn">     
                  <input type="submit" name="submit" class="btn btn-primary btn-md" value="Submit">
              </span>
          </div>
      </form>

  </div>
</div>

<hr>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Nama Kategori</th>
        <th>Deskripsi</th>
        <th colspan="2">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $query = "SELECT * FROM tbl_kategori";       
        $records_per_page=5;
        $newquery = $kategori->paging($query,$records_per_page);
        $kategori->listKategori($newquery);
      ?>
      <tr>
          <td colspan="7" align="center">
        <div class="pagination-wrap">
              <?php $kategori->paginglink($query,$records_per_page); ?>
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
          
      