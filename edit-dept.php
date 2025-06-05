<?php
  session_start();
  error_reporting(0);
  include('includes/dbconnection.php');

  if (strlen($_SESSION['sturecmsaid']==0)) {
    header('location:logout');
  } else{
    if(isset($_POST['submit'])){
    $school=$_POST['school'];
    $dept=$_POST['dept'];
    $hod=$_POST['hod'];
    $eid=$_GET['editid'];

    $sql="UPDATE departments SET school=:school, dept=:dept, hod=:hod where id=:eid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':school',$school,PDO::PARAM_STR);
    $query->bindParam(':dept',$dept,PDO::PARAM_STR);
    $query->bindParam(':hod',$hod,PDO::PARAM_STR);
    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->execute();
    
    echo '<script>alert("Department has been updated")</script>';
    header("Location: manage-dept");
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
              <h3 class="page-title"> Manage Department </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Manage Department</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Edit Department</h4>
                   
                    <form class="forms-sample" method="post">
                      <?php
                        $eid=$_GET['editid'];
                        $sql="SELECT * from  departments where id=$eid";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;

                        if($query->rowCount() > 0){
                          foreach($results as $row){               
                      ?>  

                      <div class="form-group">
                        <label for="exampleInputName1">School</label>
                        <input type="text" name="name" value="<?php  echo htmlentities($row->school);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Department</label>
                        <input type="text" name="code" value="<?php  echo htmlentities($row->dept);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">HOD</label>
                        <select  name="school" class="form-control" required='true'>
                          <?php 
                            $query = "SELECT * FROM lecturers";
                            $query = $dbh->prepare($query);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            if($query->rowCount() > 0) {
                              foreach($results as $result) {
                          ?> 
                          <option value="<?php echo htmlentities($result->name); ?>" <?php if($row->hod == $result->name) echo 'selected'; ?>>
                            <?php echo htmlentities($result->name); ?>
                          </option>
                          <?php }} ?>
                        </select>
                      </div>
                      
                      <?php $cnt=$cnt+1;}} ?>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                     
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
  <?php }  ?>