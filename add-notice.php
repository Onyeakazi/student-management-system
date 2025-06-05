<?php
  session_start();
  error_reporting(0);
  include('includes/dbconnection.php');
  if (strlen($_SESSION['sturecmsaid']==0)) {
    header('location:logout.php');
  } else{
    if(isset($_POST['submit'])){
    $title=$_POST['title'];
    $classid=$_POST['classid'];
    $message= strip_tags($_POST['message']);
    $sql="INSERT INTO notice(NoticeTitle,NoticeMsg)values(:title,:message)";
    $query=$dbh->prepare($sql);
    $query->bindParam(':title',$title,PDO::PARAM_STR);
    $query->bindParam(':message',$message,PDO::PARAM_STR);
    $query->execute();
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
      echo '<script>alert("Notice has been added.")</script>';
      echo "<script>window.location.href ='add-notice'</script>";
    }else{
      echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
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
              <h3 class="page-title">Add Notice </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Add Notice</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Add Notice</h4>
                   
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label for="exampleInputName1">Notice Title</label>
                        <input type="text" name="title" value="" class="form-control" required='true'>
                      </div>
                     
                      <div class="form-group">
                        <label for="exampleInputName1">Notice Message</label>
                        <textarea name="message" value="" class="form-control"></textarea>
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

<?php }  ?>