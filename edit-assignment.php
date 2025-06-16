<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
    exit();
}

$assignment_id = $_GET['id'] ?? 0;

// Fetch assignment details
$stmt = $dbh->prepare("SELECT * FROM assignments WHERE id = ?");
$stmt->execute([$assignment_id]);
$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$assignment) {
    echo "<p>Assignment not found.</p>";
    exit();
}

// Update logic
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    $file_path = $assignment['file_path'];
    if (!empty($_FILES['assignment_file']['name'])) {
        $file_name = time() . '_' . basename($_FILES['assignment_file']['name']);
        $target = 'assets/assignments/' . $file_name;
        move_uploaded_file($_FILES['assignment_file']['tmp_name'], $target);
        $file_path = $file_name;
    }

    $stmt = $dbh->prepare("UPDATE assignments SET title = ?, description = ?, deadline = ?, file_path = ? WHERE id = ?");
    $stmt->execute([$title, $description, $deadline, $file_path, $assignment_id]);

    echo "<script>alert('Assignment updated successfully'); window.location='lect-assignments';</script>";
    exit();
}

include_once('includes/header.php');
?>
<div class="container-fluid page-body-wrapper">
  <?php include_once('includes/sidebar.php'); ?>
  <div class="main-panel">
    <div class="content-wrapper">
      <h3 class="page-title">Edit Assignment</h3>
      <div class="row">
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <form class="forms-sample" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" name="title" class="form-control" value="<?= htmlentities($assignment['title']); ?>" required>
                </div>
                <div class="form-group">
                  <label>Description</label>
                  <textarea name="description" class="form-control" required><?= htmlentities($assignment['description']); ?></textarea>
                </div>
                <div class="form-group">
                  <label>Deadline</label>
                  <input type="datetime-local" name="deadline" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($assignment['deadline'])); ?>" required>
                </div>
                <div class="form-group">
                  <label>Change File (Optional)</label>
                  <input type="file" name="assignment_file" class="form-control">
                  <?php if (!empty($assignment['file_path'])): ?>
                    <p>Current: <a href="assets/assignments/<?= htmlentities($assignment['file_path']); ?>" target="_blank">Download</a></p>
                  <?php endif; ?>
                </div>
                <button type="submit" name="submit" class="btn btn-success">Update</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once('includes/footer.php'); ?>
  </div>
</div>
