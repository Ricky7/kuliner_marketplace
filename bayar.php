<?php

	  require_once "class/db.php";
  	require_once "class/User.php";
  	require_once "class/Produk.php";

  	$user = new User($db);

  	$produk = new Produk($db);

  	//cek jika belum login, maka kembalikan ke hal. login
  	if(!$user->isUserLoggedIn()) {
  		header("Location: login.php");
  	}

  	$curUser = $user->getUser();

  	// format mata uang
   	$jumlah_desimal = "0";
  	$pemisah_desimal = ",";
  	$pemisah_ribuan = ".";

    $id_order = $_REQUEST['slug'];

    if(isset($_POST['submit'])) {

        $imgFile = $_FILES['bukti']['name'];
        $tmp_dir = $_FILES['bukti']['tmp_name'];
        $imgSize = $_FILES['bukti']['size'];


        if(empty($imgFile)) {
            $errMsg = "Please select image File..";
        } else {
            $upload_dir = 'asset/bukti_transfer/'; // upload directory
 
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
        }

        if(!isset($errMsg)) {

            try {
              $produk->insertBayar(array(
                'no_rek' => $_POST['no_rek'],
                'nama_rek' => $_POST['nama_rek'],
                'nama_bank' => $_POST['nama_bank'],
                'nominal' => $_POST['nominal'],
                'detail_transfer' => $_POST['detail'],
                'bukti_transfer' => $userpic,
                'status_order' => 'PENDING'
              ),$curUser['id_user'], $id_order);
              header("Location: order_diproses.php");
            } catch (Exception $e) {
              die($e->getMessage());
            }
        }

        
    }

    

    // if(isset($_POST['submit'])) {

    //     try {
    //       $produk->insertBayar(array(
    //         'no_rek' => $_POST['no_rek'],
    //         'nama_rek' => $_POST['nama_rek'],
    //         'nama_bank' => $_POST['nama_bank'],
    //         'nominal' => $_POST['nominal'],
    //         'detail_transfer' => $_POST['detail'],
    //         'bukti_transfer' => $userpic,
    //         'status_order' => 'PENDING'
    //       ),$curUser['id_user'], $id_order);
    //       header("Location: order_diproses.php");
    //     } catch (Exception $e) {
    //       die($e->getMessage());
    //     }
    // }

?>

<?php

	include "header.php";

?>

<!-- breadcrumbs -->
<div class="breadcrumbs">
  <div class="container">
    <ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
      <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Beranda</a></li>
      <li class="active">Pembayaran</li>
    </ol>
  </div>
</div>
<!-- //breadcrumbs -->

