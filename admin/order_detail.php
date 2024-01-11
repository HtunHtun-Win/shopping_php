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
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <h3 class="card-title">Order Detail</h3>
                  </div>
                  <div class="col-md-6">
                    <a class="btn btn-default float-right" href="/admin/order.php">Back</a>
                  </div>
                </div>
              </div>
              <?php
                if ($_GET['id']) {
                  $id = $_GET['id'];
                  $sql = "SELECT * FROM sale_order_detail WHERE sale_order_id=:id";
                  $pdostatement = $pdo->prepare($sql);
                  $pdostatement->execute([':id'=>$id]);
                  $results = $pdostatement->fetchAll();
                  $i = 1;
                }
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 100px">No</th>
                      <th>Item</th>
                      <th>Quantity</th>
                      <th style="width: 200px">Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($results as $result): ?>
                      <?php 
                        $p_sql = "SELECT * FROM products WHERE id=".$result['product_id'];
                        $p_pdo = $pdo->prepare($p_sql);
                        $p_pdo->execute();
                        $p_result = $p_pdo->fetch();
                      ?>
                      <tr>
                        <td><?= $i ?></td>
                        <td><?= $p_result['name'] ?></td>
                        <td><?= $result['quantity'] ?></td>
                        <td><?= escape(date("Y-M-d",strtotime($result['order_date']))) ?></td>
                      </tr>
                    <?php $i++; endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <?php include 'footer.html'; ?>
