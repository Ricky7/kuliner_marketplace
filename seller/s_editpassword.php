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
  Ubah Password
</h1>

<div class="row">
  <div class="col-md-12">
    <?php
      if(isset($_POST['submit'])) {

          try {
              $user->ubahPassUser($getID, $_POST['old_password'], $_POST['new_password']);
              //header("location: kasir_ubah_password.php?changed");
            } catch (Exception $e) {
              //die($e->getMessage());

            }
        }
    ?>
  </div>
</div>
<div class="row placeholders">
  <div class="col-md-4">
      <form method="post" enctype="multipart/form-data">
          <input type="password" name="old_password" class="form-control input-sm chat-input" placeholder="Password Lama" required/>
          </br>
          <input type="password" name="new_password" class="form-control input-sm chat-input" placeholder="Password Bary" required/>
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

<?php
  include "s_footer.php";
?>
          
      