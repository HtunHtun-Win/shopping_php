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
      $file = "images/".$_FILES['image']['name'];
      $fileType = pathinfo($file,PATHINFO_EXTENSION);
      if ($fileType!="png" && $fileType!="jpg" && $fileType!="jpeg") {
        echo "<script>alert('Image must be png,jpg or jpeg')</script>";
      }else{
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $quantity = empty($_POST['quantity']) ? 0 : $_POST['quantity'];
        $price = $_POST['price'];
        $sql = "INSERT INTO products(name,description,category_id,quantity,price,image) VALUES(:name,:description,:category,:quantity,:price,:image)";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute([
          ':name' => $name,
          ':description' => $description,
          ':category' => $category,
          ':quantity' => $quantity,
          ':price' => $price,
          ':image' => $file,
        ]);
        move_uploaded_file($_FILES['image']['tmp_name'], $file);
      }
    }
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
                <h3 class="card-title">Add New Product</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
                  <div class="form-group">
                    <label>Name</label>
                    <span class="text-danger"><?php if($nameError) echo "*".$nameError; ?></span>
                    <input type="text" name="name" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <span class="text-danger"><?php if($descError) echo "*".$descError; ?></span>
                    <textarea name="description" rows="4" class="form-control"></textarea>
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
                        <option value="<?= $c_result['id'] ?>"><?= $c_result['name'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Quantity</label>
                    <span class="text-danger"><?php if($qtyError) echo "*".$qtyError; ?></span>
                    <input type="number" name="quantity" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Price</label>
                    <span class="text-danger"><?php if($priceError) echo "*".$priceError; ?></span>
                    <input type="number" name="price" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Image</label>
                    <span class="text-danger"><?php if($imageError) echo "*".$imageError; ?></span>
                    <input type="file" name="image">
                  </div>
                  <button class="btn btn-primary">Submit</button>
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
