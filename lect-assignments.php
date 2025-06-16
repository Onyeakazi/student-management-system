<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
    exit();
}

if (isset($_GET['id'])) {
    $assignment_id = intval($_GET['id']);
    $stmt = $dbh->prepare("DELETE FROM assignments WHERE id = ?");
    $stmt->execute([$assignment_id]);
    header("Location: lect-assignments");
    exit();
}

// Get lecturer ID from session
$lecturer_user_id = $_SESSION['sturecmsaid'];
$stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
$stmt->execute([$lecturer_user_id]);
$lecturer = $stmt->fetch(PDO::FETCH_ASSOC);
$lecturer_id = $lecturer['id'] ?? 0;

// Fetch all assignments created by this lecturer
$stmt = $dbh->prepare("
    SELECT a.*, c.name as course_name
    FROM assignments a
    JOIN courses c ON a.course_id = c.id
    WHERE a.lecturer_id = ?
    ORDER BY a.id DESC
");
$stmt->execute([$lecturer_id]);
$assignments = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 class="page-title">My Assignments</h3>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <?php if (count($assignments) > 0): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Title</th>
                                            <th>Deadline</th>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($assignments as $a): ?>
                                            <tr>
                                                <td><?= htmlentities($a->course_name); ?></td>
                                                <td><?= htmlentities($a->title); ?></td>
                                                <td><?= htmlentities($a->deadline); ?></td>
                                                <td>
                                                    <?php if (!empty($a->file_path)): ?>
                                                        <a href="assets/assignments/<?= htmlentities($a->file_path); ?>" target="_blank">Download</a>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="edit-assignment?id=<?= $a->id ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="lect-assignments?id=<?= $a->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No assignments found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
