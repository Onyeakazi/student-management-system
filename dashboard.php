<?php
session_start();
include('includes/dbconnection.php');

// Check login
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout');
    exit();
}

$instructor_id = $_SESSION['sturecmsaid']; // Assume instructor_id is saved as 'sturecmsaid'

// Counts for chart
$totclass = $dbh->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$totdept = $dbh->query("SELECT COUNT(*) FROM departments")->fetchColumn();
$totstu = $dbh->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totlect = $dbh->query("SELECT COUNT(*) FROM lecturers")->fetchColumn();
$totnotice = $dbh->query("SELECT COUNT(*) FROM notice")->fetchColumn();
?>

<?php include_once('includes/header.php'); ?>

<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-sm-flex align-items-baseline report-summary-header">
                                        <h5 class="font-weight-semibold">Report Summary</h5> 
                                        <span class="ml-auto">Updated Report</span>
                                        <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") : ?>

                              <?php
                                // Total Courses
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM courses");
                                $stmt->execute();
                                $courses = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card color-1">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total Courses</span>
                                    <h4><?php echo $courses->total; ?></h4>
                                    <a href="manage-class"><span class="report-count">View Courses</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-rocket"></i></div>
                                </div>
                              </div>

                               <?php
                                // Total Videos
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM lecturers");
                                $stmt->execute();
                                $lecturers = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card" style="background-color: red;">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total Lecturers</span>
                                    <h4><?php echo $lecturers->total; ?></h4>
                                    <a href="add-video"><span class="report-count">View lecturers</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-user"></i></div>
                                </div>
                              </div>

                               <?php
                                // Total Videos
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM students");
                                $stmt->execute();
                                $students = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card" style="background-color: green;">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total Students</span>
                                    <h4><?php echo $students->total; ?></h4>
                                    <a href="add-video"><span class="report-count">View students</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-people"></i></div>
                                </div>
                              </div>

                               <?php
                                // Total Videos
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM school");
                                $stmt->execute();
                                $school = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card" style="background-color: orange;">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total School</span>
                                    <h4><?php echo $school->total; ?></h4>
                                    <a href="add-video"><span class="report-count">View school</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-graduation"></i></div>
                                </div>
                              </div>
                              
                               <?php
                                // Total Videos
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM departments");
                                $stmt->execute();
                                $departments = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card" style="background-color: blue;">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total Departments</span>
                                    <h4><?php echo $departments->total; ?></h4>
                                    <a href="add-video"><span class="report-count">View departments</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-organization"></i></div>
                                </div>
                              </div>

                              <?php
                                // Total Videos
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM videos");
                                $stmt->execute();
                                $videos = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card" style="background-color: aquamarine;">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total Videos</span>
                                    <h4><?php echo $videos->total; ?></h4>
                                    <a href="add-video"><span class="report-count">View Videos</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-camrecorder"></i></div>
                                </div>
                              </div>

                              <?php
                                // Total Materials
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM materials");
                                $stmt->execute();
                                $materials = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card color-2">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total Materials</span>
                                    <h4><?php echo $materials->total; ?></h4>
                                    <a href="add-materials"><span class="report-count">View Materials</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-doc"></i></div>
                                </div>
                              </div>

                               <?php
                                // Total Videos
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM notice");
                                $stmt->execute();
                                $notice = $stmt->fetch(PDO::FETCH_OBJ);
                              ?>
                              <div class="col-md-4 report-inner-cards-wrapper">
                                <div class="report-inner-card" style="background-color: #a0a08feb;">
                                  <div class="inner-card-text text-white">
                                    <span class="report-title">Total Notice</span>
                                    <h4><?php echo $notice->total; ?></h4>
                                    <a href="add-video"><span class="report-count">View notice</span></a>
                                  </div>
                                  <div class="inner-card-icon"><i class="icon-speech"></i></div>
                                </div>
                              </div>

                            <?php endif; ?>


                            <!-- Instructor -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "instructor") : ?>

                                <?php
                                  $lecturer_id = $_SESSION['sturecmsaid']; 

                                  $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
                                  $stmt->execute([$lecturer_id]);
                                  $lecturer = $stmt->fetch();
                                  $actual_lecturer_id = $lecturer['id'];
                                  // Total Courses
                                  $stmt = $dbh->prepare("SELECT * FROM courses WHERE instructor_id = :instructor_id");
                                  $stmt->bindParam(':instructor_id', $actual_lecturer_id, PDO::PARAM_INT);
                                  $stmt->execute();
                                  $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
                                ?>

                                <div class="col-md-6 report-inner-cards-wrapper">
                                    <div class="report-inner-card color-1">
                                        <div class="inner-card-text text-white">
                                            <span class="report-title">Total Courses</span>
                                            <h4><?php echo count($courses); ?></h4>
                                            <a href="manage-class"><span class="report-count"> View Courses</span></a>
                                        </div>
                                        <div class="inner-card-icon"><i class="icon-rocket"></i></div>
                                    </div>
                                </div>

                                <?php
                                // Total Videos
                                $lecturer_id = $_SESSION['sturecmsaid']; 

                                $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
                                $stmt->execute([$lecturer_id]);
                                $lecturer = $stmt->fetch();
                                $actual_lecturer_id = $lecturer['id'];
                                
                                $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM videos 
                                    INNER JOIN courses ON videos.course_id = courses.id 
                                    WHERE videos.lect_id = :instructor_id");
                                $stmt->bindParam(':instructor_id', $actual_lecturer_id, PDO::PARAM_INT);
                                $stmt->execute();
                                $videos = $stmt->fetch(PDO::FETCH_OBJ);
                                ?>

                                <div class="col-md-6 report-inner-cards-wrapper">
                                    <div class="report-inner-card" style="background-color: aquamarine;">
                                        <div class="inner-card-text text-white">
                                            <span class="report-title">Videos</span>
                                            <h4><?php echo $videos->total; ?></h4>
                                            <a href="add-video"><span class="report-count"> View Videos</span></a>
                                        </div>
                                        <div class="inner-card-icon"><i class="icon-camrecorder"></i></div>
                                    </div>
                                </div>

                                <?php
                                  // Total Materials
                                  $lecturer_id = $_SESSION['sturecmsaid']; 

                                  $stmt = $dbh->prepare("SELECT id FROM lecturers WHERE user_id = ?");
                                  $stmt->execute([$lecturer_id]);
                                  $lecturer = $stmt->fetch();
                                  $actual_lecturer_id = $lecturer['id'];

                                  $stmt = $dbh->prepare("SELECT COUNT(*) as total FROM materials WHERE lecturer_id = :lecturer_id");
                                  $stmt->bindParam(':lecturer_id', $actual_lecturer_id, PDO::PARAM_INT);
                                  $stmt->execute();
                                  $materials = $stmt->fetch(PDO::FETCH_OBJ);
                                ?>

                                <div class="col-md-6 report-inner-cards-wrapper">
                                    <div class="report-inner-card color-2">
                                        <div class="inner-card-text text-white">
                                            <span class="report-title">Total Materials</span>
                                            <h4><?php echo $materials->total; ?></h4>
                                            <a href="add-materials"><span class="report-count"> View Materials</span></a>
                                        </div>
                                        <div class="inner-card-icon"><i class="icon-doc"></i></div>
                                    </div>
                                </div>

                            <?php endif; ?>
                          </div>

                          <div class="row">
                              <div class="col-md-12">
                                  <div id="piechart" style="width: 100%; height: 500px;"></div>
                              </div>
                          </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") : ?>

    <?php
    // Get total courses
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM courses");
    $stmt->execute();
    $courses = $stmt->fetch(PDO::FETCH_OBJ);

    // Get total lecturers
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM lecturers");
    $stmt->execute();
    $lecturers = $stmt->fetch(PDO::FETCH_OBJ);

     // Get total studentd
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM students");
    $stmt->execute();
    $students = $stmt->fetch(PDO::FETCH_OBJ);

     // Get total school
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM school");
    $stmt->execute();
    $school = $stmt->fetch(PDO::FETCH_OBJ);

     // Get total departments
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM departments");
    $stmt->execute();
    $department = $stmt->fetch(PDO::FETCH_OBJ);

    // Get total videos
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM videos");
    $stmt->execute();
    $videos = $stmt->fetch(PDO::FETCH_OBJ);

    // Get total materials
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM materials");
    $stmt->execute();
    $materials = $stmt->fetch(PDO::FETCH_OBJ);

    // Get total notice
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM notice");
    $stmt->execute();
    $notice = $stmt->fetch(PDO::FETCH_OBJ);
    ?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Entity', 'Count'],
                ['Courses', <?php echo $courses->total; ?>],
                ['Lecturers', <?php echo $lecturers->total; ?>],
                ['Students', <?php echo $students->total; ?>],
                ['School', <?php echo $school->total; ?>],
                ['Department', <?php echo $department->total; ?>],
                ['Videos', <?php echo $videos->total; ?>],
                ['Materials', <?php echo $materials->total; ?>],
                ['Notice', <?php echo $notice->total; ?>],

            ]);

            var options = {
                title: 'System Overview',
                colors: ['#4043dd', '#ff0000', '#008000', '#ffa500', '#0000ff', '#7fffd4', '#f48324', '#a7a798']
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>

<?php endif; ?>

                                
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === "instructor") : ?>
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
          var data = google.visualization.arrayToDataTable([
              ['Entity', 'Count'],
              ['Courses', <?php echo count($courses); ?>],
              ['Videos', <?php echo $videos->total; ?>],
              ['Materials', <?php echo $materials->total; ?>]
          ]);

          var options = {
              title: 'Instructor Overview',
              colors: ['#4043dd', '#7fffd4', '#f48324']
          };

          var chart = new google.visualization.PieChart(document.getElementById('piechart'));
          chart.draw(data, options);
      }
  </script>
<?php endif; ?>
