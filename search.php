<?php

	require_once "class/db.php";
  	require_once "class/User.php";
  	require_once "class/Produk.php";

  	$user = new User($db);

  	$produk = new Produk($db);

  	$curUser = $user->getUser();

?>
<?php

	include "header.php";

?>
	<!-- top-brands -->
	<div class="top-brands">
		<div class="container">
		<h2>
			Hasil Pencarian
		</h2>

			<div class="row">                         
             <table class="table table-striped">
                <thead></thead>
                <tbody>
                    <?php

                    	if(isset($_GET['search'])){

                    		$param = $_GET['search'];
                    		$query = "SELECT * FROM tbl_produk WHERE nama_produk LIKE '%{$param}%' AND status=1";
                    		$records_per_page=9;
                            $newquery = $produk->paging($query,$records_per_page);
                            $produk->searchProduk($newquery);
                    	}    
                        
                     ?>
                     <tr>
                        <td colspan="7" align="center">
                            <div class="pagination-wrap">
                            <?php $produk->paginglink($query,$records_per_page); ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
         </div> 

		</div>
	</div>
<!-- //top-brands -->

<?php

	include "footer.php";

?>