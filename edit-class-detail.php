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
    $lecturer=$_POST['lecturer'];
    $description=$_POST['description'];
    $eid=$_GET['editid'];

    $sql="UPDATE courses SET name=:name,code=:code, school=:school, lecturer=:lecturer, description=:description where ID=:eid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':name',$name,PDO::PARAM_STR);
    $query->bindParam(':code',$code,PDO::PARAM_STR);
    $query->bindParam(':school',$school,PDO::PARAM_STR);
    $query->bindParam(':lecturer',$lecturer,PDO::PARAM_STR);
    $query->bindParam(':description',$description,PDO::PARAM_STR);
    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->execute();
    
    // 2. Fetch lecturer's current courses from lecturers table
    $sqlLect = "SELECT courses FROM lecturers WHERE name = :lecturer";
    $queryLect = $dbh->prepare($sqlLect);
    $queryLect->bindParam(':lecturer', $lecturer, PDO::PARAM_STR);
    $queryLect->execute();
    $lecturerRow = $queryLect->fetch(PDO::FETCH_ASSOC);

    $currentCourses = $lecturerRow ? $lecturerRow['courses'] : '';

    // 3. Make an array of courses assigned to lecturer (split by comma, trimming spaces)
    $coursesArray = array_filter(array_map('trim', explode(',', $currentCourses)));

    // 4. Add new course code if not already present
    if (!in_array($name, $coursesArray)) {
        $coursesArray[] = $name;
    }

    // 5. Convert back to string
    $updatedCourses = implode(',', $coursesArray);

    // 6. Update lecturers table with new courses list
    $sqlUpdateLect = "UPDATE lecturers SET courses = :courses WHERE name = :lecturer";
    $queryUpdateLect = $dbh->prepare($sqlUpdateLect);
    $queryUpdateLect->bindParam(':courses', $updatedCourses, PDO::PARAM_STR);
    $queryUpdateLect->bindParam(':lecturer', $lecturer, PDO::PARAM_STR);
    $queryUpdateLect->execute();
    
    echo '<script>alert("Course has been updated")</script>';
    header("Location: manage-class");
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
                  <li class="breadcrumb-item active" aria-current="page"> Manage Course</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Edit Course</h4>
                   
                    <form class="forms-sample" method="post">
                      <?php
                        $eid=$_GET['editid'];
                        $sql="SELECT * from  courses where ID=$eid";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;

                        if($query->rowCount() > 0){
                          foreach($results as $row){               
                      ?>  

                      <div class="form-group">
                        <label for="exampleInputName1">Course Title</label>
                        <input type="text" name="name" value="<?php  echo htmlentities($row->name);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Course Code</label>
                        <input type="text" name="code" value="<?php  echo htmlentities($row->code);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">School</label>
                        <select  name="school" class="form-control" required='true'>
                          <?php 
                            $query = "SELECT * FROM school";
                            $query = $dbh->prepare($query);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            if($query->rowCount() > 0) {
                              foreach($results as $result) {
                          ?> 
                          <option value="<?php echo htmlentities($result->name); ?>" <?php if($row->school == $result->name) echo 'selected'; ?>>
                            <?php echo htmlentities($result->name); ?>
                          </option>
                          <?php }} ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">Assign to Lecturer</label>
                        <select  name="lecturer" class="form-control" required='true'>
                          <option>-- Select Lecturer --</option>
                          <?php 
                            $query = "SELECT * FROM lecturers";
                            $query = $dbh->prepare($query);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            if($query->rowCount() > 0) {
                              foreach($results as $result) {
                          ?> 
                          <option value="<?php echo htmlentities($result->name); ?>" <?php if($row->lecturer == $result->name) echo 'selected'; ?>>
                            <?php echo htmlentities($result->name); ?>
                          </option>
                          <?php }} ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Note</label>
                        <textarea name="description" class="form-control"><?php  echo htmlentities($row->description);?></textarea>
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