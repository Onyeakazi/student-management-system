<?php
  session_start();
  error_reporting(0);
  include('includes/dbconnection.php');

  if (strlen($_SESSION['sturecmsaid']==0)) {
      header('location:logout');
  } else{
        if(isset($_POST['submit'])){
            $dept=$_POST['dept'];
            $school=$_POST['school'];
            $hod=$_POST['hod'];

            $sql="INSERT INTO departments(dept,school,hod)VALUES(:dept,:school,:hod)";
            $query=$dbh->prepare($sql);
            $query->bindParam(':dept',$dept,PDO::PARAM_STR);
            $query->bindParam(':school',$school,PDO::PARAM_STR);
            $query->bindParam(':hod',$hod,PDO::PARAM_STR);
            $query->execute();
            $LastInsertId=$dbh->lastInsertId();

            if ($LastInsertId>0) {
            echo '<script>alert("Department has been added.")</script>';
            echo "<script>window.location.href ='manage-dept'</script>";
            }else{
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
            }
        }
?>

     <?php include_once('includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Add Department </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Add Department</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   
                    <form class="forms-sample" method="post">
                      <div class="form-group">
                        <label for="exampleInputName1">School</label>
                        <input type="text" name="school" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Department</label>
                        <input type="text" name="dept" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">HOD</label>
                        <select  name="hod" class="form-control" required='true'>
                            <option>-- Assign HOD --</option>
                            <?php 
                                $query = "SELECT * FROM lecturers";
                                $query = $dbh->prepare($query);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                foreach($results as $result) {
                            ?>
                            <option value="<?php echo htmlentities($result->name); ?>">
                                <?php echo htmlentities($result->name); ?>
                            </option>
                            <?php }} ?>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
                     
                    </form>
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
    <!-- plugins:js -->
   <?php }  ?>