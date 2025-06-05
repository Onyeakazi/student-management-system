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
      $sql="delete from students where ID=:rid";
      $query=$dbh->prepare($sql);
      $query->bindParam(':rid',$rid,PDO::PARAM_STR);
      $query->execute();
      echo "<script>alert('Data deleted');</script>"; 
      echo "<script>window.location.href = 'manage-students'</script>";     
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
              <h3 class="page-title"> Manage Students </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Manage Students</li>
                </ol>
              </nav>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-sm-flex align-items-center mb-4">
                      <a class="text-dark ml-auto mb-3 mb-sm-0"> View all Students</a>
                    </div>
                    <div class="table-responsive border rounded p-1">
                      <table class="table">
                        <thead>
                          <tr>
                            <th class="font-weight-bold">S.No</th>
                            <th class="font-weight-bold">Student Name</th>
                            <th class="font-weight-bold">Student Reg.NO</th>
                            <th class="font-weight-bold">Student Dept</th>
                            <th class="font-weight-bold">Student Level</th>
                            <th class="font-weight-bold">Student Email</th>
                            <th class="font-weight-bold">Student DOB</th>
                            <th class="font-weight-bold">Guardian's Name</th>
                            <th class="font-weight-bold">Guardian's Phone</th>
                            <th class="font-weight-bold">Admissin Date</th>
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
                              $no_of_records_per_page = 15;
                              $offset = ($pageno-1) * $no_of_records_per_page;
                              $ret = "SELECT ID FROM students";
                              $query1 = $dbh -> prepare($ret);
                              $query1->execute();
                              $results1=$query1->fetchAll(PDO::FETCH_OBJ);
                              $total_rows=$query1->rowCount();
                              $total_pages = ceil($total_rows / $no_of_records_per_page);
                              $sql="SELECT students.*, users.email 
                                    FROM students 
                                    JOIN users ON students.user_id = users.id 
                                    LIMIT $offset, $no_of_records_per_page";
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
                            <td><?php  echo htmlentities($row->matric_number);?> </td>
                            <td><?php  echo htmlentities($row->department);?></td>
                            <td><?php  echo htmlentities($row->level);?></td>
                            <td><?php  echo htmlentities($row->email);?></td>
                            <td><?php  echo htmlentities($row->dob);?></td>
                            <td><?php  echo htmlentities($row->guardian_name);?></td>
                            <td><?php  echo htmlentities($row->guardian_phone);?></td>
                            <td><?php  echo htmlentities($row->creationDate);?></td>
                            <td>
                              <a href="manage-students?delid=<?php echo ($row->id);?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm"> <i class="icon-trash"></i></a>
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