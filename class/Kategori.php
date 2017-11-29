<?php

    class Kategori
        {

            private $db; 
            private $error; 

            // Contructor untuk class Kategori, membutuhkan satu parameter yaitu koneksi ke databse
            function __construct($db_conn)
            {
                $this->db = $db_conn;

            }

            public function getKategoriID($id)
            {
                $stmt = $this->db->prepare("SELECT * FROM tbl_kategori WHERE id_kategori=:id");
                $stmt->execute(array(":id"=>$id));
                $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
                return $editRow;
            }

            public function insertKategori($kategories = array()) {

                $keys = array_keys($kategories);

                $values = "'" . implode( "','", $kategories ) . "'";

                $sql = "INSERT INTO tbl_kategori (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

                if ($this->db->prepare($sql)) {
                    if ($this->db->exec($sql)) {
                        return true;
                    }
                }

                return false;

            }

            public function listKategori($query) {

                $stmt = $this->db->prepare($query);
                $stmt->execute();
            
                if($stmt->rowCount()>0)
                {
                    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        ?>

                        <tr>
                            <td><?php print($row['nama_kategori']); ?></td>
                            <td><?php print($row['desk_kategori']); ?></td>
                            <td><a href="a_edit_kategori.php?id=<?php print($row['id_kategori']); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></td>
                            <td>
                            <a href="#" data-href="a_delete_kategori.php?id=<?php print($row['id_kategori']); ?>" data-toggle="modal" data-target="#confirm-delete"><span class="glyphicon glyphicon-trash"></span></a>
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

            public function editKategori($fields = array(), $id) {

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

                $sql = "UPDATE tbl_kategori SET {$set} WHERE id_kategori = {$id}";

                if ($this->db->prepare($sql)) {
                    if ($this->db->exec($sql)) {
                        return true;
                    }
                }

                return false;

            }

            public function deleteKategori($id) {
                $stmt = $this->db->prepare("DELETE FROM tbl_kategori WHERE id_kategori=:id");
                $stmt->bindparam(":id",$id);
                $stmt->execute();
                return true;
            }

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
        }

?>