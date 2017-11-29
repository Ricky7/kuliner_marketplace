<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";
  require_once "../class/Kategori.php";

  $user = new User($db);

  $kategori = new Kategori($db);

  $datas = $user->getAdmin();
  $id_pengurus = $datas['id_admin'];

  if(isset($_REQUEST['id']))
  {
      $id = $_REQUEST['id'];
      extract($kategori->getKategoriID($id)); 
  }

  $user->cekLogin();

  if(isset($_POST['submit'])) {

      try {
        $kategori->editKategori(array(
          'nama_kategori' => $_POST['nama_kategori'],
          'desk_kategori' => $_POST['desk_kategori']
        ), $id);
        header("Location: a_kategori.php");
      } catch (Exception $e) {
      die($e->getMessage());
    }
  }

?>

<?php
  include "a_header.php";
?>

<h2 class="page-header">
      Edit Kategori
    </h2>
<div class="row placeholders">
  <div class="col-md-4">
      <form method="post">
          <input type="text" name="nama_kategori" class="form-control input-sm chat-input" value="<?php echo $nama_kategori; ?>" placeholder="Nama Kategori" required/>
          </br>
          <textarea name="desk_kategori" class="form-control" placeholder="Deskripsi Kategori" required><?php echo $desk_kategori; ?></textarea>
          </br>
          <div class="wrapper">
              <span class="group-btn">     
                  <input type="submit" name="submit" class="btn btn-primary btn-md" value="Submit">
              </span>
          </div>
      </form>

  </div>
</div>

<?php
  include "a_footer.php";
?>
          
      