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
		
<!-- //navigation -->
	<!-- main-slider -->
		<ul id="demo1">
			<li>
				<img src="asset/index/images/11.jpg" alt="" width="1080px"/>
				<!--Slider Description example-->
				<div class="slide-desc">
					<h3>Buy Rice Products Are Now On Line With Us</h3>
				</div>
			</li>
			<li>
				<img src="asset/index/images/22.jpg" alt="" width="1080px"/>
				  <div class="slide-desc">
					<h3>Whole Spices Products Are Now On Line With Us</h3>
				</div>
			</li>
			
			<li>
				<img src="asset/index/images/44.jpg" alt="" width="1080px" />
				<div class="slide-desc">
					<h3>Whole Spices Products Are Now On Line With Us</h3>
				</div>
			</li>
		</ul>
	<!-- //main-slider -->
	<!-- //top-header and slider -->
	<!-- top-brands -->
	<div class="top-brands">
		<div class="container">
		<h2>
			Produk
			<?php
				if(isset($_GET['kategori']) && !empty($_GET['kategori'])) {
					$kategori = $_GET['kategori'];
					echo '"'.$kategori.'"';
				}
			?>
		</h2>

			<div class="row">                         
             <table class="table table-striped">
                <thead></thead>
                <tbody>
                    <?php

                        if(isset($_GET['kategori']) && !empty($_GET['kategori'])) {

                            $kategori = $_GET['kategori'];

                            $query = "SELECT * FROM tbl_produk LEFT JOIN tbl_kategori ON (tbl_produk.id_kategori=tbl_kategori.id_kategori) WHERE tbl_kategori.nama_kategori='$kategori' AND tbl_produk.status=1";

                            $records_per_page=9;
                            $newquery = $produk->paging($query,$records_per_page);
                            $produk->indexProduk($newquery);

                        } else {
                            $query = "SELECT * FROM tbl_produk WHERE status=1";       
                            $records_per_page=9;
                            $newquery = $produk->paging($query,$records_per_page);
                            $produk->indexProduk($newquery);
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
 
<!--banner-bottom-->
	<div class="ban-bottom-w3l">
		<div class="container">
		<div class="col-md-6 ban-bottom3">
				<div class="ban-top">
					<img src="asset/index/images/p2.jpg" class="img-responsive" alt=""/>
					
				</div>
				<div class="ban-img">
					<div class=" ban-bottom1">
						<div class="ban-top">
							<img src="asset/index/images/p3.jpg" class="img-responsive" alt=""/>
							
						</div>
					</div>
					<div class="ban-bottom2">
						<div class="ban-top">
							<img src="asset/index/images/p4.jpg" class="img-responsive" alt=""/>
							
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="col-md-6 ban-bottom">
				<div class="ban-top">
					<img src="asset/index/images/111.jpg" class="img-responsive" alt=""/>
					
					
				</div>
			</div>
			
			<div class="clearfix"></div>
		</div>
	</div>
<!--banner-bottom-->

<?php

	include "footer.php";

?>