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

  if(isset($_POST['submit'])) {

      try {
        $user->updateUser(array(
          'nama' => $_POST['nama'],
          'no_hp' => $_POST['no_hp'],
          'alamat' => $_POST['alamat'],
          'no_rek' => $_POST['no_rek'],
          'nama_rek' => $_POST['nama_rek'],
          'nama_bank' => $_POST['nama_bank'],
          'x_lat' => $_POST['x_lat'],
          'x_long' => $_POST['x_long']
        ),$getID);
        header("Refresh:0");
      } catch (Exception $e) {
      die($e->getMessage());
    }
  }


?>

<?php
  include "s_header.php";
?>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="../asset/dist/jquery.addressPickerByGiro.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7zVeusOAU0YBF9JtwV97OXVM9dowacso&sensor=false&language=en"></script>
<link href="../asset/dist/jquery.addressPickerByGiro.css" rel="stylesheet" media="screen">
<h1 class="page-header">
  Profil 
</h1>

<!--content-->
<form method="post" enctype="multipart/form-data" autocomplete="off">
  <div class="row placeholders">
      <div class="col-md-4">
          <label>Nama</label>
          <input type="text" name="nama" value="<?php echo $datas['nama']; ?>" class="form-control input-sm chat-input" required/>
      </div>
      <div class="col-md-4">
          <label>Username</label>
          <input type="text" class="form-control input-sm chat-input" value="<?php echo $datas['username']; ?>" disabled/>
      </div>
      <div class="col-md-4">
          <label>Password</label>
          <input type="password" name="password" class="form-control input-sm chat-input" value="<?php echo $datas['password']; ?>" disabled/>
      </div>
  </div>
  
  <div class="row placeholders">
      <div class="col-md-4">
          <label>No Rekening</label>
          <input type="text" name="no_rek" class="form-control input-sm chat-input" value="<?php echo $datas['no_rek']; ?>" required/>
      </div>
      <div class="col-md-4">
          <label>Nama Rekening</label>
          <input type="text" name="nama_rek" class="form-control input-sm chat-input" value="<?php echo $datas['nama_rek']; ?>" required/>
      </div>
      <div class="col-md-4">
          <label>Nama Bank</label>
          <select class="form-control border-input" name="nama_bank">
            <option value="<?php echo $datas['nama_bank']; ?>"><?php echo $datas['nama_bank']; ?></option>
            <option value="BNI">BNI</option>
            <option value="BCA">BCA</option>
            <option value="MANDIRI">MANDIRI</option>
            <option value="BRI">BRI</option>
            <option value="SUMUT">SUMUT</option>
            <option value="CIMB">CIMB</option>
            <option value="BII">BII</option>
          </select>
      </div>
  </div>

  <div class="row placeholders">
      <div class="col-md-4">
          <label>No Telepon</label>
          <input type="text" name="no_hp" class="form-control input-sm chat-input" value="<?php echo $datas['no_hp']; ?>" required/>
      </div>
      <div class="col-md-4">
        <label>Latitude</label>
        <input type="text" name="x_lat" class="form-control latitude" id="latitude" value="<?php echo $datas['x_lat']; ?>" >
      </div>
      <div class="col-md-4">
        <label>Longitude</label>
        <input type="text" name="x_long" class="form-control longitude" id="longitude" value="<?php echo $datas['x_long']; ?>" >
      </div>
  </div>

  <div class="row placeholders">
      <div class="col-md-12">
        <div class="span9">
        <div class="row-fluid">
          <label class="control-label" for="inputAddress">Masukkan Alamat</label>
          <div class="controls">
            <input type="text" name="alamat" class="inputAddress input-xxlarge form-control" value="<?php echo $datas['alamat'] ?>" autocomplete="off" placeholder=" Cth : Medan, Medan City, North Sumatra, Indonesia">
          </div>
        </div>
        </div>  
      </div>
  </div>
  <div class="row placeholders">
      <div class="col-md-5"></div>
      <div class="col-md-2">
        <div class="wrapper">
            <span class="group-btn">     
                <input type="submit" name="submit" class="btn btn-primary btn-md" value="Submit">
            </span>
        </div>
      </div>
      <div class="col-md-5"></div>
  </div>
</form>
<script>
  $('.inputAddress').addressPickerByGiro({
      distanceWidget: true,
      boundElements: {
          'latitude': '.latitude',
          'longitude': '.longitude'
      }
  });
</script>

<?php
  include "s_footer.php";
?>