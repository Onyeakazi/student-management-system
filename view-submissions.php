<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
    exit();
}

// Fetch all ungraded assignment submissions
$stmt = $dbh->prepare("
    SELECT s.*, st.name, st.matric_number, a.title, c.name AS courses_name, c.code
    FROM assignment_submissions s
    JOIN students st ON s.student_id = st.id
    JOIN assignments a ON s.assignment_id = a.id
    JOIN courses c ON a.course_id = c.id
    WHERE s.score IS NULL
");
$stmt->execute();
$ungraded = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 class="page-title">Ungraded Submissions</h3>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <?php if (count($ungraded) > 0): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Matric No</th>
                                            <th>Course</th>
                                            <th>Code</th>
                                            <th>Assignment Title</th>
                                            <th>Submitted At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ungraded as $sub): ?>
                                            <tr>
                                                <td><?= htmlentities($sub->name); ?></td>
                                                <td><?= htmlentities($sub->matric_number); ?></td>
                                                <td><?= htmlentities($sub->courses_name); ?></td>
                                                <td><?= htmlentities($sub->code); ?></td>
                                                <td><?= htmlentities($sub->title); ?></td>
                                                <td><?= htmlentities($sub->submitted_at); ?></td>
                                                <td>
                                                    <a href="grade-assignment?submission_id=<?= $sub->id; ?>" class="btn btn-sm btn-primary">
                                                        Grade Now
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No ungraded submissions available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
