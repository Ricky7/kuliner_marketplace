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
    
    if(isset($_REQUEST['id'])) {

    	echo $id = $_REQUEST['id'];

        try {
            $produk->deleteCart($id);
            header("Location: cart.php");
        } catch (Exception $e) {
            die($e->getMessage());
            header("Location: cart.php");
        }
    }
    
?>