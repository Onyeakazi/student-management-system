<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout');
    exit();
}

// Handle deletion
if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM students WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_INT);
    $query->execute();

    // Lecturers deletion
    $sql = "DELETE FROM lecturers WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_INT);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'search';</script>";
    exit();
}

include_once('includes/header.php');
?>

<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">Search Student</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Search Student</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form method="post">
                                <div class="form-group">
                                    <strong>Search Lecturer / Student:</strong>
                                    <input type="text" name="searchdata" class="form-control" required placeholder="Search by Name">
                                </div>
                                <button type="submit" class="btn btn-primary" name="search">Search</button>
                            </form>

                            <?php
                            if (isset($_POST['search'])) {
                                $sdata = trim($_POST['searchdata']);
                                echo '<h4 class="mt-4">Search Results for: "' . htmlspecialchars($sdata) . '"</h4>';

                                // Search Students
                                $sql_students = "SELECT students.ID, students.name, users.email, students.department AS department
                                                 FROM students
                                                 JOIN users ON students.user_id = users.id
                                                 WHERE students.name LIKE :search";
                                $stmt_students = $dbh->prepare($sql_students);
                                $searchTerm = '%' . $sdata . '%';
                                $stmt_students->bindParam(':search', $searchTerm, PDO::PARAM_STR);
                                $stmt_students->execute();
                                $students = $stmt_students->fetchAll(PDO::FETCH_OBJ);

                                // If student found, show table
                                if (count($students) > 0) {
                                    echo '<div class="table-responsive border mt-3">';
                                    echo '<h5 class="text-primary">Students Found</h5>';
                                    echo '<table class="table ">';
                                    echo '<thead><tr><th>S/N</th><th>Name</th><th>Email</th><th>Department</th><th>Actions</th></tr></thead><tbody>';
                                    $sn = 1;
                                    foreach ($students as $student) {
                                        echo '<tr>';
                                        echo '<td>' . $sn++ . '</td>';
                                        echo '<td>' . htmlspecialchars($student->name) . '</td>';
                                        echo '<td>' . htmlspecialchars($student->email) . '</td>';
                                        echo '<td>' . htmlspecialchars($student->department) . '</td>';
                                        echo '<td>
                                                <a href="manage-class?delid' . htmlentities($student->id) .'" onclick="return confirm("Do you really want to Delete ?");" class="btn btn-danger btn-sm" title="Delete"> <i class="icon-trash"></i></a>
                                              </td> ';
                                        echo '</tr>';
                                    }
                                    echo '</tbody></table></div>';
                                } else {
                                  // If no student, search lecturers
                                  $sql_lecturers = "SELECT lecturers.ID as lecturer_id, lecturers.name, users.email, lecturers.department AS department
                                  FROM lecturers
                                  JOIN users ON lecturers.user_id = users.id
                                  WHERE lecturers.name LIKE :search";
;
                                  $stmt_lecturers = $dbh->prepare($sql_lecturers);
                                  $stmt_lecturers->bindParam(':search', $searchTerm, PDO::PARAM_STR);
                                  $stmt_lecturers->execute();
                                  $lecturers = $stmt_lecturers->fetchAll(PDO::FETCH_OBJ);

                                  if (count($lecturers) > 0) {
                                    echo '<div class="table-responsive border mt-3">';
                                    echo '<h5 class="text-success">Lecturers Found</h5>';
                                    echo '<table class="table ">';
                                    echo '<thead><tr><th>S/N</th><th>Name</th><th>Email</th><th>Department</th><th>Actions</th></tr></thead><tbody>';
                                    $sn = 1;
                                    foreach ($lecturers as $lecturer) {
                                        echo '<tr>';
                                        echo '<td>' . $sn++ . '</td>';
                                        echo '<td>' . htmlspecialchars($lecturer->name) . '</td>';
                                        echo '<td>' . htmlspecialchars($lecturer->email) . '</td>';
                                        echo '<td>' . htmlspecialchars($lecturer->department) . '</td>';
                                        echo '<td>
                                                <a href="search?delid' . htmlentities($lecturer->lecturer_id) .'" onclick="return confirm("Do you really want to Delete ?");" class="btn btn-danger btn-sm" title="Delete"> <i class="icon-trash"></i></a>
                                              </td> ';
                                        echo '</tr>';
                                    }
                                    echo '</tbody></table></div>';
                                  } else {
                                      echo '<p class="text-center mt-3 text-danger">No results found.</p>';
                                  }
                                }
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
