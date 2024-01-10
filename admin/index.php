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
                    <h3 class="card-title">Products list</h3>
                  </div>
                  <div class="col-md-6">
                    <a href="add.php" class="btn btn-primary float-right">Add New Item</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>

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
