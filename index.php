<?php
	session_start();
	require "config/config.php";
	require "config/common.php";
	if(empty($_SESSION['user_id']) and empty($_SESSION['logged_in'])){
    	header('location: /login.php');
  	}

  	if ($_GET) {
    	//pass
	}elseif ($_POST['search']) {
		$_SESSION['search'] = $_POST['search'];
	}else{
		unset($_SESSION['search']);
	}

  	if ($_GET['pageno']) {
  		$pageno = $_GET['pageno'];
  	}else{
  		$pageno = 1;
  	}
  	$numOfrecs = 3;
  	$offset = ($pageno-1) * 3;

  	if ($_SESSION['search']) {
  		$search = $_SESSION['search'];
  		//raw result
  		$rawsql = "SELECT * FROM products WHERE name LIKE '%$search%'";
  		$rawstmt = $pdo->prepare($rawsql);
  		$rawstmt->execute();
  		$rawresults = $rawstmt->fetchAll();
  		$total_pages = ceil(count($rawresults)/$numOfrecs);

  		$sql = "SELECT * FROM products WHERE name LIKE '%$search%' LIMIT $offset,$numOfrecs";
  		$pdostatement = $pdo->prepare($sql);
  		$pdostatement->execute();
  		$products = $pdostatement->fetchAll();

  	}elseif($_GET['category']){
  		//raw result
  		$rawsql = "SELECT * FROM products WHERE category_id=:id";
  		$rawstmt = $pdo->prepare($rawsql);
  		$rawstmt->execute([':id'=>$_GET['category']]);
  		$rawresults = $rawstmt->fetchAll();
  		$total_pages = ceil(count($rawresults)/$numOfrecs);

  		$sql = "SELECT * FROM products WHERE category_id=:id LIMIT $offset,$numOfrecs";
  		$pdostatement = $pdo->prepare($sql);
  		$pdostatement->execute([':id'=>$_GET['category']]);
  		$products = $pdostatement->fetchAll();
  		// print_r($products);

  	}else{
  		//raw result
  		$rawsql = "SELECT * FROM products";
  		$rawstmt = $pdo->prepare($rawsql);
  		$rawstmt->execute();
  		$rawresults = $rawstmt->fetchAll();
  		$total_pages = ceil(count($rawresults)/$numOfrecs);

  		$sql = "SELECT * FROM products LIMIT $offset,$numOfrecs";
  		$pdostatement = $pdo->prepare($sql);
  		$pdostatement->execute();
  		$products = $pdostatement->fetchAll();
  	}
?>

<?php include('header.php') ?>
				<!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="pagination">
						<a href="?pageno=1<?php echo empty($_GET['category'])?"":"&category=".$_GET['category'] ?>">First</a>
						<a href="<?php echo $pageno<=1 ? "#" : "?pageno=".$pageno-1; echo empty($_GET['category'])?"":"&category=".$_GET['category'] ?>">
							<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
						</a>
						<a href="#"><?php echo $pageno; ?></a>
						<a href="<?php echo $pageno>=$total_pages ? "#" : "?pageno=".$pageno+1; echo empty($_GET['category'])?"":"&category=".$_GET['category'] ?>">
							<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
						</a>
						<a href="?pageno=<?php echo $total_pages; echo empty($_GET['category'])?"":"&category=".$_GET['category'] ?>">Last</a>
					</div>
				</div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<!-- single product -->
						<?php foreach($products as $product): ?>
							<div class="col-lg-4 col-md-6">
								<div class="single-product">
									<img class="img-fluid" src="<?= "admin/".$product['image'] ?>" style="height: 250px;">
									<div class="product-details">
										<h6><?= $product['name'] ?></h6>
										<div class="price">
											<h6><?= $product['price'] ?></h6>
										</div>
										<div class="prd-bottom">
											<a href="" class="social-info">
												<span class="ti-bag"></span>
												<p class="hover-text">add to bag</p>
											</a>
											<a href="product_detail.php?id=<?= $product['id'] ?>" class="social-info">
												<span class="lnr lnr-move"></span>
												<p class="hover-text">view more</p>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<!-- single product -->
					</div>
				</section>
				<!-- End Best Seller -->
			</div>
		</div>
	</div>



	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->

	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
