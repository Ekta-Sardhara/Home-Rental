<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* DATABASE */
$conn = new mysqli("localhost","root","","home_rental_system");
$msg = "";

/* RESET PASSWORD */
if(isset($_POST['reset'])){

    $email = $conn->real_escape_string($_POST['email']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if($check && $check->num_rows > 0){

        if($new_pass === $confirm_pass){

            // Hash the password before saving
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

            $conn->query("UPDATE users SET password='$hashed_pass' WHERE email='$email'");

            echo "<script>
                    alert('Password changed successfully!');
                    window.location='login.php';
                  </script>";
            exit();

        } else {
            $msg = "❌ Passwords do not match!";
        }

    } else {
        $msg = "❌ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Home Rental System - Forgot Password</title>
<style>
body{
    background:#0f172a;
    color:#fff;
    font-family:Segoe UI;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.box{
    background:#020617;
    padding:25px;
    border-radius:10px;
    width:350px;
    border:1px solid #374151;
}
h2{
text-align:center;
margin-bottom:10px;
}
input{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:none;
    border-radius:5px;
}
button{
    width:100%;
    padding:10px;
    background:#3b82f6;
    border:none;
    color:#fff;
    border-radius:5px;
    cursor:pointer;
}
.msg{
    margin-top:10px;
    text-align:center;
    color:#f87171;
}
</style>
</head>

<body>
<div class="box">
<h2>🏠 Home Rental System</h2>
<p style="text-align:center;">Forgot Password</p>

<form method="POST">
<input type="email" name="email" placeholder="Enter Email" required>
<input type="password" name="new_password" placeholder="New Password" required>
<input type="password" name="confirm_password" placeholder="Confirm Password" required>
<button type="submit" name="reset">Change Password</button>
</form>

<p class="msg"><?php echo $msg; ?></p>
</div>
</body>
</html>