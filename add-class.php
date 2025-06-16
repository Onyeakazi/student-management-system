<?php
  session_start();
  error_reporting(0);
  include('includes/dbconnection.php');

  if (strlen($_SESSION['sturecmsaid']==0)) {
      header('location:logout');
  } else{
    if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $code=$_POST['code'];
    $school=$_POST['school'];
    $description = $_POST['description'];

    $lecturer_id = $_SESSION['sturecmsaid']; 

    $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
    $stmt->execute([$lecturer_id]);
    $lecturer = $stmt->fetch();

    if ($lecturer) {
      $actual_lecturer_id = $lecturer['id'];

      $sql="INSERT INTO courses(name,code,school, description, instructor_id)VALUES(:name,:code,:school, :description, :instructor_id)";
      $query=$dbh->prepare($sql);
      $query->bindParam(':name',$name,PDO::PARAM_STR);
      $query->bindParam(':code',$code,PDO::PARAM_STR);
      $query->bindParam(':school',$school,PDO::PARAM_STR);
      $query->bindParam(':description', $description, PDO::PARAM_STR);
      $query->bindParam(':instructor_id', $actual_lecturer_id, PDO::PARAM_STR);
      $query->execute();
      $LastInsertId=$dbh->lastInsertId();

      if ($LastInsertId>0) {
        echo '<script>alert("Course has been added.")</script>';
        echo "<script>window.location.href ='add-class'</script>";
      }else{
        echo '<script>alert("Something Went Wrong. Please try again")</script>';
      }
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
              <h3 class="page-title"> Add Course </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Add Course</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   
                    <form class="forms-sample" method="post">
                      <div class="form-group">
                        <label for="exampleInputName1">Course Title</label>
                        <input type="text" name="name" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Course Code</label>
                        <input type="text" name="code" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">Scool</label>
                          <select  name="school" class="form-control" required='true'>
                            <option>-- Select School --</option>
                            <?php 
                              $query = "SELECT * FROM school";
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
                      <div class="form-group">
                        <label for="exampleInputName1">Note</label>
                        <textarea name="description" value="" class="form-control"></textarea>
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