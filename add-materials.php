<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
  header('location:logout');
  exit();
}

// ===== DELETE MATERIAL =====
if (isset($_GET['delid'])) {
  $id = intval($_GET['delid']);

  $stmt = $dbh->prepare("SELECT file_path FROM materials WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $file = $stmt->fetch(PDO::FETCH_OBJ);
  if ($file && file_exists($file->file_path)) {
    unlink($file->file_path);
  }

  $sql = "DELETE FROM materials WHERE id = :id";
  $query = $dbh->prepare($sql);
  $query->bindParam(':id', $id);
  $query->execute();

  echo "<script>alert('Material deleted successfully.');</script>";
  echo "<script>window.location.href = 'upload-material.php';</script>";
}

// ===== UPLOAD MATERIAL =====
if (isset($_POST['submit'])) {
  $course_id = $_POST['course_id'];
  $title = $_POST['title'];
  $file_name = $_FILES["material"]["name"];
  $temp_name = $_FILES["material"]["tmp_name"];
  $target_dir = "assets/materials/";
  $file_path = $target_dir . time() . "_" . basename($file_name);

  $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
  $allowed = array("pdf", "docx", "pptx");

  $lecturer_id = $_SESSION['sturecmsaid']; 

    $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
    $stmt->execute([$lecturer_id]);
    $lecturer = $stmt->fetch();

    if ($lecturer) {
        $actual_lecturer_id = $lecturer['id'];

        if (!in_array($file_type, $allowed)) {
            echo "<script>alert('Only PDF, DOCX, and PPTX files are allowed.');</script>";
        } else {
            if (move_uploaded_file($temp_name, $file_path)) {
                // Add lecturer_id to the INSERT query
                $sql = "INSERT INTO materials (course_id, title, file_path, lecturer_id)
                        VALUES (:course_id, :title, :file_path, :lecturer_id)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':course_id', $course_id);
                $query->bindParam(':title', $title);
                $query->bindParam(':file_path', $file_path);
                $query->bindParam(':lecturer_id', $actual_lecturer_id, PDO::PARAM_INT); 
                $query->execute();

                echo "<script>alert('Material uploaded successfully.');</script>";
                echo "<script>window.location.href = 'add-materials';</script>";
            } else {
                echo "<script>alert('File upload failed.');</script>";
            }
        }
    } else {
        echo "<script>alert('Lecturer not found.');</script>";
    }

}
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
  <?php include_once('includes/sidebar.php'); ?>
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
        <h3 class="page-title">Upload Course Material</h3>
      </div>
      <div class="row">
        <!-- Upload Form -->
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
                  <label>Material Title</label>
                  <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Upload File (PDF, DOCX, PPTX)</label>
                  <input type="file" name="material" accept=".pdf,.docx,.pptx" class="form-control" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Upload</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Display Table -->
        <div class="col-md-12 grid-margin stretch-card mt-4">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Uploaded Materials</h4>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Course</th>
              <th>File</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Step 1: Get the current lecturer's ID
            $lecturer_user_id = $_SESSION['sturecmsaid'];
            $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
            $stmt->execute([$lecturer_user_id]);
            $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);
            $actual_lecturer_id = $lecturer ? $lecturer['id'] : 0;

            // Step 2: Fetch only materials for courses the lecturer teaches
            $sql = "SELECT m.id, m.title, m.file_path, c.name AS course_name 
                    FROM materials m 
                    JOIN courses c ON m.course_id = c.id 
                    WHERE m.lecturer_id = :lecturer_id 
                    ORDER BY m.created_at DESC";
            $query = $dbh->prepare($sql);
            $query->bindParam(':lecturer_id', $actual_lecturer_id, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            $cnt = 1;
            foreach ($results as $row) {
            ?>
              <tr>
                <td><?php echo htmlentities($cnt++); ?></td>
                <td><?php echo htmlentities($row->title); ?></td>
                <td><?php echo htmlentities($row->course_name); ?></td>
                <td><a href="<?php echo htmlentities($row->file_path); ?>" target="_blank">Download</a></td>
                <td>
                  <a href="add-materials?delid=<?php echo $row->id; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

      </div>
      <?php include_once('includes/footer.php'); ?>
    </div>
  </div>
</div>
