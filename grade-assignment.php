<?php
    session_start();
    error_reporting(E_ALL);
    include('includes/dbconnection.php');

    if (strlen($_SESSION['sturecmsaid']) == 0) {
        header('location:logout');
        exit();
    }

    $submission_id = $_GET['submission_id'] ?? 0;

    // Fetch the specific submission
    $stmt = $dbh->prepare("
        SELECT s.*, st.name, st.matric_number, a.title 
        FROM assignment_submissions s
        JOIN students st ON s.student_id = st.id
        JOIN assignments a ON s.assignment_id = a.id
        WHERE s.id = ?
    ");
    $stmt->execute([$submission_id]);
    $submission = $stmt->fetch(PDO::FETCH_OBJ);

    // Handle grading
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $score = $_POST['grade'];
        $feedback = $_POST['feedback'];
        $updateStmt = $dbh->prepare("UPDATE assignment_submissions SET score = ?, feedback = ? WHERE id = ?");
        $updateStmt->execute([$score, $feedback, $submission_id]);

        header('Location: view-submissions'); 
        exit();
    }
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 class="page-title">Grade Submission</h3>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($submission): ?>
                                <p><strong>Student:</strong> <?= htmlentities($submission->name); ?></p>
                                <p><strong>Matric No:</strong> <?= htmlentities($submission->matric_number); ?></p>
                                <p><strong>Assignment Title:</strong> <?= htmlentities($submission->title); ?></p>
                                <p><strong>Submitted At:</strong> <?= htmlentities($submission->submitted_at); ?></p>
                                <p><strong>Answer Text:</strong><br><?= nl2br(htmlentities($submission->answer_text)); ?></p>
                                <p><strong>File:</strong>
                                    <?php if ($submission->answer_file): ?>
                                        <a href="uploads/submissions/<?= htmlentities($submission->answer_file); ?>" target="_blank">Download</a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </p>

                                <form method="POST">
                                    <div class="form-group">
                                        <label for="grade">Score (0 - 100)</label>
                                        <input type="number" name="grade" id="grade" class="form-control" min="0" max="100" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="feedback">Feedback</label>
                                        <input type="text" name="feedback" id="feedback" class="form-control" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success">Submit Grade</button>
                                </form>
                            <?php else: ?>
                                <p>No such submission found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
