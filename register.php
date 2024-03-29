<?php
	session_start();
	require 'config/config.php';
	require 'config/common.php';
	if ($_POST) {
		if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['password'])){
			if (empty($_POST['name'])) {
				$nameError = "Name can't be null";
			}
			if (empty($_POST['email'])) {
				$emailError = "Email can't be null";
			}
			if (empty($_POST['phone'])) {
				$phoneError = "Phone No. can't be null";
			}
			if (empty($_POST['address'])) {
				$addressError = "Address can't be null";
			}
			if (empty($_POST['password'])) {
				$passwordError = "Password can't be null";
			}
		}elseif( strlen($_POST['password'])< 4 ){
			$passwordError = "Password must be 4 characters at least!";
		}else{
			$name = $_POST['name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$address = $_POST['address'];
			$password = md5($_POST['password']);

			$sql = "SELECT * FROM users where email=:email";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([':email'=>$email]);
			$result = $stmt->fetch();
			if (empty($result['name'])) {
				$sql = "INSERT INTO users(name,email,password,address,phone) VALUES(:name,:email,:password,:address,:phone)";
				$stmt = $pdo->prepare($sql);
				$stmt->execute([
					':name' => $name,
					':email' => $email,
					':password' => $password,
					':address' => $address,
					':phone' => $phone
				]);
				echo "<script>alert('Account is created successfully!');window.location.href='login.php';</script>";
			}else{
				echo "<script>alert('This email already exists!')</script>";
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Shop | Login</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.html"><h4>AP Shopping<h4></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="login_form_inner">
						<h3>Register to use our shopping site</h3>
						<form class="row login_form" action="register.php" method="post" novalidate="novalidate">
							<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" name="name" placeholder="Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'" style="<?php echo empty($nameError) ? '' : 'border: 1px solid red' ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" style="<?php echo empty($emailError) ? '' : 'border: 1px solid red' ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" name="address" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'" style="<?php echo empty($addressError) ? '' : 'border: 1px solid red' ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" name="phone" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'" style="<?php echo empty($phoneError) ? '' : 'border: 1px solid red' ?>">
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" style="<?php echo empty($passwordError) ? '' : 'border: 1px solid red' ?>">
								<p style="color: red; text-align: left;">
									<?= $passwordError ?>
								</p>
							</div>
							<div class="col-md-12 form-group">
								<button class="primary-btn">Register</button>
								<a class="primary-btn" href="login.php" style="color: white;">Log In</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

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