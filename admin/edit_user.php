<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';
  $role = $_SESSION['user_role'];
  //check session
  if(empty($_SESSION['user_id']) and empty($_SESSION['logged_in'])){
    header('location: /admin/login.php');
  }elseif ($role != 1) {
    $_SESSION['user_level'] = 'user';
    header('location: /admin/login.php');
  }
?>

    <?php include "header.html"; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
           <!-- php-code -->
            <?php
              $uid = $_GET['id'];
              if ($_POST){
                $name = $_POST['name'];
                $email = $_POST['email'];
                $role = $_POST['role'] == 'admin' ? 1 : 0 ;
                if (empty($name) || empty($email)) {
                  if(empty($name)){
                    $nameError = "Name can't be null!";
                  }
                  if(empty($email)){
                    $emailError = "Email can't be null!";
                  }
                }else{
                  $updateSql = "UPDATE users SET name=:name,email=:email,role=:role WHERE id=$uid";
                  $pdostatement = $pdo->prepare($updateSql);
                  $pdostatement->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':role' => $role
                  ]);
                  echo "<script>window.location.href='user_list.php'</script>";
                }
              }
              $getSql = "SELECT * FROM users WHERE id=$uid";
              $pdostatement = $pdo->prepare($getSql);
              $pdostatement->execute();
              $user = $pdostatement->fetch();
            ?>
          <!-- php-code -->
            <form action="edit_user.php?id=<?= $uid ?>" method="post">
            <!-- card start -->
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-6">
                      <h3 class="card-title">Edit User Info</h3>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="form-group">
                    <label>Name</label>
                    <span class="text-danger"><?php if($nameError) echo "*".$nameError; ?></span>
                    <input type="text" name="name" class="form-control" value="<?= escape($user['name']) ?>" >
                  </div>
                  <div class="from-group">
                    <label>Email</label>
                    <span class="text-danger"><?php if($emailError) echo "*".$emailError; ?></span>
                    <input type="text" name="email" class="form-control" value="<?= escape($user['email']) ?>" >
                  </div>
                  <div class="form-group mt-3">
                    <label>Role</label>
                    <select class="form-control" name="role">
                      <option <?php if($user['role']==1) {echo "selected";} ?>>admin</option>
                      <option <?php if($user['role']==0) {echo "selected";} ?>>user</option>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button class="btn btn-primary">
                    Update
                  </button>
                </div>
              </div>
              <!-- /.card -->
            </form>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <?php include 'footer.html'; ?>
