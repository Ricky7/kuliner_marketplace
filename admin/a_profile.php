<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";

  $user = new User($db);

  $datas = $user->getAdmin();
  $id_pengurus = $datas['id_admin'];

  $user->cekLogin();

?>

<?php
  include "a_header.php";
?>
<h1 class="page-header">
      Ubah Password
    </h1>
<div class="row placeholders">
  <div class="col-md-3">
      <?php

        if(isset($_POST['kirim'])) {

            try {
                $user->ubahPassword($id_pengurus, $_POST['pass_lama'], $_POST['pass_baru']);
                //header("location: kasir_ubah_password.php?changed");
              } catch (Exception $e) {
                //die($e->getMessage());

              }
          }

      ?>
      <form method="post">
          <input type="password" name="pass_lama" class="form-control input-sm chat-input" placeholder="password lama" />
          </br>
          <input type="password" name="pass_baru" class="form-control input-sm chat-input" placeholder="password baru" />
          </br>
          <div class="wrapper">
              <span class="group-btn">     
                  <input type="submit" name="kirim" class="btn btn-primary btn-md" value="Change">
              </span>
          </div>
      </form>

  </div>
</div>

<hr>

<?php
  include "a_footer.php";
?>
          
      