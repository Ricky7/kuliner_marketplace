<?php

    require_once "../class/db.php";
    require_once "../class/User.php";

    $user = new User($db);

    //Jika sudah login
    if($user->isAdminLoggedIn()){
        header("location: a_dashboard.php");
    }

    if(isset($_POST['kirim'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Proses login user
        if($user->login($username, $password)){
            header("location: a_dashboard.php");
        }else{
            // Jika login gagal, ambil pesan error
            $error = $user->getLastError();
        }
    }

?>
<!--Pulling Awesome Font -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href="../asset/css/admin_login.css" rel="stylesheet">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<title>Admin Login</title>
<div class="container">
    <div class="row">
        <div class="col-md-offset-5 col-md-3">
            <div class="form-login">
            <h4>Admin Login</h4>
            <form method="post">
                <?php if (isset($error)): ?>
                  <div class="error">
                      <?php echo $error ?>
                  </div>
                <?php endif; ?>
                <input type="text" name="username" id="userName" class="form-control input-sm chat-input" placeholder="username" />
                </br>
                <input type="password" name="password" id="userPassword" class="form-control input-sm chat-input" placeholder="password" />
                </br>
                <div class="wrapper">
                    <span class="group-btn">     
                        <input type="submit" name="kirim" class="btn btn-primary btn-md" value="Login">
                    </span>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>