<!-- content tabel 1 -->
<div class="checkout">
  <div class="container">
      <div class="checkout-right">
        <table class="timetable_sub">
          <thead>
            <tr>
              <th>Tanggal Order</th>
              <th>Penjual</th>
              <th>Jarak Antar</th>
              <th>Ongkir</th>
              <th>Status</th>
            </tr>
          </thead>
      <?php
      
          $query = "SELECT * FROM tbl_order INNER JOIN tbl_user ON (tbl_order.id_penjual=tbl_user.id_user) WHERE (tbl_order.id_order = {$id_order})";

          $stmt = $db->prepare($query);
          $stmt->execute();
          $row=$stmt->fetch(PDO::FETCH_ASSOC);
        ?>
          <tr class="rem1">
            <td class="invert-image"><?php echo $row['tgl_order'] ?></td>
            <td class="invert"><?php echo $row['nama'] ?></td>
            <td class="invert"><?php echo $row['jarak'].' KM' ?></td>              
            <td class="invert"><?php echo "Rp ".number_format($row['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
            <td class="invert"><?php echo $row['status_order'] ?></td>
          </tr>
        </table>
    <div class="clearfix"></div>
  </div>
</div>

<!-- content tabel 2 -->
<div class="checkout">
  <div class="container">
      <div class="checkout-right">
        <table class="timetable_sub">
          <thead>
            <tr>
              <th>Nama Bank</th>
              <th>No Rekening</th>
              <th>Nama Rekening</th>
            </tr>
          </thead>
          <tr class="rem1">
            <td class="invert">BNI</td>
            <td class="invert">56357788909456546</td>
            <td class="invert">Erta Sianturi</td>
          </tr>
          <tr class="rem1">
            <td class="invert">BCA</td>
            <td class="invert">56778575345768678</td>
            <td class="invert">Erta Sianturi</td>
          </tr>
          <tr class="rem1">
            <td class="invert">MANDIRI</td>
            <td class="invert">87945245535457683</td>
            <td class="invert">Erta Sianturi</td>
          </tr>
          <tr class="rem1">
            <td class="invert">BRI</td>
            <td class="invert">80123457052347877</td>
            <td class="invert">Erta Sianturi</td>
          </tr>
        </table>
    <div class="clearfix"></div>
  </div>
</div>

<!-- content tabel 3 -->
<div class="checkout">
  <div class="container">
      <div class="checkout-right">
        <table class="timetable_sub">
          <thead>
            <tr>
              <th>Produk</th>
              <th>Jumlah</th>
              <th>Nama Produk</th>
              <th>Harga</th>
            </tr>
          </thead>
    <?php
          $id_order = $_REQUEST['slug'];
          $ongkir = $row['ongkir'];
      
          $query2 = "SELECT tbl_produk.gambar,tbl_produk.nama_produk,tbl_order_detail.kuantitas,tbl_order_detail.harga FROM tbl_order_detail INNER JOIN tbl_produk ON (tbl_order_detail.id_produk=tbl_produk.id_produk) WHERE tbl_order_detail.id_order = {$id_order}";

          $stmt2 = $db->prepare($query2);
          $stmt2->execute();
      
          if($stmt2->rowCount()>0)
          {
              while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
              {
                  ?>
                    <tr class="rem1">
                      <td class="invert-image"><a href="#"><img src="asset/img_produk/<?php echo $row2['gambar']; ?>" alt=" " class="img-responsive" /></a></td>
                      <td class="invert"><?php echo $row2['kuantitas'] ?></td>
                      <td class="invert"><?php echo $row2['nama_produk'] ?></td>              
                      <td class="invert"><?php echo "Rp ".number_format($row2['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
                    </tr>
                <?php
              }
        }
      ?>
      <?php
          $getHarga = $db->prepare("SELECT SUM(harga) as xtotal FROM tbl_order_detail  WHERE id_order=:id");
          $getHarga->execute(array(":id"=>$id_order));
          $xharga = $getHarga->fetch(PDO::FETCH_ASSOC);
      ?>
        <tr>
          <td colspan="3"><b>TOTAL YANG HARUS DIBAYAR</b></td>
          <td><?php echo "Rp. ".number_format($xharga['xtotal']+$row['ongkir'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan); ?></td>
        </tr>
        </table>
      <div class="clearfix"> <br><br></div>
    </div>

    <?php if($row['status_order'] == 'NOT PAID' || $row['status_order'] == 'PENDING') {

      ?>
    <form method="post" enctype="multipart/form-data">
      <div class="row" style="padding-bottom:10px">
        <div class="col-md-4">
          <div class="control-group">
          <label class="control-label">No Rekening</label>
          <div class="controls">
            <input type="text" name="no_rek" class="form-control" required>
          </div>
        </div>
        </div>
        <div class="col-md-4">
          <div class="control-group">
          <label class="control-label">Nama Rekening</label>
          <div class="controls">
            <input type="text" name="nama_rek" class="form-control" required>
          </div>
        </div>
        </div>
        <div class="col-md-4">
          <div class="control-group">
          <label class="control-label">Nama Bank</label>
            <select class="form-control border-input" name="nama_bank" required>
              <option></option>
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
      </div>

      <div class="row" style="padding-bottom:10px">
        <div class="col-md-4">
          <div class="control-group">
          <label class="control-label">Nominal</label>
          <div class="controls">
            <input type="number" name="nominal" class="form-control" required>
          </div>
        </div>
        </div>
        <div class="col-md-4">
          <div class="control-group">
          <label class="control-label">Detail Transfer</label>
          <div class="controls">
            <textarea class="form-control" name="detail" rows="3" Placeholder="Isi disini bila ada catatan tambahan"></textarea>
          </div>
        </div>
        </div>
        <div class="col-md-4">
          <div class="control-group">
          <label class="control-label">Bukti Transfer</label>
          <div class="controls">
            <input type="file" name="bukti" class="form-control" required>
          </div>
        </div>
        </div>
      </div>
      <?php

          }
    ?>
  </div>
  <div class="row" style="padding-bottom:10px">
    <div class="col-md-4 col-md-offset-4">
      <div class="control-group">
        <div class="snipcart-details top_brand_home_details">
          <input type="submit" name="submit" value="Submit Pembayaran" class="button" style="height: 40px; width: 280px"/>
        </div> 
      </div>
    </div>
    </form>
  </div>
</div>



<?php

  include "footer.php";

?>