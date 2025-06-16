<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

// Restrict access to only instructors
if ($_SESSION['role'] !== 'instructor') {
    header('location:logout');
    exit();
}

// Handle new meeting link submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['link'])) {
    $link = $_POST['link'];
    $stmt = $dbh->prepare("INSERT INTO meet_link (link) VALUES (?)");
    $stmt->execute([$link]);
    echo "<script>alert('Meeting link added successfully'); window.location.href='meet_link';</script>";
}

// Handle deletion of meeting link
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $stmt = $dbh->prepare("DELETE FROM meet_link WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Meeting link deleted successfully'); window.location.href='meet_link';</script>";
}

// Fetch all meeting links
$stmt = $dbh->query("SELECT * FROM meet_link ORDER BY created_at DESC");
$meet_links = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 class="page-title">Class Meeting Links</h3>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <!-- Add New Link -->
                            <h4 class="card-title">Create New Meeting Link</h4>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Google Meet Link</label>
                                    <input type="url" name="link" class="form-control" placeholder="https://meet.google.com/..." required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Link</button>
                            </form>

                            <!-- Divider -->
                            <hr class="my-4">

                            <!-- List of Links -->
                            <h4 class="card-title">Past Links</h4>
                            <?php if (count($meet_links) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Link</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($meet_links as $link): ?>
                                                <tr>
                                                    <td><a href="<?php echo htmlentities($link['link']); ?>" target="_blank"><?php echo htmlentities($link['link']); ?></a></td>
                                                    <td><?php echo htmlentities($link['created_at']); ?></td>
                                                    <td>
                                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this link?');">
                                                            <input type="hidden" name="delete_id" value="<?php echo $link['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p>No meeting links found.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
