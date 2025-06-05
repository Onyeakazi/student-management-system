<?php
  session_start();
  error_reporting(0);
  include('includes/dbconnection.php');

  if (strlen($_SESSION['sturecmsaid']==0)) {
    header('location:logout');
  } else{
    // Code for deletion
    if(isset($_GET['delid'])){
    $rid=intval($_GET['delid']);
    $sql="delete from courses where ID=:rid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Course deleted');</script>"; 
    echo "<script>window.location.href = 'manage-class'</script>";     
  }
?>

      <!-- partial:partials/_navbar.html -->
     <?php include_once('includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
             <div class="page-header">
              <h3 class="page-title"> Manage Class </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Manage Class</li>
                </ol>
              </nav>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive border rounded p-1">
                      <table class="table">
                        <thead>
                          <tr>
                            <th class="font-weight-bold">S.No</th>
                            <th class="font-weight-bold">Course Title</th>
                            <th class="font-weight-bold">Course Code</th>
                            <th class="font-weight-bold">School</th>
                            <th class="font-weight-bold">Note</th>
                            <th class="font-weight-bold">Lecturer</th>
                            <th class="font-weight-bold">Creation Date</th>
                            <th class="font-weight-bold">Action</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                           <?php
                              if (isset($_GET['pageno'])) {
                                $pageno = $_GET['pageno'];
                              } else {
                                $pageno = 1;
                              }
                              // Formula for pagination
                              $no_of_records_per_page =10;
                              $offset = ($pageno-1) * $no_of_records_per_page;
                              $ret = "SELECT ID FROM courses";
                              $query1 = $dbh -> prepare($ret);
                              $query1->execute();
                              $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                              $total_rows=$query1->rowCount();
                              $total_pages = ceil($total_rows / $no_of_records_per_page);
                              $sql="SELECT * from courses LIMIT $offset, $no_of_records_per_page";
                              $query = $dbh -> prepare($sql);
                              $query->execute();
                              $results=$query->fetchAll(PDO::FETCH_OBJ);

                              $cnt=1;
                              if($query->rowCount() > 0){
                                foreach($results as $row){               
                            ?>   
                            <tr>
                            <td><?php echo htmlentities($cnt);?></td>
                            <td><?php  echo htmlentities($row->name);?></td>
                            <td><?php  echo htmlentities($row->code);?></td>
                            <td><?php  echo htmlentities($row->school);?></td>
                            <td><?php echo htmlentities(substr($row->description, 0, 50)) . '...'; ?></td>
                            <td><?php  echo htmlentities($row->lecturer);?></td>
                            <td><?php  echo htmlentities($row->CreationDate);?></td>
                            <td>
                              <a href="edit-class-detail?editid=<?php echo htmlentities ($row->ID);?>" class="btn btn-primary btn-sm" title="Edit"><i class="icon-eye"></i></a>
                              <a href="manage-class?delid=<?php echo ($row->ID);?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm" title="Delete"> <i class="icon-trash"></i></a>
                            </td> 
                          </tr><?php $cnt=$cnt+1;}} ?>
                        </tbody>
                      </table>
                    </div>
                    <div align="left">
                      <ul class="pagination" >
                        <li><a href="?pageno=1"><strong>First></strong></a></li>
                        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>"><strong style="padding-left: 10px">Prev></strong></a>
                        </li>
                        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>"><strong style="padding-left: 10px">Next></strong></a>
                        </li>
                        <li><a href="?pageno=<?php echo $total_pages; ?>"><strong style="padding-left: 10px">Last</strong></a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         <?php include_once('includes/footer.php');?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php }  ?>