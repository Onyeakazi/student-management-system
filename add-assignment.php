<?php
  session_start();
  error_reporting(E_ALL);
  include('includes/dbconnection.php');

  if (strlen($_SESSION['sturecmsaid']==0)) {
    header('location:logout');
  }else{
    if (isset($_POST['submit'])) {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    $file_path = '';
    if (!empty($_FILES['assignment_file']['name'])) {
        $file_name = time() . '_' . basename($_FILES['assignment_file']['name']);
        $target = 'assets/assignments/' . $file_name;
        move_uploaded_file($_FILES['assignment_file']['tmp_name'], $target);
        $file_path = $file_name;
    }

    // Get lecturer_id from the logged-in user
    $lecturer_user_id = $_SESSION['sturecmsaid'];

    $stmt1 = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
    $stmt1->execute([$lecturer_user_id]);
    $lecturer = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($lecturer) {
        $lecturer_id = $lecturer['id'];

        // Now insert including lecturer_id
        $stmt = $dbh->prepare("INSERT INTO assignments (course_id, title, description, deadline, file_path, lecturer_id) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$course_id, $title, $description, $deadline, $file_path, $lecturer_id]);

        echo "<script>alert('Assignment posted successfully');</script>";
    } else {
        echo "<script>alert('Lecturer not found');</script>";
    }
  }
?>

     <?php include_once('includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Add Assignment </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Add Assignment</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   
                    <form class="forms-sample" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Course</label>
                            <select class="form-control" name="course_id" required>
                                <?php
                                    $lecturer_id = $_SESSION['sturecmsaid']; 

                                    $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
                                    $stmt->execute([$lecturer_id]);
                                    $lecturer = $stmt->fetch();

                                    if ($lecturer) {
                                        $actual_lecturer_id = $lecturer['id'];
                                        
                                        $courses = $dbh->query("SELECT id, name FROM courses WHERE instructor_id = $actual_lecturer_id");
                                        foreach ($courses as $c) {
                                            echo "<option value='{$c['id']}'>{$c['name']}</option>";
                                        }
                                    }
                                ?>
                             </select>
                        </div>

                        <div class="form-group">
                            <label>Title</label>
                            <input class="form-control" type="text" name="title" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Deadline</label>
                            <input class="form-control" type="datetime-local" name="deadline" required>
                        </div>

                        <div class="form-group">
                            <label>File (Optional)</label>
                            <input class="form-control" type="file" name="assignment_file">
                        </div>

                        <button class="btn btn-primary" type="submit" name="submit">Upload Assignment</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         <?php include_once('includes/footer.php');?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
   <?php }  ?>