<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
    exit();
}

$video_id = intval($_GET['id'] ?? 0);

// Fetch current video info
$sql = "SELECT * FROM videos WHERE id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $video_id, PDO::PARAM_INT);
$query->execute();
$video = $query->fetch(PDO::FETCH_OBJ);

if (!$video) {
    echo "Video not found.";
    exit();
}

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $course_id = $_POST['course_id'];
    $new_file_path = $video->file_path;

    if (!empty($_FILES["video"]["name"])) {
      $video_name = $_FILES["video"]["name"];
      $temp_name = $_FILES["video"]["tmp_name"];
      $target_dir = "assets/videos/";
      $file_type = strtolower(pathinfo($video_name, PATHINFO_EXTENSION));

      if ($file_type != "mp4") {
          echo "<script>alert('Only MP4 files are allowed.');</script>";
      } else {
          $filename = time() . "_" . basename($video_name);
          $full_path = $target_dir . $filename;

          if (move_uploaded_file($temp_name, $full_path)) {
              // Optionally delete old video
              if (file_exists($target_dir . $video->file_path)) {
                  unlink($target_dir . $video->file_path);
              }
              $new_file_path = $filename;  // save only filename in DB
          } else {
              echo "<script>alert('New video upload failed.');</script>";
              $new_file_path = $video->file_path;  // fallback to old filename
          }
      }
    }
    // Update DB
    $sql = "UPDATE videos SET title = :title, course_id = :course_id, file_path = :file_path WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':title', $title);
    $query->bindParam(':course_id', $course_id);
    $query->bindParam(':file_path', $new_file_path);
    $query->bindParam(':id', $video_id);
    $query->execute();

    echo "<script>alert('Video updated successfully.');</script>";
    echo "<script>window.location.href = 'add-video';</script>";
}
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
  <?php include_once('includes/sidebar.php'); ?>
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header"><h3 class="page-title">Edit Video</h3></div>
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
                        $selected = ($course->id == $video->course_id) ? "selected" : "";
                        echo "<option value='$course->id' $selected>" . htmlentities($course->name) . "</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label>Video Title</label>
                  <input type="text" name="title" class="form-control" value="<?= htmlentities($video->title) ?>" required>
                </div>

                <div class="form-group">
                  <label>Current Video</label><br>
                  <video width="100%" height="200" controls>
                    <source src="assets/videos/<?= htmlentities($video->file_path) ?>" type="video/mp4">
                  </video>
                </div>

                <div class="form-group">
                  <label>Replace Video (optional, MP4 only)</label>
                  <input type="file" name="video" accept=".mp4" class="form-control">
                </div>

                <button type="submit" name="update" class="btn btn-success">Update Video</button>
                <a href="add-video" class="btn btn-secondary">Cancel</a>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php include_once('includes/footer.php'); ?>
    </div>
  </div>
</div>
