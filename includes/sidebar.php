<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="dashboard">
      <i class="icon-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>

      </a>
    </li>
    
    <!-- Admin -->
    <?php 
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        echo '
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-layers menu-icon"></i>
              <span class="menu-title">Courses</span>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="add-class">Add Course</a></li>
                <li class="nav-item"> <a class="nav-link" href="manage-class">Manage Courses</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Departments</span>
            </a>
            <div class="collapse" id="ui-basic1">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="add-dept">Add New Dept</a></li>
                <li class="nav-item"> <a class="nav-link" href="manage-dept">Manage Dept</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="manage-lecturers">
            <i class="icon-user menu-icon"></i>
              <span class="menu-title">Lecturers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="manage-students">
            <i class="icon-people menu-icon"></i>
              <span class="menu-title">Students</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="icon-doc menu-icon"></i>
              <span class="menu-title">Notice</span>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="add-notice"> Add Notice </a></li>
                <li class="nav-item"> <a class="nav-link" href="manage-notice"> Manage Notice </a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="search">
              <i class="icon-magnifier menu-icon"></i>
              <span class="menu-title">Search</span>
            </a>
          </li>
        ';
      };    
    ?>

    <?php 
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'instructor') {
        echo '
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-layers menu-icon"></i>
              <span class="menu-title">Courses</span>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="add-class">Add Course</a></li>
                <li class="nav-item"> <a class="nav-link" href="manage-class">Manage Courses</a></li>
              </ul>
            </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="add-video">
            <i class="icon-camrecorder menu-icon"></i>
            <span class="menu-title">Add Video Course</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add-materials">
              <i class="icon-doc menu-icon"></i>
              <span class="menu-title">Add Course Material</span>
            </a>
          </li>
        ';
      };    
    ?>

    <?php 
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
        echo '
          // <li class="nav-item">
          //   <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          //     <i class="icon-layers menu-icon"></i>
          //     <span class="menu-title">Courses</span>
          //   </a>
          //   <div class="collapse" id="ui-basic">
          //     <ul class="nav flex-column sub-menu">
          //       <li class="nav-item"> <a class="nav-link" href="add-class">Add Course</a></li>
          //       <li class="nav-item"> <a class="nav-link" href="manage-class">Manage Courses</a></li>
          //       <li class="nav-item"> <a class="nav-link" href="manage-class">Assign Course</a></li>
          //     </ul>
          //   </div>
          // </li>
          // <li class="nav-item">
          //   <a class="nav-link" href="manage-lecturers">
          //   <i class="icon-user menu-icon"></i>
          //     <span class="menu-title">Lecturers</span>
      
          //   </a>
          // </li>
          // <li class="nav-item">
          //   <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
          //     <i class="icon-people menu-icon"></i>
          //     <span class="menu-title">Students</span>
          //   </a>
          //   <div class="collapse" id="ui-basic1">
          //     <ul class="nav flex-column sub-menu">
          //       <li class="nav-item"> <a class="nav-link" href="add-students">Add Students</a></li>
          //       <li class="nav-item"> <a class="nav-link" href="manage-students">Manage Students</a></li>
          //     </ul>
          //   </div>
          // </li>
          // <li class="nav-item">
          //   <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
          //     <i class="icon-doc menu-icon"></i>
          //     <span class="menu-title">Notice</span>
          //   </a>
          //   <div class="collapse" id="auth">
          //     <ul class="nav flex-column sub-menu">
          //       <li class="nav-item"> <a class="nav-link" href="add-notice"> Add Notice </a></li>
          //       <li class="nav-item"> <a class="nav-link" href="manage-notice"> Manage Notice </a></li>
          //     </ul>
          //   </div>
          // </li>
          // <li class="nav-item">
          //   <a class="nav-link" data-toggle="collapse" href="#auth1" aria-expanded="false" aria-controls="auth">
          //     <i class="icon-doc menu-icon"></i>
          //     <span class="menu-title">Public Notice</span>
          //   </a>
          //   <div class="collapse" id="auth1">
          //     <ul class="nav flex-column sub-menu">
          //       <li class="nav-item"> <a class="nav-link" href="add-public-notice"> Add Public Notice </a></li>
          //       <li class="nav-item"> <a class="nav-link" href="manage-public-notice"> Manage Public Notice </a></li>
          //     </ul>
          //   </div>
          //   <li class="nav-item">
          //   <a class="nav-link" href="between-dates-reports">
          //   <i class="icon-flag menu-icon"></i>
          //   <span class="menu-title">Reports</span>
          //   </a>
          // </li>
          // <li class="nav-item">
          //   <a class="nav-link" href="search">
          //     <i class="icon-magnifier menu-icon"></i>
          //     <span class="menu-title">Search</span>
          //   </a>
          // </li>
        ';
      };    
    ?>

  </ul>
</nav>