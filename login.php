<?php
  session_start();
  include('includes/dbconnection.php');

  if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Step 1: Fetch user with role
    $sql = "SELECT ID, password, role FROM users WHERE email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result && password_verify($password, $result->password)) {
      $_SESSION['sturecmsaid'] = $result->ID;
      $_SESSION['login'] = $email;
      $_SESSION['role'] = $result->role;

      // Remember Me
      if (!empty($_POST["remember"])) {
          setcookie("user_login", $_POST["email"], time() + (10 * 365 * 24 * 60 * 60));
          setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
      } else {
        if (isset($_COOKIE["user_login"])) {
            setcookie("user_login", "");
        }
        if (isset($_COOKIE["userpassword"])) {
            setcookie("userpassword", "");
        }
      }

      // Step 2: Check if user profile is complete
      $user_id = $result->ID;
      $role = $result->role;
      $incomplete = false;

      if ($role === 'student') {
        $stmt = $dbh->prepare("SELECT * FROM students WHERE user_id = :id AND (matric_number IS NULL OR matric_number = '')");
      } elseif ($role === 'instructor') {
        $stmt = $dbh->prepare("SELECT * FROM lecturers WHERE user_id = :id AND (staff_id IS NULL OR staff_id = '')");
      } 

      if (isset($stmt)) {
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $incomplete = true;
        }
      }

      // Step 3: Redirect accordingly
      if ($incomplete) {
          // echo "<script type='text/javascript'> document.location ='profile'; </script>";
          header("Location: profile");
          exit();
      } else {
        // echo "<script type='text/javascript'> document.location ='dashboard'; </script>";
        header("Location: dashboard");
        exit();
      }
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login Page</title>
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .content-wrapper {
      background-image: url('assets/images/background.jpg');
      background-size: cover;
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
                <!-- <img src="assets/images/logo.png" alt="logo"> -->
              </div>
              <h4>Hello! Let’s get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3" id="login" method="post" name="login">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" placeholder="Enter your email" required name="email" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" placeholder="Enter your password" required name="password" value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>">
                </div>
                <div class="mt-3">
                  <button class="btn btn-success btn-block loginbtn" name="login" type="submit">Login</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="text-muted" style="margin-left: 23px;">
                      <input type="checkbox" class="form-check-input" name="remember" <?php if(isset($_COOKIE["user_login"])) { echo 'checked'; } ?> required> Keep me signed in
                    </label>
                  </div>
                  <a href="forgot-password" class="auth-link text-black">Forgot password?</a>
                </div>
                <div class="text-center font-weight-light">
                    Don't have an account? <a href="registerUser" class="text-primary">Signup</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
