<!DOCTYPE html>
<html lang="en">
  <head>
   
    <title>Student Management System</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="assets/vendors/chartist/chartist.min.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
        }
        </script>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
    <script type="text/javascript">
      function checkpass(){
        if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value){
          alert('New Password and Confirm Password field does not match');
          document.changepassword.confirmpassword.focus();
          return false;
        }
        return true;
      }   

    </script>
  </head>
  <body>
    <div id="page"></div>
    <!-- <div id="loading"></div> -->
    <div class="container-scroller">
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex align-items-center">
        <a class="navbar-brand brand-logo ml-auto text-right" href="dashboard">
          <img src="assets/images/lo.png" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="dashboard"><img src="assets/images/logo.png" alt="logo" /></a>
      </div>
        <?php
          $aid= $_SESSION['sturecmsaid'];
          $sql="SELECT * from users where ID=:aid";

          $query = $dbh -> prepare($sql);
          $query->bindParam(':aid',$aid,PDO::PARAM_STR);
          $query->execute();
          $results=$query->fetchAll(PDO::FETCH_OBJ);

          $cnt=1;
          if($query->rowCount() > 0){
            foreach($results as $row){              
        ?>
      <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1">
        <h5 class="mb-0 font-weight-medium d-none d-lg-flex mx-auto"><?php  echo htmlentities($row->fullname);?> Welcome to your Dashboard</h5>
        <div id="google_translate_element" class="ml-auto"></div>

        <ul class="navbar-nav navbar-nav-right ml-auto">
          <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle ml-2" src="assets/images/faces/face8.jpg" alt="Profile image"> <span class="font-weight-normal"> <?php  echo htmlentities($row->fullname);?> </span></a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header d-flex">
                <img class="img-md rounded-circle" src="assets/images/faces/face8.jpg" width="60px" alt="Profile image">
                <div><p class="mb-1 mt-3"><?php  echo htmlentities($row->fullname);?></p>
                <p class="font-weight-light text-muted mb-0"><?php  echo htmlentities($row->email);?></p> </div>
                
              </div><?php $cnt=$cnt+1;}} ?>
              <a class="dropdown-item" href="profile"><i class="dropdown-item-icon icon-user text-primary"></i> My Profile</a>
              <a class="dropdown-item" href="change-password"><i class="dropdown-item-icon icon-energy text-primary"></i> Change Password</a>
              <a class="dropdown-item" href="logout"><i class="dropdown-item-icon icon-power text-primary"></i>Sign Out</a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>