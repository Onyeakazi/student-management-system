<?php
  session_start();
  error_reporting(0);
  include('includes/dbconnection.php');
  if (strlen($_SESSION['sturecmsaid']==0)) {
    header('location:logout');
  } else{
    if(isset($_POST['submit'])){
    $title=$_POST['title'];
    $message=$_POST['message'];
    $eid=$_GET['editid'];
    $sql="UPDATE notice SET Noticetitle=:title, NoticeMsg=:message where ID=:eid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':title',$title,PDO::PARAM_STR);
    $query->bindParam(':message',$message,PDO::PARAM_STR);
    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Notice has been updated")</script>';
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
              <h3 class="page-title">Update Notice </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Update Notice</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Update Notice</h4>
                   
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      <?php
                        $eid=$_GET['editid'];
                        $sql="SELECT * FROM notice WHERE ID=:eid";
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':eid',$eid,PDO::PARAM_STR);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;
                        if($query->rowCount() > 0) {
                          foreach($results as $row){               
                      ?>
                      <div class="form-group">
                        <label for="exampleInputName1">Notice Title</label>
                        <input type="text" name="title" value="<?php  echo htmlentities($row->NoticeTitle);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Notice Message</label>
                        <textarea name="message" value="" class="form-control" required='true'><?php  echo htmlentities($row->NoticeMsg);?></textarea>
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