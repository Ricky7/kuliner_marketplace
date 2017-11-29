<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";

  $user = new User($db);
  $datas = $user->getUser();

  $user->cekUserLogin();

?>

<?php
  include "s_header.php";
?>
<h1 class="page-header">
      Dashboard
    </h1>
<div class="row placeholders">
  <div class="col-xs-6 col-sm-3 placeholder text-center">
    <img src="//placehold.it/200/6666ff/fff" class="center-block img-responsive img-circle" alt="Generic placeholder thumbnail">
    <h4>Label</h4>
    <span class="text-muted">Something else</span>
  </div>
  <div class="col-xs-6 col-sm-3 placeholder text-center">
    <img src="//placehold.it/200/66ff66/fff" class="center-block img-responsive img-circle" alt="Generic placeholder thumbnail">
    <h4>Label</h4>
    <span class="text-muted">Something else</span>
  </div>
  <div class="col-xs-6 col-sm-3 placeholder text-center">
    <img src="//placehold.it/200/6666ff/fff" class="center-block img-responsive img-circle" alt="Generic placeholder thumbnail">
    <h4>Label</h4>
    <span class="text-muted">Something else</span>
  </div>
  <div class="col-xs-6 col-sm-3 placeholder text-center">
    <img src="//placehold.it/200/66ff66/fff" class="center-block img-responsive img-circle" alt="Generic placeholder thumbnail">
    <h4>Label</h4>
    <span class="text-muted">Something else</span>
  </div>
</div>

<hr>

<?php
  include "s_footer.php";
?>
          
      