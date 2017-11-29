<?php

	require_once "../class/db.php";
    require_once "../class/User.php";
    require_once "../class/Produk.php";

    $user = new User($db);

    $datas = $user->getUser();

    $user->cekUserLogin();

    $produk = new Produk($db);
    
    if(isset($_REQUEST['id'])) {

    	echo $id = $_REQUEST['id'];

        try {
            $user->deleteBlog($id);
            header("Location: a_daftar_blog.php");
        } catch (Exception $e) {
            die($e->getMessage());
            header("Location: a_daftar_blog.php");
        }
    }
    
?>