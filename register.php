<?php

	require_once "class/db.php";
  	require_once "class/User.php";
  	require_once "class/Produk.php";

  	$user = new User($db);

  	$produk = new Produk($db);

  	//cek jika sudah login, maka kembalikan ke hal. index
  	if($user->isUserLoggedIn()) {
  		header("Location: index.php");
  	}

?>
<?php

	include "header.php";

?>
<!-- breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
				<li class="active">Register Page</li>
			</ol>
		</div>
	</div>
<!-- //breadcrumbs -->
<?php

  	if(isset($_POST['submit'])){
  		$nama = $_POST['nama'];
        $no_hp = $_POST['no_hp'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Proses login user
        if($user->register($nama, $no_hp, $username, $password)){
        	$success = true;
            //header("location: index.php");
        }else{
            // Jika login gagal, ambil pesan error
            $error = $user->getLastError();
        }
    }

?>
<!-- register -->
	<div class="register">
		<div class="container">
			<h2>Register Here</h2>
			<?php if (isset($error)): ?>
              <div class="error">
                  <?php echo $error ?>
              </div>
	          <?php endif; ?>
	          <?php if (isset($success)): ?>
	              <div class="success">
	                  <center>Berhasil mendaftar. Silakan <a href="login.php">masuk</a></center>
	              </div>
	          <?php endif; ?>
			<div class="login-form-grids">
				<h5>Profile information</h5>
				<form action="#" method="post">
					<input type="text" name="nama" placeholder="Nama" required>
					<input type="text" name="no_hp" placeholder="No Kontak" required>
				<h6>Login information</h6>
					<input type="text" name="username" placeholder="username" required>
					<input type="password" name="password" placeholder="Password" required>
					
					<input type="submit" name="submit" value="Register">
				</form>
			</div>
			<div class="register-home">
				<a href="index.php">Home</a>
			</div>
		</div>
	</div>
<!-- //register -->
<?php

	include "footer.php";

?>