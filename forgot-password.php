<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('includes/dbconnection.php');
include('forget_password_email.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // 1. Check if email exists
    $stmt = $dbh->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user) {
        // 2. Generate token
        $token = bin2hex(random_bytes(16));
        $token_expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // 3. Store in DB
        $update = $dbh->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?");
        $update->execute([$token, $token_expiry, $user->id]);

        // 4. Send email
        $reset_link = "http://localhost/eduauth/reset_password?token=$token";

        if (sendResetEmail($email, $reset_link)) {
            $message = "<div style='color: green;'>Check your email for a password reset link.</div>";
        } else {
            $message = "<div style='color: red;'>Something went wrong. Email not sent.</div>";
        }
    } else {
        // Avoid revealing user existence
        $message = "<div style='color: green;'>If this email exists, a password reset link has been sent.</div>";
    }
}
?>


  <script type="text/javascript">
    function valid()
    {
    if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
    {
    alert("New Password and Confirm Password Field do not match  !!");
    document.chngpwd.confirmpassword.focus();
    return false;
    }
    return true;
    }
  </script>
  
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
                <h6 class="font-weight-light">Enter your email address reset password!</h6>
                <?php if (!empty($message)) echo $message; ?>
                <form class="pt-3" id="login" method="post" name="login">
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" placeholder="Email Address" required="true" name="email">
                  </div>
                  
                  <div class="mt-3">
                    <button class="btn btn-success btn-block loginbtn" name="submit" type="submit">Reset</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    
                    <a href="login" class="auth-link text-black">signin</a>
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