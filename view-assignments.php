<?php
  session_start();
  include('includes/dbconnection.php');

  if (strlen($_SESSION['sturecmsaid']) == 0) {
      header('location:logout');
      exit();
  }

  $student_id = $_SESSION['sturecmsaid'];

  $stmt = $dbh->prepare("SELECT id FROM students WHERE user_id = ?");
  $stmt->execute([$student_id]);
  $student = $stmt->fetch();

  $course_ids = $dbh->prepare("SELECT course_id FROM student_courses WHERE student_id = ?");
  $course_ids->execute([$student['id']]);
  $ids = $course_ids->fetchAll(PDO::FETCH_COLUMN);

  $in = str_repeat('?,', count($ids) - 1) . '?';
  $assignments = $dbh->prepare("
    SELECT 
      a.*, 
      c.name AS course_name,
      s.id AS submission_id,
      s.score
    FROM assignments a
    JOIN courses c ON a.course_id = c.id
    LEFT JOIN assignment_submissions s 
      ON s.assignment_id = a.id AND s.student_id = ?
    WHERE a.course_id IN ($in)
    ORDER BY a.deadline DESC
  ");
  $assignments->execute(array_merge([$student['id']], $ids));

?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
  <?php include_once('includes/sidebar.php'); ?>
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
        <h3 class="page-title">Available Assignments</h3>
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
                <div class="table-responsive border rounded p-1">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Course</th>
                          <th>Deadline</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($assignments as $a): ?>
                          <tr>
                            <td><?php echo htmlentities($a['title']); ?></td>
                            <td><?php echo htmlentities($a['course_name']); ?></td>
                            <td><?php echo htmlentities($a['deadline']); ?></td>
                            <td>
                              <a href="submit-assignment?id=<?= $a['id']; ?>" class="btn btn-info btn-sm me-2">
                                Do Assignment
                              </a>

                              <?php if (!is_null($a['score'])): ?>
                                <a href="view-assignment-grade?submission_id=<?= $a['submission_id']; ?>" class="btn btn-success btn-sm mt-1">
                                  View Grade
                                </a>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once('includes/footer.php'); ?>
  </div>
</div>
