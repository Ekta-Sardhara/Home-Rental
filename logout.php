<?php
session_start();

// 🔹 DESTROY SESSION
session_unset();
session_destroy();

// 🔹 DELETE COOKIES (set expiry time in past)
if (isset($_COOKIE['user_email'])) {
    setcookie("user_email", "", time() - 3600, "/");
}

if (isset($_COOKIE['user_role'])) {
    setcookie("user_role", "", time() - 3600, "/");
}

// 🔹 REDIRECT TO LOGIN PAGE
header("Location: login.php");
exit();
?>
