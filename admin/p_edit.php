<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';
  //check session
  if(empty($_SESSION['user_id']) and empty($_SESSION['logged_in'])){
    header('location: /admin/login.php');
  }
  if ($_POST) {
    $qtyValid = is_numeric($_POST['quantity']);
    $priceValid = is_numeric($_POST['price']);
    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) || empty($_POST['price'])) {
      if (empty($_POST['name'])) {
        $nameError = "Name can't be null!";
      }
      if (empty($_POST['description'])) {
        $descError = "Description can't be null!";
      }
      if (empty($_POST['category'])) {
        $catError = "Category can't be null!";
      }
      if (empty($_POST['price'])) {
        $priceError = "Price can't be null!";
      }
    }elseif($qtyValid != 1){
      $qtyError = "Quantity must be numeric!";
    }elseif($priceValid != 1){
      $priceError = "Price must be numeric!";
    }else{
      $id = $_POST['id'];
      $name = $_POST['name'];
      $description = $_POST['description'];
      $category = $_POST['category'];
      $quantity = empty($_POST['quantity']) ? 0 : $_POST['quantity'];
      $price = $_POST['price'];
      //check image update or not
      if($_FILES['image']['name']){
        $file = "images/".$_FILES['image']['name'];
        $fileType = pathinfo($file,PATHINFO_EXTENSION);
        if ($fileType!="png" && $fileType!="jpg" && $fileType!="jpeg") {
          echo "<script>alert('Image must be png,jpg or jpeg')</script>";
        }else{
          move_uploaded_file($_FILES['image']['tmp_name'], $file);
          $usql = "UPDATE products SET name=:name,description=:description,category_id=:category,quantity=:quantity,price=:price,image=:image WHERE id=:id";
          $pdostatement = $pdo->prepare($usql);
          $pdostatement->execute([
            ':name' => $name,
            ':description' => $description,
            ':category' => $category,
            ':quantity' => $quantity,
            ':price' => $price,
            ':image' => $file,
            ':id' => $id
          ]);
        }
      }else{
        $usql = "UPDATE products SET name=:name,description=:description,category_id=:category,quantity=:quantity,price=:price WHERE id=:id";
        $pdostatement = $pdo->prepare($usql);
        $pdostatement->execute([
          ':name' => $name,
          ':description' => $description,
          ':category' => $category,
          ':quantity' => $quantity,
          ':price' => $price,
          ':id' => $id
        ]);
      }
      header("Location: /admin/index.php");
    }
  }
  if ($_GET) {
    $id = $_GET['id'];
    $gSql = "SELECT * FROM products WHERE id=".$id;
    $getPdo = $pdo->prepare($gSql);
    $getPdo->execute();
    $p_result = $getPdo->fetch();
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
                <h3 class="card-title">Edit Product</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
                  <input type="hidden" name="id" value="<?php echo $p_result['id'] ?>">
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group container">
                        <label>Name</label>
                        <span class="text-danger"><?php if($nameError) echo "*".$nameError; ?></span>
                        <input type="text" name="name" class="form-control" value="<?= escape($p_result['name']) ?>">
                      </div>
                      <div class="form-group container">
                        <label>Description</label>
                        <span class="text-danger"><?php if($descError) echo "*".$descError; ?></span>
                        <textarea name="description" rows="4" class="form-control"><?= escape($p_result['description']) ?></textarea>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Image</label><br>
                        <span class="text-danger"><?php if($imageError) echo "*".$imageError; ?></span>
                        <img src="<?= escape($p_result['image']) ?>" width='150px' height='150px'><br>
                        <input type="file" name="image">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <?php 
                      $cat_sql = "SELECT * FROM categories ORDER BY name";
                      $c_pdo = $pdo->prepare($cat_sql);
                      $c_pdo->execute();
                      $c_results = $c_pdo->fetchAll();
                    ?>
                    <label>Category</label>
                    <span class="text-danger"><?php if($catError) echo "*".$catError; ?></span>
                    <select class="form-control" name="category">
                      <option value="">Select Category</option>
                      <?php foreach($c_results as $c_result): ?>
                        <option value="<?= $c_result['id'] ?>" <?php if($c_result['id']==$p_result['category_id']){echo 'selected';} ?>><?= $c_result['name'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Quantity</label>
                    <span class="text-danger"><?php if($qtyError) echo "*".$qtyError; ?></span>
                    <input type="number" name="quantity" class="form-control" value="<?= escape($p_result['quantity']) ?>" diabled>
                  </div>
                  <div class="form-group">
                    <label>Price</label>
                    <span class="text-danger"><?php if($priceError) echo "*".$priceError; ?></span>
                    <input type="number" name="price" class="form-control" value="<?= escape($p_result['price']) ?>">
                  </div>
                  <button class="btn btn-primary">Update</button>
                  <a href="/admin/index.php" class="btn btn-warning">Back</a>
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
