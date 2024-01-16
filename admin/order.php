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

    <?php include "header.php"; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <h3 class="card-title">Order list</h3>
                  </div>
                </div>
              </div>
              <?php
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
                $numOfrecs = 20;
                $offset = ($pageno-1)*$numOfrecs;
                if (empty($_POST['search'])) {
                  //Get Total Pagesr
                  $pdostatement = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id");
                  $pdostatement->execute();
                  $rawresults = $pdostatement->fetchAll();
                  $total_pages = ceil(count($rawresults)/$numOfrecs);
                  //
                  $pdostatement = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id LIMIT $offset,$numOfrecs");
                  $pdostatement->execute();
                  $results = $pdostatement->fetchAll();
                }else{
                  $search = $_POST['search'];
                  //Get Total Pages
                  $pdostatement = $pdo->prepare("SELECT * FROM sale_orders WHERE title LIKE '%$search%' ORDER BY id");
                  $pdostatement->execute();
                  $rawresults = $pdostatement->fetchAll();
                  $total_pages = ceil(count($rawresults)/$numOfrecs);
                  //
                  $pdostatement = $pdo->prepare("SELECT * FROM sale_orders WHERE title LIKE '%$search%' ORDER BY id LIMIT $offset,$numOfrecs");
                  $pdostatement->execute();
                  $results = $pdostatement->fetchAll();
                }
                $i = 1;
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Customer</th>
                      <th>TotalPrice</th>
                      <th>Date</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($results as $result): ?>
                      <?php 
                        $cat_sql = "SELECT * FROM users WHERE id=".$result['user_id'];
                        $c_pdo = $pdo->prepare($cat_sql);
                        $c_pdo->execute();
                        $c_result = $c_pdo->fetch();
                      ?>
                      <tr>
                        <td><?= $i ?></td>
                        <td><?= escape($c_result['name']) ?></td>
                        <td><?= escape($result['total_price']) ?></td>
                        <td><?= escape(date("Y-M-d",strtotime($result['order_date']))) ?></td>
                        <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="order_detail.php?id=<?php echo $result['id'] ?>" class="btn btn-default btn-sm">
                                <span class="fa fa-list"></span>
                              </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php $i++; endforeach;?>
                  </tbody>
                </table>
                <nav aria-label="Page navigation example" style="float:right;margin-top:10px;">
                  <ul class="pagination">
                    <li class="page-item"> <a class="page-link" href="?pageno=1">First</a> </li>
                    <li class="page-item <?php if($pageno<=1){echo 'disabled';} ?>">
                      <a class="page-link " href="?pageno=<?php echo $pageno-1; ?>">Previous</a>
                    </li>
                    <li class="page-item"> <a class="page-link" href="#"><?php echo $pageno; ?></a> </li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                      <a class="page-link" href="?pageno=<?php echo $pageno+1; ?>">Next</a>
                    </li>
                    <li class="page-item"> <a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a> </li>
                  </ul>
                </nav>
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
