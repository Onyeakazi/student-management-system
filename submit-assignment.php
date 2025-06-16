<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
    exit();
}
  $assignment_id = $_GET['id'] ?? 0;
  $student_id = $_SESSION['sturecmsaid'];

  // Get student actual ID
  $stmt = $dbh->prepare("SELECT id FROM students WHERE user_id = ?");
  $stmt->execute([$student_id]);
  $student = $stmt->fetch();
  $actual_student_id = $student['id'];

  $check = $dbh->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE assignment_id = ? AND student_id = ?");
  $check->execute([$assignment_id, $actual_student_id]);
  $already_submitted = $check->fetchColumn() > 0;

  // Fetch assignment details
  $stmt = $dbh->prepare("SELECT a.*, c.name AS course_name FROM assignments a 
                        JOIN courses c ON a.course_id = c.id 
                        WHERE a.id = ?");
  $stmt->execute([$assignment_id]);
  $assignment = $stmt->fetch();

  if (!$assignment) {
      die("Invalid assignment.");
  }

  if (isset($_POST['submit'])) {
    $assignment_id = intval($_POST['assignment_id']);
    $student_user_id = $_SESSION['sturecmsaid'];

    // Check for duplicate submission
    $check = $dbh->prepare("SELECT COUNT(*) FROM assignment_submissions WHERE assignment_id = ? AND student_id = ?");
    $check->execute([$assignment_id, $actual_student_id]);
    if ($check->fetchColumn() > 0) {
        die("You have already submitted this assignment.");
    }

    // Check deadline
    $deadlineCheck = $dbh->prepare("SELECT deadline FROM assignments WHERE id = ?");
    $deadlineCheck->execute([$assignment_id]);
    $deadline = $deadlineCheck->fetchColumn();

    if (strtotime($deadline) < time()) {
        die("Submission deadline has passed.");
    }

    // Handle file upload
    $answer_file = '';
    if (!empty($_FILES['answer_file']['name'])) {
        $allowed_extensions = ['pdf', 'doc', 'docx', 'txt'];
        $ext = strtolower(pathinfo($_FILES['answer_file']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_extensions)) {
            die("Invalid file format. Only PDF, DOC, DOCX, TXT allowed.");
        }

        $file_name = time() . '_' . basename($_FILES['answer_file']['name']);
        $target_path = 'uploads/submissions/' . $file_name;

        if (move_uploaded_file($_FILES['answer_file']['tmp_name'], $target_path)) {
            $answer_file = $file_name;
        } else {
            die("File upload failed.");
        }
    }

    // Insert into database
    $answer_text = trim($_POST['answer_text'] ?? '');

    $insert = $dbh->prepare("INSERT INTO assignment_submissions (assignment_id, student_id, answer_text, answer_file, submitted_at)
                             VALUES (?, ?, ?, ?, NOW())");
    $insert->execute([$assignment_id, $actual_student_id, $answer_text, $answer_file]);

    echo "<script>alert('Assignment submitted successfully!'); window.location.href='view-assignments';</script>";
    exit();
  } 

?>


<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
  <?php include_once('includes/sidebar.php'); ?>
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
        <h3 class="page-title"> Submit Assignment </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
          </ol>
        </nav>
      </div>
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              
              <p><strong>Title:</strong> <?php echo htmlentities($assignment['title']); ?></p>
              <p><strong>Course:</strong> <?php echo htmlentities($assignment['course_name']); ?></p>
              <p><strong>Deadline:</strong> <?php echo htmlentities($assignment['deadline']); ?></p>
              <p><strong>Description:</strong> <?php echo htmlentities($assignment['description']); ?></p>
              <?php if ($assignment['file_path']): ?>
                <p><a href="uploads/assignments/<?php echo htmlentities($assignment['file_path']); ?>" target="_blank">Download File</a></p>
              <?php endif; ?>

              <?php if ($already_submitted): ?>
                <div class="alert alert-success">You have already submitted this assignment.</div>
              <?php elseif (strtotime($assignment['deadline']) < time()): ?>
                <div class="alert alert-danger">Submission deadline has passed.</div>
              <?php else: ?>
                <form method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="assignment_id" value="<?php echo $assignment['id']; ?>">

                  <div class="form-group">
                    <label>Your Answer</label>
                    <textarea name="answer_text" class="form-control" rows="8" required></textarea>
                  </div>

                  <div class="form-group">
                    <label>Upload a file (optional)</label>
                    <input type="file" name="answer_file" class="form-control">
                  </div>

                  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once('includes/footer.php'); ?>
  </div>
</div>
