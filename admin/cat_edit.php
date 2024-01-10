<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';
  //check session
  if(empty($_SESSION['user_id']) and empty($_SESSION['logged_in'])){
    header('location: /admin/login.php');
  }

  if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['description'])) {
      if (empty($_POST['name'])) {
        $nameError = "Category name is required";
      }
      if (empty($_POST['description'])) {
        $descError = "Category description is required";
      }
    }else{
      $id = $_POST['id'];
      $name = $_POST['name'];
      $description = $_POST['description'];
      $sql = "UPDATE categories set name=:name,description=:description WHERE id=:id";
      $pdostatement = $pdo->prepare($sql);
      $pdostatement->execute([
        ':name' => $name,
        ':description' => $description,
        ':id' => $id,
      ]);
      header("Location: category.php");
    }
  }

  if ($_GET) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE id=$id";
    $pdostatement = $pdo->prepare($sql);
    $pdostatement->execute();
    $result = $pdostatement->fetch();
  }
?>

    <?php include "header.html"; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Add Category</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="" method="post">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
                  <input type="hidden" name="id" value="<?= $result['id'] ?>">
                  <div class="form-group">
                    <label>Name</label>
                    <span class="text-danger"><?php if($nameError) echo "*".$nameError; ?></span>
                    <input type="text" name="name" class="form-control" value="<?= escape($result['name']) ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <span class="text-danger"><?php if($descError) echo "*".$descError; ?></span>
                    <textarea name="description" rows="8" class="form-control" required><?= escape($result['description']) ?></textarea>
                  </div>
                  <button class="btn btn-primary">Update</button>
                  <a href="/admin/category.php" class="btn btn-warning">Back</a>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <?php include 'footer.html'; ?>
