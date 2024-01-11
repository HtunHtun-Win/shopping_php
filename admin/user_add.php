<?php
  session_start();
  require 'config/config.php';
  if($_POST){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($name) || empty($email) || empty($password) || strlen($password) < 4) {
      if (empty($name)) {
        $nameError = "Name can't be null!";
      }
      if (empty($email)) {
        $emailError = "Email can't be null!";
      }
      if (empty($password)) {
        $passwordError = "Password can't be null!";
      }elseif (strlen($password) < 4) {
        $passwordError = "Password must be 4 characters at least!";
      }
    }else{
      try{
      //check duplicate
        $password = md5($password);
        $sql = "SELECT * FROM users where email='$email'";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute();
        $result = $pdostatement->fetch();
        //
        if ($result['email']) {
          $_SESSION['error'] = "error";
        }else{
          $sql = "INSERT INTO users(name,password,email,role) VALUES(:name,:password,:email,0)";
          $pdostatement = $pdo->prepare($sql);
          $pdostatement->execute([
            ':name' => $name,
            ':password' => $password,
            ':email' => $email,
          ]);
          echo "<script>alert('Account creaded successfully.');window.location.href='user_list.php'</script>";
        }
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
  }
 ?>

 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New User</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Shop</b>Admin</a>
  </div>
  <?php if($_SESSION['error']): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Warning!</strong> Your Email has been used!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; unset($_SESSION['error'])?>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Create New Account</p>

      <form action="" method="post">
        <span class="text-danger"><?php if($nameError) echo $nameError; ?></span>
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <span class="text-danger"><?php if($emailError) echo $emailError; ?></span>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <span class="text-danger"><?php if($passwordError) echo $passwordError; ?></span>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="container">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a type="submit" class="btn btn-success btn-block" href="user_list.php">Back</a>
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
