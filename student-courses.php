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
                            <th class="font-weight-bold">Lecturer</th>
                            <th class="font-weight-bold">Videos</th>
                            <th class="font-weight-bold">Materials</th>
                            
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            // Get logged-in user's ID from session
                            $userId = $_SESSION['sturecmsaid'];

                            // Step 1: Get student ID linked to this user
                            $studentSql = "SELECT id FROM students WHERE user_id = :user_id";
                            $studentQuery = $dbh->prepare($studentSql);
                            $studentQuery->bindParam(':user_id', $userId, PDO::PARAM_INT);
                            $studentQuery->execute();
                            $student = $studentQuery->fetch(PDO::FETCH_ASSOC);

                            if ($student) {
                                $studentId = $student['id'];

                                // Step 2: Fetch enrolled courses from student_courses + join courses table
                                $sql = "SELECT courses.* FROM student_courses 
                                        JOIN courses ON student_courses.course_id = courses.ID 
                                        WHERE student_courses.student_id = :student_id";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':student_id', $studentId, PDO::PARAM_INT);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                  foreach ($results as $row) {
                                    // Get videos linked to the course
                                    $videoSql = "SELECT * FROM videos WHERE course_id = :course_id";
                                    $videoQuery = $dbh->prepare($videoSql);
                                    $videoQuery->bindParam(':course_id', $row->ID, PDO::PARAM_INT);
                                    $videoQuery->execute();
                                    $videos = $videoQuery->fetchAll(PDO::FETCH_OBJ);

                                    // Get materials linked to the course
                                    $materialSql = "SELECT * FROM materials WHERE course_id = :course_id";
                                    $materialQuery = $dbh->prepare($materialSql);
                                    $materialQuery->bindParam(':course_id', $row->ID, PDO::PARAM_INT);
                                    $materialQuery->execute();
                                    $materials = $materialQuery->fetchAll(PDO::FETCH_OBJ);
                            ?>
                                    <tr>
                                      <td><?php echo htmlentities($cnt);?></td>
                                      <td><?php echo htmlentities($row->name);?></td>
                                      <td><?php echo htmlentities($row->code);?></td>
                                      <td><?php echo htmlentities($row->school);?></td>
                                      <td><?php echo htmlentities($row->lecturer);?></td>
                                      <!-- VIDEO SECTION -->
                                      <td>
                                        <?php
                                          if ($videos) {
                                            foreach ($videos as $video) {
                                              $videoPath = 'assets/videos/' . $video->file_path;
                                              $ext = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));

                                              echo '<div style="margin-bottom: 15px;">';

                                              if ($ext === 'mp4') {
                                                echo '
                                                <video style="width: 147px; height: 10;" controls>
                                                  <source src="' . $videoPath . '" type="video/mp4">
                                                  Your browser does not support the video tag.
                                                </video>';
                                              } else {
                                                echo '
                                                🎥 <a href="' . $videoPath . '" target="_blank" style="color: #007bff;">
                                                    ' . htmlentities($video->title) . '
                                                </a>';
                                              }

                                              echo '<div><strong>' . htmlentities($video->title) . '</strong></div>';
                                              echo '</div>';
                                            }
                                          } else {
                                            echo 'No videos';
                                          }
                                        ?>
                                      </td>

                                      <!-- MATERIAL SECTION -->
                                      <td>
                                        <?php
                                          if ($materials) {
                                            foreach ($materials as $material) {
                                              $materialPath = 'assets/materials/' . $material->file_path;
                                              $fileName = basename($materialPath);

                                              echo '
                                              <div style="margin-bottom: 12px;">
                                                📄 <strong>' . htmlentities($material->title) . '</strong><br>
                                                <a href="' . $materialPath . '" target="_blank" style="color: #007bff;">🔗 Preview</a> |
                                                <a href="' . $materialPath . '" download="' . $fileName . '" style="color: #28a745;">⬇️ Download</a>
                                              </div>';
                                            }
                                          } else {
                                            echo 'No materials';
                                          }
                                        ?>
                                      </td>

                                    </tr>
                            <?php
                                        $cnt++;
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>You have not enrolled in any courses.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No student record found for this user.</td></tr>";
                            }
                        ?>

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