<?php

	require_once "../class/db.php";
    require_once "../class/User.php";
    require_once "../class/Kategori.php";

    $user = new User($db);

    $datas = $user->getUser();

    $user->cekUserLogin();

    $kategori = new Kategori($db);
    
    if(isset($_REQUEST['id'])) {

    	$id = $_REQUEST['id'];

        try {
            $kategori->deleteKategori($id);
            header("Location: a_kategori.php");
        } catch (Exception $e) {
            die($e->getMessage());
            header("Location: a_kategori.php");
        }
    }
    
?>