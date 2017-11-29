<?php

    class User
        {

            private $db; 
            private $error; 

            // Contructor untuk class User, membutuhkan satu parameter yaitu koneksi ke databse
            function __construct($db_conn)
            {
                $this->db = $db_conn;

                // Mulai session
                 if(!isset($_SESSION)){
                    session_start();
                }
            }

            //Login admin
            public function login($username, $password)
            {
                try
                {
                    // Ambil data dari database
                    $query = $this->db->prepare("SELECT * FROM tbl_admin WHERE username = :username");
                    $query->bindParam(":username", $username);
                    $query->execute();
                    $data = $query->fetch();

                    // Jika jumlah baris > 0
                    if($query->rowCount() > 0){
                        // jika password yang dimasukkan sesuai dengan yg ada di database
                        if(password_verify($password, $data['password'])){
                            $_SESSION['user_session'] = $data['id_admin'];
                            $_SESSION['user_role'] = $data['role'];
                            return true;
                        }else{
                            $this->error = "Username atau Password Salah";
                            return false;
                        }
                    }else{
                        $this->error = "Akun tidak ada";
                        return false;
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return false;
                }
            }

            // Cek apakah Admin sudah login
            public function isAdminLoggedIn(){
                // Apakah user_session sudah ada di session
                if(isset($_SESSION['user_session']))
                {
                    if($_SESSION['user_role'] == 'admin') 
                    {
                        return true;
                    }
                }
            }

            public function cekLogin() {

                if(!self::isAdminLoggedIn()){
                    header("location: a_login.php");
                }
            }

            // Ambil data admin yang sudah login
            public function getAdmin(){
                // Cek apakah sudah login
                if(!$this->isAdminLoggedIn()){
                    return false;
                }

                try {
                    // Ambil data Pengurus dari database
                    $query = $this->db->prepare("SELECT * FROM tbl_admin WHERE id_admin = :id");
                    $query->bindParam(":id", $_SESSION['user_session']);
                    $query->execute();
                    return $query->fetch();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return false;
                }
            }

            // Logout Admin
            public function logout(){
                // Hapus session
                session_destroy();
                // Hapus user_session
                unset($_SESSION['user_session']);
                return true;
            }

            public function ubahPassword($id, $old, $new) {

                // cek old password

                $cek = "SELECT password FROM tbl_admin WHERE id_admin=:id";
                $stmt = $this->db->prepare($cek);
                $stmt->execute(array(":id"=>$id));
                $pass=$stmt->fetch(PDO::FETCH_ASSOC);

                $newPass = password_hash($new, PASSWORD_DEFAULT);

                if($stmt->rowCount()>0) {

                    if(password_verify($old, $pass['password'])) {

                        // update new password
                        $new = "UPDATE tbl_admin SET password='{$newPass}' WHERE id_admin={$id}";

                        $stmtC = $this->db->prepare($new);
                        $stmtC->execute();

                        ?>
                            <div class="alert alert-success">
                                <strong>Success!</strong>
                            </div>
                        <?php
                        
                        return true;
                    } else {

                        ?>
                            <div class="alert alert-danger">
                                <strong>Gagal!</strong> Coba Lagi..
                            </div>
                        <?php
                    }
                }
                
            }

            // Ambil error terakhir yg disimpan di variable error
            public function getLastError(){
                return $this->error;
            }


            //**User

            // Registrasi user baru
            public function register($nama, $no_hp, $username, $password)
            {
                try
                {
                    // buat hash dari password yang dimasukkan
                    $hashPasswd = password_hash($password, PASSWORD_DEFAULT);
                    //$tgl_reg = date('Y-m-d H:i:s');

                    //Masukkan user baru ke database
                    $query = $this->db->prepare("INSERT INTO tbl_user(nama, no_hp, username, password, tgl_registrasi, role) VALUES(:nama, :no_hp, :username, :pass, NOW(), 'user')");
                    $query->bindParam(":nama", $nama);
                    $query->bindParam(":no_hp", $no_hp);
                    $query->bindParam(":username", $username);
                    $query->bindParam(":pass", $hashPasswd);
                    //$query->bindParam(":tgl", $tgl_reg);
                    $query->execute();

                    return true;
                }catch(PDOException $e){
                    // Jika terjadi error
                    if($e->errorInfo[0] == 23000){
                        //errorInfor[0] berisi informasi error tentang query sql yg baru dijalankan
                        //23000 adalah kode error ketika ada data yg sama pada kolom yg di set unique
                        $this->error = "Username sudah digunakan!";
                        return false;
                    }else{
                        echo $e->getMessage();
                        return false;
                    }
                }
            }

            public function loginUser($username, $password)
            {
                try
                {
                    // Ambil data dari database
                    $query = $this->db->prepare("SELECT * FROM tbl_user WHERE username = :username");
                    $query->bindParam(":username", $username);
                    $query->execute();
                    $data = $query->fetch();

                    // Jika jumlah baris > 0
                    if($query->rowCount() > 0){
                        // jika password yang dimasukkan sesuai dengan yg ada di database
                        if(password_verify($password, $data['password'])){
                            $_SESSION['user_session'] = $data['id_user'];
                            $_SESSION['user_role'] = $data['role'];
                            return true;
                        }else{
                            $this->error = "Username atau Password Salah";
                            return false;
                        }
                    }else{
                        $this->error = "Akun tidak ada";
                        return false;
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return false;
                }
            }

            public function updateUser($fields = array(), $id_user) {

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
                $sql = "UPDATE tbl_user SET {$set} WHERE id_user={$id_user}";

                if ($this->db->prepare($sql)) {
                    if ($this->db->exec($sql)) {
                        return true;
                    }
                }

                return false;
            }

            public function ubahPassUser($id, $old, $new) {

                // cek old password

                $cek = "SELECT password FROM tbl_user WHERE id_user=:id";
                $stmt = $this->db->prepare($cek);
                $stmt->execute(array(":id"=>$id));
                $pass=$stmt->fetch(PDO::FETCH_ASSOC);

                $newPass = password_hash($new, PASSWORD_DEFAULT);

                if($stmt->rowCount()>0) {

                    if(password_verify($old, $pass['password'])) {

                        // update new password
                        $new = "UPDATE tbl_user SET password='{$newPass}' WHERE id_user={$id}";

                        $stmtC = $this->db->prepare($new);
                        $stmtC->execute();

                        ?>
                            <div class="alert alert-success">
                                <strong>Password berhasil diganti!!</strong>
                            </div>
                        <?php
                        
                        return true;
                    } else {

                        ?>
                            <div class="alert alert-danger">
                                <strong>Gagal mengubah password!</strong>
                            </div>
                        <?php
                    }
                }
                
            }

            // Cek apakah User sudah login
            public function isUserLoggedIn(){
                // Apakah user_session sudah ada di session
                if(isset($_SESSION['user_session']))
                {
                    if($_SESSION['user_role'] == 'user') 
                    {
                        return true;
                    }
                }
            }

            public function cekUserLogin() {

                if(!self::isUserLoggedIn()){
                    header("location: login.php");
                }
            }

            // Ambil data user yang sudah login
            public function getUser(){
                // Cek apakah sudah login
                if(!$this->isUserLoggedIn()){
                    return false;
                }

                try {
                    // Ambil data Pengurus dari database
                    $query = $this->db->prepare("SELECT * FROM tbl_user WHERE id_user = :id");
                    $query->bindParam(":id", $_SESSION['user_session']);
                    $query->execute();
                    return $query->fetch();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return false;
                }
            }

            public function getBlogID($id)
            {
                $stmt = $this->db->prepare("SELECT * FROM tbl_blog WHERE id_blog=:id");
                $stmt->execute(array(":id"=>$id));
                $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
                return $editRow;
            }

            public function daftarBlog($query) {

                $stmt = $this->db->prepare($query);
                $stmt->execute();
            
                if($stmt->rowCount()>0)
                {
                    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        ?>

                        <tr>
                            <td><?php print($row['id_blog']); ?></td>
                            <td><?php print($row['judul']); ?></td>
                            <td><?php print($row['lokasi']); ?></td>
                            <td><?php print($row['jenis_konten']); ?></td>
                            <td><a href="a_edit_blog.php?id=<?php print($row['id_blog']); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></td>
                            <td>
                                <a href="#" data-href="a_delete_blog.php?id=<?php print($row['id_blog']); ?>" data-toggle="modal" data-target="#confirm-delete"><span class="glyphicon glyphicon-trash"></span></a>
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

            public function deleteBlog($id) {
                $stmt = $this->db->prepare("DELETE FROM tbl_blog WHERE id_blog=:id");
                $stmt->bindparam(":id",$id);
                $stmt->execute();
                return true;
            }

            public function buatBlog($blog = array()) {

                $keys = array_keys($blog);

                $values = "'" . implode( "','", $blog ) . "'";

                $sql = "INSERT INTO tbl_blog (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

                if ($this->db->prepare($sql)) {
                    if ($this->db->exec($sql)) {
                        return true;
                    }
                }

                return false;

            }

            public function editBlog($fields = array(), $id) {

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

                $sql = "UPDATE tbl_blog SET {$set} WHERE id_blog = {$id}";

                if ($this->db->prepare($sql)) {
                    if ($this->db->exec($sql)) {
                        return true;
                    }
                }

                return false;

            }


        //end
        }


?>