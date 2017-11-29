<?php  
    // Lampirkan db dan User
    require_once "../class/db.php";
    require_once "../class/User.php";

    // Buat object admin
    $user = new User($db);

    // Logout! hapus session user
    $user->logout();

    // Redirect ke login
    header('location: a_login.php');
 ?>