<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
    exit();
}

if (!isset($_GET['submission_id'])) {
    echo "Invalid request.";
    exit();
}

$submission_id = $_GET['submission_id'];

// Confirm this submission belongs to the logged-in student
$student_user_id = $_SESSION['sturecmsaid'];

$stmt = $dbh->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->execute([$student_user_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student not found.";
    exit();
}

$submission = $dbh->prepare("
    SELECT s.*, a.title AS assignment_title, c.name AS course_name 
    FROM assignment_submissions s
    JOIN assignments a ON s.assignment_id = a.id
    JOIN courses c ON a.course_id = c.id
    WHERE s.id = ? AND s.student_id = ?
");
$submission->execute([$submission_id, $student['id']]);
$data = $submission->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Submission not found or access denied.";
    exit();
}
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 class="page-title">Assignment Grade</h3>
            <div class="row">
                <div class="col-md-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?= htmlentities($data['assignment_title']) ?> (<?= htmlentities($data['course_name']) ?>)</h4>
                            <p><strong>Submitted At:</strong> <?= htmlentities($data['submitted_at']) ?></p>
                            <p><strong>Score:</strong> <?= is_null($data['score']) ? 'Not graded yet' : $data['score'] . '%' ?></p>
                            <p><strong>Feedback:</strong><br> <?= nl2br(htmlentities($data['feedback'])) ?: 'No feedback given.' ?></p>
                            <a href="view-assignments" class="btn btn-secondary">Back to Assignments</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
