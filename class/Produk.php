<?php


class Produk {

	private $db; 
    private $error;

    // Contructor untuk class User, membutuhkan satu parameter yaitu koneksi ke databse
    function __construct($db_conn)
    {
        $this->db = $db_conn;


    }

    public function getProdukID($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_produk WHERE id_produk=:id");
        $stmt->execute(array(":id"=>$id));
        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
        return $editRow;
    }

    public function indexProduk($query) {

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        // format mata uang
        $jumlah_desimal = "0";
        $pemisah_desimal = ",";
        $pemisah_ribuan = ".";

        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                <div class="col-md-4 top_brand_left" style="padding-bottom:15px; padding-top:15px;">
                    <div class="hover14 column">
                        <div class="agile_top_brand_left_grid">
                            <div class="agile_top_brand_left_grid1">
                                <figure>
                                    <div class="snipcart-item block">
                                        <div class="snipcart-thumb">
                                            <a href="single.php?id=<?php print($row['id_produk']); ?>">
                                                <img src="asset/img_produk/<?php print($row['gambar']); ?>" alt=" " class="img-responsive" width="150px" height="150px"/>
                                            </a>
                                            <p><?php print($row['nama_produk']); ?></p>
                                            <h4><?php print("Rp. ".number_format($row['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></h4>
                                        </div>
                                        <div class="snipcart-details top_brand_home_details">
                                            <a href="single.php?id=<?php print($row['id_produk']); ?>">
                                                <?php 
                                                    require_once "User.php";
                                                    $user = new User($this->db);
                                                    if($user->isUserLoggedIn()){

                                                        ?>
                                                            <input type="submit" value="Add to cart" class="button" />
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </a>
                                        </div>
                                    </div>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            }
        }
        else
        {
            ?>
            <tr>
            <td>Stok Habis!!</td>
            </tr>
            <?php
        }
    }

    public function addtoCart($datas = array()) {

        $keys = array_keys($datas);

        $values = "'" . implode( "','", $datas ) . "'";

        $id_pembeli = $datas['id_pembeli'];
        $id_produk = $datas['id_produk'];

        // Cek Jika Produk tersebut sudah ada di table cart dengan id sesi yg sama
        $stmt = $this->db->prepare("SELECT * FROM tbl_cart WHERE id_pembeli=:id_pembeli AND id_produk=:id_produk");
        $stmt->execute(array(":id_pembeli"=>$id_pembeli, ":id_produk"=>$id_produk));
        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
        
        // jika ada
        if($stmt->rowCount()>0) {

            $harga = $editRow['harga'] + $datas['harga'];
            $kuantitas = $editRow['kuantitas'] + $datas['kuantitas'];

            $sql = "UPDATE tbl_cart SET harga={$harga}, kuantitas={$kuantitas}  WHERE id_produk = {$id_produk} AND id_pembeli = {$id_pembeli}";


            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }
            
            return false;


        } else {

            $sql = "INSERT INTO tbl_cart (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

            if ($this->db->prepare($sql)) {
                if ($this->db->exec($sql)) {
                    return true;
                }
            }

            return false;
        }

        return true;

    }

    public function menuKategori($query) {

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        

        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                    <li><a href="index.php?kategori=<?php print($row['nama_kategori']); ?>"><?php print($row['nama_kategori']); ?></a></li>

                <?php
            }
        }
        else
        {
            ?>
                <li><a href="#">Error!!</a></li>
            <?php
        }
    }

    public function getKategori() {

    	try {
            // Ambil data kategori dari database
            $query = $this->db->prepare("SELECT * FROM tbl_kategori");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getID($id)
    {

        $stmt = $this->db->prepare("SELECT * FROM tbl_produk INNER JOIN tbl_user ON (tbl_produk.id_seller=tbl_user.id_user) WHERE tbl_produk.id_produk=:id");
        $stmt->execute(array(":id"=>$id));
        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
        return $editRow;
    }

    public function insertProduk($fields = array()) {
		$keys = array_keys($fields);

		$values = "'" . implode( "','", $fields ) . "'";

		$sql = "INSERT INTO tbl_produk (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

		if ($this->db->prepare($sql)) {
	        if ($this->db->exec($sql)) {
	            return true;
	        }
	    }

		return false;
	}

    public function listMYproduk($query) {

    	// format mata uang
	 	$jumlah_desimal = "0";
		$pemisah_desimal = ",";
		$pemisah_ribuan = ".";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                <tr>
                    <td><img src="../asset/img_produk/<?php print($row['gambar']); ?>" width="75px" height="75px"></td>
                    <td><?php print($row['nama_produk']); ?></td>
                    <td><?php print(number_format($row['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                    <td>
                        <?php
                            if($row['status'] == 0) {
                                print('Tidak Tersedia');
                            } else if($row['status'] == 1) {
                                print('Tersedia');
                            } else {
                                print('Tidak ada Produk');
                            }
                        ?>
                    </td>
                    <td><a href="s_edit_produk.php?id=<?php print($row['id_produk']); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></td>
                    <td>
                    <a href="#" data-href="<?php print($row['id_produk']); ?>" data-toggle="modal" data-target="#confirm-status"><span class="glyphicon glyphicon-exclamation-sign"></span></a>
                    </td>
                    <td>
                    <a href="#" data-href="s_del_produk.php?id=<?php print($row['id_produk']); ?>" data-toggle="modal" data-target="#confirm-delete"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
                <?php
            }
        }
        else
        {
            ?>
            <tr>
            <td>Nothing here...</td>
            </tr>
            <?php
        }
    }

    public function editProduk($fields = array(), $id) {

        //$set = "ekor = 'bulu',";
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = '{$value}'";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        //var_dump($set);

        $sql = "UPDATE tbl_produk SET {$set} WHERE id_produk = {$id}";

        if ($this->db->prepare($sql)) {
            if ($this->db->exec($sql)) {
                return true;
            }
        }

        return false;

    }

    // public function ubahStatus($status, $id)
    // {
    //     $sql = "UPDATE tbl_produk SET status={$status} WHERE id_produk={$id}";

    //     if ($this->db->prepare($sql)) {
    //         if ($this->db->exec($sql)) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    public function paging($query,$records_per_page)
    {
        $starting_position=0;
        if(isset($_GET["page_no"]))
        {
            $starting_position=($_GET["page_no"]-1)*$records_per_page;
        }
        $query2=$query." limit $starting_position,$records_per_page";
        return $query2;
    }

    public function paginglink($query,$records_per_page)
    {
        
        $self = $_SERVER['PHP_SELF'];
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $total_no_of_records = $stmt->rowCount();
        
        if($total_no_of_records > 0)
        {
            ?><ul class="pagination"><?php
            $total_no_of_pages=ceil($total_no_of_records/$records_per_page);
            $current_page=1;
            if(isset($_GET["page_no"]))
            {
                $current_page=$_GET["page_no"];
            }
            if($current_page!=1)
            {
                $previous =$current_page-1;
                echo "<li><a href='".$self."?page_no=1'>First</a></li>";
                echo "<li><a href='".$self."?page_no=".$previous."'>Previous</a></li>";
            }
            for($i=1;$i<=$total_no_of_pages;$i++)
            {
                if($i==$current_page)
                {
                    echo "<li><a href='".$self."?page_no=".$i."' style='color:red;'>".$i."</a></li>";
                }
                else
                {
                    echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
                }
            }
            if($current_page!=$total_no_of_pages)
            {
                $next=$current_page+1;
                echo "<li><a href='".$self."?page_no=".$next."'>Next</a></li>";
                echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
            }
            ?></ul><?php
        }
    }

    public function deleteProduk($id) {
		$stmt = $this->db->prepare("DELETE FROM tbl_produk WHERE id_produk=:id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		return true;
	}

    public function deleteCart($id) {
        $stmt = $this->db->prepare("DELETE FROM tbl_cart WHERE id_cart=:id");
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return true;
    }

    //Order

    public function insertOrder($fields = array(), $id_pembeli, $id_penjual) {

        $keys = array_keys($fields);

        $values = "'" . implode( "','", $fields ) . "'";

        $sql = "INSERT INTO tbl_order (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

        if ($this->db->prepare($sql)) {

            if ($this->db->exec($sql)) {

                $lastId = $this->db->lastInsertId();

                $move_data = "INSERT INTO tbl_order_detail (id_order, id_produk, harga, kuantitas)
SELECT {$lastId}, id_produk, harga, kuantitas FROM tbl_cart WHERE id_pembeli={$id_pembeli} AND id_penjual={$id_penjual}";

                if($this->db->exec($move_data)) {
                    $delCart = $this->db->prepare("DELETE FROM tbl_cart WHERE id_pembeli=:id_pembeli AND id_penjual=:id_penjual");
                    $delCart->bindparam(":id_pembeli",$id_pembeli);
                    $delCart->bindparam(":id_penjual",$id_penjual);
                    $delCart->execute();
                    return true;
                }
                
                return true;
            }
        }

        return false;
    }

    public function insertBayar($fields = array(), $id_user, $id_order) {

        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = '{$value}'";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        //var_dump($set);
        $sql = "UPDATE tbl_order SET {$set} WHERE id_pembeli={$id_user} AND id_order={$id_order}";

        if ($this->db->prepare($sql)) {
            if ($this->db->exec($sql)) {
                return true;
            }
        }

        return false;
    }


    public function daftarOrder($query) {

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                <tr>
                    <td><?php print($row['id_order']); ?></td>
                    <td><?php print($row['tgl_order']); ?></td>
                    <td><?php print($row['status_order']); ?></td>
                    <td>
                        <?php
                            switch ($row['status_order']) {
                              case 'PENDING':
                                ?>
                                    <a href="a_konfirmasi_pending.php?slug=<?php print($row['id_order']); ?>">Lihat</a>
                                <?php
                                break;

                              case 'PAID':
                                ?>
                                    <a href="a_konfirmasi_pending.php?slug=<?php print($row['id_order']); ?>">Lihat</a>
                                <?php
                                break;

                              case 'SENDING':
                                ?>
                                    <a href="a_konfirmasi_sending.php?slug=<?php print($row['id_order']); ?>">Lihat</a>
                                <?php
                                break;

                              case 'SENT':
                                ?>
                                    <a href="a_konfirmasi_sending.php?slug=<?php print($row['id_order']); ?>">Lihat</a>
                                    <a href="a_konfirmasi_sent.php?slug=<?php print($row['id_order']); ?>" style="padding-left:30px;">Bayar</a>
                                <?php
                                break;

                              case 'PAYING':
                                ?>
                                    <a href="a_konfirmasi_sent.php?slug=<?php print($row['id_order']); ?>">Lihat</a>
                                <?php
                                break;

                              case 'FINISH':
                                ?>
                                    <a href="a_info_selesai.php?slug=<?php print($row['id_order']); ?>">DONE</a>
                                <?php
                                break;
                            }
                        
                        ?>
                    
                    </td>
                </tr>
                <?php
            }
        }
        else
        {
            ?>
            <tr>
            <td>Saat ini tidak tersedia...</td>
            </tr>
            <?php
        }
    }

    public function goToPaid($status, $id_order) {

        //var_dump($set);
        $sql = "UPDATE tbl_order SET status_order='{$status}' WHERE id_order={$id_order}";

        if ($this->db->prepare($sql)) {
            if ($this->db->exec($sql)) {
                return true;
            }
        }

        return false;
    }

    //seller

    public function orderPerSeller($query) {

        // format mata uang
        $jumlah_desimal = "0";
        $pemisah_desimal = ",";
        $pemisah_ribuan = ".";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                <tr>
                    <td><?php print($row['id_order']); ?></td>
                    <td><?php print($row['tgl_order']); ?></td>
                    <td><?php print($row['status_order']); ?></td>
                    <td>
                        <?php

                            switch ($row['status_order']) {
                              case 'PAID':
                                ?>
                                    <a href="s_kirim_pesanan.php?slug=<?php print($row['id_order']); ?>">Lihat</a>
                                <?php
                                break;

                              case 'PAYING':
                                ?>
                                    <a href="s_konfirmasi_pembayaran.php?slug=<?php print($row['id_order']); ?>">Lihat</a>
                                <?php
                                break;

                            }

                        ?>
                    
                    </td>
                </tr>
                <?php
            }
        }
        else
        {
            ?>
            <tr>
            <td>Belum tersedia...</td>
            </tr>
            <?php
        }
    }

    public function orderPerProses($query) {

        // format mata uang
        $jumlah_desimal = "0";
        $pemisah_desimal = ",";
        $pemisah_ribuan = ".";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                <tr>
                    <td><?php print($row['id_order']); ?></td>
                    <td><?php print($row['tgl_kirim']); ?></td>
                    <td><?php print($row['nama_driver']); ?></td>
                    <td><?php print($row['status_order']); ?></td>
                </tr>
                <?php
            }
        }
        else
        {
            ?>
            <tr>
            <td>Belum tersedia...</td>
            </tr>
            <?php
        }
    }

    public function infoProduk($query) {

        // format mata uang
        $jumlah_desimal = "0";
        $pemisah_desimal = ",";
        $pemisah_ribuan = ".";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                <tr>
                    <td>
                        <a href="#"><img src="../asset/img_produk/<?php echo $row['gambar']; ?>" width="70px" height="70px" class="img-responsive" /></a>
                    </td>
                    <td><?php print($row['nama_produk']); ?></td>
                    <td><?php print('Rp. '.number_format($row['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                    <td><?php print($row['kuantitas']); ?></td>
                </tr>
                <?php
            }
        }
        else
        {
            ?>
            <tr>
            <td>Belum tersedia...</td>
            </tr>
            <?php
        }
    }

    public function kirimProduk($fields = array(), $id_order) {

        $keys = array_keys($fields);

        $values = "'" . implode( "','", $fields ) . "'";

        //var_dump($fields);

        $sql = "INSERT INTO tbl_kirim (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

        if ($this->db->prepare($sql)) {
            if ($this->db->exec($sql)) {

                $lastId = $this->db->lastInsertId();
                $notes = $note;

                $updatekirim = "UPDATE tbl_order SET id_kirim={$lastId}, status_order='SENDING' WHERE id_order={$id_order}";

                if ($this->db->prepare($updatekirim)) {
                    if ($this->db->exec($updatekirim)) {
                        return true;
                    }
                }
                return true;
            }
        }

        return false;
    }

    public function searchProduk($query) {

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        // format mata uang
        $jumlah_desimal = "0";
        $pemisah_desimal = ",";
        $pemisah_ribuan = ".";

        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>

                <div class="col-md-4 top_brand_left" style="padding-bottom:15px; padding-top:15px;">
                    <div class="hover14 column">
                        <div class="agile_top_brand_left_grid">
                            <div class="agile_top_brand_left_grid1">
                                <figure>
                                    <div class="snipcart-item block">
                                        <div class="snipcart-thumb">
                                            <img src="asset/img_produk/<?php print($row['gambar']); ?>" alt=" " class="img-responsive" width="150px" height="150px"/>
                                            <p><?php print($row['nama_produk']); ?></p>
                                            <h4><?php print("Rp. ".number_format($row['harga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></h4>
                                        </div>
                                        <div class="snipcart-details top_brand_home_details">
                                            <a href="single.php?id=<?php print($row['id_produk']); ?>">
                                                <input type="submit" value="Add to cart" class="button" />
                                            </a>
                                        </div>
                                    </div>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            }
        }
        else
        {
            ?>
            <tr>
            <td>Stok Habis!!</td>
            </tr>
            <?php
        }
    }

    public function laporanPenjualan($query) {

        // format mata uang
        $jumlah_desimal = "0";
        $pemisah_desimal = ",";
        $pemisah_ribuan = ".";

        $total_harga;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                $total_harga += $row['xharga'];
                ?>

                <tr>
                    <td><?php print($row['nama_produk']); ?></td>
                    <td><?php print($row['xkuantitas']); ?></td>
                    <td><?php echo "Rp. ".number_format($row['xharga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan) ?></td>
                </tr>
                <?php
            }
            ?>
                <tr>
                    <td colspan='2'>Total Penjualan</td>
                    <td><font color="red"><?php echo "Rp. ".number_format($total_harga,$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan) ?></font></td>
                </tr>
            <?php
        }
        else
        {
            ?>
            <tr>
            <td>Belum ada Penjualan...</td>
            </tr>
            <?php
        }
    }

}

?>