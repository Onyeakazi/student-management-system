<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout');
  exit();
}
 
  // Code for deletion
  if(isset($_GET['id'])){
    $id=intval($_GET['id']);
    $sql="delete from videos where ID=:id";
    $query=$dbh->prepare($sql);
    $query->bindParam(':id',$id,PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Video deleted');</script>"; 
    echo "<script>window.location.href = 'add-video'</script>";     
  }

if (isset($_POST['upload'])) {
  $course_id = $_POST['course_id'];
  $title = $_POST['title'];

  $video_name = $_FILES["video"]["name"];
  $temp_name = $_FILES["video"]["tmp_name"];
  $target_dir = "assets/videos/";
  $unique_file_name = time() . "_" . basename($video_name);
  $video_path = $unique_file_name; // store only the file name in DB

// Save to folder using full path

  $lecturer_id = $_SESSION['sturecmsaid']; 

  $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
  $stmt->execute([$lecturer_id]);
  $lecturer = $stmt->fetch();

  if ($lecturer) {
    $actual_lecturer_id = $lecturer['id'];

    // Validate file type (e.g., only MP4)
    $file_type = strtolower(pathinfo($video_path, PATHINFO_EXTENSION));
    if ($file_type != "mp4") {
      echo "<script>alert('Only MP4 files are allowed.');</script>";
    } else {
      // Move uploaded file
      if (move_uploaded_file($temp_name, $target_dir . $unique_file_name)) {
        $sql = "INSERT INTO videos(course_id, title, file_path, lect_id) VALUES(:course_id, :title, :file_path, :lect_id)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':course_id', $course_id);
        $query->bindParam(':title', $title);
        $query->bindParam(':file_path', $video_path);
        $query->bindParam(':lect_id', $actual_lecturer_id);
        $query->execute();
        echo "<script>alert('Video uploaded successfully.');</script>";
        header("Location: add-video");
      } else {
        echo "<script>alert('Video upload failed.');</script>";
        header("Location: add-video");
      }
    }
  }
}
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
  <?php include_once('includes/sidebar.php'); ?>
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header"><h3 class="page-title">Upload Course Video</h3></div>
      <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Select Course</label>
                  <select name="course_id" class="form-control" required>
                    <option value="">-- Select Course --</option>
                    <?php
                      $stmt = $dbh->query("SELECT id, name FROM courses");
                      while ($course = $stmt->fetch(PDO::FETCH_OBJ)) {
                        echo "<option value='$course->id'>" . htmlentities($course->name) . "</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Video Title</label>
                  <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Upload Video (MP4 only)</label>
                  <input type="file" name="video" accept=".mp4" class="form-control" required>
                </div>
                <button type="submit" name="upload" class="btn btn-primary">Upload Video</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Optional: Show Uploaded Videos -->
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Uploaded Videos</h4>
              <div class="row">
                <?php
                  // Step 1: Get the current lecturer's internal ID from session
                  $lecturer_user_id = $_SESSION['sturecmsaid'];
                  $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
                  $stmt->execute([$lecturer_user_id]);
                  $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);
                  $actual_lecturer_id = $lecturer ? $lecturer['id'] : 0;

                  // Step 2: Fetch only videos uploaded under this lecturer's courses
                  $stmt = $dbh->prepare("
                    SELECT v.id, v.title, v.file_path, c.name AS course_name 
                    FROM videos v 
                    JOIN courses c ON v.course_id = c.id 
                    WHERE v.lect_id = :lecturer_id 
                    ORDER BY v.uploaded_at DESC
                  ");
                  $stmt->bindParam(':lecturer_id', $actual_lecturer_id, PDO::PARAM_INT);
                  $stmt->execute();

                  while ($video = $stmt->fetch(PDO::FETCH_OBJ)) {
                      echo "<div class='col-md-6 mb-4'>";
                      echo "<div class='p-3 border rounded'>";
                      echo "<h5>" . htmlentities($video->title) . 
                          " <small class='text-muted'>(" . htmlentities($video->course_name) . ")</small></h5>";
                      echo "<video width='100%' height='200' controls>
                            <source src='assets/videos/" . htmlentities($video->file_path) . "' type='video/mp4'>
                          </video><br>";
                      echo "<a href='edit-video?id={$video->id}' class='btn btn-sm btn-primary mt-2'>Edit</a> ";
                      echo "<a href='add-video?id={$video->id}' class='btn btn-sm btn-danger mt-2' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                      echo "</div>";
                      echo "</div>";
                  }
                ?>
              </div>
            </div>
          </div>
        </div>

    </div>
    <?php include_once('includes/footer.php'); ?>
  </div>
</div>