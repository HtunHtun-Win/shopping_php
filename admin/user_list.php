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
                    <h3 class="card-title">UserList</h3>
                  </div>
                  <div class="col-md-6">
                    <a href="user_add.php" class="btn btn-primary float-right">Add New User</a>
                  </div>
                </div>
              </div>
              <?php
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
                $numOfrecs = 5;
                $offset = ($pageno-1)*$numOfrecs;
                if (empty($_POST['search'])) {
                  //Get Total Pagesr
                  $pdostatement = $pdo->prepare("SELECT * FROM users");
                  $pdostatement->execute();
                  $rawresults = $pdostatement->fetchAll();
                  $total_pages = ceil(count($rawresults)/$numOfrecs);
                  //
                  $pdostatement = $pdo->prepare("SELECT * FROM users LIMIT $offset,$numOfrecs");
                  $pdostatement->execute();
                  $results = $pdostatement->fetchAll();
                }else{
                  $search = $_POST['search'];
                  echo $search;
                  //Get Total Pages
                  $pdostatement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC");
                  $pdostatement->execute();
                  $rawresults = $pdostatement->fetchAll();
                  $total_pages = ceil(count($rawresults)/$numOfrecs);
                  //
                  $pdostatement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
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
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($results as $result): ?>
                      <tr>
                        <td><?= $i ?></td>
                        <td><?= escape($result['name']) ?></td>
                        <td><?= escape($result['email'])  ?></td>
                        <td>
                          <span class="badge bg-primary">
                            <?php
                                $user_role = $result['role'] == 1 ? 'admin' : 'user';
                                echo "$user_role";
                             ?>
                          </span>
                        </td>
                        <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="user_edit.php?id=<?php echo $result['id'] ?>" class="btn btn-warning">Edit</a>
                            </div>
                            <?php if($_SESSION['user_id'] != $result['id']){ ?>
                              <div class="container">
                                <a href="#" class="btn btn-danger">Delete</a>
                              </div>
                            <?php }else{ ?>
                              <div class="container">
                                <a href="#" class="btn btn-success">Active</a>
                              </div>
                            <?php } ?>
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
