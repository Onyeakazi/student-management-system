<?php
session_start();
include('includes/dbconnection.php');

  if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);


    // Check if email already exists
    $check = $dbh->prepare("SELECT ID FROM users WHERE email = :email");
    $check->bindParam(':email', $email, PDO::PARAM_STR);
    $check->execute();

    if ($check->rowCount() > 0) {
        echo "<script>alert('Email already exists!');</script>";
    } else {
      // Insert into users table
      $sql = "INSERT INTO users (fullname, email, password, phone, role, gender, address) 
              VALUES (:fullname, :email, :password, :phone, :role, :gender, :address)";
      $query = $dbh->prepare($sql);
      $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->bindParam(':password', $password, PDO::PARAM_STR);
      $query->bindParam(':phone', $phone, PDO::PARAM_STR);
      $query->bindParam(':role', $role, PDO::PARAM_STR);
      $query->bindParam(':gender', $gender, PDO::PARAM_STR);
      $query->bindParam(':address', $address, PDO::PARAM_STR);

      $result = $query->execute();

      if ($result) {
        // Get the inserted user ID
        $user_id = $dbh->lastInsertId();

        // Insert into role-specific table with only user_id
        if ($role === 'student') {
            $stmt = $dbh->prepare("INSERT INTO students (user_id) VALUES (:user_id)");
        } elseif ($role === 'instructor') {
            $stmt = $dbh->prepare("INSERT INTO lecturers (user_id) VALUES (:user_id)");
        } elseif ($role === 'admin') {
            $stmt = $dbh->prepare("INSERT INTO admins (user_id) VALUES (:user_id)");
        }

        if (isset($stmt)) {
          $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
          $stmt->execute();
        }

        echo "<script>alert('Registration successful! Please login.'); document.location ='login';</script>";
      } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
      }
    }
  }
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Registration Page</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
      .content-wrapper {
        background-image: url('assets/images/background.jpg');
        background-size: cover;
        background-position: center;
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-center p-5">
                <div class="brand-logo">
                  <!-- <img src="assets/images/logo.png" alt="Logo"> -->
                </div>
                <h4>Welcome! Create your account</h4>
                <h6 class="font-weight-light">Sign up to get started.</h6>

                <form class="pt-3" method="post" name="register">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="fullname" placeholder="Enter your Fullname" required>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Enter your email" required>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Create a password" required>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="phone" placeholder="Enter your phone" required>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="address" placeholder="Enter your Address" required>
                  </div>
                  <div class="form-group">
                    <select name="gender" class="form-control form-control-lg" required>
                      <option selected>Gender</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                    </select>
                  </div>
                 <div class="form-group">
                    <select name="role" class="form-control form-control-lg" required>
                      <option selected>Select Role</option>
                      <option value="student">Student</option>
                      <option value="instructor">Lecturer</option>
                    </select>
                  </div>

                  <div class="mt-3">
                    <button class="btn btn-success btn-block" type="submit" name="register">Register</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light">
                    Already have an account? <a href="login" class="text-primary">Login</a>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>
