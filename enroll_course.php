<?php
  session_start();
  error_reporting(E_ALL);
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

                       <?php 

                            $userId = $_SESSION['sturecmsaid']; 

                            // Get the student's actual ID from the 'students' table using the userId
                            $stmt = $dbh->prepare("SELECT id FROM students WHERE user_id = :uid LIMIT 1");
                            $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
                            $stmt->execute();
                            $student = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($student && isset($_POST['enroll'])) {
                                $student_id = $student['id']; 
                                $course_id = $_POST['course_id'];

                                // Check if already enrolled
                                $check = $dbh->prepare("SELECT * FROM student_courses WHERE student_id = :sid AND course_id = :cid");
                                $check->bindParam(':sid', $student_id, PDO::PARAM_INT);
                                $check->bindParam(':cid', $course_id, PDO::PARAM_INT);
                                $check->execute();

                                if ($check->rowCount() == 0) {
                                    $insert = $dbh->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (:sid, :cid)");
                                    $insert->bindParam(':sid', $student_id, PDO::PARAM_INT);
                                    $insert->bindParam(':cid', $course_id, PDO::PARAM_INT);
                                    $insert->execute();
                                    echo "<script>alert('Course enrolled successfully');</script>";
                                } else {
                                    echo "<script>alert('You are already enrolled in this course');</script>";
                                }
                            }

                            // Fetch all courses
                            $sql = "SELECT * FROM courses";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $courses = $query->fetchAll(PDO::FETCH_OBJ);

                            // Handle unenrollment
                            if ($student && isset($_POST['unenroll'])) {
                                $student_id = $student['id'];
                                $course_id = $_POST['course_id'];

                                $delete = $dbh->prepare("DELETE FROM student_courses WHERE student_id = :sid AND course_id = :cid");
                                $delete->bindParam(':sid', $student_id, PDO::PARAM_INT);
                                $delete->bindParam(':cid', $course_id, PDO::PARAM_INT);
                                $delete->execute();

                                echo "<script>alert('You have successfully unenrolled from the course');</script>";
                            }

                        ?>


                        <h3>Available Courses</h3>
                        <form method="post">
                            <div class="table-responsive border rounded p-1">
                                <table class="table">
                                    <tr>
                                        <th class="font-weight-bold">Course Title</th>
                                        <th class="font-weight-bold">Code</th>
                                        <th class="font-weight-bold">Action</th>
                                    </tr>
                                    <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td><?php echo $course->name; ?></td>
                                        <td><?php echo $course->code; ?></td>
                                        <td>
                                            <?php
                                                

                                                // Check if student is already enrolled in this course
                                                $enrolledCheck = $dbh->prepare("SELECT * FROM student_courses WHERE student_id = :sid AND course_id = :cid");
                                                $enrolledCheck->bindParam(':sid', $student['id'], PDO::PARAM_INT);
                                                $enrolledCheck->bindParam(':cid', $course->ID, PDO::PARAM_INT);
                                                $enrolledCheck->execute();

                                                if ($enrolledCheck->rowCount() > 0) {
                                                    echo '
                                                        <button type="submit" name="unenroll" value="1" onclick="document.getElementById(\'course_id\').value=\'' . $course->ID . '\'" class="btn btn-danger btn-sm">Unenroll</button>
                                                    ';
                                                } else {
                                                    echo '
                                                        <button type="submit" name="enroll" value="1" onclick="document.getElementById(\'course_id\').value=\'' . $course->ID . '\'" class="btn btn-primary btn-sm">Enroll</button>
                                                    ';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <input type="hidden" name="course_id" id="course_id" />
                        </form>

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