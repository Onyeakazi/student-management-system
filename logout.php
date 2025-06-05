<?php
session_start();
session_unset();
session_destroy();

// Clear cookies
setcookie("user_login", "", time() - 3600);
setcookie("userpassword", "", time() - 3600);

header('Location: login');
exit;
?>
