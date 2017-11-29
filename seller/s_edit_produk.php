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
  $datax = $produk->getKategori();

  if(isset($_REQUEST['id']))
  {
      $id = $_REQUEST['id'];
      extract($produk->getProdukID($id)); 
  }

  if(isset($_POST['submit'])) {

      $imgFile = $_FILES['gambar']['name'];
      $tmp_dir = $_FILES['gambar']['tmp_name'];
      $imgSize = $_FILES['gambar']['size'];


      if(empty($imgFile)) {
        
        try {
            $produk->editProduk(array(
              'id_kategori' => $_POST['kategori'],
              'nama_produk' => $_POST['nama_produk'],
              'harga' => $_POST['harga'],
              'deskripsi' => $_POST['deskripsi']
            ), $id);
            header("location: s_myproduk.php");
          } catch (Exception $e) {
            die($e->getMessage());
          }

      } else {
        $upload_dir = '../asset/img_produk/'; // upload directory
 
        $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
      
        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
      
        // rename uploading image
        $userpic = rand(1000,1000000).".".$imgExt;

        // allow valid image file formats
        if(in_array($imgExt, $valid_extensions)){   
            // Check file size '5MB'
            if($imgSize < 5000000)    {
              move_uploaded_file($tmp_dir,$upload_dir.$userpic);
            } else {
              $errMSG = "Sorry, your file is too large.";
            }
        } else {
            $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";  
        }

        if(!isset($errMsg)) {

            try {
              $produk->editProduk(array(
                'id_kategori' => $_POST['kategori'],
                'nama_produk' => $_POST['nama_produk'],
                'harga' => $_POST['harga'],
                'deskripsi' => $_POST['deskripsi'],
                'gambar' => $userpic
              ), $id);
              header("location: s_myproduk.php");
            } catch (Exception $e) {
            die($e->getMessage());
            }
        }

        }
      
    }

?>

<?php
  include "s_header.php";
?>

<h1 class="page-header">
      Add Produk <a href="s_myproduk.php"><span class="glyphicon glyphicon-list-alt"></span></a>
    </h1>
<div class="row placeholders">
  <div class="col-md-4">
      <form method="post" enctype="multipart/form-data">
          <input type="text" name="nama_produk" class="form-control input-sm chat-input" value="<?php echo $nama_produk; ?>" placeholder="Nama Produk" required/>
          </br>
          <input type="text" name="harga" class="form-control input-sm chat-input" value="<?php echo $harga; ?>" placeholder="Harga" required/>
          </br>
          <input type="file" id="imgcheck" name="gambar" class="form-control border-input" placeholder="Gambar" accept="image/*"><input type="checkbox" id="isNews" name="isNews" onclick="document.getElementById('imgcheck').disabled=this.checked;"/> <small>Check bila gambar tidak ikut diubah..</small><br>
          
          <?php echo $errMsg; ?>
          </br>
          <select class="form-control border-input" id="sel1" name="kategori" required>
              <option></option>
              <?php foreach ($datax as $value): ?>
              <option value="<?php echo $value['id_kategori']; ?>"><?php echo $value['nama_kategori']; ?></option>
              <?php endforeach; ?>
          </select>
          </br>
          <textarea name="deskripsi" rows="5" class="form-control" placeholder="Deskripsi Kategori" required><?php echo $deskripsi; ?></textarea>
          </br>
          <div class="wrapper">
              <span class="group-btn">     
                  <input type="submit" name="submit" class="btn btn-primary btn-md" value="Submit">
              </span>
          </div>
      </form>
  </div>
  <div class="col-md-4">
    <img src="../asset/img_produk/<?php echo $gambar; ?>">
  </div>
</div>

<hr>
<script>
$('#isNews').change(function(){
   $("#newsSource").prop("disabled", !$(this).is(':checked'));
});
</script>
<?php
  include "s_footer.php";
?>
          
      