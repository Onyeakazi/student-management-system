<?php 
    include('includes/dbconnection.php');
    
    $token = $_GET['token'] ?? '';

    $stmt = $dbh->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        die("Invalid or expired token.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = $_POST['password'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password and clear reset token/expiry
        $update = $dbh->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
        $update->execute([$hashedPassword, $user->id]);

        echo "Password successfully reset.";
        header("Location: login");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forgot Password</title>
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
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <!-- <img src="images/logo.svg"> -->
                </div>
                <h4>RECOVER PASSWORD</h4>
                <h6 class="font-weight-light">Enter New Password!</h6>
                <form class="pt-3" id="login" method="post" name="login">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Email Address" required="true" name="password">
                  </div>
                  
                  <div class="mt-3">
                    <button class="btn btn-success btn-block loginbtn" name="submit" type="submit">Reset</button>
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
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>