<?php
  session_start();
	require 'config/config.php';
  require 'config/common.php';
	if($_POST){
		$email = $_POST['email'];
		$password = md5($_POST['password']);
    try{
      $sql = "SELECT * FROM users where email='$email'";
      $pdostatement = $pdo->prepare($sql);
      $pdostatement->execute();
      $result = $pdostatement->fetch();
      if ($result['email']){
        if ($result['password'] == $password) {
          $_SESSION['user_id'] = $result['id'];
          $_SESSION['user_name'] = $result['name'];
          $_SESSION['user_role'] = $result['role'];
          $_SESSION['logged_in'] = time();
          header('location: index.php');
        }else{
          echo "<script>alert('Email or password is incorrect!')</script>";
        }
      }else{
        echo "<script>alert('Email or password is incorrect!')</script>";
      }
    }catch(Exception $e){
      echo $e->getMessage();
    }
	}
 ?>

 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog App | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Blog</b>Admin</a>
  </div>
  <!-- /.login-logo -->
  <?php if($_SESSION['user_level']): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Warning!</strong> You don't have access to see admin page!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php session_destroy(); endif;?>
  <!-- Alert Box -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="login.php" method="post">
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
